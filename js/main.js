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

function getDateDiff(start,end){
	
}