<?php
// Hero Bilder über esc_url(get_option(''));
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
    $css_hero     = 'assets/css/hero.css';
    $css_referenzen     = 'assets/css/referenzen.css';
    $css_dienstleistungen     = 'assets/css/dienstleistungen.css';
	$css_news     = 'assets/css/news.css';
	$css_kontakt     = 'assets/css/kontakt.css';
	$css_dsgvo     = 'assets/css/dsgvo.css';
	// JS-Dateien
	$js_base       = 'assets/js/base.js';
	$js_animations = 'assets/js/animations.js';
	$js_btn_snake  = 'assets/js/btn-border-snake.js';
	$js_faq        = 'assets/js/faq.js';
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

    wp_enqueue_style(
        'wwd-website-redesign-hero',
        get_theme_file_uri( $css_hero ),
        array( 'wwd-website-redesign-base' ),
        file_exists( get_theme_file_path( $css_hero ) ) ? filemtime( get_theme_file_path( $css_hero ) ) : null
    );

    wp_enqueue_style(
        'wwd-website-redesign-referenzen',
        get_theme_file_uri( $css_referenzen ),
        array( 'wwd-website-redesign-base' ),
        file_exists( get_theme_file_path( $css_referenzen ) ) ? filemtime( get_theme_file_path( $css_referenzen ) ) : null
    );

    wp_enqueue_style(
        'wwd-website-redesign-dienstleistungen',
        get_theme_file_uri( $css_dienstleistungen ),
        array( 'wwd-website-redesign-base' ),
        file_exists( get_theme_file_path( $css_dienstleistungen ) ) ? filemtime( get_theme_file_path( $css_dienstleistungen ) ) : null
    );
	wp_enqueue_style(
        'wwd-website-redesign-news',
        get_theme_file_uri( $css_news ),
        array( 'wwd-website-redesign-base' ),
        file_exists( get_theme_file_path( $css_news ) ) ? filemtime( get_theme_file_path( $css_news ) ) : null
    );
	wp_enqueue_style(
        'wwd-website-redesign-kontakt',
        get_theme_file_uri( $css_kontakt ),
        array( 'wwd-website-redesign-base' ),
        file_exists( get_theme_file_path( $css_kontakt ) ) ? filemtime( get_theme_file_path( $css_kontakt) ) : null
    );
	wp_enqueue_style(
        'wwd-website-redesign-dsgvo',
        get_theme_file_uri( $css_dsgvo ),
        array( 'wwd-website-redesign-base' ),
        file_exists( get_theme_file_path( $css_dsgvo ) ) ? filemtime( get_theme_file_path( $css_dsgvo) ) : null
    );
	/**
	 * JS einbinden
	 * → falls base.js KEIN jQuery nutzt, einfach 'jquery' entfernen
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

	if ( file_exists( get_theme_file_path( $js_btn_snake ) ) ) {
		wp_enqueue_script(
			'wwd-website-redesign-btn-snake',
			get_theme_file_uri( $js_btn_snake ),
			array( 'wwd-website-redesign-base' ),
			filemtime( get_theme_file_path( $js_btn_snake ) ),
			true
		);
	}

	if ( file_exists( get_theme_file_path( $js_faq ) ) ) {
		wp_enqueue_script(
			'wwd-website-redesign-faq',
			get_theme_file_uri( $js_faq ),
			array(),
			filemtime( get_theme_file_path( $js_faq ) ),
			true
		);
	}

	if ( file_exists( get_theme_file_path( 'assets/js/btn-hover-anim.js' ) ) ) {
		$hover_deps = array();
		if ( wp_script_is( 'wwd-website-redesign-gsap', 'registered' ) ) {
			$hover_deps[] = 'wwd-website-redesign-gsap';
		}

		wp_enqueue_script(
			'wwd-website-redesign-btn-hover-anim',
			get_theme_file_uri( 'assets/js/btn-hover-anim.js' ),
			$hover_deps,
			filemtime( get_theme_file_path( 'assets/js/btn-hover-anim.js' ) ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'wwd_website_redesign_enqueue_assets' );


/**
 * Optional: defer für Theme-JavaScript
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
		'referenzen',
		array(
			'labels' => array(
				'name'          => 'Referenzen',
				'singular_name' => 'Referenz',
				'add_new_item'  => 'Neue Referenz',
				'edit_item'     => 'Referenz bearbeiten',
				'view_item'     => 'Referenz ansehen',
				'search_items'  => 'Referenzen durchsuchen',
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'referenzen' ),
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

	register_post_type(
		'slider_slide',
		array(
			'labels' => array(
				'name'          => 'Slider',
				'singular_name' => 'Slide',
				'add_new_item'  => 'Neuen Slide hinzufügen',
				'edit_item'     => 'Slide bearbeiten',
				'view_item'     => 'Slide ansehen',
				'search_items'  => 'Slides durchsuchen',
				'all_items'     => 'Alle Slides',
			),
			'public'       => true,
			'has_archive'  => false,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'slider' ),
			// "page-attributes" aktiviert die Reihenfolge (menu_order) im Editor.
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
			'menu_icon'    => 'dashicons-images-alt',
		)
	);

	// Unterseiten-CPTs (zentral verwaltet, in Schleife registriert).
	$unterseiten_cpts = array(
		'home' => array(
			'singular'   => 'Home',
			'plural'     => 'Home',
			'menu_icon'  => 'dashicons-admin-home',
			'menu_pos'   => 20,
		),
		'dienstleistungen' => array(
			'singular'   => 'Dienstleistung',
			'plural'     => 'Dienstleistungen',
			'menu_icon'  => 'dashicons-admin-tools',
			'menu_pos'   => 21,
		),
		'ki-integration' => array(
			'singular'   => 'KI-Integration',
			'plural'     => 'KI-Integration',
			'menu_icon'  => 'dashicons-lightbulb',
			'menu_pos'   => 22,
		),
		'ueber-uns' => array(
			'singular'   => '�ber uns',
			'plural'     => '�ber uns',
			'menu_icon'  => 'dashicons-groups',
			'menu_pos'   => 23,
		),
		'website_check' => array(
			'singular'     => 'Website-Check Inhalt',
			'plural'       => 'Website-Check Inhalte',
			'menu_icon'    => 'dashicons-visibility',
			'menu_pos'     => 24,
			'rewrite_slug' => 'website-check',
		),
	);

	foreach ( $unterseiten_cpts as $slug => $config ) {
		register_post_type(
			$slug,
			array(
				'labels' => array(
					'name'          => $config['plural'],
					'singular_name' => $config['singular'],
					'add_new_item'  => $config['singular'] . ' hinzuf�gen',
					'edit_item'     => $config['singular'] . ' bearbeiten',
					'view_item'     => $config['singular'] . ' ansehen',
					'search_items'  => $config['plural'] . ' durchsuchen',
					'all_items'     => $config['plural'],
				),
				'public'       => true,
				'show_ui'      => true,
				'show_in_menu' => true,
				'show_in_rest' => true,
				'has_archive'  => false,
				'hierarchical' => false,
				// "page-attributes" aktiviert das Feld "Reihenfolge" (menu_order) im Editor.
				'supports'     => array( 'title', 'editor', 'revisions', 'page-attributes' ),
				'menu_position'=> $config['menu_pos'],
				'menu_icon'    => $config['menu_icon'],
				// Hinweis: Falls es bereits Pages mit denselben Slugs gibt, kann es Konflikte geben.
				// In dem Fall muss entweder die Page umbenannt oder der CPT-Slug pr�fixiert werden.
				'rewrite'      => array(
					'slug'       => isset( $config['rewrite_slug'] ) ? $config['rewrite_slug'] : $slug,
					'with_front' => false,
				),
			)
		);
	}

	register_post_type(
		'website_weg',
		array(
			'labels' => array(
				'name'          => 'Website Weg',
				'singular_name' => 'Website Weg Eintrag',
				'menu_name'     => 'Website Weg',
				'add_new_item'  => 'Neuen Eintrag hinzufuegen',
				'edit_item'     => 'Eintrag bearbeiten',
				'view_item'     => 'Eintrag ansehen',
				'search_items'  => 'Website Weg durchsuchen',
				'all_items'     => 'Alle Eintraege',
			),
			'public'       => true,
			'show_ui'      => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'has_archive'  => false,
			'hierarchical' => false,
			'supports'     => array( 'title', 'editor', 'page-attributes' ),
			'menu_position'=> 25,
			'menu_icon'    => 'dashicons-editor-ol',
			'rewrite'      => array(
				'slug'       => 'website-weg',
				'with_front' => false,
			),
		)
	);

	register_post_type(
		'dsgvo',
		array(
			'labels' => array(
				'name'          => 'DSGVO',
				'singular_name' => 'DSGVO Eintrag',
				'menu_name'     => 'DSGVO',
				'add_new_item'  => 'Neuen DSGVO Eintrag hinzufuegen',
				'edit_item'     => 'DSGVO Eintrag bearbeiten',
				'view_item'     => 'DSGVO Eintrag ansehen',
				'search_items'  => 'DSGVO Eintraege durchsuchen',
				'all_items'     => 'Alle DSGVO Eintraege',
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'has_archive'  => false,
			'hierarchical' => false,
			'supports'     => array( 'title', 'editor', 'page-attributes' ),
			'menu_position'=> 26,
			'menu_icon'    => 'dashicons-shield',
			'rewrite'      => false,
		)
	);
}
add_action( 'init', 'wwd_register_cpts' );

function wwd_ensure_dsgvo_posts_exist() {
	$needed_posts = array(
		'impressum' => 'Impressum',
		'datenschutzerklaerung' => 'Datenschutzerklärung',
	);

	foreach ( $needed_posts as $post_name => $post_title ) {
		$existing = get_posts(
			array(
				'post_type'      => 'dsgvo',
				'post_status'    => 'any',
				'name'           => $post_name,
				'posts_per_page' => 1,
				'no_found_rows'  => true,
				'fields'         => 'ids',
			)
		);

		if ( empty( $existing ) ) {
			wp_insert_post(
				array(
					'post_type'   => 'dsgvo',
					'post_status' => 'publish',
					'post_title'  => $post_title,
					'post_name'   => $post_name,
				)
			);
		}
	}
}
add_action( 'init', 'wwd_ensure_dsgvo_posts_exist', 12 );

/**
 * Media taxonomy for attachments.
 */
