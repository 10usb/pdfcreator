<?php
class PDFDOM {
	public static function init(){
		include self::path('document.php');
		include self::path('info.php');
		include self::path('node.php');
		include self::path('parent.php');
		include self::path('element.php');
		include self::path('section.php');
		include self::path('text.php');
		include self::path('../css/css.php');
		
		include self::path('parser/parser.php');
		include self::path('parser/parserstate.php');
		include self::path('parser/filestate.php');
		include self::path('parser/defaultstate.php');
		include self::path('parser/documentstate.php');
		include self::path('parser/infostate.php');
		include self::path('parser/stylestate.php');
		include self::path('parser/contentstate.php');
		include self::path('parser/sectionstate.php');
		include self::path('parser/elementstate.php');
		
		include self::path('writer.php');
	}

	public static function path($file){
		return dirname(__FILE__ ).'/'.$file;
	}
}
PDFDOM::init();