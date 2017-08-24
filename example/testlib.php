<?php
include 'lib/pdf.php';

$document = new PDFDocument();
$document->getInfo()->setTitle('Test document');
$document->getInfo()->setSubject('Document om te testen of alles werkt');
$document->getInfo()->setAuthor('Tinus Bruins');
$document->getInfo()->setCreator('10usb');

$document->getPages()->setSize(395.28, 241.89);

$page = $document->getPages()->addPage();
$page->setFont($document->getResources()->getFont('Times-Italic'), 16);

$page->setColor(192, 192, 192);
$page->text(30 + 0.8, 30 + 0.8, "PDF Library");

$page->setColor(0, 0, 0);
$page->text(30, 30, "PDF Library");

$page->setFont($document->getResources()->getFont('Helvetica'), 11);



$page->text(30, 56, "Hallo moie wereld \thoe gaat\n het (er) mee");

$width = $page->getTextWidth("Hallo moie wereld \thoe gaat\n het (er) mee");
$page->setLineWidth(0.55);
$page->line(30, 69, 30 + $width, 69);

$page->setLineWidth(5);
$page->line(30, 89, 30 + $width, 89);

$page->setLineWidth(1);

$page->setLineColor(0, 0, 0);
$page->setColor(192, 192, 192);
$page->rectangle(30, 100, 100, 50);
$page->addLink(30, 100, 100, 50, 'http://www.tinusbruins.nl');
$page->rectangle(135, 100, 100, 50, true, true);
$page->addLink(135, 100, 100, 50, null, 1, 0);
$page->rectangle(235, 100, 100, 50, false, true);
$page->addLink(235, 100, 100, 50, 'http://www.google.com');

$page->setColor(0, 0, 0);
$page->text(30, 100, "Hallo moie wereld \thoe gaat\n het (er) mee");


$image = $document->getResources()->loadImage('pdf.jpg');
$page->image($image, 35, 130, 118*0.7, 87*0.7);

$image = $document->getResources()->loadImage('pdf.png');
$page->image($image, 135, 100, 118*0.7, 87*0.7);


$page = $document->getPages()->addPage(new PDFSize(295.28, 341.89));
$page->setFont($document->getResources()->getFont('Times-Italic'), 16);
$page->text(30, 30, "PDF Library");

$page = $document->getPages()->addPage();
$page->setFont($document->getResources()->getFont('Times-Italic'), 16);
$page->text(30, 30, "PDF Library");

$file = $document->makeFile();

// Output the file
$file->output();