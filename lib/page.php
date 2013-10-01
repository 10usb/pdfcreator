<?php

class PDFPage extends PDFWriter {
	private $root;
	private $size;
	private $font;
	private $fontSize;
	private $lineWidth;
	private $links;
	private $header;

	public function __construct($root, $size){
		$this->root		= $root;
		$this->size		= $size;
		$this->fontSize	= 16;
		$this->links	= array();
		
		$this->header = null;

		$this->write('2 J');
		$this->setLineWidth(1);
	}
	
	public function getSize(){
		return $this->size ? $this->size : $this->root->getSize();
	}
	
	public function getLinks(){
		return $this->links;
	}

	public function addLink($x, $y, $w, $h, $url, $index = 0, $top = 0){
		$rectangle = new PDFRectangle($x, $this->getSize()->getHeight() - $y, $w, -$h);
		$this->links[] = new PDFLink($rectangle, $url, $index, $top);
	}
	
	public function getBuffer(){
		return $this->buffer;
	}
	
	public function setColor($r, $g = null, $b = null){
		if($r instanceof PDFColor){
			$b = $r->getBlue();
			$g = $r->getGreen();
			$r = $r->getRed();
		}
		$this->write(sprintf('%.3F %.3F %.3F rg',$r/255,$g/255,$b/255));
	}
	
	public function setLineColor($r, $g = null, $b = null){
		if($r instanceof PDFColor){
			$b = $r->getBlue();
			$g = $r->getGreen();
			$r = $r->getRed();
		}
		$this->write(sprintf('%.3F %.3F %.3F RG',$r/255,$g/255,$b/255));
	}

	public function setLineWidth($width){
		$this->lineWidth = $width;
		$this->write(sprintf('%.2F w', $width));
	}

	public function getFont(){
		return $this->font;
	}
	
	public function setFont($font, $size){
		$this->font		= $font;
		$this->fontSize	= $size;
	
		$this->write(sprintf('BT /%s %.2F Tf ET', $font->getKey(), $size));
	}

	public function getTextWidth($str){
		return $this->font->getTextWidth($str) * $this->fontSize;
	}
	
	public function text($x, $y, $str){
		$this->write(sprintf('BT %.2F %.2F Td %s Tj ET', $x, $this->getSize()->getHeight() - ($y + $this->fontSize * 0.84), new PDFText($str)));
	}
	
	public function line($x1, $y1, $x2, $y2){
		$this->write(sprintf('%.2F %.2F m %.2F %.2F l S', $x1, $this->getSize()->getHeight() - $y1, $x2,$this->getSize()->getHeight() - $y2));
	}

	public function rectangle($x, $y, $w, $h, $filled=true, $border = false){
		if($filled && $border){
			$this->write(sprintf('%.2F %.2F %.2F %.2F re B',$x, $this->getSize()->getHeight() - $y, $w, -$h));
		}elseif($filled){
			$this->write(sprintf('%.2F %.2F %.2F %.2F re f',$x, $this->getSize()->getHeight() - $y, $w, -$h));
		}elseif($border){
			$this->write(sprintf('%.2F %.2F %.2F %.2F re S',$x, $this->getSize()->getHeight() - $y, $w, -$h));
		}
	}
	
	public function image($image, $x, $y, $w, $h){
		$this->write(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm %s Do Q', $w, $h, $x, $this->getSize()->getHeight() - ($y + $h), new PDFName($image->getName())));
	}
	
	public function init($file){
		$this->header = $file->getObject();
	}
	
	public function getHeader(){
		return $this->header;
	}
}