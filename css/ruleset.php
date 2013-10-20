<?php

class PDFCSSRuleSet {
	private $selectors;
	private $properties;
	
	public function __construct($selector){
		$this->selector		= $selector;
		$this->properties	= array();
	}

	/**
	 * Returns the CSS
	 * @return string
	 */
	public function __toString(){
		$css = ($this->selector ? $this->selector : '/* null */')." {\n";
		foreach($this->properties as $key=>$value){
			$css.= "  $key: $value\n";
		}
		$css.= "}\n";
		return $css;
	}
}