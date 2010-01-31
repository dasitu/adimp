<?php
require ('../lib/xajax/xajax_core/xajax.inc.php');
require_once("../common/functions.php");
$xajax = new xajax();
$xajax->configure('javascript URI','../lib/xajax/');

function listActiveType($biz_type_id,$target_obj_name){

	$objResponse = new xajaxResponse();
	$objResponse->assign($target_obj_name,"innerHTML","");
	if($biz_type_id!="")
	{
		$db = new database();
		$db->connect();

		//get the options from db
		$table_name = "pbc_active_type";
		$col_name = "pbc_active_name";
		$col_value = "pbc_active_name";
		$where = " WHERE pbc_biz_type_id = $biz_type_id";
		$target_value = listSelection($db,$table_name,$col_name,$col_value,$where);

		if($target_value=="")
		{
			$target_value = "<input class=textbox name='$target_obj_name'></input>";
		}
		else
		{
			$target_value = "<select name='$target_obj_name'>".$target_value."</select>";
		}

		$objResponse->assign($target_obj_name,"innerHTML",$target_value);
		$db->close();
	}

	return $objResponse;
}

//********register the php function as JS function********//
$xajax->register(XAJAX_FUNCTION,"listActiveType");
//*******************************************************//

$xajax->processRequest();
?>