<?php


define('BTS_TEMPLATE_DIR', './tpl');
require('ToasterDoc.php');

$book = new ToasterDoc;
$return = $book->display();
if(PEAR::isError($return)) {
    die($book->getMessage());
}

?>
