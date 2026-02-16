<?php
get_header();
?>


<?php
$heroImgSrc = esc_url(get_option('home-img'));
$heroTxt = "Website-Check";
?>

<main>
    <?php include_once "assets/_snippets/hero.php"; ?>

    <?php
    $allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
    $default_layout  = 'two-img-layout';
    $first_post_id   = 0;

    $render_website_check_post = static function( $post_id ) use ( $allowed_layouts, $default_layout ) {
        $layout = get_post_meta( $post_id, '_layout_template', true );
        if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
            $layout = $default_layout;
        }

        $meta = array(
            'mini_heading' => get_post_meta( $post_id, '_section_mini_heading', true ),
            'headline'     => get_post_meta( $post_id, '_section_headline', true ),
            'text'         => get_post_meta( $post_id, '_section_text', true ),
            'cta_label'    => get_post_meta( $post_id, '_cta_label', true ),
            'cta_url'      => get_post_meta( $post_id, '_cta_url', true ),
            'img_1_id'     => absint( get_post_meta( $post_id, '_img_1_id', true ) ),
            'img_2_id'     => absint( get_post_meta( $post_id, '_img_2_id', true ) ),
            'img_3_id'     => absint( get_post_meta( $post_id, '_img_3_id', true ) ),
        );

        if ( empty( $allowed_layouts[ $default_layout ] ) ) {
            return;
        }

        $snippet_rel_path = isset( $allowed_layouts[ $layout ] ) ? $allowed_layouts[ $layout ] : $allowed_layouts[ $default_layout ];
        include get_template_directory() . '/' . $snippet_rel_path;
    };

    $first_query = new WP_Query(
        array(
            'post_type'      => 'website_check',
            'posts_per_page' => 1,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ),
            'no_found_rows'  => true,
        )
    );

    if ( $first_query->have_posts() ) :
        while ( $first_query->have_posts() ) :
            $first_query->the_post();
            $first_post_id = get_the_ID();
            $render_website_check_post( $first_post_id );
        endwhile;
    endif;

    wp_reset_postdata();
    ?>


    <div class="contact-holder">
        <div class="contact mw-small wc-form">
            <div class="contact-info">
                <h4>Jetzt<br>kostenlosen <br><span>Websitecheck</span><br>anfordern!</h4>
            </div>
            <form action="" class="contact-form" method="post">
                <div class="form-row">
                    <input class="light" type="text" id="domain" name="domain">
                    <label class="default" for="domain">Ihre Domain</label>
                </div>
                <div class="form-row">
                    <input class="light" type="email" id="wc-email" name="wc-email">
                    <label class="default" for="wc-email">Email</label>
                </div>
                <div class="form-row">
                    <input type="checkbox" class="light" required>
                    <p class="light">Ich bin mit den <a href="<?php echo esc_url( home_url( '/datenschutzerklaerung/' ) ); ?>">Datenschutzbestimmungen</a> , der Verwendung meiner Daten zur Verarbeitung meiner Anfrage und der Zusendung weiterer Informationen per E-Mail einverstanden.</p>
                </div>
                <button type="submit" class="btn light">
                    <span class="btn__border" aria-hidden="true">
                        <svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                            <path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                            <path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                            <path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                            <path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                            <path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        </svg>
                    </span>
                    <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Anfordern</p>
                </button>
            </form>
        </div>
    </div>

    <?php
    $rest_args = array(
        'post_type'      => 'website_check',
        'posts_per_page' => -1,
        'orderby'        => array(
            'menu_order' => 'ASC',
            'date'       => 'DESC',
        ),
    );

    if ( $first_post_id > 0 ) {
        $rest_args['post__not_in'] = array( $first_post_id );
    }

    $rest_query = new WP_Query( $rest_args );

    if ( $rest_query->have_posts() ) :
        while ( $rest_query->have_posts() ) :
            $rest_query->the_post();
            $render_website_check_post( get_the_ID() );
        endwhile;
    endif;

    wp_reset_postdata();
    ?>

</main>


<?php
get_footer();
