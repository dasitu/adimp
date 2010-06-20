<?php
require_once "../lib/database.class.php";
require_once "../common/config.inc.php";

$db = new database();
$db->connect();
//*********************************println********************************//
function echoln($msg)
{
	echo $msg."<br>";
}
//************************************************************************//

//***********rule for check which pbc month the insert will be************//
function determinPbcInsertTime($pbc_config)
{
	$current_month = date('n');
	$current_day = date('d');
	$start_insert = explode('#',$pbc_config['start_submit_day']);
	$last_insert = explode('#',$pbc_config['last_submit_day']);
	if($current_day > $last_insert[1] && $current_day > $start_insert[1])
	{
		return strtotime('+1 month');
	}
	else if($current_day <= $last_insert[1])
	{
		if(($start_insert[0]==0 && $current_day > $start_insert[1]) || $start_insert[0]==-1)
			return time();
	}
	return false;
}
//************************************************************************//

//**********rule for check the user can evaluate the grade of itself******//
function isCanEvaluate($pbc_config,$pbc_status,$pbc_time,$admin=0)
{
	$pbc_month = date('n',$pbc_time);
	$start_evaluate = explode('#',$pbc_config['start_evaluate_day']); 
	$last_evaluate = explode('#',$pbc_config['last_evaluate_day']); 
	
	//this is for group manager to evaluate
	if($admin==1) 
	{
		$start_evaluate = explode('#',$pbc_config['start_score_day']); 
		$last_evaluate = explode('#',$pbc_config['last_score_day']);	
	}
	
	//echoln('e1');
	//check the time range
	$start_date = mktime(0,0,0,($pbc_month+$start_evaluate[0]),$start_evaluate[1]);//the 1st day of the pbc month
	$end_date = mktime(23,59,59,($pbc_month+$last_evaluate[0]),$last_evaluate[1]);//the 1st day of the pbc month
	$current = time();

	//echo date('n-d',$start_date);
    //echo date('n-d',$end_date);
	if($current >= $start_date && $current <= $end_date)
	{
		//echoln('e3');
		//check the status of pbc
		//pbc process "initial->submitted->approved->self_scored->scored->closed"
		if($admin==0 && $pbc_status == "approved")
			return true;
		if($admin==1 && $pbc_status == "self_scored")
			return true;
	}
	
	return false;
}
//************************************************************************//

//***********check the condition of submit the current PBC ***************//
function isCanSubmitPBC($pbc_config,$pbc_status,$pbc_time,$admin=0)
{
	$pbc_month = date('n',$pbc_time);
	$start_submit = explode('#',$pbc_config['start_submit_day']); 
	$last_submit = explode('#',$pbc_config['last_submit_day']);
	if($admin==1)
	{
		$start_submit = explode('#',$pbc_config['start_approve_day']); 
		$last_submit = explode('#',$pbc_config['last_approve_day']);	
	}
	//echoln('s1');
	//check the time range
	$start_date = mktime(0,0,0,($pbc_month+$start_submit[0]),$start_submit[1]);//the 1st day of the pbc month
	$end_date = mktime(23,59,59,($pbc_month+$last_submit[0]),$last_submit[1]);//the 1st day of the pbc month
	$current = time();
	if($current >= $start_date && $current <= $end_date)
	{
		//echoln('s3');
		//check the current status of pbc
		//pbc process "initial->submitted->approved->self_scored->scored->closed"
		if($admin==0 && $pbc_status == "initial")
			return true;
		if($admin==1 && $pbc_status == "submitted")
			return true;
	}
	return false;
}
//************************************************************************//

//*****************************login**************************************//
function login($db,$login,$pass)
{
	$sql = "select u.*,d.depart_name from user u, department d where u.user_login='$login' and u.user_pwd=password('$pass') and u.user_depart_id=d.depart_id and u.user_active=1 ";
	$user = $db->query_first($sql);
	if($user){
		return $user;
	}
	return false;
}
//************************************************************************//

//*****************insert one user to table user*************************//
function insertUser($db,$user)
{
	print $db->query_insert("user",$user);
	print "<BR>";
}
//***********************************************************************//


