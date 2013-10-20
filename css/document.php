<?php

class PDFCSSDocument {
	private $rulesets;
	
	public function __construct(){
		$this->rulesets	= array();
	}

	/**
	 * Returns the CSS
	 * @return string
	 */
	public function __toString(){
		$css = '';
		foreach($this->rulesets as $ruleset){
			$css.= $ruleset."\n\n";
		}
		return $css;
	}
}