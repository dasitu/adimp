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
function goLink($link,$msg)
{
echo "
	$msg
	<br />
	页面将在 <span id=\"totalSecond\">5</span> 秒后跳转至以下地址 <br>
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

	return "<table border=1>".$header.$tr."</table>";
}

//when you use db_array, set the second paremeter to true and give the third paremter $col_name, then the function will convert all of the timestamp to time string 
function time2str($epoch,$db_array=false,$col_name="")
{
	if(is_array($epoch)){
		//if you want to convert the time in dataset, you should specify the colum name
		if($db_array){
			for($i=0;$i<count($epoch);$i++)
			{
				//change the value of the text index
				$epoch["$i"]["$col_name"] = date('Y-m-d H:i:s', $epoch["$i"]["$col_name"]);
			}
		}
		//convert the array directly
		else
		{
			for($i=0;$i<count($epoch);$i++)
			{
				$epoch[$i] = date("Y-m-d H:i:s", $epoch[$i]);
			}
		}
		return $epoch;
	}
	return date('Y-m-d H:i:s', $epoch);	
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
?>