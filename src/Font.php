<?php
namespace pdfcreator;

class Font implements \alf\Font {
	/**
	 * 
	 * @var \pdflib\structure\Font
	 */
	private $font;
	
	/**
	 * 
	 * @param \pdflib\structure\Font $font
	 */
	public function __construct($font){
		$this->font = $font;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \alf\Font::getName()
	 */
	public function getName(){
		return $this->font->getName();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \alf\Font::getSize()
	 */
	public function getSize(){
		return $this->font->getSize();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \alf\Font::getTextWith()
	 */
	public function getTextWith($text){
		return $this->font->getTextWith($text);
	}
}