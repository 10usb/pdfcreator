<?php

class PDFLink {
	private $rectangle;
	private $url;
	private $index;
	private $top;

	public function __construct($rectangle, $url, $index, $top){
		$this->rectangle	= $rectangle;
		$this->url			= $url;
		$this->index		= $index;
		$this->top			= $top;
	}
	
	public function getRactangle(){
		return $this->rectangle;
	}

	public function getUrl(){
		return $this->url;
	}

	public function getIndex(){
		return $this->index;
	}

	public function getTop(){
		return $this->top;
	}
}