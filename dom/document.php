<?php

class PDFDOMDocument {
	private $info;
	private $stylesheet;
	private $sections;
	
	public function __construct(){
		$this->info			= new PDFDOMInfo();
		$this->stylesheet	= new PDFDOMStylesheet();
		$this->sections		= array();
	}
	
	public function getInfo(){
		return $this->info;
	}
	
	public function getStylesheet(){
		return $this->stylesheet;
	}
	
	public function addSection(){
		return $this->sections[] = new PDFDOMSection();
	}
}