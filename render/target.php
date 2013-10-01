<?php
class PDFTarget {
	private $document;
	private $page;
	
	/**
	 * 
	 * @param PDFDocument $document
	 * @param PDFPage $page
	 */
	public function __construct($document, $page){
		$this->document	= $document;
		$this->page		= $page;
	}
	
	/**
	 * @return PDFDocument
	 */
	public function getDocument(){
		return $this->document;
	}
	
	/**
	 * @return PDFPage
	 */
	public function getPage(){
		return $this->page;
	}
}