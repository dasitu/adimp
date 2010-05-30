<?php
require_once("../common/session.php");
require "../common/header.php";
require_once "../common/functions.php";
$action = $_GET['a'];
$id = @$_GET['id'];
$depart_name = "";

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
		$sql = "select * from department where depart_id=$id";
		$depart = $db->fetch_all_array($sql);
		if($depart)
		{
			$depart_name = $depart[0]['depart_name'];
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
<form name="userForm" action="../depart/actions.php?a=<?php echo $action;?>" method="post" onsubmit="return checkNull(this);">
<table class="mytable">
	<tr>
		<td align="right">工程组名称</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="depart_name" id="depart_name" alt="NotNull" value="<?php echo $depart_name;?>" />
		</td>
	</tr>
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交"></input>
		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>