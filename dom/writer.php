<?php
class PDFDOMWriter {
	private $domdocument;
	private $pdfdocument;
	
	public function __construct($domdocument){
		$this->domdocument = $domdocument;
	}
	
	public function create(){
		$this->pdfdocument = new PDFDocument();
		
		
		
		return $this->pdfdocument;
	}
}
