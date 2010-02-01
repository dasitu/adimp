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
	$month = date('n');
}
if($year=="")
{
	$year = date('Y');
}
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
<input class=btn type="submit" value='GO'></input>
</form>
</div>
</br>
<center>
<?php
//全部显示
$sql = "select * FROM pbc_data pd, user u, pbc p, pbc_biz_type pbt, department dp
WHERE u.user_id = ".$_SESSION['user_id']." 
and MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
and YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and pd.pbc_id = p.pbc_id
and pd.pbc_biz_type_id = pbt.pbc_biz_type_id
and p.pbc_user_id = u.user_id  
and u.user_depart_id = depart_id
";
$head = array("业务类型","活动分类","活动内容","完成标志","计划完成时间","关联任务","权重","考核主体","评分规则","自评分","评分","备注");
$show_col = array("pbc_biz_type_name","pbc_active_type","pbc_active","pbc_end_tag","pbc_planned_end_date","pbc_refer_task","pbc_weights","pbc_evaluator","pbc_rule","pbc_grade_self","pbc_grade","pbc_comment");//determin with column will be shown
$body = $db->fetch_all_array($sql);
$body = time2str($body,true,"pbc_planned_end_date",false);
//convert the datetime to string, "true" means it is a dataset, "f_date" means the column name, "false" means the datetime format is not inlcude the time

//show the table title
if(@$body[0])
	echo "<font size=4>
			<b>".$body[0]['depart_name'].
			"——".$body[0]['user_name'].
			"——".$year."年".$month."月"."
			</b>
		 </font>";
//show the table itself
?>
<form name="pbcSubmitForm" action="../pbc/actions.php" method="post" >
<table class=mytable>
	<?php
		//check what this page is used for
		// 1. just a view of the data $action = "null"
		// 2. Submit the self evaluate grade of last month $action = "self_evaluate"
		// 3. Submit the pbc of current month $action = "pbc_submit"
		
		$current_pbc_time = @$body[0]['pbc_time'];
		$current_pbc_id   = @$body[0]['pbc_id'];
		$is_evaluate = isSelfCanEvaluate($db,$_SESSION['user_id'],$current_pbc_time);
		$final_btn = "";
		$action = "";

		if($is_evaluate)
		{
			$action = "self_evaluate"; //used to indentify the action in the "../pbc/actions.php"
			$final_btn = "<input class='btn' type='submit' name='submit' value='提交自评分'></input>";
		}

		if (date("n",$current_pbc_time) == date('n'))
		{
			$action = "pbc_submit";
			$is_evaluate = false;
			$final_btn = "
			<input class='btn' type='button' name='back' value='继续录入' onclick=\"location.href='../pbc/pbc.php'\"></input>
			<input class='btn' type='submit' name='submit' value='提交PBC'></input>";
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
			for($i=0;$i<count($show_col);$i++)
			{
				$td_value = $record["$show_col[$i]"];

				//if the month is the used to submit the grade it self, add the input
				if($show_col[$i] == "pbc_grade_self" && $is_evaluate)
				{
					$pbc_data_id = $record['pbc_data_id'];
					$td_value = "<input maxlength=3 size=3 type='textbox' name='pbc_grade_self#$pbc_data_id'></input>";
				}

				//parse the grade rule
				if($show_col[$i] == "pbc_rule")
					$td_value = parseGradeRule($td_value);

				//add the % 
				if($show_col[$i] == "pbc_weights")
					$td_value = $td_value."%";

				$tr .= "<td>".$td_value."</td>";
			}
			$tr .= "</tr>";
		}
		if($tr=="")
		{
			$tr = "<tr><td colspan=$col_cnt align=center>没有记录！</td></tr>";
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