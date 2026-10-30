<?php
/** Non-destructive installation of editorial content shipped with the theme. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function phinix_theme_picture_shortcode( $attributes ) {
    $images = array(
        'phinix-brand-stage.webp' => array( 'people', 1600, 900 ),
        /* Backward-compatible alias for article content installed by 0.7.0. */
        'phinix-brand-stage.jpg' => array( 'people', 1600, 900, 'phinix-brand-stage.webp' ),
        'website-pruefen-impressum-hero.webp' => array( 'articles', 1672, 941 ),
        'website-pruefen-checkliste.webp' => array( 'articles', 1536, 1024 ),
    );
    $attributes = shortcode_atts( array( 'name' => '', 'alt' => '', 'class' => '', 'priority' => 'auto' ), $attributes, 'phinix_theme_picture' );
    $name = sanitize_file_name( $attributes['name'] );
    if ( ! isset( $images[ $name ] ) ) { return ''; }
    $file = isset( $images[ $name ][3] ) ? $images[ $name ][3] : $name;
    $priority = 'high' === $attributes['priority'];
    $classes = array_filter( array_map( 'sanitize_html_class', preg_split( '/\s+/', (string) $attributes['class'] ) ) );
    return '<figure class="phinix-article-image ' . esc_attr( implode( ' ', $classes ) ) . '"><img src="' . esc_url( get_theme_file_uri( 'assets/images/' . $images[ $name ][0] . '/' . $file ) ) . '" alt="' . esc_attr( $attributes['alt'] ) . '" width="' . (int) $images[ $name ][1] . '" height="' . (int) $images[ $name ][2] . '" loading="' . ( $priority ? 'eager' : 'lazy' ) . '" decoding="async"' . ( $priority ? ' fetchpriority="high"' : '' ) . '></figure>';
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
        $person_walk_markup .= '<img class="screens-story__pose screens-story__pose--walk screens-story__walk-frame" src="' . esc_url( get_theme_file_uri( 'assets/images/screens/' . $frame ) ) . '" alt="" width="1024" height="1536" loading="lazy" decoding="async">';
    }
    $person_scan = get_theme_file_uri( 'assets/images/screens/customer-scan-v2.webp' );
    $person_jump = get_theme_file_uri( 'assets/images/screens/customer-jump-v2.webp' );
    $qr_code = get_theme_file_uri( 'assets/images/screens/screen-qr-v1.svg' );
    return '<section class="screens-story" aria-labelledby="screens-story-title">
        <div class="screens-story__head">
            <p class="eyebrow">PHINIX.SCREENS / SO FUNKTIONIERT ES</p>
            <h2 id="screens-story-title">BUCHEN.<br>SCANNEN.<br><em>AUFFALLEN.</em></h2>
            <p>Eine kurze Szene zeigt den ganzen Weg: ankommen, QR-Code scannen und den eigenen Spot direkt auf dem Display erleben.</p>
        </div>
        <div class="screens-story__stage" data-screens-story>
            <p class="screen-reader-text">Eine Person geht mit einem Smartphone zu einem Digital-Signage-Display, scannt den QR-Code, sieht den Werbespot auf dem Screen und springt vor Freude.</p>
            <div class="screens-story__meta" aria-hidden="true"><span>PHINIX.SCREENS</span><span>DER WEG ZUM DIGITALEN AUFTRITT</span></div>
            <button class="screens-story__toggle" type="button" aria-label="Animation pausieren" data-paused="false" hidden>Animation pausieren</button>
            <div class="screens-story__statement" aria-hidden="true"><span>DEINE WERBUNG.</span><strong>MITTEN IM LEBEN.</strong></div>
            <div class="screens-story__world" aria-hidden="true">
                <div class="screens-story__ground"></div>
                <div class="screens-story__shadow"></div>
                <div class="screens-story__person">
                    ' . $person_walk_markup . '
                    <img class="screens-story__pose screens-story__pose--scan" src="' . esc_url( $person_scan ) . '" alt="" width="1024" height="1536" loading="lazy" decoding="async">
                    <img class="screens-story__pose screens-story__pose--jump" src="' . esc_url( $person_jump ) . '" alt="" width="1024" height="1536" loading="lazy" decoding="async">
                </div>
                <div class="screens-story__scan-beam"></div>
                <div class="screens-story__signal"><i></i><i></i><i></i></div>
                <div class="screens-story__display">
                    <div class="screens-story__screen">
                        <div class="screens-story__idle"><span>PHINIX.</span><strong>SCREENS</strong><small>DEINE STADT. DEINE BÜHNE.</small></div>
                        <div class="screens-story__qr"><img src="' . esc_url( $qr_code ) . '" alt="" width="512" height="512" loading="lazy" decoding="async"></div>
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
<!-- wp:shortcode -->[phinix_theme_picture name="phinix-brand-stage.webp" alt="Inszeniertes Phinix Media Markenmotiv mit Kevin und Janine" class="article-lead-visual"]<!-- /wp:shortcode -->
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

