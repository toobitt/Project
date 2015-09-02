/* $Id: topic.js 8395 2012-03-23 07:24:31Z repheal $ */
var gHtml = '';
var gClick = '';
var gPid = '';

function hg_request_source(id) {
	var tips = '';
	var start_time = $("#dates").val()+" "+$("#start_time"+id).val();
	var end_time = $("#dates").val()+" "+$("#end_time"+id).val();
	var timenow = (Date.parse(new Date()))/1000;
	if(timenow)
	{
		if(dateToUnix(start_time) >= dateToUnix(end_time))
		{
			tips = '结束时间必须大于开始时间';
		}

		if(dateToUnix(end_time) <= timenow)
		{
			tips = '结束时间必须大于当前时间';
		}
	}
	if(tips)
	{
		hg_single_tips(id,tips);
	}
	else
	{
		if($("#lb_column_"+id).html())
		{
			if($('#lb_column_'+id).css('display')=='none')
			{
			//	$('#lb_column_'+id).html(data);
				$('#lb_column_'+id).show();
				$('#lb-btn_'+id).addClass('cur');
				$('#update_item_'+id).addClass('b');
			}
			else
			{
				$('#lb_column_'+id).hide();
				$('#lb-btn_'+id).removeClass('cur');
				$('#update_item_'+id).removeClass('b');
			}
		}
		else
		{
			var url = './run.php?'+'mid=' + gMid + '&a=check_day&id=' + id + '&channel_id='+$("#channel_id").val()+"&start_time="+dateToUnix(start_time)+"&end_time=" + dateToUnix(end_time);
			hg_request_to(url);		
		}
	}
 }

function hg_move_focus(id)
{
	$('#focus_cache_'+id).focus();
}

function hg_show_delete(id,type)
{
	var lb=0,del=0;	
	type ? $("#del_" + id).show() : $("#del_" + id).hide();
}

function hg_delete_topic(id)
{
	if(confirm("确定要删除该条记录？！"))
	{
		var start_time = $("#li_" + id).find('input[id^=start_time]').val();
		var end_time = $("#li_" + id).find('input[id^=end_time]').val();
		if($("#li_"+id).prev('li').attr('class') == 'none' )
		{
			start_time = $("#li_" + id).prev('li').find('input[id^=start_time]').val();
			$("#li_" + id).prev('li').remove();
		}
		if($("#li_" + id).next('li').attr('class') == 'none' )
		{
			end_time = $("#li_" + id).next('li').find('input[id^=end_time]').val();
			$("#li_" + id).next('li').remove();
		}		
		hg_create_null(id,start_time,end_time,1);
		if(!gHtml)
		{
			gHtml = $('<div></div>').append($("#li_" + id).clone()).html();
		}
		$("#li_" + id).remove();
		if(!gClick)
		{
			gClick = 1;
			$("#save_edit").removeClass('button_none');
			gPid = hg_add2Task({'name':'话题未保存'});
		}
	}
}

function hg_call_topic(data)
{
	$("#single_day_ul").html(data);
	hg_taskCompleted(gPid);
	$("#save_edit").addClass('button_none');
	gClick = 0;
	hg_resize_nodeFrame();
}

