<?php
require_once("../common/functions.php");
//cron_appr_score_pbc.php
//do this job each 5th to approve and mark for pbc automatically. 

$now = date('Y-m-d');
$last_date = date('Y-m-d',strtotime('-1 month',time()));
$pbc_appr_from_status_ini = "initial";
$pbc_appr_from_status_sub = "submitted";
$pbc_appr_to_status = "approved";

$where_appr = "MONTH(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = MONTH('".$now."') 
		       AND YEAR(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = YEAR('".$now."')";
$msg = updatePBCStatus_cron($pbc_appr_from_status_ini,$pbc_appr_to_status,$where_appr,$db);
$msg = updatePBCStatus_cron($pbc_appr_from_status_sub,$pbc_appr_to_status,$where_appr,$db);
echoln($where);
$affected_rows = 0;
$msg = "Failed update pbc status to $pbc_appr_to_status: $affected_rows rows was updated";
if($r)
{
	$affected_rows = $db->affected_rows;
	$msg = "Success update pbc status to $pbc_appr_to_status: $affected_rows rows was updated";
}
echoln($msg);
$db->write_log($msg);

$sql = "SELECT pd.pbc_grade_self, p.pbc_user_id
       FROM pbc p, pbc_data pd
	   WHERE p.pbc_id = pd.pbc_id
	   AND MONTH(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = MONTH('".$last_date."') 
	   AND YEAR(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = YEAR('".$now."')
	   AND p.pbc_status <> 'scored'
";
$sg_array = $db->fetch_all_array($sql);
foreach($sg_array as $sg)
{
    $user_id = $sg['pbc_user_id'];
	if($sg['pbc_grade_self']=="")
	{
	   $self_grade = 0;	   
	}
	else
	{
	   $self_grade = $sg['pbc_grade_self'];
	}
	
	$update_sql = "UPDATE pbc p, pbc_data pd
	              SET p.pbc_status = 'scored', 
				      pd.pbc_grade_self = '".$self_grade."'
			      WHERE p.pbc_id = pd.pbc_id
				  AND p.pbc_user_id = '".$user_id."'
				  AND MONTH(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = MONTH('".$last_date."') 
	              AND YEAR(FROM_UNIXTIME(pbc_time,'%Y-%m-%d')) = YEAR('".$now."')
	              AND p.pbc_status <> 'scored'";
    $affected_rows = 0;
    $msg = "Failed update $user_id 's pbc status to SCORED.";
    if($db->query($update_sql))
    {
	   $affected_rows = $db->affected_rows;
	   $msg = "Success update $user_id 's pbc status to SCORED.";
    }
    echoln($msg);
    $db->write_log($msg);				  	
}

?>