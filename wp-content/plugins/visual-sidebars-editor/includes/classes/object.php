<?php
namespace ERROPiX\VCSE;

class Object {
	
	public function __construct( $data=array() ) {
		if( is_array($data) )
		foreach( $data as $name=>$value ) {
			$this->__set( $name, $value );
		}
	}
	
	public function __set( $name, $value ) {
		if( is_array($value) ) {
			$value = new self( $value );
		}
		$this->$name = $value;
	}
	
	public function __get( $name ) {
		if( property_exists( $this, $name ) ) {
			return $this->$name;
		}
		return null;
	}
	
	public function __isset( $name ) {
		return property_exists( $this, $name );
	}
	
	public function __unset( $name ) {
		unset( $this->$name );
	}
	
	static function __set_state( $data=array() ) {
		return new self( $data );
	}
}