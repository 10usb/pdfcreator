<?php
namespace pdfcreator;

use quill\Repository;
use quill\Book;

class Adapter implements Repository, Book {
	/**
	 * 
	 * @var \pdflib\File
	 */
	private $file;
	
	/**
	 * 
	 * @var \pdflib\structure\Catalog
	 */
	private $catalog;
	
	/**
	 * 
	 * @var number $width
	 * @var number $height
	 */
	private $width, $height;
	
	/**
	 * 
	 * @param \pdflib\File $file
	 */
	public function __construct($file){
		$this->file 	= $file;
		$this->catalog	= $file->getCatalog();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \quill\Repository::getFont()
	 */
	public function getFont($name, $size, $italic = false, $bold = false){
		try {
			switch(strtolower($name)){
				case 'times roman':
					if($italic && $bold){
						$name = 'Times-BoldItalic';
					}elseif($italic){
						$name = 'Times-Italic';
					}elseif($bold){
						$name = 'Times-Bold';
					}else{
						$name = 'Times-Roman';
					}
				break;
				case 'helvetica':
					if($italic && $bold){
						$name = 'Helvetica-BoldOblique';
					}elseif($italic){
						$name = 'Helvetica-Oblique';
					}elseif($bold){
						$name = 'Helvetica-Bold';
					}else{
						$name = 'Helvetica';
					}
				break;
				case 'courier':
					if($italic && $bold){
						$name = 'Courier-BoldOblique';
					}elseif($italic){
						$name = 'Courier-Oblique';
					}elseif($bold){
						$name = 'Courier-Bold';
					}else{
						$name = 'Courier';
					}
				break;
				case 'symbol':
					$name = 'Symbol';
				break;
				case 'zapfdingbats':
					$name = 'ZapfDingbats';
				break;
			}
			return new Font($this->catalog->getFont($name, $size));
		}catch (\Exception $ex){
			return new Font($this->catalog->getFont('Helvetica', $size));
		}
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \quill\Book::setInfo()
	 */
	public function setInfo($info){
		$information = $this->file->getInformation();
		
		if($info->get('title')) $information->setTitle($info->get('title'));
		if($info->get('subject')) $information->setSubject($info->get('subject'));
		if($info->get('author')) $information->setAuthor($info->get('author'));
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \quill\Book::hasSize()
	 */
	public function hasSize(){
		return $this->catalog->getSize($this->width, $this->height);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \quill\Book::setSize()
	 */
	public function setSize($width, $height){
		$this->catalog->setSize($width, $height);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \quill\Book::addPage()
	 */
	public function addPage($width = false, $height = false){
		$page = $this->catalog->addPage();
		if($width != $this->width || $height != $this->height){
			$page->setSize($width, $height);
		}
		
		return new Canvas($page);
	}
}