<?php
class PDFCSS {
	public static function init(){
		include self::path('document.php');
		include self::path('ruleset.php');
		include self::path('selector.php');
	}

	public static function path($file){
		return dirname(__FILE__ ).'/'.$file;
	}
}
PDFCSS::init();