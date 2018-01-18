<?php

/*--------------------------------------------------------------
bbPress Forum Search Form
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_search' ) ) {

	function ghostpool_bbp_search( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Search Forums', 'socialize-plugin' ),		
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$type = 'BBP_Search_Widget';
		
		// Title color
		if ( $title_color ) {
			$title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$title_icon = '';
		}
				
		$args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $title_color . '>' . wp_kses_post( $title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $type, $atts, $args );

		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
add_shortcode( 'bbp_search', 'ghostpool_bbp_search' );

/*--------------------------------------------------------------
bbPress Forums List
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_forums_list' ) ) {

	function ghostpool_bbp_forums_list( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Forums List', 'socialize-plugin' ),
			'parent_forum' => '0',			
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$type = 'BBP_Forums_Widget';
		
		// Title color
		if ( $title_color ) {
			$title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$title_icon = '';
		}
				
		$args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $title_color . '>' . wp_kses_post( $title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $type, $atts, $args );

		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
add_shortcode( 'bbp_forums_list', 'ghostpool_bbp_forums_list' );

/*--------------------------------------------------------------
bbPress Recent Replies
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_recent_replies' ) ) {

	function ghostpool_bbp_recent_replies( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Recent Replies', 'socialize-plugin' ),
			'max_shown' => 5,
			'show_date' => '',
			'show_user' => '',			
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$type = 'BBP_Replies_Widget';
		
		// Title color
		if ( $title_color ) {
			$title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$title_icon = '';
		}
				
		$args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $title_color . '>' . wp_kses_post( $title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $type, $atts, $args );
		
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
add_shortcode( 'bbp_recent_replies', 'ghostpool_bbp_recent_replies' );

/*--------------------------------------------------------------
bbPress Recent Topics
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_recent_topics' ) ) {

	function ghostpool_bbp_recent_topics( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Recent Topics', 'socialize-plugin' ),
			'max_shown' => 5,
			'show_date' => '',
			'show_user' => '',
			'parent_forum' => 'any',
			'order_by' => 'newness',			
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$type = 'BBP_Topics_Widget';
		
		// Title color
		if ( $title_color ) {
			$title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$title_icon = '';
		}
				
		$args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $title_color . '>' . wp_kses_post( $title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $type, $atts, $args );
		
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
add_shortcode( 'bbp_recent_topics', 'ghostpool_bbp_recent_topics' );

/*--------------------------------------------------------------
bbPress Statistics
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_statistics' ) ) {

	function ghostpool_bbp_statistics( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Forum Statistics', 'socialize-plugin' ),
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$type = 'BBP_Stats_Widget';
		
		// Title color
		if ( $title_color ) {
			$title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$title_icon = '';
		}
				
		$args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $title_color . '>' . wp_kses_post( $title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $type, $atts, $args );
		
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;
	}
}
add_shortcode( 'bbp_statistics', 'ghostpool_bbp_statistics' );

?>