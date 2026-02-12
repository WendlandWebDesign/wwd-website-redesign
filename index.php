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
                <span class="hero-line hero-line--1">Professionelles <span class="ac">Webdesign.</span></span>
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
            <button onclick="window.location.href='<?php echo esc_url( home_url( '/kontakt/' ) ); ?>'" class="btn light" data-hero-snake-load="1">
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
			<p class="mw-small"><?php echo esc_html( 'Seite nicht gefunden' ); ?></p>
			<?php
		endif;
	endif;
	?>

</main>

<div class="website-weg-holder">
    <h3 class="mw">Der Weg zu deiner Website</h3>
    <div class="website-weg-wrapper">
        <div class="website-weg">
            <div class="website-weg__media">
                <div class="img-transition-top"></div>
                <div class="img-transition-bottom"></div>
            </div>
            <div class="website-weg__overlay">
                <div class="txt-holder">
                    <p class="light mini-heading">Kostenloses Erstgespräch</p>
                    <p class="light">Alles beginnt mit einem entspannten Kennenlernen. Wir analysieren deine bestehende Website, sprechen über mögliche Probleme und zeigen ungenutzte Chancen auf. Gemeinsam definieren wir das Ziel deiner neuen Website und den Weg dorthin.</p>
                </div>
                <div class="txt-holder">
                    <p class="light mini-heading">Konzept & Design</p>
                    <p class="light">Auf Basis dieser Ziele entwickeln wir ein durchdachtes Konzept und ein modernes Design. Struktur, Nutzerführung und Inhalte werden von Anfang an sauber geplant – damit alles logisch aufgebaut und zukunftssicher ist.</p>
                </div>
                <div class="txt-holder">
                    <p class="light mini-heading">Custom Entwicklung mit Fokus auf Performance</p>
                    <p class="light">Nach deiner Freigabe setzen wir das Design individuell und codebasiert um. Performance, Technik und Details stehen dabei im Fokus. Die Inhalte können später bei Bedarf über ein CMS selbst gepflegt werden, ohne die technische Basis anzutasten.</p>
                </div>
                <div class="txt-holder">
                    <p class="light mini-heading">Hosting & Launch</p>
                    <p class="light">Nach der Fertigstellung übernehmen wir das Hosting und bringen deine Website online. Stabil, sicher und sauber aufgesetzt – damit deine Website nicht nur startet, sondern langfristig zuverlässig läuft.</p>
                </div>
            </div>
        </div>
    </div>
</div>





<main>

</main>





<?php
get_footer();





