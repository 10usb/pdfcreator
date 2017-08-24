<?php
namespace pdfcreator;

use pdflib\File;
use quill\Pen;

class Document {
	/**
	 * 
	 * @var \pdflib\File
	 */
	private $file;
	
	/**
	 * 
	 * @var array
	 */
	private $imports;
	
	/**
	 * 
	 * @param string $filename
	 */
	public function __construct($filename = 'php://temp', $overwrite = true){
		$this->file = new File($filename);
		if(!$overwrite){
			$this->file->load();
		}
		
		$this->clear();
	}
	
	/**
	 * 
	 * @return \pdflib\File
	 */
	public function getFile(){
		return $this->file;
	}
	
	/**
	 * 
	 * @param string $filename
	 */
	public function import($name, $contents = false){
		$import = new \stdClass();
		if($contents){
			$import->name		= $name;
			$import->contents	= $contents;
		}else{
			if(!file_exists($name)) throw new \Exception('File "'.$name.'" not exists');
			if(!is_readable($name)) throw new \Exception('File "'.$name.'" is not readable');
			$import->name	= $name;
			$import->path	= $name;
		}
		
		$this->imports[] = $import;
	}
	
	/**
	 * 
	 * @param boolean $userAgent
	 */
	public function clear($userAgent = true){
		$this->imports = [];
		if($userAgent){
			$import = new \stdClass();
			$import->name	= 'user-agent';
			$import->path	= realpath(__DIR__.'/../data/user-agent.css');
			$this->imports[] = $import;
		}
	}
	
	/**
	 * 
	 * @param string $filename
	 */
	public function load($filename){
		$document = new \quill\Document();
		
		foreach($this->imports as $import){
			$parser = new \csslib\parsers\Parser($document->getStylesheet()->addSegment($import->name));
			if(isset($import->contents)){
				$parser->setSource($import->contents);
			}else{
				$parser->setSource(file_get_contents($import->path));
			}
			$parser->parse();
		}
		
		$parser = new \quill\Parser($document);
		$parser->parse(file_get_contents($filename));
		
		
		$adapter = new Adapter($this->file);
		$adapter->setInfo($document->getInfo());
		
		$pen = new Pen($adapter, $adapter);
		foreach($document->getSections() as $section){
			$pen->write($section);
		}
	}
	
	/**
	 * 
	 * 
	 * @param string $filename
	 */
	public function output($filename){
		header('Content-Type: application/pdf');
		header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
		header('Pragma: public');
		header('Expires: '.date('D, d M Y H:i:s z'));
		header('Content-Disposition: inline; filename="'.$filename.'"');
		echo $this->file->getContents();
		exit;
	}
}