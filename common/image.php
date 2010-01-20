<?php
include "../common/queries.php";
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
$title = $_POST['submit'];

$result  = $db->fetch_all_array($sql);
for($i=0;$i<count($result);$i++)
{
	$row = $result[$i];
	$Data[$i] = $row["$y_name"];
	$DataDescription[$i] = $row["$x_name"];
}

//$Data = array(8,4,2,3,2);
//$DataDescription = array('张三','李四','王五','士大夫','搜房');

$canvas_width = 400;
$canvas_height = 300;
$margin = 40;
$radius = 5;
$padding = $radius + 2;
$bg_color_R = 240;
$bg_color_G = 240;
$bg_color_B = 240;
$drawTicks = TRUE;
$gridLine = 5;

// Dataset definition 
$DataSet = new pData;
$DataSet->AddPoint($Data,"Serie1");
$DataSet->AddPoint($DataDescription,"Serie2"); //this is for pie but no influence with bar
$DataSet->AddAllSeries();
$DataSet->SetAbsciseLabelSerie("Serie2");//this is for pie but no influence with bar

// Initialise the graph
$Test = new pChart($canvas_width,$canvas_height);
$Test->setFontProperties("../lib/chart/Fonts/vistak.TTF",9);
$Test->setGraphArea($margin,$margin,$canvas_width-$margin,$canvas_height-$margin);
$Test->drawFilledRoundedRectangle($padding,$padding,$canvas_width-$padding,$canvas_height-$padding,$radius,$bg_color_R,$bg_color_G,$bg_color_B);
$Test->drawRoundedRectangle($radius,$radius,$canvas_width-$radius,$canvas_height-$radius,$radius,$bg_color_R-15,$bg_color_G-15,$bg_color_B-15);

//**************for bar image*****************//
if($draw_type == "bar")
{
	$Test->drawGraphArea(255,255,255,TRUE);
	$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALLSTART0,150,150,150,$drawTicks,0,2,TRUE,100);
	$Test->drawGrid($gridLine,FALSE,$bg_color_R-10,$bg_color_G-10,$bg_color_B-10,50);
	// Draw the 0 line
	$Test->drawTreshold(0,143,55,72,FALSE,FALSE);
	// Draw the bar graph
	$Test->drawOverlayBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),$DataDescription);
}

//*************For Pie image****************//
if($draw_type == "pie")
{
	$thickness = 15;
	$angle = 45;
	$Test->AntialiasQuality = 0;
	$Test->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),$canvas_width/2,$canvas_height/2,$canvas_height/2-$margin,PIE_PERCENTAGE_LABEL,true,$angle,$thickness,5);
	//$Test->drawPieLegend(330,15,$DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
}

//draw title
$Test->setFontProperties("../lib/chart/Fonts/JDJYCU.TTF",12);
$Test->drawTitle($margin-$padding,$margin-$padding,$title,50,50,50,$canvas_width-$margin);

//render
$Test->Stroke("export.png");
?>