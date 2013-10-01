<?php

class PDFFont {
	private $key;
	private $name;
	private $standard;
	private $widths;

	public function __construct($key, $name, $standard, $widths){
		$this->key		= $key;
		$this->name		= $name;
		$this->standard	= $standard;
		$this->widths	= $widths;
	}

	public function getKey(){
		return $this->key;
	}

	public function getName(){
		return $this->name;
	}

	public function isStandaard(){
		return $this->standard;
	}

	public function getTextWidth($str){
		$width = 0;
		for($i=0; $i<strlen($str); $i++){
			$width+= $this->widths[ord($str[$i])];
		}
		return $width / 1000;
	}
}