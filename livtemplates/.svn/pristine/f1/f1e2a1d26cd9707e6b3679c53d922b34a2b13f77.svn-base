/**
 * 
 */
/*显示select菜单*/
function hg_show_select(id,val){
	$("#con_"+id).hide();
	$("#showselect_"+id).show();
	$("#select_"+id).focus();
	var value=$("#select_"+id).find('option:selected').text();
	$("#con_"+id).html(value);
	$("#hidden_"+id).val($("#select_"+id).val());
}
/*改变角色时调用的方法*/
function hg_onchange_showcon(id,val)
{
	var value=$("#select_"+id).find('option:selected').text();
	$("#con_"+id).html(value);
	$("#con_"+id).show();
	$("#showselect_"+id).hide();
	$("#hidden_"+id).val($("#select_"+id).val());
	
}
/*修改完成后返回原来状态*/
function hg_onblur_showcon(id)
{
	$("#con_"+id).show();
	$("#showselect_"+id).hide();
	
}
/*删除回调函数*/
function hg_manage_delback(json)
{
	var json_data = $.parseJSON(json);
	for(var key in json_data.id)
	{
		$('#con_'+json_data.id[key]).html('暂无角色');
		$('#op_'+json_data.id[key]).html('<a title="编辑" href="./run.php?mid='+gMid+'&a=add&id='+json_data.id[key]+'&interview_id='+json_data.interview_id+'"><i class="hg_icons edit_icon"></i></a>');
	}
}
/*将单个用户加到访谈用户中去*/
function hg_add_user(id,interview_id)
{
	var role = $('#hidden_'+id).attr('value');
	if (!role){
		role = 0;
	}
	var url = "./run.php?mid="+gMid+"&a=add&id="+id+"&role="+role+"&interview_id="+interview_id;
	hg_ajax_post(url);
}
/*添加单个用户返回*/
function hg_add_userback(json)
{
	var json_data = $.parseJSON(json);
	if (json_data.group)
	{
		$("#con_"+json_data.user_id).html(json_data.group);	
	}else{
		$("#con_"+json_data.user_id).html('暂无分组');	
	}
	$("#op_"+json_data.user_id).html('<a onclick="return hg_ajax_post(this, \'删除\', 1);" href="./run.php?mid='+gMid+'&a=delete&id='+json_data.user_id+'&interview_id='+json_data.interview_id+'"><i class="hg_icons del_icon"></i></a>');
}

/*添加多个用户到访谈中去*/
function hg_add_more(interview_id)
{
	var length = $('input[name="infolist[]"]').length;
	var checked = false;
	var num = 0;
	var id_arr = new Array();
	var group_arr = new Array();
	for (var i=0; i<length; i++)
	{
		var e = $('input[name="infolist[]"]')[i];
		if(e.checked)
		{
			checked = true;
			var id= e.value;
			id_arr.push(id);
			var group = $("#hidden_"+e.value).val();
			if (!group){
				group=0;
			}
			group_arr.push(group);	
		}
	}
	var ids = id_arr.join(',');
	var groups = group_arr.join(',');
	var url = "./run.php?mid="+gMid+"&a=add_more&id="+ids+"&role="+groups+"&interview_id="+interview_id;
	hg_ajax_post(url);
	if(!checked)
	{
		alert('请选需要加入访谈的用户！');
		return false;
	}
	return true;

}
/*增加多个用户的回调*/
function hg_add_more_back(json)
{
	var json_data = $.parseJSON(json);
	//alert(json_data.id)
	for( var i=0;i<json_data.id.length;i++){
		$("#con_"+json_data.id[i]).html(json_data.group[i]);	
		
		$("#op_"+json_data.id[i]).html('<a onclick="return hg_ajax_post(this, \'删除\', 1);" href="./run.php?mid='+gMid+'&a=delete&id='+json_data.id[i]+'&interview_id='+json_data.interview_id+'"><i class="hg_icons del_icon"></i></a>');
		
	}
}








