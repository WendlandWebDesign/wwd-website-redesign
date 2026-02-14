<?php
get_header();
?>



<main>
    <?php
        $heroImgSrc = esc_url(get_option('leistungen'));
        $heroTxt = "Unsere Leistungen";

    ?>

    <?php include_once "assets/_snippets/hero.php" ?>




	<?php
	$allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
	$default_layout  = 'two-img-layout';

	$dienstleistungen_query = new WP_Query(
		array(
			'post_type'      => 'dienstleistungen',
			// Wenn mehrere Einträge genutzt werden sollen, posts_per_page erhöhen oder auf -1 setzen.
			'posts_per_page' => -1,
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
