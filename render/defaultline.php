<?php

class PDFDefaultLine extends PDFBlock {
	private $width;
	private $height;
	private $lineHeight;
	private $items;
	private $align;

	public function __construct($height = 0){
		$this->width		= 0;
		$this->height		= $height;
		$this->lineHeight	= 0;
		$this->items		= array();
		$this->align		= 'left';
	}
	
	/**
	 * Height of the line
	 * @return float
	 */
	public function getHeight(){
		return max($this->lineHeight, $this->height);
	}

	/**
	 * Returns the remaining space for new items on the line given a width
	 * @param float $width
	 * @return float
	 */
	public function getRemain($width){
		return max(0, $width - $this->width);
	}
	
	/**
	 *
	 * @param PDFItem $item
	 */
	public function addItem($item, $lineHeight){
		$this->items[]  	= $item;
		$this->width	   += $item->getWidth();
		$this->height		= max($this->height, $item->getHeight());
		$this->lineHeight	= max($this->lineHeight, $lineHeight);
	}
	
	/**
	 * Does last caculations to put all the items on there place
	 */
	public function calculate(){
		$left = 0;
		foreach($this->items as $item){
			$top = $this->getHeight() - $item->getHeight();
			$item->setPosition($left, $top);
			$left+= $item->getWidth();
		}
	}
	
	/**
	 *
	 * @param PDFTarget $target
	 * @param PDFPoint $offset
	 */
	public function render($target, $offset){
		foreach($this->items as $item){
			$item->render($target, clone $offset);
		}
	}
}