function hg_check_input_get(e,id,type)
{
	switch(type)
	{
		case 'start':
			var dates = $("#dates").val();
			var times = dateToUnix(dates +" "+$(e).val());
			var start =	dateToUnix(dates +" "+$("#start_time"+id).val());
			var end =	dateToUnix(dates +" "+$("#end_time"+id).val());

			if(!start)
			{
				hg_single_tips(id,'开始时间不为空');
				return false;
			}

			if(end <= start)
			{
				hg_single_tips(id,'结束时间必须大于开始时间');
				return false;
			}
			else
			{
				var prev_end = $("#li_"+id).prevAll('li').find('input[id^=end_time]').val();//上一节点的结束时间为当前节点的开始时间
				prev_end = prev_end ? prev_end : '00:00:00';
				var prev_end_unix = dateToUnix(dates + " " + prev_end);
				if(start < prev_end_unix)
				{
					var tips = hg_check_all_time(id,(prev_end_unix-start),0);
					if(tips)
					{
						if(tips[0])
						{
							if(confirm(tips[1]))
							{
								$("#single_day_ul").sortable('disable');
								hg_change_all_time(id,(prev_end_unix-start),0);
							}
							else
							{
								$(e).val(gStart[id]);
								hg_verify_dom(id,1);
							}
							return true;
						}
						else
						{
							hg_single_tips(id,tips[1]);
							$(e).val(gStart[id]);
							hg_verify_dom(id,1);
							return true;
						}
					}
					else
					{
						hg_verify_all_time();
					}
					return false;
				}
				else
				{
					hg_verify_dom(id,0);
					hg_check_change(id,($(e).val() != gStart[id]?0:1));
					return true;
				}
			}
			break;
		case 'name':
			if(!$(e).val())
			{
				hg_single_tips(id,'节目名称不为空');
			}
			break;
		case 'end':
			var dates = $("#dates").val();
			var times = dateToUnix(dates + " " + $(e).val());
			var start =	$("#start_time"+id).val() ? dateToUnix(dates + " " + $("#start_time"+id).val()) : "";
			var end = $("#end_time"+id).val() ? dateToUnix(dates + " " + $("#end_time"+id).val()) : "";
			
			if(!end)
			{
				hg_single_tips(id,'结束时间不为空');
				return false;
			}

			if(end <= start)
			{
				hg_single_tips(id,'结束时间必须大于开始时间');
				return false;
			}
			else
			{
				var next_start = $("#li_"+id).nextAll('li').find('input[id^=start_time]').val();//下一节点的开始时间为当前节点的结束时间
				next_start = next_start ? next_start : '23:59:59';
				var next_start_unix = dateToUnix(dates + " " + next_start);
				if(end > next_start_unix)
				{
					var tips = hg_check_all_time(id,(end-next_start_unix),1);
					if(tips)
					{
						if(tips[0])
						{
							if(confirm(tips[1]))
							{
								$("#single_day_ul").sortable( 'disable' );
								hg_change_all_time(id,(end-next_start_unix),1);
							}
							else
							{
								$(e).val(gEnd[id]);
								hg_verify_dom(id,1);
							}
							return true;
						}
						else
						{
							hg_single_tips(id,tips[1]);
							$(e).val(gEnd[id]);
							hg_verify_dom(id,1);
							return true;
						}
					}
					else
					{
						hg_verify_all_time();
					}
					return false;
				}
				else
				{
					hg_verify_dom(id,1);
					hg_check_change(id,($(e).val() != gEnd[id]?0:1));
					return true;
				}
			}
			break;
		default:
			break;
	}
	hg_check_change(id);
}

function hg_verify_all_time()
{
	var dates = $("#dates").val();
	$("#single_day_ul").children('li').each(function(){
		var this_start = $(this).find('input[id^=start_time]').val();
		var this_end = $(this).find('input[id^=end_time]').val();
		var this_id = $(this).find('input[id^=pid_]').val();
		var start_unix = dateToUnix(dates + " " + this_start);
		var end_unix = dateToUnix(dates + " " + this_end);
		var prev_end = $(this).prev('li').find('input[id^=end_time]').val() ? $(this).prev('li').find('input[id^=end_time]').val() :'00:00:00';
		var toff = end_unix - start_unix;

		if(prev_end != this_start)
		{
			this_start = prev_end;
		}
		this_end = unixToDate(dateToUnix(dates + " " + this_start)+toff,'H:i:s');
		$(this).find('input[id^=start_time]').val(this_start);
		$(this).find('input[id^=end_time]').val(this_end);
	});
}

function hg_href_single(channel_id)
{
	var url = "./run.php?mid=" + gMid + "&infrm=1&channel_id=" + channel_id + "&dates="+$("#dates").val();
	location.href = url;
}

