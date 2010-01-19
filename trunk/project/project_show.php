<?php
require "../common/queries.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<div class="topbody"></div>
<center>
<?php
$where = "";
if(isset($_POST['actions']) && $_POST['actions'] == "filter_project")
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

$sql_from = " FROM project p, user u 
where p.project_creator_id = u.user_id ";

//person-count 
$sql_select_p_c = "SELECT count(f.firewall_id) as user_cnt, u.user_name";
$sql_select_p_c = $sql_select_p_c.$sql_from.$where." GROUP by u.user_name";
//echo $sql_select_p_c."<br>";

//type-count
$sql_select_t_c = "SELECT count(f.firewall_id) as t_type_cnt, t.f_c_type_name";
$sql_select_t_c = $sql_select_t_c.$sql_from.$where." GROUP by t.f_c_type_name";
//echo $sql_select_t_c;

//show part
$sql_select = " select * ";
$sql = $sql_select.$sql_from.$where;
$head = array("ID","项目名称","项目代号","创建人","创建时间");
$show_col = array("project_id","project_name","project_no","user_name","project_create_date");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"project_create_date",false); 
//convert the datetime to string, "true" means it is a dataset, "project_creat_date" means the column name, "false" means the datetime format is not inlcude the time

echo listInTable($head,$body,$show_col);
?>
</center>