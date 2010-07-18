<?php
require_once("../common/session.php");
require "../common/functions.php";
require "../common/header.php";
?>
<div class="topbody">
	<input class=btn type="button" name="add" value="添加记录" onclick="location.href='../depart/depart.php?a=add'"/>
</div>
<center>
<?php
//show part
$sql_select = " select * ";
$sql_from = "from department ";
$where = "where depart_active=1";
$sql = $sql_select.$sql_from.$where;
$body = $db->fetch_all_array($sql);
$folder = "depart";
$id_name = "depart_id";
$body = addOPLink($body,$folder,$id_name);//this will add a hash which index named "op_link"

$head = array("工程组名称","操作");
$show_col = array("depart_name","op_link");//determin with column will be shown
echo listInTable($head,$body,$show_col);
?>
</center>