function phinix_website_check_article_content() {
    return '<!-- wp:shortcode -->[phinix_theme_picture name="website-pruefen-impressum-hero.webp" alt="Laptop mit abstraktem Website-Check und orangefarbener Prüflinie" class="article-lead-visual website-check-hero" priority="high"]<!-- /wp:shortcode -->
<!-- wp:paragraph {"className":"service-lead"} --><p class="service-lead">Solche Zahlen sorgen für Aufmerksamkeit. Als pauschale Angabe für „Abmahnkosten“ sind 2.500 bis 200.000 Euro jedoch nicht belastbar. Das Gesetz nennt für Impressumsverstöße keine solche feste Kostenspanne. Ein unvollständiges oder veraltetes Impressum kann trotzdem rechtliche Folgen haben. Deshalb lohnt sich der Check besonders nach geschäftlichen Veränderungen.</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"article-fact-box","layout":{"type":"constrained"}} --><div class="wp-block-group article-fact-box"><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">DER FAKTENCHECK.</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Eine Abmahnung ist kein behördliches Bußgeld. Abmahnkosten, mögliche Gerichtskosten und Bußgelder beruhen auf unterschiedlichen Grundlagen. Für bestimmte Verstöße gegen die Impressumspflichten des § 5 DDG sieht § 33 DDG einen Bußgeldrahmen von bis zu 50.000 Euro vor. Ob überhaupt ein Bußgeld verhängt wird und wie hoch es ausfällt, hängt vom Einzelfall ab.</p><!-- /wp:paragraph --></div><!-- /wp:group -->
<!-- wp:heading --><h2 class="wp-block-heading">Der Copy-and-paste-Fehler</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Mara betreibt ein kleines Designstudio im Ruhrgebiet. Als sie ihre erste Website veröffentlicht, arbeitet sie noch als Einzelunternehmerin. Das Impressum entsteht aus einer alten Vorlage und liegt anschließend gut erreichbar im Footer. Ein Jahr später wächst das Unternehmen und wird in eine GmbH umgewandelt. Das Logo wird neu gestaltet, die Startseite erhält frische Texte und eine Werbekampagne wird vorbereitet.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Nur das Impressum bleibt unverändert. Dort fehlen nun die Rechtsform, die vertretungsberechtigte Person, das Registergericht und die Registernummer. Auch die neue Geschäftsanschrift wurde nicht übernommen. Niemand hat bewusst falsche Angaben gemacht. Die Pflichtseite wurde beim Relaunch schlicht vergessen.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Kurz vor dem Kampagnenstart lässt Mara ihre Website noch einmal prüfen. Die veralteten Angaben fallen auf und können rechtzeitig aktualisiert werden. Diese Geschichte ist ein <strong>fiktives, typisches Beispiel</strong> – kein echter Fall. Der Auslöser ist dennoch alltäglich: Unternehmen ziehen um, wechseln die Rechtsform, bekommen eine neue Geschäftsführung oder übernehmen beim Relaunch veraltete Inhalte.</p><!-- /wp:paragraph -->
<!-- wp:shortcode -->[phinix_theme_picture name="website-pruefen-checkliste.webp" alt="Abstrakte Prüfung eines Website-Footers mit drei vollständigen und einem offenen Punkt" class="article-support-visual"]<!-- /wp:shortcode -->
<!-- wp:heading --><h2 class="wp-block-heading">Was § 5 DDG für geschäftliche Websites verlangt</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Nach <a href="https://www.gesetze-im-internet.de/ddg/__5.html" target="_blank" rel="noopener noreferrer">§ 5 des Digitale-Dienste-Gesetzes</a> müssen Anbieter geschäftsmäßiger, in der Regel gegen Entgelt angebotener digitaler Dienste bestimmte Informationen bereithalten. Sie müssen leicht erkennbar, unmittelbar erreichbar und ständig verfügbar sein.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Dazu gehören insbesondere der vollständige Name oder die Firma und eine ladungsfähige Anschrift. Bei juristischen Personen kommen unter anderem Rechtsform und vertretungsberechtigte Person hinzu. Abhängig vom Unternehmen können außerdem Register und Registernummer, Umsatzsteuer-Identifikationsnummer, Angaben zur Aufsichtsbehörde oder berufsrechtliche Informationen erforderlich sein. Eine E-Mail-Adresse sowie Angaben für eine schnelle elektronische Kontaktaufnahme gehören ebenfalls zu den Pflichtinformationen.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Was bei falschen oder fehlenden Angaben passieren kann</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Wer erforderliche Informationen nicht, nicht richtig oder nicht vollständig verfügbar hält, kann nach <a href="https://www.gesetze-im-internet.de/ddg/__33.html" target="_blank" rel="noopener noreferrer">§ 33 DDG</a> ordnungswidrig handeln. Für diesen Verstoß sieht das Gesetz eine Geldbuße von bis zu 50.000 Euro vor. Diese Obergrenze ist keine automatische Rechnung und keine Aussage darüber, welche Sanktion in einem konkreten Fall verhängt würde.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Zusätzlich können wettbewerbsrechtliche Ansprüche eine Rolle spielen. Eine Abmahnung soll nach <a href="https://www.gesetze-im-internet.de/uwg_2004/__13.html" target="_blank" rel="noopener noreferrer">§ 13 UWG</a> die Möglichkeit schaffen, einen Streit ohne Gerichtsverfahren beizulegen. Mahnt ein Mitbewerber einen Verstoß gegen gesetzliche Informations- oder Kennzeichnungspflichten im elektronischen Geschäftsverkehr oder bei digitalen Diensten ab, ist sein Anspruch auf Ersatz der Abmahnaufwendungen nach § 13 Absatz 4 Nummer 1 UWG ausgeschlossen. Das bedeutet nicht, dass ein Impressumsfehler folgenlos bleibt: Beseitigungs- oder Unterlassungsansprüche, Verfahren anderer berechtigter Stellen sowie eigene Beratungs- und mögliche Gerichtskosten können weiterhin relevant werden.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2 class="wp-block-heading">Impressum prüfen: die kompakte Checkliste</h2><!-- /wp:heading -->
<!-- wp:list {"className":"article-checklist"} --><ul class="wp-block-list article-checklist"><!-- wp:list-item --><li>Ist das Impressum eindeutig bezeichnet und von jeder Seite leicht erreichbar?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Sind Firma oder vollständiger Name und eine aktuelle Anschrift angegeben?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Stimmen Rechtsform und vertretungsberechtigte Person?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Sind E-Mail-Adresse und ein geeigneter direkter Kontaktweg vorhanden?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Sind Registergericht und Registernummer angegeben, falls erforderlich?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Sind Umsatzsteuer-ID oder Wirtschafts-ID aufgeführt, sofern vorhanden?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Sind bei erlaubnispflichtigen oder reglementierten Tätigkeiten zusätzliche Angaben nötig?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Wurde das Impressum nach Umzug, Umfirmierung oder Wechsel der Geschäftsführung aktualisiert?</li><!-- /wp:list-item --><!-- wp:list-item --><li>Passen die Angaben auch zu den geschäftlichen Social-Media-Profilen?</li><!-- /wp:list-item --></ul><!-- /wp:list -->
<!-- wp:heading --><h2 class="wp-block-heading">Finde Auffälligkeiten, bevor sie zum Problem werden</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Ein Website-Check kann offensichtliche Lücken, veraltete Angaben und technische Auffälligkeiten früh sichtbar machen. Complioneer prüft Websites automatisiert auf zentrale Anforderungen an Impressum, Datenschutzerklärung und Cookie-Consent. Funde werden mit Schweregrad und Gesetzesreferenz dargestellt und in einem Compliance-Score zusammengefasst. Prüfe dort nur deine eigene Website oder Seiten, für deren Prüfung du berechtigt bist.</p><!-- /wp:paragraph -->
<!-- wp:buttons {"className":"article-cta"} --><div class="wp-block-buttons article-cta"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://complioneer.eu/" target="_blank" rel="noopener noreferrer">Website mit Complioneer prüfen ↗</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="https://wa.me/4917655376651?text=Hallo%20Phinix%20Media%2C%20ich%20m%C3%B6chte%20meine%20Website%20pr%C3%BCfen%20lassen." target="_blank" rel="noopener noreferrer">Per WhatsApp anfragen · +49 176 5537 6651</a></div><!-- /wp:button --></div><!-- /wp:buttons -->
<!-- wp:group {"className":"article-legal-note","layout":{"type":"constrained"}} --><div class="wp-block-group article-legal-note"><!-- wp:paragraph --><p><strong>Hinweis:</strong> Dieser Beitrag enthält allgemeine Informationen und keine Rechtsberatung. Ein automatisierter oder redaktioneller Website-Check kann Hinweise auf mögliche Auffälligkeiten geben, ersetzt aber keine anwaltliche Prüfung des Einzelfalls.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><strong>Rechtsstand: 7. Oktober 2026.</strong> Amtliche Quellen: <a href="https://www.gesetze-im-internet.de/ddg/__5.html" target="_blank" rel="noopener noreferrer">§ 5 DDG</a>, <a href="https://www.gesetze-im-internet.de/ddg/__33.html" target="_blank" rel="noopener noreferrer">§ 33 DDG</a>, <a href="https://www.gesetze-im-internet.de/uwg_2004/__13.html" target="_blank" rel="noopener noreferrer">§ 13 UWG</a> und die <a href="https://www.medienanstalt-nrw.de/aufsicht/transparenz-im-internet.html" target="_blank" rel="noopener noreferrer">Landesanstalt für Medien NRW</a>.</p><!-- /wp:paragraph --></div><!-- /wp:group -->';
}

