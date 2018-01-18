<?php

/*--------------------------------------------------------------
Statistics
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_statistics' ) ) {

	function ghostpool_statistics( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'widget_title' => '',
			'posts' => '',
			'comments' => '',
			'blogs' => '',
			'activity' => '',
			'members' => '',
			'groups' => '',
			'forums' => '',
			'topics' => '',
			'icon_color' => '#e93100',
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',
		), $atts ) );
		
		// Unique Name	
		STATIC $i = 0;
		$i++;
		$name = 'gp_statistics_wrapper_' . $i;

		// Get activity count	
		if ( ! function_exists( 'ghostpool_bp_activity_updates' ) ) {
			function ghostpool_bp_activity_updates() {
				global $bp, $wpdb;
				if ( ! $count = wp_cache_get( 'gp_bp_activity_updates', 'bp' ) ) {
					$count = $wpdb->get_var( $wpdb->prepare( "SELECT count(a.id) FROM {$bp->activity->table_name} a WHERE type = %s AND a.component = '{$bp->activity->id}'", 'activity_update' ) );
					if ( ! $count ) {
						$count == 0;
					}	
					if ( ! empty( $count ) ) {
						wp_cache_set( 'gp_bp_activity_updates', $count, 'bp' );
					}	
				}
				return $count;
			}
		}	
		
		if ( ! function_exists( 'ghostpool_bp_activity_updates_delete_clear_cache' ) ) {
			function ghostpool_bp_activity_updates_delete_clear_cache( $args ) {
				if ( $args['type'] && $args['type'] == 'activity_update' )
					wp_cache_delete( 'gp_bp_activity_updates' );
			}
		}	
		add_action( 'bp_activity_delete', 'ghostpool_bp_activity_updates_delete_clear_cache' );

		if ( ! function_exists( 'ghostpool_bp_activity_updates_add_clear_cache' ) ) {
			function ghostpool_bp_activity_updates_add_clear_cache() {
				wp_cache_delete( 'gp_bp_activity_updates' );
			}
		}
		add_action( 'bp_activity_posted_update', 'ghostpool_bp_activity_updates_add_clear_cache' );
		
		// Statistics icon background color
		if ( $icon_color ) {
			echo '<style>.gp-statistics-wrapper .gp-stats > div:before{background-color: ' . esc_attr( $icon_color ) . '}</style>';
		}
				
		ob_start(); ?>

		<div id="<?php echo sanitize_html_class( $name ); ?>" class="gp-statistics-wrapper gp-vc-element <?php echo esc_attr( $classes ); ?>">
	
			<?php if ( $widget_title ) { ?>
				<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>
					<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
					<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
					<div class="gp-triangle"></div>
				</h3>
			<?php } ?>

			<div class="gp-stats">
			
				<?php if ( $posts == '1' ) { ?>
					<div class="gp-post-stats">
						<?php $count_posts = wp_count_posts(); ?>
						<span class="gp-stat-details">
							<span class="gp-stat-title"><?php esc_html_e( 'Posts', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( $count_posts->publish ); ?></span>
						</span>	
					</div>	
				<?php } ?>

				<?php if ( $comments == '1' ) { ?>
					<div class="gp-comment-stats">
						<?php $comments_count = wp_count_comments(); ?>
						<span class="gp-stat-details">
							<span class="gp-stat-title"><?php esc_html_e( 'Comments', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( $comments_count->approved ); ?></span>
						</span>		
					</div>	
				<?php } ?>

				<?php if ( is_multisite() && $blogs == '1' ) { ?>
					<div class="gp-blog-stats">
						<span class="gp-stat-details">
							<span class="gp-stat-title"><?php esc_html_e( 'Blogs', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( get_blog_count() ); ?></span>
						</span>		
					</div>	
				<?php } ?>

				<?php if ( function_exists( 'bp_is_active' ) && bp_is_active( 'activity' ) && $activity == '1' ) { ?>
					<div class="gp-activity-update-stats">
						<span class="gp-stat-details">
							<span class="gp-stat-title"><?php esc_html_e( 'Activity', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( ghostpool_bp_activity_updates() ); ?></span>
						</span>		
					</div>	
				<?php } ?>
															
				<?php if ( $members == '1' ) { ?>
					<div class="gp-member-stats">
						<?php $user_count = count_users(); ?>
						<span class="gp-stat-details">
							<span class="gp-stat-title"><?php esc_html_e( 'Members', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( $user_count['total_users'] ); ?></span>
						</span>	
					</div>	
				<?php } ?>

				<?php if ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) && $groups == '1' ) { ?>
					<div class="gp-group-stats">
						<span class="gp-stat-details">
							<span class="gp-stat-title"><?php esc_html_e( 'Groups', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( groups_get_total_group_count() ); ?></span>
						</span>	
					</div>	
				<?php } ?>

				<?php if ( class_exists( 'bbPress' ) && $forums == '1' ) { ?>
					<div class="gp-forum-stats">
						<span class="gp-stat-details">
							<?php $count_posts = wp_count_posts( 'forum' ); ?>
							<span class="gp-stat-title"><?php esc_html_e( 'Forums', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( $count_posts->publish ); ?></span>
						</span>		
					</div>	
				<?php } ?>

				<?php if ( class_exists( 'bbPress' ) && $topics == '1' ) { ?>
					<div class="gp-topic-stats">
						<span class="gp-stat-details">
							<?php $count_posts = wp_count_posts( 'topic' ); ?>
							<span class="gp-stat-title"><?php esc_html_e( 'Topics', 'socialize-plugin' ); ?></span>
							<span class="gp-stat-count"><?php echo absint( $count_posts->publish ); ?></span>
						</span>	
					</div>	
				<?php } ?>

			</div>
															
		</div>
						
		<?php

		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;

	}

}
add_shortcode( 'statistics', 'ghostpool_statistics' );

?>