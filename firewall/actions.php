<?php
require_once("../common/session.php");
header("Content-Type: text/html; charset=utf-8");
require_once "../common/functions.php";
require "../lib/upload.class.php"; //classes is the map where the class file is stored (one above the root)
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<div class="topbody"></div>

<?php
if(!isset($_POST['actions']))
{
	$msg = "if is forbidden to access this file!";
	golink($msg,$config['index']);//print a js for automatic redirect
	exit;
}

$actions = $_POST['actions'];
$msg = "";

//******************actions for insert firewall_rule**************//
if($actions == "insert_firewall_rule")
{
	$upfile_name = $_POST['upfile_name'];
	$upfile = $_FILES['upfile'];
	$doc_id = uploadFile($db,$config,$upfile,$upfile_name);
	$msg = $doc_id;
	if(is_numeric($doc_id))
	{
		$firewall_rule['rule_doc_id'] = $doc_id;
		$insert_id = $db->query_insert("firewall_rule",$firewall_rule);
		$msg = "添加成功！";
	}
}
//*********************end insert firewall_rule******************//

//*********************actions for insert firewall**************//
else if($actions == "insert_firewall")
{
	$table_arr = "";
	$table_name = "firewall";
	foreach($_POST as $key => $value)
	{
		if($key != "submit" && $key != "actions")
		{
			$table_arr["$key"] = $_POST["$key"];
		}

		if($key == "f_date")
		{
			$table_arr["$key"] = strtotime($_POST["$key"]);
		}
	}
	$insert_id = $db->query_insert($table_name,$table_arr);
	$msg = "添加成功！";
}
//*********************end insert firewall**********************//

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect

?>