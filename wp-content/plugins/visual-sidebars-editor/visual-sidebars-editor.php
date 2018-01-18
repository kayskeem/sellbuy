<?php
/*
Plugin Name: Visual Sidebar Editor
Plugin URI: http://codecanyon.net/user/ERROPiX/portfolio?ref=ERROPiX
Description: An addon that allow you to use WPBakery Visual Composer or wordress editor to override sidebars
Version: 1.2.3
Author: ERROPiX
Author URI: http://codecanyon.net/user/ERROPiX/portfolio?ref=ERROPiX
*/

namespace ERROPiX\VCSE;

define( 'epx_vcsb_version', '1.2.3' );
define( 'epx_vcsb_url', plugin_dir_url( __FILE__, '/' ) );
define( 'epx_vcsb_path', plugin_dir_path( __FILE__, '/' ) );

require epx_vcsb_path . 'includes/classes/base.php';
require epx_vcsb_path . 'includes/classes/object.php';
require epx_vcsb_path . 'includes/classes/widget.php';

class Main extends Base {

	const post_type = 'epx_vcsb';
	const page_slug = 'visual-sidebar-editor';
	
	public function __construct() {
		$this->admin_url = admin_url( 'themes.php?page='.self::page_slug );
		if( $this->get_var('post_type')==self::post_type ) {
			header( "location: {$this->admin_url}" );
			die;
		}
		
		$this->styles = array();
		$this->sidebars = array();
		$this->widgets = array();
		$this->admin_notices = array();

		register_activation_hook( __FILE__, $this->cb('activate') );
		
		$this->add_action( 'after_setup_theme' );
		$this->add_action( 'init', 999 );
		$this->add_action( 'admin_footer' );
		$this->add_action( 'wp_footer' );

		$this->add_filter( 'wp_redirect' );
		$this->add_filter( 'wp_enqueue_scripts' );
		$this->add_filter( 'vc_shortcode_output', 5, 3 );
	}
	
	// Fix for VC grid shortcodes
	public function vc_shortcode_output( $output, $instance=null, $atts=array() ){
		$id = get_the_ID();
		
		if( is_a($instance, 'WPBakeryShortCode_VC_Basic_Grid') ) {
			$marker = '<!-- VC-GRID-FIXED -->';
			if( strpos($output, $marker) === false ) {
				$output = preg_replace( '/&quot;page_id&quot;:\d+/i', '&quot;page_id&quot;:'.$id, $output);
				$output = preg_replace( '/post-id="\d+"/i', 'post-id="'.$id.'"', $output);
				$output.= $marker;
			}
		}
		
		return $output;
	}

	public function activate() {
		// get the previous plugin version
		$previous_version = get_site_option( 'epx_vcsb_version', '1.0.1' );
		
		// If previous version is older than version 1.0.2
		if( version_compare( $previous_version, '1.0.2', '<' ) ) {
			// Upgrade data
			$posts = get_posts(array(
				'post_type' => self::post_type,
				'posts_per_page' => -1
			));

			foreach( $posts as $post ) {
				update_post_meta( $post->ID, 'epx_vcsb_settings', array(
					'behavior' => 'replace',
					'behavior_value' => '',
					'container' => 'default',
				));
			}
		}

		// Store the new version
		update_site_option( 'epx_vcsb_version', epx_vcsb_version );
	}
	
	public function after_setup_theme() {
		$this->enabled_vc_composer = defined('WPB_VC_VERSION') && function_exists('vc_disable_frontend');
	}
	
	public function init() {
		$this->messages = array(
			// Save/Publish sidebar
			'1' => array(
				'updated',
				"Sidebar successfully updated"
			),
			'2' => array(
				'error',
				"There was some errors while saving this sidebar!"
			),

			// Import sidebar
			'3' => array(
				'updated',
				"Sidebar successfully imported"
			),
			'4' => array(
				'error',
				"There was some errors while importing your sidebar content!"
			),

			// Restore sidebar
			'5' => array(
				'updated',
				"Sidebar successfully restored to revision from %s",
			),

			// Delete sidebar
			'10' => array(
				'updated',
				"Sidebar successfully deleted"
			),
			'11' => array(
				'error',
				"There was some errors while deleting this sidebar!"
			),

			// Admin notines
			'20' => array(
				'error',
				'Your current active theme doesn\'t seems to be supporting sidebars!'
			)
		);

		if( !current_theme_supports('widgets') ) {
			$this->admin_notices[] = $this->messages[20];
		}
		if( !empty( $this->admin_notices ) ) {
			$this->add_action( 'admin_notices' );
			return;
		}

		$args = array(
			'label'					=> 'Visual Sidebar Editor',
			'supports'				=> array( 'title', 'editor', 'revisions' ),
			'public'				=> true,
			'show_ui'				=> false,
			'rewrite'				=> false,
			'show_in_nav_menus'		=> false,
			'exclude_from_search'	=> true,
			'labels' => array (
				'name' => 'Visual Sidebar Editor',
			)
		);
		register_post_type( self::post_type, $args );

		$this->add_action( 'admin_init' );
		$this->add_action( 'admin_menu' );
		$this->add_action( 'current_screen' );
		$this->add_filter( 'sidebars_widgets', 0 );
		$this->add_filter( 'get_edit_post_link', 10, 2 );
		
		// Fix Ultimate Addons conflicts
		if( !is_admin() && function_exists('bsf_get_option') ) {
			if( bsf_get_option('ultimate_global_scripts') != 'enable' ) {
				bsf_update_option('ultimate_global_scripts', 'enable');
			}
		}
	}

