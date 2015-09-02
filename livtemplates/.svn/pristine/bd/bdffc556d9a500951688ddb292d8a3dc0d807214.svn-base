/*数据提交*/
function hg_program_shield_edit()
{
	return hg_ajax_submit('shield_form','','','shield_edit_back');
}
function shield_edit_back(obj)
{	
	var obj = obj[0];
	var ids = obj['id'];
	var theme = obj['theme'];
	var channel_id = obj['channel_id'];
	if (!obj)
	{
		return;
	}
	for(var i = 0; i < $('#shield_list li').length; i++)
	{
		$($('#shield_list li').find('input[name^="ids[]"]')[i]).val(ids[i]);
		$($('#shield_list li').find('input[name^="theme[]"]')[i]).val(theme[i]);
		$($('#shield_list li').find('input[name^="flag[]"]')[i]).val('');
		$($('#shield_list li').find('span[name^="delete_buttom[]"]')[i]).attr('onclick', 'hg_delete(this,'+ids[i]+')');
		
		if (!$($('#shield_list li').find('input[name^="dvr_delete[]"]')[i]).val())
		{
			$($('#shield_list li').find('span[name^="dvr_delete[]"]')[i]).attr('onclick', 'hg_dvr_delete(this,'+ids[i]+', '+channel_id+')');
		}
	}
	sub_disabled(1);
}

function hg_p_bg(obj,type)
{
	if (type == 'over')
	{
		$(obj).addClass('p_bg_1');
	}
	else
	{
		$(obj).removeClass('p_bg_1');
	}
}
/*添加屏蔽节目*/
function hg_add_shield(obj,type)
{
	var start_time = '';
	var end_time = '';
	var offset = parseInt($('#shield_toff').val());
	
	$('#shield_list').removeAttr('style');
	
//	$('#shield_list').show();
	if (type == 'p' && $(obj).prev().find('input[name^="end_time"]').val())
	{
		start_time = $(obj).prev().find('input[name^="end_time"]').val();
		end_time = $(obj).next().find('input[name^="start_time"]').val();
	}
	else if (type == 'p' && !$(obj).prev().find('input[name^="end_time"]').val())
	{
		end_time = $(obj).next().find('input[name^="start_time[]"]').val();
		if (end_time)
		{
			start_time = encode_time(decode_time(end_time) - offset);
		}
		if ((decode_time(end_time) - offset) <= 0)
		{
			start_time = '00:00:00';
		}
		else
		{
			append_p(obj,1);
		}
	}
	else
	{
		start_time = $('#shield_list li').last().find('input[name^="end_time[]"]').val();
		
		if (!start_time && $('#dates').val() == timenow().substr(0,10))
		{
			var TIMENOW = timenow(5);
			start_time = TIMENOW.substr(11);
		}
		else if (!start_time && $('#dates').val() != timenow().substr(0,10))
		{
			start_time = '00:00:00';
		}
		
		end_time = encode_time(decode_time(start_time) + offset);
	}
	
	if (end_time >= '23:59:59')
	{
		end_time = '23:59:59';
		$('#add_sub').hide();
	}
	
	var html = $($('#dom_tmp').html());
	html.find('input[name^="start_time"]').val(start_time);
	html.find('input[name^="end_time"]').val(end_time);
	html.find('input[name^="start"]').val(start_time);
	html.find('input[name^="end"]').val(end_time);
	html.find('input[name^="flag"]').val(1);
	
	$('#shield_list').append(html);
	
	if (type == 'p')
	{
		html.insertBefore($(obj));
		$(obj).remove();
	}
	
	if ($('#shield_list li').length > 0)
	{
		$('#add_sub').removeClass('p_bg_2');
	}
	
	hg_resize_nodeFrame();
	sub_disabled();
}

/*删除按钮显示*/
function hg_show_delete(obj,type)
{
	type ? $(obj).find('span').show() : $(obj).find('span').hide();
}

/*删除*/
var gObj = '';
function hg_delete(obj,id)
{
	gObj = obj;
	if (id)
	{
		if (confirm('确定删除吗！？'))
		{
			var url = "./run.php?mid=" + gMid + "&a=delete&id=" + id;
			hg_ajax_post(url,'','','delete_back');
		}
	}
	else
	{
		delete_dom(obj);
	}
}

