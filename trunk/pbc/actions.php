<?php
require_once("../common/session.php");
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
$actions = $_POST['actions'];
$msg = "操作失败";

//*********************actions for insert table**************//
if($actions == "insert_pbc")
{
	$table_arr="";
	$table_name = "pbc";
	$user_id = $_GET['uid'];
	if($user_id=="")
	{
		echo "插入pbc出错！";
		exit;
	}
	//print_r($_POST);
	//check whether the pbc of this month is existed.
	//if not, insert the pbc of this month first. insert into table "pbc"
	$insert_time = determinPbcInsertTime($pbc_config);
	$sql = "select pbc_id,pbc_status from pbc where pbc_user_id = ".$user_id." 
	and MONTH(FROM_UNIXTIME(pbc_time,'%y-%m-%d')) = ".date('n',$insert_time);
	$pbc = $db->query_first($sql);
	$pbc_id = $pbc['pbc_id'];

	if(!$pbc_id)
	{
		$table_arr['pbc_user_id'] = $user_id;
		$table_arr['pbc_time'] = $insert_time;
		$table_arr['pbc_status'] = "initial";
		$table_arr['pbc_change_time'] = time();
		$table_arr['pbc_change_by'] = $_SESSION['user_name'];
		$pbc_id = $db->query_insert($table_name,$table_arr);
	}
	else
	{
		$pbc_status = 'submitted';
		$update_user = $_SESSION['user_name'];
		updatePBCStatus($pbc_id,$pbc['pbc_status'],$update_user,$db);
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
//*********************end insert**********************//

//********************submit the pbc*******************//
else if($actions == "pbc_submit")
{
	$pbc_id = $_POST['pbc_id'];
	$pbc_reward = $_POST['pbc_reward'];
	$sql = "update pbc set pbc_reward='".$pbc_reward."' where pbc_id=".$pbc_id;
	if($pbc_reward!=""){
		$db->query($sql);
	}
	$pbc_status = 'submitted';
	$update_user = $_SESSION['user_name'];
	if(	updatePBCStatus($pbc_id,$pbc_status,$update_user,$db))
		$msg = "提交成功！";
}
//*****************************************************//

//********************approve the pbc*******************//
else if($actions == "pbc_approve")
{
	$pbc_id = $_POST['pbc_id'];
	$pbc_reward = $_POST['pbc_reward'];
	$sql = "update pbc set pbc_reward='".$pbc_reward."' where pbc_id=".$pbc_id;
	if($pbc_reward!=""){
		$db->query($sql);
	}
	$pbc_status = 'approved';
	$update_user = $_SESSION['user_name'];
	if(	updatePBCStatus($pbc_id,$pbc_status,$update_user,$db))
		$msg = "提交成功！";
}
//*****************************************************//

//***************submit the self grade*****************//
else if($actions == "self_evaluate")
{
	$pbc_id = $_POST['pbc_id'];
	foreach($_POST as $key => $value)
	{
		if(substr($key, 0, 14) == 'pbc_grade_self')
		{
			$arr = explode("#",$key);
			$col_name = $arr[0];
			$col_where = $arr[1];
			$sql = "update pbc_data set $col_name='$value' where pbc_data_id='$col_where'";
			//echo $sql."<br>";
			$db->query($sql);
		}
	}
	$pbc_status = 'self_scored';
	$update_user = $_SESSION['user_name'];
	if(	updatePBCStatus($pbc_id,$pbc_status,$update_user,$db))
		$msg = "提交成功！";
}
//*****************************************************//

//***************submit the grade*****************//
else if($actions == "pbc_evaluate")
{
	$pbc_id = $_POST['pbc_id'];
	foreach($_POST as $key => $value)
	{
		if(substr($key, 0, 9) == 'pbc_grade')
		{
			$arr = explode("#",$key);
			$col_name = $arr[0];
			$col_where = $arr[1];
			$sql = "update pbc_data set $col_name='$value' where pbc_data_id='$col_where'";
			//echo $sql."<br>";
			$db->query($sql);
		}
	}

	$pbc_status = 'scored';
	$update_user = $_SESSION['user_name'];
	$pbc_total = calculatePBC($pbc_id,$db);
	$sql = "UPDATE pbc 
	SET pbc_status= '".$pbc_status."',
		pbc_total_grade = '".$pbc_total."',
		pbc_change_time = '".time()."',
		pbc_change_by = '".$update_user."'
	WHERE pbc_id = '".$pbc_id."'";
	if($db->query($sql))
		$msg = "提交成功！";
}
//*****************************************************//

//*********************actions for update table**************//
if($actions == "modify_pbc")
{
	//update pbc_data 
	$table_arr="";
	$table_name = "pbc_data";
	$pbc_data_id = $_POST['pbc_data_id'];
	$pbc_id = $_POST['pbc_id'];
	$_POST["pbc_planned_end_date"] = strtotime($_POST["pbc_planned_end_date"]);
	unset($_POST['pbc_data_id']);
	unset($_POST['pbc_id']);
	unset($_POST['submit']);
	unset($_POST['actions']);
	
	foreach($_POST as $key => $value)
	{
		$table_arr["$key"] = $_POST["$key"];
	}

	$update_user = $_SESSION['user_name'];
	$where="pbc_data_id=$pbc_data_id";
	$update_id = $db->query_update($table_name,$table_arr,$where);
	$sql = 
		"UPDATE pbc 
		SET	pbc_change_time = '".time()."',
			pbc_change_by = '".$update_user."'
		WHERE pbc_id = '".$pbc_id."'";
	if($db->query($sql))
		echo "<script>history.go(-2);</script>";
}
//*********************end insert**********************//

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect
?>