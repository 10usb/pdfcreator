<?php

class PDFDOMParserDocumentState extends PDFDOMParserState {
	public function openTag($tag, $attributes){
		switch($tag){
			case 'info': $this->pushState(new PDFDOMParserInfoState($this->getParser())); break;
			case 'style': $this->pushState(new PDFDOMParserStyleState($this->getParser())); break;
			case 'content': $this->pushState(new PDFDOMParserContentState($this->getParser(), $attributes)); break;
			default: throw new Exception("Unexpected tag open got '$tag'");
		}
	}
	public function closeTag($tag){
		if($tag!='document') throw new Exception("Unexpected tag close expected 'document' but got '$tag'");
		$this->popState();
	}
}