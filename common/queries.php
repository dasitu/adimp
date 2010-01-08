<?php
require_once "../lib/database.class.php";
require_once "../common/config.inc.php";
require_once "../common/functions.php";

$db = new Database($config['server'],$config['user'],$config['pass'],$config['database'],$config['tablePrefix']);
$db->connect();

//insert one user to table user
function insertUser($db,$user)
{
	print $db->query_insert("user",$user);
	print "<BR>";
}

//give the options for selectbox according to the parameters, return html code
function listSelection($db,$table_name,$col_name,$col_value)
{
	$sql = "select $col_name,$col_value from $table_name";
	$rs = $db->fetch_all_array($sql);
	$options = "";
	foreach ($rs as $record)
	{
		$options .= "<option value='".$record["$col_value"]."'>".$record["$col_name"]."</option>";
	}
	return $options;
}

//get the upfile info, the return value is an array
function getFileInfo($db,$file_id)
{
	$sql = "select * from upfiles where upfile_id = $file_id";
	$file = $db->query_first($sql);
	return $file;
}

//insert the upfile info, $file_info is an array
function insertFileInfo($db,$file_info)
{
	return $db->query_insert("upfiles",$file_info);
}

function insertFirewallRule($db,$doc_id)
{
	$firewall_rule['rule_doc_id'] = $doc_id;
	return $db->query_insert("firewall_rule",$firewall_rule);
}

//check file exsiting in both DB and upload folder
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

//list all of the users in table
function listUser($db){
	$sql = "select a.user_id,a.user_login,a.user_name,b.depart_name from user a, department b where a.user_depart_id = b.depart_id";
	$head = array("ID","登录名","用户名","部门");
	$show_col = array("user_id","user_login","user_name","depart_name");//determin with column will be shown
	$body = $db->fetch_all_array($sql);
	return listInTable($head,$body,$show_col);
}

//list all of the Firewall Rule document
function listFirewallRule($db){
	$sql = "select b.upfile_id, b.upfile_name,b.upfile_time,b.upfile_ext,c.user_name from firewall_rule a, upfiles b, user c where a.rule_doc_id = b.upfile_id and b.upfile_user_id = c.user_id";
	$head = array("文件名","上传时间","上传人","文档");
	$show_col = array("upfile_name","upfile_time","user_name","doc_link");//determin with column will be shown
	$body = $db->fetch_all_array($sql);
	$body = time2str($body,true,"upfile_time"); //convert the datetime to string, "true"means it is a dataset, "upfile_time" means the column name
	$body = addDownloadLink($body); //add the last image link for download files, the column name is "doc_link"
	return listInTable($head,$body,$show_col);
}
?>