function hg_row_show(obj, onout, type)
{
	if (onout == 'on')
	{
		$(obj).addClass('interactive_hover');
		$(obj).find('.c_m').removeClass('bg_' + type);
		$(obj).find('.c_b').removeClass('bg_' + type);
		$(obj).find('.c_m').addClass('bg_');
		$(obj).find('.c_b').addClass('bg_');
	}
	else
	{
		$(obj).removeClass('interactive_hover');
		$(obj).find('.c_m').addClass('bg_' + type);
		$(obj).find('.c_b').addClass('bg_' + type);
		$(obj).find('.c_m').removeClass('bg_');
		$(obj).find('.c_b').removeClass('bg_');
	}
}
/*互动类型操作*/
function hg_interactive_type(id, type)
{
	if (!id)
	{
		return false;
	}
	var url = "./run.php?mid=" + gMid + "&a=type&id=" + id + "&type=" + type;
	hg_ajax_post(url,'','','interactive_type_back');
}

function interactive_type_back(obj)
{
	var obj = obj[0];
	var ids = obj['id'].split(',');
	if (!ids)
	{
		return false;
	}
	var type = obj['type'];
	
	for (var i in ids)
	{
		var id = ids[i];
		$('#r_' + id).find('div[name="c_m"]').removeAttr('class');
		$('#r_' + id).find('div[name="c_b"]').removeAttr('class');
		$('#r_' + id).find('div[name="c_m"]').attr('class', 'c_m bg_' + type);
		$('#r_' + id).find('div[name="c_b"]').attr('class', 'c_b bg_' + type);
		$('#r_' + id).attr('onmouseover', 'hg_row_show(this, "on", '+type+')');
		$('#r_' + id).attr('onmouseout', 'hg_row_show(this, "out", '+type+')');

		$('#type_' + id).find('.font_color').removeClass('font_color');
		
		if ($('#type_' + id).find('a[name="'+type+'"]').attr('name') == type)
		{
			$('#type_' + id).find('a[name="'+type+'"]').addClass('font_color');
		}
	}
}

function hg_interactive_checkbox(obj)
{
	if ($(obj).parent().find('input[name^="infolist[]"]').attr('checked') == 'checked')
	{
		$(obj).parent().find('input[name^="infolist[]"]').removeAttr('checked');
	}
	else
	{
		$(obj).parent().find('input[name^="infolist[]"]').attr('checked', 'checked');
	}
}

function hg_interactive_checkall(obj)
{
	if ($(obj).attr('checked') == 'checked')
	{
		$('form').find('input[name^="infolist[]"]').attr('checked', 'checked');
	}
	else
	{
		$('form').find('input[name^="infolist[]"]').removeAttr('checked');
	}
}
/*互动审核*/
function hg_audit(id, audit)
{
	if (!id)
	{
		return false;
	}
	var url = "./run.php?mid=" + gMid + "&a=audit&id=" + id + "&audit=" + audit;
	hg_ajax_post(url,'','','audit_back');
}

function audit_back(obj)
{
	var obj = obj[0];
	var ids = obj['id'].split(',');
	if (!ids)
	{
		return false;
	}
	var audit = obj['audit'];

	var audit_text = '';
	if (audit == 1)
	{
		audit_text = '已审核';
	}
	else if (audit == 2)
	{
		audit_text = '被打回';
	}
	else
	{
		audit_text = '待审核';
	}
		
	for (var i in ids)
	{
		var id = ids[i];
		$('#r_' +　id).find('span[name="status_dom"]').html(audit_text);
	}
}

/*获取站外平台信息*/
function hg_get_plat()
{
	if ($('#plat_info').css('display') == 'none')
	{
		$('#plat_info').show();
		var url = "./run.php?mid=" + gMid + "&a=get_plat";
		hg_ajax_post(url,'','','get_plat_back');
	}
	else
	{
		var str='<div id="plat_loading" class="plat_loading"></div>';
		$("#plat_info").html(str);
		$("#plat_info").hide();	
	}
}

function get_plat_back(html)
{
	if (html)
	{
		$('#plat_info').html(html);
	}
}