function hg_check_all_time(id,cha,type)
{
	var dates = $("#dates").val();
	var start =	dateToUnix(dates + " " + $("#start_time" + id).val()); //当前这个节点的开始时间
	var end = dateToUnix(dates + " " + $("#end_time" + id).val()); //当前这个节点的结束时间
	var prev_top = dateToUnix(dates + " 00:00:00");//此日期的上限
	var next_bottom = dateToUnix(dates + " 23:59:59");//此日期的下限
	if(!type)//向上
	{
		if(start < prev_top)
		{
			var tips = new Array(0,'一天不能小于00:00:00');
			return tips;
		}
		else
		{	
			var verify_cha = 0;
			if($("#li_"+id).prev('li').attr('class') == 'none')
			{
				var start_here = dateToUnix(dates + " " + $("#li_"+id).prev('li').find("input[id^=start_time]").val());
				var end_here = dateToUnix(dates + " " + $("#li_"+id).prev('li').find("input[id^=end_time]").val());
				verify_cha = cha - (end_here - start_here);
			}

			if(verify_cha <= 0)
			{
				hg_change_all_time(id,cha,type);
				return false;
			}
			else
			{			
				$('#li_' + id).prevAll('li').each(function(){
					var start_here = dateToUnix(dates + " " + $(this).find("input[id^=start_time]").val());
					var end_here = dateToUnix(dates + " " + $(this).find("input[id^=end_time]").val());
					cha = cha - (end_here - start_here);
				 });

				if(cha <= 0)
				{
					var tips = new Array(1,'如此填写会覆盖向上的部分节目？！');
				}
				else
				{
					var lave_hour = Math.floor(cha/3600);
					var lave_min = Math.floor((cha - lave_hour*3600)/60);
					var lave_sec = cha - lave_min*60 - lave_hour*3600;
					var cha_tips = '';
					if(lave_hour)
					{
						cha_tips += lave_hour + '时';
					}
					if(lave_min || cha_tips)
					{
						cha_tips += lave_min + '分';
					}
					cha_tips += lave_sec + '秒';
					var tips = new Array(0,'向上的时间线没有足够的空余的时间,还缺少' + cha_tips + '！');
				}
				return tips;
			}
		}
	}
	else//向下
	{
		if(end > next_bottom)
		{
			var tips = new Array(0,'一天不能超过23:59:59');
			return tips;
		}
		else
		{
			var verify_cha = 0;
			if($("#li_"+id).next('li').attr('class') == 'none')
			{
				var start_here = dateToUnix(dates + " " + $("#li_"+id).next('li').find("input[id^=start_time]").val());
				var end_here = dateToUnix(dates + " " + $("#li_"+id).next('li').find("input[id^=end_time]").val());
				verify_cha = cha - (end_here - start_here);
			}

			if(verify_cha <= 0)
			{
				hg_change_all_time(id,cha,type);
				return false;
			}
			else
			{
				$('#li_' + id).nextAll('li').each(function(){
					var start_here = dateToUnix(dates + " " + $(this).find("input[id^=start_time]").val());
					var end_here = dateToUnix(dates + " " + $(this).find("input[id^=end_time]").val());
				//	var id_here = $(this).find("input[id^=pid_]").val();
					cha = cha - (end_here - start_here);
				 });
				if(cha <= 0)
				{
					var tips = new Array(1,'如此填写会覆盖向下的部分节目？！');
				}
				else
				{
					var lave_hour = Math.floor(cha/3600);
					var lave_min = Math.floor((cha - lave_hour*3600)/60);
					var lave_sec = cha - lave_min*60 - lave_hour*3600;
					var cha_tips = '';
					if(lave_hour)
					{
						cha_tips += lave_hour + '时';
					}
					if(lave_min || cha_tips)
					{
						cha_tips += lave_min + '分';
					}
					cha_tips += lave_sec + '秒';
					var tips = new Array(0,'向下的时间线没有足够的空余的时间,还缺少' + cha_tips + '！');
				}
				return tips;
			}
		}
	}
}

