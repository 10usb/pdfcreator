<?php
class CSSPath {
	/**
	 * 
	 * @var CSSDocument
	 */
	private $documment;
	
	/**
	 * 
	 * @var array<CSSSelector>
	 */
	private $items;
	
	/**
	 * 
	 * @var array<CSSRuleSet>
	 */
	private $rulesets;
	
	/**
	 * 
	 * @param CSSDocument $document
	 */
	public function __construct($document){
		$this->document	= $document;
		$this->items	= array();
		$this->rulesets	= null;
	}
	
	/**
	 * 
	 * @return CSSRuleSet
	 */
	public function getRuleSet(){
		return end($this->rulesets);
	}
	
	/**
	 * 
	 * @param string $tagName
	 * @param array<string> $classes
	 * @param array<string> $pseudos
	 */
	public function push($tagName, $classes, $pseudos){
		$selector = new CSSSelector('>', $tagName, $classes, $pseudos);
		if($this->items){
			$last = end($this->items);
			$last->setSelector($selector);
		}
		$this->items[] = $selector;
		

		$ruleset = $this->document->match($this->items[0]);
		if($this->rulesets){
			$ruleset->setParent(end($this->rulesets));
		}
		$this->rulesets[] = $ruleset;
	}
	
	/**
	 * 
	 */
	public function pop(){
		array_pop($this->items);
		$last = end($this->items);
		$last->setSelector(null);

		array_pop($this->rulesets);
	}
}