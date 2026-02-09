<?php
get_header();
?>



<main>
    <?php
        $heroImgSrc = esc_url(get_option('home-img'));
        $heroTxt = "Unsere Leistungen";

    ?>

    <?php include_once "assets/_snippets/hero.php" ?>

    <div class="dienstleistungen-cards-holder">
        <div class="dienstleistungen-cards mw">
            <div class="dienstleistung-card">
                <div class="icon-holder">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/webentwicklung-blue.svg' ); ?>" alt="">
                </div>
                <p class="light mini-heading">Webentwicklung</p>
                <p>Wir entwickeln Websites individuell und codebasiert – exakt auf dein Projekt abgestimmt. Dabei nutzen wir Wordpress als flexibles Backend, damit Inhalte einfach gepflegt werden können. Das Ergebnis: saubere Technik, starke Performance und volle Kontrolle.</p>
            </div>
            <div class="dienstleistung-card">
                <div class="icon-holder">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/seo-blue.svg' ); ?>" alt="">
                </div>
                <p class="light mini-heading">SEO</p>
                <p>Gutes SEO ist kein Trick, sondern saubere Arbeit. Wir optimieren Technik, Struktur und Inhalte so, dass deine Website sichtbar wird. Auf Wunsch bieten wir auch gezielte SEO-Maßnahmen wie Keyword-Optimierung und Weiterentwicklung an.</p>
            </div>
            <div class="dienstleistung-card">
                <div class="icon-holder">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/wartung-blue.svg' ); ?>" alt="">
                </div>
                <p class="light mini-heading">Wartung</p>
                <p>Eine Website braucht Pflege. Wir übernehmen Wartung, Updates und technische Kontrolle, sorgen für Sicherheit und Stabilität und halten deine Website langfristig auf einem sauberen, zuverlässigen Stand.</p>
            </div>
        </div>
    </div>


	<?php
	$allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
	$default_layout  = 'two-img-layout';

	$dienstleistungen_query = new WP_Query(
		array(
			'post_type'      => 'dienstleistungen',
			// Wenn mehrere Einträge genutzt werden sollen, posts_per_page erhöhen oder auf -1 setzen.
			'posts_per_page' => 1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	if ( $dienstleistungen_query->have_posts() ) :
		while ( $dienstleistungen_query->have_posts() ) :
			$dienstleistungen_query->the_post();
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
			<p class="mw-small"><?php echo esc_html( 'Kein CPT-Eintrag fuer "dienstleistungen" gefunden. Bitte einen Eintrag anlegen.' ); ?></p>
			<?php
		endif;
	endif;
	?>
</main>

<?php
get_footer();
