<?php
class PDFFile extends PDFWriter {
	private $objects;
	private $root;
	private $info;
	private $compression;

	public function __construct() {
		$this->objects		= array();
		$this->root			= null;
		$this->info			= null;
		$this->compression	= 'optimize';
	}

	public function getObject($compression = null){
		$object = new PDFObject($this, count($this->objects) + 1, $compression);
		$this->objects[] = $object;
		return $object;
	}

	public function setCompression($compression){
		$this->compression = $compression;
	}

	public function getCompression(){
		return $this->compression;
	}

	public function setRoot($root){
		$this->root = $root;
	}

	public function setInfo($info){
		$this->info = $info;
	}

	public function output($return='doc.pdf') {
		$this->write('%PDF-1.4');

		foreach($this->objects as $object){
			$object->setOffset($this->getSize());
			$this->write($object->getBuffer());
		}

		// Cross-ref
		$this->startXref = $this->getSize();
		$this->write('xref');
		$this->write('0 '.(count($this->objects) + 1));
		$this->write('0000000000 65535 f ');
		foreach($this->objects as $object){
			$this->write(sprintf('%010d 00000 n ', $object->getOffset()));
		}

		// Trailer
		$this->write('trailer');
		$trailer = new PDFDictionary();
		$trailer->set('Size', count($this->objects) + 1);
		$trailer->set('Root', $this->root);
		if($this->info){
			$trailer->set('Info', $this->info);
		}
		$this->write($trailer);

		$this->write('startxref');
		$this->write($this->startXref);
		$this->write('%%EOF');

		if($return===true) return $this->buffer;

		if(headers_sent() || ob_get_length()) exit;

		header('Content-Type: application/pdf');
		header('Content-Disposition: inline; filename="'.$return.'"');
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		echo $this->buffer;
		exit;
	}
}