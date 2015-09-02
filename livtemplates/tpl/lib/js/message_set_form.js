function check(name,obj)
{
	var num = 0;
	$('input[name='+name+']').each(function(){
		if($(this).attr('checked'))
		{
			num++;
		}
	});
	if(num == 2)
	{
		$('input[name='+name+']').attr('checked',false);
		$(obj).attr('checked','checked');
	}
}

function check_colation(type)
{
	if(type == 1)
	{
		$('#message_colation').show();
	}
	else
	{
		$('#message_colation').hide();
	}
}
function change_module()
{
	var url= './run.php?mid='+gMid+'&a=get_app&app_uniqueid='+$('#app_id').val();
	hg_ajax_post(url);
}

function app_back(json)
{	
	var data = $.parseJSON(json);
	$('#app').html(get_html('module_id',data));
}

function get_html(name,data)
{
	var html = '<select name='+name+' ><option value="0">-请选择-</option>';
	for (var i in data)
	{
		html = html + '<option value='+i+'@'+data[i]+'>' +data[i] + '</option>';
	}
	html = html + '</select>';
	return html;
}