function wwd_register_media_category_taxonomy() {
	$labels = array(
		'name'          => __( 'Media-Kategorien', 'wwd' ),
		'singular_name' => __( 'Media-Kategorie', 'wwd' ),
		'search_items'  => __( 'Media-Kategorien durchsuchen', 'wwd' ),
		'all_items'     => __( 'Alle Media-Kategorien', 'wwd' ),
		'edit_item'     => __( 'Media-Kategorie bearbeiten', 'wwd' ),
		'update_item'   => __( 'Media-Kategorie aktualisieren', 'wwd' ),
		'add_new_item'  => __( 'Neue Media-Kategorie hinzufuegen', 'wwd' ),
		'new_item_name' => __( 'Neue Media-Kategorie', 'wwd' ),
		'menu_name'     => __( 'Media-Kategorien', 'wwd' ),
	);

	register_taxonomy(
		'media_category',
		array( 'attachment' ),
		array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
		)
	);

	if ( ! term_exists( 'icons', 'media_category' ) ) {
		wp_insert_term(
			'Icons',
			'media_category',
			array(
				'slug' => 'icons',
			)
		);
	}
}
add_action( 'init', 'wwd_register_media_category_taxonomy', 11 );

/**
 * Unterseiten-Layouts Allowlist (niemals freie Dateinamen includen).
 */
function wwd_get_allowed_layouts() {
	return array(
		'leistungen-cards' => 'assets/_snippets/leistungen-cards.php',
		'offer-card'       => 'assets/_snippets/offer-card.php',
		'faq'              => 'assets/_snippets/faq.php',
		'three-img-layout' => 'assets/_snippets/three-img-layout.php',
		'two-img-layout'   => 'assets/_snippets/two-img-layout.php',
		'one-img-layout'   => 'assets/_snippets/one-img-layout.php',
		'balken-layout'    => 'assets/_snippets/balken.php',
		'slider-layout'    => 'assets/_snippets/slider.php',
	);
}

function theme_get_selected_layout( $post_id ) {
	$layout_key = '_layout_template';
	$value      = get_post_meta( $post_id, $layout_key, true );

	return is_string( $value ) ? $value : '';
}

function theme_is_balken_layout( $post_id ) {
	return 'balken-layout' === theme_get_selected_layout( $post_id );
}

function theme_is_faq_layout( $post_id ) {
	return 'faq' === theme_get_selected_layout( $post_id );
}

function theme_is_offer_card_layout( $post_id ) {
	return 'offer-card' === theme_get_selected_layout( $post_id );
}

/**
 * Unterseiten Meta Boxes (Layout-Auswahl + Inhalte).
 */
function wwd_get_unterseiten_post_types() {
	return array( 'home', 'dienstleistungen', 'ki-integration', 'ueber-uns', 'website_check' );
}

