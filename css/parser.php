<?php

class CSSparser {
	/**
	 * 
	 * @var CSSDocument
	 */
	private $document;
	
	/**
	 * 
	 * @param CSSDocument $document
	 */
	public function __construct($document){
		$this->document	= $document;
	}
	
	public function parse($text){
		$offset = 0;
		$state = 0;
		$ruleset = null;
		while($offset < strlen($text)){
			if($state==0){
				if(preg_match('/^\s+/is', substr($text, $offset), $matches)){
					$offset+= strlen($matches[0]);
					if($offset >= strlen($text)) break;
				}
				if(!preg_match('/\s*(.+?)\s*{/is', substr($text, $offset), $matches)) throw new Exception("Invalid selector at '".substr($text, $offset, 20)."'");
				$offset+= strlen($matches[0]);
				
				$ruleset = new CSSRuleSet($this->parseSelector($matches[1]));
				$this->document->addRuleSet($ruleset);
		
				$state = 1;
			}else{
				if(!preg_match('/\s+(.+?):\s*((\s*(".+?"|[^"]+?)\s*)(\s*,\s*(".+?"|[^"]+?)\s*)*)((;\s*})|(;|\}))/is', substr($text, $offset), $matches)) throw new Exception("Invalid property at '".substr($text, $offset, 20)."'");
				$offset+= strlen($matches[0]);
		
				$ruleset->setProperty($matches[1], $matches[2]);
		
				if(strpos(end($matches), '}')) $state = 0;
			}
		}
	}
	
	public function parseSelector($text){
		$text = preg_replace('/(\>)\s+/is', '>', $text);
		
		$selector	= null;
		$current	= null;
		
		foreach(preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY) as $part){
			if(!preg_match('/^(\>?)([^ >]+)$/is', $part, $matches)) throw new Exception("Invalid selector part '$part'");
			
			$type		= $matches[1] ? $matches[1] : null;
			$tagName	= null;
			$classes	= array();
			$pseudos	= array();
			
			$offset = 0;
			
			$subtext = $matches[2];
			
			while($offset < strlen($subtext)){
				if(!preg_match('/^([\.:]?)([a-z0-1\-]+)/is', substr($subtext, $offset), $matches)) throw new Exception("Invalid property at '".substr($text, $offset, 20)."'");
				
				switch($matches[1]){
					case '.': $classes[] = $matches[2]; break;
					case ':': $pseudos[] = $matches[2]; break;
					default: $tagName =  $matches[2]; break;
				}

				$offset+= strlen($matches[0]);
			}
			
			if($current==null){
				$selector = $current = new CSSSelector($type, $tagName, $classes, $pseudos);
			}else{
				$current = $current->setSelector(new CSSSelector($type, $tagName, $classes, $pseudos));
			}
		}

		return $selector;
	}
}