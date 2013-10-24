<?php
class PDFDOMElement extends PDFDOMParent {
	private $attributes;
	
	public function __construct($tagName){
		parent::__construct($tagName);
		$this->attributes = array();
	}
	
	public function setAttributes($attributes){
		$this->attributes = array_merge($this->attributes, $attributes);
	}
	
	public function getAttribute($name){
		return $this->attributes[$name];
	}
	
	public function getClasses(){
		if(!isset($this->attributes['class'])) return array();
		return explode(' ', $this->attributes['class']);
	}
	
	public function addClass($name){
		$classes = $this->getClasses();
		if(in_array($name, $classes)) return false;
		
		$classes[] = $name;
		$this->attributes['class'] = implode(' ', $classes);
		return true;
	}
	
	public function removeClass($name){
		$classes = $this->getClasses();
		
		$index = array_search($name, $classes);
		if($index===false) return false;
		
		unset($classes[$index]);
		$this->attributes['class'] = implode(' ', $classes);
		return true;
	}

	/**
	 * Returns the XML
	 * @return string
	 */
	public function __toString(){
		$xml = '';
		$xml.= "\n<".$this->getTagName();
		foreach($this->attributes as $key=>$value){
			$xml.= ' '.$key.'="'.str_replace('"', '&qout;', htmlentities($value)).'"';
		}
		$xml.= ">\n";
		foreach($this->getChildren() as $child){
			if($child instanceof PDFDOMText){
				$xml.= trim($child);
			}else{
				$xml.= str_replace("\n", "\n  ", $child);
			}
		}
		$xml.= "\n</".$this->getTagName().">";
		return $xml;
	}
}