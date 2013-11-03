<?php

class PDFContentWriter {
	/**
	 * 
	 * @var PDFContent
	 */
	private $content;
	
	/**
	 * 
	 * @var PDFStyle
	 */
	private $style;
	
	/**
	 * 
	 * @param PDFContent $content
	 */
	public function __construct($content) {
		$this->content	= $content;
		$this->style	= new PDFStyle($content->getDocument());
		$this->style->setLineHeight(0);
	}
	
	/**
	 * 
	 * @return PDFStyle
	 */
	public function getStyle(){
		return $this->style;
	}
	
	/**
	 * 
	 * @param string $text
	 */
	public function text($text){
		$line = $this->content->getLastLine();
		if(!$line || !$line instanceof PDFDefaultLine){
			$line = $this->content->append(new PDFDefaultLine());
		}
	
		$width = $this->style->getTextWidth($text);
		$remain = $line->getRemain($this->content->getWidth());
	
		while($width && $width > $remain){
			$part = $this->style->getTextSplit($text, $width, $remain);
			if($part===null){
				$line = $this->content->append(new PDFDefaultLine());
				$remain = $line->getRemain($this->content->getWidth());
				$part = $this->style->getTextSplit($text, $width, $remain, true);
			}
			$line->addItem(new PDFItemText($this->style->getTextWidth($part), clone $this->style, $part), $this->style->lineHeight);
	
			$width = $this->style->getTextWidth($text);
			$remain = $line->getRemain($this->content->getWidth());
		}
	
		if($width) $line->addItem(new PDFItemText($width, clone $this->style, $text), $this->style->lineHeight);
	}
	
	public function rectangle($param){
	}
	
	public function image($param){
	}
	
	
	public function endline($height = null){
		$last = $this->content->getLastLine();
		if($last instanceof PDFDefaultLine){
			// Don't break empty lines
			if($last->getRemain($this->content->getWidth())!=$this->content->getWidth()){
				$this->content->append(new PDFDefaultLine());
			}
		}else{
			$this->content->append(null);
		}

		if($height===true){
			$this->content->append(new PDFDefaultLine($this->style->lineHeight));
			
			
			$this->content->append(null);
		}elseif($height!==null && is_numeric($height)){
			$this->content->append(new PDFDefaultLine($height));
			
			$this->content->append(null);
		}
	}
}