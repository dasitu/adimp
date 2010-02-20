<?php
include "../common/functions.php";
// Standard inclusions   
include("../lib/chart/pChart/pData.class");
include("../lib/chart/pChart/pChart.class");

error_reporting(0);
header("Content-Type: text/html; charset=utf-8");

$sql = stripslashes($_POST['sql']);
$x_name = $_POST['x_name']; //user_name
$y_name = $_POST['y_name']; //pbc_total_grade
$z_name = $_POST['z_name']; //pbc_time(unix_timestamp) 
$title = $_POST['submit'];
$result  = $db->fetch_all_array($sql);

$DataSet = new pData;
for($i=0;$i<count($result);$i++)
{
	$row = $result[$i];
	$user_name = $row["$x_name"];
	$data_month = date('y年n月',$row["$z_name"]);
	$serie_cnt["$data_month"] = $i;
	$user_cnt["$user_name"] = $i;
	$data["$data_month"]["$user_name"] = $row["$y_name"];
}

$i=0;
foreach ($serie_cnt as $month => $value)
{
	foreach ($user_cnt as $user => $value_u)
	{
		if(!isset($data["$month"]["$user"]))
			$data["$month"]["$user"] = 0;
		$DataSet->AddPoint($data["$month"]["$user"],"Serie".$i,$user);
	}
	$i++;
} 

$canvas_width = 700;
$canvas_height = 400;
$margin = 50;
$radius = 5;
$padding = $radius + 2;
$bg_color_R = 240;
$bg_color_G = 240;
$bg_color_B = 240;
$drawTicks = TRUE;
$gridLine = 5;

$DataSet->AddAllSeries();
$DataSet->SetAbsciseLabelSerie();

$i=0;
foreach ($serie_cnt as $month => $value)
{
	$DataSet->SetSerieName($month,"Serie".$i);
	$i++;
}

$Test = new pChart($canvas_width,$canvas_height);
$Test->setFontProperties("../lib/chart/Fonts/vistak.TTF",10);
$Test->setGraphArea($margin,$margin,$canvas_width-$margin-50,$canvas_height-$margin);
$Test->drawFilledRoundedRectangle($padding,$padding,$canvas_width-$padding,$canvas_height-$padding,$radius,$bg_color_R,$bg_color_G,$bg_color_B);
$Test->drawRoundedRectangle($radius,$radius,$canvas_width-$radius,$canvas_height-$radius,$radius,$bg_color_R-15,$bg_color_G-15,$bg_color_B-15);
$Test->drawGraphArea(255,255,255,TRUE);
$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALLSTART0,150,150,150,TRUE,0,2,TRUE);
$Test->drawGrid(4,TRUE,230,230,230,50);

// Draw the bar graph
$Test->drawStackedBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),80);

// Finish the graph
$Test->drawLegend($canvas_width-$margin-$padding-35,$margin+5,$DataSet->GetDataDescription(),255,255,255);

//draw title
$Test->setFontProperties("../lib/chart/Fonts/JDJYCU.TTF",12);
$Test->drawTitle($margin-$padding,$margin-$padding,$title,50,50,50,$canvas_width-$margin);

//render
$Test->Stroke("export.png");
?>