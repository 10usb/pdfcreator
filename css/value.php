<?php

class CSSValue {
	private $value;
	
	public function __construct($value){
		$invalid = true;
		if(preg_match('/^\d+(\.\d+)?pt$/is', $value)) $invalid = false;
		elseif(preg_match('/^[a-z\-]+$/is', $value)) $invalid = false;
		elseif(preg_match('/^\#([0-9a-f]{3}|[0-9a-f]{6})$/is', $value)) $invalid = false;
		elseif(preg_match('/^"[^"]+"$/is', $value)) $invalid = false;
		
		if($invalid) throw new Exception("Invalid Value '$value'");
		
		$this->value = $value;
	}
	
	public function getPoint($throw = false){
		if(preg_match('/^(\d+(\.\d+)?)pt$/is', $this->value, $matches)) return $matches[1];
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}
	
	public function getName($throw = false){
		if(preg_match('/^([a-z\-]+)$/is', $this->value, $matches)) return $matches[1];
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}
	
	public function getHex($throw = false){
		if(preg_match('/^(\#([0-9a-f]{3}|[0-9a-f]{6}))$/is', $this->value, $matches)) return $matches[1];
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}
	
	public function getString($throw = false){
		if(preg_match('/^"([^"]+)"$/is', $this->value, $matches)) return $matches[1];
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}

	/**
	 * Returns the CSS
	 * @return string
	 */
	public function __toString(){
		return $this->value;
	}
}