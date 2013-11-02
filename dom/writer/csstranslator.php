<?php
class PDFDOMCSSTranslator extends CSSTranslator {
	/**
	 * (non-PHPdoc)
	 * @see CSSTranslator::getProperty()
	 */
	public function getProperty($ruleset, $key){
		$property = $ruleset->getProperty($key);
		if($property){
			if($property->getCount()==1) return $property->getValue(0);
			throw new Exception("Invalid property count for '$key'");
		}
		
		if(preg_match('/^(.+)-(left|top|right|bottom)$/', $key, $matches)){
			return $this->getTranslatedStyleLTRB($ruleset, $matches[1], $matches[2]);
		}else{
			switch($key){
				case 'border-color': try {
					if($property = $ruleset->getProperty('border')){
						return $property->getColor();
					}
				} catch(Exception $ex){
				}
				return null;
				case 'border-width': if($property = $ruleset->getProperty('border')){
					// TODO make use of a check function
					for($i=0; $i<$property->getCount(); $i++){
						try {
							$property->getMeasurement('pt');
							return $property;
						} catch(Exception $ex){
						}
					}
				}
				return null;
				case 'background-color': try {
					if($property = $ruleset->getProperty('border')){
						return $property->getColor();
					}
				} catch(Exception $ex){
				}
				return null;
			}
		}
		throw new Exception("Unknow property '$key'");
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSTranslator::getValue()
	 */
	public function getValue($value, $key){
		switch ($key){
			case 'font-size': return $value->getMeasurement('pt');
			case '-pdf-font-family': return $value->getString();
			case 'display': return $value->getName();
			default:
				if(strpos($key, 'margin')!==false) return $value->getMeasurement('pt');
				if(strpos($key, 'padding')!==false) return $value->getMeasurement('pt');
				if(strpos($key, 'height')!==false) return $value->getMeasurement('pt');
				if(strpos($key, 'width')!==false) return $value->getMeasurement('pt');
				if(strpos($key, 'color')!==false){
					return new PDFColor($value->getRed(), $value->getGreen(), $value->getBlue());
				}
				throw new Exception("Usupported key '$key'");
		}
	}
	
	/**
	 *
	 * @param CSSRuleSet $ruleset
	 * @param string $property
	 * @return CSSValue
	 * @throws Exception
	 */
	private function getTranslatedStyleLTRB($ruleset, $key, $side){
		$property = $ruleset->getProperty($key);
		if(!$property) throw new Exception("Unknow property '$key'");
	
		switch($property->getCount()){
			case 1: return $property->getValue(0);
			case 2: switch($side){
				case 'top': case 'bottom': return $property->getValue(0);
				case 'left': case 'right': return $property->getValue(1);
			}
			case 3: switch($side){
				case 'top': return $property->getValue(0);
				case 'right': return $property->getValue(1);
				case 'bottom': return $property->getValue(2);
				case 'left':  return new CSSValue('0pt');
			}
			case 4: switch($side){
				case 'top': return $property->getValue(0);
				case 'right': return $property->getValue(1);
				case 'bottom': return $property->getValue(2);
				case 'left':  return $property->getValue(3);
			}
				
		}
		throw new Exception("Invalid property count for '$key'");
	}
}