<?php
get_header();
?>


<main>


    <div class="home-hero observe-nav">
        <img class="hero-img" src="<?php echo esc_url(get_option('home-img')); ?>" alt="hero image">
        <div class="home-hero-inner mw">
            <h2 class="light">Gutes Webdesign.<br>Klar umgesetzt.</h2>
            <h1 class="light"><span>W</span>endland <span>W</span>eb <span>D</span>esign</h1>
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







</main>





<?php
get_footer();



