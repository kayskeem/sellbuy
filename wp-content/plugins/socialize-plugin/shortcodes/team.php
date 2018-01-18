<?php 

global $columns, $ghostpool_counter;

extract( shortcode_atts( array( 
	'columns' => '3',
	'classes' => '',
 ), $atts ) );

ob_start(); 

$ghostpool_counter = 0;
	
?>

<div class="gp-team-wrapper <?php echo esc_attr( $classes ); ?>">
	<?php echo do_shortcode( $content ); ?>
</div>

<?php

$output_string = ob_get_contents();
ob_end_clean(); 
echo wp_kses_post( $output_string );

?>