<?php
require_once("../common/session.php");
require_once("../common/functions.php");
header("Content-Type: text/html; charset=utf-8");
//Four kind of pbc user role, refer to the table pbc_user_role
//when pbc_role = 1, it is the person of admin
//when pbc_role = 2, it is the team leader of his department
//when pbc_role = 3, it is the common user, he could not access this page
//when pbc_role = 4, it is the super admin

$month = @$_GET['m'];
$year = @$_GET['y'];
if($month=="")
{
	$month = date('n');
}
if($year=="")
{
	$year = date('Y');
}
$pbc_role = $_SESSION['pbc_role_id'];
$export_link = "?m=$month&y=$year&d=".$_SESSION['depart_id'];
if($pbc_role == 1 || $pbc_role == 4)
{
	$pbc_role = 0;
	$export_link = "?m=$month&y=$year";
}
$sql_common = "SELECT * FROM user u, pbc p, department dp
WHERE MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
AND YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
AND p.pbc_user_id = u.user_id 
AND u.user_active=1
AND u.user_depart_id = dp.depart_id 
AND u.user_id <> ".$_SESSION['user_id'];
$sql[0] = $sql_common;
$sql[2] = $sql_common." AND dp.depart_id=".$_SESSION['depart_id'];
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
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
	<input class=btn type="submit" value='GO'></input>
</form>
<input class=btn type="button" name="export" value="导出到Excel" 
onclick="location.href='../pbc/pbc_export.php<?php echo $export_link;?>'"></input>
</div>
<center>
<?php
//echo($sql["$pbc_role"]);
$head = array("姓名","部门","状态","评分","最后修改时间","修改人");
$show_col = array("user_name","depart_name","pbc_status","pbc_total_grade","pbc_change_time","pbc_change_by");//determin with column will be shown
$body = $db->fetch_all_array($sql["$pbc_role"]);
$body = time2str($body,true,"pbc_change_time");

//create the header
		$col_cnt = count($head);
		for($header = "<tr bgColor='#B0DFEF'>",$i=0;$i<$col_cnt;$i++)
		{
			$header .= "<td><b>".$head[$i]."</b></td>";
		}
		$header .= "</tr>";

		//create the body
		$tr = "";
		foreach ($body as $record)
		{
			$tr .= "<tr>";
			$user_id = $record['user_id'];
			for($i=0;$i<count($show_col);$i++)
			{
				$td_value = $record["$show_col[$i]"];
				
				//parse the status value
				if($show_col[$i] == "pbc_status")
					$td_value = parsePBCStatus($td_value);

				//add the link to show the detail info
				if($show_col[$i] == "user_name")
					$td_value = "<a href=
				'pbc_admin_show.php?m=".$month."&y=".$year."&uid=".$user_id."'>
				".$td_value."</a>";

				$tr .= "<td>".$td_value."</td>";
			}
			$tr .= "</tr>";
		}
		if($tr=="")
		{
			$tr = "<tr><td colspan=$col_cnt align=center>没有记录！</td></tr>";
		}
		echo "<table class=mytable>
			<font size=4><b>".$year."年".$month."月</b></font>
			".$header.$tr."
		</table>";
?>
</center>