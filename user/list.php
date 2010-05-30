<?php
require_once("../common/session.php");
require "../common/functions.php";
require "../common/header.php";
?>
<div class="topbody">
	<input class=btn type="button" name="add" value="添加记录" onclick="location.href='../user/user.php?a=add'"></input>
</div>
<center>
<?php
//show part
$sql_select = " select * ";
$sql_from = "user u, pbc_user_role p,department d, pbc_template pt";
$where = "u.user_pbc_role_id = p.pbc_user_role_id 
		  and u.user_depart_id = d.depart_id  
		  and u.user_pbc_template_id = pt.pbc_template_id 
		  and u.user_active=1 
		  order by u.user_depart_id,u.user_pbc_role_id";
$sql = $sql_select." from ".$sql_from." where ".$where;
$body = $db->fetch_all_array($sql);
$folder = "user";
$id_name = "user_id";
$body = addOPLink($body,$folder,$id_name);//this will add a hash which index named "op_link"
$head = array("姓名","用户名","所在工程组","用户类型","PBC模板类型","操作");
$show_col = array("user_name","user_login","depart_name","pbc_role_name","pbc_template_name","op_link");//determin with column will be shown
echo listInTable($head,$body,$show_col);
?>
</center>