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
	$sql = "update user set user_active=0 where user_id = $id";
	$msg = "删除失败！";
	if($db->query($sql))
	{
		$msg = "删除成功！";
	}
}

//-------------------------- actions for modify ---------------------
if($id!="" && $action=="mdf")
{
	$user_name = $_POST['user_name'];
	$user_login = $_POST['user_login'];
	$user_depart_id = $_POST['depart_id'];
	$user_pbc_role_id = $_POST['pbc_user_role_id'];
	$user_pbc_template_id = $_POST['pbc_template_id'];

	$sql = "UPDATE		user 
			SET			user_name='$depart_name',
						user_login='$user_login',
						user_depart_id='$user_depart_id',
						user_pbc_role_id='$user_pbc_role_id',
						user_pbc_template_id='$user_pbc_template_id' 		
			WHERE		user_id = $id";
	$msg = "修改失败！";
	if($db->query($sql))
	{
		$msg = "修改成功！";
	}
}

//-------------------------- actions for add ---------------------
if($action=="add")
{
	$user_name = $_POST['user_name'];
	$user_login = $_POST['user_login'];
	$user_depart_id = $_POST['user_depart_id'];
	$user_pbc_role_id = $_POST['user_pbc_role_id'];
	$user_pbc_template_id = $_POST['user_pbc_template_id'];
	$sql = "
	INSERT INTO  user(user_name,user_login,user_depart_id,user_pbc_role_id,user_pbc_template_id) 
	VALUES        ('$user_name','$user_login','$user_depart_id','$user_pbc_role_id','$user_pbc_template_id')";
	$msg = "添加失败！";
	if($db->query($sql))
	{
		$msg = "添加成功！";
	}
}

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect
?>