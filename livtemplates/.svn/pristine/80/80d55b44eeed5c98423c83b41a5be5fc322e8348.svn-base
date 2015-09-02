function hg_call_news_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}
	if($('#edit_show'))
	{
		hg_close_opration_info();
	}
}
function hg_material_indexpic(id,m_id)
{
	var url = './run.php?'+'mid=' + gMid + '&a=update_indexpic&id=' + id + '&m_id=' + m_id;
	hg_request_to(url);
}

function hg_material_indexpic_call(data)
{
	var obj = eval('(' + data + ')');
	if(obj.indexpic)
	{
	
		$(".img_"+obj.id).attr('src',obj.small);
	}
}