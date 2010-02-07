<?php
require_once("../common/session.php");
error_reporting(0);
require "../common/xajax_server.php";
require_once ("../common/functions.php");
header("Content-Type: text/html; charset=utf-8");
$xajax->printJavascript();
?>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/border-radius.css" />
	<script type="text/javascript" src="../js/main.js"></script>
    <script type="text/javascript" src="../js/jscal2.js"></script>
    <script type="text/javascript" src="../js/lang/cn.js"></script>
  </head>
<body>
<div class="topbody">
	<input class=btn type="button" name="filter" value="查看PBC" onclick="location.href='../pbc/pbc_show.php'"></input>
</div>
<center>
<!-- user input form -->
<form name="pbcForm" enctype="multipart/form-data" action="../pbc/actions.php" method="post" onsubmit="return checkNull(this);">
<table class="mytable">
	<tr>
		<td align="right">业务类别</td>
		<td align="left">
		<select name="pbc_biz_type_id" id="pbc_biz_type_id" onchange="xajax_listActiveType(this.value,'pbc_active_type')" alt="NotNull">
			<option value="">请选择</option>
			<?php
			$table_name = "user u, pbc_temp_biz ptb, pbc_biz_type pbt";
			$col_name = "pbt.pbc_biz_type_name";
			$col_value = "pbt.pbc_biz_type_id";
			$where = " 
			WHERE	u.user_pbc_template_id = ptb.pbc_template_id 
			AND		ptb.pbc_biz_type_id = pbt.pbc_biz_type_id 
			AND		u.user_id = ".$_SESSION['user_id']; 
			echo listSelection($db,$table_name,$col_name,$col_value,$where);
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td align="right">活动分类</td>
		<td align="left">
		<div id="pbc_active_type"></div>
		</td>
	</tr>
	<tr>
		<td align="right">活动内容</td>
		<td align="left">
			<input class=textbox name="pbc_active" id="pbc_active" alt="NotNull"></input>
		</td>
	</tr>
	<tr>
		<td align="right">关联任务</td>
		<td align="left"><INPUT class=textbox type="textbox" name="pbc_refer_task" id="pbc_refer_task" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">权重</td>
		<td align="left">
		   <select name="pbc_weights" id="pbc_weights" alt="NotNull" >
		      <option value = "10"> 10% </option>
			  <option value = "20"> 20% </option>
			  <option value = "30"> 30% </option>
			  <option value = "40"/> 40% </option>
			  <option value = "50"/> 50% </option>
			  <option value = "60"/> 60% </option>
			  <option value = "70"/> 70% </option>
			  <option value = "80"/> 80% </option>
			  <option value = "90"/> 90% </option>
		   </select>
		</td>
	</tr>
	<tr>
		<td align="right">考核主体</td>
		<td align="left"><INPUT class=textbox type="textbox" name="pbc_evaluator" id="pbc_evaluator" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">评分规则</td>
		<td align="left">
		  <select name="pbc_rule" id="pbc_rule" alt="NotNull" >
		      <option value = "1"> 0与1 </option>
			  <option value = "2"> 实现程度得分 </option>
			  <option value = "3"> 分层得分 </option>
			  <option value = "4"/> 扣分 </option> 
		  </select>
		</td>
	<tr>
		<td align="right">完成标志</td>
		<td align="left"><INPUT class=textbox type="textbox" name="pbc_end_tag" id="pbc_end_tag" alt="NotNull" /></td>
	</tr>
	</tr>
		<tr>
		<td align="right">计划完成时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="pbc_planned_end_date" id="pbc_planned_end_date" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">备注</td>
		<td align="left"><INPUT class=textbox type="textbox" name="pbc_comment" id="pbc_comment" alt="NotNull" /></td>
	</tr>
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="保存"></input>
		</td>
	</tr>
</table>
<input type="hidden" name="actions" value="insert_pbc" />
</form>

<script>
	var cal = Calendar.setup({
	  onSelect: function(cal) { cal.hide(); }
	});
	cal.manageFields("pbc_planned_end_date", "pbc_planned_end_date", "%Y/%m/%d");
</script>

</center>
</body>
</html>