<?php

class PDFDOMDocument {
	private $info;
	private $size;
	private $stylesheet;
	private $sections;
	
	public function __construct(){
		$this->info			= new PDFDOMInfo();
		$this->size			= 'A4';
		$this->stylesheet	= new PDFDOMStylesheet();
		$this->sections		= array();
	}
	
	/**
	 * 
	 * @return PDFDOMInfo
	 */
	public function getInfo(){
		return $this->info;
	}
	
	/**
	 * 
	 * @param string $size
	 */
	public function setSize($size){
		$this->size = $size;
	}
	
	/**
	 * 
	 * @return PDFDOMStylesheet
	 */
	public function getStylesheet(){
		return $this->stylesheet;
	}
	
	/**
	 * 
	 * @return PDFDOMSection
	 */
	public function addSection(){
		return $this->sections[] = new PDFDOMSection();
	}
}