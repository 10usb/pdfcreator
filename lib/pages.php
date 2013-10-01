<?php

class PDFPages {
	private $pages;
	private $root;
	private $size;

	public function __construct(){
		$this->pages = array();
		$this->root = null;
		$this->size = new PDFSize(595.28, 841.89);
		
	}
	
	public function getPage($index){
		if(isset($this->pages[$index])) return $this->pages[$index];
		return null;
	}
	
	public function addPage($size = null){
		$page = new PDFPage($this, $size);
		$this->pages[] = $page;
		return $page;
	}
	
	public function setSize($width, $height){
		$this->size = new PDFSize($width, $height);
	}
	
	public function getSize(){
		return $this->size;
	}
	
	public function init($file){
		return $this->root = $file->getObject();
	}
	
	public function append($file, $resources){
		$pages = new PDFArray();
		
		foreach($this->pages as $page){
			$page->init($file);
		}

		foreach($this->pages as $page){
			$header = $page->getHeader();
			$contents = $file->getObject();
			
			$header->getDictionary()->set('Type', new PDFName('Page'));
			$header->getDictionary()->set('Parent', $this->root);
			$header->getDictionary()->set('Resources', $resources);
			$header->getDictionary()->set('Contents', $contents);
			
			if(!$page->getSize()->isEqual($this->size)){
				$header->getDictionary()->set('MediaBox', $page->getSize()->toRactangle()->toArray());
			}
			
			$links = $page->getLinks();
			if($links){
				$annots = new PDFArray();

				foreach($links as $link){
					$annot = new PDFDictionary();
					$annot->set('Type', new PDFName('Annot'));
					$annot->set('Subtype', new PDFName('Link'));
					$annot->set('Rect', $link->getRactangle()->toArray(true));
					$annot->set('Border', new PDFArray(array(0, 0, 0)));
					
					if($link->getUrl()){
						$uri = new PDFDictionary();
						$uri->set('S', new PDFName('URI'));
						$uri->set('URI', new PDFText($link->getUrl()));
						$annot->set('A', $uri);
					}else{
						$other = $this->pages[$link->getIndex()]; 

						$destination = new PDFArray();
						$destination->push($other->getHeader());
						$destination->push(new PDFName('XYZ'));
						$destination->push(0);
						$destination->push(sprintf('%.2F', $other->getSize()->getHeight() - $link->getTop()));
						$destination->push('null');
						$annot->set('Dest', $destination);
					}
					$annots->push($annot);
				}
				$header->getDictionary()->set('Annots', $annots);
			}
			
			
			$pages->push($header);
			
			$contents->setContents($page->getBuffer());
		}
		
		// Register al pages
		$this->root->getDictionary()->set('Type', new PDFName('Pages'));
		$this->root->getDictionary()->set('Kids', $pages);
		$this->root->getDictionary()->set('Count', $pages->size());
		$this->root->getDictionary()->set('MediaBox', $this->size->toRactangle()->toArray());
		
		return $pages;
	}
}
