<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title(); ?></title>
	<meta name="description" content="<?php bloginfo( 'description' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script>document.documentElement.classList.add('js');</script>
	<?php
	$theme_robots_noindex = false;

	if ( is_search() || is_404() ) {
		$theme_robots_noindex = true;

		if ( function_exists( 'add_filter' ) ) {
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
					$canonical = get_day_link( (int) get_query_var( 'year' ), (int) get_query_var( 'monthnum' ), (int) get_query_var( 'day' ) );
				} elseif ( is_month() ) {
					$canonical = get_month_link( (int) get_query_var( 'year' ), (int) get_query_var( 'monthnum' ) );
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

	if ( ! ( is_singular() && has_action( 'wp_head', 'rel_canonical' ) ) && ! empty( $canonical ) ) {
		?>
		<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>" />
		<?php
	}

	if ( $theme_robots_noindex && ! has_action( 'wp_head', 'wp_robots' ) ) {
		?>
		<meta name="robots" content="noindex, follow" />
		<?php
	}
	?>

	<?php wp_head(); ?>
	<?php
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

	$data_org = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => function_exists( 'theme_normalize_seo_url' ) ? theme_normalize_seo_url( home_url( '/' ) ) : home_url( '/' ),
	);

	if ( ! empty( $logo_url ) ) {
		$data_org['logo'] = function_exists( 'theme_normalize_seo_url' ) ? theme_normalize_seo_url( $logo_url ) : $logo_url;
	}

	if ( ! empty( $same_as_array ) ) {
		$data_org['sameAs'] = $same_as_array;
	}

	$data_site = array(
		'@type' => 'WebSite',
		'name'  => get_bloginfo( 'name' ),
		'url'   => function_exists( 'theme_normalize_seo_url' ) ? theme_normalize_seo_url( home_url( '/' ) ) : home_url( '/' ),
	);

	$data_site['potentialAction'] = array(
		'@type'       => 'SearchAction',
		'target'      => function_exists( 'theme_normalize_seo_url' ) ? theme_normalize_seo_url( home_url( '/?s={search_term_string}' ) ) : home_url( '/?s={search_term_string}' ),
		'query-input' => 'required name=search_term_string',
	);

	$graph_items = array( $data_org, $data_site );

	if ( function_exists( 'theme_get_breadcrumb_items' ) ) {
		$breadcrumb_items = theme_get_breadcrumb_items();
		if ( ! is_front_page() && count( $breadcrumb_items ) > 1 ) {
			$breadcrumb_list = array();
			$position        = 1;

			foreach ( $breadcrumb_items as $breadcrumb_item ) {
				if ( empty( $breadcrumb_item['name'] ) || empty( $breadcrumb_item['url'] ) ) {
					continue;
				}

				$breadcrumb_list[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'name'     => wp_strip_all_tags( (string) $breadcrumb_item['name'] ),
					'item'     => function_exists( 'theme_normalize_seo_url' ) ? theme_normalize_seo_url( (string) $breadcrumb_item['url'] ) : esc_url_raw( (string) $breadcrumb_item['url'] ),
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
</head>
<body <?php body_class(); ?>>
	<div id="page-fade-overlay" aria-hidden="true"></div>
<?php wp_body_open(); ?>
	<?php
	get_template_part( 'assets/_snippets/nav' );
	?>
	<div class="site-overlay" aria-hidden="true"></div>
