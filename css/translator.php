<?php

abstract class CSSTranslator {
	/**
	 * 
	 * @param CSSRuleSet $ruleset
	 * @param string $key
	 * @return CSSValue
	 */
	public abstract function getProperty($ruleset, $key);
	
	/**
	 * 
	 * @param CSSValue $property
	 * @param string $key
	 * @return mixed
	 */
	public abstract function getValue($value, $key);
}