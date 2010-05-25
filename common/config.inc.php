<?php
//server info config
$config['index'] = 'http://'.$_SERVER['SERVER_NAME'].'/ad_imp/common/welcome.php';

//file upload config
$config['language_upfile'] = "ch";
$config['upload_dir'] = "../upload/"; //ending with "/"
$config['allow_extension'] = array(".rar",".gif",".png", ".zip", ".pdf", ".doc", ".txt", ".xls", ".ppt");
$config['max_length_filename'] = 50;
$config['max_size'] = 100*1024*1024; // the max. size for uploading

//database server configured in Database class

//pbc related config
//the current day is inclued

//the data range for group member to evaluate there self score 相对于 PBC 本身的时间来讲
$pbc_config['start_evaluate_day'] = '-1#25';//-1 means last month and 25 means 25th day of the month
$pbc_config['last_evaluate_day'] = '0#5';

//the date range for group members to submit there pbc 相对于当前时间来讲
$pbc_config['start_submit_day'] = '-1#25';
$pbc_config['last_submit_day'] = '0#8';

//the date range of group manager to approve the pbc
$pbc_config['start_approve_day'] = '0#1';
$pbc_config['last_approve_day'] = '0#6';

//the date range of group manager to score the pbc
$pbc_config['start_score_day'] = '0#1';
$pbc_config['last_score_day'] = '0#9';
?>