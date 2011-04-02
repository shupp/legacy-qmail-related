#!/usr/bin/php
<?php

ini_set('error_reporting', E_ALL);
require_once 'I18Nv2.php';

function setLang($locale) {
    I18Nv2::setLocale($locale);
    // putenv("LANG=$locale");
    // putenv("LANGUAGE=$locale");
    // setlocale(LC_ALL, $locale);
    bindtextdomain("messages", "./");
    bind_textdomain_codeset("messages", 'UTF-8');
    textdomain("messages");
}

$locale = $argv[1];
echo "locale: $locale\n";
setLang($locale);

echo _("Donate!");
echo "\n";
exit;

?>
