<?php
require "../common/queries.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<div class="topbody"></div>
<center>
<?php
$sql = "
select * 
from firewall f, user u, department d
where f.f_user_id = u.user_id
and d.depart_id = u.user_depart_id ";

if(isset($_POST['actions']) && $_POST['actions'] == "filter_firewall")
{
	$i=0;
	$where = "";
	//add the user filter
	if(isset($_POST['f_user_id']))
	{
		foreach($_POST['f_user_id'] as $uid)
		{
			$where[$i] = " (u.user_id = '$uid') ";
			$i++;
		}
	}

	//add the depart filter
	if(isset($_POST['depart_id']))
	{
		foreach($_POST['depart_id'] as $depart_id)
		{
			$where[$i] = " (d.depart_id = '$depart_id') ";
			$i++;
		}
	}

	//add the data filter
	if($_POST['f_date_start']!="")
	{
		$where[$i] = " (f.f_date > '".strtotime($_POST['f_date_start'])."') ";
		$i++;
	}

	if($_POST['f_date_end']!="")
	{
		$where[$i] = " (f.f_date < '".strtotime($_POST['f_date_end'])."') ";
		$i++;
	}

	//formate the where
	while($i > 0)
	{
		$sql .= " and ".$where[$i-1];
		$i--;
	}
}

$head = array("ID","用户名","部门","事件","日期","证明人","处罚条款");
$show_col = array("firewall_id","user_name","depart_name","f_content","f_date","f_refer_name","f_rules");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"f_date",false); 
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time
echo listInTable($head,$body,$show_col);
?>
<br><br>
<form action="../common/images.php" method="post" target="_blank">
	<input class=btn type="button" name="filter" value="人员 —— 次数 直方图" onclick="location.href='#'"></input>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input class=btn type="button" name="filter" value="事件 —— 类型 直方图" onclick="location.href='#'"></input>
</form>
</center>