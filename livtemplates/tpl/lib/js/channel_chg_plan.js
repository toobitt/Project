var gCid = 0;
var gEndTime = '';
$(function(){
	gEndTime = $($('#chg_box').find('.input_div').last().find('.chg_plan_input')[1]).val();
	if($($('#chg_box').find('.input_div').last().find('.chg_plan_input')[1]).val() == '23:59:59')
	{
		$('#div_box_0').hide();
	}
	else
	{
		$('#div_box_0').show();
	}
})
function hg_add_plan(obj,p)
{
	sub_disabled();
	var s_time = new Date();

	var Y = s_time.getFullYear();
	var m = s_time.getMonth()+1;
	var d = s_time.getDate();

	var N = s_time.getHours();
	var H = s_time.getHours()+1;
	var E = s_time.getHours()+2;
	var i = s_time.getMinutes();
	var s = s_time.getSeconds();
	var N = N<10 ? '0'+N : N;
	var H = H<10 ? '0'+H : H;
	var E = E<10 ? '0'+E : E;
	var i = i<10 ? '0'+i : i;
	var s = s<10 ? '0'+s : s;
	var m = m<10 ? '0'+m : m;
	var d = d<10 ? '0'+d : d;
	var sTime = H+':'+ i+':'+s;
	var eTime = E+':'+ i+':'+s;
//	var nTime = N+':'+ i+':'+s;
	var nTime = gNtpHis;

	var date = Y+'-'+m+'-'+d;
	$('#channel_chg_plan_source').hide();
	var div = $($('#change_item_html').html());
	$('#chg_box').append(div);
	if(obj !== null)
	{
		div.insertBefore($(obj).parent());
	}
	if($('#chg_box').children().length === 1)
	{
		if (date < $('#chg_date').val())
		{
			$(div.find('.chg_plan_input')[0]).val('00:00:00');
			div.find('.start_time').val('00:00:00');
			$(div.find('.chg_plan_input')[1]).val('01:00:00');
			div.find('.end_time').val('01:00:00');
		}
		else
		{
			var pp = $('<p onclick="add_plan_p(this);" onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:10px;width:100%;border-bottom:1px solid #D2D6D9;"></p>');
			$('#chg_box').append(pp);
			pp.insertBefore(div);
			if(sTime > '23:59:59')
			{
				$(div.find('.chg_plan_input')[0]).val(encode_time(decode_time(nTime)+300));
				div.find('.start_time').val(encode_time(decode_time(nTime)+300));
			}
			else
			{
				$(div.find('.chg_plan_input')[0]).val(sTime);
				div.find('.start_time').val(sTime);
			}
			
			if(eTime > '23:59:59')
			{
				$(div.find('.chg_plan_input')[1]).val('23:59:59');
				div.find('.end_time').val('23:59:59');
			}
			else
			{
				$(div.find('.chg_plan_input')[1]).val(eTime);
				div.find('.end_time').val(eTime);
			}
		}
	}
	else
	{
		$('#channel_chg_plan_source').prependTo('#div_box_0').hide();
		/*配置300秒*/
		if($($(div).prev().find('.chg_plan_input')[1]).val() < nTime)
		{
			var s_nTime = '';
			if(date < $('#chg_date').val())
			{
				s_nTime = $($(div).prev().find('.chg_plan_input')[1]).val();
			}
			else
			{
				s_nTime = encode_time(decode_time(nTime)+300);

				if(s_nTime < $($(div).prev().find('.chg_plan_input')[1]).val())
				{
					s_nTime = $($(div).prev().find('.chg_plan_input')[1]).val();
				}
			}

			$(div.find('.chg_plan_input')[0]).val(s_nTime);
			div.find('.start_time').val(s_nTime);
			var endtime = decode_time(s_nTime);
		}
		else
		{
			if($($(div).prev().find('.chg_plan_input')[1]).val() == undefined)
			{
				var first_sTime = encode_time(decode_time($($(obj).parent().find('.chg_plan_input')[0]).val())-3600);
				if(first_sTime < '00:00:00')
				{
					first_sTime = '00:00:00';
				}
				else
				{
					if($('#chg_box').find('[name=p]').last().attr('name') === 'p')
					{
						$('#chg_box').find('[name=p]').last().remove();
					}
					first_sTime = first_sTime ? first_sTime : gEndTime;
				}
				$(div.find('.chg_plan_input')[0]).val(first_sTime);
				div.find('.start_time').val(first_sTime);
			}
			else
			{
				$(div.find('.chg_plan_input')[0]).val($($(div).prev().find('.chg_plan_input')[1]).val());
				div.find('.start_time').val($($(div).prev().find('.chg_plan_input')[1]).val());
			}
			
			var endtime = decode_time($($(div).prev().find('.chg_plan_input')[1]).val());
		}

		if(p == 1)
		{
			$(div.find('.chg_plan_input')[1]).val($($(obj).parent().find('.chg_plan_input')[0]).val());
			div.find('.end_time').val($($(obj).parent().find('.chg_plan_input')[0]).val());
		}
		else
		{
			/*配置默认时长3600秒*/
			if(endtime < 82800)
			{
				$(div.find('.chg_plan_input')[1]).val(encode_time(endtime + 3600));
				div.find('.end_time').val(encode_time(endtime + 3600));
			}
			else
			{
				$(div.find('.chg_plan_input')[1]).val('23:59:59');
				div.find('.end_time').val('23:59:59');
			}
		}
	}
	hg_resize_nodeFrame();
}
function hg_last_time_checked(obj)
{
	if($($(obj).parent().prev().find('.input_div').last().find('.chg_plan_input')[1]).val() == '23:59:59')
	{
		$('#div_box_0').hide();
	}
}

