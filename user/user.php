<?php
require_once("../common/session.php");
require "../common/header.php";
require_once "../common/functions.php";
$action = $_GET['a'];
$id = @$_GET['id'];
$user_name="";
$user_login="";
$depart_id="";
$pbc_user_role_id="";
$pbc_template_id="";

if($action=="")
{
	$msg = "非正常访问！";
	golink($msg,$config['index']);//print a js for automatic redirect
	exit;
}

if($action == "mdf")
{
	if($id != "")
	{
		$sql = "
			SELECT		* 
			FROM		user u, pbc_user_role p,department d, pbc_template pt
			WHERE		u.user_pbc_role_id = p.pbc_user_role_id 
			AND			u.user_depart_id = d.depart_id  
			AND			u.user_pbc_template_id = pt.pbc_template_id 
			AND			u.user_active=1 
			AND			u.user_id = $id";
		$user = $db->fetch_all_array($sql);
		if($user)
		{
			$user_name = $user[0]['user_name'];
			$user_login = $user[0]['user_login'];
			$depart_id = $user[0]['depart_id'];
			$pbc_user_role_id = $user[0]['pbc_user_role_id'];
			$pbc_template_id = $user[0]['pbc_template_id'];
			$action .= "&id=$id";
		}
		else
		{
			$msg = "未找到相应的记录！";
			golink($msg,$_SERVER["HTTP_REFERER"]);
			exit;
		}
	}
	else
	{
		$msg = "非正常访问！";
		golink($msg,$_SERVER["HTTP_REFERER"]);
		exit;
	}
}
?>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
	<script src="../js/main.js" type=text/javascript></script>
  </head>
<body>
<div class="topbody"></div>
<center>
<!-- user input form -->
<form name="userForm" action="../user/actions.php?a=<?php echo $action;?>" method="post" onsubmit="return checkNull(this);">
<table class="mytable">
	<tr>
		<td align="right">姓名</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="user_name" id="user_name" alt="NotNull" value="<?php echo $user_name;?>" />
		</td>
	</tr>
	<tr>
		<td align="right">用户名</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="user_login" id="user_login" alt="NotNull" value="<?php echo $user_login;?>" />
		</td>
	</tr>
	<tr>
		<td align="right">所在工程组</td>
		<td align="left">
		<select name="user_depart_id" id="user_depart_id" alt="NotNull">
			<?php 
			//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
			echo listSelection($db,"department","depart_name","depart_id","where depart_active=1","$depart_id");
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td align="right">用户类型</td>
		<td align="left">
			<select name="user_pbc_role_id" id="user_pbc_role_id" alt="NotNull">
				<?php 
				//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
				echo listSelection($db,"pbc_user_role","pbc_role_name","pbc_user_role_id","","$pbc_user_role_id");
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">PBC模板类型</td>
		<td align="left">
			<select name="user_pbc_template_id" id="user_pbc_template_id" alt="NotNull">
				<?php 
				//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
				echo listSelection($db,"pbc_template","pbc_template_name","pbc_template_id","","$pbc_template_id");
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交"/>
		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>