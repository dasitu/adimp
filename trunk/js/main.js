function checkNull(objForm)
{
	var inputs = objForm.getElementsByTagName("input");
	for (var i=0;i<inputs.length;i++)
	{
		if(inputs[i].alt=="NotNull" && inputs[i].value=="")
		{
			alert('请检查您的输入，确保填写完整之后提交！');
			inputs[i].focus();
			return false;
		}
	}
	return true;
}

function checkPercent(value)
{
	if(value>100)
	{
		alert('您的PBC总权重为'+value+'%, 超过了100%, 请查正后提交！');
		return false;
	}
	return true;
}

// start and end should be formate like '2010/01/20'
function getDateDiff(startObj,endObj,outObj){
	var start = document.getElementById(startObj).value;
	var end = document.getElementById(endObj).value;
	if(start != '' && end != '')
	{
		var d1 = new Date(Date.parse(start));   
		var d2 = new Date(Date.parse(end));   
		var diff = (d2 - d1) / (24 * 3600 * 1000);
		if(diff>0){
			document.getElementById(outObj).value = diff;
			return true;
		}
		else
		{
			document.getElementById(outObj).value = '';
			alert('起始日期大于结束日期，请检查后重新输入！');
		}
		return false;
	}
}