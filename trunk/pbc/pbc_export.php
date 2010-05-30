<?php
error_reporting(0);
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once("../common/functions.php");
//header("Content-Type: text/html; charset=utf-8");

@$month = $_GET['m'];
@$year = $_GET['y'];
$user_id = $_GET['u'];
$depart_id = $_GET['d'];
if($month=="")
	$month = date('n');
if($year=="")
	$year = date('Y');

//$month = 3;
//$year = 2010;

//prepare the user list	and its template relationship
$sql_pbc = "
SELECT		u.user_pbc_template_id, p.pbc_user_id
FROM		user u, pbc p
WHERE		YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
AND			MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month
AND			p.pbc_user_id = u.user_id 
AND			u.user_active=1 ";
if($user_id!="")
{
	$sql_pbc .= " AND u.user_id=".$user_id;
}
if($depart_id!="")
{
	$sql_pbc .= " AND u.user_depart_id=".$depart_id;
}
$sql_pbc .=" ORDER BY	u.user_name";

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
$sql_data = "
SELECT pbt.pbc_biz_type_name, pd.pbc_active, pd.pbc_end_tag, FROM_UNIXTIME(pd.pbc_planned_end_date,'%Y-%m-%d') as pbc_planned_end_date, 
pd.pbc_refer_task, pd.pbc_weights, pd.pbc_evaluator, pd.pbc_rule, pd.pbc_active_type, pd.pbc_grade_self, pd.pbc_grade, pd.pbc_comment, u.user_pbc_template_id, u.user_id,p.pbc_reward,p.pbc_total_grade,u.user_name
FROM	pbc_data pd, user u, pbc p, pbc_biz_type pbt
WHERE	YEAR(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $year 
AND		MONTH(FROM_UNIXTIME(p.pbc_time,'%y-%m-%d')) = $month
AND		pd.pbc_id = p.pbc_id
AND		p.pbc_user_id = u.user_id
AND		pbt.pbc_biz_type_id = pd.pbc_biz_type_id ";
if($user_id!="")
{
	$sql_data .= " AND u.user_id=".$user_id;
}
if($depart_id!="")
{
	$sql_pbc .= " AND u.user_depart_id=".$depart_id;
}
$sql_data .= " ORDER BY u.user_name, pbt.pbc_biz_type_name";
$result_arr = $db->fetch_all_array($sql_data);
//print_r($result_arr);
//exit;

//change the result to new array structure
foreach($result_arr as $record)
{
    $biz = $record['pbc_biz_type_name'];
	$active = $record['pbc_active_type'];
	$template_id[] = $record['user_pbc_template_id'];
	$user_id = $record['user_id'];
	$data[$user_id]['pbc_reward'] = $record['pbc_reward'];
	$data[$user_id]['pbc_total_grade'] = $record['pbc_total_grade'];
	$data[$user_id]['user_name'] = $record['user_name'];

	unset($record['pbc_reward']);
	unset($record['user_name']);
	unset($record['pbc_total_grade']);
	unset($record['pbc_biz_type_name']);
	unset($record['pbc_active_name']);
	unset($record['user_id']);
	unset($record['user_name']);
	unset($record['pbc_active_type']);
	unset($record['user_pbc_template_id']);
	$data[$user_id][$biz][$active][] = $record;
}

//print_r($data);

//draw the excel sheet
$objPHPExcel = new PHPExcel();
$objPHPExcel->removeSheetByIndex(0);

$tab=0;
foreach($usr_tmp_list as $user_id => $temp_id)
{
	$objPHPExcel->createSheet();
	$objActSheet = $objPHPExcel->getSheet($tab);
	$tab_name = $data[$user_id]['user_name'];

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
	$objActSheet->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDDDDD');
	$objActSheet->setTitle($tab_name);
	//setColWidth($objActSheet);
	
	//draw the table according to the template that already prepared
	$template = $pbc_template[$temp_id];

	$row = 2;
	$col = 'A'; //ord chr
	//echoln($tab_name['user_name']);
	//print_r($template);
	//exit;
	foreach($template as $biz_type_name => $actives)
	{
		$objActSheet->setCellValue($col.$row, $biz_type_name);
		$col = chr(ord($col)+1);

		//no pbc_active_type defined, print the data directly
		if($actives[0]=="")
		{
			$record_cnt = count($data[$user_id][$biz_type_name]);
			if($record_cnt>0)//if there are record in this kind of biz_type
			{
				foreach ($data[$user_id][$biz_type_name] as $active_type => $left_array)
				{
					$objActSheet->setCellValue($col.$row, $active_type);
					$col = chr(ord($col)+1);
					$row = setLeftDataCell($objActSheet,$left_array,$col,$row);
					$col = chr(ord($col)-1);
				}
			}
			else //if there are no record for this kind of biz type, just increace the row number
			{
				$row++;
			}
		}
		else
		{
			foreach($actives as $active_type)
			{
				//echoln($active_type);
				//write a row of this kind of active type name
				$objActSheet->setCellValue($col.$row, $active_type);
				$row++; //give a blank row to seapreate each kind of active type

				//write the data
				if(count($data[$user_id][$biz_type_name][$active_type])==0)
				{
					$row++; //add another blank row;
				}
				else
				{
					$row = setLeftDataCell($objActSheet, $data[$user_id][$biz_type_name][$active_type],$col,$row);
				}
			}
		}
		$col = 'A';
	}
	mergeCells($objActSheet,$row);
	setBottomCell($objActSheet,$data[$user_id],$row); //write the bottom columns
	//setColWidth($objActSheet);

	//set the border
	$objActSheet->getStyle('A1:L'.($row+1))->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	$tab++;
}

//exit;
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2003 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//output to file
$outputFileName = "export_".$year.'-'.$month.".xls";
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
	foreach($array as $data_other)
	{
		$objActSheet->setCellValue('C'.$row, $data_other['pbc_active']);
		$objActSheet->setCellValue('D'.$row, $data_other['pbc_end_tag']);
		$objActSheet->setCellValue('E'.$row, $data_other['pbc_planned_end_date']);
		$objActSheet->setCellValue('F'.$row, $data_other['pbc_refer_task']);
		$objActSheet->setCellValue('G'.$row, $data_other['pbc_weights']."%");
		$objActSheet->setCellValue('H'.$row, $data_other['pbc_evaluator']);
		$objActSheet->setCellValue('I'.$row, parseGradeRule($data_other['pbc_rule']));
		$objActSheet->setCellValue('J'.$row, $data_other['pbc_grade_self']);
		$objActSheet->setCellValue('K'.$row, $data_other['pbc_grade']);
		$objActSheet->setCellValue('L'.$row, $data_other['pbc_comment']);
		$row++;
	}
	return $row;
}