/*时移时间*/
function hg_channel_chg_plan_start_time(obj,type,start)
{
	if($.trim($('#info_live').html()) === '')
	{
		jAlert('请从时移里面选择节目');
		$('#start_times').val($('#channel_chg_plan_source').prev().find('.program_start_time').val());
		$('#end_times').val($('#channel_chg_plan_source').prev().find('.program_end_time').val());
	}
	else
	{
		$('#channel_chg_plan_source').prev().find('.chg_type_fir').html('时移');
		var s_time = $("#start_times").val();
		var e_time = $("#end_times").val();
		var stime = s_time.substr(5);
		var etime = e_time.substr(11);
	//	var channel_id = $('input[id^=channel_id_plan_]').val();
		var channel_name = $('input[id^=channel_name_plan_]').val();
		if(channel_name == 'null')
		{
			channel_name = $('#channel_chg_plan_source').prev().find('.channel2_name').val();
		}
		$('#channel_chg_plan_source').prev().find('.chg_type_sec').html('<span class="title">'+channel_name+'</span><span>'+stime+' - '+etime+'</span>');
		/*外面时长计算*/
		var start_time = decode_time($($('#channel_chg_plan_source').prev().find('.chg_plan_input')[0]).val());
		var end_time = decode_time($($('#channel_chg_plan_source').prev().find('.chg_plan_input')[1]).val());
		var out_toff = end_time - start_time;
		/*里面时长计算*/
		var in_toff = decode_time(e_time.substr(11)) - decode_time(s_time.substr(11));
		if(out_toff != in_toff)
		{
			var new_end_time = encode_time(start_time + in_toff);
			$($('#channel_chg_plan_source').prev().find('.chg_plan_input')[1]).val(new_end_time);
		}
		if(start ==1)
		{
			$('#channel_chg_plan_source').prev().find('.program_start_time').val($(obj).val());
		}
		else
		{
			$('#channel_chg_plan_source').prev().find('.program_end_time').val($(obj).val());
		}
	}
	$('#channel_chg_plan_source').prev().find('.hidden_temp').val(1);
	sub_disabled();
	$('#channel_chg_plan_source').prev().css('background','#FEF2F2');
}

/*外面结束时间 start==1表示开始时间*/
function hg_out_end_time(obj,start)
{
	if($('#channel_name_plan').val())
	{
		var channel_name = $('#channel_name_plan').val();
	}
	else
	{
		var channel_name = $(obj).parent().find('.channel2_name').val();
	}
	var out_toff;
	if(start==1)
	{
		var out_toff =  decode_time($($(obj).parent().find('.chg_plan_input')[1]).val()) - decode_time($(obj).val());
	}
	else
	{
		var out_toff = decode_time($(obj).val()) - decode_time($($(obj).parent().find('.chg_plan_input')[0]).val());
	}
	
	var type = $(obj).parent().find('.type').val();
	if(type == 3 && $.trim($('#channel_chg_plan_source').html()) != '')
	{
		var in_toff = decode_time($("#end_times").val().substr(11))- decode_time($("#start_times").val().substr(11));
	
		if(out_toff != in_toff)
		{
			var s_time = $("#start_times").val();
			var e_time = $("#end_times").val();
			var stime = s_time.substr(5);
			var etime = e_time.substr(11);
	//		var channel_id = $('#channel_id_plan').val();
			if(start == 1)
			{
				var in_s_time = encode_time(decode_time($("#end_times").val().substr(11)) - out_toff);
				var in_stime = $("#start_times").val().substr(5,5)+' '+in_s_time;
				var new_in_s_time = $("#start_times").val().substr(0,10) + ' '+ in_s_time;
			
				$("#start_times").val(new_in_s_time);
				$(obj).parent().find('input[name^="program_start_time"]').val(new_in_s_time);
				$(obj).parent().find('.chg_type_sec').html('<span class="title">'+channel_name+'</span><span>'+in_stime+' - '+etime+'</span>');
			}
			else
			{
				var in_e_time = encode_time(decode_time($("#start_times").val().substr(11)) + out_toff);
				var new_in_e_time = $("#start_times").val().substr(0,10) + ' '+ in_e_time;
				$("#end_times").val(new_in_e_time);
				$(obj).parent().find('.chg_type_sec').html('<span class="title">'+channel_name+'</span><span>'+stime+' - '+in_e_time+'</span>');
			}
		}
	}
	if($(obj).val() == '23:59:59')
	{
		$('#div_box_0').hide();
	}
	else
	{
		$('#div_box_0').show();
	}

	sub_disabled();
}

