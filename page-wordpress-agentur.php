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
$schema_service_name        = 'WordPress Agentur';
$schema_service_description = 'Individuelle WordPress Websites für Unternehmen – mit Fokus auf Performance, einfache Verwaltung und langfristige Erweiterbarkeit.';
$schema_area_served = array(
	array('@type' => 'Place', 'name' => 'Wendland'),
	array('@type' => 'AdministrativeArea', 'name' => 'Landkreis Lüchow-Dannenberg'),
	array('@type' => 'AdministrativeArea', 'name' => 'Lüneburg'),
	array('@type' => 'AdministrativeArea', 'name' => 'Uelzen'),
	array('@type' => 'AdministrativeArea', 'name' => 'Niedersachsen'),
	array('@type' => 'Country', 'name' => 'Deutschland'),
);
$schema_offer_price         = ''; // Optional, z. B. '890.00'.
$schema_offer_currency      = 'EUR'; // Nur relevant, wenn Preis gesetzt ist.
$schema_offer_availability  = 'https://schema.org/InStock'; // Optional bei Offer.

// FAQ nur aktivieren, wenn derselbe Inhalt sichtbar im HTML gerendert wird.
$has_visible_faq_section = true;
$faqs = array(

	array(
		'q' => 'Warum wird WordPress für Websites verwendet?',
		'a' => 'WordPress ist eines der weltweit meistgenutzten Content-Management-Systeme. Es bietet eine flexible Grundlage für Websites und ermöglicht eine einfache Verwaltung von Inhalten wie Texten, Bildern oder neuen Seiten.',
	),

	array(
		'q' => 'Kann ich meine WordPress Website später selbst bearbeiten?',
		'a' => 'Ja, Inhalte wie Texte, Bilder oder neue Seiten können jederzeit selbst angepasst werden. WordPress ermöglicht eine einfache Verwaltung der Website ohne Programmierkenntnisse.',
	),

	array(
		'q' => 'Kann eine WordPress Website später erweitert werden?',
		'a' => 'Ja, WordPress Websites lassen sich jederzeit erweitern. Neue Seiten, Funktionen oder zusätzliche Inhalte können problemlos ergänzt werden, wenn sich Anforderungen oder das Unternehmen weiterentwickeln.',
	),

	array(
		'q' => 'Ist WordPress für Unternehmen geeignet?',
		'a' => 'Ja, WordPress eignet sich sowohl für kleinere Unternehmen als auch für umfangreiche Websites. Durch die flexible Struktur kann das System an unterschiedliche Anforderungen angepasst werden.',
	),

	array(
		'q' => 'Wird meine WordPress Website individuell gestaltet?',
		'a' => 'Ja, wir entwickeln alle Websites individuell. Design, Struktur und Funktionen werden genau auf die Anforderungen deines Unternehmens abgestimmt. So entsteht eine Website, die perfekt zu deinem Unternehmen und deinen Zielen passt.',
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
	 * - Alle Inhaltsmodule kommen aus CPT-Posts vom Typ "wordpress_agentur".
	 * - Layout-/Inhalts-Mapping nutzt bestehende Unterseiten-Felder und faellt auf Standardfelder
	 *   (post_title, post_excerpt, featured_image) zurueck.
	 */
	$heroImgSrc = esc_url( get_option( 'wordpress-agentur' ) );
	$heroTxt    = 'WordPress Agentur'; // Fallback-Text, falls kein Titel gesetzt ist

	if ( '' === (string) $heroTxt ) {
		$heroTxt = 'WordPress Agentur';
	}
	?>

	<?php include_once 'assets/_snippets/hero.php'; ?>

	<?php
	$allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
	$default_layout  = 'two-img-layout';

	$wordpress_agentur_query = new WP_Query(
		array(
			'post_type'      => 'wordpress_agentur',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'order'          => 'ASC',
		)
	);

	if ( $wordpress_agentur_query->have_posts() ) :
		while ( $wordpress_agentur_query->have_posts() ) :
			$wordpress_agentur_query->the_post();
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
		echo "\n<!-- Kein CPT-Inhalt fuer wordpress_agentur vorhanden. -->\n";
	endif;
	?>
</main>

<?php
get_footer();
