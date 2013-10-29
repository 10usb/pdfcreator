<?php

class CSSMeasurement extends CSSValue {
	/**
	 * 
	 * @var number
	 */
	private $number;
	
	/**
	 * 
	 * @var string
	 */
	private $unit;

	/**
	 * (non-PHPdoc)
	 * @see CSSValue::init()
	 */
	protected function init(){
		if(!preg_match('/^(\d+(\.\d+)?)(\%|in|cm|mm|em|pt|pc|px)$/is', $this->value, $matches)) throw new Exception("Invalid string '$this->value'");
		$this->number	= $matches[1];
		$this->unit		= $matches[3];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CSSValue::getMeasurement()
	 */
	public function getMeasurement($unit, $value = null, $throw = true){
		if($this->unit=='%'){
			$value = $value instanceof CSSMeasurement ? $value : new CSSMeasurement($value);
			return self::convert($value->number, $value->unit, $unit) * $this->number / 100;
		}
		return self::convert($this->number, $this->unit, $unit);
	}
	
	/**
	 * Convert units from to
	 * @param number $number
	 * @param string $from
	 * @param string $to
	 * @return number
	 */
	public static function convert($number, $from, $to){
		switch($from){
			case 'in': switch($to){
				case 'in': return $number;
				case 'cm': return $number * 2.54;
				case 'mm': return $number * 25.4;
				case 'pt': return $number * 72;
				case 'pc': return $number * 6;
				case 'px': return $number * 96;
			}
			case 'cm': switch($to){
				case 'in': return $number / 2.54;
				case 'cm': return $number;
				case 'mm': return $number / 10;
				case 'pt': return $number * 72 / 2.54;
				case 'pc': return $number * 6 / 2.54;
				case 'px': return $number * 96 / 2.54;
			}
			case 'mm': switch($to){
				case 'in': return $number / 25.4;
				case 'cm': return $number * 10;
				case 'mm': return $number;
				case 'pt': return $number * 72 / 25.4;
				case 'pc': return $number * 6 / 25.4;
				case 'px': return $number * 96 / 25.4;
			}
			case 'pt': switch($to){
				case 'in': return $number / 72;
				case 'cm': return $number * 2.54  / 72;
				case 'mm': return $number * 25.4 / 72;
				case 'pt': return $number;
				case 'pc': return $number * 12;
				case 'px': return $number * 96  / 72;
			}
			case 'pc': switch($to){
				case 'in': return $number / 6;
				case 'cm': return $number * 2.54  / 6;
				case 'mm': return $number * 25.4 / 6;
				case 'pt': return $number / 12;
				case 'pc': return $number;
				case 'px': return $number * 96  / 6;
			}
			case 'px': switch($to){
				case 'in': return $number / 96;
				case 'cm': return $number * 2.54 / 96;
				case 'mm': return $number * 25.4 / 96;
				case 'pt': return $number * 72 / 96;
				case 'pc': return $number * 6 / 96;
				case 'px': return $number;
			}
		}
		
		throw new Exception("Invalid conversion from $from to $to with a value of $number");
	}
}