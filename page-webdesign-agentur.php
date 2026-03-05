<?php
get_header();

$page_id           = get_queried_object_id();
$page_url          = get_permalink( $page_id );
$page_title        = get_the_title( $page_id );
$page_excerpt_raw  = get_the_excerpt( $page_id );
$page_excerpt      = wp_strip_all_tags( (string) $page_excerpt_raw );
$page_image_url    = get_the_post_thumbnail_url( $page_id, 'full' );
$site_name         = get_bloginfo( 'name' );
$site_url          = home_url( '/' );

if ( '' === trim( $page_excerpt ) ) {
	$page_content_plain = wp_strip_all_tags( (string) get_post_field( 'post_content', $page_id ) );
	$page_excerpt       = '' !== trim( $page_content_plain ) ? wp_trim_words( $page_content_plain, 30, '...' ) : '';
}

/*
 * JSON-LD: zentral editierbare Variablen fuer diese Landingpage.
 * Nur Plain-Text pflegen (kein HTML).
 */
$schema_service_name        = 'Webdesign & Webentwicklung';
$schema_service_description = 'Individuelles Webdesign für Unternehmen – konzipiert für Performance, Conversion und einfache Pflege mit WordPress.';
$schema_area_served = array(
						array('@type' => 'Place', 'name' => 'Wendland'),
						array('@type' => 'AdministrativeArea', 'name' => 'Landkreis Lüchow-Dannenberg'),
						array('@type' => 'AdministrativeArea', 'name' => 'Niedersachsen'),
						);
$schema_offer_price         = ''; // Optional, z.B. '1490.00' (nur Zahlenformat als String).
$schema_offer_currency      = 'EUR'; // Nur relevant, wenn Preis gesetzt ist.

// FAQ nur setzen, wenn derselbe Inhalt sichtbar im HTML ausgegeben wird.
$has_visible_faq_section = true;
$faqs                    = array(
	array(
		'q' => 'Wie lange dauert die Erstellung einer Website?',
		'a' => 'Die Dauer hängt vom Umfang der Website ab. Mittelgroße Websites für lokale Unternehmen können meist innerhalb von 2 bis 3 Wochen umgesetzt werden – vom Vertragsabschluss bis zur Veröffentlichung. Voraussetzung ist, dass Inhalte wie Texte, Bilder und Informationen rechtzeitig bereitgestellt werden.',
	),

	array(
		'q' => 'Was kostet eine professionelle Website?',
		'a' => 'Die Kosten hängen vom Umfang, Design und den gewünschten Funktionen ab. Individuelle Websites werden genau auf die Anforderungen eines Unternehmens abgestimmt. Gerne besprechen wir dein Projekt unverbindlich und erstellen anschließend ein passendes Angebot.',
	),

	array(
		'q' => 'Wird meine Website auch für Google optimiert?',
		'a' => 'Ja, jede Website wird technisch sauber aufgebaut und erhält eine grundlegende Suchmaschinenoptimierung. Dazu gehören eine klare Seitenstruktur, schnelle Ladezeiten und eine saubere technische Umsetzung.',
	),

	array(
		'q' => 'Kann ich meine Website später selbst bearbeiten?',
		'a' => 'Ja. Unsere Websites basieren auf WordPress. Dadurch können Inhalte wie Texte, Bilder oder neue Seiten jederzeit selbst bearbeitet und aktualisiert werden – ganz ohne Programmierkenntnisse.',
	),

	array(
		'q' => 'Bietet ihr auch Wartung und Betreuung für Websites an?',
		'a' => 'Ja, wir bieten Wartungspakete mit Updates, Sicherheitsprüfungen und regelmäßigen Backups an. Inhalte können grundsätzlich auch selbst gepflegt werden, die technische Wartung übernehmen jedoch meist wir, damit die Website dauerhaft stabil und sicher bleibt.',
	),
);

$service_schema = array(
	'@type'       => 'Service',
	'@id'         => $page_url . '#service',
	'name'        => '' !== trim( $schema_service_name ) ? $schema_service_name : $page_title,
	'description' => '' !== trim( $schema_service_description ) ? $schema_service_description : $page_excerpt,
	'url'         => $page_url,
	'provider'    => array(
		'@type' => 'Organization',
		'@id'   => $site_url . '#organization',
		'name'  => $site_name,
		'url'   => $site_url,
	),
	'areaServed'  => $schema_area_served,
	'serviceType' => 'Webdesign'
);

if ( ! empty( $page_image_url ) ) {
	$service_schema['image'] = $page_image_url;
}

if ( '' !== trim( (string) $schema_offer_price ) ) {
	$service_schema['offers'] = array(
		'@type'         => 'Offer',
		'price'         => (string) $schema_offer_price,
		'priceCurrency' => (string) $schema_offer_currency,
		'url'           => $page_url,
	);
}

$webpage_schema = array(
	'@type'       => 'WebPage',
	'@id'         => $page_url . '#webpage',
	'name'        => $page_title,
	'url'         => $page_url,
	'description' => $page_excerpt,
	'isPartOf'    => array(
		'@type' => 'WebSite',
		'@id'   => $site_url . '#website',
		'name'  => $site_name,
		'url'   => $site_url,
	),
	'mainEntity'  => array(
		'@id' => $page_url . '#service'
	),
);

if ( ! empty( $page_image_url ) ) {
	$webpage_schema['primaryImageOfPage'] = array(
		'@type' => 'ImageObject',
		'url'   => $page_image_url,
	);
}

$json_ld_graph = array( $service_schema, $webpage_schema );

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
		$json_ld_graph[] = array(
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
	 * - Alle Inhaltsmodule kommen aus CPT-Posts vom Typ "webdesign_agentur".
	 * - Layout-/Inhalts-Mapping nutzt bestehende Unterseiten-Felder und faellt auf Standardfelder
	 *   (post_title, post_excerpt, featured_image) zurueck.
	 */
	$heroImgSrc = esc_url( get_option( 'web-agentur' ) );
	$heroTxt    = 'Webdesign Agentur'; // Fallback-Text, falls kein Titel gesetzt ist

	if ( '' === (string) $heroTxt ) {
		$heroTxt = 'Webdesign Agentur';
	}
	?>

	<?php include_once 'assets/_snippets/hero.php'; ?>

	<?php
	$allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
	$default_layout  = 'two-img-layout';

	$webdesign_agentur_query = new WP_Query(
		array(
			'post_type'      => 'webdesign_agentur',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'order'          => 'ASC',
		)
	);

	if ( $webdesign_agentur_query->have_posts() ) :
		while ( $webdesign_agentur_query->have_posts() ) :
			$webdesign_agentur_query->the_post();
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
		echo "\n<!-- Kein CPT-Inhalt fuer webdesign_agentur vorhanden. -->\n";
	endif;
	?>
</main>

<?php
get_footer();
