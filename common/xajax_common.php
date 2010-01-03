<?php
require ('../lib/xajax/xajax_core/xajax.inc.php');
$xajax = new xajax('../common/xajax_server.php');
$xajax->configure('javascript URI','../lib/xajax/');

//register the php function as JS function
$xajax->register(XAJAX_FUNCTION,"test1");
?>