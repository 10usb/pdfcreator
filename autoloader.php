<?php
namespace pdfcreator;

/**
 * It likes to load things
 * @author 10usb
 */
class Autoloader {
	/**
	 * Registeres the autoloader
	 */
	public static function register() {
		spl_autoload_register('pdfcreator\\Autoloader::load');
	}
	
	/**
	 * Load a class or interface by its name, ain't that cool?
	 * @param string $name	Class name
	 * @throws \Exception
	 * @return boolean
	 */
	public static function load($name){
		if(substr($name, 0, 11)!='pdfcreator\\') return false;
		
		$filename =  __DIR__.'/src/'.implode('/', array_slice(explode('\\', $name), 1)).'.php';
		
		if(!file_exists($filename)) throw new \Exception('File "'.$filename.'" not found');
		
		require_once $filename;
		
		if(class_exists($name) || interface_exists($name)) return true;
		
		
		throw new \Exception('Class or Interface "'.$name.'" not exists');
	}
}

// Lets kick some ass
Autoloader::register();