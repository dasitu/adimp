<?php
require_once("../common/session.php");
require "../common/functions.php";
header("Content-Type: text/html; charset=utf-8");
?>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<?php
$user_id = @$_GET['uid'];
@$month = $_GET['m'];
@$year = $_GET['y'];
if($month=="")
	$month = date('n');
if($year=="")
	$year = date('Y');
if($user_id=="")
	$user_id = $_SESSION['user_id'];
?>
<div class="topbody">
<form action='#' method="get">
<?php
echo 
"<input class='btn' type='button' name='back' value='<< 返回列表' onclick=\"location.href='../pbc/pbc_admin.php'\"></input> ";
echo "<input class=btn type='button' onclick=\"location.href='?m=".($month-1)."&uid=".$user_id."'\" value='上个月'></input>";
if($month != date('n',time()) || $year != date('Y',time()) )
	echo "<input class=btn type='button' onclick=\"location.href='?m=".date('n',time())."&uid=$user_id'\" value='当月'></input> ";
if($month < date('n',time()) && $year <= date('Y',time()) )
	echo '<input class=btn type=button onclick="location.href=\'?m='.($month+1).'&uid=$user_id\'" value="下个月"></input> ';
echo "
年份：<input name=y type=textbox size='4' value=".$year."></input>
月份：<input name=m type=textbox size='3'></input>
<input name=uid type=hidden value=".$user_id."></input>
<input class=btn type=submit value='GO'></input>
";
?>
</form>
</div>
</br>
<center>
<?php
//全部显示
$sql = "select * FROM pbc_data pd, user u, pbc p, pbc_biz_type pbt, department dp
WHERE u.user_id = ".$user_id." 
and MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
and YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and pd.pbc_id = p.pbc_id
and pd.pbc_biz_type_id = pbt.pbc_biz_type_id
and p.pbc_user_id = u.user_id  
and u.user_depart_id = depart_id
order by pbt.pbc_biz_type_id
";
//echo $sql."<br>";
$head = array("业务类型","活动分类","活动内容","完成标志","计划完成时间","关联任务","权重","考核主体","评分规则","自评分","评分","备注");
$show_col = array("pbc_biz_type_name","pbc_active_type","pbc_active","pbc_end_tag","pbc_planned_end_date","pbc_refer_task","pbc_weights","pbc_evaluator","pbc_rule","pbc_grade_self","pbc_grade","pbc_comment");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"pbc_planned_end_date",false);
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time

$pbc_status = @$body[0]['pbc_status']; //about the status, please refer to the function parsePBCStatus

$user = getUser($user_id,$db);
	echo "<font size=4>
			<b>".$user['depart_name'].
			"——".$user['user_name'].
			"——".$year."年".$month."月"."
			</b>
		 </font>";
//show the table title
if(@$body[0])
echo "
<br><br>
<div align='left' style='margin-left:25px;'>
<b>上一次更改时间: </b>".time2str(@$body[0]['pbc_change_time'])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>更改人: </b>".@$body[0]['pbc_change_by']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>状态: </b><font color=red>".parsePBCStatus($pbc_status)."</font>
</div>";

//show the table itself
?>
<form name="pbcSubmitForm" action="../pbc/actions.php" method="post" >
<table class=mytable>
	<?php
		//check what this page is used for
		// 1. just a view of the data $action = ""
		// 2. Submit the evaluate grade of last month $action = "pbc_evaluate"
		// 3. Submit the pbc of current month $action = "pbc_approve"
		
		$current_pbc_time = @$body[0]['pbc_time'];
		$current_pbc_id   = @$body[0]['pbc_id'];
		$pbc_reward = @$body[0]['pbc_reward'];
		$admin = 1;
		$is_evaluate = isCanEvaluate($pbc_config,$pbc_status,$current_pbc_time,$admin); //$admin = 1
		$is_submit = isCanSubmitPBC($pbc_config,$pbc_status,$current_pbc_time,$admin);	//#admin = 1
		$final_btn = "";
		$action = "";
		if($is_evaluate)
		{
			$action = "pbc_evaluate"; //used to indentify the action in the "../pbc/actions.php"
			$final_btn = "<input class='btn' type='submit' name='submit' value='提交评分'></input>";
		}

		if ($is_submit)
		{
			$action = "pbc_approve";
			$pbc_reward = "<input name='pbc_reward' type='textbox' maxlength=6 size='4' value=\"$pbc_reward\"></input>";
			$is_evaluate = false;
			$final_btn = "
			<input class='btn' type='submit' name='submit' value='批准PBC'></input>
			<input class='btn' type='button' name='add' value='添加记录' onclick=\"location.href='../pbc/pbc.php?uid=".$user_id."'\">
			";
			array_push($head,'操作');
		}

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
			$pbc_data_id = $record['pbc_data_id'];
			for($i=0;$i<count($show_col);$i++)
			{
				$td_value = $record["$show_col[$i]"];

				//if the month is the used to submit the grade it self, add the input
				if($show_col[$i] == "pbc_grade" && $is_evaluate)
				{
					$pbc_data_id = $record['pbc_data_id'];
					$td_value = "<input maxlength=3 size=3 type='textbox' name='pbc_grade#$pbc_data_id'></input>";
				}

				//parse the grade rule
				if($show_col[$i] == "pbc_rule")
					$td_value = parseGradeRule($td_value);

				//add the % 
				if($show_col[$i] == "pbc_weights")
					$td_value = $td_value."%";

				$tr .= "<td>".$td_value."</td>";
			}

			//add the modify colomn
			if($is_submit)
			{
				$tr.="
					<td>
						<a href='pbc_del.php?id=$pbc_data_id&pbc_id=$current_pbc_id'>删除</a>
						<BR>
						<a href='pbc_modify.php?id=$pbc_data_id&pbc_id=$current_pbc_id&uid=$user_id'>修改</a>
					</td>";
			}
			$tr .= "</tr>";
		}
		if($tr=="")
		{
			$tr = "<tr><td colspan=$col_cnt align=center>没有记录！</td></tr>";
		}
		else
		{
			$tr .= "<tr>
						<td colspan=3 align=right>本月预计绩效奖:</td><td colspan=3>
						$pbc_reward
						</td>
						<td colspan=3 align=right>PBC合计得分:</td><td colspan=3>".$body[0]['pbc_total_grade']."</td>
					</tr>";
		}
		echo $header.$tr."
		</table>
		<input type='hidden' name='pbc_id' value='$current_pbc_id' />
		<input type='hidden' name='actions' value='$action' />
		<br>
		$final_btn";
?>
</form>
</center>