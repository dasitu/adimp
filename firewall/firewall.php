<!DOCTYPE html PUBLIC
          "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/border-radius.css" />
    <script type="text/javascript" src="../js/jscal2.js"></script>
    <script type="text/javascript" src="../js/lang/cn.js"></script>
  </head>

<?php
require "../common/queries.php";
?>

<!-- user input form -->
<form name="firewallForm" enctype="multipart/form-data" action="../firewall/actions.php" method="post">
<table>
	<tr>
		<td align="right">姓名</td>
		<td align="left"><input type="textbox" name="f_user_id" id="f_user_id" readonly /></td>
	</tr>
	<tr>
		<td align="right">所在工程组</td>
		<td align="left"><input type="textbox" name="user_depart_id" id="user_depart_id" /></td>
	</tr>
	<tr>
		<td align="right">事件类型</td>
		<td align="left">
			<select name="f_type_id" id="f_type_id">
				<?php 
				//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
				echo listSelection($db,"firewall_content_type","f_c_type_name","f_c_type_id");
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">事件</td>
		<td align="left"><textarea name="f_content" id="f_content"></textarea></td>
	</tr>
	<tr>
		<td align="right">时间</td>
		<td align="left"><input type="textbox" name="f_date" id="f_date" /></td>
	</tr>
	<tr>
		<td align="right">证明人</td>
		<td align="left"><input type="textbox" name="f_refer_name" id="f_refer_name" /></td>
	</tr>
	<tr>
		<td align="right">处罚条款</td>
		<td align="left"><input type="textbox" name="f_rules" id="f_rules" /></td>
	</tr>
</table>
	<input type="submit" name="submit" value="提交"></input>
</form>

<script>
    Calendar.setup({
        trigger    : "f_date",
        inputField : "f_date"
    });
</script>