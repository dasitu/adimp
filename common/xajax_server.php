<?php
function test1() {
	$objResponse = new xajaxResponse();
	$objResponse->alert('this is the test');
	return $objResponse;
}

require ('../lib/xajax/xajax_core/xajax.inc.php');
$xajax = new xajax();
$xajax->configure('javascript URI','../lib/xajax/');

//********register the php function as JS function********//
$xajax->register(XAJAX_FUNCTION,"test1");
//*******************************************************//

$xajax->processRequest();
?>