<?php
/**
 * Managed legal pages for the static, tracking-free Phinix Media website.
 *
 * The installer only creates missing pages or replaces content that was created
 * by this installer (including the former, explicit blueprint placeholder).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function phinix_legal_heading( $text ) {
    return '<!-- wp:heading --><h2 class="wp-block-heading">' . esc_html( $text ) . '</h2><!-- /wp:heading -->';
}

function phinix_legal_paragraph( $html ) {
    return '<!-- wp:paragraph --><p>' . wp_kses_post( $html ) . '</p><!-- /wp:paragraph -->';
}

function phinix_legal_list( $items ) {
    $out = '<!-- wp:list --><ul class="wp-block-list">';
    foreach ( $items as $item ) {
        $out .= '<!-- wp:list-item --><li>' . wp_kses_post( $item ) . '</li><!-- /wp:list-item -->';
    }
    return $out . '</ul><!-- /wp:list -->';
}

function phinix_imprint_content() {
    $out  = phinix_legal_paragraph( '<strong>Angaben gemäß § 5 Digitale-Dienste-Gesetz (DDG)</strong>' );
    $out .= phinix_legal_paragraph( "Phinix Media<br>Kevin Stumpe<br>Buerelterstraße 27<br>45896 Gelsenkirchen<br>Deutschland" );
    $out .= phinix_legal_heading( 'Kontakt' );
    $out .= phinix_legal_paragraph( 'Telefon: <a href="tel:+4917655376651">+49 176 5537 6651</a><br>E-Mail: <a href="mailto:kontakt@phinix.media">kontakt@phinix.media</a>' );
    $out .= phinix_legal_heading( 'Umsatzsteuer-Identifikationsnummer' );
    $out .= phinix_legal_paragraph( 'Umsatzsteuer-Identifikationsnummer gemäß § 27a Umsatzsteuergesetz: <strong>DE369075395</strong>' );
    $out .= phinix_legal_heading( 'Verantwortlich für journalistisch-redaktionelle Inhalte' );
    $out .= phinix_legal_paragraph( 'Verantwortlich gemäß § 18 Abs. 2 Medienstaatsvertrag (MStV):<br>Kevin Stumpe, Buerelterstraße 27, 45896 Gelsenkirchen' );
    return $out;
}

function phinix_privacy_content() {
    $out  = phinix_legal_paragraph( '<strong>Stand: Oktober 2026</strong>' );
    $out .= phinix_legal_paragraph( 'Diese Datenschutzerklärung erläutert, welche personenbezogenen Daten beim Besuch dieser Website und bei einer Kontaktaufnahme verarbeitet werden. Die öffentlich bereitgestellte Website verwendet nach aktuellem technischen Stand keine Analyse-, Marketing- oder Profiling-Dienste, keine Kontaktformulare und keine extern geladenen Schriftarten.' );

    $out .= phinix_legal_heading( '1. Verantwortlicher' );
    $out .= phinix_legal_paragraph( "Phinix Media<br>Kevin Stumpe<br>Buerelterstraße 27<br>45896 Gelsenkirchen<br>Deutschland<br>Telefon: <a href=\"tel:+4917655376651\">+49 176 5537 6651</a><br>E-Mail: <a href=\"mailto:kontakt@phinix.media\">kontakt@phinix.media</a>" );

    $out .= phinix_legal_heading( '2. Bereitstellung der Website und Server-Protokolle' );
    $out .= phinix_legal_paragraph( 'Beim Aufruf der Website verarbeitet die ausliefernde Server-Infrastruktur technisch erforderliche Verbindungsdaten. Dazu können insbesondere IP-Adresse, Zeitpunkt, angeforderte Datei, übertragene Datenmenge, Browser, Betriebssystem, Referrer und Zugriffsstatus gehören. Die Verarbeitung ist erforderlich, um die Website sicher und zuverlässig auszuliefern, Angriffe zu erkennen und technische Fehler zu beheben.' );
    $out .= phinix_legal_paragraph( 'Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO. Das berechtigte Interesse liegt in der sicheren, stabilen und missbrauchsfreien Bereitstellung des Online-Angebots. Einzelheiten zur Speicherdauer und zu etwaigen Empfängern richten sich nach der tatsächlich eingesetzten Server- und Hosting-Konfiguration.' );

    $out .= phinix_legal_heading( '3. Kontaktaufnahme' );
    $out .= phinix_legal_paragraph( 'Wenn du per E-Mail oder Telefon Kontakt aufnimmst, verarbeiten wir die von dir übermittelten Angaben, um deine Anfrage zu beantworten und die Kommunikation zu dokumentieren. Die Rechtsgrundlage ist Art. 6 Abs. 1 lit. b DSGVO, soweit es um vorvertragliche oder vertragliche Kommunikation geht, und im Übrigen Art. 6 Abs. 1 lit. f DSGVO aufgrund unseres Interesses an einer sachgerechten Bearbeitung von Anfragen.' );
    $out .= phinix_legal_paragraph( 'Wir löschen die dabei anfallenden Daten, wenn die Anfrage abschließend bearbeitet ist und keine gesetzlichen Aufbewahrungspflichten oder berechtigten Gründe für eine weitere Speicherung bestehen.' );

    $out .= phinix_legal_heading( '4. Externe Links und WhatsApp' );
    $out .= phinix_legal_paragraph( 'Diese Website enthält Links zu externen Angeboten, unter anderem zu PHINIX.SCREENS, Complioneer und WhatsApp. Eine Verbindung zum jeweiligen Anbieter wird erst hergestellt, wenn du den Link aktiv öffnest. Ab diesem Zeitpunkt gelten die Datenschutzbestimmungen des externen Anbieters. Bei WhatsApp ist insbesondere eine Verarbeitung durch den Anbieter Meta möglich. Nutze alternativ E-Mail oder Telefon, wenn du diesen Dienst nicht verwenden möchtest.' );

    $out .= phinix_legal_heading( '5. Cookies, Reichweitenmessung und lokale Schriftarten' );
    $out .= phinix_legal_paragraph( 'Die öffentlich zugängliche Website setzt keine Analyse- oder Marketing-Cookies ein und verwendet keine Reichweitenmessung. Die verwendete Schriftart wird lokal von derselben Website ausgeliefert; beim Seitenaufruf entsteht deshalb keine Verbindung zu einem externen Schriftanbieter. Technisch notwendige Cookies können nur in nicht öffentlichen Verwaltungs- oder Anmeldebereichen der zugrunde liegenden Software eingesetzt werden.' );

    $out .= phinix_legal_heading( '6. Deine Rechte' );
    $out .= phinix_legal_paragraph( 'Du hast nach Maßgabe der gesetzlichen Voraussetzungen insbesondere folgende Rechte:' );
    $out .= phinix_legal_list( array(
        'Auskunft über die Verarbeitung deiner personenbezogenen Daten (Art. 15 DSGVO)',
        'Berichtigung unrichtiger Daten (Art. 16 DSGVO)',
        'Löschung (Art. 17 DSGVO) und Einschränkung der Verarbeitung (Art. 18 DSGVO)',
        'Datenübertragbarkeit (Art. 20 DSGVO)',
        'Widerspruch gegen eine Verarbeitung auf Grundlage von Art. 6 Abs. 1 lit. e oder f DSGVO (Art. 21 DSGVO)',
        'Widerruf einer erteilten Einwilligung mit Wirkung für die Zukunft (Art. 7 Abs. 3 DSGVO)',
    ) );
    $out .= phinix_legal_paragraph( 'Zur Ausübung deiner Rechte genügt eine Nachricht an <a href="mailto:kontakt@phinix.media">kontakt@phinix.media</a>.' );

    $out .= phinix_legal_heading( '7. Beschwerderecht' );
    $out .= phinix_legal_paragraph( 'Du hast das Recht, dich bei einer Datenschutzaufsichtsbehörde zu beschweren. Für Nordrhein-Westfalen ist dies die <a href="https://www.ldi.nrw.de/" target="_blank" rel="noopener noreferrer">Landesbeauftragte für Datenschutz und Informationsfreiheit Nordrhein-Westfalen</a>.' );

    $out .= phinix_legal_heading( '8. Aktualisierung dieser Erklärung' );
    $out .= phinix_legal_paragraph( 'Wir passen diese Datenschutzerklärung an, wenn sich die Website, die eingesetzte Server-Infrastruktur oder die rechtlichen Anforderungen ändern.' );
    return $out;
}

function phinix_legal_pages() {
    return array(
        'impressum' => array(
            'title'       => 'Impressum',
            'description' => 'Impressum von Phinix Media in Gelsenkirchen mit Anbieter-, Kontakt- und Umsatzsteuerangaben.',
            'content'     => phinix_imprint_content(),
        ),
        'datenschutz' => array(
            'title'       => 'Datenschutz',
            'description' => 'Datenschutzhinweise von Phinix Media zur Website, Server-Protokollen, Kontaktaufnahme und externen Links.',
            'content'     => phinix_privacy_content(),
        ),
    );
}

function phinix_install_legal_pages( $status = 'draft' ) {
    $created = array();
    $updated = array();
    $skipped = array();
    $legacy_placeholder = 'Entwurfsseite. Die geprüften Angaben werden vor Veröffentlichung ergänzt.';

    foreach ( phinix_legal_pages() as $slug => $data ) {
        $existing = get_page_by_path( $slug, OBJECT, 'page' );
        $postarr = array(
            'post_type'    => 'page',
            'post_name'    => $slug,
            'post_title'   => $data['title'],
            'post_excerpt' => $data['description'],
            'post_content' => $data['content'],
        );

        if ( $existing ) {
            $managed = '1' === get_post_meta( $existing->ID, '_phinix_managed_legal_page', true );
            $legacy  = false !== strpos( $existing->post_content, $legacy_placeholder );
            if ( ! $managed && ! $legacy ) {
                $skipped[] = $slug;
                continue;
            }
            $postarr['ID'] = $existing->ID;
            $result = wp_update_post( $postarr, true );
            if ( is_wp_error( $result ) ) {
                $skipped[] = $slug;
                continue;
            }
            $updated[] = $slug;
            $id = $existing->ID;
        } else {
            $postarr['post_status'] = 'publish' === $status ? 'publish' : 'draft';
            $id = wp_insert_post( $postarr, true );
            if ( is_wp_error( $id ) ) {
                $skipped[] = $slug;
                continue;
            }
            $created[] = $slug;
        }

        update_post_meta( $id, '_phinix_managed_legal_page', '1' );
        update_post_meta( $id, '_phinix_seo_title', $data['title'] . ' | Phinix Media' );
        update_post_meta( $id, '_phinix_seo_description', $data['description'] );
    }

    return array( 'created' => $created, 'updated' => $updated, 'skipped' => $skipped );
}