	public function wp_redirect( $location ) {
		global $post;

		$matches = array();
		if( preg_match( "/sidebar=([^&]+)&message=(\d+)&revision=(\d+)/i", $location, $matches ) ) {
			$location = add_query_arg( 'sidebar', $matches[1], $this->admin_url );

			$this->set_cookie( 'message', $matches[2] );
			$this->set_cookie( 'revision', $matches[3] );
		}
		return $location;
	}

	public function get_edit_post_link( $link, $post_id ) {
		$post = get_post( $post_id );
		if( $post->post_type == self::post_type ) {
			$link = add_query_arg( 'sidebar', $post->post_name, $this->admin_url );
		}
		return $link;
	}

	private function get_sidebar_id( $sidebar_id = null ) {
		global $wp_registered_sidebars;

		if( !$sidebar_id )
			$sidebar_id = $this->request_var( 'sidebar' );

		if( empty( $wp_registered_sidebars[$sidebar_id] ) )
			$sidebar_id = current( array_keys($wp_registered_sidebars) );
		
		$sidebar_id = apply_filters( 'vse_get_sidebar_id', $sidebar_id );
		return $sidebar_id;
	}

	private function get_sidebar_slug( $sidebar_id = null ) {
		$sidebar_slug = $this->get_sidebar_id( $sidebar_id );

		$sidebar_slug = apply_filters( 'vse_get_sidebar_slug', $sidebar_slug, $sidebar_id );
		return $sidebar_slug;
	}

	private function get_sidebar_post( $sidebar_id = null ) {
		$sidebar_id = $this->get_sidebar_id( $sidebar_id );
		$sidebar_slug = $this->get_sidebar_slug( $sidebar_id );

		$args = array (
			'post_type' => self::post_type,
			'name' => $sidebar_slug,
			'post_status' => array('publish', 'private')
		);
		
		$query = new \WP_Query( $args );

		$post = new Object;
		$settings = array();

		if( $query->have_posts() ) {
			$post = $query->post;
			$settings = get_post_meta( $post->ID, 'epx_vcsb_settings', true );
		} else {
			$data = array(
				'post_title' => '',
				'post_content' => '',
				'post_type' => self::post_type,
				'post_name' => $sidebar_slug,
				'post_status' => 'private',
				'guid' => $sidebar_slug,
			);
			
			// Save post data
			$post_id = wp_insert_post( $data );
			$post = get_post( $post_id );
		}

		$post->settings = new Object($settings);
		$post = apply_filters( 'vse_get_sidebar_post', $post, $sidebar_id );

		return $post;
	}

	private function setup_sidebar_data( $sidebar_id = null ) {
		global $post, $current_sidebar, $wp_registered_sidebars;
		$sidebar_id = $this->get_sidebar_id( $sidebar_id );

		$post = $this->get_sidebar_post( $sidebar_id );
		
		$current_sidebar = $wp_registered_sidebars[ $sidebar_id ];

		$revisions = wp_get_post_revisions( $post->ID );
		$revisions = apply_filters( 'vse_get_sidebar_revisions', $revisions, $sidebar_id );

		$current_sidebar['revisions'] = $revisions;
		$current_sidebar['revisions_count'] = count( $revisions );
		$current_sidebar['revision_id'] = key( $revisions );
	}

