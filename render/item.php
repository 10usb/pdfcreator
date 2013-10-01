<?php
abstract class PDFItem {
	private $left;
	private $top;
	private $width;
	private $height;

	public function __construct($width, $height){
		$this->left		= 0;
		$this->top		= 0;
		$this->width	= $width;
		$this->height	= $height;
	}
	
	public function getLeft(){
		return $this->left;
	}

	public function getTop(){
		return $this->top;
	}
	
	public function getHeight(){
		return $this->height;
	}

	public function getWidth(){
		return $this->width;
	}

	public function setPosition($left, $top){
		$this->left	= $left;
		$this->top	= $top;
	}
	
	public abstract function render($target, $offset);
}