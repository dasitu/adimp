<?php
include "../common/functions.php";
// Standard inclusions   
include("../lib/chart/pChart/pData.class");
include("../lib/chart/pChart/pChart.class");

error_reporting(0);
header("Content-Type: text/html; charset=utf-8");
if(!isset($_POST["draw_type"]))
	$draw_type = "bar";

$draw_type = $_POST["draw_type"];
$sql = stripslashes($_POST['sql']);
$x_name = $_POST['x_name'];
$y_name = $_POST['y_name'];
$z_name = $_POST['z_name'];
$title = $_POST['submit'];

$result  = $db->fetch_all_array($sql);
for($i=0;$i<count($result);$i++)
{
	$row = $result[$i];
	$y[$i] = $row["$y_name"];
	$x[$i] = $row["$x_name"];
	$z[$i] = $row["$z_name"];
}

//$Data = array(8,4,2,3,2);
//$DataDescription = array('张三','李四','王五','士大夫','搜房');

$canvas_width = 600;
$canvas_height = 400;
$margin = 50;
$radius = 5;
$padding = $radius + 2;
$bg_color_R = 240;
$bg_color_G = 240;
$bg_color_B = 240;
$drawTicks = TRUE;
$gridLine = 5;

// Dataset definition 
/*$DataSet = new pData;
$DataSet->AddPoint($Data,"Serie1");
$DataSet->AddPoint($DataDescription,"Serie2"); //this is for pie but no influence with bar
$DataSet->AddAllSeries();
$DataSet->SetAbsciseLabelSerie("Serie2");//this is for pie but no influence with bar
*/
 $DataSet = new pData;
 //$DataSet->AddPoint(array(1,4,-3,2,-3,3,2,1,0,7,4),"Serie1");
 //$DataSet->AddPoint(array(3,3,-4,1,-2,2,1,0,-1,6,3),"Serie2");
 //$DataSet->AddPoint(array(4,1,2,-1,-4,-2,3,2,1,2,2),"Serie3");
 
 $DataSet->AddPoint($x,"Serie1");
 $DataSet->AddPoint($y,"Serie2");
 $DataSet->AddPoint($z,"Serie3");
 $DataSet->AddAllSeries();
 $DataSet->SetAbsciseLabelSerie();
 //$DataSet->SetSerieName("January","Serie1");
 //$DataSet->SetSerieName("February","Serie2");
 //$DataSet->SetSerieName("March","Serie3");
 $DataSet->SetSerieName("姓名","Serie1");
 $DataSet->SetSerieName("分数","Serie2");
 $DataSet->SetSerieName("时间","Serie3");

// Initialise the graph
/*$Test = new pChart($canvas_width,$canvas_height);
$Test->setFontProperties("../lib/chart/Fonts/vistak.TTF",9);
$Test->setGraphArea($margin,$margin,$canvas_width-$margin,$canvas_height-$margin);
$Test->drawFilledRoundedRectangle($padding,$padding,$canvas_width-$padding,$canvas_height-$padding,$radius,$bg_color_R,$bg_color_G,$bg_color_B);
$Test->drawRoundedRectangle($radius,$radius,$canvas_width-$radius,$canvas_height-$radius,$radius,$bg_color_R-15,$bg_color_G-15,$bg_color_B-15);
*/
 $Test = new pChart($canvas_width,$canvas_height);
 $Test->setFontProperties("../lib/chart/Fonts/vistak.TTF",9);
 $Test->setGraphArea($margin,$margin,$canvas_width-$margin,$canvas_height-$margin);
 $Test->drawFilledRoundedRectangle($padding,$padding,$canvas_width-$padding,$canvas_height-$padding,$radius,$bg_color_R,$bg_color_G,$bg_color_B);
 $Test->drawRoundedRectangle($radius,$radius,$canvas_width-$radius,$canvas_height-$radius,$radius,$bg_color_R-15,$bg_color_G-15,$bg_color_B-15);
 $Test->drawGraphArea(255,255,255,TRUE);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALL,150,150,150,TRUE,0,2,TRUE);
 $Test->drawGrid(4,TRUE,230,230,230,50);

//**************for bar image*****************//
/*if($draw_type == "bar")
{
	$Test->drawGraphArea(255,255,255,TRUE);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALLSTART0,150,150,150,$drawTicks,0,2,TRUE,100);
	$Test->drawGrid($gridLine,FALSE,$bg_color_R-10,$bg_color_G-10,$bg_color_B-10,50);
	// Draw the 0 line
	$Test->drawTreshold(0,143,55,72,FALSE,FALSE);
	// Draw the bar graph
	$Test->drawOverlayBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),$DataDescription);
}
*/
//*************For Pie image****************//
/*if($draw_type == "pie")
{
	$thickness = 15;
	$angle = 45;
	$Test->AntialiasQuality = 0;
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),$canvas_width/2,$canvas_height/2,$canvas_height/2-$margin,PIE_PERCENTAGE_LABEL,true,$angle,$thickness,5);
	//$Test->drawPieLegend(330,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
}
*/
//draw title
$Test->setFontProperties("../lib/chart/Fonts/JDJYCU.TTF",12);
$Test->drawTitle($margin-$padding,$margin-$padding,$title,50,50,50,$canvas_width-$margin);

// Draw the 0 line
 $Test->setFontProperties("../lib/chart/Fonts/JDJYCU.TTF",10);
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

 // Draw the bar graph
 $Test->drawStackedBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),100);

 // Finish the graph
 $Test->setFontProperties("../lib/chart/Fonts/JDJYCU.TTF",10);
 $Test->drawLegend(596,150,$DataSet->GetDataDescription(),255,255,255);
 //$Test->setFontProperties("Fonts/tahoma.ttf",10);
 //$Test->drawTitle(50,22,"Example 20",50,50,50,585);
 //$Test->Stroke("example20.png");

//render
$Test->Stroke("export.png");
?>