<?php
require_once("../common/session.php");
require_once "../common/functions.php";
require_once "../common/header.php";
$pbc_data_id = $_GET['id'];
$pbc_id = $_GET['pbc_id'];

$msg = "非法操作！";

if($pbc_id)
{
	$sql = "delete from pbc_data where pbc_data_id = $pbc_data_id";
	$sql1 = "update pbc 
		set pbc_change_time=".time().", pbc_change_by='".$_SESSION['user_name']."' 
		where pbc_id = $pbc_id";

	$msg = "删除失败！";
	if($db->query($sql))
	{
		$db->query($sql1);
		$pbc_text = "PBC 删除一条记录完成 ";
		logPBC($pbc_id,$pbc_text,$_SESSION['user_name'],$db);
		$msg = "删除成功！";
	}
}

//direct back to the page with the info
golink($msg,$_SERVER["HTTP_REFERER"]);//print a js for automatic redirect
?>