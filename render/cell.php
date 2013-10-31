<?php

class PDFCell extends PDFBlock {
	private $document;
	private $maxWidth;
	private $width;
	private $height;
	private $style;
	private $content;
	
	/**
	 * 
	 * @param float $width
	 * @param float $height
	 */
	public function __construct($document, $width, $style){
		$this->document	= $document;
		$this->maxWidth	= $width;
		$this->width	= $style->width ? min($style->width, $width) : $width;
		$this->height	= $style->height ? $style->height : 0;
		$this->style	= $style;
		
		$contentWidth = $this->width;
		if($this->style->paddingLeft)	$contentWidth -= $this->style->paddingLeft;
		if($this->style->paddingRight)	$contentWidth -= $this->style->paddingRight;
		if($this->style->borderWidth)	$contentWidth -= $this->style->borderWidth * 2;
		$this->content	= new PDFContent($document, $contentWidth);
	}

	/**
	 * (non-PHPdoc)
	 * @see PDFLine::getHeight()
	 */
	public function getHeight(){
		$height = $this->content->getHeight();
		if($this->style->paddingTop)	$height += $this->style->paddingTop;
		if($this->style->paddingBottom)	$height += $this->style->paddingBottom;
		if($this->style->borderWidth)	$height += $this->style->borderWidth * 2;

		return max($this->height, $height);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PDFBlock::slice()
	 */
	public function slice($height, $newHeight = true){
		$contentHeight = $height;
		if($this->style->paddingTop)	$contentHeight -= $this->style->paddingTop;
		if($this->style->paddingBottom)	$contentHeight -= $this->style->paddingBottom;
		if($this->style->borderWidth)	$contentHeight -= $this->style->borderWidth * 2;

		$slice = $this->content->slice($contentHeight);
		if(!$slice) return null;
		
		$newCell = new PDFCell($this->document, $this->maxWidth, clone $this->style);
		$newCell->height	= min($this->height, $height);
		$newCell->content	= $slice;

		if($newHeight) $this->height = max(0, $this->height - $slice->getHeight());
		
		return $newCell;
	}
	
	/**
	 * 
	 * @return PDFContent
	 */
	public function getContent(){
		return $this->content;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PDFLine::render()
	 */
	public function render($target, $offset){
		$border = false;
		$filled = false;

		if($this->style->borderColor){
			$border = true;
			$borderWidth = $this->style->borderWidth ? $this->style->borderWidth : 1;
			$target->getPage()->setLineWidth($borderWidth);
			$target->getPage()->setLineColor($this->style->borderColor);
		}
		if($this->style->backgroundColor){
			$filled = true;
			if(!$border) $borderWidth = 0;
			$target->getPage()->setColor($this->style->backgroundColor);
		}
		if($border || $filled){
			$target->getPage()->rectangle($offset->getLeft()+$borderWidth/2, $offset->getTop()+$borderWidth/2, $this->width-$borderWidth, $this->getHeight()-$borderWidth, $filled, $border);
		}
		
		$contentOffset = clone $offset;
		if($this->style->paddingLeft)	$contentOffset->addLeft($this->style->paddingLeft);
		if($this->style->paddingTop)	$contentOffset->addTop($this->style->paddingTop);
		
		if($this->style->borderWidth){
			$contentOffset->addLeft($this->style->borderWidth);
			$contentOffset->addTop($this->style->borderWidth);
		}
		$this->content->render($target, $contentOffset);
	}
}