<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require_once "../common/queries.php";
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

//******************actions for insert trip**************//
if($actions == "insert_trip")
{
	//print_r($_POST);
	//exit;
	//upload the report doc file to upfiles
	$upfile = $_FILES['upfile'];
	$doc_id = uploadFile($db,$config,$upfile);
	$msg = $doc_id;
	$table_arr = "";
	$table_name = "trip";
	
	//insert the record into table trip
	if(is_numeric($doc_id))
	{
		$table_arr['trip_report_doc_id'] = $doc_id;
		foreach($_POST as $key => $value)
		{
			if($key != "submit" && $key != "actions")
			{
				$table_arr["$key"] = $_POST["$key"];
			}

			if($key == "trip_leaving_date" || $key == "trip_back_date")
			{
				$table_arr["$key"] = strtotime($_POST["$key"]);
			}
		}
		print_r($table_arr);
		$insert_id = $db->query_insert($table_name,$table_arr);
		$msg = "添加成功！";
	}
}
//*********************end insert firewall**********************//

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect

?>