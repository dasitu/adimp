<?php
require_once("../common/session.php");
require "../common/xajax_server.php";
require_once ("../common/functions.php");
header("Content-Type: text/html; charset=utf-8");
$xajax->printJavascript();

$user_id = $_GET['uid'];
if($user_id=="")
	$user_id = $_SESSION['user_id'];

$pbc_data_id = $_GET['id'];
$pbc_id = $_GET['pbc_id'];
$sql = "select * from pbc_data where pbc_data_id = '".$pbc_data_id."'";
$data = $db->query_first($sql);
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
			AND		u.user_id = ".$user_id;
			$selected_value = $data['pbc_biz_type_id'];
			echo listSelection($db,$table_name,$col_name,$col_value,$where,$selected_value);
			?>
		</select>
		</td>
	</tr>
	<tr>
		<td align="right">活动分类</td>
		<td align="left">
		<div id="pbc_active_type">
			<input class=textbox name='pbc_active_type' 
			value="<?php echo $data['pbc_active_type'];?>">
			</input>
		</div>
		</td>
	</tr>
	<tr>
		<td align="right">活动内容</td>
		<td align="left">
			<input class=textbox name="pbc_active" id="pbc_active" alt="NotNull" 
			value="<?php echo $data['pbc_active'];?>">
			</input>
		</td>
	</tr>
	<tr>
		<td align="right">关联任务</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="pbc_refer_task" id="pbc_refer_task" alt="NotNull" 
			value="<?php echo $data['pbc_refer_task'];?>">
		</input>
		</td>
	</tr>
	<tr>
		<td align="right">权重</td>
		<td align="left">
		   <select name="pbc_weights" id="pbc_weights" alt="NotNull" >
			   <?php
			   
			   for($i=10;$i<100;$i=$i+10)
			   {
					$select = "";
					if($i == $data['pbc_weights'])
						$select = "selected";
					echo "<option value = '$i' $select> ".$i."% </option>";
			   }
			   ?>
		   </select>
		</td>
	</tr>
	<tr>
		<td align="right">考核主体</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="pbc_evaluator" id="pbc_evaluator" alt="NotNull" 
			value="<?php echo $data['pbc_evaluator'];?>" />
		</td>
	</tr>
	<tr>
		<td align="right">评分规则</td>
		<td align="left">
		  <select name="pbc_rule" id="pbc_rule" alt="NotNull" >
			  <?php 
			  for($i=1;$i<5;$i++)
			  {
				$rule_value = parseGradeRule("$i");
				if($i == $data['pbc_rule'])
					echo " <option value = ".$i." selected> ".$rule_value." </option>";
				else
					echo "<option value = ".$i."> ".$rule_value." </option>";
			  }
			  ?>
		  </select>
		</td>
	<tr>
		<td align="right">完成标志</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="pbc_end_tag" id="pbc_end_tag" alt="NotNull" 
			value="<?php echo $data['pbc_end_tag'];?>" />
		</td>
	</tr>
	</tr>
		<tr>
		<td align="right">计划完成时间</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="pbc_planned_end_date" id="pbc_planned_end_date" alt="NotNull" value ="<?php echo time2str($data['pbc_planned_end_date'],false,"",false);?>" />
		</td>
	</tr>
	<tr>
		<td align="right">备注</td>
		<td align="left">
		<INPUT class=textbox type="textbox" name="pbc_comment" id="pbc_comment" alt="NotNull" 
		value="<?php echo $data['pbc_comment'];?>"/>
	</tr>
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="保存"></input>
		<input class=btn type="button" name="back" value="取消" onclick="javascript:history.back();" />
		</td>
	</tr>
</table>
<input type="hidden" name="actions" value="modify_pbc" />
<input type="hidden" name="pbc_data_id" value="<?php echo $pbc_data_id;?>" />
<input type="hidden" name="pbc_id" value="<?php echo $pbc_id;?>" />
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