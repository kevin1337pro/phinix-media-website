<?php
/** Explicit, non-destructive setup of real WordPress pages (not virtual SEO routes). */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function phinix_service_pages() {
    static $pages;
    if ( null === $pages ) { $pages = json_decode( file_get_contents( get_theme_file_path( 'content/seo-pages.json' ) ), true ); }
    return is_array( $pages ) ? $pages : array();
}
function phinix_block_paragraph( $text ) { return '<!-- wp:paragraph --><p>' . esc_html( $text ) . '</p><!-- /wp:paragraph -->'; }
function phinix_service_content( $data ) {
    $out = '<!-- wp:paragraph {"className":"service-lead"} --><p class="service-lead">' . esc_html( $data['intro'] ) . '</p><!-- /wp:paragraph -->';
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
function phinix_install_service_pages( $status = 'draft' ) {
    $created = array(); $skipped = array();
    foreach ( phinix_service_pages() as $slug => $data ) {
        if ( get_page_by_path( $slug ) ) { $skipped[] = $slug; continue; }
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
        echo '<div class="wrap"><h1>Phinix: Leistungsseiten einrichten</h1><p>Erstellt fünf echte Seiten als Entwürfe. Vorhandene Seiten werden nicht verändert. Prüfe die Inhalte und veröffentliche die Seiten vor dem Livegang, damit die Links funktionieren.</p><p>Bei einem aktiven SEO-Plugin trägst du Titel, Beschreibungen und Unternehmensdaten dort ein; das Theme vermeidet doppelte SEO-Ausgaben.</p>';
        echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="phinix_setup_pages">';
        wp_nonce_field( 'phinix_setup_pages' ); submit_button( 'Fehlende Leistungsseiten als Entwürfe anlegen' ); echo '</form></div>';
    } );
} );
add_action( 'admin_post_phinix_setup_pages', function () {
    if ( ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'edit_pages' ) ) { wp_die( 'Keine Berechtigung.' ); }
    check_admin_referer( 'phinix_setup_pages' ); phinix_install_service_pages();
    wp_safe_redirect( admin_url( 'edit.php?post_type=page' ) ); exit;
} );
