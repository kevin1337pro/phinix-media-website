<?php
/** Explicit, non-destructive setup of real WordPress pages (not virtual SEO routes). */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function phinix_service_pages() {
    static $pages;
    if ( null === $pages ) { $pages = json_decode( file_get_contents( get_theme_file_path( 'content/seo-pages.json' ) ), true ); }
    return is_array( $pages ) ? $pages : array();
}
function phinix_block_paragraph( $text ) { return '<!-- wp:paragraph --><p>' . esc_html( $text ) . '</p><!-- /wp:paragraph -->'; }
function phinix_service_lead( $intro ) {
    return '<!-- wp:paragraph {"className":"service-lead"} --><p class="service-lead">' . esc_html( $intro ) . '</p><!-- /wp:paragraph -->';
}
function phinix_service_content( $data ) {
    $out = phinix_service_lead( $data['intro'] );
    $out .= '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="mailto:kontakt@phinix.media">Projekt per E-Mail besprechen ↗</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
    foreach ( $data['sections'] as $section ) {
        $out .= '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html( $section['title'] ) . '</h2><!-- /wp:heading -->';
        if ( ! empty( $section['text'] ) ) { $out .= phinix_block_paragraph( $section['text'] ); }
        if ( ! empty( $section['items'] ) ) {
            $out .= '<!-- wp:list --><ul class="wp-block-list">';
            foreach ( $section['items'] as $item ) { $out .= '<!-- wp:list-item --><li>' . esc_html( $item ) . '</li><!-- /wp:list-item -->'; }
            $out .= '</ul><!-- /wp:list -->';
        }
    }
    $out .= '<!-- wp:heading --><h2 class="wp-block-heading">Fragen vor dem Start</h2><!-- /wp:heading -->';
    foreach ( $data['questions'] as $q ) { $out .= '<!-- wp:details --><details class="wp-block-details"><summary>' . esc_html( $q[0] ) . '</summary>' . phinix_block_paragraph( $q[1] ) . '</details><!-- /wp:details -->'; }
    $out .= '<!-- wp:heading --><h2 class="wp-block-heading">Passende Leistungen</h2><!-- /wp:heading -->';
    $pages = phinix_service_pages();
    foreach ( $data['related'] as $slug ) {
        $out .= '<!-- wp:paragraph --><p><a href="' . esc_url( home_url( '/' . $slug . '/' ) ) . '">' . esc_html( $pages[$slug]['service'] ) . ' ↗</a></p><!-- /wp:paragraph -->';
    }
    return $out;
}

// Resolve the article at render time so older service pages also link to newly published posts.
add_filter( 'render_block_core/post-content', function ( $html, $block, $instance ) {
    $id = get_queried_object_id();
    if ( ! is_page() || (int) ( $instance->context['postId'] ?? 0 ) !== $id || ! get_post_meta( $id, '_phinix_service', true ) ) { return $html; }
    $pages = phinix_service_pages();
    $slug = get_post_field( 'post_name', $id );
    if ( empty( $pages[ $slug ]['related_article'] ) ) { return $html; }
    $article_slug = $pages[ $slug ]['related_article'];
    $article = get_page_by_path( $article_slug, OBJECT, array( 'post', 'page' ) );
    if ( ! $article || 'publish' !== $article->post_status || $article->post_password ) { return $html; }
    $url = get_permalink( $article );
    if ( false !== strpos( $html, esc_url( $url ) . '"' ) || false !== strpos( $html, '/' . $article_slug . '/"' ) ) { return $html; }
    return $html . '<aside aria-label="Weiterlesen"><h2>Aus unserem Blog</h2><p><a href="' . esc_url( $url ) . '">' . esc_html( get_the_title( $article ) ) . ' ↗</a></p></aside>';
}, 10, 3 );

