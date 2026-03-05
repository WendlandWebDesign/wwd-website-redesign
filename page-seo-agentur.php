<?php
get_header();

$page_id       = get_queried_object_id();
$page_url      = get_permalink( $page_id );
$page_title    = get_the_title( $page_id );
$page_image    = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$excerpt_plain = wp_strip_all_tags( (string) get_the_excerpt( $page_id ) );

if ( empty( $page_image ) && $page_id ) {
	$page_image = get_the_post_thumbnail_url( $page_id, 'full' );
}

if ( '' === trim( $excerpt_plain ) ) {
	$page_content_plain = wp_strip_all_tags( (string) get_post_field( 'post_content', $page_id ) );
	$excerpt_plain      = '' !== trim( $page_content_plain ) ? wp_trim_words( $page_content_plain, 30, '...' ) : '';
}

// Zentral editierbare Service-Daten (nur Plain-Text).
$schema_service_name        = 'SEO Agentur';
$schema_service_description = 'Suchmaschinenoptimierung fuer Unternehmen mit Fokus auf nachhaltige Rankings und qualifizierte Anfragen.';
$schema_area_served = array(
	array('@type' => 'Place', 'name' => 'Wendland'),
	array('@type' => 'AdministrativeArea', 'name' => 'Landkreis Lüchow-Dannenberg'),
	array('@type' => 'AdministrativeArea', 'name' => 'Lüneburg'),
	array('@type' => 'AdministrativeArea', 'name' => 'Uelzen'),
	array('@type' => 'AdministrativeArea', 'name' => 'Niedersachsen'),
	array('@type' => 'Country', 'name' => 'Deutschland'),
);
$schema_offer_price         = ''; // Optional, z. B. '490.00'.
$schema_offer_currency      = 'EUR'; // Nur relevant, wenn Preis gesetzt ist.
$schema_offer_availability  = 'https://schema.org/InStock'; // Optional bei Offer.

// FAQ nur aktivieren, wenn derselbe Inhalt sichtbar im HTML gerendert wird.
$has_visible_faq_section = true;
$faqs = array(

	array(
		'q' => 'Wie lange dauert es, bis SEO Ergebnisse zeigt?',
		'a' => 'Suchmaschinenoptimierung ist eine langfristige Maßnahme. Erste Verbesserungen können oft nach einigen Wochen sichtbar werden, während stabile Rankings meist über mehrere Monate aufgebaut werden.',
	),

	array(
		'q' => 'Warum ist Suchmaschinenoptimierung wichtig?',
		'a' => 'Die meisten Kunden suchen heute online nach Produkten und Dienstleistungen. Eine gute Platzierung bei Google sorgt dafür, dass dein Unternehmen genau dann gefunden wird, wenn potenzielle Kunden nach deinen Leistungen suchen.',
	),

	array(
		'q' => 'Kann ich SEO auch selbst umsetzen?',
		'a' => 'Grundlegende Optimierungen können grundsätzlich selbst vorgenommen werden. Für nachhaltige Ergebnisse ist jedoch eine strategische und technische Umsetzung entscheidend, weshalb viele Unternehmen die Suchmaschinenoptimierung durch eine Agentur durchführen lassen.',
	),

	array(
		'q' => 'Wird meine Website technisch für SEO optimiert?',
		'a' => 'Ja, eine saubere technische Grundlage gehört zu jeder SEO-Strategie. Dazu zählen schnelle Ladezeiten, eine klare Seitenstruktur und eine Optimierung der Inhalte für Suchmaschinen.',
	),

	array(
		'q' => 'Unterstützt ihr auch bei SEO-Inhalten?',
		'a' => 'Ja, hochwertige Inhalte sind ein wichtiger Bestandteil der Suchmaschinenoptimierung. Wir unterstützen dabei, Inhalte so zu strukturieren und zu optimieren, dass sie sowohl für Besucher als auch für Suchmaschinen relevant sind.',
	),

);

$service_schema = array(
	'@type'       => 'Service',
	'name'        => '' !== trim( $schema_service_name ) ? $schema_service_name : $page_title,
	'description' => '' !== trim( $schema_service_description ) ? $schema_service_description : $excerpt_plain,
	'url'         => $page_url,
	'provider'    => array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	),
	'areaServed'  => $schema_area_served,
);

if ( ! empty( $page_image ) ) {
	$service_schema['image'] = array( $page_image );
}

if ( '' !== trim( (string) $schema_offer_price ) ) {
	$service_schema['offers'] = array(
		'@type'         => 'Offer',
		'price'         => (string) $schema_offer_price,
		'priceCurrency' => (string) $schema_offer_currency,
		'availability'  => (string) $schema_offer_availability,
		'url'           => $page_url,
	);
}

$schemas_out = array( $service_schema );

if ( $has_visible_faq_section && ! empty( $faqs ) ) {
	$faq_entities = array();

	foreach ( $faqs as $faq ) {
		$question = isset( $faq['q'] ) ? wp_strip_all_tags( (string) $faq['q'] ) : '';
		$answer   = isset( $faq['a'] ) ? wp_strip_all_tags( (string) $faq['a'] ) : '';

		if ( '' === trim( $question ) || '' === trim( $answer ) ) {
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
		$schemas_out[] = array(
			'@type'      => 'FAQPage',
			'mainEntity' => $faq_entities,
		);
	}
}
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
?>

<main>
	<?php
	/*
	 * Dynamische Bereiche:
	 * - Hero-Headline aus dem Seitentitel, Hero-Bild aus Option "leistungen".
	 * - Alle Inhaltsmodule kommen aus CPT-Posts vom Typ "seo_agentur".
	 * - Layout-/Inhalts-Mapping nutzt bestehende Unterseiten-Felder und faellt auf Standardfelder
	 *   (post_title, post_excerpt, featured_image) zurueck.
	 */
	$heroImgSrc = esc_url( get_option( 'seo-agentur' ) );
	$heroTxt    = 'SEO Agentur'; // Fallback-Text, falls kein Titel gesetzt ist

	if ( '' === (string) $heroTxt ) {
		$heroTxt = 'SEO Agentur';
	}
	?>

	<?php include_once 'assets/_snippets/hero.php'; ?>

	<?php
	$allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
	$default_layout  = 'two-img-layout';

	$seo_query = new WP_Query(
		array(
			'post_type'      => 'seo_agentur',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'order'          => 'ASC',
		)
	);

	if ( $seo_query->have_posts() ) :
		while ( $seo_query->have_posts() ) :
			$seo_query->the_post();
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
		echo "\n<!-- Kein CPT-Inhalt fuer seo_agentur vorhanden. -->\n";
	endif;
	?>
</main>

<?php
get_footer();
