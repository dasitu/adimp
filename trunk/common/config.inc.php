<?php
//server info config
$config['index'] = 'http://'.$_SERVER['SERVER_NAME'].'/adimp/';

//file upload config
$config['language_upfile'] = "ch";
$config['upload_dir'] = "../upload/"; //ending with "/"
$config['allow_extension'] = array(".rar",".gif",".png", ".zip", ".pdf", ".doc", ".txt", ".xls", ".ppt");
$config['max_length_filename'] = 50;
$config['max_size'] = 1024*250; // the max. size for uploading

//database server configured in Database class

//pbc related config
//the current day is inclued

//the data range for group member to evaluate there self score
$pbc_config['start_evaluate_day'] = '-1#25';//-1 means last month and 25 means 25th day of the month
$pbc_config['last_evaluate_day'] = '0#1';

//the date range for group members to submit there pbc
$pbc_config['start_submit_day'] = '-1#25';
$pbc_config['last_submit_day'] = '0#3';

//the date range of group manager to approve the pbc
$pbc_config['start_approve_day'] = '0#1';
$pbc_config['last_approve_day'] = '0#4';

//the date range of group manager to approve the pbc
$pbc_config['start_score_day'] = '0#1';
$pbc_config['last_score_day'] = '0#4';
?>