function isTime(str)
{
	//短时间，形如 (13:04:06)
	if(str)
	{
		var a = str.match(/^(\d{1,2})(:)?(\d{1,2})\2(\d{1,2})$/);
		if(a == null)
		{
			jAlert('输入的参数不是时间格式');
			return false;
		}
		if(a[1]>23 || a[3]>59 || a[4]>59)
		{
			jAlert("时间格式不对");
			return false
		}
	}
} 
/*时间格式验证*/
function hg_time_checked(obj,start)
{
	if(isTime($(obj).val()) == false)
	{
		if(start == 1)
		{
			if(!$(obj).parent().find('.start_time').val())
			{
				$(obj).val(encode_time(decode_time($($(obj).parent().find('.chg_plan_input')[1]).val()) - 3600));
			}
			else
			{
				$(obj).val($(obj).parent().find('.start_time').val());
			}
		}
		else
		{
			if(!$(obj).parent().find('.end_time').val())
			{
				if(encode_time(decode_time($($(obj).parent().find('.chg_plan_input')[0]).val()) + 3600) > '23:59:59')
				{
					$(obj).val('23:59:59');
				}
				else
				{
					$(obj).val(encode_time(decode_time($($(obj).parent().find('.chg_plan_input')[0]).val()) + 3600));
				}
			}
			else
			{
				$(obj).val($(obj).parent().find('.end_time').val());
			}
		
		}
	}
}
/*编辑验证时间  start==1表示验证开始时间*/
function update_check_time(obj,start)
{
	var s_time = new Date();
	var Y = s_time.getFullYear();
	var m = s_time.getMonth()+1;
	var d = s_time.getDate();
	
	var N = s_time.getHours();
	var i = s_time.getMinutes();
	var s = s_time.getSeconds();
	var N = N<10 ? '0'+N : N;
	var i = i<10 ? '0'+i : i;
	var s = s<10 ? '0'+s : s;
	var m = m<10 ? '0'+m : m;
	var d = d<10 ? '0'+d : d;
	var nowTime = N+':'+ i+':'+s;
	
	var date = Y+'-'+m+'-'+d;

	if(start == 1)
	{
		//时移
		if($(obj).parent().find('.toff').val() && $(obj).parent().find('.type').val()==3)
		{
			/* 验证时移时结束时间不能和下一个串联单的结束时间有冲突*/
			if($(obj).parent().next().attr('id') === 'channel_chg_plan_source')
			{
				if($(obj).parent().next().next().attr('name') === 'p')
				{
					var next_start_time = $($(obj).parent().next().next().next().find('.chg_plan_input')[0]).val();
				}
				else
				{
					var next_start_time = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
				}
			}
			else
			{
				if($(obj).parent().next().attr('name') === 'p')
				{
					var next_start_time = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
				}
				else
				{
					var next_start_time = $($(obj).parent().next().find('.chg_plan_input')[0]).val();
				}
				
			}

			var start_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.start_time').val());
			var self_end_time = encode_time(decode_time($(obj).parent().find('.end_time').val()) + start_toff);
			/*验证时移时开始时间不能和上一个串联单的结束时间有冲突*/
			if($(obj).parent().prev().attr('name') === 'p')
			{
				var prev_end_time_3 = $($(obj).parent().prev().prev().find('.chg_plan_input')[1]).val();
			}
			else
			{
				var prev_end_time_3 = $($(obj).parent().prev().find('.chg_plan_input')[1]).val();
			}
			var start_start_toff = decode_time($(obj).parent().find('.start_time').val()) - decode_time($(obj).val());
			var self_start_time = encode_time(decode_time($(obj).parent().find('.start_time').val()) - start_start_toff);
			
			if($(obj).parent().prev().prev().attr('class') === undefined)
			{
				if(self_end_time <= next_start_time)
				{
					var new_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.start_time').val());
					if(new_toff > 0)
					{
						$($(obj).parent().find('.chg_plan_input')[1]).val(encode_time(decode_time($(obj).parent().find('.end_time').val()) + new_toff));
					}
					if(new_toff < 0)
					{
						$($(obj).parent().find('.chg_plan_input')[1]).val(encode_time(decode_time($(obj).parent().find('.end_time').val()) + new_toff));
					}
				}
				else
				{
					jAlert('时间设置和下一个串联单有冲突，请修改');
					$(obj).val($(obj).parent().find('.start_time').val());
					$($(obj).parent().find('.chg_plan_input')[1]).val($(obj).parent().find('.end_time').val());
				}
			}
			else
			{

				if (next_start_time === undefined)
				{
					if(self_start_time >= prev_end_time_3)
					{
						var new_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.start_time').val());
						if(new_toff > 0)
						{
							$($(obj).parent().find('.chg_plan_input')[1]).val(encode_time(decode_time($(obj).parent().find('.end_time').val()) + new_toff));
						}
						if(new_toff < 0)
						{
							$($(obj).parent().find('.chg_plan_input')[1]).val(encode_time(decode_time($(obj).parent().find('.end_time').val()) + new_toff));
						}
						
						$(obj).parent().find('.end_time').val(self_end_time);
					}
					else
					{
						jAlert('时间设置和下一个串联单有冲突，请修改');
						$(obj).val($(obj).parent().find('.start_time').val());
						$($(obj).parent().find('.chg_plan_input')[1]).val($(obj).parent().find('.end_time').val());
					}
				}
				else
				{
					if(self_end_time <= next_start_time && self_start_time >= prev_end_time_3)
					{
						var new_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.start_time').val());
						if(new_toff > 0)
						{
							$($(obj).parent().find('.chg_plan_input')[1]).val(encode_time(decode_time($(obj).parent().find('.end_time').val()) + new_toff));
						}
						if(new_toff < 0)
						{
							$($(obj).parent().find('.chg_plan_input')[1]).val(encode_time(decode_time($(obj).parent().find('.end_time').val()) + new_toff));
						}
						$(obj).parent().find('.end_time').val(self_end_time);
					}
					else
					{
						jAlert('时间设置和下一个串联单有冲突，请修改');
						$(obj).val($(obj).parent().find('.start_time').val());
						$($(obj).parent().find('.chg_plan_input')[1]).val($(obj).parent().find('.end_time').val());
					}
				}
				
			}
		}
		//直播、文件
		if($(obj).parent().prev().attr('name') === 'p')
		{
			if ($(obj).parent().prev().prev().attr('id') == 'channel_chg_plan_source')
			{
				var prev_end_time = $($(obj).parent().prev().prev().prev().find('.chg_plan_input')[1]).val();
			}
			else
			{
				var prev_end_time = $($(obj).parent().prev().prev().find('.chg_plan_input')[1]).val();
			}
			
			if($(obj).val() == prev_end_time)
			{
				$(obj).parent().prev().remove();
			}
			
			if ($(obj).val() >= prev_end_time)
			{
				//修改 .start_time
				
				$(obj).parent().find('.start_time').val($(obj).val());
			}
		}
		else
		{
		//	alert($(obj).parent().prev().attr('id'));
			if ($(obj).parent().prev().attr('id') == 'channel_chg_plan_source')
			{
				var prev_end_time = $($(obj).parent().prev().prev().find('.chg_plan_input')[1]).val();
			}
			else
			{
				var prev_end_time = $($(obj).parent().prev().find('.chg_plan_input')[1]).val();
			}
			/*添加空白p标签*/
			if($(obj).val() > prev_end_time)
			{
				var p = $('<p onclick="add_plan_p(this);" onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:10px;width:100%;border-bottom:1px solid #D2D6D9;"></p>');
				
				if (date == $.trim($('#chg_date').val()) && prev_end_time < nowTime)
				{
					var p = $('<p onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:10px;width:100%;border-bottom:1px solid #D2D6D9;"></p>');
				}
				
				$('#chg_box').append(p);
				if(obj !== null)
				{
					p.insertBefore($(obj).parent());
				}
				
				
			}
			//修改 .start_time
			if ($(obj).val() < $($(obj).parent().find('.chg_plan_input')[1]).val() && $(obj).val() > prev_end_time)
			{
				$(obj).parent().find('.start_time').val($(obj).val());
			}
		}
		
		if($(obj).val() < prev_end_time)
		{
			jAlert('开始时间不能小于上一个串联单的结束时间，请修改');
			if($(obj).parent().find('.start_time').val())
			{
				$(obj).val($(obj).parent().find('.start_time').val());
			}
			else
			{
				$(obj).val($($(obj).parent().prev().find('.chg_plan_input')[1]).val());
			}
			
			return;
			
			if ($(obj).parent().prev().attr('name') === 'p')
			{
				$(obj).parent().prev().remove();
			}
			return;
		}
		
		if($(obj).val() >= $($(obj).parent().find('.chg_plan_input')[1]).val())
		{
			if ($($(obj).parent().next().find('.chg_plan_input')[0]).val() == undefined)
			{
				//$($(obj).parent().find('.chg_plan_input')[1]).val();
				var newToff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.start_time').val());
				var newEndTime = encode_time(decode_time($($(obj).parent().find('.chg_plan_input')[1]).val()) + newToff);
				if (newEndTime > '23:59:59')
				{
					newEndTime = '23:59:59';
				}
				$($(obj).parent().find('.chg_plan_input')[1]).val(newEndTime);
				return;
			//	alert($(obj).val() +'----' +$(obj).parent().find('.start_time').val() +'' + newToff + $($(obj).parent().find('.chg_plan_input')[1]).val() +'===='+newEndTime);
			}
			jAlert('开始时间不能大于结束时间，请修改');

			if($(obj).parent().find('.start_time').val())
			{
				$(obj).val($(obj).parent().find('.start_time').val());
			}
			else
			{
				$(obj).val($($(obj).parent().prev().find('.chg_plan_input')[1]).val());
			}
			
			if ($(obj).parent().prev().attr('name') === 'p')
			{
				$(obj).parent().prev().remove();
			}
			return;
		}
	}
	else
	{
		//时移
		if($(obj).parent().find('.toff').val() && $(obj).parent().find('.type').val()==3)
		{
			/* 验证时移时结束时间不能和下一个串联单的结束时间有冲突*/
			if($(obj).parent().next().attr('id') === 'channel_chg_plan_source')
			{
				if($(obj).parent().next().next().attr('name') === 'p')
				{
					var next_start_time = $($(obj).parent().next().next().next().find('.chg_plan_input')[0]).val();
				}
				else
				{
					var next_start_time = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
				}
			}
			else
			{
				if($(obj).parent().next().attr('name') === 'p')
				{
					var next_start_time = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
				}
				else
				{
					var next_start_time = $($(obj).parent().next().find('.chg_plan_input')[0]).val();
				}
			}
			var end_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.end_time').val());
			var self_end_time = encode_time(decode_time($(obj).parent().find('.end_time').val()) + end_toff);
			/*验证时移时开始时间不能和上一个串联单的结束时间有冲突*/
			if($(obj).parent().prev().attr('name') === 'p')
			{
				var prev_end_time_3 = $($(obj).parent().prev().prev().find('.chg_plan_input')[1]).val();
			}
			else
			{
				var prev_end_time_3 = $($(obj).parent().prev().find('.chg_plan_input')[1]).val();
			}
			var end_end_toff = decode_time($(obj).parent().find('.end_time').val()) - decode_time($(obj).val());
			var self_start_time = encode_time(decode_time($(obj).parent().find('.start_time').val()) - end_end_toff);
			if($(obj).parent().prev().prev().attr('class') === undefined)
			{
				if(self_end_time <= next_start_time)
				{
					var new_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.end_time').val());
					if(new_toff > 0)
					{
						$($(obj).parent().find('.chg_plan_input')[0]).val(encode_time(decode_time($(obj).parent().find('.start_time').val()) + new_toff));
					}
					if(new_toff < 0)
					{
						$($(obj).parent().find('.chg_plan_input')[0]).val(encode_time(decode_time($(obj).parent().find('.start_time').val()) + new_toff));
					}
				}
				else
				{
					jAlert('时间设置和下一个串联单有冲突，请修改');
					$(obj).val($(obj).parent().find('.end_time').val());
					$($(obj).parent().find('.chg_plan_input')[0]).val($(obj).parent().find('.start_time').val());
				}
			}
			else
			{
		
				if (next_start_time === undefined)
				{
					if(self_start_time >= prev_end_time_3)
					{
						var new_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.end_time').val());
						if(new_toff > 0)
						{
							$($(obj).parent().find('.chg_plan_input')[0]).val(encode_time(decode_time($(obj).parent().find('.start_time').val()) + new_toff));
						}
						if(new_toff < 0)
						{
							$($(obj).parent().find('.chg_plan_input')[0]).val(encode_time(decode_time($(obj).parent().find('.start_time').val()) + new_toff));
						}
						$(obj).parent().find('.start_time').val(self_start_time);
						$(obj).parent().find('.end_time').val(self_end_time);
					}
					else
					{
						jAlert('时间设置和下一个串联单有冲突，请修改');
						$(obj).val($(obj).parent().find('.end_time').val());
						$($(obj).parent().find('.chg_plan_input')[0]).val($(obj).parent().find('.start_time').val());
					}
				}
				else
				{
					if(self_end_time <= next_start_time && self_start_time >= prev_end_time_3)
					{
						var new_toff = decode_time($(obj).val()) - decode_time($(obj).parent().find('.end_time').val());
						if(new_toff > 0)
						{
							$($(obj).parent().find('.chg_plan_input')[0]).val(encode_time(decode_time($(obj).parent().find('.start_time').val()) + new_toff));
						}
						if(new_toff < 0)
						{
							$($(obj).parent().find('.chg_plan_input')[0]).val(encode_time(decode_time($(obj).parent().find('.start_time').val()) + new_toff));
						}
						$(obj).parent().find('.start_time').val(self_start_time);
						$(obj).parent().find('.end_time').val(self_end_time);
					}
					else
					{
						jAlert('时间设置和下一个串联单有冲突，请修改');
						$(obj).val($(obj).parent().find('.end_time').val());
						$($(obj).parent().find('.chg_plan_input')[0]).val($(obj).parent().find('.start_time').val());
					}
				}
				
			}
		}
		
		//直播、文件
		if($(obj).parent().next().attr('id') === 'channel_chg_plan_source')
		{
			//获取下一个串联单的开始时间
			if ($(obj).parent().next().next().attr('name') === 'p')
			{
				var next_start_time_new = $($(obj).parent().next().next().next().find('.chg_plan_input')[0]).val();
			}
			else
			{
				var next_start_time_new = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
			}
			
			if ($(obj).val() > $($(obj).parent().find('.chg_plan_input')[0]).val() && $(obj).val() <= next_start_time_new)
			{
				//修改 .end_time
				$(obj).parent().find('.end_time').val($(obj).val());
			}
			
			if($($(obj).parent().next().next().find('.chg_plan_input')[0]).val() != undefined)
			{
				if($(obj).val() > $($(obj).parent().next().next().find('.chg_plan_input')[0]).val())
				{
					jAlert('结束时间不能大于下一个串联单的开始时间，请修改');
					$(obj).val($(obj).parent().find('.end_time').val());
				}
				/*添加空白p标签*/
				if($(obj).val() < $($(obj).parent().next().next().find('.chg_plan_input')[0]).val())
				{
					var p = $('<p onclick="add_plan_p(this);" onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:10px;width:100%;border-bottom:1px solid #D2D6D9;"></p>');
					$('#chg_box').append(p);
					if(obj !== null)
					{
					//	p.insertAfter($(obj).parent());
						p.insertAfter($('#channel_chg_plan_source'));
					}
				}
			}
			
			if($(obj).parent().next().next().attr('name') === 'p')
			{
				if($(obj).val() > $($(obj).parent().next().next().next().find('.chg_plan_input')[0]).val())
				{
					jAlert('结束时间不能大于下一个串联单的开始时间，请修改');
					$(obj).val($(obj).parent().find('.end_time').val());
				}
				if($(obj).val() == $($(obj).parent().next().next().next().find('.chg_plan_input')[0]).val())
				{
					$(obj).parent().next().next().remove();
				}
			}
		}
		else
		{
			//获取下一个串联单的开始时间
			if ($(obj).parent().next().attr('name') === 'p')
			{
				var next_start_time_new = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
			}
			else
			{
				var next_start_time_new = $($(obj).parent().next().find('.chg_plan_input')[0]).val();
			}
			
			if ($(obj).val() > $($(obj).parent().find('.chg_plan_input')[0]).val() && $(obj).val() <= next_start_time_new)
			{
				//修改 .end_time
				$(obj).parent().find('.end_time').val($(obj).val());
			}
			
			if($($(obj).parent().next().find('.chg_plan_input')[0]).val() != undefined)
			{
				if($(obj).val() > $($(obj).parent().next().find('.chg_plan_input')[0]).val())
				{
					jAlert('结束时间不能大于下一个串联单的开始时间，请修改');
					$(obj).val($(obj).parent().find('.end_time').val());
				}
				
				/*添加空白p标签*/
				if($(obj).val() < $($(obj).parent().next().find('.chg_plan_input')[0]).val())
				{
					var p = $('<p onclick="add_plan_p(this);" onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:10px;width:100%;border-bottom:1px solid #D2D6D9;"></p>');
					$('#chg_box').append(p);
					if(obj !== null)
					{
						p.insertAfter($(obj).parent());
					}
				}
			}
			
			if($(obj).parent().next().attr('name') === 'p')
			{
				if($(obj).val() > $($(obj).parent().next().next().find('.chg_plan_input')[0]).val())
				{
					jAlert('结束时间不能大于下一个串联单的开始时间，请修改');
					$(obj).val($(obj).parent().find('.end_time').val());
				}
				if($(obj).val() == $($(obj).parent().next().next().find('.chg_plan_input')[0]).val())
				{
					$(obj).parent().next().remove();
				}
			}
		}

		if($(obj).val() <= $($(obj).parent().find('.chg_plan_input')[0]).val())
		{
			jAlert('结束时间不能小于开始时间，请修改');
			$(obj).val($(obj).parent().find('.end_time').val());
			
			if ($(obj).parent().next().attr('name') === 'p')
			{
				var next_start_time_new_2 = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
			}
			else if ($(obj).parent().next().attr('id') == 'channel_chg_plan_source')
			{
				if ($(obj).parent().next().next().attr('name') === 'p')
				{
					var next_start_time_new_2 = $($(obj).parent().next().next().next().find('.chg_plan_input')[0]).val();
				}
				else
				{
					var next_start_time_new_2 = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
				}
			}
			else
			{
				var next_start_time_new_2 = $($(obj).parent().next().next().find('.chg_plan_input')[0]).val();
			}
			
			if($(obj).val() == next_start_time_new_2)
			{
				if ($(obj).parent().next().attr('id') == 'channel_chg_plan_source')
				{
					$(obj).parent().next().next().remove();
				}
				else
				{
					$(obj).parent().next().remove();
				}
			}
		}
	}
	sub_disabled();
	$(obj).parent().css('background','#FEF2F2');
	$(obj).parent().find('input[name^="hidden_temp"]').val(1);
}
/*添加串联单 */
function add_plan_check(obj)
{
	if($(obj).parent().prev().attr('name') === 'p')
	{
		$(obj).parent().prev().remove();
		hg_add_plan(obj,1);
	}
	else
	{
		jAlert('没有时间可以在添加串联单！');
	}
}
/*在空白处添加串联单*/
function add_plan_p(obj)
{
	var s_time = new Date();
	var Y = s_time.getFullYear();
	var m = s_time.getMonth()+1;
	var d = s_time.getDate();
	
	var N = s_time.getHours();
	var i = s_time.getMinutes();
	var s = s_time.getSeconds();
	var N = N<10 ? '0'+N : N;
	var i = i<10 ? '0'+i : i;
	var s = s<10 ? '0'+s : s;
	var m = m<10 ? '0'+m : m;
	var d = d<10 ? '0'+d : d;
	var nowTime = N+':'+ i+':'+s;
	
	var date = Y+'-'+m+'-'+d;
	if (date == $('#chg_date').val() && $($(obj).prev().find('.chg_plan_input')[1]).val() < nowTime)
	{
		jAlert('此时间段不能添加串联单，请修改时间');
		return;
	}
	if($(obj).attr('name') === 'p')
	{
		var div = $($('#change_item_html').html());
		$('#chg_box').append(div);
		div.insertBefore($(obj));
		if($(obj).prev().prev().attr('id') === 'channel_chg_plan_source')
		{
			var start = $($('#channel_chg_plan_source').prev().find('.chg_plan_input')[1]).val();
		}
		else
		{
			if($($(obj).prev().prev().find('.chg_plan_input')[1]).val() !=undefined)
			{
				var start = $($(obj).prev().prev().find('.chg_plan_input')[1]).val();
			}
			else
			{
				var start = '00:00:00';
			}
		}
		var end = $($(obj).next().find('.chg_plan_input')[0]).val();
		if ($(obj).next().attr('id') === 'channel_chg_plan_source')
		{
			end = $($('#channel_chg_plan_source').next().find('.chg_plan_input')[0]).val();
		}
	//	var end = $($(obj).next().find('.chg_plan_input')[0]).val();
	//	jAlert($(obj).next().next().attr('id'));
		$($(obj).prev().find('.chg_plan_input')[0]).val(start);
		$($(obj).prev().find('.chg_plan_input')[1]).val(end);
		$(obj).prev().find('.start_time').val(start);
		$(obj).prev().find('.end_time').val(end);
		$(obj).remove();
		sub_disabled();
	}
}
/*空白p标签的背景色*/
function plan_bg_color(obj,type)
{
	if(type)
	{
		$(obj).css({background:'',cursor:'default'});
		$(obj).removeAttr('title');
	}
	else
	{
		$(obj).css({background:'#DDEEFE',cursor:'pointer'});
		$(obj).attr('title','点击添加');
	}
}
/*删除串联单*/
var gDelPlanObj = '';
var gDelPlanId = '';
function hg_del_plan(obj)
{
	gDelPlanObj = obj;
	$('#channel_chg_plan_source').prependTo('#div_box_0').hide();
	id = $(obj).parent().find('.hidden_id').val();
	gDelPlanId = id;
	
	if(id)
	{
		var name=confirm('确定删除该串联单吗？');
		if(name == true)
		{
			/*添加空白p标签*/
			if($(obj).parent().prev().attr('name') != 'p' && $(obj).parent().next().attr('class') != undefined)
			{
				var p = $('<p onclick="add_plan_p(this);" onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:10px;width:100%;border-bottom:1px solid #D2D6D9;"></p>');
				$('#chg_box').append(p);
				if(obj !== null)
				{
					p.insertAfter($(obj).parent());
				}
			}
			
			if($(obj).parent().prev().attr('name') === 'p' && $(obj).parent().next().attr('class') == undefined)
			{
				$(obj).parent().prev().remove();
			}
			
			if($('#chg_box .input_div').length == 1)
			{
				
				$('#chg_box').find('p[name=p]').remove();
			}
			
			if (id.length >= 12)
			{
				$(obj).parent().remove();
				return;
			}
			var url = "./run.php?mid="+gMid+"&a=delete&id="+id;
			hg_ajax_post(url,'','','del_plan_back');
		}
	}
	else
	{
		/*添加空白p标签*/
		if($(obj).parent().prev().attr('name') != 'p' && $(obj).parent().next().attr('class') != undefined)
		{
			var p = $('<p onclick="add_plan_p(this);" onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:10px;width:100%;border-bottom:1px solid #D2D6D9;"></p>');
			$('#chg_box').append(p);
			if(obj !== null)
			{
				p.insertAfter($(obj).parent());
			}
		}
		if($(obj).parent().prev().attr('name') === 'p' && $(obj).parent().next().attr('class') == undefined)
		{
			$(obj).parent().prev().remove();
		}
		if($('#chg_box .input_div').length == 1)
		{
			
			$('#chg_box').find('p[name=p]').remove();
		}
		$(obj).parent().remove();
	}
	if($($(obj).parent().find('.chg_plan_input')[1]).val() == '23:59:59')
	{
		$('#div_box_0').show();
	}
}

