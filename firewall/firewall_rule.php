<?php
require "../common/session.php";
require "../common/functions.php";
?>
<html>
<head>
<?php require "../common/header.php"; ?>
</head>
<body>
<div class="topbody"></div>
<center>
	<div style="width:70%">
		<div style="display:block;">
		<?php if($_SESSION['depart_id'] == '1'){?>
		<!-- upload section -->
		<form name="uploadForm" enctype="multipart/form-data" action="../firewall/actions.php" method="post">
		<table class="mytable">
		<tr>
			<td>文档名称</td>
			<td><input class="textbox" type="textbox" name="upfile_name"/></td>
		</tr>
		<tr>
			<td>路径</td>
			<td><input type="file" name="upfile"/></td>
		</tr>
		<tr><td colSpan="2" align="center"><input  class=btn type="submit" name="submit" value="上传"/></td></tr>
		</table>
		<input type="hidden" name="actions" value="insert_firewall_rule"/>
		</form>
		<?php }?>
		</div>

		<div>
		<!-- show section -->
		<?php 
			$sql = "select b.upfile_id, b.upfile_name,b.upfile_time,b.upfile_ext,c.user_name from firewall_rule a, upfiles b, user c where a.rule_doc_id = b.upfile_id and b.upfile_user_id = c.user_id";
			$head = array("文件名","上传时间","上传人","文档");
			$show_col = array("upfile_name","upfile_time","user_name","doc_link");//determin with column will be shown
			$body = $db->fetch_all_array($sql);
			$body = time2str($body,true,"upfile_time"); //convert the datetime to string, "true"means it is a dataset, "upfile_time" means the column name
			$body = addDownloadLink($body); //add the last image link for download files, the column name is "doc_link"
			echo listInTable($head,$body,$show_col);
		?>
		</div>
	</div>
</center>
</body>
</html>