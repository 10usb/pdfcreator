<?php

class CSSDocument {
	private $rulesets;
	
	public function __construct(){
		$this->rulesets	= array();
	}
	
	/**
	 * 
	 * @param CSSRuleSet $ruleset
	 */
	public function addRuleSet($ruleset){
		$ruleset->setIndex(count($this->rulesets));
		$this->rulesets[] = $ruleset;
	}
	
	/**
	 * 
	 * @param CSSSelector $path
	 */
	public function match($path){
		$matches = array();
		foreach($this->rulesets as $ruleset){
			if($ruleset->match($path)){
				$matches[] = $ruleset;
			}
		}
		usort($matches, array('CSSRuleSet', 'compare'));
		$result = new CSSRuleSet();
		foreach($matches as $ruleset){
			foreach($ruleset->getProperties() as $key=>$value){
				$result->setProperty($key, $value);
			}
		}
		return $result;
	}

	/**
	 * Returns the CSS
	 * @return string
	 */
	public function __toString(){
		$css = '';
		foreach($this->rulesets as $ruleset){
			$css.= $ruleset."\n";
		}
		return $css;
	}
}