function del_plan_back(obj)
{
	if (obj == gDelPlanId)
	{
		$(gDelPlanObj).parent().remove();
	}
}

function hg_input_div_bgcolor(obj)
{
	$('.input_div').removeClass('cur');
	$(obj).addClass('cur');
}
/*调用channel_chg_plan_source模板*/
var gType = '';
var gChannel2_id = '';
var gChannel_name = '';
function hg_plan_form(obj)
{
	var type = $(obj).parent().find('.type').val();
	var channel2_id = $(obj).parent().find('.channel2_id').val();
	var program_start_time = $(obj).parent().find('.program_start_time').val();
	var program_end_time = $(obj).parent().find('.program_end_time').val();
	var channel2_name = $(obj).parent().find('.channel2_name').val();
	var audio_only = $('#audio_only').val();

	gType = type;
	gChannel2_id = channel2_id;
	gChannel_name = channel2_name;
	if(program_start_time)
	{
		$("#start_times").val(program_start_time);
		if(program_end_time)
		{
			$("#end_times").val(program_end_time);
		}
	}
	if($.trim($('#channel_chg_plan_source').html()) === '')
	{
		var url='./run.php?mid='+gMid+'&a=type_source&channel_id='+$('#channel_id').val()+'&type='+type+'&channel2_id='+channel2_id+'&channel2_name='+channel2_name+'&program_start_time='+program_start_time+'&program_end_time='+program_end_time + '&audio_only=' + audio_only + '&server_id=' + gServerId;
		hg_ajax_post(url);
	}
	else
	{
		gType = gType ? gType : 4;
		$('#channel_chg_plan_source').find('#type_'+gType).click(change(gType));
		if(gType==1 || gType == 4)
		{
			$('#type_content_'+gType).find('li a').removeClass('type_source');
			$('#type_content_'+gType).find('#live_'+gChannel2_id+' a').addClass('type_source');
		}
		else if(gType==2)
		{
			$('#type_content_'+gType).find('li').removeClass('cur');
			$('#type_content_'+gType).find('#file_'+gChannel2_id).addClass('cur');
		}
		else if(gType == 3)
		{
			$('#type_content_'+gType).find('#channel_list #item_shows_ li').first().find('a').attr('attrid',gChannel2_id);
			$('#channel2_id').val(gChannel2_id);
			$('#display_item_shows_').html(gChannel_name);
			
			if($('#type_content_'+gType).find('#channel_list #item_shows_ li a').attr('attrid') == gChannel2_id)
			{
				$('#type_content_'+gType).find('#channel_list #item_shows_ li a').click(hg_select_channel_plan(obj,1));
			}
		}
	}
	if($('#channel_chg_plan_source').css('display') === 'none')
	{
		$('#channel_chg_plan_source').slideDown(function(){
			hg_resize_nodeFrame();
		});
	}
	else if($(obj).parent().next().length > 0 && $('#channel_chg_plan_source')[0].id === $(obj).parent().next()[0].id)
	{
		$('#channel_chg_plan_source').slideUp(function(){
			hg_resize_nodeFrame();
		});
	}

	$('#channel_chg_plan_source').insertAfter($(obj).parent());
}
/*回调函数*/
function hg_type_source(html)
{
	$('#channel_chg_plan_source').html(html);
//	hg_backupPage();
	gType = gType ? gType : 4;
	$('#channel_chg_plan_source').find('#type_'+gType).click(change(gType));
	if(gType==1 || gType==4)
	{
		$('#type_content_'+gType).find('li a').removeClass('type_source');
		$('#type_content_'+gType).find('#live_'+gChannel2_id+' a').addClass('type_source');
	}
	else if(gType==2)
	{
		$('#type_content_'+gType).find('li').removeClass('cur');
		$('#type_content_'+gType).find('#file_'+gChannel2_id).addClass('cur');
	}
	else if (gType == 3)
	{
		if($('#type_content_'+gType).find('#channel_list #item_shows_ li a').attr('attrid') == gChannel2_id)
		{
			$('#type_content_'+gType).find('#channel_list #item_shows_ li a').click(hg_select_channel_plan(obj,1));
		}
	}
	
}

