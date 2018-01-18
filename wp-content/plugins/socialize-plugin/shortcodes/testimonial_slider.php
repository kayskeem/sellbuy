<?php 

extract( shortcode_atts( array( 
	'effect'  => 'slide',
	'buttons' => 'true',
	'speed'   => '0',
	'classes' => ''
 ), $atts ) );

// Unique Name
STATIC $i = 0;
$i++;
$name = 'gp_testimonial_slider_' . $i;

ob_start(); ?>

<div id="<?php echo sanitize_html_class( $name ); ?>" class="gp-testimonial-slider gp-slider <?php echo esc_attr( $classes ); ?>">
	<ul class="slides">
		<?php echo do_shortcode( $content ); ?>
	</ul>
</div>

<?php

$output_string = ob_get_contents();
ob_end_clean(); 
echo wp_kses_post( $output_string );

?> 

<script>
jQuery( document ).ready( function( $ ) {
	$( window ).load( function() {
		'use strict';
		if ( $( 'body' ).hasClass( 'gp-theme' ) ) {
			$( '#<?php echo sanitize_html_class( $name ); ?>.gp-slider' ).flexslider( { 
				animation: '<?php echo esc_attr( $effect ); ?>',
				slideshowSpeed: <?php if ( $speed == 0 ) { echo '9999999'; } else { echo absint( $speed ) * 1000; } ?>,
				animationSpeed: 600,
				smoothHeight: true,   
				directionNav: false,			
				controlNav: <?php if ( $buttons == 'true' ) { ?>true<?php } else { ?>false<?php } ?>,				
				pauseOnAction: true, 
				pauseOnHover: false
			});	
		}
	});
});
</script>