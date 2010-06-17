<?php
require_once("../common/session.php");
require "../common/functions.php";
require "../common/header.php";
$user_id = @$_SESSION['user_id'];
@$month = $_GET['m'];
@$year = $_GET['y'];
if($month=="")
	$month = date('n');
if($year=="")
	$year = date('Y');
?>
<div class="topbody">
<form action='#' method="get">
<?php
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
AND u.user_active=1
order by pbt.pbc_biz_type_id
";
//echo $sql."<br>";
$head = array("业务类型","活动分类","活动内容","完成标志","计划完成时间","关联任务","权重","考核主体","评分规则","自评分","评分","备注","评分意见");
$show_col = array("pbc_biz_type_name","pbc_active_type","pbc_active","pbc_end_tag","pbc_planned_end_date","pbc_refer_task","pbc_weights","pbc_evaluator","pbc_rule","pbc_grade_self","pbc_grade","pbc_comment","pbc_advice");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"pbc_planned_end_date",false);
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time

$final_btn = "";
$action = "";
$current_pbc_id = "";
$modify_td = "";
$update_str = "";
if(@$body[0])
{
	$pbc_status = @$body[0]['pbc_status'];
	$update_str = "
		<div align='left' style='margin-left:25px;'>
		<b>上一次更改时间: </b>".time2str(@$body[0]['pbc_change_time'])."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>更改人: </b>".@$body[0]['pbc_change_by']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<b>状态: </b><font color=red>".parsePBCStatus($pbc_status)."</font>
		</div>";

	//check what this page is used for
	// 1. just a view of the data $action = "null"
	// 2. Submit the self evaluate grade of last month $action = "self_evaluate"
	// 3. Submit the pbc of current month $action = "pbc_submit"
	$current_pbc_time = @$body[0]['pbc_time'];
	$current_pbc_id   = @$body[0]['pbc_id'];
	$pbc_reward = @$body[0]['pbc_reward'];
	$is_evaluate = isCanEvaluate($pbc_config,$pbc_status,$current_pbc_time);
	$is_submit = isCanSubmitPBC($pbc_config,$pbc_status,$current_pbc_time);

	if($is_evaluate)
	{
		$action = "self_evaluate"; //used to indentify the action in the "../pbc/actions.php"
		$final_btn = "
		<input class='btn' type='button' name='back' value='继续录入' onclick=\"location.href='../pbc/pbc.php'\"></input>
		<input class='btn' type='submit' name='submit' value='提交自评分'></input>";
		array_push($head,'操作');
	}

	if ($is_submit)
	{
		$action = "pbc_submit";
		$pbc_reward = "<input name='pbc_reward' type='textbox' maxlength=6 size='4'></input>";
		$is_evaluate = false;
		$final_btn = "
		<input class='btn' type='button' name='back' value='继续录入' onclick=\"location.href='../pbc/pbc.php'\"></input>
		<input class='btn' type='submit' name='submit' value='提交PBC'></input>";
		array_push($head,'操作');
	}
}
?>
<input class=btn type="button" name="export" value="导出到Excel" onclick="location.href='../pbc/pbc_export.php?m=<?php echo $month;?>&y=<?php echo $year?>&u=<?php echo $user_id?>'"></input>
&nbsp;&nbsp;&nbsp;
<input class=btn type="button" name="export" value="查看历史" onclick="location.href='../pbc/pbc_log.php?id=<?php echo $current_pbc_id;?>'"></input>
</div>
</br>
<center>
<?php
//show the table title
	echo "<font size=4>
			<b>".$_SESSION['depart_name'].
			"——".$_SESSION['user_name'].
			"——".$year."年".$month."月"."
			</b>
		 </font>
<br><br>";
echo $update_str;
?>
<form name="pbcSubmitForm" action="../pbc/actions.php" method="post" 
onsubmit="return checkPercent(this.total_percent.value)">
<table class=mytable>
	<?php
		//create the header
		$col_cnt = count($head);
		for($header = "<tr bgColor='#B0DFEF'>",$i=0;$i<$col_cnt;$i++)
		{
			$header .= "<td><b>".$head[$i]."</b></td>";
		}
		$header .= "</tr>";

		//create the body
		$tr = "";
		$total_percent = 0;
		foreach ($body as $record)
		{
			$pbc_data_id = $record['pbc_data_id'];
			$tr .= "<tr>";
			for($i=0;$i<count($show_col);$i++)
			{
				$td_value = $record["$show_col[$i]"];

				//if the month is the used to submit the grade it self, add the input
				if($show_col[$i] == "pbc_grade_self" && $is_evaluate)
				{
					$td_value = "<input maxlength=3 size=3 type='textbox' name='pbc_grade_self#$pbc_data_id'></input>";
				}

				//parse the grade rule
				if($show_col[$i] == "pbc_rule")
					$td_value = parseGradeRule($td_value);

				//add the % 
				if($show_col[$i] == "pbc_weights")
				{
					$td_value = $td_value."%";
					$total_percent += $td_value;
				}
				$tr .= "<td>".$td_value."</td>";
			}
			if($is_submit || $pbc_status=='approved')
			{
				$tr.="
					<td>
						<a href='pbc_del.php?id=$pbc_data_id&pbc_id=$current_pbc_id'>删除</a>
						<BR>
						<a href='pbc_modify.php?id=$pbc_data_id&pbc_id=$current_pbc_id'>修改</a>
					</td>";
			}
			$tr .= "</tr>";
		}
		if($tr=="")
		{
			$tr = "<tr><td colspan=$col_cnt align=center>没有记录！</td></tr>";
			if(date('n',determinPbcInsertTime($pbc_config))==$month)
				$final_btn .= "<input class='btn' type='button' name='back' value='录入' onclick=\"location.href='../pbc/pbc.php'\"></input>";
		}
		else
		{
			$tr .= "<tr>
						<td colspan=3 align=right>本月预计绩效奖:</td><td colspan=3>
						$pbc_reward
						</td>
						<td colspan=3 align=right>PBC合计得分:</td><td colspan=5>".$body[0]['pbc_total_grade']."</td>
					</tr>";
		}
		echo $header.$tr."
		</table>
		<input type='hidden' name='pbc_id' value='$current_pbc_id' />
		<input type='hidden' name='actions' value='$action' />
		<input type='hidden' name='total_percent' value='$total_percent' />
		<br>
		$final_btn";
?>
</form>
</center>