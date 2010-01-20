<?php
require_once "../lib/database.class.php";
require_once "../common/config.inc.php";
require_once "../common/functions.php";

$db = new Database($config['server'],$config['user'],$config['pass'],$config['database'],$config['tablePrefix']);
$db->connect();

//login
function login($db,$login,$pass)
{
	$sql = "select u.*,d.depart_name from user u, department d where u.user_login='$login' and u.user_pwd=password('$pass') and u.user_depart_id=d.depart_id";
	$user = $db->query_first($sql);
	if($user){
		return $user;
	}
	return false;
}

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

?>