function phinix_editorial_articles() {
    return array(
        'digitale-aussenwerbung-gladbeck-phinix-screens' => array(
            'post_type' => 'page',
            'title' => 'Digitale Außenwerbung in Gladbeck: So funktioniert PHINIX.SCREENS',
            'excerpt' => 'Was Digital Signage und DOOH bedeuten, wie PHINIX.SCREENS die Buchung beschreibt und wie Phinix Media ein passendes Screen-Motiv gestaltet.',
            'content' => 'phinix_screens_article_content',
            'seo_title' => 'Digitale Außenwerbung Gladbeck | PHINIX.SCREENS',
            'seo_description' => 'Digitale Außenwerbung in Gladbeck: So funktioniert PHINIX.SCREENS – von Standort und Upload bis zur Ausspielung. Plus Gestaltung durch Phinix Media.',
            'image' => 'assets/images/people/phinix-brand-stage.webp',
            'image_width' => 1600,
            'image_height' => 900,
            'image_alt' => 'Inszeniertes Phinix Media Markenmotiv mit Kevin und Janine',
            'schema_type' => 'Article',
            'about' => array(
                array( '@type' => 'Thing', 'name' => 'Digitale Außenwerbung' ),
                array( '@type' => 'Thing', 'name' => 'Digital Signage' ),
                array( '@type' => 'Thing', 'name' => 'PHINIX.SCREENS', 'url' => 'https://www.phinix-screens.com/' ),
            ),
        ),
        'impressum-pruefen-website-check' => array(
            'post_type' => 'post',
            'title' => '2.500 bis 200.000 Euro wegen eines Impressumsfehlers?',
            'excerpt' => 'Welche Folgen fehlende oder veraltete Impressumsangaben wirklich haben können, was § 5 DDG verlangt und welche Punkte Unternehmen prüfen sollten.',
            'content' => 'phinix_website_check_article_content',
            'seo_title' => 'Impressum prüfen: Folgen von Fehlern | Phinix Media',
            'seo_description' => 'Fehlende Impressumsangaben können Unterlassungsansprüche und Bußgelder auslösen. Erfahre, was Unternehmen prüfen sollten – mit Website-Check.',
            'image' => 'assets/images/articles/website-pruefen-impressum-hero.webp',
            'image_width' => 1672,
            'image_height' => 941,
            'image_alt' => 'Laptop mit abstraktem Website-Check und orangefarbener Prüflinie',
            'schema_type' => 'BlogPosting',
            'about' => array(
                array( '@type' => 'Thing', 'name' => 'Impressumspflicht' ),
                array( '@type' => 'Thing', 'name' => 'Digitale-Dienste-Gesetz' ),
                array( '@type' => 'Thing', 'name' => 'Website-Check' ),
            ),
            'mentions' => array(
                array( '@type' => 'SoftwareApplication', 'name' => 'Complioneer', 'url' => 'https://complioneer.eu/' ),
            ),
        ),
    );
}

