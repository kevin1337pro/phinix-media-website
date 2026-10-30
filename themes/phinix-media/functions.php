<?php
/** Phinix Media theme setup. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'after_setup_theme', function () {
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/theme.css' );
} );

add_action( 'wp_enqueue_scripts', function () {
    $version = wp_get_theme()->get( 'Version' );
    $style_file = get_theme_file_path( 'assets/theme.css' );
    $style_version = is_readable( $style_file ) ? $version . '.' . filemtime( $style_file ) : $version;
    wp_enqueue_style( 'phinix-media', get_theme_file_uri( 'assets/theme.css' ), array(), $style_version );
    wp_enqueue_script( 'phinix-gsap', get_theme_file_uri( 'assets/gsap.min.js' ), array(), '3.13.0', array( 'strategy' => 'defer', 'in_footer' => true ) );
    wp_enqueue_script( 'phinix-motion', get_theme_file_uri( 'assets/motion.js' ), array( 'phinix-gsap' ), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
} );

add_action( 'init', function () {
    register_block_pattern_category( 'phinix', array( 'label' => 'Phinix Media' ) );
} );

// Frontend accessibility labels stay German even in a minimal Playground
// installation where the WordPress language pack is not available.
add_filter( 'gettext', function ( $translation, $text ) {
    if ( is_admin() ) { return $translation; }
    $labels = array(
        'Skip to content' => 'Zum Inhalt springen',
        'Menu'            => 'Menü',
        'Open menu'       => 'Menü öffnen',
        'Close menu'      => 'Menü schließen',
    );
    return isset( $labels[ $text ] ) ? $labels[ $text ] : $translation;
}, 20, 2 );

require_once get_theme_file_path( 'inc/seo.php' );
require_once get_theme_file_path( 'inc/legal-pages.php' );
require_once get_theme_file_path( 'inc/service-pages.php' );
require_once get_theme_file_path( 'inc/articles.php' );

add_shortcode( 'phinix_breadcrumbs', function () {
    if ( ! is_singular() || is_front_page() ) { return ''; }
    return '<nav class="phinix-breadcrumbs" aria-label="Brotkrümelnavigation"><a href="' . esc_url( home_url( '/' ) ) . '">Startseite</a><span aria-hidden="true"> / </span><span aria-current="page">' . esc_html( get_the_title( get_queried_object_id() ) ) . '</span></nav>';
} );
