<?php
	require_once("../lib/database.class.php");
	require_once("../common/config.inc.php");

	//initial the connection
	$db = new database($config['server'],$config['user'],$config['pass'],$config['database'],$config['tablePrefix']);
	$db->connect();
	$db->query('Set names UTF8');

	//
	$user["user_name"] = "测试用户";
	$user["user_depart_id"] = "1";
	//getAllUser($db);
	//insertUser($db,$user);
	//getAllUser($db);

	$db->close();

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
		
	}

?>