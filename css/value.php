<?php

class CSSValue {
	/**
	 * 
	 * @var string
	 */
	protected $value;
	
	/**
	 * 
	 * @param string $value
	 * @throws Exception
	 * @return CSSName|CSSString|CSSColor|CSSMeasurement
	 */
	public static function parse($value){
		if(preg_match('/^([a-z\-]+)$/is', $value)) return new CSSName($value);
		if(preg_match('/^"([^"]+)"$/is', $value)) return new CSSString($value);
		if(preg_match('/^(\#([0-9a-f]{3}|[0-9a-f]{6}))$/is', $value)) return new CSSColor($value);
		if(preg_match('/^(\d+(\.\d+)?)(\%|in|cm|mm|em|pt|pc|px)$/is', $value)) return new CSSMeasurement($value);
		
		throw new Exception("Invalid Value '$value'");
	}
	
	/**
	 * 
	 * @param string $value
	 */
	private function __construct($value){
		$this->value = $value;
		$this->init();
	}
	
	/**
	 * Empty init function
	 */
	protected function init(){}
	
	/**
	 * Returns the translated value
	 * @param string $value
	 * @param string $unit
	 * @param boolean $throw
	 * @throws Exception
	 * @return boolean
	 */
	public function getMeasurement($unit, $value = null, $throw = true){
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}
	
	/**
	 * Return the name
	 * @param boolean $throw
	 * @throws Exception
	 * @return boolean
	 */
	public function getName($throw = true){
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}
	
	/**
	 * Return true if the value is a color
	 * @return boolean
	 */
	public function isColor(){
		return false;
	}
	
	/**
	 * The red value
	 * @param boolean $throw
	 * @throws Exception
	 * @return boolean
	 */
	public function getRed($throw = true){
		if($throw) throw new Exception("Invalid Value '$this->value'");
	}
	
	/**
	 * The green value
	 * @param boolean $throw
	 * @throws Exception
	 * @return boolean
	 */
	public function getGreen($throw = true){
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}
	
	/**
	 * The blue value
	 * @param boolean $throw
	 * @throws Exception
	 * @return boolean
	 */
	public function getBlue($throw = true){
		if($throw) throw new Exception("Invalid Value '$this->value'");
		return false;
	}

	/**
	 * String value or name
	 * @param boolean $throw
	 * @throws Exception
	 * @return boolean
	 */
	public function getString($throw = true){
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