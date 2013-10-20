<?php

class PDFDOMParserContentState extends PDFDOMParserState {
	public function __construct($parser, $attributes){
		parent::__construct($parser);
		if(isset($attributes['size'])){
			$this->getDocument()->setSize($attributes['size']);
		}
		
		echo "Content:\n";
		print_r($attributes);
	}
	
	public function openTag($tag, $attributes){
		if($tag!='section') throw new Exception("Unexpected tag open got '$tag'");
		$this->pushState(new PDFDOMParserSectionState($this->getParser(), $attributes));
	}
	
	public function closeTag($tag){
		if($tag!='content') throw new Exception("Unexpected tag close expected 'content' but got '$tag'");
		$this->popState();
	}
}