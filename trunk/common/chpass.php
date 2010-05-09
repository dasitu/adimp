<?php
require_once("../common/session.php");
require "../common/functions.php";
?>
<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<script src="../js/main.js" type=text/javascript></script>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
</head>
<body>
<div class="topbody"></div>
<center>
<?php
if(isset($_POST['passwd2']))
{
	if($_POST['passwd1'] == $_POST['passwd2'])
	{
		$sql = "UPDATE user SET 
				user_login='".$_POST['user_login']."',
				user_pwd=password('".$_POST['passwd1']."') where user_id='".$_SESSION['user_id']."'";
		//echo $sql;
		if($db->query($sql))
		{
			echo "<font color='red'>修改成功！</font><br>";
		}
	}
	else
	{
		echo "<font color='red'>两次密码输入不一致，请重新输入！</font><br>";
	}
}
?>
<form name='chpass' action="#" method="post" onsubmit="return checkNull(this);">
<table class="mytable">
	<tr>
		<td>姓名</td>
		<td><?php echo $_SESSION['user_name'];?></td>
	</tr>
	<tr>
		<td>用户名</td>
		<td><input name='user_login' value='<?php echo $_SESSION['user_login'];?>' alt="NotNull"></input></td>
	</tr>
	<tr>
		<td>新密码</td>
		<td><input name='passwd1' alt="NotNull"></input></td>
	</tr>
	<tr>
		<td>确认新密码</td>
		<td><input name='passwd2' alt="NotNull"></input></td>
	</tr>
	<tr>
		<td></td>
		<td><input type='submit' value='提交'></input></td>
	</tr>
</table>
<form>
<center>
</body>
</html>