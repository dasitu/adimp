<?php
require_once("../common/session.php");
require "../common/functions.php";
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
<div class="topbody">
	<input class=btn type="button" name="filter" value="筛选数据" onclick="location.href='../trip/trip_search.php'"></input>
	&nbsp;&nbsp;
	<input class=btn type="button" name="filter" value="查看记录" onclick="location.href='../trip/trip_show.php'"></input>
</div>
<center>
<!-- user input form -->
<form name="tripForm" enctype="multipart/form-data" action="../trip/actions.php" method="post" onsubmit="return checkNull(this);">
<table class="mytable">
	<tr>
		<td align="right">出差人员</td>
		<td align="left">
		<?php echo $_SESSION['user_name']?>
		<INPUT type="hidden" name="trip_user_id" id="trip_user_id" value=<?php echo $_SESSION['user_id']?> />
		</td>
	</tr>
	<tr>
		<td align="right">项目代号</td>
		<td align="left">
		<select name="trip_project_id" id="trip_project_id" alt="NotNull">
				<?php 
				//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
				echo listSelection($db,"project","project_no","project_id");
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">任务类型</td>
		<td align="left">
			<select name="trip_type_id" id="trip_type_id" alt="NotNull">
				<?php 
				//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
				echo listSelection($db,"trip_type","trip_type_name","trip_type_id");
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">出差时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_leaving_date" id="trip_leaving_date" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">回所时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_back_date" id="trip_back_date" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">出差天数</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_day_off" id="trip_day_off" alt="NotNull" readonly /></td>
	</tr>
	<tr>
		<td align="right">出差地点</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_location" id="trip_location" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">派差单位</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_sender_depart" id="trip_sender_depart" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">派差人员</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_sender" id="trip_sender" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">差旅费用</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_fee" id="trip_fee" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">完成情况</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_result" id="trip_result" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">联系人</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_contact" id="trip_contact" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">联系方式</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_phone" id="trip_phone" alt="NotNull" /></td>
	</tr>
	<tr>
		<td align="right">出差报告</td>
		<td align="left"><INPUT type="file" name="upfile" id="upfile" alt="NotNull" /></td>
	</tr>
	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交"></input>
		</td>
	</tr>
</table>
<input type="hidden" name="actions" value="insert_trip" />
</form>

<script>
	  var cal = Calendar.setup({
		  onSelect: function(cal) { cal.hide(); getDateDiff('trip_leaving_date','trip_back_date','trip_day_off'); }
	  });
	cal.manageFields("trip_back_date", "trip_back_date", "%Y/%m/%d");
	cal.manageFields("trip_leaving_date", "trip_leaving_date", "%Y/%m/%d");
</script>
</center>
</body>
</html>