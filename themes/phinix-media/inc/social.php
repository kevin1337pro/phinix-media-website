<?php
/** Social profile links and article sharing without embeds or third-party scripts. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function phinix_social_channels() {
    return array(
        'linkedin'  => array( 'LinkedIn', '' ),
        'x'         => array( 'X', 'https://x.com/realgoat31337' ),
        'instagram' => array( 'Instagram', 'https://www.instagram.com/phinix.media/' ),
        'facebook'  => array( 'Facebook', '' ),
        'whatsapp'  => array( 'WhatsApp', 'https://wa.me/4917655376651' ),
    );
}

add_action( 'admin_init', function () {
    add_settings_section( 'phinix_social', 'Social-Media-Profile', function () {
        echo '<p>Diese Links erscheinen im Footer. Ein leeres Feld blendet den jeweiligen Kanal aus.</p>';
    }, 'general' );
    foreach ( phinix_social_channels() as $service => list( $label, $default ) ) {
        $option = 'phinix_social_' . $service;
        register_setting( 'general', $option, array(
            'type' => 'string', 'default' => $default,
            'sanitize_callback' => function ( $value ) { return esc_url_raw( (string) $value, array( 'https' ) ); },
        ) );
        add_settings_field( $option, $label, function () use ( $option, $default ) {
            printf( '<input class="regular-text" type="url" name="%1$s" id="%1$s" value="%2$s">', esc_attr( $option ), esc_attr( get_option( $option, $default ) ) );
        }, 'general', 'phinix_social', array( 'label_for' => $option ) );
    }
} );

function phinix_social_link( $service, $url, $label, $accessible_label ) {
    return '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( $accessible_label ) . '">' .
        block_core_social_link_get_icon( $service ) . '<span>' . esc_html( $label ) . '</span></a>';
}

function phinix_social_profiles() {
    $links = '';
    foreach ( phinix_social_channels() as $service => list( $label, $default ) ) {
        $url = get_option( 'phinix_social_' . $service, $default );
        if ( $url ) {
            $action = 'whatsapp' === $service ? 'Phinix Media auf WhatsApp kontaktieren' : 'Phinix Media auf ' . $label;
            if ( 'x' === $service && $url === $default ) {
                $label = 'X · Phinix Screens';
                $action = 'Phinix Screens auf X';
            }
            $links .= phinix_social_link( $service, $url, $label, $action );
        }
    }
    return $links ? '<nav class="phinix-social" aria-label="Social Media und Kontakt">' . $links . '</nav>' : '';
}

function phinix_share_article() {
    if ( ! is_single() && ! get_post_meta( get_queried_object_id(), '_phinix_article', true ) ) { return ''; }
    $permalink = get_permalink( get_queried_object_id() );
    if ( ! wp_parse_url( $permalink, PHP_URL_HOST ) ) {
        $permalink = rtrim( get_option( 'home' ), '/' ) . '/' . ltrim( $permalink, '/' );
    }
    $url = rawurlencode( $permalink );
    $links = array(
        'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
        'x'        => 'https://x.com/intent/post?url=' . $url,
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
    );
    $out = '<nav class="phinix-social article-share" aria-label="Beitrag teilen"><span>Beitrag teilen</span>';
    foreach ( $links as $service => $target ) {
        $name = phinix_social_channels()[ $service ][0];
        $out .= phinix_social_link( $service, $target, $name, 'Beitrag auf ' . $name . ' teilen' );
    }
    return $out . '</nav>';
}

add_action( 'init', function () {
    register_block_type( 'phinix/social-profiles', array(
        'api_version' => 3,
        'supports' => array( 'inserter' => false ),
        'render_callback' => 'phinix_social_profiles',
    ) );
    register_block_type( 'phinix/article-share', array(
        'api_version' => 3,
        'supports' => array( 'inserter' => false ),
        'render_callback' => 'phinix_share_article',
    ) );
} );
