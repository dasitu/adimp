<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require_once "../common/functions.php";
error_reporting(-1);
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

print_r($_POST);
exit;

$actions = $_POST['actions'];
$msg = "";

//*********************actions for insert table**************//
if($actions == "insert_pbc")
{
	$table_arr="";
	$table_name = "pbc";
	//print_r($_POST);
	//check whether the pbc of this month is existed.
	//if not, insert the pbc of this month first. insert into table "pbc"
	$sql = "select pbc_id from pbc where pbc_user_id = ".$_SESSION['user_id']." 
	and MONTH(FROM_UNIXTIME(pbc_time,'%y-%m-%d')) = ".date('n',time());
	$pbc = $db->query_first($sql);
	$pbc_id = $pbc['pbc_id'];

	if(!$pbc_id)
	{
		$table_arr['pbc_user_id'] = $_SESSION['user_id'];
		$table_arr['pbc_time'] = time();
		$table_arr['pbc_status'] = "initial";
		$table_arr['pbc_change_time'] = time();
		$table_arr['pbc_change_by'] = $_POST['user_name'];
		$pbc_id = $db->query_insert($table_name,$table_arr);
	}
	
	//insert into pbc_data
	$table_arr="";
	$table_name = "pbc_data";
	foreach($_POST as $key => $value)
	{
		if($key != "submit" && $key != "actions")
		{
			$table_arr["$key"] = $_POST["$key"];
		}
		if($key == "pbc_planned_end_date")
		{
			$table_arr["$key"] = strtotime($_POST["$key"]);
		}
	}
	$table_arr['pbc_id'] = $pbc_id;
	$insert_id = $db->query_insert($table_name,$table_arr);
	$msg = "添加成功！";
}
//*********************end insert firewall**********************//

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect

?>