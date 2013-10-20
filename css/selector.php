<?php

class CSSSelector {
	private $type;
	private $tagName;
	private $classes;
	private $pseudos;
	private $selector;
	
	/**
	 * 
	 * @param string $type
	 * @param string $tagName
	 * @param array<string> $classes
	 * @param array<string> $pseudos
	 */
	public function __construct($type, $tagName, $classes, $pseudos){
		$this->type		= $type;
		$this->tagName	= $tagName;
		$this->classes	= $classes ? $classes : array();
		$this->pseudos	= $pseudos ? $pseudos : array();
		$this->selector	= null;
	}
	
	/**
	 * 
	 * @param CSSSelector $selector
	 * @return CSSSelector
	 */
	public function setSelector($selector){
		return $this->selector = $selector;
	}
	
	/**
	 * 
	 * @param CSSSelector $path
	 */
	public function match($path){
		$current = $path;

		if($this->type=='>'){
			if(!$this->matches($path)) return false;
			
			if($path->selector==null){
				return $this->selector==null;
			}
			if($this->selector==null) return false;
			
			return $this->selector->match($path->selector);
		}
		
		$match = false;
		while($current!=null){
			if($this->matches($current)){
				$match = true;
				break;
			}
			$current = $current->selector;
		}
		if($current==null) return false;
		
		if($current->selector==null){
			return $this->selector==null;
		}
		if($this->selector==null) return false;

		return $this->selector->match($current->selector);
	}
	
	/**
	 * 
	 * @param CSSSelector $other
	 */
	public function matches($other){
		if($this->tagName && $this->tagName!=$other->tagName) return false;
		if($this->classes && count(array_intersect($this->classes, $other->classes))!=count($this->classes)) return false;
		if($this->pseudos && count(array_intersect($this->pseudos, $other->pseudos))!=count($this->pseudos)) return false;
		return true;
	}
	
	/**
	 * 
	 * @param CSSSpecificity $specificity
	 */
	public function getSpecificity($specificity){
		if($this->tagName) $specificity->c++;
		if($this->pseudos) $specificity->c+=count($this->pseudos);
		if($this->classes) $specificity->b+=count($this->classes);
		if($this->selector!=null) return $this->selector->getSpecificity($specificity);
		return $specificity;
	}

	/**
	 * Returns the CSS
	 * @return string
	 */
	public function __toString(){
		$css = '';
		if($this->type){
			$css.= $this->type.' ';
		}
		if($this->tagName){
			$css.= $this->tagName;
		}
		if($this->classes){
			$css.= '.'.implode('.', $this->classes);
		}
		if($this->pseudos){
			$css.= ':'.implode(':', $this->pseudos);
		}
		if($this->selector){
			$css.= ' '.$this->selector;
		}
		return $css;
	}
}