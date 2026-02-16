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

	$home_query_args = array(
		'post_type'      => 'home',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	$render_home_box = static function( $post_id, $allowed_layouts, $default_layout ) {
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

		$snippet_rel_path = isset( $allowed_layouts[ $layout ] ) ? $allowed_layouts[ $layout ] : $allowed_layouts[ $default_layout ];
		include get_template_directory() . '/' . $snippet_rel_path;
	};

	$letzte_box_query_args                   = $home_query_args;
	$letzte_box_query_args['posts_per_page'] = 1;
	$letzte_box_query_args['meta_query']     = array(
		array(
			'key'     => 'letzte_box',
			'value'   => '1',
			'compare' => '=',
		),
	);
	$letzte_box_query                        = new WP_Query( $letzte_box_query_args );
	$has_letzte_box                          = $letzte_box_query->have_posts();

	$home_query_args['meta_query'] = array(
		'relation' => 'OR',
		array(
			'key'     => 'letzte_box',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => 'letzte_box',
			'value'   => '1',
			'compare' => '!=',
		),
	);
	$home_query                  = new WP_Query( $home_query_args );

	if ( $home_query->have_posts() ) :
		while ( $home_query->have_posts() ) :
			$home_query->the_post();
			$render_home_box( get_the_ID(), $allowed_layouts, $default_layout );
		endwhile;
		wp_reset_postdata();
	elseif ( ! $has_letzte_box && current_user_can( 'edit_posts' ) ) :
		?>
		<p class="mw-small"><?php echo esc_html( 'Seite nicht gefunden' ); ?></p>
		<?php
	endif;
	?>

</main>

<?php
$website_weg_items = array();

$website_weg_query = new WP_Query(
	array(
		'post_type'      => 'website_weg',
		'posts_per_page' => 4,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);

if ( $website_weg_query->have_posts() ) {
	$website_weg_items = $website_weg_query->posts;
}
wp_reset_postdata();
?>

<div class="website-weg-holder">
    <h3 class="mw">Der Weg zu deiner Website</h3>
    <div class="website-weg-wrapper">
        <div class="website-weg">
            <svg class="website-weg__connector" aria-hidden="true" focusable="false"></svg>
            <div class="website-weg__media">

            </div>
            <div class="website-weg__overlay">
                <div class="txt-holder">
                    <?php
                    $website_weg_post = isset( $website_weg_items[0] ) && $website_weg_items[0] instanceof WP_Post ? $website_weg_items[0] : null;
                    $website_weg_title = 'Kostenloses Erstgespräch';
                    $website_weg_content = 'Alles beginnt mit einem entspannten Kennenlernen. Wir analysieren deine bestehende Website, sprechen über mögliche Probleme und zeigen ungenutzte Chancen auf. Gemeinsam definieren wir das Ziel deiner neuen Website und den Weg dorthin.';

                    if ( $website_weg_post ) {
                        $website_weg_title = get_the_title( $website_weg_post );
                        $website_weg_content = apply_filters( 'the_content', $website_weg_post->post_content );
                    }
                    ?>
                    <p class="dark mini-heading"><span class="mini-heading__anchor" aria-hidden="true"></span><?php echo esc_html( $website_weg_title ); ?></p>
                    <p class="dark"><?php echo wp_kses_post( $website_weg_content ); ?></p>
                </div>
                <div class="txt-holder">
                    <?php
                    $website_weg_post = isset( $website_weg_items[1] ) && $website_weg_items[1] instanceof WP_Post ? $website_weg_items[1] : null;
                    $website_weg_title = 'Konzept & Design';
                    $website_weg_content = 'Auf Basis dieser Ziele entwickeln wir ein durchdachtes Konzept und ein modernes Design. Struktur, Nutzerführung und Inhalte werden von Anfang an sauber geplant – damit alles logisch aufgebaut und zukunftssicher ist.';

                    if ( $website_weg_post ) {
                        $website_weg_title = get_the_title( $website_weg_post );
                        $website_weg_content = apply_filters( 'the_content', $website_weg_post->post_content );
                    }
                    ?>
                    <p class="dark mini-heading"><span class="mini-heading__anchor" aria-hidden="true"></span><?php echo esc_html( $website_weg_title ); ?></p>
                    <p class="dark"><?php echo wp_kses_post( $website_weg_content ); ?></p>
                </div>
                <div class="txt-holder">
                    <?php
                    $website_weg_post = isset( $website_weg_items[2] ) && $website_weg_items[2] instanceof WP_Post ? $website_weg_items[2] : null;
                    $website_weg_title = 'Custom Entwicklung mit Fokus auf Performance';
                    $website_weg_content = 'Nach deiner Freigabe setzen wir das Design individuell und codebasiert um. Performance, Technik und Details stehen dabei im Fokus. Die Inhalte können später bei Bedarf über ein CMS selbst gepflegt werden, ohne die technische Basis anzutasten.';

                    if ( $website_weg_post ) {
                        $website_weg_title = get_the_title( $website_weg_post );
                        $website_weg_content = apply_filters( 'the_content', $website_weg_post->post_content );
                    }
                    ?>
                    <p class="dark mini-heading"><span class="mini-heading__anchor" aria-hidden="true"></span><?php echo esc_html( $website_weg_title ); ?></p>
                    <p class="dark"><?php echo wp_kses_post( $website_weg_content ); ?></p>
                </div>
                <div class="txt-holder">
                    <?php
                    $website_weg_post = isset( $website_weg_items[3] ) && $website_weg_items[3] instanceof WP_Post ? $website_weg_items[3] : null;
                    $website_weg_title = 'Hosting & Launch';
                    $website_weg_content = 'Nach der Fertigstellung übernehmen wir das Hosting und bringen deine Website online. Stabil, sicher und sauber aufgesetzt – damit deine Website nicht nur startet, sondern langfristig zuverlässig läuft.';

                    if ( $website_weg_post ) {
                        $website_weg_title = get_the_title( $website_weg_post );
                        $website_weg_content = apply_filters( 'the_content', $website_weg_post->post_content );
                    }
                    ?>
                    <p class="dark mini-heading"><span class="mini-heading__anchor" aria-hidden="true"></span><?php echo esc_html( $website_weg_title ); ?></p>
                    <p class="dark"><?php echo wp_kses_post( $website_weg_content ); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>





<main>
	<?php
	if ( $has_letzte_box ) :
		while ( $letzte_box_query->have_posts() ) :
			$letzte_box_query->the_post();
			$render_home_box( get_the_ID(), $allowed_layouts, $default_layout );
		endwhile;
		wp_reset_postdata();
	endif;
	?>
</main>





<?php
get_footer();
?>
