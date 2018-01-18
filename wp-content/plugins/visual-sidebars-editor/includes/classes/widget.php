<?php
namespace ERROPiX\VCSE;

class Widget {
	private $post;
	private $widget;
	
	public $id;
	public $id_base = 'epx-vcse-widget';
	public $name = 'Visual Sidebar Editor Widget';
	
	public function __construct( $post ) {
		$this->post = $post;
		
		$this->id = "{$this->id_base}-{$post->ID}";
		
		$this->widget = array(
			'id' => $this->id,
			'name' => $this->name,
			'params' => array(),
			'classname' => __CLASS__,
			'callback' => array($this, 'display'),
		);
	}
	
	public function display($args) {
		global $VSE;
		
		extract( $args );
		
		$post = $this->post;
		
		// Design options
		if( empty( $VSE->styles[$id] ) ) {
			$custom_css = get_post_meta( $post->ID, '_wpb_post_custom_css', true );
			$custom_css.= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
			$VSE->inline_css( $custom_css, $id );
		}
		
		// Fix BB Press conflicts
		if( function_exists('bbp_restore_all_filters') ) {
			bbp_restore_all_filters('the_content', 0);
		}
		
		// Disable JetPack sharing
		if( function_exists('sharing_display') ) {
			$removed_sharing_display = remove_filter( 'the_content', 'sharing_display', 19 );
		}
		
		// Filter content
		$global = $post->settings->global;
		if( $global == 'yes' ) {
			$this->setup_postdata();
		}
		$content = apply_filters( 'the_content', $post->post_content );
		if( $global ) {
			$this->reset_postdata();
		}
		
		// Restore JetPack sharing
		if( function_exists('sharing_display') && $removed_sharing_display ) {
			add_filter( 'the_content', 'sharing_display', 19 );
		}
		
		// Sidebar container
		if( $post->settings->container == 'default' ) {
			$output = '';
			$output.= $before_widget;
			$title = apply_filters( 'widget_title', $post->post_title );
			if( !empty($title) ) {
				$output.= $before_title . $title . $after_title;
			}
			$output.= $content;
			$output.= $after_widget;
		} else {
			$output = $content;
		}
		
		echo $output;
	}
	
	public function get_widget() {
		return $this->widget;
	}
	
	public function get_settings() {
		return array();
	}
	
	private function setup_postdata() {
		global $wp_query;
		if( is_object($wp_query->post) ) {
			$wp_query->_post = clone $wp_query->post;
		} else {
			$wp_query->_post = $wp_query->post;
		}
		$wp_query->post = $this->post;
		$wp_query->reset_postdata();
	}
	
	private function reset_postdata() {
		global $wp_query;
		if( is_object($wp_query->_post) ) {
			$wp_query->post = clone $wp_query->_post;
		} else {
			$wp_query->post = $wp_query->_post;
		}
		$wp_query->reset_postdata();
	}
}