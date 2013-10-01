<?php
class PDFLibrary {
	public static function init(){
		include self::path('datatypes.php');
		include self::path('writer.php');
		include self::path('file.php');
		include self::path('object.php');
		include self::path('info.php');
		include self::path('link.php');
		include self::path('page.php');
		include self::path('pages.php');
		include self::path('font.php');
		include self::path('xobject.php');
		include self::path('image.php');
		include self::path('resources.php');
		include self::path('document.php');
	}

	public static function path($file){
		return dirname(__FILE__ ).'/'.$file;
	}
}
PDFLibrary::init();