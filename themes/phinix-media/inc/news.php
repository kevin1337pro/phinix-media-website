<?php
/** Public News index and homepage selection. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function phinix_install_news_page( $status = 'draft' ) {
    $existing = get_page_by_path( 'news' );
    if ( $existing ) { return $existing->ID; }
    return wp_insert_post( array(
        'post_type'    => 'page',
        'post_status'  => 'publish' === $status ? 'publish' : 'draft',
        'post_title'   => 'News (Blog)',
        'post_name'    => 'news',
        'post_excerpt' => 'Ideen für 3D-Rundgänge, Digital Signage und den digitalen Auftritt von Unternehmen im Ruhrgebiet.',
        'post_content' => '<!-- wp:paragraph {"className":"service-lead"} --><p class="service-lead">Wie fühlt sich eine VIP-Loge vor der ersten Besichtigung an? Was lässt sich auf einem City-Screen in wenigen Sekunden erzählen? Hier zeigen wir, wie Unternehmen Räume, Marken und Angebote digital erlebbar machen.</p><!-- /wp:paragraph -->' .
            '<!-- wp:shortcode -->[phinix_news]<!-- /wp:shortcode -->',
        'meta_input'   => array(
            '_phinix_seo_title' => 'News zu 3D-Rundgängen, Digital Signage & Web | Phinix Media',
            '_phinix_seo_description' => 'Im Blog von Phinix Media findest du Ideen für virtuelle 360°-Rundgänge, digitale Außenwerbung und bessere Websites. Mit Beispielen für Unternehmen im Ruhrgebiet.',
        ),
    ), true );
}

function phinix_public_news( $limit ) {
    $posts = get_posts( array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'has_password'   => false,
        'posts_per_page' => $limit,
        'orderby'        => array( 'date' => 'DESC', 'ID' => 'DESC' ),
    ) );
    // The original magazine article was published as a page. Keep its public URL.
    $screens = get_page_by_path( 'digitale-aussenwerbung-gladbeck-phinix-screens' );
    if ( $screens && 'publish' === $screens->post_status && '' === $screens->post_password ) {
        $posts[] = $screens;
        usort( $posts, function ( $a, $b ) {
            return ( $b->post_date <=> $a->post_date ) ?: ( $b->ID <=> $a->ID );
        } );
    }
    return $limit > 0 ? array_slice( $posts, 0, $limit ) : $posts;
}

function phinix_news_topic( $post ) {
    $topics = array(
        'markenidentitaet-wiedererkennung' => 'Branding',
        'virtuelle-rundgaenge-branchen' => '3D-Rundgänge',
        'digitale-aussenwerbung-gladbeck-phinix-screens' => 'Digital Signage',
        'impressum-pruefen-website-check' => 'Website prüfen',
    );
    if ( isset( $topics[ $post->post_name ] ) ) { return $topics[ $post->post_name ]; }
    foreach ( get_the_category( $post->ID ) as $category ) {
        if ( $category->term_id !== (int) get_option( 'default_category' ) ) { return $category->name; }
    }
    return 'Branchennews';
}

function phinix_news_card_image( $post ) {
    if ( has_post_thumbnail( $post ) ) {
        return get_the_post_thumbnail( $post, 'medium_large', array( 'loading' => 'lazy', 'decoding' => 'async' ) );
    }
    $image = get_post_meta( $post->ID, '_phinix_article_image', true );
    if ( ! $image ) { return ''; }
    $width = (int) get_post_meta( $post->ID, '_phinix_article_image_width', true );
    return sprintf( '<img src="%s"%s width="%d" height="%d" alt="%s" loading="lazy" decoding="async">',
        esc_url( get_theme_file_uri( $image ) ),
        phinix_article_srcset_attributes( $image, $width, 'auto, (max-width: 600px) 90vw, (max-width: 900px) 45vw, 33vw' ),
        $width,
        (int) get_post_meta( $post->ID, '_phinix_article_image_height', true ),
        esc_attr( get_post_meta( $post->ID, '_phinix_article_image_alt', true ) )
    );
}

function phinix_news_shortcode( $attributes ) {
    $attributes = shortcode_atts( array( 'view' => 'all' ), $attributes );
    $home = 'home' === $attributes['view'];
    $posts = phinix_public_news( $home ? 3 : -1 );
    if ( ! $posts ) { return $home ? '' : '<p>Hier erscheinen die nächsten Beiträge von Phinix Media.</p>'; }
    $heading = $home ? 'h3' : 'h2';
    ob_start(); ?>
    <div class="news-grid">
        <?php foreach ( $posts as $post ) : ?>
            <article class="news-card">
                <?php $image = phinix_news_card_image( $post ); ?>
                <?php if ( $image ) : ?><div class="news-card__image"><?php echo $image; ?></div><?php endif; ?>
                <div class="news-card__copy">
                    <p class="news-card__meta"><span><?php echo esc_html( phinix_news_topic( $post ) ); ?></span>
                        <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $post ) ); ?>"><?php echo esc_html( get_the_date( 'd.m.Y', $post ) ); ?></time>
                    </p>
                    <<?php echo $heading; ?> class="news-card__title"><a class="news-card__link" href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></<?php echo $heading; ?>>
                    <p class="news-card__excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt( $post ) ), 27, ' …' ) ); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php return ob_get_clean();
}
add_shortcode( 'phinix_news', 'phinix_news_shortcode' );

add_action( 'init', function () {
    register_block_type( 'phinix/news', array(
        'api_version' => 3,
        'attributes' => array( 'view' => array( 'type' => 'string', 'default' => 'all' ) ),
        'supports' => array( 'inserter' => false ),
        'render_callback' => 'phinix_news_shortcode',
    ) );
} );

function phinix_news_is_public() {
    $page = get_page_by_path( 'news' );
    return $page && 'publish' === $page->post_status && '' === $page->post_password;
}

add_action( 'wp_head', function () {
    if ( ( is_front_page() || is_page( 'news' ) || is_single() || get_post_meta( get_queried_object_id(), '_phinix_article', true ) ) && phinix_news_is_public() ) {
        echo '<link id="phinix-news-feed" rel="alternate" type="application/rss+xml" title="Phinix Media – News" href="' . esc_url( home_url( '/news/feed.xml' ) ) . '">' . "\n";
    }
} );

add_action( 'template_redirect', function () {
    global $wp;
    if ( 'news/feed.xml' !== $wp->request || ! phinix_news_is_public() ) { return; }
    status_header( 200 );
    header( 'Content-Type: application/rss+xml; charset=UTF-8' );
    if ( ! get_option( 'blog_public' ) ) { header( 'X-Robots-Tag: noindex' ); }
    if ( 'HEAD' === $_SERVER['REQUEST_METHOD'] ) { exit; }
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
    <title>Phinix Media – News</title>
    <link><?php echo esc_xml( home_url( '/news/' ) ); ?></link>
    <description>3D-Rundgänge, Digital Signage und Ideen für den digitalen Markenauftritt.</description>
    <language>de-DE</language>
    <atom:link href="<?php echo esc_url( home_url( '/news/feed.xml' ) ); ?>" rel="self" type="application/rss+xml"/>
    <?php foreach ( phinix_public_news( max( 1, (int) get_option( 'posts_per_rss', 10 ) ) ) as $post ) : ?>
    <item>
        <title><?php echo esc_xml( html_entity_decode( get_the_title( $post ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ); ?></title>
        <link><?php echo esc_xml( get_permalink( $post ) ); ?></link>
        <guid isPermaLink="true"><?php echo esc_xml( get_permalink( $post ) ); ?></guid>
        <pubDate><?php echo esc_xml( get_post_time( DATE_RSS, true, $post ) ); ?></pubDate>
        <category><?php echo esc_xml( phinix_news_topic( $post ) ); ?></category>
        <description><?php echo esc_xml( html_entity_decode( wp_strip_all_tags( get_the_excerpt( $post ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ); ?></description>
    </item>
    <?php endforeach; ?>
</channel>
</rss>
    <?php exit;
}, 0 );
