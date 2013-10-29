<?php

class CSSColor extends CSSValue {
	/**
	 * 
	 * @var number
	 */
	private $red, $green, $blue;

	/**
	 * (non-PHPdoc)
	 * @see CSSValue::init()
	 */
	protected function init(){
		if(preg_match('/^\#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/is', strtolower($this->value), $matches)){
			$this->red		= hexdec($matches[1]);
			$this->green	= hexdec($matches[2]);
			$this->blue		= hexdec($matches[2]);
		}elseif(preg_match('/^\#([0-9a-f])([0-9a-f])([0-9a-f])$/is', strtolower($this->value), $matches)){
			$this->red		= hexdec($matches[1].$matches[1]);
			$this->green	= hexdec($matches[2].$matches[2]);
			$this->blue		= hexdec($matches[2].$matches[2]);
		}else{
			throw new Exception("Invalid color value '$this->value'");
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::isColor()
	 */
	public function isColor(){
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::getRed()
	 */
	public function getRed($throw = true){
		return $this->red;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::getGreen()
	 */
	public function getGreen($throw = true){
		return $this->green;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::getBlue()
	 */
	public function getBlue($throw = true){
		return $this->blue;
	}
}