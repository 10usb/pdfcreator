<?php
include 'lib/pdf.php';
include 'render/render.php';
include 'dom/dom.php';

header('Content-Type: text/plain; charset="utf-8"');

$parser = new PDFDOMParser();
$document = $parser->parse(file_get_contents('doc.xml'));
if(!$document) die($parser->getException()->getMessage());

print_r($document);