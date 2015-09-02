function hg_plan_repeat(e,type)
{
	if(!type)
	{
		if($(e).attr('checked'))
		{
			$('#week_date').slideDown(400);			
			$("div[id^=week_date] input[type=checkbox]").removeAttr('checked');
			//$('#date_list').hide();
		}
		else
		{
			$('#week_date').slideUp(400);
			//$('#date_list').show();
			$("div[id^=week_date] input[type=checkbox]").removeAttr('checked');
		}
	}
	else
	{
		if(type == 2)
		{
			if($("div[id^=week_date] input[id^=week_day_]:checked").length < 7)
			{
				$("#every_day").removeAttr('checked');
			}
			else
			{
				$("#every_day").attr('checked','checked');
			}
		}
		else
		{
			if($(e).attr('checked'))
			{
				$("div[id^=week_date] input[id^=week_day_]").attr('checked','checked');
			}
			else
			{
				$("div[id^=week_date] input[id^=week_day_]").removeAttr('checked');
			}
		}
		
	}
}
function hg_plan_check_day()
{
	if(!trim($("#dates").val()))
	{
		$("#date_tips").html('请先选择日期').fadeIn(1000).fadeOut(1000);		
        var nstr = new Date(); //当前Date资讯
		var nows = nstr.getFullYear() + '-' + (nstr.getMonth()+1) + '-' + nstr.getDate(); //今日日期
		$("#dates").val(nows);
		return true;
	}
	else
	{
		return true;
	}
}

function hg_plan_toff(type)
{
	if(!trim($("#dates").val()))
	{
		$("#day_tips").html('日期不为空').fadeIn(1000).fadeOut(1000);
		var nstr = new Date(); //当前Date资讯
		var nows = nstr.getFullYear() + '-' + (nstr.getMonth()+1) + '-' + nstr.getDate(); //今日日期
		$("#dates").val(nows);
		return true;
	}
	else
	{
		var dates = trim($("#dates").val());
		if(trim($("#start_times").val()) && trim($("#end_times").val()))
		{
			var toff = dateToUnix(dates + ' ' + trim($("#end_times").val())) - dateToUnix(dates + ' ' + trim($("#start_times").val()));
			if(toff<0)
			{
				alert('该计划将会录制到次日的'+trim($("#end_times").val()));
				toff = toff+24*3600;
			}

			var min = Math.floor(toff/60);
			var sec = toff - min*60;
			var ret = (min ? min + "'":'')+(sec ? sec + "''":'');
			$("#toff").html(ret);
		}
	}
}

function hg_form_check()
{
	if(!trim($("#channel_id").val()))
	{
		$("#channel_tips").html('请选择频道').fadeIn(1000).fadeOut(1000);
		return false;
	}

	if(!trim($("#dates").val()))
	{
		$("#date_tips").html('请填写日期').fadeIn(1000).fadeOut(1000);
		var nstr = new Date(); //当前Date资讯
		var nows = nstr.getFullYear() + '-' + (nstr.getMonth()+1) + '-' + nstr.getDate(); //今日日期
		$("#dates").val(nows);
		//return false;
	}
	var timestamp = new Date().getTime();

	if($("div[id^=week_date] input[id^=week_day_]:checked").length < 1)
	{
		if(dateToUnix(trim($("#dates").val()) + ' ' + trim($("#start_times").val())) <= (parseInt(timestamp/1000)-20))
		{
			$("#day_tips").html('录制开始时间必须大于当前时间').fadeIn(1000).fadeOut(1000);
			return false;	
		}
	}
	if((dateToUnix(trim($("#dates").val()) + ' ' + trim($("#end_times").val()))-dateToUnix(trim($("#dates").val()) + ' ' + trim($("#start_times").val()))) == 0)
	{
		$("#day_tips").html('结束时间必须大于开始时间').fadeIn(1000).fadeOut(1000);
		return false;	
	}
	
	if(!trim($("#start_times").val()) || !trim($("#end_times").val()))
	{
		$("#day_tips").html('请填写完整的录制时间').fadeIn(1000).fadeOut(1000);
		return false;	
	}

	if(parseInt($("#item").val()) < 0)
	{
		//$("#item_tips").html('请选择分类').fadeIn(1000).fadeOut(1000);
		//return false;	
	}
	
/*
	if(parseInt($("#server_id").val()) < 0)
	{
		$("#server_id_tips").html('请选择录制服务').fadeIn(1000).fadeOut(1000);
		return false;	
	}	
*/
	
	return true;
}

function hg_plan_channel(e,id,save_time)
{
	$("#channel_list").slideUp();
	$("#default_value").slideDown();
	$("#select_repeat").slideDown();
	$("#channel_name").html($(e).children('span').html());
	$("#channel_id").val(id);
	$("#show_span").html("重新选择频道");
	
	flags = 1;
}

function hg_request_program()
{
	if($("#other_list").attr('_slide_type') == 'plan')
	{
		$("#other_list").attr('_slide_type','program');
		$("#other_list").slideUp();
		var channel_id = parseInt($("#channel_id").val()) ? parseInt($("#channel_id").val()) : 0;
		var url = './run.php?mid=' + gMid + '&a=get_greater_program&channel_id=' + channel_id;
		hg_request_to(url);
	}
	else
	{
		if(!$("#other_list").is(":visible"))
		{
			$("#other_list").slideUp();
			var channel_id = parseInt($("#channel_id").val()) ? parseInt($("#channel_id").val()) : 0;
			var url = './run.php?mid=' + gMid + '&a=get_greater_program&channel_id=' + channel_id;
			hg_request_to(url);
		}
		else
		{
			$("#other_list").slideUp();
		}
	}	
}

function hg_reponse_program(html)
{
	$("#other_list").html(html).slideDown().show();
}


function hg_request_plan()
{
	if($("#other_list").attr('_slide_type') == 'program')
	{
		$("#other_list").attr('_slide_type','plan');
		$("#other_list").slideUp();
		var channel_id = parseInt($("#channel_id").val()) ? parseInt($("#channel_id").val()) : 0;
		var url = './run.php?mid=' + gMid + '&a=get_plan&channel_id=' + channel_id;
		hg_request_to(url);
	}
	else
	{
		if(!$("#other_list").is(":visible"))
		{
			var channel_id = parseInt($("#channel_id").val()) ? parseInt($("#channel_id").val()) : 0;
			var url = './run.php?mid=' + gMid + '&a=get_plan&channel_id=' + channel_id;
			hg_request_to(url);
		}
		else
		{
			$("#other_list").slideUp();
		}
	}
}

function hg_reponse_plan(html)
{
	$("#other_list").html(html).slideDown().show();
}