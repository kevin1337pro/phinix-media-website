<?php
/**
 * Title: Phinix – Header
 * Slug: phinix/header
 * Categories: phinix
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$groups = array(
    'Leistungen' => array(
        'href' => '/#leistungen',
        'links' => array(
            '/webdesign/' => 'Webdesign & WordPress',
            '/branding/' => 'Branding & Logodesign',
            '/print-design/' => 'Print & Design',
            '/marketing-content/' => 'Marketing & Content',
            '/seo-local-geo/' => 'SEO & lokale Suche',
            '/3d-rundgaenge/' => '3D-Rundgänge',
            '/digitale-aussenwerbung-gladbeck-phinix-screens/' => 'Digital Signage',
            '/#website-pruefen' => 'Website prüfen',
        ),
    ),
    'Agentur' => array(
        'href' => '/#team',
        'links' => array(
            '/#team' => 'Kevin & Janine',
            '/#arbeiten' => 'Unsere Arbeiten',
            '/#ablauf' => 'So arbeiten wir',
        ),
    ),
);
?>
<!-- wp:html -->
<div class="site-header phinix-masthead" data-phinix-menu>
    <p class="wordmark"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Phinix Media – Startseite"><img class="wordmark-symbol" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/phinix-symbol-white.svg' ) ); ?>" alt="" width="735" height="595">PHINIX.MEDIA</a></p>
    <button type="button" class="masthead-toggle" aria-controls="phinix-navigation" aria-expanded="false" hidden>
        <span class="masthead-toggle__icon" aria-hidden="true"></span>
        <span class="masthead-toggle__label">Menü</span>
    </button>
    <nav id="phinix-navigation" class="masthead-nav" aria-label="Hauptnavigation">
        <ul class="masthead-nav__items">
            <?php foreach ( $groups as $label => $group ) : ?>
                <li class="masthead-nav__item">
                    <a class="masthead-nav__link" href="<?php echo esc_url( home_url( $group['href'] ) ); ?>"><?php echo esc_html( $label ); ?></a>
                    <details class="masthead-dropdown" name="phinix-navigation-group">
                        <summary aria-label="<?php echo esc_attr( 'Untermenü ' . $label ); ?>"><span aria-hidden="true"></span></summary>
                        <ul class="masthead-dropdown__links">
                            <?php foreach ( $group['links'] as $path => $title ) : ?>
                                <li><a href="<?php echo esc_url( home_url( $path ) ); ?>"><?php echo esc_html( $title ); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </details>
                </li>
            <?php endforeach; ?>
            <li><a class="masthead-nav__link" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">News</a></li>
        </ul>
        <a class="masthead-cta" href="<?php echo esc_url( home_url( '/#kontakt' ) ); ?>">Projekt besprechen <span aria-hidden="true">↗</span></a>
    </nav>
</div>
<!-- /wp:html -->
