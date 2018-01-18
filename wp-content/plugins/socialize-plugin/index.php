<?php
/*
Plugin Name: Socialize Plugin
Plugin URI: 
Description: A required plugin for Socialize theme you purchased from ThemeForest. It includes a number of features that you can still use if you switch to another theme.
Version: 3.9.5
Author: GhostPool
Author URI: http://themeforest.net/user/GhostPool/portfolio?ref=GhostPool
License: You should have purchased a license from ThemeForest.net
Text Domain: socialize-plugin
*/

// Ensure latest version of plugin installed
function ghostpool_socialize_plugin_update() {}
	
if ( ! class_exists( 'GhostPool_Socialize' ) ) {

	class GhostPool_Socialize {

		public function __construct() {

			// Load plugin translations
			add_action( 'plugins_loaded', array( &$this, 'ghostpool_plugin_load_textdomain' ) );	
						
			// Add shortcode support to Text widget
			add_filter( 'widget_text', 'do_shortcode' );

			// Add excerpt support to pages
			if ( ! function_exists( 'ghostpool_add_excerpts_to_pages' ) ) {
				function ghostpool_add_excerpts_to_pages() {
					 add_post_type_support( 'page', 'excerpt' );
				}
			}
			add_action( 'init', 'ghostpool_add_excerpts_to_pages' );

			// Add post tags support to pages
			if ( ! function_exists( 'ghostpool_page_tags_support' ) ) {
				function ghostpool_page_tags_support() {
					register_taxonomy_for_object_type( 'post_tag', 'page' );
				}
			}
			add_action( 'init', 'ghostpool_page_tags_support' );

			// Display pages in post tag queries
			if ( ! function_exists( 'ghostpool_page_tags_support_query' ) ) {
				function ghostpool_page_tags_support_query( $wp_query ) {
					if ( $wp_query->get( 'tag' ) ) {
						$wp_query->set( 'post_type', 'any' );
					}	
				}
			}
			add_action( 'pre_get_posts', 'ghostpool_page_tags_support_query' );

			// Add user profile fields
			if ( ! function_exists( 'ghostpool_custom_profile_methods' ) ) {
				function ghostpool_custom_profile_methods( $profile_fields ) {
					$profile_fields['twitter'] = esc_html__( 'Twitter URL', 'socialize-plugin' );
					$profile_fields['facebook'] = esc_html__( 'Facebook URL', 'socialize-plugin' );
					$profile_fields['googleplus'] = esc_html__( 'Google+ URL', 'socialize-plugin' );
					$profile_fields['pinterest'] = esc_html__( 'Pinterest URL', 'socialize-plugin' );
					$profile_fields['youtube'] = esc_html__( 'YouTube URL', 'socialize-plugin' );
					$profile_fields['vimeo'] = esc_html__( 'Vimeo URL', 'socialize-plugin' );
					$profile_fields['flickr'] = esc_html__( 'Flickr URL', 'socialize-plugin' );
					$profile_fields['linkedin'] = esc_html__( 'LinkedIn URL', 'socialize-plugin' );
					$profile_fields['instagram'] = esc_html__( 'Instagram URL', 'socialize-plugin' );
					return $profile_fields;
				}
			}
			add_filter( 'user_contactmethods', 'ghostpool_custom_profile_methods' );

			// Load shortcodes
			if ( ! class_exists( 'GhostPool_Shortcodes' ) ) {
				require_once( dirname( __FILE__ ) . '/shortcodes/theme-shortcodes.php' );
				$GhostPool_Shortcodes = new GhostPool_Shortcodes();
			}
			
			// Load portfolio post type
			if ( ! post_type_exists( 'gp_portfolio' ) && ! class_exists( 'GhostPool_Portfolio' ) ) {
				require_once( dirname( __FILE__ ) . '/inc/portfolio-tax.php' );
				$GhostPool_Portfolios = new Ghostpool_Portfolios();
			}
			
			// Load slide post type
			if ( ! post_type_exists( 'gp_slide' ) && ! class_exists( 'GhostPool_Slides' ) ) {
				require_once( dirname( __FILE__ ) . '/inc/slide-tax.php' );
				$GhostPool_Slides = new Ghostpool_Slides();
			}
																
		} 
		
		public static function ghostpool_activate() {} 		
		
		public static function ghostpool_deactivate() {}

		public function ghostpool_plugin_load_textdomain() {
			load_plugin_textdomain( 'socialize-plugin', false, trailingslashit( WP_LANG_DIR ) . 'plugins/' );
			load_plugin_textdomain( 'socialize-plugin', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}
						
	}
	
}

// User registration emails
$theme_variable = get_option( 'socialize' );
if ( ! function_exists( 'wp_new_user_notification' ) && ! function_exists( 'bp_is_active' ) && ( isset ( $theme_variable['popup_box'] ) && $theme_variable['popup_box'] == 'enabled' ) ) {
	function wp_new_user_notification( $user_id, $deprecated = null, $notify = 'both' ) {

		if ( $deprecated !== null ) {
			_deprecated_argument( __FUNCTION__, '4.3.1' );
		}
	
		global $wpdb;
		$user = get_userdata( $user_id );
		
		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );

		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		
		// Admin email
		$message  = sprintf( esc_html__( 'New user registration on your blog %s:', 'socialize-plugin' ), $blogname ) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'Username: %s', 'socialize-plugin' ), $user_login ) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'Email: %s', 'socialize-plugin' ), $user_email ) . "\r\n";
		$message = apply_filters( 'gp_registration_notice_message', $message, $blogname, $user_login, $user_email );
		@wp_mail( get_option( 'admin_email' ), sprintf( apply_filters( 'gp_registration_notice_subject', esc_html__( '[%s] New User Registration', 'socialize-plugin' ), $blogname ), $blogname ), $message );

		if ( 'admin' === $notify || empty( $notify ) ) {
			return;
		}
		
		// User email
		$message  = esc_html__( 'Hi there,', 'socialize-plugin' ) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'Welcome to %s.', 'socialize-plugin' ), $blogname ) . "\r\n\r\n";
		$message .= sprintf( esc_html__( 'Username: %s', 'socialize-plugin' ), $user_login ) . "\r\n";
		$message .= esc_html__( 'Password: [use the password you entered when signing up]', 'socialize-plugin' ) . "\r\n\r\n";
		$message .= 'Please login at ' . home_url( '/#login' ) . "\r\n\r\n";	
		$message = apply_filters( 'gp_registered_user_message', $message, $blogname, $user_login, $user_email );
		wp_mail( $user_email, sprintf( apply_filters( 'gp_registered_user_subject', esc_html__( '[%s] Your username and password', 'socialize-plugin' ), $blogname ), $blogname ), $message );

	}
}

// Active/deactivate plugin
if ( class_exists( 'GhostPool_Socialize' ) ) {

	register_activation_hook( __FILE__, array( 'GhostPool_Socialize', 'ghostpool_activate' ) );
	register_deactivation_hook( __FILE__, array( 'GhostPool_Socialize', 'ghostpool_deactivate' ) );

	$ghostpool_plugin = new GhostPool_Socialize();

}

?>