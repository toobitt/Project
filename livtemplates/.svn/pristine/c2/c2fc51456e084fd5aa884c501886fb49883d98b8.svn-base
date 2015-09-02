
function input_content_color(i)
{
	if(!$("#required_" + i).val())
	{
		$('#important_' + i).addClass('i');
		$('#sub').attr('disabled','disabled')
	}
	else
	{
		$('#important_' + i).removeClass('i');
		$('#sub').removeAttr('disabled');
	}
	
}

function hg_set_way(id)
{
	var url = "./run.php?mid="+gMid+"&a=set_way&id="+id;
	hg_ajax_post(url);
}

function hg_set_way_callback(json){
	var json_data = $.parseJSON(json);
	if(json_data['send_way'] == 1)
	{
		$('#set_way_'+json_data['id']).html('推送');
	}
	if(json_data['send_way'] == 0)
	{
		$('#set_way_'+json_data['id']).html('拉取');
	}
}
