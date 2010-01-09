function checkNull(objForm)
{
	var inputs = objForm.getElementsByTagName("input");
	for (var i=0;i<inputs.length;i++)
	{
		if(inputs[i].alt=="NotNull" && inputs[i].value=="")
		{
			alert('该值不能为空！');
			inputs[i].focus();
			return false;
		}
	}
	return true;
}