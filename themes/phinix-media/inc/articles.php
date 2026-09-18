<?php
/** Non-destructive installation of editorial content shipped with the theme. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function phinix_theme_picture_shortcode( $attributes ) {
    $images = array(
        'phinix-brand-stage.jpg' => array( 1600, 900 ),
    );
    $attributes = shortcode_atts( array( 'name' => '', 'alt' => '', 'class' => '' ), $attributes, 'phinix_theme_picture' );
    $name = sanitize_file_name( $attributes['name'] );
    if ( ! isset( $images[ $name ] ) ) { return ''; }
    return '<figure class="phinix-article-image ' . esc_attr( sanitize_html_class( $attributes['class'] ) ) . '"><img src="' . esc_url( get_theme_file_uri( 'assets/images/people/' . $name ) ) . '" alt="' . esc_attr( $attributes['alt'] ) . '" width="' . (int) $images[ $name ][0] . '" height="' . (int) $images[ $name ][1] . '" loading="eager" fetchpriority="high"></figure>';
}
add_shortcode( 'phinix_theme_picture', 'phinix_theme_picture_shortcode' );

function phinix_article_meta_shortcode() {
    $id = get_queried_object_id();
    if ( ! $id ) { return ''; }
    return '<p class="article-meta">Ratgeber · Aktualisiert am ' . esc_html( get_the_modified_date( 'j. F Y', $id ) ) . '</p>';
}
add_shortcode( 'phinix_article_meta', 'phinix_article_meta_shortcode' );

function phinix_screens_article_content() {
    return '<!-- wp:shortcode -->[phinix_article_meta]<!-- /wp:shortcode -->
<!-- wp:paragraph {"className":"service-lead"} --><p class="service-lead">Digitale Außenwerbung bringt eine Botschaft auf Displays im öffentlichen Raum. PHINIX.SCREENS beschreibt dafür eine Plattform, über die Unternehmen in Gladbeck einen Standort und eine Ausspielung wählen, ihr Motiv hochladen und die Kampagne nach Freigabe ausspielen lassen können.</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[phinix_theme_picture name="phinix-brand-stage.jpg" alt="Inszeniertes Phinix Media Markenmotiv mit Kevin und Janine" class="article-lead-visual"]<!-- /wp:shortcode -->
<!-- wp:heading --><h2 class="wp-block-heading">Was ist digitale Außenwerbung?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Digital Out of Home, kurz DOOH, bezeichnet digitale Werbeflächen außerhalb der eigenen Website und Social-Media-Kanäle. Dazu zählen Stadt-Displays und andere Digital-Signage-Flächen. Motive lassen sich zeitlich planen und ohne einen neuen Plakatdruck wechseln. Das macht lokale Werbung beispielsweise für Angebote, Veranstaltungen, Neueröffnungen oder freie Termine interessant.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">So beschreibt PHINIX.SCREENS den Ablauf</h2><!-- /wp:heading -->
<!-- wp:list {"ordered":true} --><ol class="wp-block-list"><!-- wp:list-item --><li><strong>Standort und Zeitraum wählen:</strong> Auf der Plattform wird eine passende digitale Werbefläche in Gladbeck ausgewählt.</li><!-- /wp:list-item --><!-- wp:list-item --><li><strong>Motiv hochladen:</strong> Je nach Buchung wird ein Bild oder Video für das vorgesehene Display bereitgestellt.</li><!-- /wp:list-item --><!-- wp:list-item --><li><strong>Prüfung und Ausspielung:</strong> Nach Autorisierung und Freigabe läuft das Motiv im gebuchten Zeitraum.</li><!-- /wp:list-item --><!-- wp:list-item --><li><strong>Kampagne auswerten:</strong> Die Plattform beschreibt Analytics und Reporting als Teil des Angebots.</li><!-- /wp:list-item --></ol><!-- /wp:list -->
<!-- wp:paragraph --><p>Die aktuelle Verfügbarkeit, Display-Formate, Freigaberegeln und Preise stehen direkt bei <a href="https://www.phinix-screens.com/">PHINIX.SCREENS – digitale Außenwerbung in Gladbeck</a>. Da sich Standorte und Konditionen ändern können, ist die Plattform die maßgebliche Quelle.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Welche lokalen Kampagnen passen auf einen City-Screen?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Ein Display-Motiv hat nur wenige Sekunden, um verstanden zu werden. Sinnvoll sind eine klare Hauptbotschaft, ein gut sichtbarer Absender und eine eindeutige nächste Handlung. Restaurants und Cafés können auf ein aktuelles Angebot hinweisen. Einzelhandel kann eine Aktion oder neue Kollektion ankündigen. Veranstalter können Termine bekannt machen. Dienstleister können freie Termine oder eine konkrete Leistung in den Mittelpunkt stellen.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Phinix Media gestaltet. PHINIX.SCREENS spielt aus.</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Die Werbefläche allein macht noch keine starke Kampagne. Phinix Media kann Botschaft, Layout und passende Bild- oder Videoformate entwickeln. Dabei stimmen wir das Screen-Motiv auf den vorhandenen Markenauftritt und weitere Kanäle ab. So können <a href="/branding/">Branding und Corporate Design</a>, <a href="/marketing-content/">Marketing und Content</a> sowie digitale Außenwerbung zusammenarbeiten.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Wichtig bleibt die Trennung: Phinix Media plant und gestaltet den vereinbarten Auftritt. Buchbare Standorte, Zeiten und technische Vorgaben werden bei PHINIX.SCREENS geprüft.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Was wir für ein Screen-Motiv brauchen</h2><!-- /wp:heading -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Ziel der Kampagne und gewünschter Zeitraum.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Produkt, Angebot oder Veranstaltung mit den finalen Angaben.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Logo und vorhandene Markenfarben in geeigneter Qualität.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Freigegebenes Bild- oder Videomaterial.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Das aktuelle Display-Format und die technischen Vorgaben der Plattform.</li><!-- /wp:list-item --></ul><!-- /wp:list -->
<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.phinix-screens.com/">PHINIX.SCREENS besuchen ↗</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="mailto:kontakt@phinix.media?subject=Screen-Motiv%20anfragen">Screen-Motiv besprechen</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
}

function phinix_install_editorial_articles( $status = 'draft' ) {
    $slug = 'digitale-aussenwerbung-gladbeck-phinix-screens';
    if ( get_page_by_path( $slug, OBJECT, 'page' ) ) { return array( 'created' => array(), 'skipped' => array( $slug ) ); }
    $id = wp_insert_post( array(
        'post_type' => 'page',
        'post_status' => 'publish' === $status ? 'publish' : 'draft',
        'post_name' => $slug,
        'post_title' => 'Digitale Außenwerbung in Gladbeck: So funktioniert PHINIX.SCREENS',
        'post_excerpt' => 'Was Digital Signage und DOOH bedeuten, wie PHINIX.SCREENS die Buchung beschreibt und wie Phinix Media ein passendes Screen-Motiv gestaltet.',
        'post_content' => phinix_screens_article_content(),
    ), true );
    if ( is_wp_error( $id ) ) { return array( 'created' => array(), 'skipped' => array(), 'errors' => array( $id->get_error_message() ) ); }
    update_post_meta( $id, '_phinix_seo_title', 'Digitale Außenwerbung Gladbeck | PHINIX.SCREENS' );
    update_post_meta( $id, '_phinix_seo_description', 'Digitale Außenwerbung in Gladbeck: So funktioniert PHINIX.SCREENS – von Standort und Upload bis zur Ausspielung. Plus Gestaltung durch Phinix Media.' );
    update_post_meta( $id, '_phinix_article', '1' );
    update_post_meta( $id, '_phinix_article_image', 'assets/images/people/phinix-brand-stage.jpg' );
    return array( 'created' => array( $slug ), 'skipped' => array() );
}
