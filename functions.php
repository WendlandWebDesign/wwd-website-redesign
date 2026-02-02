
<?php
// Hero Bilder Ã¼ber esc_url(get_option(''));
/**
 * Theme Supports
 */
function auto_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
}add_action('after_setup_theme','auto_theme_support');

function allow_svg($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg');



function wwd_website_redesign_enqueue_assets() {

	// CSS-Dateien
	$css_fonts    = 'assets/css/fonts.css';
	$css_base     = 'assets/css/base.css';
	$css_nav      = 'assets/css/nav.css';
	$css_sections = 'assets/css/sections.css';
    $css_home     = 'assets/css/home.css';

	// JS-Dateien
	$js_base       = 'assets/js/base.js';
	$js_animations = 'assets/js/animations.js';
	$js_gsap       = 'assets/js/node_modules/gsap/dist/gsap.min.js';
	$js_scrolltrigger = 'assets/js/node_modules/gsap/dist/ScrollTrigger.min.js';

	/**
	 * CSS einbinden
	 */
	wp_enqueue_style(
		'wwd-website-redesign-fonts',
		get_theme_file_uri( $css_fonts ),
		array(),
		file_exists( get_theme_file_path( $css_fonts ) ) ? filemtime( get_theme_file_path( $css_fonts ) ) : null
	);

	wp_enqueue_style(
		'wwd-website-redesign-base',
		get_theme_file_uri( $css_base ),
		array( 'wwd-website-redesign-fonts' ),
		file_exists( get_theme_file_path( $css_base ) ) ? filemtime( get_theme_file_path( $css_base ) ) : null
	);

	wp_enqueue_style(
		'wwd-website-redesign-nav',
		get_theme_file_uri( $css_nav ),
		array( 'wwd-website-redesign-base' ),
		file_exists( get_theme_file_path( $css_nav ) ) ? filemtime( get_theme_file_path( $css_nav ) ) : null
	);

	wp_enqueue_style(
		'wwd-website-redesign-sections',
		get_theme_file_uri( $css_sections ),
		array( 'wwd-website-redesign-base' ),
		file_exists( get_theme_file_path( $css_sections ) ) ? filemtime( get_theme_file_path( $css_sections ) ) : null
	);

    wp_enqueue_style(
        'wwd-website-redesign-home',
        get_theme_file_uri( $css_home ),
        array( 'wwd-website-redesign-base' ),
        file_exists( get_theme_file_path( $css_home ) ) ? filemtime( get_theme_file_path( $css_home ) ) : null
    );

	/**
	 * JS einbinden
	 * â†’ falls base.js KEIN jQuery nutzt, einfach 'jquery' entfernen
	 */
	if ( file_exists( get_theme_file_path( $js_gsap ) ) ) {
		wp_enqueue_script(
			'wwd-website-redesign-gsap',
			get_theme_file_uri( $js_gsap ),
			array(),
			filemtime( get_theme_file_path( $js_gsap ) ),
			true
		);
	}

	if ( file_exists( get_theme_file_path( $js_scrolltrigger ) ) ) {
		wp_enqueue_script(
			'wwd-website-redesign-gsap-scrolltrigger',
			get_theme_file_uri( $js_scrolltrigger ),
			array( 'wwd-website-redesign-gsap' ),
			filemtime( get_theme_file_path( $js_scrolltrigger ) ),
			true
		);
	}

	$base_deps = array( 'jquery' );
	if ( file_exists( get_theme_file_path( $js_gsap ) ) ) {
		$base_deps[] = 'wwd-website-redesign-gsap';
		if ( file_exists( get_theme_file_path( $js_scrolltrigger ) ) ) {
			$base_deps[] = 'wwd-website-redesign-gsap-scrolltrigger';
		}
	}

	wp_enqueue_script(
		'wwd-website-redesign-base',
		get_theme_file_uri( $js_base ),
		$base_deps,
		file_exists( get_theme_file_path( $js_base ) ) ? filemtime( get_theme_file_path( $js_base ) ) : null,
		true
	);

	wp_enqueue_script(
		'wwd-website-redesign-animations',
		get_theme_file_uri( $js_animations ),
		array( 'wwd-website-redesign-base' ),
		file_exists( get_theme_file_path( $js_animations ) ) ? filemtime( get_theme_file_path( $js_animations ) ) : null,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'wwd_website_redesign_enqueue_assets' );


/**
 * Optional: defer fÃ¼r Theme-JavaScript
 */
function wwd_website_redesign_defer_scripts( $tag, $handle ) {

	$defer_scripts = array(
		'wwd-website-redesign-base',
		'wwd-website-redesign-animations',
	);

	if ( in_array( $handle, $defer_scripts, true ) ) {
		if ( strpos( $tag, ' defer' ) === false ) {
			$tag = str_replace( ' src=', ' defer src=', $tag );
		}
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'wwd_website_redesign_defer_scripts', 10, 2 );

/**
 * Custom Post Types
 */
function wwd_register_cpts() {
	$common_supports = array( 'title', 'thumbnail', 'editor', 'page-attributes' );

	register_post_type(
		'nav_dienstleistungen',
		array(
			'labels' => array(
				'name'          => 'Nav Dienstleistungen',
				'singular_name' => 'Nav Dienstleistung',
				'add_new_item'  => 'Neue Nav Dienstleistung',
				'edit_item'     => 'Nav Dienstleistung bearbeiten',
				'view_item'     => 'Nav Dienstleistung ansehen',
				'search_items'  => 'Nav Dienstleistungen durchsuchen',
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'nav-dienstleistungen' ),
			'supports'     => $common_supports,
			'menu_icon'    => 'dashicons-menu',
		)
	);

	register_post_type(
		'nav_referenzen',
		array(
			'labels' => array(
				'name'          => 'Nav Referenzen',
				'singular_name' => 'Nav Referenz',
				'add_new_item'  => 'Neue Nav Referenz',
				'edit_item'     => 'Nav Referenz bearbeiten',
				'view_item'     => 'Nav Referenz ansehen',
				'search_items'  => 'Nav Referenzen durchsuchen',
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'nav-referenzen' ),
			'supports'     => $common_supports,
			'menu_icon'    => 'dashicons-images-alt2',
		)
	);

	register_post_type(
		'news',
		array(
			'labels' => array(
				'name'          => 'News',
				'singular_name' => 'News',
				'add_new_item'  => 'Neue News',
				'edit_item'     => 'News bearbeiten',
				'view_item'     => 'News ansehen',
				'search_items'  => 'News durchsuchen',
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'news' ),
			'supports'     => array( 'title', 'thumbnail', 'editor' ),
			'menu_icon'    => 'dashicons-megaphone',
		)
	);
}
add_action( 'init', 'wwd_register_cpts' );

/**
 * CPT queries for nav panels.
 */
function wwd_get_nav_dienstleistungen_query() {
	return new WP_Query(
		array(
			'post_type'      => 'nav_dienstleistungen',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'order'          => 'ASC',
		)
	);
}

function wwd_get_nav_referenzen_query() {
	return new WP_Query(
		array(
			'post_type'      => 'nav_referenzen',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'order'          => 'ASC',
		)
	);
}

function wwd_get_news_query() {
	return new WP_Query(
		array(
			'post_type'      => 'news',
			'posts_per_page' => 4,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);
}

/**
 * Admin column for manual ordering (menu_order).
 */
function wwd_add_menu_order_column( $columns ) {
	$columns['menu_order'] = 'Reihenfolge';
	return $columns;
}

function wwd_render_menu_order_column( $column, $post_id ) {
	if ( 'menu_order' === $column ) {
		echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
	}
}

function wwd_make_menu_order_sortable( $columns ) {
	$columns['menu_order'] = 'menu_order';
	return $columns;
}

function wwd_apply_menu_order_sorting( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );
	if ( 'menu_order' === $orderby ) {
		$query->set( 'order', 'ASC' );
	}
}

foreach ( array( 'nav_dienstleistungen', 'nav_referenzen' ) as $post_type ) {
	add_filter( "manage_edit-{$post_type}_columns", 'wwd_add_menu_order_column' );
	add_action( "manage_{$post_type}_posts_custom_column", 'wwd_render_menu_order_column', 10, 2 );
	add_filter( "manage_edit-{$post_type}_sortable_columns", 'wwd_make_menu_order_sortable' );
}
add_action( 'pre_get_posts', 'wwd_apply_menu_order_sorting' );

/**
 * Meta box for nav card links.
 */
function wwd_add_nav_card_link_metabox() {
	add_meta_box(
		'wwd_nav_card_link',
		'Card Link (URL)',
		'wwd_render_nav_card_link_metabox',
		array( 'nav_dienstleistungen', 'nav_referenzen' ),
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_nav_card_link_metabox' );

function wwd_render_nav_card_link_metabox( $post ) {
	$link = get_post_meta( $post->ID, '_nav_card_link', true );
	wp_nonce_field( 'wwd_nav_card_link_save', 'wwd_nav_card_link_nonce' );
	?>
	<p>
		<label for="wwd-nav-card-link"><?php echo esc_html( 'URL' ); ?></label>
	</p>
	<input
		type="url"
		id="wwd-nav-card-link"
		name="wwd_nav_card_link"
		value="<?php echo esc_attr( $link ); ?>"
		class="widefat"
		placeholder="<?php echo esc_attr( 'https://example.com' ); ?>"
	/>
	<?php
}

function wwd_save_nav_card_link_metabox( $post_id ) {
	if ( ! isset( $_POST['wwd_nav_card_link_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['wwd_nav_card_link_nonce'], 'wwd_nav_card_link_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['wwd_nav_card_link'] ) ) {
		$link = esc_url_raw( wp_unslash( $_POST['wwd_nav_card_link'] ) );
		if ( '' === $link ) {
			delete_post_meta( $post_id, '_nav_card_link' );
		} else {
			update_post_meta( $post_id, '_nav_card_link', $link );
		}
	}
}
add_action( 'save_post_nav_dienstleistungen', 'wwd_save_nav_card_link_metabox' );
add_action( 'save_post_nav_referenzen', 'wwd_save_nav_card_link_metabox' );

/**
 * Inline SVG helper for theme icons (assets/icons).
 */
function wwd_inline_svg( $filename, $args = array() ) {
	$defaults = array(
		'class'       => '',
		'aria_hidden' => true,
		'title'       => '',
	);
	$args = wp_parse_args( $args, $defaults );

	$filename = sanitize_file_name( $filename );
	if ( empty( $filename ) || 'svg' !== strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) ) ) {
		return '';
	}

	$base_dir = wp_normalize_path( get_theme_file_path( 'assets/icons' ) );
	$path     = wp_normalize_path( trailingslashit( $base_dir ) . $filename );

	if ( strpos( $path, $base_dir ) !== 0 || ! is_readable( $path ) ) {
		return '';
	}

	static $cache = array();
	if ( ! isset( $cache[ $path ] ) ) {
		$raw = file_get_contents( $path );
		if ( false === $raw ) {
			$cache[ $path ] = '';
		} else {
			$allowed = array(
				'svg'       => array(
					'class'               => true,
					'xmlns'               => true,
					'xmlns:xlink'         => true,
					'viewbox'             => true,
					'width'               => true,
					'height'              => true,
					'preserveaspectratio' => true,
					'fill'                => true,
					'stroke'              => true,
					'stroke-width'        => true,
					'stroke-linecap'      => true,
					'stroke-linejoin'     => true,
					'stroke-miterlimit'   => true,
					'stroke-dasharray'    => true,
					'stroke-dashoffset'   => true,
					'role'                => true,
					'aria-hidden'         => true,
					'focusable'           => true,
				),
				'g'         => array(
					'clip-path' => true,
					'fill'      => true,
					'stroke'    => true,
					'transform' => true,
					'opacity'   => true,
				),
				'path'      => array(
					'd'              => true,
					'fill'           => true,
					'fill-rule'      => true,
					'fill-opacity'   => true,
					'stroke'         => true,
					'stroke-width'   => true,
					'stroke-linecap' => true,
					'stroke-linejoin'=> true,
					'stroke-miterlimit' => true,
					'stroke-dasharray'  => true,
					'stroke-dashoffset' => true,
					'opacity'        => true,
					'transform'      => true,
					'clip-rule'      => true,
				),
				'defs'      => array(),
				'clippath'  => array(
					'id' => true,
				),
				'rect'      => array(
					'x'       => true,
					'y'       => true,
					'width'   => true,
					'height'  => true,
					'rx'      => true,
					'ry'      => true,
					'fill'    => true,
					'stroke'  => true,
					'opacity' => true,
				),
				'circle'    => array(
					'cx'      => true,
					'cy'      => true,
					'r'       => true,
					'fill'    => true,
					'stroke'  => true,
					'opacity' => true,
				),
				'line'      => array(
					'x1'      => true,
					'y1'      => true,
					'x2'      => true,
					'y2'      => true,
					'stroke'  => true,
					'opacity' => true,
				),
				'polyline'  => array(
					'points'  => true,
					'fill'    => true,
					'stroke'  => true,
					'opacity' => true,
				),
				'polygon'   => array(
					'points'  => true,
					'fill'    => true,
					'stroke'  => true,
					'opacity' => true,
				),
				'use'       => array(
					'xlink:href' => true,
					'href'       => true,
				),
				'title'     => array(),
				'desc'      => array(),
			);
			$cache[ $path ] = wp_kses( $raw, $allowed );
		}
	}

	$svg = $cache[ $path ];
	if ( '' === $svg ) {
		return '';
	}

	$classes = trim( 'icon ' . $args['class'] );
	$attrs   = ' class="' . esc_attr( $classes ) . '"';
	if ( ! empty( $args['aria_hidden'] ) ) {
		$attrs .= ' aria-hidden="true" focusable="false"';
	} else {
		$attrs .= ' role="img"';
	}

	$svg = preg_replace( '/<svg\\b([^>]*)>/i', '<svg$1' . $attrs . '>', $svg, 1 );

	if ( empty( $args['aria_hidden'] ) && ! empty( $args['title'] ) && false === stripos( $svg, '<title' ) ) {
		$title = '<title>' . esc_html( $args['title'] ) . '</title>';
		$svg   = preg_replace( '/(<svg\\b[^>]*>)/i', '$1' . $title, $svg, 1 );
	}

	return $svg;
}


function wwd_get_seitenbilder_fields() {
	return array(
		'home' => 'Homepage Hero',
		'ansatz-1' => 'ansatz-1',
		'ansatz-2' => 'ansatz-2',
		'weg-zur-website-1' => 'weg-zur-website-1',
		'weg-zur-website-2' => 'weg-zur-website-2',
		'weg-zur-website-3' => 'weg-zur-website-3',
		'weg-zur-website-4' => 'weg-zur-website-4',
		'kunden' => 'Kunden Hero',
		'das-machen-wir-moeglich-1' => 'das-machen-wir-moeglich-1',
		'das-machen-wir-moeglich-2' => 'das-machen-wir-moeglich-2',
		'das-machen-wir-moeglich-3' => 'das-machen-wir-moeglich-3',
		'ki-home' => 'KI Homepage',
		'ki-integration' => 'KI Integration',
		'news' => 'News Hero',
		'ueber-uns' => 'Ueber uns Hero',
		'kontakt' => 'Kontakt Hero',
	);
}

function wwd_register_seitenbilder_menu() {
	add_menu_page(
		'Seitenbilder',
		'Seitenbilder',
		'manage_options',
		'theme-seitenbilder',
		'wwd_seitenbilder_callback',
		'dashicons-format-image',
		60
	);
}
add_action( 'admin_menu', 'wwd_register_seitenbilder_menu' );

function wwd_sanitize_seitenbilder( $value ) {
	$fields = wwd_get_seitenbilder_fields();
	$sanitized = array();

	if ( ! is_array( $value ) ) {
		return $sanitized;
	}

	foreach ( $fields as $key => $label ) {
		$safe_key = sanitize_key( $key );
		if ( isset( $value[ $key ] ) ) {
			$sanitized[ $safe_key ] = absint( $value[ $key ] );
		}
	}

	return $sanitized;
}

function wwd_register_seitenbilder_settings() {
	register_setting(
		'wwd_seitenbilder',
		'wwd_seitenbilder',
		array(
			'type' => 'array',
			'sanitize_callback' => 'wwd_sanitize_seitenbilder',
			'default' => array(),
		)
	);
}
add_action( 'admin_init', 'wwd_register_seitenbilder_settings' );

function wwd_seitenbilder_admin_assets( $hook ) {
	if ( 'toplevel_page_theme-seitenbilder' !== $hook ) {
		return;
	}

	wp_enqueue_media();

	$js_admin = 'assets/js/admin-seitenbilder.js';
	$css_admin = 'assets/css/admin-seitenbilder.css';

	if ( file_exists( get_theme_file_path( $js_admin ) ) ) {
		wp_enqueue_script(
			'wwd-seitenbilder-admin',
			get_theme_file_uri( $js_admin ),
			array( 'jquery' ),
			filemtime( get_theme_file_path( $js_admin ) ),
			true
		);
	}

	if ( file_exists( get_theme_file_path( $css_admin ) ) ) {
		wp_enqueue_style(
			'wwd-seitenbilder-admin',
			get_theme_file_uri( $css_admin ),
			array(),
			filemtime( get_theme_file_path( $css_admin ) ),
			'all'
		);
	}
}
add_action( 'admin_enqueue_scripts', 'wwd_seitenbilder_admin_assets' );



function wwd_seitenbilder_callback() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Keine Berechtigung.', 'wwd' ) );
    }

    $fields  = wwd_get_seitenbilder_fields();
    $options = get_option( 'wwd_seitenbilder', array() );

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Seitenbilder verwalten', 'wwd' ) . '</h1>';
    settings_errors( 'wwd_seitenbilder' );
    echo '<form method="post" action="options.php">';
    settings_fields( 'wwd_seitenbilder' );

    foreach ( $fields as $key => $label ) {
        $stored_id    = isset( $options[ $key ] ) ? absint( $options[ $key ] ) : 0;
        $legacy_url   = $stored_id ? '' : get_option( $key );
        $legacy_id    = $legacy_url ? attachment_url_to_postid( $legacy_url ) : 0;
        $image_id     = $stored_id ? $stored_id : absint( $legacy_id );
        $preview_html = $image_id
            ? wp_get_attachment_image( $image_id, 'medium', false, array( 'class' => 'wwd-seitenbilder-preview-img' ) )
            : '';
        $preview_class = $image_id ? '' : ' is-hidden';
        $remove_class  = $image_id ? '' : ' is-hidden';

        echo '<div class="wwd-seitenbilder-field">';
        echo '<h3>' . esc_html( $label ) . '</h3>';
        echo '<input type="hidden" class="wwd-media-id" name="wwd_seitenbilder[' . esc_attr( $key ) . ']" value="' . esc_attr( $image_id ) . '">';
        echo '<button type="button" class="button wwd-media-select" data-title="' . esc_attr__( 'Bild auswählen', 'wwd' ) . '" data-button="' . esc_attr__( 'Bild verwenden', 'wwd' ) . '">' . esc_html__( 'Bild auswählen', 'wwd' ) . '</button> ';
        echo '<button type="button" class="button wwd-media-remove' . esc_attr( $remove_class ) . '">' . esc_html__( 'Entfernen', 'wwd' ) . '</button>';
        echo '<div class="wwd-seitenbilder-preview' . esc_attr( $preview_class ) . '">' . $preview_html . '</div>';
        echo '</div>';
    }

    submit_button( esc_html__( 'Bilder speichern', 'wwd' ) );
    echo '</form>';
    echo '</div>';
}