//********give the options for selectbox according to the parameters****//
//*************************return html code****************************//
function listSelection($db,$table_name,$col_name,$col_value,$where="",$selected="impossible_value")
{
	$sql = "select $col_name,$col_value from $table_name $where";
	$rs = $db->fetch_all_array($sql);
	$options = "";

	//if the col_value used in multi-table queries, it will contain the table name before '.' like "user.username",
	// but when you use the name in the array, it should be "username", so do the things followed.
	$col_value = strrchr($col_value, ".")? str_replace('.','', strrchr($col_value, ".")) : $col_value;
	$col_name = strrchr($col_name, ".")? str_replace('.','', strrchr($col_name, ".")) : $col_name;
	
	foreach ($rs as $record)
	{
		$select = "";
		if($record["$col_value"] == $selected)
			$select = "selected";
		$options .= "<option value='".$record["$col_value"]."' $select>".$record["$col_name"]."</option>";
	}
	return $options;
}
//**********************************************************************//


//************get the upfile info, the return value is an array**********//
function getFileInfo($db,$file_id)
{
	$sql = "select * from upfiles where upfile_id = $file_id";
	$file = $db->query_first($sql);
	return $file;
}
//**********************************************************************//


//**********check file exsiting in both DB and upload folder*************//
function checkFileExsit($db,$file_name,$path)
{
	$sql = "select * from upfiles where upfile_name='$file_name'";
	$rs = $db->query_first($sql);
	if(isset($rs))
	{
		$full_sysfile_name = $path.$rs['upfile_sysname'];
		$del_sql = "delete from upfiles where upfile_sysname = '".$rs['upfile_sysname']."'";

		//exsiting in DB and in folder
		if($rs['upfile_sysname'] && file_exists($full_sysfile_name))
		{
			return true;
		}
		else if($rs['upfile_sysname'] && !file_exists($full_sysfile_name))
		{
			$db->query($del_sql);
			return false;
		}
	}
	return false;
}
//**********************************************************************//


//******************list all of the users in table***********************//
function listUser($db){
	$sql = "select a.user_id,a.user_login,a.user_name,b.depart_name from user a, department b where a.user_depart_id = b.depart_id and a.user_active=1";
	$head = array("登录名","用户名","部门");
	$show_col = array("user_login","user_name","depart_name");//determin with column will be shown
	$body = $db->fetch_all_array($sql);
	return listInTable($head,$body,$show_col);
}
//**********************************************************************//


//need to be completed. used to create the input
/*
function input($tablename,$use,$checkNull,$is_use = true)
{
	$sql = "select * from $tablename limit 0,1";
	$input_arr = "<input type="" name="upfile_name"></input>";
}
*/

//********************create the show chart button**********************//
function chartButton($sql,$btn_name,$draw_type,$x_col_name,$y_col_name){
	echo '
			<form action="../common/image.php" method=post>
				<input type=hidden name=sql value = "'.$sql.'"></input>
				<input class=btn type=submit name=submit value="'.$btn_name.'"></input>
				<input type=hidden name=draw_type value="'.$draw_type.'"></input>
				<input type=hidden name=x_name value="'.$x_col_name.'"></input>
				<input type=hidden name=y_name value="'.$y_col_name.'"></input>
			</form>
	';
}
//**********************************************************************//

//********************create the show pbc chart **********************//
function pbcChartButton($sql,$btn_name,$draw_type,$x_col_name,$y_col_name,$z_name){
	echo '
			<form action="../common/image_pbc.php" method=post>
				<input type=hidden name=sql value = "'.$sql.'"></input>
				<input class=btn type=submit name=submit value="'.$btn_name.'"></input>
				<input type=hidden name=draw_type value="'.$draw_type.'"></input>
				<input type=hidden name=x_name value="'.$x_col_name.'"></input>
				<input type=hidden name=y_name value="'.$y_col_name.'"></input>
				<input type=hidden name=z_name value="'.$z_name.'"></input>
			</form>
	';
}
//**********************************************************************//


