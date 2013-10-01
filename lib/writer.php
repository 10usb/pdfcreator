<?php
class PDFWriter {
	protected $buffer;

	protected function __construct(){
		$this->buffer = '';
	}

	protected function clear(){
		$this->buffer = '';
	}

	protected function write($value){
		$this->buffer.= $value."\n";
	}

	public function getSize(){
		return strlen($this->buffer);
	}
}