<?php
require "../common/functions.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<?php
$where = "";
if(isset($_POST['actions']) && $_POST['actions'] == "filter_pbc")
{
	$and_cnt = 0;
	$i=0;
	$where_and = array();

	//add the user filter
	if(isset($_POST['pbc_user_id']))
	{
		$where_and[$and_cnt] = " ( ";
		foreach($_POST['pbc_user_id'] as $uid)
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
	if($_POST['pbc_date_start']!="")
	{
		$where_and[$and_cnt] = " p.pbc_time > '".strtotime($_POST['pbc_date_start'])."' ";
		$and_cnt++;
	}

	if($_POST['pbc_date_end']!="")
	{
		$where_and[$and_cnt]= " p.pbc_time < '".strtotime($_POST['pbc_date_end'])."' ";
		$and_cnt++;
	}

	//formate the where
	while($and_cnt > 0)
	{
		$where .= " and ".$where_and[$and_cnt-1];
		$and_cnt--;
	}
}

$sql_from = " FROM pbc p, user u, department d 
WHERE p.pbc_user_id = u.user_id
AND d.depart_id = u.user_depart_id
";

//person-count 
$sql_select_p_c = "SELECT count(p.pbc_user_id) as user_cnt, u.user_name";
$sql_select_p_c = $sql_select_p_c.$sql_from.$where." GROUP by u.user_name";
//echo $sql_select_p_c."<br>";

//time-count
$sql_select_t_c = "SELECT count(p.pbc_time) as time_cnt, p.pbc_time";
$sql_select_t_c = $sql_select_t_c.$sql_from.$where." GROUP by p.pbc_time";
//echo $sql_select_t_c;

//show part
$sql_select = " select * ";
$sql = $sql_select.$sql_from.$where;
$head = array("ID","姓名","部门","时间","总分");
$show_col = array("pbc_id","user_name","depart_name","pbc_time","pbc_total_grade");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"pbc_time",false); 
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time
?>
<div class="topbody">
<table>
	<tr>
		<td height="30">
			<?php echo chartButton($sql_select_p_c,'人员——时间——分数 直方图','bar','user_name','user_cnt');?>
		</td>
		<td>
			<?php echo chartButton($sql_select_t_c,'类型——次数 饼图','pie','pbc_time','time_cnt');?>
		</td>
	</tr>
</table>
</div>
</br>
<center><?php echo listInTable($head,$body,$show_col);?></center>