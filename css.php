<?php
include 'css/css.php';

$selector = new PDFCSSSelector(null, 'strong', array('beer', 'apple'), array('first-child', 'hover'));

$selector->setSelector(new PDFCSSSelector('>', 'a', null, null));

echo '<pre>';
echo $selector;