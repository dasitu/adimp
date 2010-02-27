<?php
require_once("../common/session.php");
require "../common/functions.php";
header("Content-Type: text/html; charset=utf-8");

if($_SESSION['depart_id']!='1')
{
	header("Location:firewall_show.php");
}

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
<div class="topbody">
	<input class=btn type="button" name="filter" value="筛选数据" onclick="location.href='../firewall/firewall_search.php'"></input>
	&nbsp;&nbsp;
	<input class=btn type="button" name="filter" value="查看记录" onclick="location.href='../firewall/firewall_show.php'"></input>
</div>
<center>
<!-- user input form -->
<form name="firewallForm" action="../firewall/actions.php" method="post" onsubmit="return checkNull(this);">
<table class="mytable">
	<tr>
		<td align="right">姓名</td>
		<td align="left">
			<select name="f_user_id" id="f_user_id" alt="NotNull">
				<?php 
				//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
				echo listSelection($db,"user","user_name","user_id");
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">事件类型</td>
		<td align="left">
			<select name="f_type_id" id="f_type_id" alt="NotNull">
				<?php 
				//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
				echo listSelection($db,"firewall_content_type","f_c_type_name","f_c_type_id");
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">事件</td>
		<td align="left"><textarea class="textarea" rows=5 cols=21 name="f_content" id="f_content" alt="NotNull" ></textarea></td>
	</tr>
	<tr>
		<td align="right">时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="f_date" id="f_date" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">证明人</td>
		<td align="left"><INPUT class=textbox type="textbox" name="f_refer_name" id="f_refer_name" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">处罚条款</td>
		<td align="left"><INPUT class=textbox type="textbox" name="f_rules" id="f_rules" alt="NotNull" /></td>
	</tr>
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交"></input>
		</td>
	</tr>
</table>
<input type="hidden" name="actions" value="insert_firewall" />
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