function wwd_add_unterseiten_metaboxes() {
	foreach ( wwd_get_unterseiten_post_types() as $post_type ) {
		add_meta_box(
			'wwd_layout_template',
			'Layout-Vorlage',
			'wwd_render_layout_template_metabox',
			$post_type,
			'side',
			'default'
		);

		add_meta_box(
			'wwd_unterseiten_content',
			'Inhalte',
			'wwd_render_unterseiten_content_metabox',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'wwd_add_unterseiten_metaboxes' );

function wwd_add_letzte_box_metabox() {
	add_meta_box(
		'wwd_letzte_box',
		'Letzte Box',
		'wwd_render_letzte_box_metabox',
		'home',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_letzte_box_metabox' );

function wwd_render_letzte_box_metabox( $post ) {
	$is_checked = '1' === get_post_meta( $post->ID, 'letzte_box', true );
	wp_nonce_field( 'wwd_save_letzte_box', 'wwd_letzte_box_nonce' );
	?>
	<p>
		<label for="wwd-letzte-box">
			<input
				type="checkbox"
				id="wwd-letzte-box"
				name="wwd_letzte_box"
				value="1"
				<?php checked( $is_checked ); ?>
			/>
			<?php echo esc_html( 'Als "Letzte Box" markieren' ); ?>
		</label>
	</p>
	<?php
}

function wwd_save_letzte_box_meta( $post_id ) {
	if ( ! isset( $_POST['wwd_letzte_box_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( wp_unslash( $_POST['wwd_letzte_box_nonce'] ), 'wwd_save_letzte_box' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$is_letzte_box = isset( $_POST['wwd_letzte_box'] ) && '1' === wp_unslash( $_POST['wwd_letzte_box'] );
	if ( $is_letzte_box ) {
		update_post_meta( $post_id, 'letzte_box', '1' );
	} else {
		delete_post_meta( $post_id, 'letzte_box' );
	}
}
add_action( 'save_post_home', 'wwd_save_letzte_box_meta' );

function wwd_render_layout_template_metabox( $post ) {
	$allowed_layouts = wwd_get_allowed_layouts();
	$current         = get_post_meta( $post->ID, '_layout_template', true );
	if ( empty( $current ) || ! isset( $allowed_layouts[ $current ] ) ) {
		$current = 'two-img-layout';
	}

	wp_nonce_field( 'wwd_layout_template_save', 'wwd_layout_template_nonce' );
	?>
	<p>
		<label for="wwd-layout-template"><?php echo esc_html( 'Layout-Vorlage' ); ?></label>
	</p>
	<select name="wwd_layout_template" id="wwd-layout-template" class="widefat">
		<option value="leistungen-cards" <?php selected( $current, 'leistungen-cards' ); ?>><?php echo esc_html( 'Leistungen Cards' ); ?></option>
		<option value="offer-card" <?php selected( $current, 'offer-card' ); ?>><?php echo esc_html( 'Offer Card' ); ?></option>
		<option value="faq" <?php selected( $current, 'faq' ); ?>><?php echo esc_html( 'FAQ' ); ?></option>
		<option value="one-img-layout" <?php selected( $current, 'one-img-layout' ); ?>><?php echo esc_html( 'One-Image Layout' ); ?></option>
		<option value="two-img-layout" <?php selected( $current, 'two-img-layout' ); ?>><?php echo esc_html( 'Two-Image Layout' ); ?></option>
		<option value="three-img-layout" <?php selected( $current, 'three-img-layout' ); ?>><?php echo esc_html( 'Three-Image Layout' ); ?></option>
		<option value="balken-layout" <?php selected( $current, 'balken-layout' ); ?>><?php echo esc_html( 'Balken' ); ?></option>
		<option value="slider-layout" <?php selected( $current, 'slider-layout' ); ?>><?php echo esc_html( 'Slider' ); ?></option>
	</select>
	<?php
}

function wwd_render_unterseiten_content_metabox( $post ) {
	$headline  = get_post_meta( $post->ID, '_section_headline', true );
	$mini_head = get_post_meta( $post->ID, '_section_mini_heading', true );
	$text      = get_post_meta( $post->ID, '_section_text', true );
	$cta_label = get_post_meta( $post->ID, '_cta_label', true );
	$cta_url   = get_post_meta( $post->ID, '_cta_url', true );

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post->ID, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}

	$img_1_id = absint( get_post_meta( $post->ID, '_img_1_id', true ) );
	$img_2_id = absint( get_post_meta( $post->ID, '_img_2_id', true ) );
	$img_3_id = absint( get_post_meta( $post->ID, '_img_3_id', true ) );

	wp_nonce_field( 'wwd_unterseiten_content_save', 'wwd_unterseiten_content_nonce' );
	?>
	<p>
		<label for="wwd-section-headline"><strong><?php echo esc_html( 'Headline' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="wwd-section-headline"
		name="wwd_section_headline"
		value="<?php echo esc_attr( $headline ); ?>"
		class="widefat"
	/>

	<p>
		<label for="wwd-section-mini-heading"><strong><?php echo esc_html( 'Mini-Heading' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="wwd-section-mini-heading"
		name="wwd_section_mini_heading"
		value="<?php echo esc_attr( $mini_head ); ?>"
		class="widefat"
	/>
	<p class="description"><?php echo esc_html( 'Kleiner Titel oberhalb der Hauptueberschrift.' ); ?></p>

	<p>
		<label for="wwd-section-text"><strong><?php echo esc_html( 'Text' ); ?></strong></label>
	</p>
	<textarea
		id="wwd-section-text"
		name="wwd_section_text"
		rows="6"
		class="widefat"
	><?php echo esc_textarea( $text ); ?></textarea>
	<p class="description"><?php echo esc_html( 'HTML ist erlaubt und wird beim Speichern bereinigt.' ); ?></p>

	<p>
		<label for="wwd-cta-label"><strong><?php echo esc_html( 'CTA Label' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="wwd-cta-label"
		name="wwd_cta_label"
		value="<?php echo esc_attr( $cta_label ); ?>"
		class="widefat"
	/>

	<p>
		<label for="wwd-cta-url"><strong><?php echo esc_html( 'CTA URL' ); ?></strong></label>
	</p>
	<input
		type="url"
		id="wwd-cta-url"
		name="wwd_cta_url"
		value="<?php echo esc_attr( $cta_url ); ?>"
		class="widefat"
		placeholder="<?php echo esc_attr( 'https://example.com' ); ?>"
	/>

	<hr />

	<p><strong><?php echo esc_html( 'Bilder' ); ?></strong></p>

	<?php
	$images = array(
		'img_1' => array(
			'label' => 'Bild 1',
			'id'    => $img_1_id,
		),
		'img_2' => array(
			'label' => 'Bild 2',
			'id'    => $img_2_id,
		),
	);
	if ( 'three-img-layout' === $layout ) {
		$images['img_3'] = array(
			'label' => 'Bild 3',
			'id'    => $img_3_id,
		);
	}

	foreach ( $images as $key => $img ) :
		$preview_url = $img['id'] ? wp_get_attachment_image_url( $img['id'], 'medium' ) : '';
		$input_id    = 'wwd-' . $key . '-id';
		?>
		<div class="wwd-media-field" data-target="<?php echo esc_attr( $input_id ); ?>">
			<p><label for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $img['label'] ); ?></label></p>
			<input
				type="hidden"
				id="<?php echo esc_attr( $input_id ); ?>"
				name="<?php echo esc_attr( $input_id ); ?>"
				value="<?php echo esc_attr( $img['id'] ); ?>"
			/>
			<div class="wwd-media-preview">
				<?php if ( $preview_url ) : ?>
					<img src="<?php echo esc_url( $preview_url ); ?>" alt="" />
				<?php endif; ?>
			</div>
			<p>
				<button type="button" class="button wwd-media-select"><?php echo esc_html( 'Bild ausw�hlen' ); ?></button>
				<button type="button" class="button wwd-media-remove"><?php echo esc_html( 'Entfernen' ); ?></button>
			</p>
		</div>
	<?php endforeach; ?>
	<?php
}

function wwd_save_unterseiten_meta( $post_id ) {
	if ( ! isset( $_POST['wwd_layout_template_nonce'], $_POST['wwd_unterseiten_content_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['wwd_layout_template_nonce'], 'wwd_layout_template_save' ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['wwd_unterseiten_content_nonce'], 'wwd_unterseiten_content_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = isset( $_POST['wwd_layout_template'] ) ? sanitize_key( wp_unslash( $_POST['wwd_layout_template'] ) ) : '';
	if ( ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	update_post_meta( $post_id, '_layout_template', $layout );

	$headline  = isset( $_POST['wwd_section_headline'] ) ? sanitize_text_field( wp_unslash( $_POST['wwd_section_headline'] ) ) : '';
	$mini_head = isset( $_POST['wwd_section_mini_heading'] ) ? sanitize_text_field( wp_unslash( $_POST['wwd_section_mini_heading'] ) ) : '';
	// HTML ist erlaubt und wird beim Speichern bereinigt.
	$text      = isset( $_POST['wwd_section_text'] ) ? wp_kses_post( wp_unslash( $_POST['wwd_section_text'] ) ) : '';
	$cta_label = isset( $_POST['wwd_cta_label'] ) ? sanitize_text_field( wp_unslash( $_POST['wwd_cta_label'] ) ) : '';
	$cta_url   = isset( $_POST['wwd_cta_url'] ) ? esc_url_raw( wp_unslash( $_POST['wwd_cta_url'] ) ) : '';

	$meta_map = array(
		'_section_headline' => $headline,
		'_section_mini_heading' => $mini_head,
		'_section_text'     => $text,
		'_cta_label'        => $cta_label,
		'_cta_url'          => $cta_url,
	);

	foreach ( $meta_map as $meta_key => $value ) {
		if ( '' === $value ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

	$image_fields = array(
		'_img_1_id' => 'wwd-img_1-id',
		'_img_2_id' => 'wwd-img_2-id',
	);
	if ( 'three-img-layout' === $layout ) {
		$image_fields['_img_3_id'] = 'wwd-img_3-id';
	}
	foreach ( $image_fields as $meta_key => $field_key ) {
		$value = isset( $_POST[ $field_key ] ) ? absint( $_POST[ $field_key ] ) : 0;
		if ( $value <= 0 ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

	if ( isset( $_POST['theme_balken_nonce'] ) && wp_verify_nonce( $_POST['theme_balken_nonce'], 'theme_save_balken_meta' ) ) {
		$balken_text = isset( $_POST['balken_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['balken_text'] ) ) : '';
		$balken_btn  = isset( $_POST['balken_button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['balken_button_text'] ) ) : '';
		$balken_url  = isset( $_POST['balken_button_url'] ) ? esc_url_raw( wp_unslash( $_POST['balken_button_url'] ) ) : '';

		$balken_meta = array(
			'balken_text'        => $balken_text,
			'balken_button_text' => $balken_btn,
			'balken_button_url'  => $balken_url,
		);

		foreach ( $balken_meta as $meta_key => $value ) {
			if ( '' === $value ) {
				delete_post_meta( $post_id, $meta_key );
			} else {
				update_post_meta( $post_id, $meta_key, $value );
			}
		}
	}

	if ( isset( $_POST['theme_faq_nonce'] ) && wp_verify_nonce( $_POST['theme_faq_nonce'], 'theme_save_faq_meta' ) ) {
		$faq_headline = isset( $_POST['faq_headline'] ) ? sanitize_text_field( wp_unslash( $_POST['faq_headline'] ) ) : '';
		if ( '' === $faq_headline ) {
			delete_post_meta( $post_id, 'faq_headline' );
		} else {
			update_post_meta( $post_id, 'faq_headline', $faq_headline );
		}

		for ( $i = 1; $i <= 10; $i++ ) {
			$q_key = 'faq_q_' . $i;
			$a_key = 'faq_a_' . $i;

			$question = isset( $_POST[ $q_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $q_key ] ) ) : '';
			$answer   = isset( $_POST[ $a_key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $a_key ] ) ) : '';

			if ( '' === $question ) {
				delete_post_meta( $post_id, $q_key );
			} else {
				update_post_meta( $post_id, $q_key, $question );
			}

			if ( '' === $answer ) {
				delete_post_meta( $post_id, $a_key );
			} else {
				update_post_meta( $post_id, $a_key, $answer );
			}
		}
	}
}

foreach ( wwd_get_unterseiten_post_types() as $post_type ) {
	add_action( "save_post_{$post_type}", 'wwd_save_unterseiten_meta' );
}

/**
 * Meta box for Balken layout.
 */
function wwd_add_balken_metaboxes( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	if ( ! theme_is_balken_layout( $post_id ) ) {
		return;
	}

	add_meta_box(
		'wwd_balken_meta',
		'Balken',
		'wwd_render_balken_metabox',
		$post_type,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_balken_metaboxes', 10, 2 );

/**
 * Hide other layout-specific metaboxes when Balken layout is active.
 */
function wwd_adjust_metaboxes_for_balken_layout( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	if ( ! theme_is_balken_layout( $post_id ) ) {
		return;
	}

	remove_meta_box( 'wwd_unterseiten_content', $post_type, 'normal' );
	remove_meta_box( 'wwd_three_img_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_one_img_bottom_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_leistungen_cards', $post_type, 'normal' );
	remove_meta_box( 'wwd_slider_layout_slides', $post_type, 'normal' );
	remove_meta_box( 'theme_faq_box', $post_type, 'normal' );
}
add_action( 'add_meta_boxes', 'wwd_adjust_metaboxes_for_balken_layout', 20, 2 );

function wwd_render_balken_metabox( $post ) {
	$balken_text       = get_post_meta( $post->ID, 'balken_text', true );
	$balken_btn_text   = get_post_meta( $post->ID, 'balken_button_text', true );
	$balken_button_url = get_post_meta( $post->ID, 'balken_button_url', true );

	wp_nonce_field( 'theme_save_balken_meta', 'theme_balken_nonce' );
	wp_nonce_field( 'wwd_unterseiten_content_save', 'wwd_unterseiten_content_nonce' );
	?>
	<p>
		<label for="balken-text"><strong><?php echo esc_html( 'Balken Text' ); ?></strong></label>
	</p>
	<textarea
		id="balken-text"
		name="balken_text"
		rows="4"
		class="widefat"
	><?php echo esc_textarea( $balken_text ); ?></textarea>

	<p>
		<label for="balken-button-text"><strong><?php echo esc_html( 'Button Text' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="balken-button-text"
		name="balken_button_text"
		value="<?php echo esc_attr( $balken_btn_text ); ?>"
		class="widefat"
	/>

	<p>
		<label for="balken-button-url"><strong><?php echo esc_html( 'Button URL' ); ?></strong></label>
	</p>
	<input
		type="url"
		id="balken-button-url"
		name="balken_button_url"
		value="<?php echo esc_attr( $balken_button_url ); ?>"
		class="widefat"
		placeholder="<?php echo esc_attr( home_url( '/kontakt/' ) ); ?>"
	/>
	<?php
}

/**
 * Meta box for FAQ layout.
 */
function wwd_add_faq_metaboxes( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	if ( ! theme_is_faq_layout( $post_id ) ) {
		return;
	}

	add_meta_box(
		'theme_faq_box',
		'FAQ (Layout)',
		'wwd_render_faq_metabox',
		$post_type,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_faq_metaboxes', 10, 2 );

function wwd_render_faq_metabox( $post ) {
	$faq_headline = get_post_meta( $post->ID, 'faq_headline', true );

	wp_nonce_field( 'theme_save_faq_meta', 'theme_faq_nonce' );
	wp_nonce_field( 'wwd_unterseiten_content_save', 'wwd_unterseiten_content_nonce' );
	?>
	<p>
		<label for="faq-headline"><strong><?php echo esc_html( 'Headline (optional)' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="faq-headline"
		name="faq_headline"
		value="<?php echo esc_attr( $faq_headline ); ?>"
		class="widefat"
	/>

	<?php for ( $i = 1; $i <= 10; $i++ ) : ?>
		<?php
		$q_key = 'faq_q_' . $i;
		$a_key = 'faq_a_' . $i;
		$q_val = get_post_meta( $post->ID, $q_key, true );
		$a_val = get_post_meta( $post->ID, $a_key, true );
		?>
		<hr />
		<p>
			<label for="<?php echo esc_attr( 'faq-q-' . $i ); ?>"><strong><?php echo esc_html( 'Frage ' . $i ); ?></strong></label>
		</p>
		<input
			type="text"
			id="<?php echo esc_attr( 'faq-q-' . $i ); ?>"
			name="<?php echo esc_attr( $q_key ); ?>"
			value="<?php echo esc_attr( $q_val ); ?>"
			class="widefat"
		/>

		<p>
			<label for="<?php echo esc_attr( 'faq-a-' . $i ); ?>"><strong><?php echo esc_html( 'Antwort ' . $i ); ?></strong></label>
		</p>
		<textarea
			id="<?php echo esc_attr( 'faq-a-' . $i ); ?>"
			name="<?php echo esc_attr( $a_key ); ?>"
			rows="3"
			class="widefat"
		><?php echo esc_textarea( $a_val ); ?></textarea>
	<?php endfor; ?>
	<?php
}

/**
 * Hide other layout-specific metaboxes when FAQ layout is active.
 */
function wwd_adjust_metaboxes_for_faq_layout( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	if ( ! theme_is_faq_layout( $post_id ) ) {
		return;
	}

	remove_meta_box( 'wwd_unterseiten_content', $post_type, 'normal' );
	remove_meta_box( 'wwd_balken_meta', $post_type, 'normal' );
	remove_meta_box( 'wwd_three_img_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_one_img_bottom_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_leistungen_cards', $post_type, 'normal' );
	remove_meta_box( 'wwd_slider_layout_slides', $post_type, 'normal' );
}
add_action( 'add_meta_boxes', 'wwd_adjust_metaboxes_for_faq_layout', 20, 2 );

/**
 * Meta box for three-img layout texts.
 */
function wwd_add_three_img_texts_metaboxes( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = array_merge( array( 'page' ), wwd_get_unterseiten_post_types() );
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'three-img-layout' !== $layout ) {
		return;
	}

	add_meta_box(
		'wwd_three_img_texts',
		'Three Img Layout Texte',
		'wwd_render_three_img_texts_metabox',
		$post_type,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_three_img_texts_metaboxes', 10, 2 );

function wwd_render_three_img_texts_metabox( $post ) {
	$t1 = get_post_meta( $post->ID, 'three_img_text_1', true );
	$t2 = get_post_meta( $post->ID, 'three_img_text_2', true );
	$t3 = get_post_meta( $post->ID, 'three_img_text_3', true );

	wp_nonce_field( 'three_img_layout_save', 'three_img_layout_nonce' );
	?>
	<p>
		<label for="three-img-text-1"><strong><?php echo esc_html( 'Text Bild 1' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="three-img-text-1"
		name="three_img_text_1"
		value="<?php echo esc_attr( $t1 ); ?>"
		class="widefat"
	/>

	<p>
		<label for="three-img-text-2"><strong><?php echo esc_html( 'Text Bild 2' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="three-img-text-2"
		name="three_img_text_2"
		value="<?php echo esc_attr( $t2 ); ?>"
		class="widefat"
	/>

	<p>
		<label for="three-img-text-3"><strong><?php echo esc_html( 'Text Bild 3' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="three-img-text-3"
		name="three_img_text_3"
		value="<?php echo esc_attr( $t3 ); ?>"
		class="widefat"
	/>
	<?php
}

function wwd_save_three_img_texts_meta( $post_id ) {
	if ( ! isset( $_POST['three_img_layout_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['three_img_layout_nonce'], 'three_img_layout_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$allowed_post_types = array_merge( array( 'page' ), wwd_get_unterseiten_post_types() );
	if ( ! in_array( get_post_type( $post_id ), $allowed_post_types, true ) ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = isset( $_POST['wwd_layout_template'] ) ? sanitize_key( wp_unslash( $_POST['wwd_layout_template'] ) ) : '';
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = get_post_meta( $post_id, '_layout_template', true );
	}
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'three-img-layout' !== $layout ) {
		return;
	}

	$fields = array(
		'three_img_text_1',
		'three_img_text_2',
		'three_img_text_3',
	);

	foreach ( $fields as $key ) {
		$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'wwd_save_three_img_texts_meta' );

/**
 * Meta box for one-img layout bottom texts.
 */
function wwd_add_one_img_bottom_texts_metaboxes( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = array_merge( array( 'page' ), wwd_get_unterseiten_post_types() );
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'one-img-layout' !== $layout ) {
		return;
	}

	add_meta_box(
		'wwd_one_img_bottom_texts',
		'One Img Layout Bottom Texte',
		'wwd_render_one_img_bottom_texts_metabox',
		$post_type,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_one_img_bottom_texts_metaboxes', 10, 2 );

function wwd_render_one_img_bottom_texts_metabox( $post ) {
	$t1 = get_post_meta( $post->ID, 'one_img_bottom_p_1', true );
	$t2 = get_post_meta( $post->ID, 'one_img_bottom_p_2', true );
	$t3 = get_post_meta( $post->ID, 'one_img_bottom_p_3', true );
	$t4 = get_post_meta( $post->ID, 'one_img_bottom_p_4', true );
	$t5 = get_post_meta( $post->ID, 'one_img_bottom_p_5', true );
	$t6 = get_post_meta( $post->ID, 'one_img_bottom_p_6', true );

	wp_nonce_field( 'one_img_bottom_texts_save', 'one_img_bottom_texts_nonce' );
	?>
	<p>
		<label for="one-img-bottom-text-1"><strong><?php echo esc_html( 'Text 1' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="one-img-bottom-text-1"
		name="one_img_bottom_p_1"
		value="<?php echo esc_attr( $t1 ); ?>"
		class="widefat"
	/>

	<p>
		<label for="one-img-bottom-text-2"><strong><?php echo esc_html( 'Text 2' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="one-img-bottom-text-2"
		name="one_img_bottom_p_2"
		value="<?php echo esc_attr( $t2 ); ?>"
		class="widefat"
	/>

	<p>
		<label for="one-img-bottom-text-3"><strong><?php echo esc_html( 'Text 3' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="one-img-bottom-text-3"
		name="one_img_bottom_p_3"
		value="<?php echo esc_attr( $t3 ); ?>"
		class="widefat"
	/>

	<p>
		<label for="one-img-bottom-text-4"><strong><?php echo esc_html( 'Text 4' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="one-img-bottom-text-4"
		name="one_img_bottom_p_4"
		value="<?php echo esc_attr( $t4 ); ?>"
		class="widefat"
	/>

	<p>
		<label for="one-img-bottom-text-5"><strong><?php echo esc_html( 'Text 5' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="one-img-bottom-text-5"
		name="one_img_bottom_p_5"
		value="<?php echo esc_attr( $t5 ); ?>"
		class="widefat"
	/>

	<p>
		<label for="one-img-bottom-text-6"><strong><?php echo esc_html( 'Text 6' ); ?></strong></label>
	</p>
	<input
		type="text"
		id="one-img-bottom-text-6"
		name="one_img_bottom_p_6"
		value="<?php echo esc_attr( $t6 ); ?>"
		class="widefat"
	/>
	<?php
}

function wwd_save_one_img_bottom_texts_meta( $post_id ) {
	if ( ! isset( $_POST['one_img_bottom_texts_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['one_img_bottom_texts_nonce'], 'one_img_bottom_texts_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$allowed_post_types = array_merge( array( 'page' ), wwd_get_unterseiten_post_types() );
	if ( ! in_array( get_post_type( $post_id ), $allowed_post_types, true ) ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = isset( $_POST['wwd_layout_template'] ) ? sanitize_key( wp_unslash( $_POST['wwd_layout_template'] ) ) : '';
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = get_post_meta( $post_id, '_layout_template', true );
	}
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'one-img-layout' !== $layout ) {
		return;
	}

	$fields = array(
		'one_img_bottom_p_1',
		'one_img_bottom_p_2',
		'one_img_bottom_p_3',
		'one_img_bottom_p_4',
		'one_img_bottom_p_5',
		'one_img_bottom_p_6',
	);

	foreach ( $fields as $key ) {
		$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'wwd_save_one_img_bottom_texts_meta' );

/**
 * Meta box for Leistungen Cards layout (up to 3 cards).
 */
function wwd_add_leistungen_cards_metaboxes( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'leistungen-cards' !== $layout ) {
		return;
	}

	add_meta_box(
		'wwd_leistungen_cards',
		'Leistungen Cards (bis zu 3)',
		'wwd_render_leistungen_cards_metabox',
		$post_type,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_leistungen_cards_metaboxes', 10, 2 );

function wwd_render_leistungen_cards_metabox( $post ) {
	wp_nonce_field( 'wwd_leistungen_cards_save', 'wwd_leistungen_cards_nonce' );

	for ( $i = 1; $i <= 3; $i++ ) :
		$icon_id = absint( get_post_meta( $post->ID, "_leistungen_card_{$i}_icon", true ) );
		$heading = get_post_meta( $post->ID, "_leistungen_card_{$i}_heading", true );
		$text    = get_post_meta( $post->ID, "_leistungen_card_{$i}_text", true );
		$preview = $icon_id ? wp_get_attachment_image_url( $icon_id, 'medium' ) : '';
		$input_id = "wwd-leistungen-card-{$i}-icon";
		?>
		<div class="wwd-leistungen-card" data-card-index="<?php echo esc_attr( $i ); ?>">
			<hr />
			<p><strong><?php echo esc_html( 'Card ' . $i ); ?></strong></p>

			<p>
				<label for="<?php echo esc_attr( $input_id ); ?>"><strong><?php echo esc_html( 'Icon' ); ?></strong></label>
			</p>
			<input
				type="hidden"
				id="<?php echo esc_attr( $input_id ); ?>"
				name="<?php echo esc_attr( "_leistungen_card_{$i}_icon" ); ?>"
				value="<?php echo esc_attr( $icon_id ); ?>"
				class="wwd-leistungen-icon-id"
			/>
			<div class="wwd-leistungen-icon-preview">
				<?php if ( $preview ) : ?>
					<img src="<?php echo esc_url( $preview ); ?>" alt="" />
				<?php endif; ?>
			</div>
			<p>
				<button type="button" class="button wwd-leistungen-icon-select"><?php echo esc_html( 'Icon auswählen' ); ?></button>
				<button type="button" class="button wwd-leistungen-icon-remove"><?php echo esc_html( 'Icon entfernen' ); ?></button>
			</p>

			<p>
				<label for="<?php echo esc_attr( "wwd-leistungen-card-{$i}-heading" ); ?>"><strong><?php echo esc_html( 'Mini-Heading' ); ?></strong></label>
			</p>
			<input
				type="text"
				id="<?php echo esc_attr( "wwd-leistungen-card-{$i}-heading" ); ?>"
				name="<?php echo esc_attr( "_leistungen_card_{$i}_heading" ); ?>"
				value="<?php echo esc_attr( $heading ); ?>"
				class="widefat"
			/>

			<p>
				<label for="<?php echo esc_attr( "wwd-leistungen-card-{$i}-text" ); ?>"><strong><?php echo esc_html( 'Text' ); ?></strong></label>
			</p>
			<textarea
				id="<?php echo esc_attr( "wwd-leistungen-card-{$i}-text" ); ?>"
				name="<?php echo esc_attr( "_leistungen_card_{$i}_text" ); ?>"
				rows="5"
				class="widefat"
			><?php echo esc_textarea( $text ); ?></textarea>
		</div>
	<?php endfor; ?>
	<?php
}

function wwd_save_leistungen_cards_meta( $post_id ) {
	if ( ! isset( $_POST['wwd_leistungen_cards_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['wwd_leistungen_cards_nonce'], 'wwd_leistungen_cards_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( get_post_type( $post_id ), $allowed_post_types, true ) ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = isset( $_POST['wwd_layout_template'] ) ? sanitize_key( wp_unslash( $_POST['wwd_layout_template'] ) ) : '';
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = get_post_meta( $post_id, '_layout_template', true );
	}
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'leistungen-cards' !== $layout ) {
		return;
	}

	$invalid_icon = false;
	for ( $i = 1; $i <= 3; $i++ ) {
		$icon_key    = "_leistungen_card_{$i}_icon";
		$heading_key = "_leistungen_card_{$i}_heading";
		$text_key    = "_leistungen_card_{$i}_text";

		$icon_id = isset( $_POST[ $icon_key ] ) ? absint( $_POST[ $icon_key ] ) : 0;
		$heading = isset( $_POST[ $heading_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $heading_key ] ) ) : '';
		$text    = isset( $_POST[ $text_key ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $text_key ] ) ) : '';

		if ( $icon_id > 0 && ( ! wp_attachment_is_image( $icon_id ) || ! has_term( 'icons', 'media_category', $icon_id ) ) ) {
			$icon_id    = 0;
			$invalid_icon = true;
		}

		if ( $icon_id > 0 ) {
			update_post_meta( $post_id, $icon_key, $icon_id );
		} else {
			delete_post_meta( $post_id, $icon_key );
		}

		if ( '' === $heading ) {
			delete_post_meta( $post_id, $heading_key );
		} else {
			update_post_meta( $post_id, $heading_key, $heading );
		}

		if ( '' === $text ) {
			delete_post_meta( $post_id, $text_key );
		} else {
			update_post_meta( $post_id, $text_key, $text );
		}
	}

	if ( $invalid_icon ) {
		set_transient( 'wwd_leistungen_cards_invalid_icon_' . $post_id, 1, 60 );
	}
}
add_action( 'save_post', 'wwd_save_leistungen_cards_meta' );

/**
 * Meta box for Offer Cards layout.
 */
function wwd_add_offer_card_metaboxes( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'offer-card' !== $layout ) {
		return;
	}

	add_meta_box(
		'wwd_offer_card_fields',
		'Offer Card Felder',
		'wwd_render_offer_card_metabox',
		$post_type,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_offer_card_metaboxes', 10, 2 );

function wwd_render_offer_card_metabox_row( $index, $card = array(), $is_template = false ) {
	$title = isset( $card['title'] ) ? (string) $card['title'] : '';
	$text  = isset( $card['text'] ) ? (string) $card['text'] : '';
	$price = isset( $card['price'] ) ? (string) $card['price'] : '';

	$bullets = array();
	if ( isset( $card['bullets'] ) && is_array( $card['bullets'] ) ) {
		$bullets = array_values( $card['bullets'] );
	}
	$bullets = array_pad( array_slice( $bullets, 0, 6 ), 6, '' );

	$row_class = 'wwd-offer-card-row';
	$style     = '';
	if ( $is_template ) {
		$row_class .= ' wwd-offer-card-template';
		$style     = 'display:none;';
	}
	?>
	<div class="<?php echo esc_attr( $row_class ); ?>" data-offer-card-index="<?php echo esc_attr( $index ); ?>"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
		<hr />
		<p><strong><?php echo esc_html( 'Card' ); ?></strong></p>

		<p>
			<label for="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-title' ); ?>"><strong><?php echo esc_html( 'Titel' ); ?></strong></label>
		</p>
		<input
			type="text"
			id="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-title' ); ?>"
			name="<?php echo esc_attr( 'offer_cards[' . $index . '][title]' ); ?>"
			value="<?php echo esc_attr( $title ); ?>"
			class="widefat"
		/>

		<p>
			<label for="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-text' ); ?>"><strong><?php echo esc_html( 'Text' ); ?></strong></label>
		</p>
		<textarea
			id="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-text' ); ?>"
			name="<?php echo esc_attr( 'offer_cards[' . $index . '][text]' ); ?>"
			rows="5"
			class="widefat"
		><?php echo esc_textarea( $text ); ?></textarea>

		<?php for ( $b = 0; $b < 6; $b++ ) : ?>
			<p>
				<label for="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-bullet-' . $b ); ?>"><strong><?php echo esc_html( 'Bullet ' . ( $b + 1 ) ); ?></strong></label>
			</p>
			<input
				type="text"
				id="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-bullet-' . $b ); ?>"
				name="<?php echo esc_attr( 'offer_cards[' . $index . '][bullets][' . $b . ']' ); ?>"
				value="<?php echo esc_attr( $bullets[ $b ] ); ?>"
				class="widefat"
			/>
		<?php endfor; ?>

		<p>
			<label for="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-price' ); ?>"><strong><?php echo esc_html( 'Preis' ); ?></strong></label>
		</p>
		<input
			type="text"
			id="<?php echo esc_attr( 'wwd-offer-card-' . $index . '-price' ); ?>"
			name="<?php echo esc_attr( 'offer_cards[' . $index . '][price]' ); ?>"
			value="<?php echo esc_attr( $price ); ?>"
			class="widefat"
		/>

		<p>
			<button type="button" class="button button-secondary wwd-offer-card-remove"><?php echo esc_html( 'Card entfernen' ); ?></button>
		</p>
	</div>
	<?php
}

function wwd_render_offer_card_metabox( $post ) {
	wp_nonce_field( 'wwd_offer_card_save', 'wwd_offer_card_nonce' );

	$cards = get_post_meta( $post->ID, 'offer_cards', true );
	if ( ! is_array( $cards ) ) {
		$cards = array();
	}
	if ( empty( $cards ) ) {
		$cards = array(
			array(
				'title'   => '',
				'text'    => '',
				'bullets' => array(),
				'price'   => '',
			),
		);
	}

	$next_index = 0;
	foreach ( array_keys( $cards ) as $card_index ) {
		if ( is_numeric( $card_index ) ) {
			$card_index = (int) $card_index;
			if ( $card_index >= $next_index ) {
				$next_index = $card_index + 1;
			}
		}
	}
	?>
	<div id="wwd-offer-card-repeater" data-next-index="<?php echo esc_attr( $next_index ); ?>">
		<?php foreach ( $cards as $index => $card ) : ?>
			<?php wwd_render_offer_card_metabox_row( (string) $index, is_array( $card ) ? $card : array(), false ); ?>
		<?php endforeach; ?>
		<?php wwd_render_offer_card_metabox_row( '__INDEX__', array(), true ); ?>
	</div>
	<p>
		<button type="button" class="button button-primary" id="wwd-offer-card-add"><?php echo esc_html( 'Card hinzufuegen' ); ?></button>
	</p>
	<?php
}

function wwd_save_offer_card_meta( $post_id ) {
	if ( ! isset( $_POST['wwd_offer_card_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( wp_unslash( $_POST['wwd_offer_card_nonce'] ), 'wwd_offer_card_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( get_post_type( $post_id ), $allowed_post_types, true ) ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = isset( $_POST['wwd_layout_template'] ) ? sanitize_key( wp_unslash( $_POST['wwd_layout_template'] ) ) : '';
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = get_post_meta( $post_id, '_layout_template', true );
	}
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'offer-card' !== $layout ) {
		return;
	}

	$raw_cards = array();
	if ( isset( $_POST['offer_cards'] ) && is_array( $_POST['offer_cards'] ) ) {
		$raw_cards = wp_unslash( $_POST['offer_cards'] );
	}

	$clean_cards = array();
	foreach ( $raw_cards as $raw_card ) {
		if ( ! is_array( $raw_card ) ) {
			continue;
		}

		$title = isset( $raw_card['title'] ) ? sanitize_text_field( $raw_card['title'] ) : '';
		$text  = isset( $raw_card['text'] ) ? wp_kses_post( $raw_card['text'] ) : '';
		$price = isset( $raw_card['price'] ) ? sanitize_text_field( $raw_card['price'] ) : '';

		$bullets_in = array();
		if ( isset( $raw_card['bullets'] ) && is_array( $raw_card['bullets'] ) ) {
			$bullets_in = array_slice( array_values( $raw_card['bullets'] ), 0, 6 );
		}

		$bullets = array();
		foreach ( $bullets_in as $bullet ) {
			$bullet = sanitize_text_field( $bullet );
			if ( '' !== $bullet ) {
				$bullets[] = $bullet;
			}
		}

		if ( '' === $title && '' === wp_strip_all_tags( $text ) && empty( $bullets ) && '' === $price ) {
			continue;
		}

		$clean_cards[] = array(
			'title'   => $title,
			'text'    => $text,
			'bullets' => $bullets,
			'price'   => $price,
		);
	}

	if ( empty( $clean_cards ) ) {
		delete_post_meta( $post_id, 'offer_cards' );
	} else {
		update_post_meta( $post_id, 'offer_cards', $clean_cards );
	}
}
add_action( 'save_post', 'wwd_save_offer_card_meta' );

/**
 * Meta box for slider layout (3 fixed slides).
 */
function wwd_add_slider_layout_metaboxes( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'slider-layout' !== $layout ) {
		return;
	}

	add_meta_box(
		'wwd_slider_layout_slides',
		'Slider (3 Slides)',
		'wwd_render_slider_layout_metabox',
		$post_type,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_slider_layout_metaboxes', 10, 2 );

function wwd_render_slider_layout_metabox( $post ) {
	wp_nonce_field( 'wwd_slider_layout_save', 'wwd_slider_layout_nonce' );

	for ( $i = 1; $i <= 3; $i++ ) {
		$img_id  = absint( get_post_meta( $post->ID, "_slider_slide_{$i}_image", true ) );
		$heading = get_post_meta( $post->ID, "_slider_slide_{$i}_heading", true );
		$text    = get_post_meta( $post->ID, "_slider_slide_{$i}_text", true );

		$preview_url = $img_id ? wp_get_attachment_image_url( $img_id, 'medium' ) : '';
		$input_id    = "wwd-slider-slide-{$i}-image";
		?>
		<hr />
		<h4><?php echo esc_html( "Slide {$i}" ); ?></h4>

		<div class="wwd-media-field" data-target="<?php echo esc_attr( $input_id ); ?>">
			<p><label for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( 'Bild' ); ?></label></p>
			<input
				type="hidden"
				id="<?php echo esc_attr( $input_id ); ?>"
				name="<?php echo esc_attr( "slider_slide_{$i}_image" ); ?>"
				value="<?php echo esc_attr( $img_id ); ?>"
			/>
			<div class="wwd-media-preview">
				<?php if ( $preview_url ) : ?>
					<img src="<?php echo esc_url( $preview_url ); ?>" alt="" />
				<?php endif; ?>
			</div>
			<p>
				<button type="button" class="button wwd-media-select"><?php echo esc_html( 'Bild auswählen' ); ?></button>
				<button type="button" class="button wwd-media-remove"><?php echo esc_html( 'Entfernen' ); ?></button>
			</p>
		</div>

		<p>
			<label for="<?php echo esc_attr( "wwd-slider-slide-{$i}-heading" ); ?>"><strong><?php echo esc_html( 'Heading' ); ?></strong></label>
		</p>
		<input
			type="text"
			id="<?php echo esc_attr( "wwd-slider-slide-{$i}-heading" ); ?>"
			name="<?php echo esc_attr( "slider_slide_{$i}_heading" ); ?>"
			value="<?php echo esc_attr( $heading ); ?>"
			class="widefat"
			required
		/>

		<p>
			<label for="<?php echo esc_attr( "wwd-slider-slide-{$i}-text" ); ?>"><strong><?php echo esc_html( 'Text' ); ?></strong></label>
		</p>
		<textarea
			id="<?php echo esc_attr( "wwd-slider-slide-{$i}-text" ); ?>"
			name="<?php echo esc_attr( "slider_slide_{$i}_text" ); ?>"
			rows="4"
			class="widefat"
			required
		><?php echo esc_textarea( $text ); ?></textarea>
		<?php
	}
}

function wwd_save_slider_layout_meta( $post_id ) {
	if ( ! isset( $_POST['wwd_slider_layout_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['wwd_slider_layout_nonce'], 'wwd_slider_layout_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = isset( $_POST['wwd_layout_template'] ) ? sanitize_key( wp_unslash( $_POST['wwd_layout_template'] ) ) : '';
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = get_post_meta( $post_id, '_layout_template', true );
	}
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'slider-layout' !== $layout ) {
		return;
	}

	$complete = true;

	for ( $i = 1; $i <= 3; $i++ ) {
		$img_id  = isset( $_POST[ "slider_slide_{$i}_image" ] ) ? absint( $_POST[ "slider_slide_{$i}_image" ] ) : 0;
		$heading = isset( $_POST[ "slider_slide_{$i}_heading" ] ) ? sanitize_text_field( wp_unslash( $_POST[ "slider_slide_{$i}_heading" ] ) ) : '';
		$text    = isset( $_POST[ "slider_slide_{$i}_text" ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ "slider_slide_{$i}_text" ] ) ) : '';

		if ( $img_id <= 0 || '' === $heading || '' === $text ) {
			$complete = false;
		}

		$meta_map = array(
			"_slider_slide_{$i}_image"   => $img_id,
			"_slider_slide_{$i}_heading" => $heading,
			"_slider_slide_{$i}_text"    => $text,
		);

		foreach ( $meta_map as $meta_key => $value ) {
			if ( '' === $value || 0 === $value ) {
				delete_post_meta( $post_id, $meta_key );
			} else {
				update_post_meta( $post_id, $meta_key, $value );
			}
		}
	}

	if ( ! $complete ) {
		set_transient( 'wwd_slider_layout_incomplete_' . $post_id, 1, 60 );
	}
}
add_action( 'save_post', 'wwd_save_slider_layout_meta' );

function wwd_slider_layout_admin_notice() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, wwd_get_unterseiten_post_types(), true ) ) {
		return;
	}
	if ( 'post' !== $screen->base ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
	if ( ! $post_id ) {
		return;
	}

	$layout = get_post_meta( $post_id, '_layout_template', true );
	if ( 'slider-layout' !== $layout ) {
		return;
	}

	$transient_key = 'wwd_slider_layout_incomplete_' . $post_id;
	if ( get_transient( $transient_key ) ) {
		delete_transient( $transient_key );
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo esc_html( 'Bitte alle 3 Slider-Slides vollständig befüllen (Bild, Heading, Text).' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wwd_slider_layout_admin_notice' );

function wwd_leistungen_cards_admin_notice() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, wwd_get_unterseiten_post_types(), true ) ) {
		return;
	}
	if ( 'post' !== $screen->base ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
	if ( ! $post_id ) {
		return;
	}

	$layout = get_post_meta( $post_id, '_layout_template', true );
	if ( 'leistungen-cards' !== $layout ) {
		return;
	}

	$transient_key = 'wwd_leistungen_cards_invalid_icon_' . $post_id;
	if ( get_transient( $transient_key ) ) {
		delete_transient( $transient_key );
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo esc_html( 'Mindestens ein Icon ist kein Bild oder nicht der Media-Kategorie "Icons" zugeordnet und wurde entfernt.' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wwd_leistungen_cards_admin_notice' );

/**
 * Hide other layout metaboxes when Leistungen Cards layout is active.
 */
function wwd_adjust_metaboxes_for_leistungen_cards_layout( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'leistungen-cards' !== $layout ) {
		return;
	}

	remove_meta_box( 'wwd_unterseiten_content', $post_type, 'normal' );
	remove_meta_box( 'wwd_three_img_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_one_img_bottom_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_slider_layout_slides', $post_type, 'normal' );
}
add_action( 'add_meta_boxes', 'wwd_adjust_metaboxes_for_leistungen_cards_layout', 20, 2 );

/**
 * Hide other layout metaboxes when Offer Card layout is active.
 */
function wwd_adjust_metaboxes_for_offer_card_layout( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'offer-card' !== $layout ) {
		return;
	}

	remove_meta_box( 'wwd_unterseiten_content', $post_type, 'normal' );
	remove_meta_box( 'wwd_three_img_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_one_img_bottom_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_leistungen_cards', $post_type, 'normal' );
	remove_meta_box( 'wwd_slider_layout_slides', $post_type, 'normal' );
}
add_action( 'add_meta_boxes', 'wwd_adjust_metaboxes_for_offer_card_layout', 20, 2 );

/**
 * Hide other layout metaboxes when slider layout is active.
 */
function wwd_adjust_metaboxes_for_slider_layout( $post_type, $post ) {
	if ( ! $post ) {
		return;
	}
	$allowed_post_types = wwd_get_unterseiten_post_types();
	if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $post->ID ) ) {
		$post_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$allowed_layouts = wwd_get_allowed_layouts();
	$layout          = get_post_meta( $post_id, '_layout_template', true );
	if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
		$layout = 'two-img-layout';
	}
	if ( 'slider-layout' !== $layout ) {
		return;
	}

	remove_meta_box( 'wwd_unterseiten_content', $post_type, 'normal' );
	remove_meta_box( 'wwd_three_img_texts', $post_type, 'normal' );
	remove_meta_box( 'wwd_one_img_bottom_texts', $post_type, 'normal' );
}
add_action( 'add_meta_boxes', 'wwd_adjust_metaboxes_for_slider_layout', 20, 2 );

function wwd_enqueue_unterseiten_admin_media( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, wwd_get_unterseiten_post_types(), true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( $post_id ) {
		$layout = get_post_meta( $post_id, '_layout_template', true );
		if ( 'slider-layout' === $layout ) {
			return;
		}
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'wwd-admin-media',
		get_theme_file_uri( 'assets/js/admin-media.js' ),
		array( 'jquery' ),
		file_exists( get_theme_file_path( 'assets/js/admin-media.js' ) ) ? filemtime( get_theme_file_path( 'assets/js/admin-media.js' ) ) : null,
		true
	);

	if ( file_exists( get_theme_file_path( 'assets/css/admin.css' ) ) ) {
		wp_enqueue_style(
			'wwd-admin-media',
			get_theme_file_uri( 'assets/css/admin.css' ),
			array(),
			filemtime( get_theme_file_path( 'assets/css/admin.css' ) )
		);
	}
}
add_action( 'admin_enqueue_scripts', 'wwd_enqueue_unterseiten_admin_media' );

function wwd_enqueue_leistungen_cards_admin_media( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, wwd_get_unterseiten_post_types(), true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$layout = get_post_meta( $post_id, '_layout_template', true );
	if ( 'leistungen-cards' !== $layout ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'wwd-admin-leistungen-cards-metabox',
		get_theme_file_uri( 'assets/js/admin-leistungen-cards-metabox.js' ),
		array( 'jquery' ),
		file_exists( get_theme_file_path( 'assets/js/admin-leistungen-cards-metabox.js' ) ) ? filemtime( get_theme_file_path( 'assets/js/admin-leistungen-cards-metabox.js' ) ) : null,
		true
	);

	if ( file_exists( get_theme_file_path( 'assets/css/admin.css' ) ) ) {
		wp_enqueue_style(
			'wwd-admin-leistungen-cards-metabox',
			get_theme_file_uri( 'assets/css/admin.css' ),
			array(),
			filemtime( get_theme_file_path( 'assets/css/admin.css' ) )
		);
	}
}
add_action( 'admin_enqueue_scripts', 'wwd_enqueue_leistungen_cards_admin_media' );

function wwd_enqueue_offer_card_admin_media( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, wwd_get_unterseiten_post_types(), true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$layout = get_post_meta( $post_id, '_layout_template', true );
	if ( 'offer-card' !== $layout ) {
		return;
	}

	wp_enqueue_script(
		'wwd-admin-offer-card-metabox',
		get_theme_file_uri( 'assets/js/admin-offer-card-metabox.js' ),
		array( 'jquery' ),
		file_exists( get_theme_file_path( 'assets/js/admin-offer-card-metabox.js' ) ) ? filemtime( get_theme_file_path( 'assets/js/admin-offer-card-metabox.js' ) ) : null,
		true
	);

	if ( file_exists( get_theme_file_path( 'assets/css/admin.css' ) ) ) {
		wp_enqueue_style(
			'wwd-admin-offer-card-metabox',
			get_theme_file_uri( 'assets/css/admin.css' ),
			array(),
			filemtime( get_theme_file_path( 'assets/css/admin.css' ) )
		);
	}
}
add_action( 'admin_enqueue_scripts', 'wwd_enqueue_offer_card_admin_media' );

function wwd_enqueue_slider_layout_admin_media( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, wwd_get_unterseiten_post_types(), true ) ) {
		return;
	}

	$post_id = 0;
	if ( isset( $_GET['post'] ) ) {
		$post_id = (int) $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = (int) $_POST['post_ID'];
	}
	if ( ! $post_id ) {
		return;
	}

	$layout = get_post_meta( $post_id, '_layout_template', true );
	if ( 'slider-layout' !== $layout ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'wwd-admin-slider-metabox',
		get_theme_file_uri( 'assets/js/admin-slider-metabox.js' ),
		array( 'jquery' ),
		file_exists( get_theme_file_path( 'assets/js/admin-slider-metabox.js' ) ) ? filemtime( get_theme_file_path( 'assets/js/admin-slider-metabox.js' ) ) : null,
		true
	);

	if ( file_exists( get_theme_file_path( 'assets/css/admin.css' ) ) ) {
		wp_enqueue_style(
			'wwd-admin-slider-metabox',
			get_theme_file_uri( 'assets/css/admin.css' ),
			array(),
			filemtime( get_theme_file_path( 'assets/css/admin.css' ) )
		);
	}
}
add_action( 'admin_enqueue_scripts', 'wwd_enqueue_slider_layout_admin_media' );

function wwd_enqueue_referenzen_admin_media( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'referenzen' !== $screen->post_type || 'post' !== $screen->base ) {
		return;
	}

	wp_enqueue_media();
	$js_admin = 'assets/js/admin-seitenbilder.js';
	wp_enqueue_script(
		'wwd-admin-seitenbilder',
		get_theme_file_uri( $js_admin ),
		array( 'jquery' ),
		file_exists( get_theme_file_path( $js_admin ) ) ? filemtime( get_theme_file_path( $js_admin ) ) : null,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'wwd_enqueue_referenzen_admin_media' );

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
			'post_type'      => 'referenzen',
			'posts_per_page' => 4,
			'orderby'        => 'date',
			'order'          => 'DESC',
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

$menu_order_post_types = array_unique(
	array_merge(
		array( 'nav_dienstleistungen', 'referenzen', 'website_weg' ),
		wwd_get_unterseiten_post_types()
	)
);

foreach ( $menu_order_post_types as $post_type ) {
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
		array( 'nav_dienstleistungen', 'referenzen' ),
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
add_action( 'save_post_referenzen', 'wwd_save_nav_card_link_metabox' );

function wwd_add_referenzen_kunde_image_metabox() {
	add_meta_box(
		'wwd_referenzen_kunde_image',
		'Kundenbild (URL)',
		'wwd_render_referenzen_kunde_image_metabox',
		'referenzen',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_referenzen_kunde_image_metabox' );

function wwd_render_referenzen_kunde_image_metabox( $post ) {
	$image_url = get_post_meta( $post->ID, 'referenzen_kundenbild_url', true );
	wp_nonce_field( 'referenzen_kundenbild_save', 'referenzen_kundenbild_nonce' );
	?>
	<div class="wwd-media-field">
		<p>
			<label for="wwd-referenzen-kunde-image"><?php echo esc_html( 'Kundenbild URL' ); ?></label>
		</p>
		<input
			type="text"
			id="wwd-referenzen-kunde-image"
			name="referenzen_kundenbild_url"
			value="<?php echo esc_attr( $image_url ); ?>"
			class="widefat"
			placeholder="<?php echo esc_attr( 'https://example.com/image.jpg' ); ?>"
		/>
		<p>
			<button type="button" class="button wwd-upload-button js-referenzen-media-select"><?php echo esc_html( 'Bild ausw�hlen' ); ?></button>
			<button type="button" class="button wwd-media-remove js-referenzen-media-remove"><?php echo esc_html( 'Entfernen' ); ?></button>
		</p>
		<img src="<?php echo esc_url( $image_url ); ?>" alt="" style="max-width:100%;height:auto;<?php echo empty( $image_url ) ? 'display:none;' : ''; ?>" />
	</div>
	<?php
}

function wwd_save_referenzen_kunde_image_metabox( $post_id ) {
	if ( ! isset( $_POST['referenzen_kundenbild_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['referenzen_kundenbild_nonce'], 'referenzen_kundenbild_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['referenzen_kundenbild_url'] ) ) {
		$image_url = esc_url_raw( wp_unslash( $_POST['referenzen_kundenbild_url'] ) );
		if ( '' === $image_url ) {
			delete_post_meta( $post_id, 'referenzen_kundenbild_url' );
		} else {
			update_post_meta( $post_id, 'referenzen_kundenbild_url', $image_url );
		}
	}
}
add_action( 'save_post_referenzen', 'wwd_save_referenzen_kunde_image_metabox' );

/**
 * Meta box for Referenzen card link.
 */
function wwd_add_referenzen_card_link_metabox() {
	add_meta_box(
		'wwd_referenzen_card_link',
		'Card Link URL',
		'wwd_render_referenzen_card_link_metabox',
		'referenzen',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wwd_add_referenzen_card_link_metabox' );

function wwd_render_referenzen_card_link_metabox( $post ) {
	$link_url = get_post_meta( $post->ID, 'referenzen_card_link_url', true );
	wp_nonce_field( 'referenzen_card_link_save', 'referenzen_card_link_nonce' );
	?>
	<p>
		<label for="wwd-referenzen-card-link"><?php echo esc_html( 'Card Link URL' ); ?></label>
	</p>
	<input
		type="url"
		id="wwd-referenzen-card-link"
		name="referenzen_card_link_url"
		value="<?php echo esc_attr( $link_url ); ?>"
		class="widefat"
		placeholder="<?php echo esc_attr( 'https://example.com' ); ?>"
	/>
	<?php
}

function wwd_save_referenzen_card_link_metabox( $post_id ) {
	if ( ! isset( $_POST['referenzen_card_link_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['referenzen_card_link_nonce'], 'referenzen_card_link_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['referenzen_card_link_url'] ) ) {
		$link_url = esc_url_raw( wp_unslash( $_POST['referenzen_card_link_url'] ) );
		if ( '' === $link_url ) {
			delete_post_meta( $post_id, 'referenzen_card_link_url' );
		} else {
			update_post_meta( $post_id, 'referenzen_card_link_url', $link_url );
		}
	}
}
add_action( 'save_post_referenzen', 'wwd_save_referenzen_card_link_metabox' );

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



/**
 * 1) Admin-Men� hinzuf�gen (wie Vorlage)
 */
add_action('admin_menu', function () {
    add_menu_page(
        'Seitenbilder �ndern',
        'Seitenbilder',
        'manage_options', // wie deine Vorgabe: Capability-Check
        'seitenbilder',
        'wwd_seitenbilder_callback',
        'dashicons-format-image',
        26
    );
});


/**
 * 2) Media-Uploader aktivieren (nur auf dieser Admin-Seite)
 */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_seitenbilder') return;

    wp_enqueue_media();

    $css_admin = 'assets/css/admin-seitenbilder.css';
    $js_admin  = 'assets/js/admin-seitenbilder.js';

    wp_enqueue_style(
        'wwd-seitenbilder-admin',
        get_theme_file_uri( $css_admin ),
        array(),
        file_exists( get_theme_file_path( $css_admin ) ) ? filemtime( get_theme_file_path( $css_admin ) ) : null
    );
    wp_enqueue_script(
        'wwd-admin-seitenbilder',
        get_theme_file_uri( $js_admin ),
        array( 'jquery' ),
        file_exists( get_theme_file_path( $js_admin ) ) ? filemtime( get_theme_file_path( $js_admin ) ) : null,
        true
    );
});


/**
 * 3) Callback: HTML-Formular + Speicherung (wie Vorlage: POST + Nonce + update_option)
 */
function wwd_seitenbilder_callback() {

    if (!current_user_can('manage_options')) {
        wp_die('Keine Berechtigung.');
    }

    // Bildschl�ssel (nur NICHT-SVG)
    $fields = [
        'home-img' => 'Homepage Hero',
		'faecher-home' => 'F�cher Homepage',
		'leistungen' => 'leistungen',
        'kunden' => 'Kunden Hero',
        'ki-integration' => 'KI Integration',
        'news' => 'News Hero',
        'ueber-uns' => 'Ueber uns Hero',
        'kontakt' => 'Kontakt Hero',
    ];

    // Speichern
    if (isset($_POST['wwd_nonce']) && wp_verify_nonce($_POST['wwd_nonce'], 'wwd_bildspeichern')) {
        foreach ($fields as $key => $label) {
            if (isset($_POST[$key])) {
                update_option($key, esc_url_raw($_POST[$key]));
            }
        }

        echo '<div class="notice notice-success is-dismissible"><p>Bilder gespeichert.</p></div>';
    }

    // Ausgabe
    echo '<div class="wrap"><h1>Seitenbilder verwalten</h1><form method="post">';
    wp_nonce_field('wwd_bildspeichern', 'wwd_nonce');

    foreach ($fields as $key => $label) {
        $url = esc_url(get_option($key));
        echo "<h3>{$label}</h3>";
        echo "<input type='text' name='{$key}' value='{$url}' class='widefat'>";
        echo "<button class='button wwd-upload-button'>Bild ausw�hlen</button><br>";
        echo "<img src='{$url}' style='max-width:300px; margin-top:10px; " . ($url ? '' : 'display:none;') . "'><br><br>";
    }

    submit_button('Bilder speichern');
    echo '</form></div>';
}


