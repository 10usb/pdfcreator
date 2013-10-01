<?php
class PDFImage extends PDFXObject {
	private $name;
	private $width;
	private $height;
	private $colorSpace;
	private $bits;
	private $isMask;
	private $data;
	private $mask;

	public function __construct($name){
		$this->name			= $name;
		$this->width		= 0;
		$this->height		= 0;
		$this->colorSpace	= null;
		$this->bits			= null;
		$this->isMask		= false;
		$this->data			= null;
		$this->mask			= null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PDFXObject::getName()
	 */
	public function getName(){
		return $this->name;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PDFXObject::append()
	 */
	public function append($file){
		$object = $file->getObject($this->filter===true);
		$object->getDictionary()->set('Type', new PDFName('XObject'));
		$object->getDictionary()->set('Subtype', new PDFName('Image'));
		$object->getDictionary()->set('Width', $this->width);
		$object->getDictionary()->set('Height', $this->height);

		if($this->colorSpace)	$object->getDictionary()->set('ColorSpace', $this->colorSpace);
		if($this->bits)			$object->getDictionary()->set('BitsPerComponent', $this->bits);
		if($this->filter && $this->filter!==true){
			$object->getDictionary()->set('Filter', $this->filter);
		}
		if($this->mask)			$object->getDictionary()->set('SMask', $this->mask->append($file));
		
		$object->setContents($this->data);
		
		return $object;
	}
	
	public function load($fileame){
		$info = getimagesize($fileame);
		$this->width	= $info[0];
		$this->height	= $info[1];

		switch($info[2]){
			case IMAGETYPE_JPEG: $this->loadJPG($fileame, $info); break;
			case IMAGETYPE_PNG: $this->loadPNG($fileame, $info); break;
			default: throw new Exception('Image image format');
		}
	}
	
	public function loadJPG($filename, $info){
		switch($info['channels']){
			case 3: $this->colorSpace = new PDFName('DeviceRGB'); break;
			case 4: $this->colorSpace = new PDFName('DeviceCMYK'); break;
			case 1: $this->colorSpace = new PDFName('DeviceGray'); break;
			default: throw new Exception('Image image format');
		}
		$this->bits		= isset($info['bits']) ? $info['bits'] : 8;
		$this->filter	= new PDFName('DCTDecode');
		$this->data		= file_get_contents($filename);
	}

	public function loadPNG($filename, $info){
		$image = imagecreatefrompng($filename);
		
		$this->colorSpace 	= new PDFName('DeviceRGB');
		$this->bits			= 8;
		$this->filter		= new PDFName('DCTDecode');

		ob_start();
		imagejpeg($image);
		$this->data = ob_get_clean();
		
		$mask = '';

		for($y=0; $y<$this->height; $y++){
			for($x=0; $x<$this->width; $x++){
				$pixel = imagecolorat($image, $x, $y);
				$value = round((127 - (($pixel >> 24) & 0xFF)) / 127 * 255);
				$mask.= pack('C', $value);
			}
		}
		
		$this->mask = new PDFImage(null);
		$this->mask->width		= $this->width;
		$this->mask->height		= $this->height;
		$this->mask->colorSpace = new PDFName('DeviceGray');
		$this->mask->bits		= 8;
		$this->mask->filter		= true;
		$this->mask->data		= $mask;
	}
}