/*div切换*/
function change(id)
{
	for(var i = 1; i <= 4; i++)
	{
		var obj = document.getElementById("type_"+i);
		var object = document.getElementById("type_content_"+i);
		if(id == i)
		{
			obj.className = "live_type_"+i;
			object.style.display = "block";
			$(obj).addClass('cur');
		}
		else
		{
			$(obj).removeClass('cur');
			obj.className = "type_"+i;
			object.style.display = "none";
		}
	}
}
function hg_select_channel_plan(e, flag)
{
	var obj = $(e);
	if (flag == 1)
	{
		obj = $('#type_content_3').find('#channel_list #item_shows_ li a');
	}
	
	if (obj.find('span').attr('open_ts') == 0)
	{
		var text = '<div style="color: red;margin-left: 125px;">未启手机流，无法选择时移</div>';
		$('#info_live').html(text).show();
		return;
	}
	
	if($('#channel2_id').val() != -1)
	{
		var channel_id = $('#channel2_id').val();
		var channel_name = $(e).find('.title').html();
		var dates = $('#chg_date').val();
		var stime = $('#channel_chg_plan_source').prev().find('input[name^="start_times[]"]').val();

		var url = "./run.php?mid="+gMid+"&a=chg_record_list&channel_id=" + channel_id+"&channel_name="+channel_name + "&dates=" + dates + '&stime=' + stime;
		hg_ajax_post(url);
	}
}
function hg_copy_record_plan(id)
{
	if($('#channel_chg_plan_source').next().attr('name') == 'p')
	{
		var next_start = $($('#channel_chg_plan_source').next().next().find('.chg_plan_input')[0]).val();
	}
	else
	{
		var next_start = $($('#channel_chg_plan_source').next().find('.chg_plan_input')[0]).val();
	}
	/*外面时长计算*/
	var start_time = decode_time($($('#channel_chg_plan_source').prev().find('.chg_plan_input')[0]).val());
	var end_time = decode_time($($('#channel_chg_plan_source').prev().find('.chg_plan_input')[1]).val());
	var out_toff = end_time - start_time;
	$("#start_times").val($("#starts_"+id).val());
	
	
	/*里面时长计算*/
	var s_time = $("#starts_"+id).val().substr(11);
	var e_time = $("#ends_"+id).val().substr(11);
	var stime = decode_time(s_time);
	var etime = decode_time(e_time);
	var in_toff = etime - stime;
	var channel_name = $('#channel_name_plan_'+id).val();
	
	
	if(channel_name == 'null')
	{
		channel_name = $('#display_item_shows_').html();
	}
	$('#channel_chg_plan_source').prev().find('.channel2_name').val($('#display_item_shows_').html());
	var end_times = start_time + in_toff;
	var new_end_time = encode_time(end_times);

	if(decode_time(new_end_time) > decode_time(next_start))
	{
		var new_offset_toff = decode_time(next_start) - decode_time($($('#channel_chg_plan_source').prev().find('.chg_plan_input')[0]).val());
		$("#end_times").val($("#starts_"+id).val().substr(0,11) + encode_time(decode_time(s_time) + new_offset_toff));
		$($('#channel_chg_plan_source').prev().find('.chg_plan_input')[1]).val(next_start);
		$('#channel_chg_plan_source').prev().find('.chg_type_sec').html('<span class="title">'+channel_name+'</span><span>'+$("#starts_"+id).val().substr(5)+' - '+encode_time(decode_time(s_time) + new_offset_toff)+'</span>');
	}
	else
	{
		$("#end_times").val($("#ends_"+id).val());
		$($('#channel_chg_plan_source').prev().find('.chg_plan_input')[1]).val(new_end_time);
		$('#channel_chg_plan_source').prev().find('.chg_type_sec').html('<span class="title">'+channel_name+'</span><span>'+$("#starts_"+id).val().substr(5)+' - '+$("#ends_"+id).val().substr(11)+'</span>');
	}
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
		if(H<10)
		{
			H = '0'+H;
		}
		if(m<10)
		{
			m = '0'+m;
		}
		if(s<10)
		{
			s = '0'+s;
		}
		var times = H + ':' + m + ':' + s;
		return times;
	}
}
function switch_date_plan(mid,channel_id)
{
	var date_form = $("#dates").val();
	var date = date_form.split('-');
	date_1 = date[0]+'/'+date[1]+'/'+date[2];

	var url = "./run.php?mid=" + mid + "&infrm=1&channel_id=" + channel_id + "&dates="+date_form+"&menuid=" + gMenuid;
	location.href = url;
	var now_dates = "{code} echo date('Y-m-d');{/code}";
	var now_dates_s = now_dates.split('-');
	var now_dates_s = now_dates_s[0]+now_dates_s[1]+now_dates_s[2];
	var dates = date[0]+ date[1]+ date[2];
	if(dates<now_dates_s)
	{
		$('#add_div').hide();
	}
	else
	{
		$('#add_div').show();
	}
	
}

