<?php

class PDFItemText extends PDFItem {
	private $style;
	private $text;

	public function __construct($width, $style, $text){
		parent::__construct($width, $style->textSize);
		$this->style	= $style;
		$this->text		= $text;
	}
	
	public function render($target, $offset){
		//$target->getPage()->setLineColor(0, 0, 0);
		//$target->getPage()->setColor(192, 192, 192);
		//$target->getPage()->rectangle($this->getLeft() + $offset->getLeft(), $this->getTop() + $offset->getTop(), $this->getWidth(), $this->getHeight(), true, true);
		
		$target->getPage()->setFont($this->style->font, $this->style->textSize);
		$target->getPage()->setColor($this->style->textColor->getRed(), $this->style->textColor->getGreen(), $this->style->textColor->getBlue());

		$target->getPage()->text($this->getLeft() + $offset->getLeft(), $this->getTop() + $offset->getTop(), $this->text);
	}
}