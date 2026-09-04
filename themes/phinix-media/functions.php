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
    wp_enqueue_style( 'phinix-media', get_theme_file_uri( 'assets/theme.css' ), array(), $version );
    wp_enqueue_script( 'phinix-motion', get_theme_file_uri( 'assets/motion.js' ), array(), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
} );

add_action( 'init', function () {
    register_block_pattern_category( 'phinix', array( 'label' => 'Phinix Media' ) );
} );

require_once get_theme_file_path( 'inc/seo.php' );
require_once get_theme_file_path( 'inc/service-pages.php' );

add_shortcode( 'phinix_breadcrumbs', function () {
    if ( ! is_singular() || is_front_page() ) { return ''; }
    return '<nav class="phinix-breadcrumbs" aria-label="Brotkrümelnavigation"><a href="' . esc_url( home_url( '/' ) ) . '">Startseite</a><span aria-hidden="true"> / </span><span aria-current="page">' . esc_html( get_the_title( get_queried_object_id() ) ) . '</span></nav>';
} );
