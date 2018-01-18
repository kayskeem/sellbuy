<?php get_header();

// Load page variables
ghostpool_loop_variables();
ghostpool_category_variables();
		
?>

<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>
	
<div id="gp-content-wrapper" class="gp-container">
	
	<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-large-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

	<div id="gp-inner-container">

		<div id="gp-left-column">
				
			<div id="gp-content">
							
				<?php ghostpool_breadcrumbs(); ?>
		
				<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-standard-page-header' ) { ?>				
					<header class="gp-entry-header">
						<h1 class="gp-entry-title" itemprop="headline"><?php if ( is_category() OR is_tag() OR is_tax() ) { ?><?php single_cat_title(); ?><?php } else { ?><?php if ( ! function_exists( '_wp_render_title_tag' ) && ! function_exists( 'ghostpool_render_title' ) ) { esc_html_e( 'Archives', 'socialize' ); } else { the_archive_title(); } ?><?php } ?></h1>
						<?php if ( category_description() != '' ) { ?>
							<h2 class="gp-subtitle"><?php echo str_replace( array( '<p>', '</p>' ), '', category_description() ); ?></h2>
						<?php } ?>		
					</header>
				<?php } ?>
		
				<div class="gp-blog-wrapper <?php echo sanitize_html_class( $GLOBALS['ghostpool_format'] ); ?>"<?php if ( function_exists( 'ghostpool_data_properties' ) ) { echo ghostpool_data_properties( 'taxonomy' ); } ?>>

					<?php if ( have_posts() ) : ?>
		
						<?php get_template_part( 'lib/sections/filter' ); ?>
							
						<div class="gp-inner-loop <?php echo sanitize_html_class( ghostpool_option( 'ajax' ) ); ?>">

							<?php if ( $GLOBALS['ghostpool_format'] == 'gp-blog-masonry' ) { ?><div class="gp-gutter-size"></div><?php } ?>
							
							<?php while ( have_posts() ) : the_post(); ?>

								<?php get_template_part( 'post', 'loop' ); ?>

							<?php endwhile; ?>
	
						</div>

						<?php echo ghostpool_pagination( $wp_query->max_num_pages ); ?>

					<?php else : ?>

						<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize' ); ?></strong>

					<?php endif; ?>
	
				</div>
			
			</div>
	
			<?php get_sidebar( 'left' ); ?>
	
		</div>

		<?php get_sidebar( 'right' ); ?>

	</div>
	
	<div class="gp-clear"></div>

</div>

<?php get_footer(); ?>