<?php

class PDFDOMParserInfoState extends PDFDOMParserState {
	private $key;
	private $value;
	
	public function __construct($parser){
		parent::__construct($parser);
		$this->key = null;
	}

	
	public function openTag($tag, $attributes){
		if($this->key!=null) throw new Exception("Unexpected open tag inside a key tag");
		$this->key = $tag;
		$this->value = '';
	}
	
	public function cdata($data){
		if(!$this->key) return;
		$this->value.= $data;
	}
	
	public function closeTag($tag){
		if($tag=='info'){
			$this->popState();
		}else{
			$this->getDocument()->getInfo()->set($this->key, $this->value);
			$this->key = null;
		}
	}
}