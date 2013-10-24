<?php

class PDFDOMDocument {
	private $info;
	private $size;
	private $stylesheet;
	private $sections;
	
	public function __construct(){
		$this->info			= new PDFDOMInfo();
		$this->size			= 'A4';
		$this->stylesheet	= new CSSDocument();
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
	
	/**
	 * @return array<PDFDOMSection>
	 */
	public function getSections(){
		return $this->sections;
	}

	/**
	 * Returns the XML
	 * @return string
	 */
	public function __toString(){
		$xml = '';
		$xml.= "<document>\n";
		$xml.= "  <content>";
		foreach($this->sections as $section){
			$xml.= str_replace("\n", "\n    ", $section);
		}
		$xml.= "\n  </content>\n";
		$xml.= "</document>";
		return $xml;
	}
}