	private function process_sidebar_data() {
		if( !current_user_can('edit_theme_options') ) {
			return;
		}

		if( empty($_POST['save']) || $_POST['save']!=self::post_type ) {
			return;
		}

		check_admin_referer( 'epx_vcsb_save' );

		$sidebar_id	= $this->post_var( 'sidebar_id' );
		$sidebar	= $this->get_sidebar_post( $sidebar_id );
		
		$redirect_to = add_query_arg( 'sidebar', $sidebar_id, $this->admin_url );
		
		$action = $this->post_var('action');
		if( $action == 'publish' ) {
			$action = 'save';
			$_POST['post_status'] = 'publish';
		}

		switch ( $action ) {
			case 'save':
				$message = '2';
				
				$sidebar->post_title	= $this->post_var( 'post_title' );
				$sidebar->post_content	= $this->post_var( 'content' );
				$sidebar->post_status	= $this->post_var( 'post_status' );
				
				// Save post data
				$post_id = wp_insert_post( $sidebar->to_array() );

				if( $post_id > 0 ) {
					// Save settings
					$settings = $this->post_var('settings');
					update_post_meta( $post_id, 'epx_vcsb_settings', $settings );

					$message = '1';
				}

				break;

			case 'import':
				$message = '4';
				
				$content = $this->post_var('content');
				if( empty($content) ) {
					break;
				}
				
				$override = $this->post_var('override', false);
				if( $override ) {
					$sidebar->post_content  = $content;
				} else {
					$sidebar->post_content .= PHP_EOL;
					$sidebar->post_content .= $content;
				}
				
				// Save imported post data
				$post_id = wp_insert_post( $sidebar->to_array() );

				if( $post_id > 0 ) {
					$message = '3';
				}

				break;

			case 'delete':
				$message = '11';

				$deleted = wp_delete_post( $sidebar->ID, true );
				
				if( $deleted ) {
					$message = '10';
				}
				break;
			
			default:
				break;
		}

		if( isset( $message ) ) {
			$this->set_cookie( 'message', $message );
		}

		$redirect_to = str_replace( " ", '+', $redirect_to );
		wp_redirect( $redirect_to );
	}

	public function admin_init() {
		$result = $this->process_sidebar_data();
	}
	
	public function current_screen() {
		global $vc_manager, $current_screen, $post, $hook_suffix;
		
		if( $current_screen->id == 'appearance_page_'.self::page_slug ) {
			
			// modify screen details
			$current_screen->base = 'post';
			$hook_suffix = 'post.php';
			
			$this->setup_sidebar_data();

			$message_id = $this->flash_cookie( 'message' );
			$message = $this->get_value( $this->messages, $message_id );
			if( $message ) {
				switch( $message_id ) {
					case '5':
						$revision_id = $this->flash_cookie('revision');
						$revision_title = wp_post_revision_title( $revision_id, false );
						$message[1] = sprintf( $message[1], $revision_title );
						break;
				}
			}
			$this->message = $message;

			if( $this->enabled_vc_composer ) {
				vc_disable_frontend();
				
				$post_types = vc_editor_post_types();
				if( !in_array(self::post_type, $post_types) ) {
					$post_types[] = self::post_type;
					vc_editor_set_post_types( $post_types );
					header('location: '.$_SERVER['REQUEST_URI']);
				}
				
				$editor = $vc_manager->backendEditor();
				$editor->addHooksSettings();
				$editor->registerBackendJavascript();
				$editor->registerBackendCss();
				$editor->render(self::post_type);
			}
			
			// Fix Jupiter assets loading by tricking mk_theme_is_post_type_post
			if( function_exists('mk_theme_is_post_type_post') ) {
				$_SERVER['PHP_SELF'] = 'wp-admin/post.php';
			}
			
			// Enqueue assets
			$this->add_action( 'admin_print_styles' );
			$this->add_action( 'admin_print_scripts' );
		}
	}

	public function generate_widget( $post, $sidebar_id ) {
		global $wp_registered_widgets;
		
		$instance = new Widget($post);
		$widget = $instance->get_widget();
		$widget_id = $instance->id;

		$wp_registered_widgets[$widget_id] = $widget;
		$this->widgets[$widget_id] = $widget;

		$sidebar_widgets = &$this->sidebars[ $sidebar_id ];
		
		$behavior = $post->settings->behavior;
		if( !is_array($sidebar_widgets) || empty($sidebar_widgets) ) {
			$behavior = 'override';
		}
		
		switch ( $behavior ) {
			case 'before':
				array_unshift( $sidebar_widgets, $widget_id );
				break;

			case 'after':
				$sidebar_widgets[] = $widget_id;
				break;

			case 'position':
				$position = intval($post->settings->behavior_value);
				if( $position ) {
					$position--;
				}
				array_splice( $sidebar_widgets, $position, 0, $widget_id);
				break;

			default:
				$sidebar_widgets = array( $widget_id );
				break;
		}
		return $widget_id;
	}

