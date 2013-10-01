<?php
class PDFDocument {
	private $info;
	private $pages;
	private $resources;
	private $zoom;
	private $layout;
	
	public function __construct(){
		$this->info = new PDFInfo();
		$this->pages = new PDFPages();
		$this->resources = new PDFResources();

		$this->zoom = 'real';
		$this->layout = 'continuous';
	}
	
	public function getInfo(){
		return $this->info;
	}
	
	public function getPages(){
		return $this->pages;
	}
	
	public function getResources(){
		return $this->resources;
	}
	
	public function makeFile(){
		// Create file object
		$file = new PDFFile();
		
		// Initialize the page tree
		$pageRoot = $this->pages->init($file);
		
		// Initialize the resources
		$resources = $this->resources->init($file);
		
		// Append pages to file
		$pages = $this->pages->append($file, $resources);
		
		// Append resources to fike
		$this->resources->append($file);

		// Some extra info
		$this->info->append($file);
		
		// Setup the first index
		$catalog = $file->getObject();
		$catalog->getDictionary()->set('Type', new PDFName('Catalog'));
		$catalog->getDictionary()->set('Pages', $pageRoot);
		
		$openAction = new PDFArray();
		$openAction->push($pages->get(0));
		switch($this->zoom){
			case 'fit':
				$openAction->push(new PDFName('Fit'));
			break;
			case 'wide':
				$openAction->push(new PDFName('FitH'));
				$openAction->push('null');
			break;
			case 'real':
				$openAction->push(new PDFName('XYZ'));
				$openAction->push('null');
				$openAction->push('null');
				$openAction->push(1);
			break;
			default:
				$openAction->push(new PDFName('XYZ'));
				$openAction->push('null');
				$openAction->push('null');
				$openAction->push(is_numeric($this->zoom) ? sprintf('%.2F', $this->zoom / 100) : 1);
			break;
		}
		$catalog->getDictionary()->set('OpenAction', $openAction);
		
		switch($this->layout){
			case 'single':
				$catalog->getDictionary()->set('PageLayout', new PDFName('SinglePage'));
			break;
			case 'twocolumns':
				$catalog->getDictionary()->set('PageLayout', new PDFName('TwoColumnLeft'));
				break;
			case 'continuous':
			default:
				$catalog->getDictionary()->set('PageLayout', new PDFName('OneColumn'));
			break;
		}
		
		$file->setRoot($catalog);
		
		// Return file
		return $file;
	}
}