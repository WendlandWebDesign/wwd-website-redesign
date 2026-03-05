<?php
get_header();
if ( is_page_template( 'page-one-pager.php' ) ) {
	$page_id          = get_queried_object_id();
	$page_url         = $page_id ? (string) get_permalink( $page_id ) : '';
	$page_title       = $page_id ? wp_strip_all_tags( (string) get_the_title( $page_id ) ) : '';
	$page_excerpt_raw = $page_id ? (string) get_the_excerpt( $page_id ) : '';
	$page_excerpt     = trim( wp_strip_all_tags( $page_excerpt_raw ) );
	$page_image_url   = $page_id ? (string) get_the_post_thumbnail_url( $page_id, 'full' ) : '';
	$site_name        = trim( wp_strip_all_tags( (string) get_bloginfo( 'name' ) ) );
	$site_url         = (string) home_url( '/' );

	if ( '' === $page_excerpt && $page_id ) {
		$page_content_plain = wp_strip_all_tags( (string) get_post_field( 'post_content', $page_id ) );
		$page_excerpt       = '' !== trim( $page_content_plain ) ? wp_trim_words( $page_content_plain, 30, '...' ) : '';
	}

	/*
	 * JSON-LD: zentral editierbare Variablen fuer One-Pager.
	 * Nur Plain-Text pflegen (kein HTML).
	 */
	$service_name_edit        = 'One Pager';
	$service_description_edit = '';
	$schema_area_served = array(
							array('@type' => 'Place', 'name' => 'Wendland'),
							array('@type' => 'AdministrativeArea', 'name' => 'Landkreis Lüchow-Dannenberg'),
							array('@type' => 'AdministrativeArea', 'name' => 'Niedersachsen'),
							);
	$offer_name_edit        = '';
	$offer_description_edit = 'Professioneller One Pager zur Aufwertung Ihrer Online-Praesenz - ab 890 EUR.';
	$offer_price            = '890.00'; // "Ab"-Preis.
	$offer_currency         = 'EUR';
	$offer_availability     = 'https://schema.org/InStock';
	$offer_condition        = 'https://schema.org/NewCondition';
	$offer_valid_from       = ''; // Optional ISO 8601, z. B. '2026-03-05T00:00:00+01:00'.

	// FAQ nur auf true setzen, wenn dieselben FAQs sichtbar im Seiteninhalt ausgegeben werden.
	$has_visible_faq_section = true;
	$faqs = array(

	array(
		'q' => 'Was ist ein Onepager?',
		'a' => 'Ein Onepager ist eine Website, bei der alle Inhalte auf einer einzigen Seite dargestellt werden. Besucher navigieren durch Scrollen oder über Ankerpunkte zu den einzelnen Abschnitten wie Leistungen, Informationen oder Kontakt.',
	),

	array(
		'q' => 'Für wen eignet sich ein Onepager?',
		'a' => 'Ein Onepager eignet sich besonders für kleinere Unternehmen, Selbstständige oder Projekte mit überschaubarem Inhalt. Wenn nur wenige Leistungen oder Informationen präsentiert werden sollen, bietet ein Onepager eine kompakte und übersichtliche Lösung.',
	),

	array(
		'q' => 'Was unterscheidet einen Onepager von einer individuellen Website?',
		'a' => 'Ein Onepager besteht aus einer einzelnen Seite mit klar strukturierten Abschnitten. Eine individuelle Website hingegen umfasst mehrere Unterseiten und bietet mehr Platz für Inhalte, Leistungen oder komplexere Strukturen.',
	),

	array(
		'q' => 'Kann ein Onepager später erweitert werden?',
		'a' => 'Ja, ein Onepager kann jederzeit erweitert werden. Wenn dein Unternehmen wächst oder mehr Inhalte benötigt werden, kann die Website später problemlos zu einer klassischen Website mit mehreren Unterseiten ausgebaut werden.',
	),

	array(
		'q' => 'Ist ein Onepager auch für Google geeignet?',
		'a' => 'Auch Onepager können für Suchmaschinen optimiert werden. Allerdings bietet eine Website mit mehreren Unterseiten meist mehr Möglichkeiten für umfangreiche Inhalte und Suchmaschinenoptimierung.',
	),

);

	$service_name        = '' !== trim( $service_name_edit ) ? wp_strip_all_tags( (string) $service_name_edit ) : $page_title;
	$service_description = '' !== trim( $service_description_edit ) ? wp_strip_all_tags( (string) $service_description_edit ) : $page_excerpt;
	$offer_name          = '' !== trim( $offer_name_edit ) ? wp_strip_all_tags( (string) $offer_name_edit ) : $page_title;
	$offer_description   = '' !== trim( $offer_description_edit ) ? wp_strip_all_tags( (string) $offer_description_edit ) : $page_excerpt;

	$service_schema = array(
		'@type'       => 'Service',
		'name'        => $service_name,
		'description' => $service_description,
		'url'         => $page_url,
		'provider'    => array(
			'@type' => 'Organization',
			'name'  => $site_name,
			'url'   => $site_url,
		),
	);

	if ( is_array( $service_area_served ) ) {
		$area_served_clean = array_values(
			array_filter(
				array_map(
					static function ( $item ) {
						return trim( wp_strip_all_tags( (string) $item ) );
					},
					$service_area_served
				)
			)
		);
		if ( ! empty( $area_served_clean ) ) {
			$service_schema['areaServed'] = $area_served_clean;
		}
	} else {
		$area_served_clean = trim( wp_strip_all_tags( (string) $service_area_served ) );
		if ( '' !== $area_served_clean ) {
			$service_schema['areaServed'] = $area_served_clean;
		}
	}

	if ( '' !== trim( $page_image_url ) ) {
		$service_schema['image'] = array( $page_image_url );
	}

	if ( '' !== trim( (string) $offer_price ) ) {
		$service_offer = array(
			'@type'         => 'Offer',
			'price'         => (string) $offer_price,
			'priceCurrency' => '' !== trim( (string) $offer_currency ) ? (string) $offer_currency : 'EUR',
			'url'           => $page_url,
		);
		if ( '' !== trim( (string) $offer_availability ) ) {
			$service_offer['availability'] = (string) $offer_availability;
		}
		$service_schema['offers'] = $service_offer;
	}

	$offer_schema = array(
		'@type'    => 'Offer',
		'url'      => $page_url,
		'name'     => $offer_name,
		'seller'   => array(
			'@type' => 'Organization',
			'name'  => $site_name,
		),
	);

	if ( '' !== $offer_description ) {
		$offer_schema['description'] = $offer_description;
	}
	if ( '' !== trim( $page_image_url ) ) {
		$offer_schema['image'] = array( $page_image_url );
	}
	if ( '' !== trim( (string) $offer_price ) ) {
		$offer_schema['price']         = (string) $offer_price;
		$offer_schema['priceCurrency'] = '' !== trim( (string) $offer_currency ) ? (string) $offer_currency : 'EUR';
	}
	if ( '' !== trim( (string) $offer_availability ) ) {
		$offer_schema['availability'] = (string) $offer_availability;
	}
	if ( '' !== trim( (string) $offer_condition ) ) {
		$offer_schema['itemCondition'] = (string) $offer_condition;
	}
	if ( '' !== trim( (string) $offer_valid_from ) ) {
		$offer_schema['validFrom'] = (string) $offer_valid_from;
	}

	$json_ld_graph = array();

	if ( '' !== trim( (string) $offer_schema['name'] ) && '' !== trim( (string) $offer_schema['url'] ) ) {
		$json_ld_graph[] = $offer_schema;
	}
	if ( '' !== trim( (string) $service_schema['name'] ) && '' !== trim( (string) $service_schema['url'] ) ) {
		$json_ld_graph[] = $service_schema;
	}

	if ( $has_visible_faq_section && ! empty( $faqs ) ) {
		$faq_entities = array();

		foreach ( $faqs as $faq ) {
			$question = isset( $faq['q'] ) ? trim( wp_strip_all_tags( (string) $faq['q'] ) ) : '';
			$answer   = isset( $faq['a'] ) ? trim( wp_strip_all_tags( (string) $faq['a'] ) ) : '';

			if ( '' === $question || '' === $answer ) {
				continue;
			}

			$faq_entities[] = array(
				'@type'          => 'Question',
				'name'           => $question,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $answer,
				),
			);
		}

		if ( ! empty( $faq_entities ) ) {
			$json_ld_graph[] = array(
				'@type'      => 'FAQPage',
				'mainEntity' => $faq_entities,
			);
		}
	}

	if ( ! empty( $json_ld_graph ) ) {
		?>
<script type="application/ld+json">
<?php
$output = array(
	'@context' => 'https://schema.org',
	'@graph'   => $json_ld_graph,
);

echo wp_json_encode( $output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
?>
</script>
		<?php
	}
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

