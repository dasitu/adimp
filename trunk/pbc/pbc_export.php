<?php
require_once("../common/session.php");
error_reporting(E_ALL);
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once("../common/functions.php");
header("Content-Type: text/html; charset=utf-8");

@$month = $_GET['m'];
@$year = $_GET['y'];
if($month=="")
	$month = date('n');
if($year=="")
	$year = date('Y');

//get template list and user list	
$sql_pbc = "select u.user_pbc_template_id, p.pbc_user_id
FROM user u, pbc p
WHERE MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
and YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and p.pbc_user_id = u.user_id
order by u.user_name
";	
$pbc_temp_user = $db->fetch_all_array($sql_pbc);
$user_cnt = count($pbc_temp_user);
$usrlist[] = $pbc_temp_user['pbc_user_id'];
$templist[] = $pbc_temp_user['user_pbc_template_id'];

//get pbc details data	
$sql_data = "select pbt.pbc_biz_type_name, pat.pbc_active_name, pd.pbc_active, pd.pbc_end_tag, pd.pbc_planned_end_date, 
pd.pbc_refer_task, pd.pbc_weights, pd.pbc_evaluator, pd.pbc_rule, pd.pbc_grade_self, pd.pbc_grade, pd.pbc_comment, u.user_name, u.user_id, u.user_pbc_template_id
FROM pbc_data pd, user u, pbc p, pbc_biz_type pbt, pbc_active_type pat
WHERE MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
and YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and pd.pbc_id = p.pbc_id
and p.pbc_user_id = u.user_id
and pd.pbc_biz_type_id = pbt.pbc_biz_type_id
and pat.pbc_biz_type_id = pd.pbc_biz_type_id
order by u.user_name, pbt.pbc_biz_type_name, pat.pbc_active_name
";
$result_arr = $db->fetch_all_array($sql_data);

//change the result to new array structure
foreach($result_arr as $record)
{
    $biz = $record['pbc_biz_type_name'];
	$active = $record['pbc_active_name'];
	$template_id[] = $record['user_pbc_template_id'];
	unset($record['pbc_biz_type_name']);
	unset($record['pbc_active_name']);
	$data[$biz][$active][] = $record;
}

//draw the excel sheet
$objPHPExcel = new PHPExcel();
for($tab=0;$tab<count($usrlist);$tab++)
{
  $tab_id = $usrlist[$tab];
  $objPHPExcel->setActiveSheetIndex($tab)
              ->setCellValue('A1', '业务类别')
              ->setCellValue('B1', '活动分类')
			  ->setCellValue('C1', '活动内容')
			  ->setCellValue('D1', '完成标志')
			  ->setCellValue('E1', '计划完成时间')
			  ->setCellValue('F1', '关联任务')
			  ->setCellValue('G1', '权重')
			  ->setCellValue('H1', '考核主体')
			  ->setCellValue('I1', '评分规则')
			  ->setCellValue('J1', '自评得分')
			  ->setCellValue('K1', '评分')
			  ->setCellValue('L1', '备注');
  $tab_user = getUser($usrlist[$tab],$db);
  $objPHPExcel->getActiveSheet()->setTitle($tab_user['user_name']);   
}

//choose the correct data to fill in
for($i=0;$i<count($templist);$i++)
{
   //get the biz and active type of each template 
   $crt_temp_id = $templist[$i];
   $sql_type = "select pbt.pbc_biz_type_name, pat.pbc_active_name 
    FROM pbc_temp_biz ptb, pbc_biz_type pbt, pbc_active_type pat
    WHERE ptb.pbc_template_id = ".$crt_temp_id."
    and ptb.pbc_biz_type_id = pbt.pbc_biz_type_id
    and pbt.pbc_biz_type_id = pat.pbc_biz_type_id 
    ";
   $type_list = $db->fetch_all_array($sql_type); 
   
   //$tab_id = $usrlist[$i];
   //$tab_user = getUser($usrlist[$i],$db);
   //$objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($objPHPExcel->getSheetByName($tab_user['user_name'])));
   
   $biz_list[] = $type_list['pbc_biz_type_name'];
   $active_list[] = $type_list['pbc_active_name'];
   
   //complete the data by null which has no record for specific biz and active
   for($m=0;$m<count($biz_list);$m++)
   {
      for ($n=0;$n<count($active_list);$n++)
	  {
	    foreach($data as $value)
		{
		   if(isset($value[$biz_list[$m]][$active_list[$n]]))
		   $value[$biz_list[$m]][$active_list[$n]] = array('','','','','','','','','','');
		}
	  }
   }
   
   //fill in the data
   $row = 2;
   for($x=0;$x<count($data);$x++)
   {
     $objPHPExcel->setActiveSheetIndex($i)
                 ->setCellValue('"A".$row', $value[$x][$biz])
                 ->setCellValue('"B".$row', $value[$x][$active])
			     ->setCellValue('"C".$row', $value[$x]['pbc_active'])
			     ->setCellValue('"D".$row', $value[$x]['pbc_end_tag'])
			     ->setCellValue('"E".$row', $value[$x]['pbc_planned_end_date'])
			     ->setCellValue('"F".$row', $value[$x]['pbc_refer_task'])
			     ->setCellValue('"G".$row', $value[$x]['pbc_weights'])
			     ->setCellValue('"H".$row', $value[$x]['pbc_evaluator'])
			     ->setCellValue('"I".$row', $value[$x]['pbc_rule'])
		         ->setCellValue('"J".$row', $value[$x]['pbc_grade_self'])
			     ->setCellValue('"K".$row', $value[$x]['pbc_grade'])
			     ->setCellValue('"L".$row', $value[$x]['pbc_comment']);
	  
      $row++;	 
   }	  
}

$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2003 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//output to file
$outputFileName = "export_".$year.$month.".xls";
////$objWriter->save($outputFileName);
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
//到浏览器
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition:inline;filename="'.$outputFileName.'"');
header("Content-Transfer-Encoding: binary");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
$objWriter->save('php://output');

?>
