<?php
abstract class PDFDOMNode {
	protected $parent;
	
	public function __construct(){
		$this->parent	= null;
	}
}