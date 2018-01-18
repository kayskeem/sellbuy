<?php 

extract( shortcode_atts( array(
	'classes' => ''
), $atts ) );	

$columns = explode( '[pricing_column', $content );
$columns_num = count( $columns );
$columns_num = $columns_num - 1;
$column_class = '';

switch ( $columns_num ) {
	case '2' :
		$column_class = 'gp-pricing-columns-2';
		break;
	case '3' :
		$column_class = 'gp-pricing-columns-3';
		break;
	case '4' :
		$column_class = 'gp-pricing-columns-4';
		break;	
	case '5' :
		$column_class = 'gp-pricing-columns-5';
		break;
}

echo '<div class="gp-pricing-table '. sanitize_html_class( $column_class ) . ' ' . esc_attr( $classes ) . '">' . do_shortcode( $content ) . '</div>';

?>