//********return an auto redirect link HTML*****************************//
//***$link should be the full address with protocol and $msg the what you want to show in the page****//
//**********it can be used in both FF and IE***************************//
function goLink($msg, $link = "")
{
echo "<center>
	<font color='red'>$msg</font>
	<br />
	页面将在 <span style='color: red; font-size:18px;' id=\"totalSecond\">2</span> 秒后跳转至以下地址,如果没有自动跳转，请尝试点击链接 <br><br>
	<a href='$link'>$link</a><br>
	<script language=\"javascript\" type=\"text/javascript\">
		var second = document.getElementById('totalSecond').textContent;

		if (navigator.appName.indexOf(\"Explorer\") > -1)
		{
			second = document.getElementById('totalSecond').innerText;
		} else
		{
			second = document.getElementById('totalSecond').textContent;
		}
		setInterval(\"redirect()\", 1000);
		function redirect()
		{
			if (second < 1)
			{
				location.href = '$link';
			} else
			{
				if (navigator.appName.indexOf(\"Explorer\") > -1)
				{
					document.getElementById('totalSecond').innerText = second--;
				} else
				{
					document.getElementById('totalSecond').textContent = second--;
				}
			}
		}
	</script>
	</center>
	";
}
//**********************************************************************//


//used to show the dataset in table. $head and $show_col are array. $body is the dataset generate by "$db->fetch_all_array"
//$show_col means what column will be shown in table, $head and $show_col should have the same size
function listInTable($head,$body,$show_col)
{
	//create the header
	$col_cnt = count($head);
	for($header = "<tr bgColor='#B0DFEF'><td><b>ID</b></td>",$i=0;$i<$col_cnt;$i++)
	{
		$header .= "<td><b>".$head[$i]."</b></td>";
	}
	$header .= "</tr>";

	//create the body
	$tr = "";
	$num = 1;
	foreach ($body as $record)
	{
		$tr .= "<tr><td>$num</td>";
		for($i=0;$i<count($show_col);$i++)
		{
			$tr .= "<td>".$record["$show_col[$i]"]."</td>";
		}
		$tr .= "</tr>";
		$num++;
	}
	if($tr=="")
	{
		$tr = "<tr><td colspan=$col_cnt align=center>没有记录！</td></tr>";
	}
	return "<table class=mytable>".$header.$tr."</table>";
}
//**********************************************************************//

//when you use db_array, set the second paremeter to true and give the third paremter $col_name, then the function will convert all of the timestamp to time string 
function time2str($epoch,$db_array=false,$col_name="",$longtime = true)
{
	$time_format = 'Y/m/d H:i:s';
	if(!$longtime)
	{
		$time_format = 'Y/m/d';
	}

	if(is_array($epoch)){
		//if you want to convert the time in dataset, you should specify the colum name
		if($db_array){
			for($i=0;$i<count($epoch);$i++)
			{
				//change the value of the text index
				$epoch["$i"]["$col_name"] = date($time_format, $epoch["$i"]["$col_name"]);
			}
		}
		//convert the array directly
		else
		{
			for($i=0;$i<count($epoch);$i++)
			{
				$epoch[$i] = date($time_format, $epoch[$i]);
			}
		}
		return $epoch;
	}
	return date($time_format, $epoch);	
}
//**********************************************************************//


//************add the download file link into the dataset***************//
//*********then it can be listed in the table by listInTable function***//
function addDownloadLink($upfiles)
{	
	for($i=0;$i<count($upfiles);$i++)
	{
		//change the value of the text index
		$upfiles[$i]['doc_link'] = "<a href='../common/download.php?id=".$upfiles[$i]['upfile_id']."'>".ext2img($upfiles[$i]['upfile_ext'])."</a>";
	}
	return $upfiles;
}
//**********************************************************************//

//************add the del and modify link into the dataset***************//
//*********then it can be listed in the table by listInTable function***//
function addOPLink($dataset,$folder,$id_name)
{	
	for($i=0;$i<count($dataset);$i++)
	{
		$dataset[$i]['op_link'] = "
		<a href='../$folder/actions.php?a=del&id=".$dataset[$i][$id_name]."' onclick=\"return confirm('确定要删除吗？')\">删除</a>
		&nbsp;&nbsp;&nbsp;
		<a href='../$folder/$folder.php?a=mdf&id=".$dataset[$i][$id_name]."'>修改</a>";
	}
	return $dataset;
}
//**********************************************************************//

//******************convert the extension string to images****************//
function ext2img($string_ext)
{
	return "<img width=20 height=20 border=0 src='../images/filetype/".$string_ext.".gif'></img>";
}
//**********************************************************************//


//*****************************upload file*********************************//
function uploadFile($db,$config,$upfile,$upfile_name="")
{
	// the max. size for uploading
	$max_size = $config['max_size']; 
	$my_upload = new file_upload('ch',$db);
	$my_upload->upload_dir = $config['upload_dir'];
	$my_upload->extensions = $config['allow_extension'];
	$my_upload->max_length_filename = $config['max_length_filename'];
	$my_upload->rename_file = true;

	//if there are files need to be upload
	if($upfile!="") {
		$my_upload->the_temp_file = $upfile['tmp_name'];
		$my_upload->the_file = $upfile['name'];
		$my_upload->http_error = $upfile['error'];
		$my_upload->replace = 'n'; 
		$my_upload->do_filename_check = 'n';
		$new_file_name = ($upfile_name!="") ? $upfile_name : $my_upload->the_file;
		if ($my_upload->upload($new_file_name)) {
			$full_path = $my_upload->upload_dir.$my_upload->file_sys_name;
			$info = $my_upload->get_uploaded_file_info($full_path);
			
			//insert into database
			//delete the extension string if it has
			$file['upfile_name'] = str_replace(".".$my_upload->file_ext,"",$new_file_name);  
			$file['upfile_sysname'] = $my_upload->file_sys_name;
			$file['upfile_time'] = time();
			$file['upfile_user_id'] = $_SESSION['user_id'];
			$file['upfile_ip'] = $_SERVER['REMOTE_ADDR'];
			$file['upfile_ext'] = $my_upload->file_ext;
			$doc_id = $db->query_insert("upfiles",$file);//insert the info into upfiles
			return $doc_id;
		}
	}
	return $my_upload->show_error_string();
}
//****************************end upload file******************************//

//*****************************explain the 评分规则 **********************//
function parseGradeRule($rule_num)
{
	switch ($rule_num) {
		case 1:
			return "0与1";
			break;
		case 2:
			return "实现程度得分";
			break;
		case 3:
			return "分层得分";
			break;
		case 4:
			return "扣分";
			break;
		default:
			return false;
	}
}
//**************************************************************************//

//*******************************explain PBC状态*****************************//
function parsePBCStatus($pbc_status)
{
	switch ($pbc_status) {
		case "initial":
			return "已保存";
			break;
		case "submitted":
			return "已提交";
			break;
		case "approved":
			return "已批准";
			break;
		case "self_scored":
			return "已自评";
			break;
		case "scored":
			return "已评分";
			break;
		case "closed":
			return "已结束";
			break;
		default:
			return false;
	}
}
//**************************************************************************//

//***********************************计算PBC总分*****************************//
function calculatePBC($pbc_id,$db)
{
	$total = 0;
	$sql = "select * from pbc_data where pbc_id='".$pbc_id."'";
	$pbc_data_arr = $db->fetch_all_array($sql);
	foreach($pbc_data_arr as $pbc_data)
	{
		$weight = $pbc_data['pbc_weights'];
		$grade = $pbc_data['pbc_grade'];
		if($pbc_data['pbc_rule'] == 4)// 扣分
			$grade = 0 - $grade;
		$total += $grade*$weight;
	}
	return number_format($total/100, 2, '.', '');
}
//***************************************************************************//

//*********************************获得用户信息******************************//
function getUser($user_id,$db)
{
	$sql = "select * from user u, department d where d.depart_id = u.user_depart_id and u.user_id=$user_id and  u.user_active=1";
	$user = $db->query_first($sql);
	return $user;
}
//***************************************************************************//

//**********************************update PBC status************************//
function updatePBCStatus($pbc_id,$pbc_status,$user_name,$db)
{
	$sql = "UPDATE pbc 
		SET pbc_status= '".$pbc_status."',
			pbc_change_time = '".time()."',
			pbc_change_by = '".$user_name."'
		WHERE pbc_id = '".$pbc_id."'";
	$pbc_text = "PBC 状态更新为 <font color=red>".parsePBCStatus($pbc_status)."</font>";
	logPBC($pbc_id,$pbc_text,$user_name,$db);
	//echo $sql;
	return $db->query($sql);
}
//**************************************************************************//

//**********************************update PBC status by crontab************************//
function updatePBCStatus_cron($pbc_from_status,$pbc_to_status,$where,$db)
{
	$sql = "UPDATE pbc 
		SET pbc_status= '".$pbc_to_status."',
			pbc_change_time = '".time()."',
			pbc_change_by = 'system'
		WHERE pbc_status = '".$pbc_from_status."'
		AND $where";
	//echo $sql;
	return $db->query($sql);
}
//**************************************************************************//

//**********************************log PBC change************************//
function logPBC($pbc_id,$pbc_text,$pbc_change_by,$db)
{
	$log['pbc_id'] = $pbc_id;
	$log['pbc_change_text'] = $pbc_text;
	$log['pbc_change_time'] = time();
	$log['pbc_change_by'] = $pbc_change_by;
	$log['pbc_change_ip'] = $_SERVER['REMOTE_ADDR'];
	$log_id = $db->query_insert("pbc_change_log",$log);//insert the info into upfiles
	return $log_id;
}
//**************************************************************************//

?>