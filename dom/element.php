<?php
class PDFDOMElement extends PDFDOMParent {
	private $attributes;
	
	public function __construct($tagName){
		parent::__construct($tagName);
		$this->attributes = array();
	}
	
	public function setAttributes($attributes){
		$this->attributes = array_merge($this->attributes, $attributes);
	}
}