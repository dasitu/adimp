<?php
require_once("../common/functions.php");
//do this job to approve all of the pbc of the current month automatically. 

$now = date('Y-m-d');
$last_date = date('Y-m-d',strtotime('-1 month',time()));
$pbc_appr_from_status_ini = "initial";
$pbc_appr_from_status_sub = "submitted";
$pbc_appr_to_status = "approved";

$where_appr = "MONTH(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = MONTH('".$now."') 
		       AND YEAR(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = YEAR('".$now."')";
$r = updatePBCStatus_cron($pbc_appr_from_status_ini,$pbc_appr_to_status,$where_appr,$db);
$r = updatePBCStatus_cron($pbc_appr_from_status_sub,$pbc_appr_to_status,$where_appr,$db);

$msg = "Failed update pbc status to $pbc_appr_to_status: $affected_rows rows was updated";
if($r)
{
	$msg = "Success update pbc status to $pbc_appr_to_status: $affected_rows rows was updated";
}
//echoln($msg);
$db->write_log($msg);

?>