/* Keep bundled article permalinks deterministic in WordPress.com and Playground. */
add_action( 'parse_request', function ( $wp ) {
    $requested = trim( (string) $wp->request, '/' );
    if ( ! $requested ) { return; }
    $articles = phinix_editorial_articles();
    if ( isset( $articles[ $requested ] ) && 'post' === $articles[ $requested ]['post_type'] ) {
        $wp->query_vars['name'] = $requested;
        $wp->query_vars['post_type'] = 'post';
        unset( $wp->query_vars['pagename'], $wp->query_vars['page_id'] );
    }
}, 1 );

function phinix_install_editorial_articles( $status = 'draft' ) {
    $result = array( 'created' => array(), 'skipped' => array(), 'errors' => array() );
    foreach ( phinix_editorial_articles() as $slug => $article ) {
        $post = get_page_by_path( $slug, OBJECT, array( 'page', 'post' ) );
        if ( $post ) {
            $id = $post->ID;
            $result['skipped'][] = $slug;
        } else {
            $id = wp_insert_post( array(
                'post_type' => $article['post_type'],
                'post_status' => 'publish' === $status ? 'publish' : 'draft',
                'post_name' => $slug,
                'post_title' => $article['title'],
                'post_excerpt' => $article['excerpt'],
                'post_content' => call_user_func( $article['content'] ),
            ), true );
            if ( is_wp_error( $id ) ) {
                $result['errors'][] = $id->get_error_message();
                continue;
            }
            $result['created'][] = $slug;
        }
        $metadata = array(
            '_phinix_seo_title' => $article['seo_title'],
            '_phinix_seo_description' => $article['seo_description'],
            '_phinix_article' => '1',
            '_phinix_article_image' => $article['image'],
            '_phinix_article_image_width' => $article['image_width'],
            '_phinix_article_image_height' => $article['image_height'],
            '_phinix_article_image_alt' => $article['image_alt'],
            '_phinix_article_schema_type' => $article['schema_type'],
            '_phinix_article_about' => wp_json_encode( $article['about'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
            '_phinix_article_mentions' => wp_json_encode( isset( $article['mentions'] ) ? $article['mentions'] : array(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
        );
        foreach ( $metadata as $key => $value ) {
            if ( '' === get_post_meta( $id, $key, true ) ) { update_post_meta( $id, $key, $value ); }
        }
    }
    if ( empty( $result['errors'] ) ) { unset( $result['errors'] ); }
    return $result;
}