function hg_oauthlogin(plat_id, type)
{
	/*
	var channel_id = $('#channel_id_' +　plat_id).val();
	if (channel_id == -1)
	{
		jAlert('请选择频道');
		return;
	}
	*/
	var url = "./run.php?mid=" + gMid + "&a=oauthlogin&plat_id=" + plat_id + "&type=" + type + "&_mid=" + gMid;
	hg_ajax_post(url,'','','oauthlogin_back');
}
function oauthlogin_back(obj)
{
	var obj = obj[0];
	if (obj.url)
	{
		location.href = obj.url;
	}
	return;
}

/*删除*/
function hg_delete(id)
{
	if (confirm('确定删除该选项吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
		hg_ajax_post(url);
	}
}

/*重新授权*/
function hg_retset_oauthlogin(id)
{
	if (!id)
	{
		jAlert('id不能为空');
		return;
	}
	var url = "./run.php?mid=" + gMid + "&a=retset_oauthlogin&id=" + id + "&_mid=" + gMid;
	hg_ajax_post(url,'','','retset_oauthlogin_back');
}

function retset_oauthlogin_back(obj)
{
	var obj = obj[0];
	if (obj.url)
	{
		location.href = obj.url;
	}
	return;
}

/*数据提交*/
function hg_program_edit()
{
	return hg_ajax_submit('program_form','','','program_edit_back');
}
function program_edit_back(obj)
{	
	var obj = obj[0];
	if (!obj)
	{
		return;
	}
	for(var i = 0; i < $('#program_list li').length; i++)
	{
		$($('#program_list li').find('input[name^="ids[]"]')[i]).val(obj[i]);
		$($('#program_list li').find('span[name^="delete_buttom[]"]')[i]).attr('onclick', 'hg_program_delete(this,'+obj[i]+')');
	}
}
function hg_program_show(channel_id)
{
	var dates = $('#dates').val();
	var url = "./run.php?mid=" + gMid + "&infrm=1&channel_id=" + channel_id + "&dates="+dates;
	location.href = url;
}

/*添加节目*/
function hg_program_add()
{
	var start_end = $('#start_end').val();
	var TimeNow = timenow(0);
	if (start_end)
	{
		start_end = start_end.split(',');
		var start = start_end[0];
		var end = start_end[1];
	}
	
	if (TimeNow)
	{
		TimeNow = TimeNow.split(' ');
		var dates = TimeNow[0];
		var times = TimeNow[1];
	}

	if ($('#dates').val() < dates)
	{
		jAlert('日期已过，不能添加节目');
		return;
	}
	var program_html = $('#program_html').html();
	/*
	$(program_html).find('input[name^="start_time[]"]').val(start);
	$(program_html).find('input[name^="end_time[]"]').val(end);
	*/
	$('#program_list').append(program_html);
}

function hg_show_delete(obj,type)
{
	type ? $(obj).find('span').show() : $(obj).find('span').hide();
}

var gProgramObj = '';
function hg_program_delete(obj,id)
{
	gProgramObj = obj;
	if (id)
	{
		if (confirm('确定删除吗！？'))
		{
			var url = "./run.php?mid=" + gMid + "&a=program_delete&id=" + id;
			hg_ajax_post(url,'','','program_delete_back');
		}
	}
	else
	{
		$(obj).parent().parent().parent().remove();
	}
}

function program_delete_back(obj)
{
	if (obj[0])
	{
		$(gProgramObj).parent().parent().parent().remove();
	}
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
/*根据时间筛选节目*/
function hg_start_end_hidden()
{
	var start_end = $('#start_end').val();
	$('#start2end').val(start_end);
	var channel_id = $('#channel_id').val();
	var dates = $('#dates').val();
	var url = "./run.php?mid=" + gMid + "&infrm=1&channel_id=" + channel_id + "&dates="+dates+"&start_end=" + start_end;
	location.href = url;
}
/*根据日期选择节目*/
function hg_program_dates(obj)
{
	var dates = $(obj).val();

	var channel_id = $('#channel_id').val();
	$('#dates').val(dates);
	var url = "./run.php?mid=" + gMid + "&infrm=1&channel_id=" + channel_id + "&dates="+dates;
	location.href = url;
}















