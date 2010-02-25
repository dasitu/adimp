<?php
error_reporting(0);
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once("../common/functions.php");
//header("Content-Type: text/html; charset=utf-8");

@$month = $_GET['m'];
@$year = $_GET['y'];
if($month=="")
	$month = date('n');
if($year=="")
	$year = date('Y');
$month = 3;
$year = 2010;

//prepare the user list	and its template relationship
/*
$sql_pbc = "select u.user_pbc_template_id, p.pbc_user_id
FROM user u, pbc p
WHERE MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month 
and YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and p.pbc_user_id = u.user_id
order by u.user_name
";
*/

$sql_pbc = "select u.user_pbc_template_id, p.pbc_user_id
FROM user u, pbc p
WHERE YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
and p.pbc_user_id = u.user_id
order by u.user_name
";

$pbc_temp_user = $db->fetch_all_array($sql_pbc);
foreach($pbc_temp_user as $pbc_user)
{
	$usr_tmp_list[$pbc_user['pbc_user_id']] = $pbc_user['user_pbc_template_id'];
}

//prepare all the pbc template data
$sql_pbc_temp = "
SELECT	pt.pbc_template_id, pbt.pbc_biz_type_name,pat.pbc_active_name
FROM	pbc_template pt, pbc_temp_biz ptb, pbc_biz_type pbt
LEFT JOIN pbc_active_type pat
ON pat.pbc_biz_type_id = pbt.pbc_biz_type_id
WHERE	pt.pbc_template_id = ptb.pbc_template_id
AND		pbt.pbc_biz_type_id = ptb.pbc_biz_type_id
";
$pbc_temp = $db->fetch_all_array($sql_pbc_temp);
foreach($pbc_temp as $temp)
{
	$temp_id = $temp['pbc_template_id'];
	$biz_type_name = $temp['pbc_biz_type_name'];
	$active_name = $temp['pbc_active_name'];
	$pbc_template[$temp_id][$biz_type_name][] = $active_name;
}


//get pbc details data	
// MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month
$sql_data = "
SELECT pbt.pbc_biz_type_name, pd.pbc_active, pd.pbc_end_tag, pd.pbc_planned_end_date, 
pd.pbc_refer_task, pd.pbc_weights, pd.pbc_evaluator, pd.pbc_rule, pd.pbc_active_type, pd.pbc_grade_self, pd.pbc_grade, pd.pbc_comment, u.user_pbc_template_id, u.user_id
FROM	pbc_data pd, user u, pbc p, pbc_biz_type pbt
LEFT JOIN pbc_active_type pat 
ON		pat.pbc_biz_type_id = pbt.pbc_biz_type_id 
WHERE  YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
AND		pd.pbc_id = p.pbc_id
AND		p.pbc_user_id = u.user_id
AND		pbt.pbc_biz_type_id = pd.pbc_biz_type_id
ORDER BY u.user_name, pbt.pbc_biz_type_name, pat.pbc_active_name
";
$result_arr = $db->fetch_all_array($sql_data);

//change the result to new array structure
foreach($result_arr as $record)
{
    $biz = $record['pbc_biz_type_name'];
	$active = $record['pbc_active_type'];
	$template_id[] = $record['user_pbc_template_id'];
	$user_id = $record['user_id'];
	unset($record['pbc_biz_type_name']);
	unset($record['pbc_active_name']);
	unset($record['user_id']);
	unset($record['user_name']);
	unset($record['pbc_active_type']);
	unset($record['user_pbc_template_id']);
	$data[$user_id][$biz][$active][] = $record;
}

//draw the excel sheet
$objPHPExcel = new PHPExcel();

$tab=0;
foreach($usr_tmp_list as $user_id => $temp_id)
{
	$objPHPExcel->createSheet();
	$objActSheet = $objPHPExcel->getSheet($tab);
	$tab_name = getUser($user_id,$db);

	//create the header
	
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

	//set the user name as the tab name 
	$objActSheet->setTitle($tab_name['user_name']);
	
	//draw the table according to the template that already prepared
	$template = $pbc_template[$temp_id];

	$row = 2;
	$col = 'A'; //ord chr
	foreach($template as $biz_type_name => $actives)
	{
		$objActSheet->setCellValue($col.$row, $biz_type_name);
		$col = chr(ord($col)+1);

		//no pbc_active_type defined, print the data directly
		if($actives[0]=="")
		{
			foreach ($data[$user_id][$biz_type_name] as $active_type => $left_array)
			{
				$objActSheet->setCellValue($col.$row, $active_type);
				$col = chr(ord($col)+1);
				$row = setLeftDataCell($objActSheet,$left_array,$col,$row);
				$col = chr(ord($col)-1);
			}
		}
		else
		{
			foreach($actives as $active_type)
			{
				//write a row of this kind of active type name
				$objActSheet->setCellValue($col.$row, $active_type);
				$row++;

				//write the data
				$row = setLeftDataCell($objActSheet, $data[$user_id][$biz_type_name][$active_type],$col,$row);
			}
		}
		$col = 'A';
	}
	$tab++;
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

function setLeftDataCell($objActSheet,$array,$col,$row)
{
	//print_r($array);
	//exit;
	foreach($array as $data_other)
	{
		$objActSheet->setCellValue('C'.$row, $data_other['pbc_active']);
		$objActSheet->setCellValue('D'.$row, $data_other['pbc_end_tag']);
		$objActSheet->setCellValue('E'.$row, $data_other['pbc_planned_end_date']);
		$objActSheet->setCellValue('F'.$row, $data_other['pbc_refer_task']);
		$objActSheet->setCellValue('G'.$row, $data_other['pbc_weights']);
		$objActSheet->setCellValue('H'.$row, $data_other['pbc_evaluator']);
		$objActSheet->setCellValue('I'.$row, $data_other['pbc_rule']);
		$objActSheet->setCellValue('J'.$row, $data_other['pbc_grade_self']);
		$objActSheet->setCellValue('K'.$row, $data_other['pbc_grade']);
		$objActSheet->setCellValue('L'.$row, $data_other['pbc_comment']);
		$row++;
	}
	return $row;
}
?>