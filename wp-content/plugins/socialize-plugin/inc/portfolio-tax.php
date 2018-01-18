<?php

if ( ! class_exists( 'GhostPool_Portfolios' ) ) {

	class GhostPool_Portfolios {

		public function __construct() {
			add_action( 'init', array( &$this, 'ghostpool_post_type_portfolio' ), 1 );
			add_action( 'manage_posts_custom_column',  array( &$this, 'ghostpool_portfolio_custom_columns' ) );
		}

		public function ghostpool_post_type_portfolio() {
		
			if ( ! function_exists( 'ghostpool_option' ) ) {
				$portfolio_cat_slug = 'portfolios';
			} else {
				$portfolio_cat_slug = ghostpool_option( 'portfolio_cat_slug' );
			}

			if ( ! function_exists( 'ghostpool_option' ) ) {
				$portfolio_item_slug = 'portfolio';
			} else {
				$portfolio_item_slug = ghostpool_option( 'portfolio_item_slug' );
			}
				
			/*--------------------------------------------------------------
			Portfolio Item Post Type
			--------------------------------------------------------------*/	
	
			register_post_type( 'gp_portfolio_item', array( 
				'labels' => array( 
					'name' => esc_html__( 'Portfolio Items', 'socialize-plugin' ),
					'singular_name' => esc_html__( 'Portfolio Item', 'socialize-plugin' ),
					'menu_name' => esc_html__( 'Portfolio Items', 'socialize-plugin' ),
					'all_items' => esc_html__( 'All Portfolio Items', 'socialize-plugin' ),
					'add_new' => _x( 'Add New', 'portfolio', 'socialize-plugin' ),
					'add_new_item' => esc_html__( 'Add New Portfolio Item', 'socialize-plugin' ),
					'edit_item' => esc_html__( 'Edit Portfolio Item', 'socialize-plugin' ),
					'new_item' => esc_html__( 'New Portfolio Item', 'socialize-plugin' ),
					'view_item' => esc_html__( 'View Portfolio Item', 'socialize-plugin' ),
					'search_items' => esc_html__( 'Search Portfolio Items', 'socialize-plugin' ),
					'not_found' => esc_html__( 'No portfolio items found', 'socialize-plugin' ),
					'not_found_in_trash' => esc_html__( 'No portfolio items found in Trash', 'socialize-plugin' ),
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'_builtin' => false,
				'_edit_link' => 'post.php?post=%d',
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array( 'slug' => sanitize_title( $portfolio_item_slug ) ),
				'menu_position' => 20,
				'with_front' => true,
				'taxonomies' => array( 'post_tag' ),
				'has_archive' => sanitize_title( $portfolio_cat_slug ),
				'supports' => array( 'title', 'thumbnail', 'editor', 'author', 'comments', 'custom-fields' )
			 ) );
	
	
			/*--------------------------------------------------------------
			Portfolio Categories Taxonomy
			--------------------------------------------------------------*/
			
			register_taxonomy( 'gp_portfolios', 'gp_portfolio_item', array( 
				'labels' => array( 
					'name' => esc_html__( 'Portfolio Categories', 'socialize-plugin' ),
					'singular_name' => esc_html__( 'Portfolio Category', 'socialize-plugin' ),
					'all_items' => esc_html__( 'All Portfolio Categories', 'socialize-plugin' ),
					'add_new' => _x( 'Add New', 'portfolio', 'socialize-plugin' ),
					'add_new_item' => esc_html__( 'Add New Portfolio Category', 'socialize-plugin' ),
					'edit_item' => esc_html__( 'Edit Portfolio Category', 'socialize-plugin' ),
					'new_item' => esc_html__( 'New Portfolio Category', 'socialize-plugin' ),
					'view_item' => esc_html__( 'View Portfolio Category', 'socialize-plugin' ),
					'search_items' => esc_html__( 'Search Portfolio Categories', 'socialize-plugin' ),
					'menu_name' => esc_html__( 'Portfolio Categories', 'socialize-plugin' )
				 ),
				'show_in_nav_menus' => true,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => sanitize_title( $portfolio_cat_slug ) )
			 ) );


			register_taxonomy_for_object_type( 'gp_portfolios', 'gp_portfolio_item' );


			/*--------------------------------------------------------------
			Portfolio Item Admin Columns
			--------------------------------------------------------------*/

			function ghostpool_portfolio_item_edit_columns( $columns ) {
				$columns = array( 
					'cb'                   => '<input type="checkbox" />',
					'title'                => esc_html__( 'Title', 'socialize-plugin' ),	
					'portfolio_categories' => esc_html__( 'Categories', 'socialize-plugin' ),
					'portfolio_image'      => esc_html__( 'Image', 'socialize-plugin' ),				
					'date'                 => esc_html__( 'Date', 'socialize-plugin' )
				 );
				return $columns;
			}	
			add_filter( 'manage_edit-gp_portfolio_item_columns', 'ghostpool_portfolio_item_edit_columns' );
		
		}

		public function ghostpool_portfolio_custom_columns( $column ) {
			switch ( $column ) {
				case 'portfolio_categories':
					echo get_the_term_list( get_the_ID(), 'gp_portfolios', '', ', ', '' );
				break;
				case 'portfolio_image':
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( array( 50, 50 ) );
					}
				break;					
			}
		}

	}

}

?>