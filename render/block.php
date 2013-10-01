<?php

abstract class PDFBlock {
	/**
	 * Do any calculation needed to know the height and or be able to render this block
	 */
	public function calculate(){
	}

	/**
	 * Height of the line
	 * @return float
	 */
	public abstract function getHeight();
	
	/**
	 * Returns a slice that fits within the height
	 * @param float $height
	 * @return PDFContent
	 */
	public function slice($height){
		return null;
	}
	
	/**
	 * 
	 * @param PDFTarget $target
	 * @param PDFPoint $offset
	 */
	public abstract function render($target, $offset);
}