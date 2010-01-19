<?php
require "../common/queries.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<?php
$where = "";
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

$sql_from = " FROM firewall f, user u, department d, firewall_content_type t
WHERE f.f_user_id = u.user_id
AND d.depart_id = u.user_depart_id
AND f.f_type_id = t.f_c_type_id
";

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
$head = array("ID","用户名","部门","事件","事件类型","日期","证明人","处罚条款");
$show_col = array("firewall_id","user_name","depart_name","f_content","f_c_type_name","f_date","f_refer_name","f_rules");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"f_date",false); 
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time
?>
<div class="topbody">
<table>
	<tr>
		<td height="30">
			<form action="../common/image.php" method="post">
				<input type="hidden" name="sql" value = "<?php echo $sql_select_p_c;?>"></input>
				<input class=btn type="submit" name="submit" value="人员——次数 直方图"></input>
				<input type="hidden" name="draw_type" value="bar"></input>
				<input type="hidden" name="x_name" value="user_name"></input>
				<input type="hidden" name="y_name" value="user_cnt"></input>
			</form>
		</td>
		<td>
			<form action="../common/image.php" method="post">
				<input type="hidden" name="sql" value = "<?php echo $sql_select_t_c;?>"></input>
				<input class=btn type="submit" name="submit" value="类型——次数 饼图"></input>
				<input type="hidden" name="draw_type" value="pie"></input>
				<input type="hidden" name="x_name" value="f_c_type_name"></input>
				<input type="hidden" name="y_name" value="t_type_cnt"></input>
			</form>
		</td>
	</tr>
</table>
</div>
</br>
<center><?php echo listInTable($head,$body,$show_col);?></center>