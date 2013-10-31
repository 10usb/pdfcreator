<?php

class CSSRuleSet {
	private $selectors;
	private $specificity;
	private $properties;
	
	public function __construct($selector = null){
		$this->selector		= $selector;
		$this->specificity	= $selector ? $selector->getSpecificity(new CSSSpecificity()) : null;
		$this->properties	= array();
	}
	
	/**
	 * 
	 * @param string $key
	 * @param string $value
	 */
	public function setProperty($key, $value){
		if($value instanceof CSSProperty){
			$this->properties[$key] = $value;
		}else{
			$this->properties[$key] = new CSSProperty($value);
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @return CSSProperty
	 */
	public function getProperty($key){
		if(isset($this->properties[$key])) return $this->properties[$key];
		return false;
	}
	
	/**
	 * 
	 * @return array<string>
	 */
	public function getProperties(){
		return $this->properties;
	}
	
	/**
	 * 
	 * @param number $index
	 */
	public function setIndex($index){
		$this->specificity->i = $index;
	}
	
	/**
	 * 
	 * @param CSSSelector $path
	 */
	public function match($path){
		return $this->selector->match($path);
	}

	/**
	 *
	 * @param CSSRuleSet $a
	 * @param CSSRuleSet $b
	 */
	public static function compare($a, $b){
		if($a->specificity->a != $b->specificity->a){
			return $a->specificity->a < $b->specificity->a ? -1 : 1;
		}
		if($a->specificity->b != $b->specificity->b){
			return $a->specificity->b < $b->specificity->b ? -1 : 1;
		}
		if($a->specificity->c != $b->specificity->c){
			return $a->specificity->c < $b->specificity->c ? -1 : 1;
		}
		if($a->specificity->i != $b->specificity->i){
			return $a->specificity->i < $b->specificity->i ? -1 : 1;
		}
		return 0;
	}
	
	/**
	 * Returns the CSS
	 * @return string
	 */
	public function __toString(){
		$css = ($this->selector ? $this->selector : '/* null */')." {\n";
		foreach($this->properties as $key=>$value){
			$css.= "  $key: $value;\n";
		}
		$css.= "}\n";
		return $css;
	}
}