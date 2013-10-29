<?php

class CSSProperty {
	private $delimeter;
	private $values;
	
	/**
	 * 
	 * @param string $values
	 */
	public function __construct($value){
		if(!preg_match_all('/(\s(,\s*)?(".+?"|[^" ,]+))/is', " $value ", $matches, PREG_SET_ORDER)) throw new Exception("Invalid property '$value'");
		$this->delimeter = isset($matches[1]) ? ' '.trim($matches[1][2]) : ' ';
		$this->values = array();
		foreach($matches as $match){
			$this->values[] = CSSValue::parse($match[3]);
		}
	}
	
	/**
	 * Return howmany arguments there are
	 * @return number
	 */
	public function getCount(){
		return count($this->values);
	}
	
	/**
	 * Returns the value at the given position
	 * @param CSSValue $index
	 */
	public function getValue($index){
		return $this->values[$index];
	}
	
	/**
	 * 
	 * @param string $unit
	 * @param string $value
	 * @throws Exception
	 * @return number
	 */
	public function getMeasurement($unit, $relativeValue = null){
		foreach($this->values as $value){
			$result = $value->getMeasurement($unit, $relativeValue, false);
			if($result!==false) return $result;
		}
		throw new Exception("Invalid values");
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return string
	 */
	public function getName(){
		foreach($this->values as $value){
			$result = $value->getName(false);
			if($result!==false) return $result;
		}
		throw new Exception("Invalid values");
	}
	
	/**
	 * 
	 * @return CSSColor
	 */
	public function getColor(){
		foreach($this->values as $value){
			if($value->isColor()) return $value;
		}
	}
	
	/**
	 * 
	 * @throws Exception
	 * @return string
	 */
	public function getString(){
		foreach($this->values as $value){
			$result = $value->getString(false);
			if($result!==false) return $result;
		}
		throw new Exception("Invalid values");
	}
	

	/**
	 * Returns the CSS
	 * @return string
	 */
	public function __toString(){
		return implode($this->delimeter, $this->values);
	}
}