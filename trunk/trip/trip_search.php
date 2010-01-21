<?php
session_start();
require "../common/functions.php";
header("Content-Type: text/html; charset=utf-8");
?>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../css/jscal2/border-radius.css" />
	<script src="../js/main.js" type='text/javascript'></script>
    <script type="text/javascript" src="../js/jscal2.js"></script>
    <script type="text/javascript" src="../js/lang/cn.js"></script>
  </head>
<body>
<div class="topbody"></div>
<center>

<!-- user input form -->
<form name="tripForm" action="../trip/trip_show.php" method="post" >
<table class="mytable">
	<tr>
		<td align="right">姓名</td>
		<td align="left">
		
		<select name="trip_user_id[]" id="trip_user_id" SIZE="6" multiple>
		<?php 
		//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
		echo listSelection($db,"user","user_name","user_id");
		?>
		</select>
		</td>
	</tr>
	
	<tr>
		<td align="right">项目代号</td>
		<td align="left">
		<select name="trip_project_id[]" id="trip_project_id" multiple>
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
		<select name="trip_type_id[]" id="trip_type_id" multiple>
			<?php 
			//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
			echo listSelection($db,"trip_type","trip_type_name","trip_type_id");
			?>
		</select>
		</td>
	</tr>
	
	<tr>
		<td align="right">起始时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_leaving_date_start" id="trip_leaving_date_start"/></td>
	</tr>
	
	<tr>
		<td align="right">结束时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="trip_leaving_date_end" id="trip_leaving_date_end"/></td>
	</tr>

	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交筛选"></input>
		</td>
	</tr>
</table>
<input type="hidden" name="actions" value="filter_trip" />
</form>

<script>
  var cal = Calendar.setup({
	  onSelect: function(cal) { cal.hide() }
  });
	cal.manageFields("trip_leaving_date_start", "trip_leaving_date_start", "%Y/%m/%d");
	cal.manageFields("trip_leaving_date_end", "trip_leaving_date_end", "%Y/%m/%d");
</script>
</center>
</body>
</html>