function delete_back(obj)
{
	if (obj[0])
	{
		delete_dom(gObj);
	}
}

function delete_dom(obj)
{
	var gParentObj = $(obj).parent().parent().parent();
	if (gParentObj.prev().attr('name') != 'p' && gParentObj.next().attr('name') != 'p')
	{
		append_p(gParentObj,1);
	}
	
	if (gParentObj.prev().attr('name') === 'p' && gParentObj.next().attr('name') === 'p')
	{
		gParentObj.next().remove();
	}
	
	if (gParentObj.prev().attr('name') === 'p' && gParentObj.next().attr('class') == undefined)
	{
		gParentObj.prev().remove();
	}
	
	gParentObj.remove();
	
	if (gParentObj.find('input[name^="end_time[]"]').val() == '23:59:59')
	{
		$('#add_sub').show();
	}
	
	/*控制提交按钮*/
	$('#shield_list li').each(function(){
		if (!$(this).find('input[name^="flag[]"]').val())
		{
			sub_disabled(1);
		}
		else
		{
			sub_disabled();
		}
	});
	
	if ($('#shield_list li').length < 1)
	{
		$('#shield_list').css('border','none');
		$('#add_sub').addClass('p_bg_2');
		sub_disabled(1);
	}
	
	hg_resize_nodeFrame();
}
/*删除时移*/
function hg_dvr_delete(obj,id,channel_id)
{
	gObj = obj;
	if (!id || !channel_id)
	{
		return;
	}
	if (confirm('确定删除此时间段的时移吗！？'))
	{
		var url = "./run.php?mid=" + gMid + "&a=dvr_delete&id=" + id + "&channel_id=" + channel_id;
		hg_ajax_post(url,'','','dvr_delete_back');
	}
}

function dvr_delete_back(obj)
{
	gParentObj = $(gObj).parent().parent().parent();
	var obj = obj[0];
	if (obj['id'])
	{
		gParentObj.find('span[name^="box[]"]').css('background','#DF6564');
		$(gObj).remove();
	}
}
/*添加p标签*/
function append_p(obj,type)
{
	var p = $('<p name="p" class="p_bg" onclick="hg_add_shield(this,\'p\');" onmouseover="hg_p_bg(this,\'over\');" onmouseout="hg_p_bg(this,\'out\');"></p>');
	$('#shield_list').append(p);
	if (type == 1)
	{
		p.insertBefore($(obj));
	}
	else
	{
		p.insertAfter($(obj));
	}
}