function setBottomCell($objActSheet,$data_arr,$row)
{
	$objActSheet->setCellValue('A'.$row, '本月预计绩效奖');
	$objActSheet->setCellValue('B'.$row, $data_arr['pbc_reward']);
	$objActSheet->setCellValue('E'.$row, 'PBC合计得分');
	$objActSheet->setCellValue('F'.$row, $data_arr['pbc_total_grade']);
	
	$row++;
	$objActSheet->setCellValue('A'.$row, '员工签名');
	$objActSheet->mergeCells('B'.$row.':C'.$row);
	$objActSheet->setCellValue('B'.$row, ' 年 月 日');
	$objActSheet->getStyle('B'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	$objActSheet->setCellValue('E'.$row, '部门领导签字');
	$objActSheet->mergeCells('F'.$row.':G'.$row);
	$objActSheet->setCellValue('F'.$row, ' 年 月 日');
	$objActSheet->getStyle('F'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	return $row;
}

function mergeCells($objActSheet,$total_row)
{
	for($i=1;$i<($total_row-1);$i++)
	{
		$cell = $objActSheet->getCell('A'.$i)->getValue();
		$cell_next = $objActSheet->getCell('A'.($i+1))->getValue();
		if($cell_next == "")
		{
			$objActSheet->mergeCells('A'.$i.':A'.($i+1));
			$objActSheet->getStyle('A'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}
	}
}

function setColWidth($objActSheet)
{
	$objActSheet->getColumnDimension('L')->setAutoSize(true);
	for($i=0;$i<12;$i++)
	{
		//$objActSheet->getColumnDimension("'".chr(ord('A')+$i)."'")->setAutoSize(true);
	}
}
?>