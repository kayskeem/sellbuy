<?php

extract( shortcode_atts( array( 
	'image_url'    => '',
	'image_width'  => '120',
	'image_height' => '120',
	'name'         => '',
 ), $atts ) );
 
ob_start(); ?>

	<li class="gp-testimonial-slide">
	
		<?php if ( $image_url ) { ?>
		
			<?php $image = aq_resize( wp_get_attachment_url( $image_url ), $image_width, $image_height, true, false, true ); ?>
			<?php if ( ghostpool_option( 'retina' ) == 'gp-retina' ) {
				$retina = aq_resize( wp_get_attachment_url( $image_url ), $image_width * 2, $image_height * 2, true, true, true );
			} else {
				$retina = '';
			} ?>
			
			<img src="<?php echo esc_url( $image[0] ); ?>" data-rel="<?php echo esc_url( $retina ); ?>" width="<?php echo absint( $image[1] ); ?>" height="<?php echo absint( $image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image gp-testimonial-image" />
											
		<?php } ?>
							
		<div class="gp-testimonial-quote"<?php if ( $image_url ) { ?> style="margin-left: <?php echo absint( $image[1] ) + 38; ?>px;"<?php } ?>>
			<?php if ( $content ) { ?><h5><?php echo do_shortcode( wpb_js_remove_wpautop( $content, true ) ); ?></h5><?php } ?>
			<?php if ( $name ) { ?><span class="gp-testimonial-name"><?php echo esc_attr( $name ); ?></span><?php } ?>
		</div>

	</li>

<?php

$output_string = ob_get_contents();
ob_end_clean(); 	
echo wp_kses_post( $output_string );

?>