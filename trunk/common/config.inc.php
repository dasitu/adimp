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
$pbc_config['start_evaluate_day'] = 1;
$pbc_config['last_evaluate_day'] = 1;
$pbc_config['start_submit_day'] = 1;
$pbc_config['last_submit_day'] = 30;

?>