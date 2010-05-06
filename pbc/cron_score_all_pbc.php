<?php
require_once("../common/functions.php");

//do this job to score all the pbc of last month automatically. 

$now = date('Y-m-d');
$last_date = date('Y-m-d',strtotime('-1 month',time()));

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
					  p.pbc_change_by = 'system'
					  p.pbc_change_time = '".time()." 
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
    //echoln($msg);
    $db->write_log($msg);				  	
}
?>