function hg_change_all_time(id,cha,type) //肯定是有足够的空余时间
{
	hg_check_change(id,0);
	var dates = $("#dates").val();
	var start =	dateToUnix(dates + " " + $("#start_time" + id).val()); //当前这个节点的开始时间
	var end = dateToUnix(dates + " " + $("#end_time" + id).val()); //当前这个节点的结束时间
	var prev_top = dateToUnix(dates + " 00:00:00");//此日期的上限
	var next_bottom = dateToUnix(dates + " 23:59:59");//此日期的下限
	if(!type)//向上
	{
		if(start < prev_top)
		{
			return true;
		}
		else
		{
			$('#li_' + id).prevAll('li').each(function(){
				var start_here = dateToUnix(dates + " " + $(this).find("input[id^=start_time]").val());
				var end_here = dateToUnix(dates + " " + $(this).find("input[id^=end_time]").val());
				var cha_here = end_here - start_here;
				var id_here = $(this).find("input[id^=pid_]").val();
				if(start_here < (end_here-cha))
				{
					$(this).find("input[id^=end_time]").val(unixToDate(end_here-cha,'H:i:s'));
					if($(this).find("input[id^=showcolor_]").val())
					{
						hg_check_change(id_here,0);
					}
					$("#single_day_ul").sortable('enable');
					return false;
				}
				else
				{
					$(this).remove();
					hg_resize_nodeFrame();
					cha = cha - (end_here - start_here);
				}
			 });
			 return true;
		}
	}
	else//向下
	{
		if(end > next_bottom)
		{
			return true;
		}
		else
		{
			$('#li_' + id).nextAll('li').each(function(){
				var start_here = dateToUnix(dates + " " + $(this).find("input[id^=start_time]").val());
				var end_here = dateToUnix(dates + " " + $(this).find("input[id^=end_time]").val());
				var cha_here = end_here - start_here;
				var id_here = $(this).find("input[id^=pid_]").val();
				if(end_here > (start_here+cha))
				{
					$(this).find("input[id^=start_time]").val(unixToDate(start_here+cha,'H:i:s'));
					if($(this).find("input[id^=showcolor_]").val())
					{
						hg_check_change(id_here,0);
					}
					return false;
				}
				else
				{
					$(this).remove();
					hg_resize_nodeFrame();
					cha = cha - (end_here - start_here);
				}
			});
			 return true;			
		}
	}
}

function hg_create_single(e)
{	
    var re = /\"li_([0-9]*)\"/g;	
	var html = $('<div></div>').append($(e).prev('li[class="day_default"]').clone()).html();
	if(!html)
	{
		html = $('<div></div>').append($(e).next('li[class="day_default"]').clone()).html();
	}
	
	if(!html)
	{
		html = gHtml;	
	}

    var arr = re.exec(html);
	var pid = arr[1];
	var start = $(e).find('input[id^=start_time]').val();
	var end = $(e).find('input[id^=end_time]').val();
	//var color = $(e).find("input[id^=showcolor_]").val();

	var start_unix = dateToUnix($("#dates").val() + ' ' + start);
	var end_unix = dateToUnix($("#dates").val() + ' ' + end);
	if((end_unix-start_unix)>3600)
	{
		end = unixToDate(start_unix+3600,'H:i:s');
		end_unix = dateToUnix($("#dates").val() + ' ' + end);
	}
	var reg = eval("/"+pid+"/ig");
	var new_id = hg_rand_num(10);
	html = html.replace(reg, new_id);

	$(e).after(html);
	$(e).remove();
	$("#start_time" + new_id).val(start);
	$("#end_time" + new_id).val(end);
	$("#name" + new_id).val('新话题');
	if(!$("#new_" + new_id).val())
	{
		var new_tips = '<input type="hidden" name="new[' + new_id + ']" id="new_' + new_id + '" value="1"/>';
		$("#checke_" + new_id).after(new_tips);
	}
	arr_color = '#DF6564,#FEF2F2'.split(",");
	var bg = arr_color[1];
	$("#update_item_" + new_id).css({'background':bg});

	hg_check_change(new_id);

	hg_verify_dom(new_id,1);
	hg_resize_nodeFrame();
}

