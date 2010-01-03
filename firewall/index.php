<?php
require "../common/queries.php";
?>

<!-- upload section -->
<form name="uploadForm" enctype="multipart/form-data" action="../firewall/actions.php" method="post">
<table border=1>
<tr>
	<td>名称</td>
	<td>文档</td>
</tr>
<tr>
	<td><input type="textbox" name="upfile_name"></input></td>
	<td><input type="file" name="upfile"></input><input type="submit" name="submit" value="上传"></input></td>
</tr>
</table>
</form>


<!-- show section -->
<table border=1>
<tr>
	<td>名称</td>
	<td>上传人</td>
	<td>上传时间</td>
	<td>文档</td>
</tr>
<tr>
	<td>sss</td>
	<td>ddd</td>
	<td>dfff</td>
	<td></td>
</tr>
</table>