// Older releases stored starter copy in the database. Update only fields/blocks still
// identical to those defaults. Keep editorial changes, existing slugs and publication dates.
function phinix_upgrade_service_defaults( $page, $data, $old ) {
    if ( ! get_post_meta( $page->ID, '_phinix_service', true ) ) { return; }
    $changes = array();
    foreach ( array( 'post_title' => 'title', 'post_excerpt' => 'description' ) as $field => $key ) {
        if ( $page->$field === $old[ $key ] && $old[ $key ] !== $data[ $key ] ) {
            $changes[ $field ] = $data[ $key ];
        }
    }
    $old_lead = phinix_service_lead( $old['intro'] );
    if ( 0 === strpos( $page->post_content, $old_lead ) && $old['intro'] !== $data['intro'] ) {
        $changes['post_content'] = phinix_service_lead( $data['intro'] ) . substr( $page->post_content, strlen( $old_lead ) );
    }
    if ( $changes ) {
        $changes['ID'] = $page->ID;
        wp_update_post( wp_slash( $changes ) );
    }
    foreach ( array( '_phinix_seo_title' => 'seo_title', '_phinix_seo_description' => 'description' ) as $field => $key ) {
        if ( $old[ $key ] !== $data[ $key ] && get_post_meta( $page->ID, $field, true ) === $old[ $key ] ) {
            update_post_meta( $page->ID, $field, $data[ $key ], $old[ $key ] );
        }
    }
}
function phinix_install_service_pages( $status = 'draft' ) {
    $created = array(); $skipped = array();
    $previous = json_decode( file_get_contents( get_theme_file_path( 'content/service-defaults-0.10.json' ) ), true );
    foreach ( phinix_service_pages() as $slug => $data ) {
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            if ( isset( $previous[ $slug ] ) ) { phinix_upgrade_service_defaults( $existing, $data, $previous[ $slug ] ); }
            $skipped[] = $slug;
            continue;
        }
        $id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish' === $status ? 'publish' : 'draft', 'post_name' => $slug, 'post_title' => $data['title'], 'post_excerpt' => $data['description'], 'post_content' => phinix_service_content( $data ) ), true );
        if ( is_wp_error( $id ) ) { $skipped[] = $slug; continue; }
        update_post_meta( $id, '_phinix_seo_title', $data['seo_title'] );
        update_post_meta( $id, '_phinix_seo_description', $data['description'] );
        update_post_meta( $id, '_phinix_service', $data['service'] );
        $created[] = $slug;
    }
    return array( 'created' => $created, 'skipped' => $skipped );
}
add_action( 'admin_menu', function () {
    add_theme_page( 'Phinix einrichten', 'Phinix einrichten', 'edit_theme_options', 'phinix-setup', function () {
        if ( ! current_user_can( 'edit_theme_options' ) ) { return; }
        echo '<div class="wrap"><h1>Phinix: Inhalte einrichten</h1><p>Erstellt fehlende Leistungsseiten, News, Artikel und Rechtstexte als Entwürfe. Auf vorhandenen Leistungsseiten aktualisiert das Theme seine ursprünglichen Titel, Einleitungen und Suchbeschreibungen, wenn sie noch mit der ausgelieferten Fassung übereinstimmen. Redaktionell angepasste Felder bleiben erhalten. Das Einrichten aktualisiert auch verwaltete Rechtstexte. Prüfe die Entwürfe und veröffentliche sie, sobald sie bereit sind.</p><p>Bei einem aktiven SEO-Plugin trägst du Titel, Beschreibungen und Unternehmensdaten dort ein.</p>';
        echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="phinix_setup_pages">';
        wp_nonce_field( 'phinix_setup_pages' ); submit_button( 'Fehlende Seiten und Artikel als Entwürfe anlegen' ); echo '</form></div>';
    } );
} );
add_action( 'admin_post_phinix_setup_pages', function () {
    if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'edit_pages' ) ) { wp_die( 'Keine Berechtigung.' ); }
    check_admin_referer( 'phinix_setup_pages' ); phinix_install_service_pages(); phinix_install_editorial_articles(); phinix_install_legal_pages(); phinix_install_news_page();
    wp_safe_redirect( admin_url( 'edit.php?post_type=page' ) ); exit;
} );