/*时间编辑*/
function hg_time_edit(obj, type)
{
	var self_time = $(obj).val();
	var gParentObj = $(obj).parent().parent().parent().parent();
	var prev_time = '';
	var int_time = '';
	var other_time = '';
	
	if (type == 1)	 /*开始时间*/
	{
		if (gParentObj.prev().attr('name') === 'p')
		{
			prev_time = gParentObj.prev().prev().find('input[name^="end_time"]').val();		
		}
		else if (gParentObj.prev().attr('name') == undefined)
		{
			prev_time =gParentObj.prev().find('input[name^="end_time"]').val();
		}
		
		other_time = gParentObj.find('input[name^="end_time[]"]').val();
		int_time = gParentObj.find('input[name^="start[]"]').val();
		
		if (self_time && other_time && self_time >= other_time)
		{
			$(obj).val(int_time);
			jAlert('开始时间不能大于结束时间');
			return;
		}
		
		/*判断开始时间是否大于上个屏蔽节目的结束时间*/
		if (self_time < prev_time && self_time && prev_time)
		{
			$(obj).val(int_time);
			jAlert('开始时间不能小于上个屏蔽节目的结束时间');
			return;
		}
		
		if (self_time == prev_time && gParentObj.prev().attr('name') === 'p')
		{
			/*去掉p标签*/
			gParentObj.prev().remove();
		}
		else if (self_time > prev_time && gParentObj.prev().attr('name') != 'p')
		{
			/*添加p标签*/
			append_p(gParentObj,1);
		}
		else if (self_time && !prev_time && gParentObj.prev().attr('name') != 'p')
		{
			/*针对 00:00:00 添加p标签*/
			if (self_time == '00:00:00')
			{
				gParentObj.prev().remove();
			}
			else
			{
				append_p(gParentObj,1);
			}
		}
		gParentObj.find('input[name^="start[]"]').val(self_time);
	}
	else	/*结束时间*/
	{
		if (gParentObj.next().attr('name') === 'p')
		{
			prev_time = gParentObj.next().next().find('input[name^="start_time"]').val();		
		}
		else if (gParentObj.next().attr('name') == undefined)
		{
			prev_time = gParentObj.next().find('input[name^="start_time"]').val();
		}

		other_time = gParentObj.find('input[name^="start_time[]"]').val();
		int_time = gParentObj.find('input[name^="end[]"]').val();
		
		if (self_time && other_time && self_time <= other_time)
		{
			$(obj).val(int_time);
			jAlert('结束时间不能小于开始时间');
			return;
		}
		
		/*判断开始时间是否大于上个屏蔽节目的结束时间*/
		if (self_time > prev_time && self_time && prev_time)
		{
			$(obj).val(int_time);
			jAlert('结束时间不能大于下个屏蔽节目的开始时间');
			return;
		}
		
		if (self_time == prev_time && gParentObj.next().attr('name') === 'p')
		{
			/*去掉p标签*/
			gParentObj.next().remove();
		}
		else if (self_time < prev_time && gParentObj.next().attr('name') != 'p')
		{
			/*添加p标签*/
			append_p(gParentObj,0);
		}
		gParentObj.find('input[name^="end[]"]').val(self_time);
		
		if (self_time == '23:59:59')
		{
			$('#add_sub').hide();
		}
	}
	
	if (int_time == '23:59:59')
	{
		$('#add_sub').show();
	}
	
	gParentObj.find('input[name^="flag[]"]').val(1);
	sub_disabled();
}

function hg_theme_edit(obj)
{
	var gParentObj = $(obj).parent().parent().parent().parent();
	gParentObj.find('input[name^="flag[]"]').val(1);
	sub_disabled();
}

/*保存按钮*/
function sub_disabled(type)
{
	if (!type)
	{
		if($('#sub').hasClass('button_none'))
		{
			$('#sub').removeAttr('disabled');
			$('#sub').removeAttr('class');
			$('#sub').attr('class','button_4');
		}
	}
	else
	{
		$('#sub').addClass('button_none');
		$('#sub').attr('disabled','disabled');
	}
}

/*根据日期选择屏蔽节目*/
function hg_shield_dades(channel_id)
{
	var dates = $("#dates").val();

	var url = "./run.php?mid=" + gMid + "&infrm=1&channel_id=" + channel_id + "&dates=" + dates;
	location.href = url;
}

function timenow(offset)
{
	var s_time = new Date();
	var offset = offset ? offset : 0;
	
	var Y = s_time.getFullYear();
	var m = s_time.getMonth()+1;
	var d = s_time.getDate();
	
	var H = s_time.getHours();
	var i = s_time.getMinutes()+offset;
	var s = s_time.getSeconds();
	
	H = H<10 ? '0'+H : H;
	i = i<10 ? '0'+i : i;
	s = s<10 ? '0'+s : s;
	m = m<10 ? '0'+m : m;
	d = d<10 ? '0'+d : d;
	var TimeNow = Y+'-'+m+'-'+d+' '+ H+':'+ i+':'+s;
	return TimeNow;
}
/*格式化时间转化为秒*/
function decode_time(str)
{	
	if(str)
	{
		var arr = str.split(':');
		var hours = arr[0]*3600;
		var mins = parseInt(arr[1])*60;
		var seconds = parseInt(arr[2]);
		var stime = hours + mins + seconds;
		return stime;
	}
	
}

function encode_time(total)
{	
	if(total)
	{
		var H = parseInt(total/3600);
		var m = parseInt((total - H*3600)/60);
		var s = parseInt(total - H*3600 - m*60);
		
		H = H<10 ? '0'+H : H;
		m = m<10 ? '0'+m : m;
		s = s<10 ? '0'+s : s;

		var times = H + ':' + m + ':' + s;
		return times;
	}
}








