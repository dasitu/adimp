<?php
require_once("../common/functions.php");
//cron_submit_all_pbc.php
//do this job every last day, submit all of pbc of current month

$now = date('Y-m-d');
$pbc_status = "submitted";
$sql = "UPDATE pbc 
		SET pbc_status = '".$pbc_status."' 
		WHERE MONTH(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = MONTH('".$now."') 
		AND YEAR(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = YEAR('".$now."') 
		AND pbc_status = 'initial';
		";
echoln($sql);
$affected_rows = 0;
$msg = "Failed update pbc status to $pbc_status: $affected_rows rows was updated($sql)";
if($db->query($sql))
{
	$affected_rows = $db->affected_rows;
	$msg = "Success update pbc status to $pbc_status: $affected_rows rows was updated($sql)";
}
echoln($msg);
$db->write_log($msg);
?>