<?php
/**
 * Title: Phinix – Header
 * Slug: phinix/header
 * Categories: phinix
 * Inserter: no
 */
if ( ! defined( "ABSPATH" ) ) { exit; }
?>
<!-- wp:group {"className":"site-header","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"wrap"}} --><div class="wp-block-group site-header"><!-- wp:paragraph {"className":"wordmark"} --><p class="wordmark"><a href="<?php echo esc_url( home_url( "/" ) ); ?>" aria-label="Phinix Media – Startseite"><img class="wordmark-symbol" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/phinix-symbol-white.svg' ) ); ?>" alt="" aria-hidden="true" width="735" height="595">PHINIX.MEDIA</a></p><!-- /wp:paragraph -->
<!-- wp:navigation {"overlayMenu":"mobile","className":"header-nav","layout":{"type":"flex","justifyContent":"right"}} --><!-- wp:navigation-link {"label": "Leistungen", "url": "/#leistungen", "kind": "custom"} /--><!-- wp:navigation-link {"label": "Arbeiten", "url": "/#arbeiten", "kind": "custom"} /--><!-- wp:navigation-link {"label": "Über uns", "url": "/#team", "kind": "custom"} /--><!-- /wp:navigation --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/#kontakt">Projekt besprechen ↗</a></div><!-- /wp:button -->
</div><!-- /wp:buttons -->
</div><!-- /wp:group -->
