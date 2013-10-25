<?php

class PDFDOMParser {
	private $parser;
	private $document;
	private $exception;
	private $stateStack;
	private $currentState;
	
	public function __construct(){
		$this->parser = xml_parser_create('UTF-8');
		xml_set_object($this->parser, $this);
		xml_set_default_handler($this->parser, 'defaultHandler');
		xml_set_element_handler($this->parser, 'openTag', 'closeTag');
		xml_set_character_data_handler($this->parser, 'cdata');
		
		$this->document		= new PDFDOMDocument();
		$this->exception	= null;
		$this->stateStack	= array();
		$this->currentState	= new PDFDOMParserFileState($this);
	}
	
	public function addDefaultStyle($css){
		$parser = new CSSparser($this->document->getStylesheet());
		$parser->parse($css);
	}
	
	public function pushState($state){
		array_push($this->stateStack, $this->currentState);
		$this->currentState = $state;
	}
	
	public function popState(){
		if(!$this->stateStack) throw new Exception("Empty state stack");
		$this->currentState = array_pop($this->stateStack);
	}

	public function getDocument(){
		return $this->document;
	}

	public function getException(){
		return $this->exception;
	}
	
	public function parse($data){
		try {
			xml_parse($this->parser, $data);
			return $this->document;
		}catch(Exception $exception){
			$this->exception = $exception;
			return false;
		}
	}
	
	public function defaultHandler($parser, $data){
		if(preg_match('/^\&[a-z0-9]+\;$/i', $data)){
			$value = html_entity_decode($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
			if($value!=$data){
				$this->currentState->cdata($value);
			}else{
				$this->currentState->defaultHandler($data);
			}
		}elseif(preg_match('/^\<\!\-\-(.+)\-\-\>/is', $data, $matches)){
			$this->currentState->comment($matches[1]);
		}else{
			$this->currentState->defaultHandler($data);
		}
	}
	
	public function openTag($parser, $tag, $attributes){
		$this->currentState->openTag(strtolower($tag), array_change_key_case($attributes));
	}
	
	public function closeTag($parser, $tag){
		$this->currentState->closeTag(strtolower($tag));
	}
	
	public function cdata($parser, $data){
		$this->currentState->cdata($data);
	}
}