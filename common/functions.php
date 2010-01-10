<?php

//need to be completed. used to create the input
/*
function input($tablename,$use,$checkNull,$is_use = true)
{
	$sql = "select * from $tablename limit 0,1";
	$input_arr = "<input type="" name="upfile_name"></input>";
}
*/

//return an auto redirect link HTML,$link should be the full address with protocol and $msg the what you want to show in the page.
//it can be used in both FF and IE
function goLink($msg, $link = "")
{
echo "<center>
	<font color='red'>$msg</font>
	<br />
	页面将在 <span style='color: red; font-size:18px;' id=\"totalSecond\">3</span> 秒后跳转至以下地址,如果没有自动跳转，请尝试点击链接 <br><br>
	<a href='$link'>$link</a><br>
	<script language=\"javascript\" type=\"text/javascript\">
		var second = document.getElementById('totalSecond').textContent;

		if (navigator.appName.indexOf(\"Explorer\") > -1)
		{
			second = document.getElementById('totalSecond').innerText;
		} else
		{
			second = document.getElementById('totalSecond').textContent;
		}
		setInterval(\"redirect()\", 1000);
		function redirect()
		{
			if (second < 1)
			{
				location.href = '$link';
			} else
			{
				if (navigator.appName.indexOf(\"Explorer\") > -1)
				{
					document.getElementById('totalSecond').innerText = second--;
				} else
				{
					document.getElementById('totalSecond').textContent = second--;
				}
			}
		}
	</script>
	</center>
	";
}

//used to show the dataset in table. $head and $show_col are array. $body is the dataset generate by "$db->fetch_all_array"
//$show_col means what column will be shown in table, $head and $show_col should have the same size
function listInTable($head,$body,$show_col)
{
	//create the header
	for($header = "<tr>",$i=0;$i<count($head);$i++)
	{
		$header .= "<td>".$head[$i]."</td>";
	}
	$header .= "</tr>";

	//create the body
	$tr = "";
	foreach ($body as $record)
	{
		$tr .= "<tr>";
		for($i=0;$i<count($show_col);$i++)
		{
			$tr .= "<td>".$record["$show_col[$i]"]."</td>";
		}
		$tr .= "</tr>";
	}

	return "<table class=mytable>".$header.$tr."</table>";
}

//when you use db_array, set the second paremeter to true and give the third paremter $col_name, then the function will convert all of the timestamp to time string 
function time2str($epoch,$db_array=false,$col_name="",$longtime = true)
{
	$time_format = 'Y-m-d H:i:s';
	if(!$longtime)
	{
		$time_format = 'Y-m-d';
	}

	if(is_array($epoch)){
		//if you want to convert the time in dataset, you should specify the colum name
		if($db_array){
			for($i=0;$i<count($epoch);$i++)
			{
				//change the value of the text index
				$epoch["$i"]["$col_name"] = date($time_format, $epoch["$i"]["$col_name"]);
			}
		}
		//convert the array directly
		else
		{
			for($i=0;$i<count($epoch);$i++)
			{
				$epoch[$i] = date($time_format, $epoch[$i]);
			}
		}
		return $epoch;
	}
	return date($time_format, $epoch);	
}

//add the download file link into the dataset, then it can be listed in the table by listInTable function
function addDownloadLink($upfiles)
{	
	for($i=0;$i<count($upfiles);$i++)
	{
		//change the value of the text index
		$upfiles[$i]['doc_link'] = "<a href='../common/download.php?id=".$upfiles[$i]['upfile_id']."'>".ext2img($upfiles[$i]['upfile_ext'])."</a>";
	}
	return $upfiles;
}

//convert the extension string to images
function ext2img($string_ext)
{
	return "<img width=20 height=20 border=0 src='../images/filetype/".$string_ext.".gif'></img>";
}

//*****************************upload file*********************************//
function uploadFile($db,$config,$upfile_name,$upfile)
{
	// the max. size for uploading
	$max_size = $config['max_size']; 
	$my_upload = new file_upload('ch',$db);
	$my_upload->upload_dir = $config['upload_dir'];
	$my_upload->extensions = $config['allow_extension'];
	$my_upload->max_length_filename = $config['max_length_filename'];
	$my_upload->rename_file = true;

	//if there are files need to be upload
	if($upfile!="") {
		$my_upload->the_temp_file = $upfile['tmp_name'];
		$my_upload->the_file = $upfile['name'];
		$my_upload->http_error = $upfile['error'];
		$my_upload->replace = 'n'; 
		$my_upload->do_filename_check = 'n';
		$new_file_name = ($upfile_name!="") ? $upfile_name : $my_upload->the_file;
		if ($my_upload->upload($new_file_name)) {
			$full_path = $my_upload->upload_dir.$my_upload->file_sys_name;
			$info = $my_upload->get_uploaded_file_info($full_path);
			//insert into database
			$file['upfile_name'] = $new_file_name;
			$file['upfile_sysname'] = $my_upload->file_sys_name;
			$file['upfile_time'] = time();
			$file['upfile_user_id'] = $_SESSION['user_id'];
			$file['upfile_ip'] = $_SERVER['REMOTE_ADDR'];
			$file['upfile_ext'] = $my_upload->file_ext;
			$doc_id = $db->query_insert("upfiles",$file);//insert the info into upfiles
			return $doc_id;
		}
	}
	return $my_upload->show_error_string();
}
//****************************end upload file******************************//
?>