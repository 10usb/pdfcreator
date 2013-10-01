<?php
class PDFDOMParent extends PDFDOMNode {
	protected $tagName;
	protected $children;

	public function __construct($tagName){
		parent::__construct();
		$this->tagName = $tagName;
		$this->children	= array();
	}

	public function getTagName(){
		return $this->tagName;
	}
	
	public function getParent(){
		return $this->parent;
	}
	
	public function getChildren(){
		return $this->children;
	}
	
	public function append($child){
		if($child->parent) return false;

		$child->parent = $this;
		$this->children[] = $child;
	}
	
	public function write($tagName, $text = ''){
		$this->append($element = new PDFDOMElement($tagName));
		if($text) $element->text($text);
		return $element;
	}
	
	public function text($text){
		$this->append($node = new PDFDOMText($text));
		return $node;
	}
}