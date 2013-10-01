<?php
class PDFDOMStyleselector {
	private $parent;
	private $part;
	private $tag;
	private $class;
	private $childIndex;
	private $direct;
	private $properties;
	private $subSelectors;
	
	/**
	 * 
	 * @param PDFDOMStyleselector $parent
	 * @param string $part
	 * @param bool $direct
	 */
	public function __construct($parent, $part, $direct){
		if(!preg_match('/([a-z0-9]*)(((.)([a-z0-9]+))?)((.)(odd|even|(last|first)\-child))?/s', $part, $matches)) throw new Exception("Invalid selector at '$part'");
		
		$this->parent	= $parent;
		$this->part		= $part;
		$this->direct	= $direct;
		$this->tag		= $matches[1] ? $matches[1] : null;
		if(isset($matches[4]) && $matches[4]==':'){
			$this->childIndex = $matches[5]; 
		}elseif(isset($matches[4]) && $matches[4]=='.'){
			$this->class = $matches[5];
			if(isset($matches[7]) && $matches[7]==':'){
				$this->childIndex = $matches[8];
			}
		}elseif($this->tag==null) throw new Exception("Invalid selector at '$part'");
		
		$this->subSelectors = array();
		$this->properties = array();
	}
	
	/**
	 * Add a child selector
	 * @param string $part
	 * @param string $direct
	 * @return PDFDOMStyleselector
	 */
	public function addSelector($part, $direct){
		$selector = new PDFDOMStyleselector($this, $part, $direct);
		$this->subSelectors[] = $selector;
		return $selector;
	}
	
	/**
	 * Returns a child selector
	 * @param string $part
	 * @param bool $directt
	 * @return PDFDOMStyleselector
	 */
	public function getSelector($part, $direct){
		foreach($this->subSelectors as $selector){
			if($selector->direct==$direct && $selector->part==$part){
				return $selector;
				break;
			}
		}
		return null;
	}
	
	/**
	 * 
	 * @param string $tag
	 * @param string $class
	 * @param int $index
	 * @param int $count
	 * @return boolean
	 */
	public function match($tag, $class, $index, $count){
		if($this->tag && $this->tag!=$tag) return false;
		if($this->class && $this->class!=$class) return false;
		if($childIndex){
			switch($childIndex){
				case 'even': if(($index&1)!=0) return false;
				case 'odd': if(($index&1)!=1) return false;
				case 'first-child': if($index!=0) return false;
				case 'last-child': if($index!=($count-1)) return false;
			}
		}
		return true;
	}
	
	/**
	 * 
	 */
	public function getPart(){
		return $this->part;
	}
	
	/**
	 * 
	 */
	public function isDirect(){
		return $this->direct;
	}
	
	/**
	 * 
	 */
	public function hasProperties(){
		return !!$this->properties;
	}
	
	/**
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function setProperty($key, $value){
		if(isset($this->properties[$key])) unset($this->properties[$key]);
		$this->properties[$key] = $value;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function __toString(){
		if($this->parent) return $this->parent.($this->direct ? ' > ': ' ').$this->part;
		return ($this->direct ? '> ': '').$this->part;
	}

	/**
	 *
	 * @return string
	 */
	public function toCSS(){
		$css = $this." {\n";
		foreach($this->properties as $key=>$value){
			$css.= "  $key: $value\n";
		}
		$css.= "}\n";
		return $css;
	}
}