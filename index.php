<script>
  document.documentElement.classList.add('js');
</script>
<?php
get_header();
?>


<main>


    <div class="home-hero observe-nav">
        <img class="hero-img" src="<?php echo esc_url(get_option('home-img')); ?>" alt="hero image">
        <div class="home-hero-inner mw">
            <h2 class="light home-hero__headline">
                <span class="hero-line hero-line--1">Gutes <span class="ac">Webdesign.</span></span>
                <span class="hero-line hero-line--2">Klar umgesetzt.</span>
            </h2>
            <h1 class="light home-hero__title" aria-label="Wendland Web Design">
                <span class="typed">
                    <span class="char blue">W</span><span class="rest">endland</span><span class="space"> </span>
                    <span class="char blue">W</span><span class="rest">eb</span><span class="space"> </span>
                    <span class="char blue">D</span><span class="rest">esign</span>
                </span>
                <span class="cursor">|</span>
            </h1>
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
                <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>kostenloses Erstgespräch</p>
            </button>
        </div>
        <img class="hero-fächer" src="<?php echo esc_url(get_option('faecher-home')); ?>" alt="website-faecher">
        <div class="img-transition-bottom"></div>
    </div>


    <?php
	$allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
	$default_layout  = 'two-img-layout';

	$home_query = new WP_Query(
		array(
			'post_type'      => 'home',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	if ( $home_query->have_posts() ) :
		while ( $home_query->have_posts() ) :
			$home_query->the_post();
			$post_id = get_the_ID();
			$layout  = get_post_meta( $post_id, '_layout_template', true );
			if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
				$layout = $default_layout;
			}

			$meta = array(
				'mini_heading' => get_post_meta( $post_id, '_section_mini_heading', true ),
				'headline'  => get_post_meta( $post_id, '_section_headline', true ),
				'text'      => get_post_meta( $post_id, '_section_text', true ),
				'cta_label' => get_post_meta( $post_id, '_cta_label', true ),
				'cta_url'   => get_post_meta( $post_id, '_cta_url', true ),
				'img_1_id'  => absint( get_post_meta( $post_id, '_img_1_id', true ) ),
				'img_2_id'  => absint( get_post_meta( $post_id, '_img_2_id', true ) ),
				'img_3_id'  => absint( get_post_meta( $post_id, '_img_3_id', true ) ),
			);

			$snippet_rel_path = isset( $allowed_layouts[ $layout ] ) ? $allowed_layouts[ $layout ] : $allowed_layouts[ $default_layout ];
			include get_template_directory() . '/' . $snippet_rel_path;
		endwhile;
		wp_reset_postdata();
	else :
		if ( current_user_can( 'edit_posts' ) ) :
			?>
			<p class="mw-small"><?php echo esc_html( 'Kein Home-CPT gefunden. Bitte einen Eintrag im CPT \'home\' anlegen.' ); ?></p>
			<?php
		endif;
	endif;
	?>



<div class="one-img-layout-holder">
    <h3 class="mw-small">Weniger Aufwand. Mehr Zeit.</h3>
    <div class="one-img-layout mw-small">
        <div class="txt-holder">
            <p class="light">KI hilft dabei, wiederkehrende Aufgaben automatisch zu erledigen – zuverlässig und im Hintergrund. Sie unterstützt Ihr Unternehmen dort, wo täglich Zeit verloren geht: E-Mails werden sortiert, Anfragen strukturiert und Informationen direkt weiterverarbeitet. So bleibt mehr Zeit für das, was wirklich wichtig ist.
            </p>
        </div>
        <div class="img-holder">
            <img src="<?php echo esc_url(get_option('weg-zur-website-1')); ?>" alt="">
        </div>
        <div class="bottom-holder">
            <ul>
                <li>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/check.svg' ); ?>" alt="">
                    <p class="light">Weniger manuelle Arbeit im Alltag</p>
                </li>
                <li>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/check.svg' ); ?>" alt="">
                    <p class="light">Ordnung statt Chaos im Posteingang</p>
                </li>
                <li>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/check.svg' ); ?>" alt="">
                    <p class="light">Automatisierte Abläufe rund um die Uhr</p>
                </li>
                <li>
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/check.svg' ); ?>" alt="">
                    <p class="light">Effizientere Prozesse ohne zusätzliches Personal</p>
                </li>
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
                    <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>zu den KI-Lösungen</p>
                </button>
            </ul>
        </div>
    </div>
</div>



</main>





<?php
get_footer();



