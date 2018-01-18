<?php 

if ( ! function_exists( 'ghostpool_carousel' ) ) {

	function ghostpool_carousel( $atts, $content = null ) {	
		
		extract( shortcode_atts( array(
			'widget_title' => '',	
			'cats' => '',
			'page_ids' => '',
			'post_types' => 'post',
			'orderby' => 'newest',
			'date_posted' => 'all',
			'date_modified' => 'all',
			'items_in_view' => '3',
			'per_page' => '12',
			'offset' => '',
			'image_width' => '350',
			'image_height' => '220',	
			'hard_crop' => true,
			'slider_speed' => '0',
			'animation_speed' => '0.6',
			'buttons' => 'enabled',
			'arrows' => 'enabled',
			'see_all' => 'disabled',
			'see_all_link' => '',
			'see_all_text' => esc_html__( 'See All Items', 'socialize-plugin' ),
			'classes' => '',	
			'title_format' => 'gp-standard-title',	
			'title_color' => '',
			'icon' => '',
		), $atts ) );	

		// Detect shortcode
		$GLOBALS['ghostpool_shortcode'] = 'carousel';

		// Load page variables
		ghostpool_shortcode_options( $atts );
		ghostpool_category_variables();
		$GLOBALS['ghostpool_image_alignment'] = 'gp-image-above';
		
		// Unique Name	
		STATIC $i = 0;
		$i++;
		$name = 'gp_carousel_wrapper_' . $i;

		// Page IDs
		if ( $page_ids ) {
			$page_ids = explode( ',', $page_ids );
		} else {
			$page_ids = '';
		}
		
		$args = array(
			'post_status'    => 'publish',
			'post_type'      => explode( ',', $post_types ),
			'post__in'       => $page_ids,
			'tax_query' 	 => $GLOBALS['ghostpool_tax'],
			'orderby'        => $GLOBALS['ghostpool_orderby_value'],
			'order'          => $GLOBALS['ghostpool_order'],
			'meta_key'       => $GLOBALS['ghostpool_meta_key'],
			'posts_per_page' => $GLOBALS['ghostpool_per_page'],		
			'offset' 		 => $GLOBALS['ghostpool_offset'],
			'paged'			 => 1,
			'no_found_rows'  => true,
			'date_query' 	 => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),
			'ignore_sticky_posts' => 1,
		);

		ob_start(); $query = new wp_query( $args ); ?>

		<div id="<?php echo sanitize_html_class( $name ); ?>" class="gp-carousel-wrapper gp-vc-element gp-slider gp-blog-standard-size <?php echo esc_attr( $classes ); ?>">
			
			<?php if ( $widget_title ) { ?>
				<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>
					<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
					<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
					<div class="gp-triangle"></div>
					<?php if ( $see_all == 'enabled' ) { ?>
						<span class="gp-see-all-link"><a href="<?php echo esc_url( $see_all_link ); ?>"><?php echo esc_attr( $see_all_text ); ?></a></span>
					<?php } ?>
				</h3>
			<?php } else { ?>
				<div class="gp-empty-widget-title"></div>
			<?php } ?>

			<?php if ( $query->have_posts() ) : ?>
				
				<ul class="slides">

					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			
						<li>			

							<section <?php post_class( 'gp-post-item' ); ?> itemscope itemtype="http://schema.org/Blog">
						
								<?php if ( has_post_thumbnail() ) { ?>
						
									<div class="gp-post-thumbnail gp-loop-featured">
									
									 	<div class="gp-image-above">

											<?php $image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $image_width, $image_height, $hard_crop, false, true ); ?>
											<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
												$retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $image_width * 2, $image_height * 2, $hard_crop, true, true );
											} else {
												$retina = '';
											} ?>

											<a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>>
			
												<img src="<?php echo esc_url( $image[0] ); ?>" data-rel="<?php echo esc_url( $retina ); ?>" width="<?php echo absint( $image[1] ); ?>" height="<?php echo absint( $image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />

											</a>
										
										</div>
														
									</div>
						
								<?php } elseif ( get_post_format() != '0' && get_post_format() != 'gallery' ) { ?>

									<div class="gp-loop-featured">
										<?php get_template_part( 'lib/sections/loop', get_post_format() ); ?>
									</div>
									
								<?php } ?>
								
								<?php if ( get_post_format() != 'quote' OR has_post_thumbnail() && $GLOBALS['ghostpool_featured_image'] == 'enabled' ) { ?>

									<div class="gp-loop-content">
								
										<h2 class="gp-loop-title" itemprop="headline"><a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>><?php the_title(); ?></a></h2>
									
										<div class="gp-loop-meta">
											<time class="gp-post-meta gp-meta-date" itemprop="datePublished" datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
										</div>	

									</div>
								
								<?php } ?>
														
							</section>
						
						</li>
					
					<?php endwhile; ?>	

				</ul>

				<script>
				jQuery( document ).ready( function( $ ) {
					'use strict';
					
					if ( $( 'body' ).hasClass( 'gp-theme' ) ) {
			
						var $window = $(window),
							flexslider = { vars:{} };

						function getGridSize() {
							return ( $window.width() <= 567 ) ? 1 : ( $window.width() <= 1023 ) ? <?php if ( $items_in_view == 1 ) { ?>1<?php } else { ?>2<?php } ?> : <?php echo absint( $items_in_view ); ?>;
						}

						$window.load(function() {
							$( '#<?php echo sanitize_html_class( $name ); ?>' ).flexslider({
								animation: 'slide',
								animationLoop: false,
								itemWidth: <?php echo absint( $GLOBALS['ghostpool_image_width'] ); ?>,
								itemMargin: 30,
								slideshowSpeed: <?php if ( $slider_speed != '0' ) { echo absint( $slider_speed ) * 1000; } else { echo '9999999'; } ?>,
								animationSpeed: <?php echo absint( $animation_speed * 1000 ); ?>,
								directionNav: <?php if ( $arrows == 'enabled' ) { ?>true<?php } else { ?>false<?php } ?>,			
								controlNav: <?php if ( $buttons == 'enabled' ) { ?>true<?php } else { ?>false<?php } ?>,			
								pauseOnAction: true, 
								pauseOnHover: false,
								prevText: '',
								nextText: '',
								minItems: getGridSize(),
								maxItems: getGridSize(),
								start: function(slider){
									flexslider = slider;
								}
							});	
						});
							
						$window.resize( function() {
							var gridSize = getGridSize();
							flexslider.vars.minItems = gridSize;
							flexslider.vars.maxItems = gridSize;
						});			
					
					}
					
				});
				</script>
																					
			<?php else : ?>

				<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize-plugin' ); ?></strong>

			<?php endif; wp_reset_postdata(); ?>

		</div>
			 				
		<?php

		$output_string = ob_get_contents();
		ob_end_clean(); 
		$GLOBALS['ghostpool_shortcode'] = null;
		return $output_string;

	}

}

add_shortcode( 'carousel', 'ghostpool_carousel' );
	
?>