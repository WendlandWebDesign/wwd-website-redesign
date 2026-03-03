<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script>document.documentElement.classList.add('js');</script>

	<?php
	/**
	 * Robots (noindex for search + 404) + Canonical
	 * Hinweis: Meta-Description & OG/Twitter gibst du in functions.php aus (wp_head Hook).
	 */

	$theme_robots_noindex = false;

	if ( is_search() || is_404() ) {
		$theme_robots_noindex = true;

		add_filter(
			'wp_robots',
			static function ( $robots ) {
				if ( ! is_array( $robots ) ) {
					$robots = array();
				}

				unset( $robots['index'] );
				$robots['noindex'] = true;
				$robots['follow']  = true;

				return $robots;
			}
		);
	}

	$canonical = '';
	$paged     = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

	if ( ! is_search() && ! is_404() ) {
		if ( is_singular() && function_exists( 'wp_get_canonical_url' ) ) {
			$canonical = (string) wp_get_canonical_url();
		}

		if ( '' === $canonical ) {
			if ( is_front_page() ) {
				$canonical = home_url( '/' );
			} elseif ( is_home() ) {
				$posts_page_id = (int) get_option( 'page_for_posts' );
				$canonical     = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
			} elseif ( is_singular() ) {
				$canonical = get_permalink();
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$term = get_queried_object();
				if ( $term instanceof WP_Term ) {
					$term_link = get_term_link( $term );
					if ( ! is_wp_error( $term_link ) ) {
						$canonical = $term_link;
					}
				}
			} elseif ( is_post_type_archive() ) {
				$post_type = get_query_var( 'post_type' );
				$post_type = is_array( $post_type ) ? reset( $post_type ) : $post_type;
				if ( is_string( $post_type ) && '' !== $post_type ) {
					$canonical = get_post_type_archive_link( $post_type );
				}
			} elseif ( is_author() ) {
				$author_id = (int) get_query_var( 'author' );
				if ( $author_id > 0 ) {
					$canonical = get_author_posts_url( $author_id );
				}
			} elseif ( is_date() ) {
				if ( is_day() ) {
					$canonical = get_day_link(
						(int) get_query_var( 'year' ),
						(int) get_query_var( 'monthnum' ),
						(int) get_query_var( 'day' )
					);
				} elseif ( is_month() ) {
					$canonical = get_month_link(
						(int) get_query_var( 'year' ),
						(int) get_query_var( 'monthnum' )
					);
				} elseif ( is_year() ) {
					$canonical = get_year_link( (int) get_query_var( 'year' ) );
				}
			}
		}

		if ( $paged > 1 ) {
			$canonical = get_pagenum_link( $paged );
		}
	}

	if ( '' !== $canonical && function_exists( 'theme_normalize_seo_url' ) ) {
		$canonical = theme_normalize_seo_url( $canonical );
	}

	// Canonical nur ausgeben, wenn nicht bereits ein Canonical via wp_head (Plugin/Core) kommt
	if ( ! has_action( 'wp_head', 'rel_canonical' ) && ! empty( $canonical ) ) : ?>
		<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>" />
	<?php endif; ?>

	<?php
	// Robots noindex fallback, falls wp_robots nicht greift (sehr selten)
	if ( $theme_robots_noindex && ! has_action( 'wp_head', 'wp_robots' ) ) : ?>
		<meta name="robots" content="noindex, follow" />
	<?php endif; ?>


	<?php
	/**
	 * Schema.org JSON-LD
	 * - Organization
	 * - WebSite (+ SearchAction)
	 * - BreadcrumbList (wenn vorhanden)
	 * - ProfessionalService (Local Business)
	 */

	$logo_url = get_site_icon_url();
	if ( empty( $logo_url ) && has_custom_logo() ) {
		$custom_logo_id = (int) get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id > 0 ) {
			$custom_logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
			if ( ! empty( $custom_logo_url ) ) {
				$logo_url = $custom_logo_url;
			}
		}
	}

	$same_as_array = array_values(
		array_filter(
			array_map(
				'esc_url_raw',
				array(
					get_option( 'social-instagram', '' ),
					get_option( 'social-facebook', '' ),
					get_option( 'social-linkedin', '' ),
					get_option( 'social-youtube', '' ),
					get_option( 'social-x', '' ),
					get_option( 'social-twitter', '' ),
					get_option( 'social-tiktok', '' ),
					get_option( 'social-xing', '' ),
				)
			)
		)
	);

	$home = home_url( '/' );
	if ( function_exists( 'theme_normalize_seo_url' ) ) {
		$home = theme_normalize_seo_url( $home );
	}

	$data_org = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => $home,
	);

	if ( ! empty( $logo_url ) ) {
		$logo_out = $logo_url;
		if ( function_exists( 'theme_normalize_seo_url' ) ) {
			$logo_out = theme_normalize_seo_url( $logo_out );
		}
		$data_org['logo'] = $logo_out;
	}

	if ( ! empty( $same_as_array ) ) {
		$data_org['sameAs'] = $same_as_array;
	}

	$data_site = array(
		'@type' => 'WebSite',
		'name'  => get_bloginfo( 'name' ),
		'url'   => $home,
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => $home . '?s={search_term_string}',
			'query-input' => 'required name=search_term_string',
		),
	);

	// Local Business / Professional Service
	$data_local = array(
		'@type'      => 'ProfessionalService',
		'name'       => 'Wendland Web Design',
		'url'        => $home,
		'telephone'  => '+4915238976827', // WICHTIG: E.164 Format (+49... ohne führende 0)
		'address'    => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Arendseer Straße 10',
			'addressLocality' => 'Lemgow',
			'postalCode'      => '29485',
			'addressRegion'   => 'Niedersachsen',
			'addressCountry'  => 'DE',
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => '09:00',
				'closes'    => '18:00',
			),
		),
		// GEO: bitte NUR setzen, wenn die Koordinaten wirklich zu deiner Adresse passen.
		// 'geo' => array(
		// 	'@type'     => 'GeoCoordinates',
		// 	'latitude'  => 52.XXXX,
		// 	'longitude' => 11.XXXX,
		// ),
		'areaServed' => array(
			array( '@type' => 'Place', 'name' => 'Wendland' ),
			array( '@type' => 'Place', 'name' => 'Lüchow-Dannenberg' ),
			array( '@type' => 'AdministrativeArea', 'name' => 'Niedersachsen' ),
		),
	);

	if ( ! empty( $logo_url ) ) {
		$img_out = $logo_url;
		if ( function_exists( 'theme_normalize_seo_url' ) ) {
			$img_out = theme_normalize_seo_url( $img_out );
		}
		$data_local['image'] = $img_out;
	}

	if ( ! empty( $same_as_array ) ) {
		$data_local['sameAs'] = $same_as_array;
	}

	$graph_items = array( $data_org, $data_site, $data_local );

	// BreadcrumbList (nur wenn sinnvoll)
	if ( function_exists( 'theme_get_breadcrumb_items' ) ) {
		$breadcrumb_items = theme_get_breadcrumb_items();
		if ( ! is_front_page() && is_array( $breadcrumb_items ) && count( $breadcrumb_items ) > 1 ) {
			$breadcrumb_list = array();
			$position        = 1;

			foreach ( $breadcrumb_items as $breadcrumb_item ) {
				if ( empty( $breadcrumb_item['name'] ) || empty( $breadcrumb_item['url'] ) ) {
					continue;
				}

				$item_url = (string) $breadcrumb_item['url'];
				if ( function_exists( 'theme_normalize_seo_url' ) ) {
					$item_url = theme_normalize_seo_url( $item_url );
				} else {
					$item_url = esc_url_raw( $item_url );
				}

				$breadcrumb_list[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'name'     => wp_strip_all_tags( (string) $breadcrumb_item['name'] ),
					'item'     => $item_url,
				);

				$position++;
			}

			if ( count( $breadcrumb_list ) > 1 ) {
				$graph_items[] = array(
					'@type'           => 'BreadcrumbList',
					'itemListElement' => $breadcrumb_list,
				);
			}
		}
	}

	$schema_output = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph_items,
	);
	?>
	<script type="application/ld+json"><?php echo wp_json_encode( $schema_output, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?></script>

	<?php
	/**
	 * Wichtig: wp_head() möglichst zuletzt im <head>, damit Plugins/WordPress alles sauber einhängen können.
	 */
	wp_head();
	?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page-fade-overlay" aria-hidden="true"></div>

	<?php get_template_part( 'assets/_snippets/nav' ); ?>

	<div class="site-overlay" aria-hidden="true"></div>