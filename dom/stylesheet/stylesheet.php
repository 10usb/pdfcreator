<?php
class PDFDOMStylesheet {
	private $selectors;

	public function __construct(){
		$this->selectors = array();
	}
	
	/**
	 * Add a child selector
	 * @param string $part
	 * @param string $direct
	 * @return PDFDOMStyleselector
	 */
	public function addSelector($part, $direct){
		$selector = new PDFDOMStyleselector(null, $part, $direct);
		$this->selectors[] = $selector;
		return $selector;
	}
	
	/**
	 * Returns a child selector
	 * @param string $part
	 * @param bool $directt
	 * @return PDFDOMStyleselector
	 */
	public function getSelector($part, $direct){
		foreach($this->selectors as $selector){
			if($selector->isDirect()==$direct && $selector->getPart()==$part){
				return $selector;
				break;
			}
		}
		return null;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString(){
		$css = '';
		foreach($this->selectors as $selector){
			foreach($selector->getSelectors() as $selector){
				$css.= $selector->toCSS()."\n\n";
			}
		}
		return $css;
	}
}