<?php
error_reporting(E_ALL);
require_once '../lib/PHPExcel/Classes/PHPExcel.php';
require_once '../lib/PHPExcel/Classes/PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '测试汉字')
            ->setCellValue('A2', '中文汉字');

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('中文');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2003 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//output to file
$outputFileName = "export.xls";
////$objWriter->save($outputFileName);
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
//到浏览器
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition:inline;filename="'.$outputFileName.'"');
header("Content-Transfer-Encoding: binary");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");
$objWriter->save('php://output');

// Echo memory peak usage
//echo date('H:i:s') . " Peak memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB\r\n";
?>