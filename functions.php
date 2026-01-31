
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
	$css_base     = 'assets/css/base.css';
	$css_nav      = 'assets/css/nav.css';
	$css_sections = 'assets/css/sections.css';

	// JS-Dateien
	$js_base       = 'assets/js/base.js';
	$js_animations = 'assets/js/animations.js';

	/**
	 * CSS einbinden
	 */
	wp_enqueue_style(
		'wwd-website-redesign-base',
		get_theme_file_uri( $css_base ),
		array(),
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

	/**
	 * JS einbinden
	 * → falls base.js KEIN jQuery nutzt, einfach 'jquery' entfernen
	 */
	wp_enqueue_script(
		'wwd-website-redesign-base',
		get_theme_file_uri( $js_base ),
		array( 'jquery' ),
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




function wwd_seitenbilder_callback() {

    if (!current_user_can('manage_options')) {
        wp_die('Keine Berechtigung.');
    }

    // Bildschlüssel (nur NICHT-SVG)
    $fields = [
        'home' => 'Homepage Hero ',
        'ansatz-1' => 'ansatz-1 ',
        'ansatz-2' => 'ansatz-2 ',
        'weg-zur-website-1' => 'weg-zur-website-1 ',
        'weg-zur-website-2' => 'weg-zur-website-2 ',
        'weg-zur-website-3' => 'weg-zur-website-3 ',
        'weg-zur-website-4' => 'weg-zur-website-4 ',
        'kunden' => 'Kunden Hero ',
        'das-machen-wir-moeglich-1' => 'das-machen-wir-moeglich-1 ',
        'das-machen-wir-moeglich-2' => 'das-machen-wir-moeglich-2 ',
        'das-machen-wir-moeglich-3' => 'das-machen-wir-moeglich-3 ',
        'ki-home' => 'KI Homepage ',
        'ki-integration' => 'KI Integration ',
        'news' => 'News Hero ',
        'ueber-uns' => 'Über uns Hero ',
        'kontakt' => 'Kontakt Hero ',
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
        echo "<button class='button wwd-upload-button'>Bild auswählen</button><br>";
        echo "<img src='{$url}' style='max-width:300px; margin-top:10px; " . ($url ? '' : 'display:none;') . "'><br><br>";
    }

    submit_button('Bilder speichern');
    echo '</form></div>';
}

