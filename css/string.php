<?php

class CSSString extends CSSValue {
	/**
	 * 
	 * @var string
	 */
	private $text;
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::init()
	 */
	protected function init(){
		if(!preg_match('/^"([^"]+)"$/is', $this->value, $matches)) throw new Exception("Invalid string '$this->value'");
		$this->text = $matches[1];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::getString()
	 */
	public function getString($throw = true){
		return $this->text;
	}
}