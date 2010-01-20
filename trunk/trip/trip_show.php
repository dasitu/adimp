<?php
require "../common/queries.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<?php
$where = "";

/*
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
		$where .= " and ".$where_and[$and_cnt-1];
		$and_cnt--;
	}
}
*/
$sql_from = " FROM trip t, trip_type tt, user u, project p, upfile, uf
WHERE t.trip_type_id = tt.trip_type_id 
AND t.trip_user_id = u.user_id 
AND t.trip_project_id = p.project_id 
AND t.trip_report_doc_id = uf.upfile_id 
";

//show part
$sql_select = " select * ";
$sql = $sql_select.$sql_from.$where;
$head = array("ID","人员","项目代号","任务类型","出差时间","回所时间","出差天数","出差地点","派差单位","派差单位","派差人员","差旅费用","完成情况","联系人","联系方式","出差报告");
$show_col = array("trip_id","user_name","depart_name","f_content","f_c_type_name","f_date","f_refer_name","f_rules");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"f_date",false); 
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time
$body = addDownloadLink($body); //add the last image link for download files, the column name is "doc_link"
?>
<div class="topbody">
<table>
	<tr>
		<td height="30">
			<?php echo chartButton($sql_select_p_c,'人员——次数 直方图','bar','user_name','user_cnt');?>
		</td>
		<td>
			<?php echo chartButton($sql_select_t_c,'类型——次数 饼图','pie','f_c_type_name','t_type_cnt');?>
		</td>
	</tr>
</table>
</div>
</br>
<center><?php echo listInTable($head,$body,$show_col);?></center>