/*取来源数据*/
function hg_channel_live(obj,type,ids,backup_id)
{
	var name;
	if(type == 1 || type== 2 || type == 4)
	{
		var id = obj.value;
		
		if(type == 1 || type == 4)
		{
			var textname = (type == 4) ? '信号' : '直播';
			$('#channel_chg_plan_source').prev().find('.chg_type_fir').html(textname);
			var name = $(obj).find('a').html();
			$('#channel_chg_plan_source').prev().find('.chg_type_sec').html(name);
		}
		if(type == 2)
		{
			$('#channel_chg_plan_source').prev().find('.chg_type_fir').html('文件');
			var name = $('#_title_'+backup_id).val();
			var toff = $('#_toff_'+backup_id).html();
			$('#channel_chg_plan_source').prev().find('.chg_type_sec').html(name + '&nbsp;' + toff);
			
			var start_times = decode_time($('#channel_chg_plan_source').prev().find('input[name^="start_times[]"]').val());
			var toff_s = $(obj).find('input[name^="toff_s[]"]').val();
			if (toff_s)
			{
				var site = toff_s.indexOf("'");
				var seconds = (toff_s.substr(0,site))*1*60 + (toff_s.substr(site+1))*1;
			}
			var backup_toff = seconds*1 + start_times;
			var end_times = encode_time(backup_toff);
			
	/*jAlert(backup_id+'--2--'+toff_s +'----'+ start_times +'---' +backup_toff + '==' + end_times +'====' +$('#channel_chg_plan_source').next().find('input[name^="start_times[]"]').val());
	*/
			$('#channel_chg_plan_source').prev().find('input[name^="end_times[]"]').val(end_times);

			update_check_time($('#channel_chg_plan_source').prev().find('input[name^="end_times[]"]'));
		}
		
		$('#channel_chg_plan_source').prev().find('.channel2_id').val(id);
		$('#channel_chg_plan_source').prev().find('.channel2_name').val(name);
	}
	else if(type == 3)
	{
		$('#channel_chg_plan_source').prev().find('.chg_type_fir').html('时移');
		var s_time = $("#starts_"+ids).val();
		var e_time = $("#ends_"+ids).val();
		var stime = s_time.substr(5);
		var etime = e_time.substr(11);
		var channel_id = $('#channel_id_plan_'+ids).val();
		var channel_name = $('#channel_name_plan_'+ids).val();
		if(channel_name == 'null')
		{
			var channel_name = gChannel_name;
		}
/*			$('#channel_chg_plan_source').prev().find('.chg_type_sec').html('<span class="title">'+channel_name+'</span><span>'+stime+' - '+etime+'</span>');*/
		$('#channel_chg_plan_source').prev().find('.channel2_id').val(channel_id);
//		$('#channel_chg_plan_source').prev().find('.channel2_name').val(channel_name);
		$('#channel_chg_plan_source').prev().find('.program_start_time').val(s_time);
	}
	$('#channel_chg_plan_source').prev().find('.type').val(type);
	$('#channel_chg_plan_source').prev().find('.chg_type_fir').show();
	$('#channel_chg_plan_source').prev().find('.hidden_temp').val(1);
	sub_disabled();
	$('#channel_chg_plan_source').prev().css('background','#FEF2F2');
}
function hg_content_change(obj)
{
	$(obj).find('.hidden_temp').val(1);
	sub_disabled();
}
function sub_disabled()
{
	if($('#sub').hasClass('button_none'))
	{
		$('#sub').removeAttr('disabled');
		$('#sub').removeAttr('class');
		$('#sub').attr('class','button_4');
		gCid = hg_add2Task({name:'串联单修改'});
	}
}

