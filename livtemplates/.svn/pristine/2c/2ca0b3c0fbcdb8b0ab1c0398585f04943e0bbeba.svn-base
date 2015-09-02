function choose_cols(sub_name, cid,cname,option)
{
	var seled = document.getElementById('select_cols').value;
	var selected_arr = seled.split(',');
	for(var i=0;i<selected_arr.length;i++)
	{
		if(cid == selected_arr[i])
		{
			alert('该栏目已经选择，请不要重复选择');
			return;
		}
	}
	var str = document.createElement( "li");
	str.attachEvent("ondblclick", new Function("remove_cols('"+cid+"');"));
	str.id = 'r_'+cid;
	if (parseInt(option) != 0)
	{
		var hidden_n = sub_name;
	}
	else
	{
		var hidden_n = sub_name + '[]';
	}
	//str.innerHTML = cname+'<a title="双击栏目名称或单击'X'删除已选栏目" onclick="remove_cols('+cid+')"            style="margin-left:8px;cursor=pointer;">&nbsp;X&nbsp;</a><input type="hidden" name="' + hidden_n + '" value="' + cid + '" />';
	str.style.cursor = 'pointer';
	str.style.float = 'left';
	str.style.clear = 'both';
	if(option == 0)
	{//多选
		document.getElementById('result_list').appendChild(str);
		var values = seled+','+cid;
		document.getElementById('select_cols').value = values;
	}
	else
	{//单选
		 document.getElementById('result_list').innerHTML = '';
		 document.getElementById('result_list').appendChild(str);
		seled = cid;
		document.getElementById('select_cols').value = seled;
	}

}

function remove_cols(cid)
{
	var sel_str = document.getElementById('result_list');
	sel_str.removeChild(document.getElementById('r_'+cid));
	var seled = document.getElementById('select_cols').value;
	seled = seled.split(',');
	for(i in seled)
	{
		if(seled[i] == cid)
		{
			seled.splice(i,1);
		}
	}
	seled = seled.join(',');
	document.getElementById('select_cols').value=seled;


}
function add_css(obj,type)
{
	var id = obj.id;
	if(type == 1)
	{

		var one_save_id = document.getElementById('one_save');
		if(parseInt(one_save_id.value))
		{
			document.getElementById('id_'+one_save_id.value).className = '';
		}
		obj.className = 'selected_css';

		id = id.split('_');
		if(one_save_id.value != id[1])
		{
			var div3 = document.getElementById('three_level_id');
			div3.style.display = "none";
		}
		one_save_id.value = id[1];
	}
	else if(type == 2)
	{
		var two_save_id = document.getElementById('two_save');
		if(document.getElementById('id_'+two_save_id.value))
		{
			if(parseInt(two_save_id.value))
			{
				document.getElementById('id_'+two_save_id.value).className = '';
			}
		}
		obj.className = 'selected_css';

		var id_2 = obj.id.split('_');
		if(two_save_id.value != id_2[1])
		{
			var div3 = document.getElementById('three_level_id');
			div3.style.display = "none";
		}
		two_save_id.value = id_2[1];
	}
	else
	{
		var third_save_id = document.getElementById('third_save');
		if(document.getElementById('id_'+third_save_id.value))
		{
			if(parseInt(third_save_id.value))
			{
				document.getElementById('id_'+third_save_id.value).className = '';
			}
		}
		obj.className = 'selected_css';
		var id_3 = id.split('_');
		third_save_id.value = id_3[1];
	}


}function choose_cols(sub_name, cid,cname,option)
{
	var seled = document.getElementById('select_cols').value;
	var selected_arr = seled.split(',');
	for(var i=0;i<selected_arr.length;i++)
	{
		if(cid == selected_arr[i])
		{
			alert('该栏目已经选择，请不要重复选择');
			return;
		}
	}
	var str = document.createElement( "li");
	str.attachEvent("ondblclick", new Function("remove_cols('"+cid+"');"));
	str.id = 'r_'+cid;
	if (parseInt(option) != 0)
	{
		var hidden_n = sub_name;
	}
	else
	{
		var hidden_n = sub_name + '[]';
	}
	//str.innerHTML = cname+'<a title="双击栏目名称或单击'X'删除已选栏目" onclick="remove_cols('+cid+')" style="margin-left:8px;cursor=pointer;">&nbsp;X&nbsp;</a><input type="hidden" name="' + hidden_n + '" value="' + cid + '" />';
	str.style.cursor = 'pointer';
	str.style.float = 'left';
	str.style.clear = 'both';
	if(option == 0)
	{//多选
		document.getElementById('result_list').appendChild(str);
		var values = seled+','+cid;
		document.getElementById('select_cols').value = values;
	}
	else
	{//单选
		 document.getElementById('result_list').innerHTML = '';
		 document.getElementById('result_list').appendChild(str);
		seled = cid;
		document.getElementById('select_cols').value = seled;
	}

}

function remove_cols(cid)
{
	var sel_str = document.getElementById('result_list');
	sel_str.removeChild(document.getElementById('r_'+cid));
	var seled = document.getElementById('select_cols').value;
	seled = seled.split(',');
	for(i in seled)
	{
		if(seled[i] == cid)
		{
			seled.splice(i,1);
		}
	}
	seled = seled.join(',');
	document.getElementById('select_cols').value=seled;


}
function add_css(obj,type)
{
	var id = obj.id;
	if(type == 1)
	{

		var one_save_id = document.getElementById('one_save');
		if(parseInt(one_save_id.value))
		{
			document.getElementById('id_'+one_save_id.value).className = '';
		}
		obj.className = 'selected_css';

		id = id.split('_');
		if(one_save_id.value != id[1])
		{
			var div3 = document.getElementById('three_level_id');
			div3.style.display = "none";
		}
		one_save_id.value = id[1];
	}
	else if(type == 2)
	{
		var two_save_id = document.getElementById('two_save');
		if(document.getElementById('id_'+two_save_id.value))
		{
			if(parseInt(two_save_id.value))
			{
				document.getElementById('id_'+two_save_id.value).className = '';
			}
		}
		obj.className = 'selected_css';

		var id_2 = obj.id.split('_');
		if(two_save_id.value != id_2[1])
		{
			var div3 = document.getElementById('three_level_id');
			div3.style.display = "none";
		}
		two_save_id.value = id_2[1];
	}
	else
	{
		var third_save_id = document.getElementById('third_save');
		if(document.getElementById('id_'+third_save_id.value))
		{
			if(parseInt(third_save_id.value))
			{
				document.getElementById('id_'+third_save_id.value).className = '';
			}
		}
		obj.className = 'selected_css';
		var id_3 = id.split('_');
		third_save_id.value = id_3[1];
	}


}