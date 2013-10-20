<?php

class PDFCSSSelector {
	private $type;
	private $tagName;
	private $classes;
	private $pseudos;
	private $selector;
	
	public function __construct($type, $tagName, $classes, $pseudos){
		$this->type		= $type;
		$this->tagName	= $tagName;
		$this->classes	= $classes ? $classes : array();
		$this->pseudos	= $pseudos ? $pseudos : array();
		$this->selector	= null;
	}
	
	/**
	 * 
	 * @param PDFCSSSelector $selector
	 * @return PDFCSSSelector
	 */
	public function setSelector($selector){
		return $this->selector = $selector;
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