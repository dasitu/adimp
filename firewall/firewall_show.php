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
	$and_cnt = 0;
	$i=0;
	$where_and = array();

	//add the user filter
	if(isset($_POST['f_user_id']))
	{
		$where_and[$and_cnt] = " ( ";
		foreach($_POST['f_user_id'] as $uid)
		{
			if($i==0)
				$where_and[$and_cnt] .= " (u.user_id = '$uid') ";
			else
				$where_and[$and_cnt] .= " or (u.user_id = '$uid') ";
			$i++;
		}
		$where_and[$and_cnt] .= ' ) ';
		$and_cnt++;
		$i=0;
	}

	//add the depart filter
	if(isset($_POST['depart_id']))
	{
		$where_and[$and_cnt] = " ( ";
		foreach($_POST['depart_id'] as $depart_id)
		{
			if($i==0)
				$where_and[$and_cnt] .= " (d.depart_id = '$depart_id') ";
			else
				$where_and[$and_cnt] .= " or (d.depart_id = '$depart_id')";
			$i++;
		}
		$where_and[$and_cnt] .= ' ) ';
		$and_cnt++;
		$i=0;
	}

	//add the data filter
	if($_POST['f_date_start']!="")
	{
		$where_and[$and_cnt] = " f.f_date > '".strtotime($_POST['f_date_start'])."' ";
		$and_cnt++;
	}

	if($_POST['f_date_end']!="")
	{
		$where_and[$and_cnt]= " f.f_date < '".strtotime($_POST['f_date_end'])."' ";
		$and_cnt++;
	}

	//formate the where
	while($and_cnt > 0)
	{
		$sql .= " and ".$where_and[$and_cnt-1];
		$and_cnt--;
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
<form action="../common/image.php" method="post" target="_blank">
	<input class=btn type="submit" name="filter" value="人员 —— 次数 直方图" onclick="location.href='#'"></input>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input class=btn type="submit" name="filter" value="事件 —— 类型 直方图" onclick="location.href='#'"></input>
</form>
</center>