<?php
require "../common/queries.php";
header("Content-Type: text/html; charset=utf-8");
?>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
  </head>
<body>
<div class="topbody"></div>
<center>
	<div style="width:70%">
		<div style="display:block;">
		<!-- upload section -->
		<form name="uploadForm" enctype="multipart/form-data" action="../firewall/actions.php" method="post">
		<table class="mytable">
		<tr>
			<td>文档名称</td>
			<td><input class="textbox" type="textbox" name="upfile_name"></input></td>
		</tr>
		<tr>
			<td>路径</td>
			<td><input type="file" name="upfile"></input></td>
		</tr>
		<tr><td colSpan="2" align="center"><input  class=btn type="submit" name="submit" value="上传"></input></td></tr>
		</table>
		<input type="hidden" name="actions" value="insert_firewall_rule"></input>
		</form>
		</div>

		<div>
		<!-- show section -->
		<?php echo listFirewallRule($db); ?>
		</div>
	</div>
</center>
</body>
</html>