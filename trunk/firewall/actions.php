<?php
require_once "../common/queries.php";
require_once "../common/functions.php";
require "../lib/upload.class.php"; //classes is the map where the class file is stored (one above the root)

// the max. size for uploading
$max_size = $config['max_size']; 
$my_upload = new file_upload('ch',$db);
$my_upload->upload_dir = $config['upload_dir'];
$my_upload->extensions = $config['allow_extension'];
$my_upload->max_length_filename = $config['max_length_filename'];
$my_upload->rename_file = true;

//if there are files need to be upload
if(isset($_FILES['upfile'])) {
	$my_upload->the_temp_file = $_FILES['upfile']['tmp_name'];
	$my_upload->the_file = $_FILES['upfile']['name'];
	$my_upload->http_error = $_FILES['upfile']['error'];
	$my_upload->replace = 'n'; 
	$my_upload->do_filename_check = 'n';
	$new_file_name = ($_POST['upfile_name']!="") ? $_POST['upfile_name'] : $my_upload->the_file;
	if ($my_upload->upload($new_file_name)) {
		$full_path = $my_upload->upload_dir.$my_upload->file_sys_name;
		$info = $my_upload->get_uploaded_file_info($full_path);
		//insert into database
		$file['upfile_name'] = $new_file_name;
		$file['upfile_sysname'] = $my_upload->file_sys_name;
		$file['upfile_time'] = time();
		$file['upfile_user_id'] = "0";
		$file['upfile_ip'] = $_SERVER['REMOTE_ADDR'];
		$file['upfile_ext'] = $my_upload->file_ext;
		$doc_id = insertFileInfo($db,$file);
		if(isset($doc_id))
		{
			$suc = insertFirewallRule($db,$doc_id);
		}
	}
	goLink($_SERVER["HTTP_REFERER"],$my_upload->show_error_string());
}
?>