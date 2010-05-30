<?php
require "../common/functions.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<?php
$where = "";

if(isset($_POST['actions']) && $_POST['actions'] == "filter_trip")
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

$sql_from = " FROM trip t, trip_type tt, user u, project p, upfiles uf 
WHERE t.trip_type_id = tt.trip_type_id 
AND t.trip_user_id = u.user_id 
AND t.trip_project_id = p.project_id 
AND t.trip_report_doc_id = uf.upfile_id 
AND u.user_active=1 
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
$head = array("人员","项目代号","任务类型","出差时间","回所时间","出差天数","出差地点","派差单位","派差人员","差旅费用","完成情况","联系人","联系方式","出差报告");
$show_col = array("user_name","project_no","trip_type_name","trip_leaving_date","trip_back_date","trip_day_off","trip_location","trip_sender_depart","trip_sender","trip_fee","trip_result","trip_contact","trip_phone","doc_link");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"trip_leaving_date",false);
$body = time2str($body,true,"trip_back_date",false);
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time
$body = addDownloadLink($body); //add the last image link for download files, the column name is "doc_link"
?>
<div class="topbody">
<table>
	<tr>
		<td>
			<?php echo chartButton($sql_select_p_d,'人员——天数 直方图','bar','user_name','day_off');?>
		</td>
		<td>
			<?php echo chartButton($sql_select_p_f,'人员——费用 直方图','bar','user_name','fee');?>
		</td>
		<td>
			<?php echo chartButton($sql_select_pj_d,'项目——天数 直方图','bar','project_no','day_off');?>
		</td>
		<td>
			<?php echo chartButton($sql_select_pj_f,'项目——费用 直方图','bar','project_no','fee');?>
		</td>
	</tr>
</table>
</div>
</br>
<center><?php echo listInTable($head,$body,$show_col);?></center>