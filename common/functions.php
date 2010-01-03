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
?>