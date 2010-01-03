<?php

//file upload config
$config['language_upfile'] = "ch";
$config['upload_dir'] = "../upload/"; //ending with "/"
$config['allow_extension'] = array(".png", ".zip", ".pdf", ".doc", ".txt", ".xls");
$config['max_length_filename'] = 50;
$config['max_size'] = 1024*250; // the max. size for uploading

//database server
$config['server'] = "localhost";
$config['user'] = "root";
$config['pass'] = "test";
$config['database'] = "ad_imp";
$config['tablePrefix'] = "";

?>