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
	 * @var CSSPath
	 */
	private $path;
	
	/**
	 * 
	 * @param PDFDOMDocument $domdocument
	 */
	public function __construct($domdocument, $defaultStyle = null){
		$this->domdocument = $domdocument;
	}
	
	/**
	 * 
	 * @return PDFDocument
	 */
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
		$this->path = new CSSPath($this->domdocument->getStylesheet(), new PDFDOMCSSTranslator());
		$this->path->push($section->getTagName(), $section->getClasses(), null);
		
		$style = new PDFStyle($this->pdfdocument);
		$style->paddingLeft		= $this->path->getValue('page-margin-left');
		$style->paddingTop		= $this->path->getValue('page-margin-top');
		$style->paddingRight	= $this->path->getValue('page-margin-right');
		$style->paddingBottom	= $this->path->getValue('page-margin-bottom');
		$style->height			= $this->pdfdocument->getPages()->getSize()->getHeight();

		$body = new PDFCell($this->pdfdocument, $this->pdfdocument->getPages()->getSize()->getWidth(), $style);
		
		$this->_content($body->getContent(), $section->getChildrenByTagName('body', true));
		
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
	 */
	private function _content($content, $body){
		$writer = $content->getWriter(true);
		$writer->getStyle()->setFont($this->path->getValue('-pdf-font-family'), $this->path->getValue('font-size'));
		$writer->getStyle()->setColor($this->path->getValue('font-color'));
		$writer->getStyle()->setLineHeight($this->path->getValue('line-height'));
		
		$this->bufferText = null;
		$this->bufferDisplay = null;

		foreach($body->getChildren() as $child){
			if($child instanceof PDFDOMText){
				$this->_flush(false);
				$this->bufferWriter = $writer;
				$this->bufferText = $child->getText();
				
			}else{
				$this->path->push($child->getTagName(), $child->getClasses(), null);
				
				switch($this->path->getValue('display')){
					case 'block':
						$this->_flush(true);
						$this->_block($content, $child);
					break;
					case 'inline':
						$this->_flush(false);
						$this->_content($content, $child);
					break;
					default:
						$writer->text("ERROR: $child");
					break;
				}
				$this->bufferDisplay = $this->path->getValue('display');
				
				$this->path->pop();
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
	private function _block($content, $element){
		$style = new PDFStyle($this->pdfdocument);
		$style->borderColor		= $this->path->getValue('border-color');
		$style->borderWidth		= $this->path->getValue('border-width');
		$style->backgroundColor	= $this->path->getValue('background-color');
		$style->paddingLeft		= $this->path->getValue('padding-left');
		$style->paddingTop		= $this->path->getValue('padding-top');
		$style->paddingRight	= $this->path->getValue('padding-right');
		$style->paddingBottom	= $this->path->getValue('padding-bottom');

		$body = new PDFCell($this->pdfdocument, $content->getWidth(), $style);
		$this->_content($body->getContent(), $element);
		$this->_flush(true);
		$content->append($body);
	}
}