	public function sidebars_widgets( $sidebars_widgets ) {
		if( is_admin() || is_customize_preview() || ( $this->enabled_vc_composer && vc_is_page_editable() ) ) {
			return $sidebars_widgets;
		}
		
		if( empty( $this->sidebars ) ) {
			global $wp_registered_sidebars;
			$this->sidebars = &$sidebars_widgets;
			
			foreach( array_keys($wp_registered_sidebars) as $sidebar_id ) {
				$sidebar_slug = $this->get_sidebar_slug( $sidebar_id );
				$post = $this->get_sidebar_post( $sidebar_slug );
				if( $post && $post->post_status=='publish' ) {
					$this->generate_widget( $post, $sidebar_id );
				}
			}
		}
		
		return $this->sidebars;
	}

	public function display_callback( $args, $w_post ) {
		global $post;
		
		if( !$w_post || !$w_post->settings ) return;
		
		$post = $w_post;
		extract( $args );
		
		if( empty( $this->styles[$id] ) ) {
			$custom_css = get_post_meta( $post->ID, '_wpb_post_custom_css', true );
			$custom_css.= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
			$this->inline_css( $custom_css, $id );
		}

		$output = '';
		$content = apply_filters( 'the_content', $post->post_content );
		if( $post->settings->container == 'default' ) {
			$output.= $before_widget;
			$title = apply_filters( 'widget_title', $post->post_title );
			if( !empty($title) ) {
				$output.= $before_title . $title . $after_title;
			}
			$output.= $content;
			$output.= $after_widget;
		} else {
			$output.= $content;
		}
		echo $output;

		wp_reset_postdata();
	}

	public function render_editor() {
		global $vc_manager, $post, $wp_registered_sidebars, $current_sidebar;

		$available_sidebars = $wp_registered_sidebars;

		$post_ID = $post->ID;
		$post_title = $post->post_title;
		$post_content = $post->post_content;
		$post_status = $post->post_status;
		$post_name = $post->post_name;
		
		$settings = $post->settings;
		$settings = apply_filters('vcse_sidebar_settings', $settings, $post);
		
		$js_conflicts = array(
			// Remove Yoast SEO js
			'yoast-seo-admin-global-script',
			'yoast-seo-post-scraper',
		);
		
		foreach( $js_conflicts as $js ) {
			wp_dequeue_script( $js );
			wp_deregister_script( $js );
		}
		
		require epx_vcsb_path . '/includes/editors/vc_editor.php';
	}

	public function admin_menu() {
		$title = "Sidebars Editor";
		$cap = 'edit_theme_options';
		$page = self::page_slug;
		$callback = $this->cb( 'render_editor' );
		add_theme_page( $title, $title, $cap, $page, $callback );
	}

	public function admin_notices() {
		foreach( $this->admin_notices as $notice ) {
			?><div class="<?php echo $notice[0] ?>"><p><?php echo $notice[1] ?></p></div><?php
		}
	}

	public function admin_print_styles() {
		// Support for MPC addons
		if( function_exists('mpc_backend_enqueue') ) {
			mpc_backend_enqueue();
		}
		
		$assets = epx_vcsb_url . 'assets/';
		wp_enqueue_style( 'vcsb_bootstrap', $assets.'bootstrap/custom.css' );
		wp_enqueue_style( 'vcsb_icons', $assets.'xico/css/xico.css' );
		wp_enqueue_style( 'vcsb_admin', $assets.'css/admin.css' );
	}

	public function admin_print_scripts() {
		$assets = epx_vcsb_url . 'assets/';
		wp_enqueue_script( 'vcsb_bootstrap', $assets.'bootstrap/custom.js' );
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_style( 'js_composer_front' );
		wp_enqueue_script( 'js_composer_front' );
	}

	public function admin_footer() {
		global $current_screen;
		
		if( $current_screen->id === 'widgets' ) {
		?>
		<script type="text/javascript">
		!function($) {
			$widgets = $("#widgets-right .widgets-holder-wrap");
			
			$widgets.each(function(){
				$description = $('.sidebar-description', this);
				
				if( !$description.length ) {
					$name = $('.sidebar-name', this);
					$description = $('<div class="sidebar-description"></div>').insertAfter($name);
				}
				$description.append(function(){
					var href = '<?php echo $this->admin_url ?>&sidebar=' + this.parentNode.id;
					var html = '';
						html+= '<div style="margin-bottom:15px">' +
									'<a class="button button-primary button-large widefat" href="'+ href +'">' +
										'<b>Manage With Visual Sidebar Editor</b>' +
									'</a>' +
								'</div>';
					return html;
				});
			});
		}(jQuery);
		</script>
		<?php
		}
	}

	public function wp_footer() {
		$css = implode( '', $this->styles );
		if( $css ) {
			echo '<style type="text/css">'. $css .'</style>';
		}
	}

	public function inline_css( $css, $id=0 ) {
		if( $css ) {
			if( !isset($this->styles[$id]) ) {
				$this->styles[$id] = '';
			}
			$this->styles[$id].= $css;
		}
	}
}
$GLOBALS['VSE'] = new Main();