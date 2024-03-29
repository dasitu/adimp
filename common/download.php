<?php
header("Content-Type: text/html; charset=utf-8");
require "../common/functions.php";
$file_id = $_GET['id'];

if(@$_SERVER["HTTP_REFERER"]) //Avoid to access directly
{
	$file = getFileInfo($db,$file_id);
	$file_extension = strtolower($file['upfile_ext']);
	$file_realname = $file['upfile_name'];
	$file_name = $config['upload_dir'].$file['upfile_sysname'];

	// required for IE, otherwise Content-disposition is ignored
	if(ini_get('zlib.output_compression'))
	  ini_set('zlib.output_compression', 'Off');

	if( $file_name == "" ) 
	{
	  echo "<html><title>Download Script</title><body>ERROR: download file NOT SPECIFIED. USE download.php?id=$file_id</body></html>";
	  exit;
	} elseif ( !file_exists($file_name) ) 
	{
	  echo "<html><title>Download Script</title><body>ERROR: File not found. USE download.php?id=$file_id</body></html>";
	  exit;
	};
	switch( $file_extension )
	{
	  case "pdf": $ctype="application/pdf"; break;
	  case "exe": $ctype="application/octet-stream"; break;
	  case "zip": $ctype="application/zip"; break;
	  case "doc": $ctype="application/msword"; break;
	  case "xls": $ctype="application/vnd.ms-excel"; break;
	  case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
	  case "gif": $ctype="image/gif"; break;
	  case "png": $ctype="image/png"; break;
	  case "jpeg":
	  case "jpg": $ctype="image/jpg"; break;
	  default: $ctype="application/force-download";
	}
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers 
	header("Content-Type: $ctype;charset=utf-8;");

	// change, added quotes to allow spaces in filenames
	$ua = $_SERVER["HTTP_USER_AGENT"];
	$filename = "$file_realname.$file_extension";
	$encoded_filename = urlencode($filename);
	$encoded_filename = str_replace("+", "%20", $encoded_filename);

	header('Content-Type: application/octet-stream');
	if (preg_match("/MSIE/", $ua)) {
		header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
	} else if (preg_match("/Firefox/", $ua)) {
		header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
	} else {
		header('Content-Disposition: attachment; filename="' . $filename . '"');
	}

	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($file_name));
	readfile($file_name);
	exit;
}
else
{
	echo "请通过正常途径下载文件!";
}
?>