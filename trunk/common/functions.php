<?php
function showFileLink()
{
	
}

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

function listInTable($head,$body)
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
		for($i=0;$i<count($head);$i++)
		{
			$tr .= "<td>".$record[$i]."</td>";
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
				$epoch_value = $epoch["$i"]["$col_name"];	//unix time stamp(int)
				$str_value = date('Y-m-d H:i:s', $epoch_value); //string datetime(string)

				//change the value of the text index
				$epoch["$i"]["$col_name"] = $str_value;

				//change the value of the num index
				$num_key = array_search($epoch_value, $epoch["$i"],true);
				$epoch["$i"]["$num_key"] = $str_value;
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

function addDownloadLink()
{
	
}
?>