<?php
include 'css/css.php';

$document = new CSSDocument();
$parser = new CSSParser($document);
$parser->parse(file_get_contents('doc.css'));




echo '<pre>';

$selector = $current = new CSSSelector(null, 'section', null, null);
$current = $current->setSelector(new CSSSelector('>', 'body', null, null));
$current = $current->setSelector(new CSSSelector('>', 'p', array('special'), null));
$current = $current->setSelector(new CSSSelector('>', 'strong', null, null));

echo $selector."\n";
echo $document->match($selector);

echo "\n------------\n";

$selector = $current = new CSSSelector(null, 'section', null, null);
$current = $current->setSelector(new CSSSelector('>', 'body', null, null));
$current = $current->setSelector(new CSSSelector('>', 'p', array('special'), null));
$current = $current->setSelector(new CSSSelector('>', 'span', null, null));
$current = $current->setSelector(new CSSSelector('>', 'strong', null, null));

echo $selector."\n";
echo $document->match($selector);
