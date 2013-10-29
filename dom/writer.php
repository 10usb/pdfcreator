<?php
class PDFDOMWriter {
	/**
	 * @var PDFDOMDocument
	 */
	private $domdocument;

	/**
	 * @var PDFDocument
	 */
	private $pdfdocument;

	/**
	 * @var string
	 */
	private $defaultStyle;
	
	/**
	 * 
	 * @param PDFDOMDocument $domdocument
	 */
	public function __construct($domdocument, $defaultStyle = null){
		$this->domdocument = $domdocument;
	}
	
	public function create(){
		$this->pdfdocument = new PDFDocument();
		
		$this->_info($this->domdocument->getInfo());
		
		foreach($this->domdocument->getSections() as $section){
			$this->_section($section);
		}
		
		return $this->pdfdocument;
	}
	
	/**
	 * 
	 * @param PDFDOMInfo $info
	 */
	private function _info($info){
		if($info->title){
			$this->pdfdocument->getInfo()->setTitle($info->title);
		}
		if($info->subject){
			$this->pdfdocument->getInfo()->setSubject($info->subject);
		}
		if($info->author){
			$this->pdfdocument->getInfo()->setAuthor($info->author);
		}
		if($info->keywords){
			$this->pdfdocument->getInfo()->setKeywords($info->keywords);
		}
		if($info->creator){
			$this->pdfdocument->getInfo()->setCreator($info->creator);
		}
	}
	
	/**
	 * 
	 * @param PDFDOMSection $section
	 */
	private function _section($section){
		$this->selector = $selector = $this->getSelector($section);
		$ruleset = $this->domdocument->getStylesheet()->match($this->selector);
		
		$style = new PDFStyle($this->pdfdocument);
		$style->paddingLeft		= $this->getTranslatedStyle($ruleset, 'page-margin-left')->getMeasurement('pt');
		$style->paddingTop		= $this->getTranslatedStyle($ruleset, 'page-margin-top')->getMeasurement('pt');
		$style->paddingRight	= $this->getTranslatedStyle($ruleset, 'page-margin-right')->getMeasurement('pt');
		$style->paddingBottom	= $this->getTranslatedStyle($ruleset, 'page-margin-bottom')->getMeasurement('pt');
		$style->height			= $this->pdfdocument->getPages()->getSize()->getHeight();

		$body = new PDFCell($this->pdfdocument, $this->pdfdocument->getPages()->getSize()->getWidth(), $style);
		
		$this->_content($body->getContent(), $section->getChildrenByTagName('body', true), $selector, $ruleset);
		
		$this->_flush(false);

		
		// Slice the body into pages
		while($slice = $body->slice($this->pdfdocument->getPages()->getSize()->getHeight(), false)){
			$page = $this->pdfdocument->getPages()->addPage();
			$target = new PDFTarget($this->pdfdocument, $page);
			$slice->render($target, new PDFPoint(0, 0));
		}
		$page = $this->pdfdocument->getPages()->addPage();
		$target = new PDFTarget($this->pdfdocument, $page);
		$body->render($target, new PDFPoint(0, 0));
	}
	
	/**
	 * 
	 * @param PDFContent $content
	 * @param PDFDOMElement $body
	 * @param CSSSelector $selector
	 * @param CSSRuleSet $ruleset
	 */
	private function _content($content, $body, $selector, $ruleset){
		$writer = $content->getWriter(true);
		$writer->getStyle()->setFont($this->getTranslatedStyle($ruleset, 'font-family')->getString(), $this->getTranslatedStyle($ruleset, 'font-size')->getMeasurement('pt'));
		$writer->getStyle()->setColor($this->getTranslatedStyle($ruleset, 'font-color')->getRed(), $this->getTranslatedStyle($ruleset, 'font-color')->getGreen(), $this->getTranslatedStyle($ruleset, 'font-color')->getBlue());
		$writer->getStyle()->setLineHeight($this->getTranslatedStyle($ruleset, 'line-height')->getMeasurement('pt'));
		
		$this->bufferText = null;
		$this->bufferDisplay = null;

		foreach($body->getChildren() as $child){
			if($child instanceof PDFDOMText){
				$this->_flush(false);
				$this->bufferWriter = $writer;
				$this->bufferText = $child->getText();
				
			}else{
				$currentSelector = $selector->setSelector($this->getSelector($child));
				$currentRuleset = $this->domdocument->getStylesheet()->match($this->selector);
				$currentRuleset->setParent($ruleset);
				
				switch($currentRuleset->getProperty('display')){
					case 'block':
						$this->_flush(true);
						$this->_block($content, $child, $currentSelector, $currentRuleset);
					break;
					case 'inline':
						$this->_flush(false);
						$this->_content($content, $child, $currentSelector, $currentRuleset);
					break;
					default:
						$writer->text("$child");
					break;
				}
				$this->bufferDisplay = $currentRuleset->getProperty('display');
			}
		}
	}
	
