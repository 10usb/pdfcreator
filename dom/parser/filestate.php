<?php

class PDFDOMParserFileState extends PDFDOMParserState {
	public function defaultHandler($data){}
	public function openTag($tag, $attributes){
		if($tag!='document') throw new Exception("Unexpected tag open expected 'document' but got '$tag'");
		$this->pushState(new PDFDOMParserDocumentState($this->getParser()));
	}
	public function cdata($data){}
	public function closeTag($tag){
		throw new Exception("Unexpected tag got '$tag'");
	}
}