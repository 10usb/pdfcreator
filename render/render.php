<?php
class PDFRender {
	public static function init(){
		include self::path('style.php');
		include self::path('content.php');
		include self::path('block.php');
		include self::path('contentwriter.php');
		include self::path('defaultline.php');
		include self::path('item.php');
		include self::path('target.php');
		include self::path('text.php');
		
		include self::path('cell.php');
	}

	public static function path($file){
		return dirname(__FILE__ ).'/'.$file;
	}
}
PDFRender::init();