	/**
	 * 
	 * @param PDFContentWriter $writer
	 * @param PDFDOMElement $body
	 */
	private function _flush($block){
		if($block){
			if($this->bufferText!==null){
				if($this->bufferDisplay=='block' || $this->bufferDisplay==null){
					if(trim($this->bufferText)){
						$this->bufferWriter->text($this->bufferText);
					}
				}else{
					$this->bufferWriter->text($this->bufferText);
				}
			}
		}else{
			if($this->bufferText!==null){
				$this->bufferWriter->text($this->bufferText);
			}
		}
		$this->bufferText = null;
		$this->bufferWriter = null;
	}
	
	/**
	 * 
	 * @param PDFContentWriter $writer
	 * @param PDFDOMElement $body
	 */
	private function _block($content, $element, $selector, $ruleset){
		$style = new PDFStyle($this->pdfdocument);
		//$style->borderColor		= new PDFColor(0, 0, 0);
		//$style->backgroundColor	= new PDFColor(223, 223, 223);
		$style->paddingLeft		= $this->getTranslatedStyle($ruleset, 'padding-left')->getMeasurement('pt');
		$style->paddingTop		= $this->getTranslatedStyle($ruleset, 'padding-top')->getMeasurement('pt');
		$style->paddingRight	= $this->getTranslatedStyle($ruleset, 'padding-right')->getMeasurement('pt');
		$style->paddingBottom	= $this->getTranslatedStyle($ruleset, 'padding-bottom')->getMeasurement('pt');

		$body = new PDFCell($this->pdfdocument, $content->getWidth(), $style);
		$this->_content($body->getContent(), $element, $selector, $ruleset);
		$this->_flush(true);
		$content->append($body);
	}
	
	/**
	 * 
	 * @param PDFDOMElement $element
	 * @return CSSSelector
	 */
	private function getSelector($element){
		return new CSSSelector('>', $element->getTagName(), $element->getClasses(), null);
	}
	
	/**
	 * 
	 * @param CSSRuleSet $ruleset
	 * @param string $property
	 * @return CSSValue
	 * @throws Exception
	 */
	private function getTranslatedStyle($ruleset, $key){
		$property = $ruleset->getProperty($key);
		if($property){
			if($property->getCount()==1) return $property->getValue(0);
			throw new Exception("Invalid property count for '$key'");
		}
		
		if(preg_match('/^(.+)-(left|top|right|bottom)$/', $key, $matches)){
			return $this->getTranslatedStyleLTRB($ruleset, $matches[1], $matches[2]);
		}else{
			switch($key){
			}
		}
		throw new Exception("Unknow property '$key'");
	}
	
	/**
	 * 
	 * @param CSSRuleSet $ruleset
	 * @param string $property
	 * @return CSSValue
	 * @throws Exception
	 */
	private function getTranslatedStyleLTRB($ruleset, $key, $side){
		$property = $ruleset->getProperty($key);
		if(!$property) throw new Exception("Unknow property '$key'");
		
		switch($property->getCount()){
			case 1: return $property->getValue(0);
			case 2: switch($side){
				case 'top': case 'bottom': return $property->getValue(0);
				case 'left': case 'right': return $property->getValue(1);
			}
			case 3: switch($side){
				case 'top': return $property->getValue(0);
				case 'right': return $property->getValue(1);
				case 'bottom': return $property->getValue(2);
				case 'left':  return new CSSValue('0pt');
			}
			case 4: switch($side){
				case 'top': return $property->getValue(0);
				case 'right': return $property->getValue(1);
				case 'bottom': return $property->getValue(2);
				case 'left':  return $property->getValue(3);
			}
			
		}
		throw new Exception("Invalid property count for '$key'");
	}
}
