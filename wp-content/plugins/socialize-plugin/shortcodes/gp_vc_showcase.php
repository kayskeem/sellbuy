<?php 

if ( ! function_exists( 'ghostpool_showcase' ) ) {

	function ghostpool_showcase( $atts, $content = null ) {	
		
		extract( shortcode_atts( array(
			'widget_title' => '',
			'cats' => '', 
			'page_ids' => '',
			'post_types' => 'post',
			'format' => 'gp-blog-horizontal',
			'orderby' => 'newest',
			'date_posted' => 'all',
			'date_modified' => 'all',
			'filter' => 'disabled',
			'filter_cats' => '',
			'filter_date' => '',
			'filter_title' => '',					
			'filter_comment_count' => '',
			'filter_views' => '',
			'filter_date_posted' => '',
			'filter_date_modified' => '',
			'filter_cats_id' => '',
			'per_page' => '5',
			'offset' => '',
			'large_featured_image' => 'enabled',
			'small_featured_image' => 'enabled',
			'large_image_width' => '497',
			'large_image_height' => '243',
			'small_image_width' => '100',
			'small_image_height' => '65',
			'hard_crop' => true,
			'large_image_alignment' => 'gp-image-above',
			'small_image_alignment' => 'gp-image-align-left',
			'large_excerpt_length' => '80',
			'small_excerpt_length' => '0',
			'large_meta_author' => '',
			'large_meta_date' => '',
			'large_meta_comment_count' => '',
			'large_meta_views' => '',
			'large_meta_cats' => '',
			'large_meta_tags' => '',		
			'small_meta_author' => '',
			'small_meta_date' => '',
			'small_meta_comment_count' => '',
			'small_meta_views' => '',
			'small_meta_cats' => '',
			'small_meta_tags' => '',					
			'large_read_more_link' => 'disabled',
			'small_read_more_link' => 'disabled',
			'page_arrows' => 'disabled',
			'page_numbers' => 'disabled',
			'see_all' => 'disabled',
			'see_all_link' => '',
			'see_all_text' => esc_html__( 'See All Items', 'socialize-plugin' ),
			'classes' => '',
			'title_format' => 'gp-fancy-title',
			'title_color' => '',	
			'icon' => '',
		), $atts ) );
				
		global $post;
		
		// Detect shortcode
		$GLOBALS['ghostpool_shortcode'] = 'showcase';
		
		// Load page variables
		ghostpool_shortcode_options( $atts );
		ghostpool_category_variables();
		$GLOBALS['ghostpool_content_display'] = 'excerpt';

		// Unique Name	
		STATIC $i = 0;
		$i++;
		$name = 'gp_showcase_wrapper_' . $i;

		// Page IDs
		if ( $page_ids ) {
			$page_ids = explode( ',', $page_ids );
		} else {
			$page_ids = '';
		}
											
		$args = array(
			'post_status'         => 'publish',
			'post_type'           => explode( ',', $post_types ),
			'post__in'            => $page_ids,
			'tax_query' 	      => $GLOBALS['ghostpool_tax'],
			'orderby' 		      => $GLOBALS['ghostpool_orderby_value'],
			'order' 		      => $GLOBALS['ghostpool_order'],	
			'meta_key' 		      => $GLOBALS['ghostpool_meta_key'],
			'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
			'offset' 		      => $GLOBALS['ghostpool_offset'],	
			'paged'          	  => $page_numbers == 'enabled' ? $GLOBALS['ghostpool_paged'] : 1,
			'date_query' 	 	  => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),
			'ignore_sticky_posts' => 1,
		);
		
		ob_start(); $query = new wp_query( $args ); $counter = 1; ?>		

		<div id="<?php echo sanitize_html_class( $name ); ?>" class="gp-showcase-wrapper gp-vc-element gp-blog-standard <?php echo $format; ?> <?php echo esc_attr( $classes ); ?>"<?php if ( function_exists( 'ghostpool_data_properties' ) ) { echo ghostpool_data_properties( 'showcase' ); } ?> data-largefeaturedimage="<?php echo esc_attr( $large_featured_image ); ?>" data-smallfeaturedimage="<?php echo esc_attr( $small_featured_image ); ?>" data-largeimagewidth="<?php echo esc_attr( $large_image_width ); ?>" data-smallimagewidth="<?php echo esc_attr( $small_image_width ); ?>" data-largeimageheight="<?php echo esc_attr( $large_image_height ); ?>" data-smallimageheight="<?php echo esc_attr( $small_image_height ); ?>" data-largeimagealignment="<?php echo esc_attr( $large_image_alignment ); ?>" data-smallimagealignment="<?php echo esc_attr( $small_image_alignment ); ?>" data-largeexcerptlength="<?php echo esc_attr( $large_excerpt_length ); ?>" data-smallexcerptlength="<?php echo esc_attr( $small_excerpt_length ); ?>" data-largemetaauthor="<?php echo esc_attr( $large_meta_author ); ?>" data-smallmetaauthor="<?php echo esc_attr( $small_meta_author ); ?>" data-largemetadate="<?php echo esc_attr( $large_meta_date ); ?>" data-smallmetadate="<?php echo esc_attr( $small_meta_date ); ?>" data-largemetacommentcount="<?php echo esc_attr( $large_meta_comment_count ); ?>" data-smallmetacommentcount="<?php echo esc_attr( $small_meta_comment_count ); ?>" data-largemetaviews="<?php echo esc_attr( $large_meta_views ); ?>" data-smallmetaviews="<?php echo esc_attr( $small_meta_views ); ?>" data-largemetacats="<?php echo esc_attr( $large_meta_cats ); ?>" data-smallmetacats="<?php echo esc_attr( $small_meta_cats ); ?>" data-largemetatags="<?php echo esc_attr( $large_meta_tags ); ?>" data-smallmetatags="<?php echo esc_attr( $small_meta_tags ); ?>" data-largereadmorelink="<?php echo esc_attr( $large_read_more_link ); ?>" data-smallreadmorelink="<?php echo esc_attr( $small_read_more_link ); ?>">

			<?php if ( $widget_title ) { ?>
				<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>				
					<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
					<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
					<div class="gp-triangle"></div>
					<?php if ( $see_all == 'enabled' ) { ?>
						<span class="gp-see-all-link"><a href="<?php echo esc_url( $see_all_link ); ?>"><?php echo esc_attr( $see_all_text ); ?></a></span>
					<?php } ?>
				</h3>
			<?php } elseif ( $filter == 'disabled' ) { ?>
				<div class="gp-empty-widget-title"></div>
			<?php } ?>
			
			<?php if ( $query->have_posts() ) : ?>
			
				<?php if ( $page_arrows == 'enabled' ) { ?>
					<div class="gp-pagination gp-standard-pagination gp-pagination-arrows">
						<?php echo ghostpool_get_previous_posts_page_link( $query->max_num_pages ); ?>
						<?php echo ghostpool_get_next_posts_page_link( $query->max_num_pages ); ?>	
					</div>
				<?php } ?>
										
				<?php get_template_part( 'lib/sections/filter' ); ?>
				
				<div class="gp-inner-loop <?php echo sanitize_html_class( ghostpool_option( 'ajax' ) ); ?>">
		
					<?php while ( $query->have_posts() ) : $query->the_post(); 
					
						if ( $counter % $GLOBALS['ghostpool_per_page'] == 1 ) {
							$GLOBALS['ghostpool_featured_image'] = $large_featured_image;
							$GLOBALS['ghostpool_image_width'] = $large_image_width;
							$GLOBALS['ghostpool_image_height'] = $large_image_height;
							$GLOBALS['ghostpool_image_alignment'] = $large_image_alignment;
							$GLOBALS['ghostpool_excerpt_length'] = $large_excerpt_length;
							$GLOBALS['ghostpool_meta_author'] = $large_meta_author;
							$GLOBALS['ghostpool_meta_date'] = $large_meta_date;
							$GLOBALS['ghostpool_meta_comment_count'] = $large_meta_comment_count;
							$GLOBALS['ghostpool_meta_views'] = $large_meta_views;
							$GLOBALS['ghostpool_meta_cats'] = $large_meta_cats;
							$GLOBALS['ghostpool_meta_tags'] = $large_meta_tags;
							$GLOBALS['ghostpool_read_more_link'] = $large_read_more_link;
						} else {
							$GLOBALS['ghostpool_featured_image'] = $small_featured_image;
							$GLOBALS['ghostpool_image_width'] = $small_image_width;
							$GLOBALS['ghostpool_image_height'] = $small_image_height;
							$GLOBALS['ghostpool_image_alignment'] = $small_image_alignment;
							$GLOBALS['ghostpool_excerpt_length'] = $small_excerpt_length;
							$GLOBALS['ghostpool_meta_author'] = $small_meta_author;
							$GLOBALS['ghostpool_meta_date'] = $small_meta_date;
							$GLOBALS['ghostpool_meta_comment_count'] = $small_meta_comment_count;
							$GLOBALS['ghostpool_meta_views'] = $small_meta_views;
							$GLOBALS['ghostpool_meta_cats'] = $small_meta_cats;
							$GLOBALS['ghostpool_meta_tags'] = $small_meta_tags;
							$GLOBALS['ghostpool_read_more_link'] = $small_read_more_link;	
						}
								
					?>

						<?php if ( ( isset( $counter ) && ( $counter % $GLOBALS['ghostpool_per_page'] == 2 OR $counter == 2 ) ) && $query->current_post != 0 ) { ?>
							<div class="gp-small-posts">
						<?php } ?>

							<?php get_template_part( 'post', 'loop' ); ?>

						<?php if ( ( isset( $counter ) && $counter % $GLOBALS['ghostpool_per_page'] == 0 ) OR ( ( ( $query->current_post + 1 ) == $query->post_count ) && $query->current_post != 0 ) ) { ?>
							</div>
						<?php } ?>

					<?php $counter++; endwhile; ?>
		
				</div>

				<?php if ( $page_numbers == 'enabled' ) { ?>
					<?php echo ghostpool_pagination( $query->max_num_pages ); ?>
				<?php } ?>

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

add_shortcode( 'showcase', 'ghostpool_showcase' );
	
?>