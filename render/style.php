<?php

class PDFStyle {
	/**
	 * 
	 * @var PDFDocument
	 */
	private $document;
	
	/**
	 * 
	 * @var array
	 */
	private $_data_;
	
	/**
	 * 
	 * @param PDFDocument $document
	 */
	public function __construct($document){
		$this->document = $document;
		$this->_data_ = array();
	}
	
	public function __get($key){
		if(array_key_exists($key, $this->_data_)) return $this->_data_[$key];
		return null;
	}
	
	public function __set($key, $value){
		$this->_data_[$key] = $value;
	}

	public function setLineHeight($height){
		$this->lineHeight = $height;
	}
	
	public function setFont($fontName, $size){
		$font = $this->document->getResources()->getFont($fontName);
		if(!$font) return false;
	
		$this->font = $font;
		$this->textSize = $size;
	
		return true;
	}
	
	public function setColor($red, $green = null, $blue = null){
		if($green==null && $blue==null){
			if(is_int($red)){
				$this->textColor = new PDFColor($red, $red, $red);
			}else{
				if(preg_match('/^\#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/is', strtolower($red), $matches)){
					$this->textColor = new PDFColor(hexdec($matches[1]), hexdec($matches[2]), hexdec($matches[2]));
				}elseif(preg_match('/^\#([0-9a-f])([0-9a-f])([0-9a-f])$/is', strtolower($red), $matches)){
					$this->textColor = new PDFColor(hexdec($matches[1].$matches[1]), hexdec($matches[2].$matches[2]), hexdec($matches[2].$matches[2]));
				}else{
					throw new Exception("Invalid color value '$red'");
				}
			}
			
		}else{
			$this->textColor = new PDFColor($red, $green, $blue);
		}
	}
	
	public function getTextWidth($text){
		return $this->font->getTextWidth($text) * $this->textSize;
	}
	
	public function getTextSplit(&$text, $width, $maxWidth, $breakWord=false){
		if($width <= $maxWidth){
			$part = substr($text, 0);
			$text = '';
			return $part;
		}
	
		$length = 0;
		$currentWidth = 0;
		$partWidth = 0;
		while(preg_match('/(\s*\S+)(\s)/s', $text, $match, 0, $length)){
			$partWidth = $this->getTextWidth($match[1]);
			if(($currentWidth + $partWidth) > $maxWidth) break;
				
			$length+= strlen($match[1]) + 1;
			$currentWidth += $partWidth + $this->getTextWidth($match[2]);
				
			// Clear as flag
			$partWidth = 0;
		}
	
		if($length > 0){
			if($partWidth==0){
				// check if it could get extended
				$extend = substr($text, $length);
				$partWidth = $this->getTextWidth($extend);
				if(($currentWidth + $partWidth) <= $maxWidth){
					$length+= strlen($extend);
					$currentWidth +=$partWidth;
				}
			}
			$part = substr($text, 0, $length);
			$text = substr($text, $length);
			return $part;
		}
	
		if(!$breakWord) return null;
	
	
	
		$length = floor(($maxWidth / $this->__getTextWidth('_')) / 2);
		$currentWidth = $this->__getTextWidth(substr($text, 0, $length));
	
		for(;;){
			$partLength = max(1, floor($length * ((($maxWidth - $currentWidth) / $maxWidth) / 2)));
			$partWidth = $this->__getTextWidth(substr($text, $length, $partLength));
			if(($currentWidth + $partWidth) > $maxWidth) break;
			$length += $partLength;
			$currentWidth += $partWidth;
		}
	
		$part = substr($text, 0, $length);
		$text = substr($text, $length);
		return $part;
	}
}