<?php
/**
 * Title: Interaktive Stadion-Demo
 * Slug: phinix/stadion-demo
 * Categories: phinix
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$stations = array(
    'eingang' => array(
        'name' => 'Eingang',
        'alt' => 'Orangefarbenes Eingangsportal mit Drehkreuzen unter dem Stahldach.',
        'text' => 'Dein Rundgang beginnt vor den Toren. Hinter dem Eingang führt der Weg hinauf zum ersten Blick aufs Spielfeld.',
        'spot' => 'Zugang zur Tribüne',
        'detail' => 'Hier beginnt der Weg zum Block. In einem Rundgang für deine Arena könnten Gäste schon vor dem Besuch den passenden Eingang für ihren Sitzplatz finden.',
        'next' => 'tribuene', 'action' => 'Zur Tribüne',
    ),
    'tribuene' => array(
        'name' => 'Tribüne',
        'alt' => 'Blick von der überdachten Tribüne auf das Spielfeld und die beleuchteten Logen gegenüber.',
        'text' => 'Nimm auf der Tribüne Platz. Von hier siehst du, wie nah der Rasen liegt. In der Loge eröffnet sich gleich eine andere Perspektive.',
        'spot' => 'Blick vom Platz',
        'detail' => 'Vom Sitzplatz aus lässt sich der Blickwinkel besser einschätzen als auf einem Blockplan. Welche Sicht sich durch die Glasfront ergibt, erlebst du an der nächsten Station.',
        'next' => 'vip-loge', 'action' => 'In die VIP-Loge',
    ),
    'vip-loge' => array(
        'name' => 'VIP-Loge',
        'alt' => 'Gedeckter Tisch und Ledersessel vor der Fensterfront mit Blick aufs Spielfeld.',
        'text' => 'Hier endet der Weg mit Blick auf den Rasen. Möchtest du deinen Gästen die eigenen Räume so zeigen? Schreib Janine auf WhatsApp.',
        'spot' => 'Veranstaltung planen',
        'detail' => 'Eine Eventplanerin möchte vorab wissen, wo ihre Gäste sitzen und wie der Raum zum Empfang wirkt. In deiner eigenen Tour können an dieser Stelle Bestuhlungspläne, Raumdaten und die Terminanfrage stehen.',
    ),
);
$whatsapp = 'https://wa.me/' . ltrim( phinix_business_data()['phone'], '+' ) . '?text=' . rawurlencode( 'Hallo Janine, ich habe die Stadion-Demo gesehen und möchte einen Rundgang für meinen Standort anfragen.' );
?>
<!-- wp:html -->
<section class="stadium-tour" id="stadion-demo" aria-labelledby="stadium-tour-title">
    <header class="stadium-tour__header">
        <h2 id="stadium-tour-title">Stadion-Demo</h2>
        <p>Interaktive Tour durch ein fiktives Stadion</p>
    </header>
    <nav class="stadium-route" aria-label="Stationen des Stadionrundgangs">
        <?php $index = 0; foreach ( $stations as $slug => $station ) : ?>
            <a href="#stadion-<?php echo esc_attr( $slug ); ?>" data-tour-link>
                <span class="stadium-route__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', ++$index ) ); ?></span>
                <?php echo esc_html( $station['name'] ); ?>
            </a>
        <?php endforeach; ?>
    </nav>
    <?php foreach ( $stations as $slug => $station ) : ?>
        <section id="stadion-<?php echo esc_attr( $slug ); ?>" class="stadium-scene" aria-labelledby="station-title-<?php echo esc_attr( $slug ); ?>">
            <div class="stadium-scene__view">
                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/stadion-demo/' . $slug . '.webp' ) ); ?>" alt="<?php echo esc_attr( $station['alt'] ); ?>" width="1440" height="810" decoding="async" loading="<?php echo 'eingang' === $slug ? 'eager' : 'lazy'; ?>">
                <details class="stadium-point">
                    <summary><span aria-hidden="true" class="stadium-point__plus">+</span><?php echo esc_html( $station['spot'] ); ?></summary>
                    <p><?php echo esc_html( $station['detail'] ); ?></p>
                </details>
            </div>
            <div class="stadium-scene__caption">
                <div>
                    <h3 tabindex="-1" id="station-title-<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $station['name'] ); ?></h3>
                    <p><?php echo esc_html( $station['text'] ); ?></p>
                </div>
                <?php if ( isset( $station['next'] ) ) : ?>
                    <a class="stadium-action" href="#stadion-<?php echo esc_attr( $station['next'] ); ?>" data-tour-link><?php echo esc_html( $station['action'] ); ?></a>
                <?php else : ?>
                    <a class="stadium-action stadium-action--contact" href="<?php echo esc_url( $whatsapp ); ?>"><?php echo block_core_social_link_get_icon( 'whatsapp' ); ?>Rundgang anfragen</a>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
</section>
<!-- /wp:html -->
