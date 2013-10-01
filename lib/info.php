<?php

class PDFInfo {
	private $title;
	private $subject;
	private $author;
	private $keywords;
	private $creator;

	public function __construct(){
		$this->title	= null;
		$this->subject	= null;
		$this->author	= null;
		$this->keywords	= null;
		$this->creator	= null;
	}

	public function setTitle($value){
		$this->title	= $value;
	}

	public function setSubject($value){
		$this->subject	= $value;
	}

	public function setAuthor($value){
		$this->author	= $value;
	}

	public function setKeywords($value){
		$this->keywords	= $value;
	}

	public function setCreator($value){
		$this->creator	= $value;
	}
	
	public function append($file){
		$info = $file->getObject();
		$info->getDictionary()->set('Producer', new PDFText('OPDF 1.0'));
		
		if($this->title)	$info->getDictionary()->set('Title', new PDFText($this->title));
		if($this->subject)	$info->getDictionary()->set('Subject', new PDFText($this->subject));
		if($this->author)	$info->getDictionary()->set('Author', new PDFText($this->author));
		if($this->keywords)	$info->getDictionary()->set('Keywords', new PDFText($this->keywords));
		if($this->creator)	$info->getDictionary()->set('Creator', new PDFText($this->creator));
		
		
		$info->getDictionary()->set('CreationDate', new PDFText('D:'.date('YmdHis')));
		$file->setInfo($info);
	}
}