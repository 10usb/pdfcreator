<?php

class PDFDOMStylesheetParser {
	private $stylesheet;
	
	/**
	 * 
	 * @param PDFDOMStylesheet $stylesheet
	 */
	public function __construct($stylesheet){
		$this->stylesheet = $stylesheet;
	}
	
	/**
	 * 
	 * @param string $text
	 * @throws Exception
	 */
	public function parse($text){
		$offset = 0;
		$state = 0;
		$selector = null;
		$properties = array();
		while($offset < strlen($text)){
			if($state==0){
				if(preg_match('/\s+/is', substr($text, $offset), $matches)){
					$offset+= strlen($matches[0]);
					if($offset >= strlen($text)) break;
				}
				if(!preg_match('/\s*(.+?)\s*{/is', substr($text, $offset), $matches)) throw new Exception("Invalid selector at '".substr($text, $offset, 20)."'");
				$offset+= strlen($matches[0]);
				
				$selector = $this->getSelector($matches[1]);
				$properties = array();

				$state = 1;
			}else{
				if(!preg_match('/\s+(.+?):\s*((\s*(".+?"|[^"]+?)\s*)(\s*,\s*(".+?"|[^"]+?)\s*)*)((;\s*})|(;|\}))/is', substr($text, $offset), $matches)) throw new Exception("Invalid property at '".substr($text, $offset, 20)."'");
				$offset+= strlen($matches[0]);
				$key = $matches[1];
				
				$selector->setProperty($key, $matches[2]);
				
				if(strpos(end($matches), '}')) $state = 0;
			}
		}
	}
	
	/**
	 * 
	 * @param string $text
	 * @throws Exception
	 * @return PDFDOMStyleselector
	 */
	public function getSelector($text){
		$text = preg_replace('/(\>)\s+/is', '>', $text);
		
		$selector = null;
		foreach(preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY) as $part){
			if(!preg_match('/^(\>?)([^ >]+)$/is', $part, $matches)) throw new Exception("Invalid selector part '$part'");
			
			if($selector == null){
				$child = $this->stylesheet->getSelector($matches[2], $matches[1]=='>');
				if(!$child) $child = $this->stylesheet->addSelector($matches[2], $matches[1]=='>');
			}else{
				$child = $selector->getSelector($matches[2], $matches[1]=='>');
				if(!$child) $child = $selector->addSelector($matches[2], $matches[1]=='>');
			}
			$selector = $child;
		}

		return $selector;
	}
}