<?php

abstract class PDFDOMParserState {
	private $parser;

	/**
	 *
	 * @param PDFDOMParser $parser
	 */
	public function __construct($parser){
		$this->parser = $parser;
	}
	
	public function getParser(){
		return $this->parser;
	}
	
	public function getDocument(){
		return $this->parser->getDocument();
	}
	
	/**
	 * Pushes a new state to handle the child
	 * @param PDFDOMParserState $state
	 */
	public function pushState($state){
		$this->parser->pushState($state);
	}
	
	/**
	 * Pops this this and sets the parent as active
	 */
	public function popState(){
		$this->parser->popState();
	}

	public function defaultHandler($data){}
	public function openTag($tag, $attributes){}
	public function closeTag($tag){}
	public function cdata($data){}
	public function comment($data){}
}