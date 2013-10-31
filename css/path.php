<?php
class CSSPath {
	/**
	 * 
	 * @var CSSDocument
	 */
	private $documment;
	/**
	 * 
	 * @var CSSTranslator
	 */
	private $translator;
	
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
	public function __construct($document, $translator){
		$this->document		= $document;
		$this->translator	= $translator;
		$this->items		= array();
		$this->rulesets		= null;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return CSSProperty
	 */
	public function getProperty($key){
		foreach(array_reverse($this->rulesets) as $ruleset){
			$property = $this->translator->getProperty($ruleset, $key);
			if($property==null) return null;
			try {
				if($property->getName()=='inherit') continue;
			}catch(Exception $ex){
				
			}
			return $property;
		}
		
		return null;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function getValue($key){
		$property = $this->getProperty($key);
		if($property==null) return null;
		return $this->translator->getValue($property, $key);
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