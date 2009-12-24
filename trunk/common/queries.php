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
	getAllUser($db);
	//insertUser($db,$user);
	//getAllUser($db);

	$db->close();

	function insertUser($db,$user)
	{
		print $db->query_insert("user",$user);
		print "<BR>";
	}

	function getAllUser($db)
	{
		$sql = "select * from user";
		$rs = $db->fetch_all_array($sql);
		foreach ($rs as $record){
			print_r($record);
			print "<BR>";
		}
	}

?>