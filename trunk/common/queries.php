<?php
require_once "../lib/database.class.php";
require_once "../common/config.inc.php";

$db = new Database($config['server'],$config['user'],$config['pass'],$config['database'],$config['tablePrefix']);
$db->connect();

//$user["user_name"] = "测试用户";
//$user["user_depart_id"] = "1";
//getAllUser($db);
//insertUser($db,$user);

//insert one user to table user
function insertUser($db,$user)
{
	print $db->query_insert("user",$user);
	print "<BR>";
}

//list all the users from table user
function getAllUser($db)
{
	$sql = "select * from user";
	$rs = $db->fetch_all_array($sql);
	foreach ($rs as $record){
		print_r($record);
		print "<BR>";
	}
}

//give the options for selectbox according to the parameters, return html code
function listSelection($db,$tableName,$colName)
{
	$sql = "select $colName from $tableName";
	$rs = $db->fetch_all_array($sql);
	foreach ($rs as $record)
	{
		$options .= "<option>$record</option>";
	}
	return $options;
}

//get the upfile info, the return value is an array
function getFileInfo($db,$file_id)
{
	$sql = "select * from upfiles where upfile_id = $file_id";
	$rs = $db->query_first($sql);
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
?>