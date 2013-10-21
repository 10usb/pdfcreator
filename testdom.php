<?php
include 'lib/pdf.php';
include 'render/render.php';
include 'dom/dom.php';

$parser = new PDFDOMParser();
$document = $parser->parse(file_get_contents('doc.xml'));
if(!$document) die($parser->getException()->getMessage());

$writer = new PDFDOMWriter($document);
$pdfdocument = $writer->create();

// Make file
$file = $pdfdocument->makeFile();

// Output the file
$file->output();