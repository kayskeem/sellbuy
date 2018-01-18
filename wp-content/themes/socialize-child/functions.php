<?php

/*
* Add your own functions here. You can also copy some of the theme functions into this file and WordPress will use these functions instead of the original functions.
*/

/**
 * Load parent style.css
 *
 */
if ( ! function_exists( 'ghostpool_enqueue_child_styles' ) ) {
	function ghostpool_enqueue_child_styles() { 
		wp_enqueue_style( 'gp-parent-style', get_template_directory_uri() . '/style.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'ghostpool_enqueue_child_styles' );

/**
 * Load translation file in child theme
 *
 */
if ( ! function_exists( 'ghostpool_child_theme_language' ) ) {
	function ghostpool_child_theme_language() {
		$language_directory = get_stylesheet_directory() . '/languages';
		load_child_theme_textdomain( 'socialize', $language_directory );
	}
}
add_action( 'after_setup_theme', 'ghostpool_child_theme_language' );

?>