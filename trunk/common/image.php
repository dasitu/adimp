<?php
// Standard inclusions   
include("../lib/chart/pChart/pData.class");
include("../lib/chart/pChart/pChart.class");

header("Content-Type: text/html; charset=utf-8");

// Dataset definition 
$DataSet = new pData;
$Data = array(8,4,2,3,2,1,7);
$DataDescription = array('张三','王如','多大','士大夫','搜房','而更','阿德勒');
$DataSet->AddPoint($Data,"Serie1",$DataDescription);
//This will put a label containing the text "Important point!" on the 3rd point of Serie1 (-3)
$DataSet->AddAllSeries();

// Initialise the graph
$Test = new pChart(700,230);
$Test->setFontProperties("../lib/chart/Fonts/tahoma.ttf",8);
$Test->setGraphArea(50,30,650,200);
$Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
$Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);
$Test->drawGraphArea(255,255,255,TRUE);
$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALLSTART0,150,150,150,TRUE,0,2,TRUE,100);
$Test->drawGrid(4,TRUE,230,230,230,50);

// Draw the 0 line
$Test->setFontProperties("../lib/chart/Fonts/JDJYCU.ttf",10);
$Test->drawTreshold(0,143,55,72,TRUE,FALSE);

// Draw the bar graph
$Test->drawOverlayBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),$DataDescription);

// Finish the graph
$Test->setFontProperties("../lib/chart/Fonts/JDJYCU.ttf",8);

$Test->setFontProperties("../lib/chart/Fonts/JDJYCU.ttf",10);
$Test->drawTitle(50,22,'演示',50,50,50,350);
$Test->Stroke("export.png");
?>