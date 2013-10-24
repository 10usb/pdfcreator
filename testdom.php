<?php
include 'lib/pdf.php';
include 'render/render.php';
include 'dom/dom.php';

header('Content-Type: text/plain');

$parser = new PDFDOMParser();
$document = $parser->parse(file_get_contents('doc.xml'));
if(!$document) die($parser->getException()->getMessage());

//print_r($document);

// Transform into a PDF document
$writer = new PDFDOMWriter($document);
$pdfdocument = $writer->create();

// Make file
$file = $pdfdocument->makeFile();

// Output the file
$file->output();