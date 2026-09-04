<?php
/** Search metadata. A detected SEO plugin owns the complete SEO output instead. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function phinix_seo_external_owner() {
    $external = defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' );
    if ( class_exists( 'Jetpack' ) && is_callable( array( 'Jetpack', 'is_module_active' ) ) ) {
        $external = $external || Jetpack::is_module_active( 'seo-tools' );
    }
    return (bool) apply_filters( 'phinix_seo_external_owner', $external );
}

function phinix_business_data() {
    // Public business contact information: phinix.media/impressum/, checked 2026-09-04.
    // No opening hours, walk-in availability, extra branches or review scores asserted.
    return array(
        'name' => 'Phinix Media', 'city' => 'Gelsenkirchen',
        'street' => 'Buerelterstraße 27', 'postal_code' => '45896', 'country' => 'DE',
        'phone' => '+4917655376651', 'phone_display' => '0176 55376651',
        'email' => 'kontakt@phinix.media', 'areas' => array( 'Gelsenkirchen', 'Gladbeck' ),
    );
}

function phinix_seo_page_data() {
    if ( is_front_page() && ! is_paged() ) {
        return array(
            'title' => 'Werbeagentur Gelsenkirchen & Gladbeck | Phinix Media',
            'description' => 'Webdesign, Branding, Visitenkarten, Marketing und lokales SEO: Phinix Media aus Gelsenkirchen für Unternehmen in Gladbeck und Umgebung. Projekt besprechen.',
            'url' => home_url( '/' ), 'name' => 'Phinix Media – Design, Web und Marketing',
        );
    }
    if ( ! is_singular() || post_password_required() ) { return null; }
    $id = get_queried_object_id();
    $title = get_post_meta( $id, '_phinix_seo_title', true );
    $description = get_post_meta( $id, '_phinix_seo_description', true );
    if ( ! $description ) { $description = wp_trim_words( wp_strip_all_tags( get_the_excerpt( $id ) ), 28, '…' ); }
    return array(
        'title' => $title ? $title : get_the_title( $id ) . ' | Phinix Media',
        'description' => $description, 'url' => get_permalink( $id ),
        'name' => get_the_title( $id ), 'id' => $id,
    );
}

add_filter( 'pre_get_document_title', function ( $title ) {
    if ( phinix_seo_external_owner() ) { return $title; }
    $data = phinix_seo_page_data();
    return $data ? $data['title'] : $title;
}, 20 );

add_action( 'wp', function () {
    if ( ! phinix_seo_external_owner() && phinix_seo_page_data() ) { remove_action( 'wp_head', 'rel_canonical' ); }
} );

add_action( 'wp_head', function () {
    if ( phinix_seo_external_owner() ) { return; }
    $data = phinix_seo_page_data();
    if ( ! $data ) { return; }
    echo '<link rel="canonical" href="' . esc_url( $data['url'] ) . '">' . "\n";
    if ( $data['description'] ) {
        echo '<meta name="description" content="' . esc_attr( $data['description'] ) . '">' . "\n";
    }
    // Jetpack normally owns social metadata on WordPress.com; do not emit a second set.
    if ( ! class_exists( 'Jetpack' ) ) {
        $social = array( 'og:type' => 'website', 'og:locale' => 'de_DE', 'og:site_name' => 'Phinix Media', 'og:title' => $data['title'], 'og:description' => $data['description'], 'og:url' => $data['url'] );
        foreach ( $social as $property => $value ) {
            echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $value ) . '">' . "\n";
        }
        // No generated or generic social image is added.
        echo '<meta name="twitter:card" content="summary">' . "\n";
    }
    $business = phinix_business_data();
    $home = home_url( '/' );
    $org = array(
        '@type' => 'LocalBusiness', '@id' => $home . '#organization',
        'name' => $business['name'], 'url' => $home,
        'telephone' => $business['phone'], 'email' => $business['email'],
        'address' => array( '@type' => 'PostalAddress', 'streetAddress' => $business['street'], 'postalCode' => $business['postal_code'], 'addressLocality' => $business['city'], 'addressCountry' => $business['country'] ),
        'areaServed' => array_map( function ( $name ) { return array( '@type' => 'City', 'name' => $name ); }, $business['areas'] ),
    );
    $webpage = array( '@type' => 'WebPage', '@id' => $data['url'] . '#webpage', 'url' => $data['url'], 'name' => $data['name'], 'description' => $data['description'], 'inLanguage' => 'de-DE', 'isPartOf' => array( '@id' => $home . '#website' ) );
    $graph = array( $org, array( '@type' => 'WebSite', '@id' => $home . '#website', 'url' => $home, 'name' => 'Phinix Media', 'inLanguage' => 'de-DE', 'publisher' => array( '@id' => $home . '#organization' ) ) );
    if ( ! is_front_page() ) {
        $webpage['breadcrumb'] = array( '@id' => $data['url'] . '#breadcrumb' );
        $graph[] = array( '@type' => 'BreadcrumbList', '@id' => $data['url'] . '#breadcrumb', 'itemListElement' => array(
            array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => $home ),
            array( '@type' => 'ListItem', 'position' => 2, 'name' => $data['name'], 'item' => $data['url'] ),
        ) );
        $service = get_post_meta( get_queried_object_id(), '_phinix_service', true );
        if ( $service ) {
            $webpage['mainEntity'] = array( '@id' => $data['url'] . '#service' );
            $graph[] = array( '@type' => 'Service', '@id' => $data['url'] . '#service', 'name' => $service, 'url' => $data['url'], 'provider' => array( '@id' => $home . '#organization' ), 'areaServed' => $org['areaServed'] );
        }
    }
    $graph[] = $webpage;
    echo '<script type="application/ld+json" id="phinix-structured-data">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . '</script>' . "\n";
}, 5 );

add_filter( 'wp_robots', function ( $robots ) {
    if ( ! phinix_seo_external_owner() && empty( $robots['noindex'] ) ) { $robots['max-image-preview'] = 'large'; }
    return $robots;
} );

add_action( 'add_meta_boxes', function () {
    if ( phinix_seo_external_owner() ) { return; }
    add_meta_box( 'phinix-seo', 'Phinix – Suchergebnis', function ( $post ) {
        wp_nonce_field( 'phinix_save_seo', 'phinix_seo_nonce' );
        echo '<p><label for="phinix-seo-title">SEO-Titel</label><input class="widefat" id="phinix-seo-title" name="phinix_seo_title" value="' . esc_attr( get_post_meta( $post->ID, '_phinix_seo_title', true ) ) . '"></p>';
        echo '<p><label for="phinix-seo-description">Beschreibung</label><textarea class="widefat" id="phinix-seo-description" name="phinix_seo_description" rows="3">' . esc_textarea( get_post_meta( $post->ID, '_phinix_seo_description', true ) ) . '</textarea></p>';
        echo '<p>Google kann Titel und Beschreibung für eine Suchanfrage anders darstellen. Bei einem SEO-Plugin werden dessen Einstellungen verwendet.</p>';
    }, array( 'page', 'post' ), 'normal' );
} );

add_action( 'save_post', function ( $post_id ) {
    if ( ! isset( $_POST['phinix_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['phinix_seo_nonce'] ) ), 'phinix_save_seo' ) || ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) { return; }
    foreach ( array( 'title', 'description' ) as $field ) {
        if ( isset( $_POST[ 'phinix_seo_' . $field ] ) ) { update_post_meta( $post_id, '_phinix_seo_' . $field, sanitize_text_field( wp_unslash( $_POST[ 'phinix_seo_' . $field ] ) ) ); }
    }
} );
