<?php

class PDFResources {
	private $dictionary;

	private $fonts;
	private $xobjects;
	
	private static $fontCache;
	
	
	public function __construct(){
		$this->dictionary = null;
		
		$this->fonts = array();
		$this->xobjects = array();
		
		self::$fontCache = json_decode(file_get_contents(PDFLibrary::path('fonts.json')));
	}

	public function loadFont($name){
		foreach(self::$fontCache as $font){
			if($font->name == $name){
				$key = 'F'.(count($this->fonts) + 1);
				$font = new PDFFont($key, $font->name, true, $font->widths);
				$this->fonts[] = $font;
				return $font;
			}
		}
		return null;
	}
	
	public function getFont($name){
		foreach($this->fonts as $font){
			if($font->getName()==$name) return $font;
		}
		return $this->loadFont($name);
	}
	
	public function loadImage($filename){
		$xobject = new PDFImage('I'.(count($this->xobjects) + 1));
		$xobject->load($filename);
		$this->xobjects[] = $xobject;
		return $xobject;
	}
	
	public function init($file){
		return $this->dictionary = $file->getObject();
	}
	
	public function append($file){
		$fonts = new PDFDictionary();
		
		foreach($this->fonts as $font){
			if($font->isStandaard()){
				// Add font
				$object = $file->getObject();
				$object->getDictionary()->set('Type', new PDFName('Font'));
				$object->getDictionary()->set('BaseFont', new PDFName($font->getName()));
				$object->getDictionary()->set('Subtype', new PDFName('Type1'));
				$object->getDictionary()->set('Encoding', new PDFName('WinAnsiEncoding'));
				$fonts->set($font->getKey(), $object);
			}
		}
		
		$xobjects = new PDFDictionary();
		foreach($this->xobjects as $xobject){
			$object = $xobject->append($file);
			$xobjects->set($xobject->getName(), $object);
		}
		
		// Set font resources
		$this->dictionary->getDictionary()->set('ProcSet', '[/PDF /Text /ImageB /ImageC /ImageI]');
		$this->dictionary->getDictionary()->set('Font', $fonts);
		$this->dictionary->getDictionary()->set('XObject', $xobjects);
	}
}
