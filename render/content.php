<?php

class PDFContent {
	private $document;
	private $width;
	private $lines;
	private $writer;
	
	/**
	 * Constructs a new content area with the given width
	 * @param PDFDocument $document
	 * @param float $width
	 */
	public function __construct($document, $width) {
		$this->document	= $document;
		$this->width	= $width;
		$this->lines	= array();
		$this->writer	= new PDFContentWriter($this);
	}
	
	/**
	 * Returns the document this content belongs to
	 * @return PDFDocument
	 */
	public function getDocument(){
		return $this->document;
	}
	
	/**
	 * Returns the width of the content area
	 * @return float
	 */
	public function getWidth(){
		return $this->width;
	}
	
	/**
	 * Return the needed height to render this content block
	 * @return float
	 */
	public function getHeight(){
		if($this->getLastLine()) $this->getLastLine()->calculate();
		
		$height = 0;
		foreach($this->lines as $line){
			$height+= $line->getHeight();
		}
		return $height;
	}
	
	/**
	 * Returns the content writer to allow easy writing of text and images
	 * @return PDFContentWriter
	 */
	public function getWriter(){
		return $this->writer;
	}
	
	/**
	 * Return the last line if present
	 * @return PDFBlock
	 */
	public function getLastLine(){
		if(!$this->lines) return null;
		return end($this->lines); 
	}
	
	/**
	 * Appends the block to the end of the content
	 * @param PDFBlock $block
	 * @return PDFBlock
	 */
	public function append($block){
		// Make sure the last line gets calculates
		if($lastLine = $this->getLastLine()) $lastLine->calculate();
		
		// Add the block as new line
		$this->lines[] = $block;
		
		// Return the given block
		return $block;
	}

	/**
	 * Returns a slice that fits within the height
	 * @param float $height
	 * @return PDFContent
	 */
	public function slice($height){
		if($this->getHeight() <= $height || count($this->lines)<=1) return null;
		
		$sliceHeight = 0;
		$sliceLineCount = 0;
		foreach($this->lines as $line){
			$lineHeight = $line->getHeight();
			if(($sliceHeight + $lineHeight) > $height) break;

			$sliceHeight+= $lineHeight;
			$sliceLineCount++;
		}
		
		// Slice of the piece that fits
		$slice = array_splice($this->lines, 0, $sliceLineCount);
		
		// Check if the last line can be split
		$splitLine = $this->lines[0]->slice($height - $sliceHeight);
		if($splitLine) $slice[] = $splitLine;
		
		// Makes use atleast one line is cut of
		if(count($slice)<=0){
			$slice = array_splice($this->lines, 0, 1);
		}
		
		// Create new content
		$newContent = new PDFContent($this->document, $this->width);
		$newContent->lines = $slice;

		return $newContent;
	}

	/**
	 * Returns the needed height to split the content into the given number of columns equally devided
	 * @param float $height
	 * @param int $columns
	 * @return float
	 */
	public function columns($height, $columns){
	}
	
	/**
	 * Renders this content on the given target with the given offset
	 * @param PDFTarget $target
	 * @param PDFPoint $offset
	 */
	public function render($target, $offset){
		if($this->getLastLine()) $this->getLastLine()->calculate();
		
		foreach($this->lines as $line){
			$line->render($target, clone $offset);
			$offset->addTop($line->getHeight());
		}
	}
}