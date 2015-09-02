function hg_group_more(html,id)
{
	if(html)
	{
		var tops = jQuery("#r" + id).position().top;
		jQuery("#group_more").css('top',tops);
		jQuery("#group_more").css('left','638px');
		jQuery("#group_more").html(html);
		jQuery("#group_more").show();
	}
}

function hg_more_close()
{
	jQuery("#group_more").hide();
}

function hg_agree_this(json,user_id,group_id,type_o)
{
	var obj = new Function("return" + json)();

	if(obj.tips)
	{
		alert(obj.msg);
	}
	else
	{
		alert(obj.msg);		
		if(type_o)
		{			
			$('#agree' + group_id + '_' + user_id).attr("checked","");
			$('#noagree' + group_id + '_' + user_id).attr("checked",true);
		}
		else
		{
			$('#agree' + group_id + '_' + user_id).attr("checked",true);
			$('#noagree' + group_id + '_' + user_id).attr("checked","");
		}
	}
}

function hg_group_update(){


}

function check_user_grandsnum(user_id,group_id,type,module_id)
{
	if(confirm("确认执行此项操作？！"))
	{
		url = "./run.php?mid=" + module_id + "&a=check&user_id=" + user_id + "&group_id=" + group_id + "&type=" + type;
		hg_ajax_post(url,'检查','');
	}
}