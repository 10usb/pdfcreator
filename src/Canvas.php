<?php
namespace pdfcreator;

class Canvas implements \alf\Canvas {
	private $page;
	private $canvas;
	
	private $state;
	
	/**
	 * 
	 * @param \pdflib\structure\Page $page
	 */
	public function __construct($page){
		$this->page		= $page;
		$this->canvas	= $page->getCanvas();
		
		$this->state = new \stdClass();
	}
	
	public function setFillColor($color){
		if(preg_match('/^#([a-z0-9]{2})([a-z0-9]{2})([a-z0-9]{2})$/i', $color, $matches)){
			$this->canvas->setFillColor(hexdec($matches[1]), hexdec($matches[2]), hexdec($matches[3]));
		}else{
			throw new \Exception('Failed to parse color');
		}
	}
	
	public function setStrokeColor($color){
		if(preg_match('/^#([a-z0-9]{2})([a-z0-9]{2})([a-z0-9]{2})$/i', $color, $matches)){
			$this->canvas->setStrokeColor(hexdec($matches[1]), hexdec($matches[2]), hexdec($matches[3]));
		}else{
			throw new \Exception('Failed to parse color');
		}
	}
	
	public function setFont($name, $size, $italic = false, $bold = false){
		$this->state->font = (object)['name'=>$name, 'size'=>$size];
		$this->canvas->setFont($this->page->getFont($name, $size));
	}
	
	public function setLineWidth($width){
		$this->canvas->setLineWidth($width);
	}
	
	public function setLineCap($style){
		$this->canvas->setLineCap($style);
	}
	
	public function setLineJoin($style){
		$this->canvas->setLineJoin($style);
	}
	
	public function setLineDash($segments, $offset = 0){
		$this->canvas->setLineDash($segments[0], $segments[1], $offset);
	}
	
	public function save(){
		$this->canvas->save();
	}
	
	public function restore(){
		$this->canvas->restore();
	}
	
	public function fillRect($x, $y, $width, $height){
		$this->canvas->rectangle($x, $y, $width, $height);
	}
	
	public function strokeRect($x, $y, $width, $height){
		$this->canvas->rectangle($x, $y, $width, $height, false, true);
	}
	
	public function fillText($text, $x, $y){
		$this->canvas->text($x, $y, $text);
	}
	
	public function strokeText($text, $x, $y){
		
	}
	
	public function measureText($text){
		
	}
	
	public function drawImage($image, $dx, $dy, $dWidth, $dHeight){
		$this->canvas->image($dx, $dy, $dWidth, $dHeight, $this->page->getImage($image));
	}
	
	public function moveTo($left, $top){
		$this->canvas->moveTo($left, $top);
	}
	
	public function lineTo($left, $top){
		$this->canvas->lineTo($left, $top);
	}
	
	public function closePath(){
		$this->canvas->closePath();
	}
	
	public function fill(){
		$this->canvas->fill();
	}
	
	public function stroke(){
		$this->canvas->stroke();
	}
	
	public function clip(){
		$this->canvas->clip();
	}
	
	public function rotate($angle){
		
	}
	
	public function scale($x, $y){
		
	}
	
	public function translate($x, $y){
		
	}
	
	public function transform($hscale, $hskew, $vskew, $vscale, $dx, $dy){
		
	}
}