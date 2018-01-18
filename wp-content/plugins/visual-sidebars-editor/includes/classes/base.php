<?php
namespace ERROPiX\VCSE;

class Base {

	/* Global variables safe getters */
	protected function get_value( $array, $key, $alt=null ) {
		return isset($array[$key]) ? $array[$key] : $alt;
	}
	protected function request_var( $key, $alt=null ) {
		return $this->get_value( $_REQUEST, $key, $alt );
	}
	protected function get_var( $key, $alt=null ) {
		return $this->get_value( $_GET, $key, $alt );
	}
	protected function post_var( $key, $alt=null ) {
		return $this->get_value( $_POST, $key, $alt );
	}
	protected function server_var( $key, $alt=null ) {
		return $this->get_value( $_SERVER, $key, $alt );
	}

	protected function get_cookie( $key, $alt=null ) {
		return $this->get_value( $_COOKIE, $key, $alt );
	}
	protected function set_cookie( $key, $value, $days=1 ) {
		$lifetime = time() + $days * DAY_IN_SECONDS;
		return setcookie( $key, $value, $lifetime, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	}
	protected function del_cookie( $key ) {
		return $this->set_cookie( $key, '', -1 );
	}
	protected function flash_cookie( $key, $alt=null ) {
		$value = $this->get_value( $_COOKIE, $key, $alt );
		if ( $value ) {
			$this->del_cookie( $key );
		}
		return $value;
	}

	protected function add_action( $handler, $priority=10, $args_num=1 ) {
		add_action( $handler, $this->cb( $handler ), $priority, $args_num );
	}
	protected function remove_action( $handler, $priority=10 ) {
		remove_action( $handler, $this->cb( $handler ), $priority );
	}

	protected function add_filter( $handler, $priority=10, $args_num=1 ) {
		add_filter( $handler, $this->cb( $handler ), $priority, $args_num );
	}
	protected function remove_filter( $handler, $priority=10 ) {
		remove_filter( $handler, $this->cb( $handler ), $priority );
	}

	protected function cb( $name ) {
		if( method_exists( $this, $name ) ) {
			return array( &$this, $name );
		}
		return '__return_false';
	}

	protected function dd_options( $choices, $current=null, $echo=true ) {
		$options = '';
		foreach ( $choices as $value => $label ) {
			$selected = selected( $value, $current );
			$options.= sprintf( '<option value="%s"%s>%s</option>', $value, $selected, $label );
		}
		if( $echo ) {
			echo $options;
		} else {
			return $options;
		}
	}

	static function debug( $var, $label=null ) {
		do_action( 'add_debug_info', $var, $label );
	}

	static function dump( $var ) {
		$style = "white-space:pre-wrap;background:rgba(0,0,0,.05);padding:20px;overflow:auto;font-family:consolas";
		echo "<pre style='$style'>".json_encode( $var, JSON_PRETTY_PRINT )."</pre>\n\n";
	}
	static function export( $var ) {
		$style = "white-space:pre-wrap;background:rgba(0,0,0,.05);padding:20px;overflow:auto;font-family:consolas";
		echo "<pre style='$style'>".var_export( $var, true )."</pre>\n\n";
	}
}