<?php
session_start();
require "../common/functions.php";
$msg = "";
if(isset($_SESSION['user_id']))
{
	Header("Location:../index/index.php");
}
if(isset($_POST['btnLogin']))
{
	$user = login($db,$_POST['txtUserName'],$_POST['txtUserPassword']);
	if(!$user)
	{
		$msg = "用户名密码错误，请检查后重新输入!";
	}
	else
	{
		$_SESSION['user_name'] = $user['user_name'];
		$_SESSION['user_login'] = $user['user_login'];
		$_SESSION['user_id'] = $user['user_id'];
		$_SESSION['depart_name'] = $user['depart_name'];
		$msg = "succesessful";
		header("location:../index/index.php");
	}
}
?>
<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.0 Transitional//EN>
<HTML>
<HEAD>
<TITLE>登录</TITLE>
<META http-equiv="Content-Type" content="text/html"; charset=utf-8>
<script src="../js/main.js" type=text/javascript></script>
<LINK href="../css/login.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/main.css" />
</HEAD>
<BODY id="body">
	<form action="<?php $_SERVER['PHP_SELF'];?>" method='post' onsubmit="return checkNull(this);">
	<table id=main>
		<tr>
			<td colSpan=3>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td id=login>
			<TABLE height=498 width=800>
              <TR height=250>
                <TD colSpan=4>&nbsp;</TD>
              </TR>
              <TR>
                <TD rowSpan=4 width=390>&nbsp;</TD>
                <TD>用户名：</TD>
                <TD>
                  <INPUT class=textbox id=txtUserName name=txtUserName alt="NotNull" />
                </TD>
                <TD width=150>&nbsp;</TD>
              </TR>
              <TR>
                <TD>密　码：</TD>
                <TD>
                  <INPUT class=textbox id=txtUserPassword type=password name=txtUserPassword alt="NotNull" />
                </TD>
                <TD>&nbsp;</TD>
              </TR>
              <TR>
                <TD>&nbsp;</TD>
                <TD align=right>
                  <INPUT class=btn id=btnLogin type=submit value=" 登 录 "  name=btnLogin />
                </TD>
                <TD>&nbsp;</TD>
              </TR>
              <TR height=140>
                <TD colSpan=4>
				<font color='red'>
				<?php 
				//put some message here
				echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "";
				echo $msg;
				?>
				&nbsp;
				</font>
				</TD>
              </TR>
          </TABLE>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td id=root>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	</form>
</BODY>
</HTML>