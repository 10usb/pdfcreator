<?php

class PDFColor {
	private $red;
	private $green;
	private $blue;

	public function __construct($red, $green, $blue){
		$this->red		= $red;
		$this->green	= $green;
		$this->blue		= $blue;
	}

	public function getRed(){
		return $this->red;
	}

	public function getGreen(){
		return $this->green;
	}

	public function getBlue(){
		return $this->blue;
	}
}

class PDFPoint {
	private $left;
	private $top;

	public function __construct($left, $top){
		$this->left	= $left;
		$this->top	= $top;
	}

	public function getLeft(){
		return $this->left;
	}

	public function getTop(){
		return $this->top;
	}
	
	public function addLeft($value){
		$this->left+= $value;
	}
	
	public function addTop($value){
		$this->top+= $value;
	}
}

class PDFSize {
	private $width;
	private $height;

	public function __construct($width, $height){
		$this->width	= $width;
		$this->height	= $height;
	}

	public function getWidth(){
		return $this->width;
	}

	public function getHeight(){
		return $this->height;
	}

	public function toRactangle(){
		return new PDFRectangle(0, 0, $this->width, $this->height);
	}

	public function isEqual($other){
		return $this->width==$other->width && $this->height==$other->height;
	}
}

class PDFRectangle {
	private $point;
	private $size;

	public function __construct($left, $top, $width, $height){
		$this->point	= new PDFPoint($left, $top);
		$this->size		= new PDFSize($width, $height);
	}

	public function getPoint(){
		return $this->point;
	}

	public function getSize(){
		return $this->size;
	}
	
	public function toArray($box = false){
		$array = new PDFArray();
		if($box){
			$array->push($this->point->getLeft());
			$array->push($this->point->getTop());
			$array->push($this->point->getLeft() + $this->size->getWidth());
			$array->push($this->point->getTop() + $this->size->getHeight());
		}else{
			$array->push($this->point->getLeft());
			$array->push($this->point->getTop());
			$array->push($this->size->getWidth());
			$array->push($this->size->getHeight());
		}
		return $array;
	}
}

class PDFName {
	private $value;

	public function __construct($value){
		$this->value = $value;
	}

	public function __toString(){
		return '/'.$this->value;
	}
}

class PDFText {
	private $value;

	public function __construct($value){
		$this->value = $value;
	}

	public function __toString(){
		$search = array("\\", "\n", "\r", "\t", "\b", "\f", "(", ")");
		$replace = array('\\', '\n', '\r', '\t', '\b', '\f', '\(', '\)');
		return '('.str_replace($search, $replace, $this->value).')';
	}
}


class PDFArray {
	private $values;

	public function __construct($values = array()){
		$this->values = $values;
	}

	public function push($value){
		$this->values[] = $value;
	}
	
	public function get($index){
		if(isset($this->values[$index])) return $this->values[$index];
		return null;
	}

	public function size(){
		return count($this->values);
	}

	public function __toString(){
		return '['.implode(' ', $this->values).']';
	}
}

class PDFPair {
	private $key;
	private $value;

	public function __construct($key, $value){
		$this->key		= $key;
		$this->value	= $value;
	}

	public function getKey(){
		return $this->key;
	}

	public function setValue($value){
		$this->value	= $value;
	}

	public function __toString(){
		if($this->value!==null) return '/'.$this->key.' '.$this->value;
		return '/'.$this->key;
	}
}

class PDFDictionary {
	private $entries;

	public function __construct(){
		$this->entries = array();
	}

	public function set($key, $value = null){
		foreach($this->entries as $entry){
			if($entry->getKey()==$key){
				$entry->setValue($value);
				return;
			}
		}
		$this->entries[] = new PDFPair($key, $value);
	}

	public function __toString(){
		return '<<'.implode(' ', $this->entries).'>>';
	}
}