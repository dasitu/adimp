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
<div class="topbody"></div>
<center>

<!-- user input form -->
筛选数据
<form name="pbcForm" action="../pbc/pbc_search_show.php" method="post" >
<table class="mytable">
	<tr>
		<td align="right">姓名</td>
		<td align="left">
		
		<select name="pbc_user_id[]" id="pbc_user_id" SIZE="6" multiple>
		<?php 
		//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
		echo listSelection($db,"user","user_name","user_id","where user_active=1");
		?>
		</select>
		</td>
	</tr>
	<tr>
		<td align="right">所在工程组</td>
		<td align="left">
		<select name="depart_id[]" id="depart_id" multiple>
			<?php 
			//$db, $table_name, $clo_name:column name that you want to list, $col_value:values that you want to add
			echo listSelection($db,"department","depart_name","depart_id","where depart_active=1");
			?>
		</select>
		</td>
	</tr>

	<tr>
		<td align="right">起始时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="pbc_date_start" id="pbc_date_start"/></td>
	</tr>
	
	<tr>
		<td align="right">结束时间</td>
		<td align="left"><INPUT class=textbox type="textbox" name="pbc_date_end" id="pbc_date_end"/></td>
	</tr>

	<tr>
		<td colSpan="2" align="center">
		<input class=btn type="submit" name="submit" value="提交筛选"></input>
		</td>
	</tr>
</table>
<input type="hidden" name="actions" value="filter_pbc" />
</form>

<script>
  var cal = Calendar.setup({
	  onSelect: function(cal) { cal.hide() }
  });
	cal.manageFields("pbc_date_start", "pbc_date_start", "%Y/%m/%d");
	cal.manageFields("pbc_date_end", "pbc_date_end", "%Y/%m/%d");
</script>
</center>
</body>
</html>