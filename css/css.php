<?php
class PDFCSS {
	public static function init(){
		include self::path('document.php');
		include self::path('ruleset.php');
		include self::path('selector.php');
		include self::path('specificity.php');
		include self::path('property.php');
		include self::path('value.php');
		include self::path('parser.php');
	}

	public static function path($file){
		return dirname(__FILE__ ).'/'.$file;
	}
}
PDFCSS::init();