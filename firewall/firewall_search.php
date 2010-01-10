<?php
session_start();
//require "../common/queries.php";
header("Content-Type: text/html; charset=utf-8");
?>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/border-radius.css" />
	<script src="../js/main.js" type=text/javascript></script>
    <script type="text/javascript" src="../js/jscal2.js"></script>
    <script type="text/javascript" src="../js/lang/cn.js"></script>
  </head>
<body>
<center>

<!-- user input form -->
<form name="firewallForm" enctype="multipart/form-data" action="../firewall/actions.php" method="post" >
<table class="mytable">
	<tr>
		<td align="right">姓名</td>
		<td align="left"><INPUT class=textbox type="textbox" name="f_user_id" id="f_user_id"/>
		</td>
	</tr>
	<tr>
		<td align="right">所在工程组</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="user_depart_name" id="user_depart_name"/>
		</td>
	</tr>

	<tr>
		<td align="right">时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="f_date" id="f_date"/></td>
	</tr>
	
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交筛选"></input>
		</td>
	</tr>
</table>
</form>

<script>
    Calendar.setup({
        trigger    : "f_date",
        inputField : "f_date",
		onSelect   : function() { this.hide() }
    });
</script>
</center>
</body>
</html>