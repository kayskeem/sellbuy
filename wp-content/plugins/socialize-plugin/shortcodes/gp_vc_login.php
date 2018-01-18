<?php

/*--------------------------------------------------------------
Login
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_login' ) ) {

	function ghostpool_login( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'widget_title' => '',
			'default_view' => 'gp-default-view-login',
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',
		), $atts ) );
	
		// Unique Name	
		STATIC $i = 0;
		$i++;
		$name = 'gp_login_wrapper_' . $i;
		
		ob_start(); ?>
		
		<?php if ( ! is_user_logged_in() ) { ?>		
			
			<div id="<?php echo sanitize_html_class( $name ); ?>" class="gp-login-wrapper gp-vc-element <?php echo esc_attr( $default_view ); ?> <?php echo esc_attr( $classes ); ?>">
					
				<?php if ( $widget_title ) { ?>
					<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>
						<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
						<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
						<div class="gp-triangle"></div>
					</h3>
				<?php } ?>
				
				<?php if ( ghostpool_option( 'popup_box' ) == 'disabled' ) { ?>
			
					<strong><?php esc_html_e( 'Please enable "Login/Register Popup Windows" from Theme Options -> General to use the Login/Register element.', 'socialize-plugin' ); ?></strong>
							
				<?php } else { ?>

					<div class="gp-login-form-wrapper">

						<form name="loginform" class="gp-login-form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">

							<p class="username"><span class="gp-login-icon"></span><input type="text" name="log" class="user_login" value="<?php if ( ! empty( $user_login ) ) { echo esc_attr( stripslashes( $user_login ), 1 ); } ?>" size="20" placeholder="<?php esc_attr_e( 'Username or Email', 'socialize-plugin' ); ?>" required /></p>

							<p class="password"><span class="gp-password-icon"></span><input type="password" name="pwd" class="user_pass" size="20" placeholder="<?php esc_attr_e( 'Password', 'socialize-plugin' ); ?>" required /></p>

							<p class="gp-lost-password-link"><a href="#" class="gp-switch-to-lost-password"><?php esc_html_e( 'Forgot your password?', 'socialize-plugin' ); ?></a></p>
		
							<?php if ( function_exists( 'gglcptch_display' ) ) { 
								echo gglcptch_display(); 
							} elseif ( has_filter( 'hctpc_verify' ) ) {
								echo apply_filters( 'hctpc_display', '' );
							} elseif ( has_filter( 'cptch_verify' ) ) {
								echo apply_filters( 'cptch_display', '' ); 
							} ?>
					
							<span class="gp-login-results" data-verify="<?php esc_html_e( 'Verifying...', 'socialize-plugin' ); ?>"></span>

							<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e( 'Login', 'socialize-plugin' ); ?>" /></p>
				
							<p class="rememberme"><input name="rememberme" class="rememberme" type="checkbox" checked="checked" value="forever" /> <?php esc_html_e( 'Remember Me', 'socialize-plugin' ); ?></p>
					
							<?php if ( get_option( 'users_can_register' ) ) { ?>
								<p class="gp-register-link"><?php esc_html_e( 'No account?', 'socialize-plugin' ); ?> <a href="<?php if ( function_exists( 'bp_is_active' ) ) { echo esc_url( bp_get_signup_page( false ) ); } else { echo '#register'; } ?>" class="gp-switch-to-register"><?php esc_html_e( 'Sign up', 'socialize-plugin' ); ?></a></p>
							<?php } ?>
					
							<?php if ( has_action ( 'wordpress_social_login' ) ) { ?>
								<div class="gp-social-login">
									<?php do_action( 'wordpress_social_login' ); ?>
								</div>
							<?php } ?>

							<input type="hidden" name="action" value="ghostpool_login" />
							
							<?php wp_nonce_field( 'ghostpool_login_action', 'ghostpool_login_nonce' ); ?>
			
						</form>
	
					</div>
				
				
					<div class="gp-lost-password-form-wrapper">
			
						<form name="lostpasswordform" class="gp-lost-password-form" action="#" method="post">
		
							<p><?php esc_html_e( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'socialize-plugin' ); ?></p>	
			
							<p><span class="gp-login-icon"></span><input type="text" name="user_login" class="user_login" value="" size="20" placeholder="<?php esc_attr_e('Username or Email', 'socialize-plugin' ); ?>" required /></p>

							<span class="gp-login-results" data-verify="<?php esc_html_e( 'Verifying...', 'socialize-plugin' ); ?>"></span>

							<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e('Reset Password', 'socialize-plugin' ); ?>" /></p>
					
							<p class="gp-login-link"><?php esc_html_e( 'Already have an account?', 'socialize-plugin' ); ?> <a href="#" class="gp-switch-to-login"><?php esc_html_e( 'Login instead', 'socialize-plugin' ); ?></a></p>
	
							<input type="hidden" name="action" value="ghostpool_lost_password" />
							
							<?php wp_nonce_field( 'ghostpool_lost_password_action', 'ghostpool_lost_password_nonce' ); ?>
					
						</form>

					</div>
				

					<?php if ( get_option( 'users_can_register' ) && ! function_exists( 'bp_is_active' ) ) { ?>
		
						<div class="gp-register-form-wrapper">

							<form name="registerform" class="gp-register-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post">

								<p class="user_login"><span class="gp-login-icon"></span><input type="text" name="user_login" class="user_login" value="<?php if ( ! empty( $user_login ) ) { echo esc_attr( stripslashes( $user_login ), 1 ); } ?>" size="20" placeholder="<?php esc_attr_e( 'Username', 'socialize-plugin' ); ?>" required /></p>
	
								<p class="user_email"><span class="gp-email-icon"></span><input type="email" name="user_email" class="user_email" size="25" placeholder="<?php esc_attr_e( 'Email', 'socialize-plugin' ); ?>" required /></p>
							
								<p class="user_confirm_pass"><span class="gp-password-icon"></span><input type="password" name="user_confirm_pass" class="user_confirm_pass" size="25" placeholder="<?php esc_attr_e( 'Password', 'socialize-plugin' ); ?>" required /></p>
							
								<p class="user_pass"><span class="gp-password-icon"></span><input type="password" name="user_pass" class="user_pass" size="25" placeholder="<?php esc_attr_e( 'Confirm Password', 'socialize-plugin' ); ?>" required /></p>
								
								<?php if ( function_exists( 'gglcptch_display' ) ) { 
									echo gglcptch_display(); 
								} elseif ( has_filter( 'hctpc_verify' ) ) {
									echo apply_filters( 'hctpc_display', '' );
								} elseif ( has_filter( 'cptch_verify' ) ) {
									echo apply_filters( 'cptch_display', '' ); 
								} ?>
							
								<span class="gp-login-results" data-verify="<?php esc_html_e( 'Verifying...', 'socialize-plugin' ); ?>"></span>
		
								<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e( 'Sign Up', 'socialize-plugin' ); ?>" /></p>
					
								<p class="gp-login-link"><?php esc_html_e( 'Already have an account?', 'socialize-plugin' ); ?> <a href="#" class="gp-switch-to-login"><?php esc_html_e( 'Login instead', 'socialize-plugin' ); ?></a></p>
					
								<input type="hidden" name="action" value="ghostpool_register" />
							
								<?php wp_nonce_field( 'ghostpool_register_action', 'ghostpool_register_nonce' ); ?>
				
							</form>
			
						</div>
				
					<?php } ?>	
									
				<?php } ?>	
	
			</div>
							
		<?php } ?>	
							
		<?php

		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;

	}

}
add_shortcode( 'login', 'ghostpool_login' );

?>