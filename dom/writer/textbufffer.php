<?php

class PDFDOMTextBuffer {
	/**
	 * 
	 * @var string
	 */
	private $text;
	
	/**
	 * 
	 * @var PDFContentWriter
	 */
	private $writer;
	
	/**
	 * 
	 * @var boolean
	 */
	private $preceding;

	/**
	 *
	 * @var boolean
	 */
	private $trailing;
	
	/**
	 * 
	 */
	public function __construct(){
		$this->text			= null;
		$this->writer		= null;
		$this->preceding	= false;
		$this->trailing		= false;
	}
	
	/**
	 * 
	 * @param string $text
	 * @param PDFContentWriter $writer
	 */
	public function setText($text, $writer){
		$this->text		= $text;
		$this->writer	= $writer;
	}
	
	/**
	 * 
	 */
	public function markPreceding(){
		$this->preceding = true;
	}
	
	/**
	 * 
	 */
	public function markTrailing(){
		$this->trailing = true;
	}
	
	public function flush(){
		if($this->text==null) return null;
		
		$text = $this->preceding ? ($this->trailing ? trim($this->text) : ltrim($this->text)) : ($this->trailing ? rtrim($this->text) : $this->text);
		
		$this->text			= null;
		$this->preceding	= false;
		$this->trailing		= false;
		
		if($text){
			$this->writer->text($text);
			return true;
		}
		return false;
	}
}