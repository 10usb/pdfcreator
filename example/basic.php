<?php
use pdfcreator\Document;

header('Content-Type: text/plain');

require_once '../autoloader.php';

$document = new Document();
$document->import('book.css');
$document->load('book.xml');
echo $document->output('pdfcreator.pdf');