<?php

class PDFDOMParserSectionState extends PDFDOMParserState {
	private $section;
	
	public function __construct($parser, $attributes){
		parent::__construct($parser);
		$this->section = $this->getDocument()->addSection();
		
		echo "Section:\n";
		print_r($attributes);
	}

	public function openTag($tag, $attributes){
		switch($tag){
			case 'header': $this->pushState(new PDFDOMParserDefaultState($this->getParser(), $tag)); break;
			case 'footer': $this->pushState(new PDFDOMParserDefaultState($this->getParser(), $tag)); break;
			case 'body': $this->pushState(new PDFDOMParserDefaultState($this->getParser(), $tag)); break;
			default: throw new Exception("Unexpected tag open got '$tag'");
		}
	}
	public function closeTag($tag){
		if($tag!='section') throw new Exception("Unexpected tag close expected 'section' but got '$tag'");
		$this->popState();
	}
}