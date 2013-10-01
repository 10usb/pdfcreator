<?php

class PDFDOMParserDefaultState extends PDFDOMParserState {
	private $tag;

	public function __construct($parser, $tag){
		parent::__construct($parser);
		$this->tag = $tag;
		echo "Open : $tag\n";
	}

	public function openTag($tag, $attributes){
		$this->pushState(new PDFDOMParserDefaultState($this->getParser(), $tag));
	}

	public function closeTag($tag){
		if($tag!=$this->tag) throw new Exception("Unexpected tag close expected '$this->tag' but got '$tag'");
		echo "Close: $tag\n";
		$this->popState();
	}
}