/*复制串联单操作*/
function hg_copy_show_plan(id,show_id)
{
	if($("#chg_box").children('.input_div').length)
	{
		if($("#"+id).css('display') == 'none')
		{
			$("#"+id).show();
		}
		else
		{
			$("#"+id).hide();
		}	
	}
	else
	{
		jAlert($("#"+show_id).val()+"没有串联单");
	}
	hg_resize_nodeFrame();
}
function hg_check_copy_plan(id,show_id)
{
	var url = './run.php?'+'mid=' + gMid + '&a=check_copy&id=' + id + '&show_id=' + show_id + '&channel_id='+$("#channel_id").val()+"&dates=" + $("#"+id).val() + '&menuid=' + gMenuid;
	hg_request_to(url);
}
function hg_copy_day_plan(json)
{
	var obj = new Function("return" + json)();
	var url = './run.php?'+'mid=' + gMid + '&a=copy_day&channel_id='+$("#channel_id").val()+"&dates=" + $("#"+obj.show_id).val() + "&copy_dates=" + $("#" + obj.id).val() + '&menuid=' + gMenuid;
	if($("#"+obj.show_id).val() != $("#" + obj.id).val())
	{
		var tips = '';
		if(obj.ret)
		{
			tips = '此操作将会覆盖'+$("#"+obj.id).val()+'已有的所有串联单';
		}
		else
		{
			tips = '确定进行复制操作？！';
		}
		if(confirm(tips))
		{
			hg_request_to(url);
		}
		else
		{
			$("#date_show_copy_plan").hide();
		}
	}
	else
	{
		jAlert('同一天中不能复制串联单');
	}
}
function hg_call_copy_day_plan(json)
{
	var obj = new Function("return" + json)();
	if(obj.ret)
	{
		var tips = '复制已成功:是否要跳转到' + $("#copy_dates").val() +'的串联单';
		var url = "./run.php?mid=" + gMid + "&infrm=1&channel_id=" + $("#channel_id").val() + "&dates="+$("#copy_dates").val() + "&menuid=" + gMenuid;
		if(confirm(tips))
		{
			location.href = url;
		}
	}
	else
	{
		jAlert('复制失败');
	}
	$("#date_show_copy_plan").hide();
}

