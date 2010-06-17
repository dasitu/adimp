<?php
require "../common/functions.php";
require "../common/header.php";
?>
<div class="topbody"></div>
<center>
<?php
$pbc_id = @$_GET['id'];
if($pbc_id=="")
{
	echo "参数不正确!";
	exit;
}
$sql = "select * from pbc_change_log where pbc_id='$pbc_id'";
$head = array("日志内容","修改时间","修改人","IP地址");
$show_col = array("pbc_change_text","pbc_change_time","pbc_change_by","pbc_change_ip");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"pbc_change_time",true); 
echo listInTable($head,$body,$show_col);
?>
</center>