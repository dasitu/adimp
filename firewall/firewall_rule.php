<?php
require "../common/queries.php";
header("Content-Type: text/html; charset=utf-8");
?>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
  </head>
<body>
<center>
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
</form>

<!-- show section -->
<?php echo listFirewallRule($db); ?>
</center>
</body>
</html>