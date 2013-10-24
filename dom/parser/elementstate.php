<?php

class PDFDOMParserElementState extends PDFDOMParserState {
	private $section;
	private $element;
	
	public function __construct($parser, $tagName, $attributes, $section){
		parent::__construct($parser);
		$this->section = $section;
		$this->element = new PDFDOMElement($tagName);
		$this->element->setAttributes($attributes);
		$this->section->append($this->element);
	}

	public function openTag($tag, $attributes){
		$this->pushState(new PDFDOMParserElementState($this->getParser(), $tag, $attributes, $this->element));
	}
	
	public function cdata($data){
		do {
			$data = preg_replace('/\s{2}/is', ' ', $data, -1, $count);
		}while($count > 0);
		$this->element->text($data);
	}
	
	public function closeTag($tag){
		$this->popState();
	}
}