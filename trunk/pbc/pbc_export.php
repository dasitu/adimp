<?php
require_once("../common/session.php");
error_reporting(E_ALL);
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once("../common/functions.php");
header("Content-Type: text/html; charset=utf-8");

$user_id = @$_GET['uid'];
@$month = $_GET['m'];
@$year = $_GET['y'];
if($month=="")
	$month = date('n');
if($year=="")
	$year = date('Y');
if($user_id=="")
	$user_id = $_SESSION['user_id'];
	
$sql_data = "select * FROM pbc_data pd, user u, pbc p, pbc_biz_type pbt
WHERE MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
and YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and pd.pbc_id = p.pbc_id
and p.pbc_user_id = u.user_id
and pd.pbc_biz_type_id = pbt.pbc_biz_type_id
";
$sql_type = "select pbt.pbc_biz_type_name, pat.pbc_active_name 
FROM user u, pbc_temp_biz ptb, pbc_biz_type pbt, pbc_active_type pat
WHERE u.user_id = ".$user_id."
and u.user_pbc_template_id = ptb.pbc_template_id
and ptb.pbc_biz_type_id = pbt.pbc_biz_type_id
and pbt.pbc_biz_type_id = pat.pbc_biz_type_id 
";
$pbc_type = $db->fetch_all_array($sql_type); 
$result_arr = $db->fetch_all_array($sql_data);

//change the result to new array structure
foreach($result_arr as $record)
{
    $biz = $record['pbc_biz_type_name'];
	$active = $record['pbc_active_name'];
	unset($record['pbc_biz_type_name']);
	unset($record['pbc_active_name']);
	data[$biz][$active] = $record;	
}

//draw the excel sheet
$objPHPExcel = new PHPExcel();
for($tab=0;$tab<count($result_arr['user_id']);$tab++)
{
  $objPHPExcel->setActiveSheetIndex($tab)
              ->setCellValue('A1', '业务类别')
              ->setCellValue('B1', '活动类型')
			  ->setCellValue('','');
			  

			  
  $objPHPExcel->getActiveSheet()->setTitle($result_arr['user_name']);			  
			  }








?>
