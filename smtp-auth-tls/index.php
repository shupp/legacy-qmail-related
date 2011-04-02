<?php

$version='20070417';
$smtpauth='0.58';
$tls='20070408';

?>

<html>
<body>
<h2>Qmail SMTP-AUTH/TLS composite patch:</h2>

<a href=http://shupp.org/patches/netqmail-1.05-tls-smtpauth-<?php echo $version?>.patch>http://shupp.org/patches/netqmail-1.05-tls-smtpauth-<?php echo $version?>.patch</a>
<p>

Patches Included:<p>
TLS: <?php echo $tls?><br>
SMTP-AUTH: <?php echo $smtpauth?><p><br><br>

See top of patch for instructions

<p>
NOTE:  If you use softlimit, make sure you allow about 8MB, by calling it with "softlimit -m 8000000".  Otherwise, you may get errors loading the SSL libraries.

<hr>
Last updated: <? echo date("F j, Y, g:i a T", filemtime('index.php'));?>

<p><br>
<center>
</body></html>
