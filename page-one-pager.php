<?php
get_header();
if ( is_page_template( 'page-one-pager.php' ) ) {
	// Hardcoded Offer-Werte (zentral editierbar).
	$offer_price        = '890.00';
	$offer_currency     = 'EUR';
	$offer_availability = 'https://schema.org/InStock';
	$offer_condition    = 'https://schema.org/NewCondition';
	$offer_description  = 'Professioneller One Pager zur Aufwertung ihrer Online-Präsenz - Ab 890€.';
	$offer_valid_from   = '2026-03-05T00:00:00+01:00';

	$page_id       = get_queried_object_id();
	$offer_url     = get_permalink( $page_id );
	$offer_name    = get_the_title( $page_id );
	$offer_image   = get_the_post_thumbnail_url( $page_id, 'full' );
	$excerpt_plain = wp_strip_all_tags( (string) get_the_excerpt( $page_id ) );
	$description   = '' !== trim( $offer_description ) ? $offer_description : $excerpt_plain;

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Offer',
		'url'           => $offer_url,
		'name'          => $offer_name,
		'description'   => $description,
		'price'         => $offer_price,
		'priceCurrency' => $offer_currency,
		'availability'  => $offer_availability,
		'itemCondition' => $offer_condition,
		'seller'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		),
	);

	if ( ! empty( $offer_image ) ) {
		$schema['image'] = $offer_image;
	}

	if ( ! empty( $offer_valid_from ) ) {
		$schema['validFrom'] = $offer_valid_from;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
}
?>

<main>
	<?php
	/*
	 * Dynamische Bereiche:
	 * - Hero-Headline aus dem Seitentitel, Hero-Bild aus Option "leistungen".
	 * - Alle Inhaltsmodule kommen aus CPT-Posts vom Typ "one_pager".
	 * - Layout-/Inhalts-Mapping nutzt bestehende Unterseiten-Felder und faellt auf Standardfelder
	 *   (post_title, post_excerpt, featured_image) zurueck.
	 */
	$heroImgSrc = esc_url( get_option( 'one-pager' ) );
	$heroTxt    = 'One Pager'; // Fallback-Text, falls kein Titel gesetzt ist

	if ( '' === (string) $heroTxt ) {
		$heroTxt = 'One Pager';
	}
	?>

	<?php include_once 'assets/_snippets/hero.php'; ?>

	<?php
	$allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
	$default_layout  = 'two-img-layout';

	$one_pager_query = new WP_Query(
		array(
			'post_type'      => 'one_pager',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'order'          => 'ASC',
		)
	);

	if ( $one_pager_query->have_posts() ) :
		while ( $one_pager_query->have_posts() ) :
			$one_pager_query->the_post();
			$post_id = get_the_ID();
			$layout  = get_post_meta( $post_id, '_layout_template', true );

			if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
				$layout = $default_layout;
			}

			$section_headline = get_post_meta( $post_id, '_section_headline', true );
			if ( '' === trim( (string) $section_headline ) ) {
				$section_headline = get_the_title( $post_id );
			}

			$section_text = get_post_meta( $post_id, '_section_text', true );
			if ( '' === trim( (string) $section_text ) ) {
				$section_text = get_the_excerpt( $post_id );
			}
			if ( '' === trim( (string) $section_text ) ) {
				$plain_content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
				$section_text  = '' !== trim( $plain_content ) ? wp_trim_words( $plain_content, 40, '...' ) : '';
			}

			$img_1_id = absint( get_post_meta( $post_id, '_img_1_id', true ) );
			if ( 0 === $img_1_id ) {
				$img_1_id = (int) get_post_thumbnail_id( $post_id );
			}

			$meta = array(
				'mini_heading' => get_post_meta( $post_id, '_section_mini_heading', true ),
				'headline'     => $section_headline,
				'text'         => $section_text,
				'cta_label'    => get_post_meta( $post_id, '_cta_label', true ),
				'cta_url'      => get_post_meta( $post_id, '_cta_url', true ),
				'img_1_id'     => $img_1_id,
				'img_2_id'     => absint( get_post_meta( $post_id, '_img_2_id', true ) ),
				'img_3_id'     => absint( get_post_meta( $post_id, '_img_3_id', true ) ),
			);

			$snippet_rel_path = isset( $allowed_layouts[ $layout ] ) ? $allowed_layouts[ $layout ] : $allowed_layouts[ $default_layout ];
			include get_template_directory() . '/' . $snippet_rel_path;
		endwhile;
		wp_reset_postdata();
	else :
		echo "\n<!-- Kein CPT-Inhalt fuer one_pager vorhanden. -->\n";
	endif;
	?>
</main>

<?php
get_footer();

