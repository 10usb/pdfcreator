<?php
class PDFObject extends PDFWriter {
	private $file;
	private $referance;
	private $offset;
	private $dictionary;
	private $contents;
	private $compression;

	public function __construct($file, $referance, $compression) {
		$this->file			= $file;
		$this->referance	= $referance;
		$this->dictionary	= new PDFDictionary();
		$this->contents		= null;
		$this->compression	= $compression;
		$this->buffer		= null;
	}

	public function setOffset($offset){
		$this->offset = $offset;
	}

	public function getOffset(){
		return $this->offset;
	}

	public function getDictionary(){
		return $this->dictionary;
	}

	public function setContents($contents){
		$this->contents = $contents;
	}

	public function getBuffer(){
		if(!$this->buffer){
			$this->write($this->referance.' 0 obj');
			if($this->contents){
				if($this->compression === null && $this->file->getCompression() == 'optimize'){
					$contents = gzcompress($this->contents);
					if(strlen($contents) < strlen($this->contents)){
						$this->dictionary->set('Filter', new PDFName('FlateDecode'));
					}else{
						$contents = $this->contents;
					}
				}elseif($this->compression){
					$this->dictionary->set('Filter', new PDFName('FlateDecode'));
					$contents = gzcompress($this->contents);
				}else{
					$contents = $this->contents;
				}
				
				$this->dictionary->set('Length', strlen($contents));
				$this->write($this->dictionary);
				$this->write('stream');
				$this->write($contents);
				$this->write('endstream');
			}else{
				$this->write($this->dictionary);
			}
			$this->write('endobj');
		}
		return $this->buffer;
	}
	
	public function __toString(){
		return $this->referance.' 0 R';
	}
}