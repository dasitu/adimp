<?php
require_once("../common/session.php");
require "../common/functions.php";
header("Content-Type: text/html; charset=utf-8");

if($_SESSION['depart_id']!='1')
{
	header("Location:project_show.php");
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
	<input class=btn type="button" name="filter" value="筛选数据" onclick="location.href='../project/project_search.php'"></input>
	&nbsp;&nbsp;
	<input class=btn type="button" name="filter" value="查看记录" onclick="location.href='../project/project_show.php'"></input>
</div>
<center>
<!-- user input form -->
<form name="projectForm" action="../project/actions.php" method="post" onsubmit="return checkNull(this);">
<table class="mytable">
	<tr>
		<td align="right">创建者</td>
		<td align="left">
		<?php echo $_SESSION['user_name']?>
		<INPUT type="hidden" name="project_creator_id" id="project_creator_id" value=<?php echo $_SESSION['user_id']?> />
		</td>
	</tr>
	<tr>
		<td align="right">项目名称</td>
		<td align="left"><INPUT class=textbox type="textbox" name="project_name" id="project_name" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">项目代号</td>
		<td align="left"><INPUT class=textbox type="textbox" name="project_no" id="project_no" alt="NotNull" /></td>
	</tr>
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交"></input>
		</td>
	</tr>
</table>
<input type="hidden" name="actions" value="insert_project" />
</form>

</center>
</body>
</html>