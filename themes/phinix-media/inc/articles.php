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

function phinix_screens_story_shortcode() {
    $person_walk_frames = array(
        'customer-walk-01-contact-a-v5.webp',
        'customer-walk-02-down-a-v5.webp',
        'customer-walk-03-passing-a-v5.webp',
        'customer-walk-04-up-a-v5.webp',
        'customer-walk-05-contact-b-v5.webp',
        'customer-walk-06-down-b-v5.webp',
        'customer-walk-07-passing-b-v5.webp',
        'customer-walk-08-up-b-v5.webp',
    );
    $person_walk_markup = '';
    foreach ( $person_walk_frames as $frame ) {
        $person_walk_markup .= '<img class="screens-story__pose screens-story__pose--walk screens-story__walk-frame" src="' . esc_url( get_theme_file_uri( 'assets/images/screens/' . $frame ) ) . '" alt="" width="1024" height="1536" decoding="async">';
    }
    $person_scan = get_theme_file_uri( 'assets/images/screens/customer-scan-v2.webp' );
    $person_jump = get_theme_file_uri( 'assets/images/screens/customer-jump-v2.webp' );
    $qr_code = get_theme_file_uri( 'assets/images/screens/screen-qr-v1.svg' );
    return '<section class="screens-story" aria-labelledby="screens-story-title">
        <div class="screens-story__head">
            <p class="eyebrow">PHINIX.SCREENS / AUTOMATISCHE DEMO</p>
            <h2 id="screens-story-title">BUCHEN.<br>SCANNEN.<br><em>AUFFALLEN.</em></h2>
            <p>Eine kurze Szene zeigt den ganzen Weg: ankommen, QR-Code scannen und den eigenen Spot direkt auf dem Display erleben.</p>
        </div>
        <div class="screens-story__stage" data-screens-story>
            <p class="screen-reader-text">Eine Person geht mit einem Smartphone zu einem Digital-Signage-Display, scannt den QR-Code, sieht den Werbespot auf dem Screen und springt vor Freude.</p>
            <div class="screens-story__meta" aria-hidden="true"><span>PHINIX.SCREENS</span><span>AUTOMATISCHE SZENE · 12 SEK.</span></div>
            <button class="screens-story__toggle" type="button" aria-label="Animation pausieren" data-paused="false" hidden>Animation pausieren</button>
            <div class="screens-story__statement" aria-hidden="true"><span>DEINE WERBUNG.</span><strong>MITTEN IM LEBEN.</strong></div>
            <div class="screens-story__world" aria-hidden="true">
                <div class="screens-story__ground"></div>
                <div class="screens-story__shadow"></div>
                <div class="screens-story__person">
                    ' . $person_walk_markup . '
                    <img class="screens-story__pose screens-story__pose--scan" src="' . esc_url( $person_scan ) . '" alt="" width="1024" height="1536" decoding="async">
                    <img class="screens-story__pose screens-story__pose--jump" src="' . esc_url( $person_jump ) . '" alt="" width="1024" height="1536" decoding="async">
                </div>
                <div class="screens-story__scan-beam"></div>
                <div class="screens-story__signal"><i></i><i></i><i></i></div>
                <div class="screens-story__display">
                    <div class="screens-story__screen">
                        <div class="screens-story__idle"><span>PHINIX.</span><strong>SCREENS</strong><small>DEINE STADT. DEINE BÜHNE.</small></div>
                        <div class="screens-story__qr"><img src="' . esc_url( $qr_code ) . '" alt="" width="512" height="512" decoding="async"></div>
                        <div class="screens-story__ad"><small>NEU IN GLADBECK</small><strong>DEIN<br>ANGEBOT.</strong><b>JETZT ENTDECKEN</b></div>
                        <div class="screens-story__flash"></div>
                    </div>
                    <div class="screens-story__stand"></div>
                </div>
                <div class="screens-story__success"><span>✓</span><strong>KAMPAGNE LIVE</strong></div>
            </div>
            <div class="screens-story__status"><i></i><p data-story-caption>Vier Schritte erklären den Ablauf unterhalb der Szene.</p></div>
            <div class="screens-story__progress" aria-hidden="true"><span></span></div>
        </div>
        <ol class="screens-story__steps" data-story-steps role="list">
            <li><span aria-hidden="true">01</span><strong>Standort wählen</strong><p>Display, Laufzeit und Zeitraum passend zur Kampagne auswählen.</p></li>
            <li><span aria-hidden="true">02</span><strong>QR-Code scannen</strong><p>Buchung auf dem Smartphone öffnen und das Motiv bereitstellen.</p></li>
            <li><span aria-hidden="true">03</span><strong>Spot ausspielen</strong><p>Nach Prüfung und Freigabe erscheint die Werbung auf dem Screen.</p></li>
            <li><span aria-hidden="true">04</span><strong>Sichtbar werden</strong><p>Lokale Aufmerksamkeit genau dort, wo Menschen unterwegs sind.</p></li>
        </ol>
    </section>';
}
add_shortcode( 'phinix_screens_story', 'phinix_screens_story_shortcode' );

/* Keep the story available on sites where the editorial page predates this theme update. */
add_filter( 'the_content', function ( $content ) {
    if ( ! is_page( 'digitale-aussenwerbung-gladbeck-phinix-screens' ) || has_shortcode( $content, 'phinix_screens_story' ) ) { return $content; }
    $story = "\n[phinix_screens_story]\n";
    $updated = preg_replace( '/(<p[^>]*class="[^"]*service-lead[^"]*"[^>]*>.*?<\/p>)/s', '$1' . $story, $content, 1 );
    return $updated !== $content ? $updated : $story . $content;
}, 8 );

function phinix_screens_article_content() {
    return '<!-- wp:shortcode -->[phinix_article_meta]<!-- /wp:shortcode -->
<!-- wp:paragraph {"className":"service-lead"} --><p class="service-lead">Digitale Außenwerbung bringt eine Botschaft auf Displays im öffentlichen Raum. PHINIX.SCREENS beschreibt dafür eine Plattform, über die Unternehmen in Gladbeck einen Standort und eine Ausspielung wählen, ihr Motiv hochladen und die Kampagne nach Freigabe ausspielen lassen können.</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[phinix_screens_story]<!-- /wp:shortcode -->
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
