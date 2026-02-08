<?php
get_header();
?>

<?php
    $heroImgSrc = esc_url(get_option('home-img'));
    $heroTxt = "Unsere Kunden";
?>

<main>
    <?php include_once "assets/_snippets/hero.php"; ?>

    <div class="kunden-holder">
        <div class="kunden mw">
            <?php
            $referenzen_query = new WP_Query(
                array(
                    'post_type'      => 'referenzen',
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                )
            );
            if ( $referenzen_query->have_posts() ) :
                while ( $referenzen_query->have_posts() ) :
                    $referenzen_query->the_post();
                    $image_url = get_post_meta( get_the_ID(), 'referenzen_kundenbild_url', true );
                    $card_link = get_post_meta( get_the_ID(), 'referenzen_card_link_url', true );
                    ?>
                    <a class="kunden-card kunde-card reveal" href="<?php echo esc_url( $card_link ? $card_link : '#' ); ?>">
                        <?php if ( ! empty( $image_url ) ) : ?>
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
                        <?php endif; ?>
                        <p class="light"><?php echo esc_html( get_the_title() ); ?></p>
                        </a>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>


    <div class="die-nächsten-holder">
        <div class="img-transition-top"></div>
        <div class="die-nächsten-inner mw">
            <h3 class="light">Sind Sie die Nächsten?</h3>
            <button onclick="window.location.href='<?php echo esc_url( home_url( '/kontakt/' ) ); ?>'" class="btn light">
                <span class="btn__border" aria-hidden="true">
                    <svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                        <path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                    </svg>
                </span>
                <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Jetzt Kontakt aufnehmen!</p>
            </button>
        </div>
        <div class="img-transition-bottom"></div>
    </div>


</main>


<?php
get_footer();
