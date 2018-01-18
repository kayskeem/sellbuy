<?php

if ( ! function_exists( 'ghostpool_advertisement' ) ) {

	function ghostpool_advertisement( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'widget_title'   => '',
			'classes' => '',			
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',		
		), $atts ) );
		
		// Unique Name	
		STATIC $i = 0;
		$i++;
		$name = 'gp_advertisement_' . $i;
	
		ob_start(); ?>
	
			<div id="<?php echo sanitize_html_class( $name ); ?>" class="gp-advertisement-wrapper widget <?php echo esc_attr( $classes ); ?>">

				<?php if ( $widget_title ) { ?>
					<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>
						<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
						<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
						<div class="gp-triangle"></div>
					</h3>
				<?php } ?>
						
				<?php echo wp_kses_post( $content ); ?>
				
			</div>
					
		<?php 

		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;

	}
}
add_shortcode( 'advertisement', 'ghostpool_advertisement' );

?>