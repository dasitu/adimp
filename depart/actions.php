<?php
require_once("../common/session.php");
require "../common/header.php";
require_once "../common/functions.php";

$id = @$_GET['id'];
$action = $_GET['a'];

$msg = "非法操作！";

//----------------------------- actions for del ----------------------
if($id!="" && $action=="del")
{
	$sql = "update user set user_active=0 where user_depart_id = $id";
	$msg = "删除失败！";
	if($db->query($sql))
	{
		$sql = "update department set depart_active=0 where depart_id = $id";
		if($db->query($sql))
			$msg = "删除成功！";
	}
}

//-------------------------- actions for modify ---------------------
if($id!="" && $action=="mdf")
{
	$depart_name = $_POST["depart_name"];
	$sql = "update department set depart_name='$depart_name' where depart_id = $id";
	$msg = "修改失败！";
	if($db->query($sql))
	{
		$msg = "修改成功！";
	}
}

//-------------------------- actions for add ---------------------
if($action=="add")
{
	$depart_name = $_POST["depart_name"];
	$sql = "insert into department(depart_name) values ('$depart_name')";
	$msg = "添加失败！";
	if($db->query($sql))
	{
		$msg = "添加成功！";
	}
}

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect
?>