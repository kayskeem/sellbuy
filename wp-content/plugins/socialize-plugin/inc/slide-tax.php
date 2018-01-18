<?php 

if ( ! class_exists( 'GhostPool_Slides' ) ) {

	class GhostPool_Slides {

		public function __construct() {
			add_action( 'init', array( &$this, 'ghostpool_post_type_slides' ), 1 );
			add_action( 'manage_posts_custom_column',  array( &$this, 'ghostpool_slides_custom_columns' ) );
		}

		public function ghostpool_post_type_slides() {

			/*--------------------------------------------------------------
			Slide Post Type
			--------------------------------------------------------------*/	
	
			register_post_type( 'gp_slide', array( 
				'labels' => array( 
					'name' => esc_html__( 'Slides', 'socialize-plugin' ),
					'singular_name' => esc_html__( 'Slide', 'socialize-plugin' ),
					'menu_name' => esc_html__( 'Slides', 'socialize-plugin' ),
					'all_items' => esc_html__( 'All Slides', 'socialize-plugin' ),
					'add_new' => _x( 'Add New', 'portfolio', 'socialize-plugin' ),
					'add_new_item' => esc_html__( 'Add New Slide', 'socialize-plugin' ),
					'edit_item' => esc_html__( 'Edit Slide', 'socialize-plugin' ),
					'new_item' => esc_html__( 'New Slide', 'socialize-plugin' ),
					'view_item' => esc_html__( 'View Slide', 'socialize-plugin' ),
					'search_items' => esc_html__( 'Search Slides', 'socialize-plugin' ),
					'not_found' => esc_html__( 'No slides found', 'socialize-plugin' ),
					'not_found_in_trash' => esc_html__( 'No slides found in Trash', 'socialize-plugin' ),
				 ),
				'public' => true,
				'exclude_from_search' => false,
				'show_ui' => true,
				'show_in_nav_menus' => true,
				'_builtin' => false,
				'_edit_link' => 'post.php?post=%d',
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array( 'slug' => 'slide' ),
				'menu_position' => 20,
				'with_front' => true,
				'has_archive' => 'gp_slides',
				'supports' => array( 'title', 'thumbnail' )
			 ) );
	
	
			/*--------------------------------------------------------------
			Slide Categories Taxonomy
			--------------------------------------------------------------*/
			
			register_taxonomy( 'gp_slides', 'gp_slide', array( 
				'labels' => array( 
					'name' => esc_html__( 'Slide Categories', 'socialize-plugin' ),
					'singular_name' => esc_html__( 'Slide Category', 'socialize-plugin' ),
					'all_items' => esc_html__( 'All Slide Categories', 'socialize-plugin' ),
					'add_new' => _x( 'Add New', 'portfolio', 'socialize-plugin' ),
					'add_new_item' => esc_html__( 'Add New Slide Category', 'socialize-plugin' ),
					'edit_item' => esc_html__( 'Edit Slide Category', 'socialize-plugin' ),
					'new_item' => esc_html__( 'New Slide Category', 'socialize-plugin' ),
					'view_item' => esc_html__( 'View Slide Category', 'socialize-plugin' ),
					'search_items' => esc_html__( 'Search Slide Categories', 'socialize-plugin' ),
					'menu_name' => esc_html__( 'Slide Categories', 'socialize-plugin' )
				 ),
				'show_in_nav_menus' => true,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => 'slides' )
			 ) );


			register_taxonomy_for_object_type( 'gp_slides', 'gp_slide' );


			/*--------------------------------------------------------------
			Slide Admin Columns
			--------------------------------------------------------------*/

			if ( ! function_exists( 'ghostpool_slide_edit_columns' ) ) { 
				function ghostpool_slides_edit_columns( $columns ) {
					$columns = array( 
						'cb'               => '<input type="checkbox" />',
						'title'            => esc_html__( 'Title', 'socialize-plugin' ),	
						'slide_categories' => esc_html__( 'Categories', 'socialize-plugin' ),
						'slide_image'      => esc_html__( 'Image', 'socialize-plugin' ),				
						'date'             => esc_html__( 'Date', 'socialize-plugin' )
					 );
					return $columns;
				}	
			}
			add_filter( 'manage_edit-gp_slide_columns', 'ghostpool_slides_edit_columns' );
		
		}

		public function ghostpool_slides_custom_columns( $column ) {
			switch ( $column ) {
				case 'slide_categories':
					echo get_the_term_list( get_the_ID(), 'gp_slides', '', ', ', '' );
				break;
				case 'slide_image':
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( array( 50, 50 ) );
					}
				break;					
			}
		}	
		
	}

}

?>