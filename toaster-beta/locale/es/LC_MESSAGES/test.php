<?php

ini_set('error_reporting', E_ALL);
// $xmlIn = file_get_contents('test.xml');
$xml = new DOMDocument('1.0', 'UTF-8');
// $xml->loadXml($xmlIn);
$xml->load('test.xml');
// $xml = DOMDocument::load('test.xml');
echo $xml->saveXML();

exit;

?>
