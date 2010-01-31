<?php
session_start();
require "../common/functions.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<?php
$where = "";
@$month = $_GET['m'];
@$year = $_GET['y'];
if($month=="")
{
	$month = date('n',time());
}
if($year=="")
{
	$year = date('Y',time());
}

if(isset($_POST['actions']) && $_POST['actions'] == "filter_pbc")
{
	$and_cnt = 0;
	$i=0;
	$where_and = array();

	//add the user filter
	if(isset($_POST['trip_user_id']))
	{
		$where_and[$and_cnt] = " ( ";
		foreach($_POST['trip_user_id'] as $uid)
		{
			if($i==0)
				$where_and[$and_cnt] .= " (t.trip_user_id = '$uid') ";
			else
				$where_and[$and_cnt] .= " or (t.trip_user_id = '$uid') ";
			$i++;
		}
		$where_and[$and_cnt] .= ' ) ';
		$and_cnt++;
		$i=0;
	}

	//add the project filter
	if(isset($_POST['trip_project_id']))
	{
		$where_and[$and_cnt] = " ( ";
		foreach($_POST['trip_project_id'] as $trip_project_id)
		{
			if($i==0)
				$where_and[$and_cnt] .= " (t.trip_project_id = '$trip_project_id') ";
			else
				$where_and[$and_cnt] .= " or (t.trip_project_id = '$trip_project_id')";
			$i++;
		}
		$where_and[$and_cnt] .= ' ) ';
		$and_cnt++;
		$i=0;
	}
	
	//add the trip type filter
	if(isset($_POST['trip_type_id']))
	{
		$where_and[$and_cnt] = " ( ";
		foreach($_POST['trip_type_id'] as $trip_type_id)
		{
			if($i==0)
				$where_and[$and_cnt] .= " (t.trip_type_id = '$trip_type_id') ";
			else
				$where_and[$and_cnt] .= " or (t.trip_type_id = '$trip_type_id')";
			$i++;
		}
		$where_and[$and_cnt] .= ' ) ';
		$and_cnt++;
		$i=0;
	}

	//add the leaving date time filter
	if($_POST['trip_leaving_date_start']!="")
	{
		$where_and[$and_cnt] = " t.trip_leaving_date > '".strtotime($_POST['trip_leaving_date_start'])."' ";
		$and_cnt++;
	}

	if($_POST['trip_leaving_date_end']!="")
	{
		$where_and[$and_cnt]= " t.trip_leaving_date < '".strtotime($_POST['trip_leaving_date_end'])."' ";
		$and_cnt++;
	}

	//formate the where
	while($and_cnt > 0)
	{
		$where .= " and ".$where_and[$and_cnt-1];
		$and_cnt--;
	}
}

$sql_from = " FROM pbc_data pd, user u, pbc p, pbc_biz_type pbt, department dp
WHERE u.user_id = ".$_SESSION['user_id']." 
and MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
and YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and pd.pbc_id = p.pbc_id
and pd.pbc_biz_type_id = pbt.pbc_biz_type_id
and p.pbc_user_id = u.user_id  
and u.user_depart_id = depart_id
";

//人员-出差天数
$sql_select_p_d = "select u.user_name, sum(t.trip_day_off) as day_off ";
$sql_select_p_d = $sql_select_p_d.$sql_from.$where." GROUP by u.user_name";
//echo $sql_select_p_d."<br>";

//项目代号-出差天数
$sql_select_pj_d = "select p.project_no, sum(t.trip_day_off) as day_off ";
$sql_select_pj_d = $sql_select_pj_d.$sql_from.$where." GROUP by p.project_no";
//echo $sql_select_pj_d."<br>";

//人员-费用
$sql_select_p_f = "select u.user_name, sum(t.trip_fee) as fee ";
$sql_select_p_f = $sql_select_p_f.$sql_from.$where." GROUP by u.user_name";
//echo $sql_select_p_f."<br>";

//项目代号-费用
$sql_select_pj_f = "select p.project_no, sum(t.trip_fee) as fee ";
$sql_select_pj_f = $sql_select_pj_f.$sql_from.$where." GROUP by p.project_no";
//echo $sql_select_pj_f."<br>";

//全部显示
$sql_select = " select * ";
$sql = $sql_select.$sql_from.$where;
$head = array("业务类型","活动分类","活动内容","完成标志","计划完成时间","关联任务","权重(%)","考核主体","评分规则","自评分","评分","备注");
$show_col = array("pbc_biz_type_name","pbc_active_type","pbc_active","pbc_end_tag","pbc_planned_end_date","pbc_refer_task","pbc_weights","pbc_evaluator","pbc_rule","pbc_grade_self","pbc_grade","pbc_comment");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"pbc_planned_end_date",false);
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time
?>
<div class="topbody">
<form action='#' method="get">
<input class=btn type='button' onclick="location.href='?m=<?php echo $month-1;?>'" value="上个月"></input>
<?php
if($month != date('n',time()) || $year != date('Y',time()) )
	echo "<input class=btn type='button' onclick=\"location.href='?m=".date('n',time())."'\" value='当月'></input> ";
if($month < date('n',time()) && $year <= date('Y',time()) )
	echo '<input class=btn type=button onclick="location.href=\'?m=' .($month+1). '\'" value="下个月"></input> ';
?>
年份：<input name=y type=textbox size='4' value=<?php echo $year?>></input>
月份：<input name=m type=textbox size='3'></input>
<input type="submit" value='go'></input>
</form>
</div>
</br>
<center>
<?php 
if(@$body[0])
echo "<font size=4><b>".$body[0]['depart_name']."——".$body[0]['user_name']."——".$year."年".$month."月"."</b></font><br>";
echo listInTable($head,$body,$show_col);
?>
</center>