<?php
require_once("../common/functions.php");
//cron_submit_all_pbc.php
//do this job every last day, submit all of pbc of current month

$now = date('Y-m-d');
$pbc_from_status = "initial";
$pbc_to_status = "closed";
$where = "WHERE MONTH(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = MONTH('".$now."') 
		AND YEAR(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = YEAR('".$now."')";
$msg = updatePBCStatus_cron($pbc_from_status,$pbc_to_status,$where,$db);
echoln($where);
$affected_rows = 0;
$msg = "Failed update pbc status to $pbc_to_status: $affected_rows rows was updated";
if($r)
{
	$affected_rows = $db->affected_rows;
	$msg = "Success update pbc status to $pbc_to_status: $affected_rows rows was updated";
}
echoln($msg);
$db->write_log($msg);
?>