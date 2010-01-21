<?php
session_start();
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

//*********************actions for insert table**************//
if($actions == "insert_project")
{
	$table_arr = "";
	$table_name = "project";
	foreach($_POST as $key => $value)
	{
		if($key != "submit" && $key != "actions")
		{
			$table_arr["$key"] = $_POST["$key"];
		}
	}
	$table_arr['project_create_date'] = time();
	$insert_id = $db->query_insert($table_name,$table_arr);
	$msg = "添加成功！";
}
//*********************end insert firewall**********************//

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect

?>