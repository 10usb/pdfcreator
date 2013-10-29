<?php

class CSSName extends CSSValue {
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::getName()
	 */
	public function getName($throw = true){
		return $this->value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::getString()
	 */
	public function getString($throw = true){
		return $this->value;
	}
}