function hg_check_change(id,type)
{
	if(type) //0--修改 1--还原
	{	
		$("#checke_" + id).val(0);	
	}
	else
	{
		$("#checke_" + id).val(1);
	}

	if(!gClick)
	{
		gClick = 1;
		$("#save_edit").removeClass('button_none');
		if(gPid == -1)
		{
			gPid = hg_add2Task({'name':'话题未保存'});
		}
	}
}

function hg_verify_dom(id,type)
{
	dates = $("#dates").val();
	if(!type)//向上
	{
		if($("#li_"+id).prev('li').attr('class') == 'none' )
		{
			$("#li_"+id).prev('li').remove();
			hg_resize_nodeFrame();
		}
		var prev_end = $("#li_"+id).prevAll('li[class="day_default"]').find('input[id^=end_time]').val();
		prev_end = prev_end ? prev_end : '00:00:00';
		if($("#start_time"+id).val() != prev_end && prev_end)
		{
			hg_create_null(id,prev_end,$("#start_time"+id).val(),0);
		}
	}
	else
	{
		if($("#li_"+id).next('li').attr('class') == 'none' )
		{
			$("#li_"+id).next('li').remove();
			hg_resize_nodeFrame();
		}
		var next_start = $("#li_"+id).nextAll('li[class="day_default"]').find('input[id^=start_time]').val();
		next_start = next_start ? next_start : '23:59:59';
		if($("#end_time"+id).val() != next_start && next_start)
		{
			hg_create_null(id,$("#end_time"+id).val(),next_start,1);
		}
	}
	return true;
}

function hg_delete_program(id)
{
	if(confirm("确定要删除该条记录？！"))
	{
		var start_time = $("#li_" + id).find('input[id^=start_time]').val();
		var end_time = $("#li_" + id).find('input[id^=end_time]').val();
		if($("#li_"+id).prev('li').attr('class') == 'none' )
		{
			start_time = $("#li_" + id).prev('li').find('input[id^=start_time]').val();
			$("#li_" + id).prev('li').remove();
		}
		if($("#li_" + id).next('li').attr('class') == 'none' )
		{
			end_time = $("#li_" + id).next('li').find('input[id^=end_time]').val();
			$("#li_" + id).next('li').remove();
		}		
		hg_create_null(id,start_time,end_time,1);
		alert(gHtml);
		if(!gHtml)
		{
			gHtml = $('<div></div>').append($("#li_" + id).clone()).html();
			
		}
		$("#li_" + id).remove();
		if(!gClick)
		{
			gClick = 1;
			$("#save_edit").removeClass('button_none');
			gPid = hg_add2Task({'name':'节目单未保存'});
		}
	}
}

function hg_create_null(id,start_time,end_time,type)
{
	var new_id = hg_rand_num(10);
	var str = '<li class="none" onclick="hg_create_single(this);" onmousedown="hg_move_focus();"><input type="hidden" id="start_time' + new_id + '" value="' + start_time + '"/><input type="hidden" id="end_time' + new_id + '" value="' + end_time + '"/><input id="pid_' + new_id + '" value="' + new_id + '" type="hidden"/><input type="text" style="width: 0px; height: 0px; position: absolute; margin-left: -100px;" id="focus_cache_' + new_id + '" value=""></li>';
	if(type)
	{
		$("#li_" + id).after(str);
	}
	else
	{
		$("#li_" + id).before(str);
	}
	hg_resize_nodeFrame();
}


function hg_single_tips(id,str)
{
	$("#single_tips_" + id).html(str).fadeIn(3000).fadeOut(3000);
}

function hg_update_single()
{
	if(gClick)
	{
		return hg_ajax_submit('theme_form');
	}
	else
	{
		return false;
	}
}