/*复制串联单到节目单*/
function hg_chg2program(channel_id)
{
	if (!channel_id)
	{
		jAlert('请选择一个频道');
	}
	var dates = $('#chg_date').val();
	
	if (!dates)
	{
		jAlert('请选择一个日期');
	}
	
	var url = "./run.php?mid=" + gMid + "&a=check_program&channel_id= " + channel_id + "&dates=" + dates;
	
	hg_ajax_post(url,'','','check_program_back');
}
function check_program_back(obj)
{
	var obj = obj[0];
	var channel_id = obj['channel_id'];
	var dates = obj['dates'];
	var result = obj['result'];
	
	var tips = '';
	if(result)
	{
		tips = '此操作将会覆盖 ' + dates + ' 已有的所有节目单';
	}
	else
	{
		tips = '确定进行生成节目单操作？';
	}
	
	if(confirm(tips))
	{
		var url = "./run.php?mid=" + gMid + "&a=chg2program&channel_id= " + channel_id + "&dates=" + dates;
		hg_request_to(url,'','','chg2program_back');
	}
	return;
}

function chg2program_back(obj)
{
	var obj = obj[0];
	var channel_id = obj['channel_id'];
	var dates = obj['dates'];
	var result = obj['result'];
	
	if(result)
	{
		var tips = '生成节目单已成功:是否要跳转到' + dates +'的节目单';
		var url = "./run.php?mid=208&infrm=1&channel_id=" + channel_id + "&dates=" + dates + "&menuid=221";
		if(confirm(tips))
		{
			location.href = url;
		}
	}
	else
	{
		jAlert('生成节目单失败！');
	}
}
