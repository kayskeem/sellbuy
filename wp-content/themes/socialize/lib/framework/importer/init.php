<?php
/**
 * Version 0.0.3
 *
 * This file is just an example you can copy it to your theme and modify it to fit your own needs.
 * Watch the paths though.
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'GhostPool_Importer' ) && class_exists( 'GhostPool_Socialize' ) ) {

	if ( file_exists( WP_PLUGIN_DIR . '/socialize-plugin/importer/radium-importer.php' ) ) {			
		require_once( WP_PLUGIN_DIR . '/socialize-plugin/importer/radium-importer.php' );
	}
	
	class GhostPool_Importer extends Radium_Theme_Importer {

		private static $instance;
		public $theme_options_framework = 'redux';
		public $theme_option_name       = 'socialize';
		public $theme_options_file_name = 'theme_options.txt';
		public $widgets_file_name       = 'widgets.json';
		public $content_demo_file_name  = 'content.xml';
		public $widget_import_results;

		public function __construct() {
			$this->demo_files_path = get_template_directory() . '/lib/framework/importer/demo-files/';
			self::$instance = $this;
			parent::__construct();
		}

		public static function getInstance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	
		public function set_demo_menus() {
			$locations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();
			if ( $menus ) {
				foreach( $menus as $menu ) { // assign menus to theme locations
					if ( $menu->name == 'Socialize Primary Main Header Menu' ) {
						$locations['gp-primary-main-header-nav'] = $menu->term_id;	
					} elseif ( $menu->name == 'Socialize Secondary Main Header Menu' ) {
						$locations['gp-secondary-main-header-nav'] = $menu->term_id;					
					} elseif ( $menu->name == 'Socialize Left Small Header Menu' ) {
						$locations['gp-left-small-header-nav'] = $menu->term_id;
						$locations['gp-footer-nav'] = $menu->term_id;	
					} elseif ( $menu->name == 'Socialize Social Icons Menu' ) {
						$locations['gp-right-small-header-nav'] = $menu->term_id;				
					}
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );	
		}

		public function after_wp_importer() {
		
			if ( get_page_by_path( 'home-1' ) ) {				
				update_option( 'page_on_front', get_page_by_path( 'home-1' )->ID );
			}	
			update_option( 'show_on_front', 'page' );
	
			/*$post_content = array(
				'post_title' => 'Custom Homepage Left',					
				'post_type' => 'epx_vcsb',
				'post_status' => 'publish',
				'post_name' => 'gp-homepage-left-sidebar',
				'guid' => 'gp-homepage-left-sidebar',
				'comment_status' => 'closed',
				'post_content'  => '[vc_row][vc_column][blog widget_title="' . esc_html__( 'Latest News', 'socialize' ) . '" per_page="20" image_width="80" image_height="80" excerpt_length="0" meta_cats="1"][/vc_column][/vc_row]',
			);
			$post = wp_insert_post( $post_content );
			$settings = 'a:4:{s:8:"behavior";s:6:"before";s:14:"behavior_value";s:0:"";s:9:"container";s:7:"default";s:6:"global";s:2:"no";}';
			if ( $post ) {
				update_post_meta( $post, 'epx_vcsb_settings', $settings );
			}*/	

			// Delete "Hello World" post
			$default_post = get_posts( array( 'title' => 'Hello World!' ) );
			if ( $default_post ) {				
				wp_delete_post( $default_post[0]->ID );
			}
		
		}					
		
	}

	GhostPool_Importer::getInstance();

}