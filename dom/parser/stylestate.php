<?php

class PDFDOMParserStyleState extends PDFDOMParserState {
	private $value;
	
	public function __construct($parser){
		parent::__construct($parser);
	}

	public function openTag($tag, $attributes){
		throw new Exception("Unexpected open tag inside style tag got '$tag'");
	}
	
	public function closeTag($tag){
		if($tag!='style') throw new Exception("Unexpected tag close expected 'style' but got '$tag'");
		
		$parser = new CSSParser($this->getDocument()->getStylesheet());
		$parser->parse($this->value);

		$this->popState();
	}

	public function cdata($data){
		$this->value.= $data;
	}
	
	public function comment($data){
		$this->value.= $data;
	}
}