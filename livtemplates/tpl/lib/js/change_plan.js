function hg_plan_none(e,type)
{
	//alert($(e).find("input[id^=channel_id]").val());
	if(!$(e).find("input[id^=channel_id]").val())
	{
		type ? $(e).html('点击添加').fadeIn('slow') : $(e).html('');
	}
}
function hg_plan_repeat(e,type)
{
	if(!type)
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

var gObj = '';
var gStart = '';
var gEnd = '';
var gWeek = '';
function hg_plan_form(e,id,start,end,week)
{
	gObj = e ;
	gStart = start ? start : '';
	gEnd = end ? end : '';
	gWeek = week ? week : '';
	var url = './run.php?mid=' + gMid + '&a=form&channel_id=' + $("#hg_channel").val() + (id ? "&id="+id : '') + '&week_d=' + gWeek;
	hg_request_to(url);
}

function hg_plan_form_show(html)
{
	if(html)
	{
		$("#show_bg").css('display','block');
		var lefts = 0;
		if($(gObj).index() > 3)
		{
			lefts = 376;
		}
		$("#plan_form").html(html).css({'display':'block',"top":$(gObj).offset().top + 21,"left":$(gObj).offset().left-lefts});
		gStart ? $("#plan_form #start_time").val(gStart) : '';
		gEnd ? $("#plan_form #end_time").val(gEnd) : '';
		i = 1;
		$("#plan_form input[id^=week_day_]").each(function(){
			if(i == gWeek)
			{
				$(this).attr('checked','checked');
			}
			i++;
		});
		$('#week_d').val(gWeek);
		var h = $('#plan_form').position().top;
		var top_id=window.parent.document.getElementById('livnodewin');
		if(h>300)
		{
			var s = $('.channel_table').height()+$('#plan_form').height()+60;
			$(top_id).height(s);
		}
	}
}

function hg_submit_plan()
{
	if($("#plan_form").html())
	{
		if($("#start_time").val() || $("#end_time").val() || $("#item").val() || $("div[id^=week_date] input[id^=week_day_]:checked").length > 0)
		{
			if(!$.trim($("#channel2_name_info").html()))
			{
				if(confirm('串联单类型不能为空，请填写！'))
				{
					hg_ajax_submit('planform');
				}
				else
				{
					hg_del_plan_dom();
				}
			}
			else
			{
				hg_ajax_submit('planform');
			//	hg_del_plan_dom();
			}
			
		}
		else
		{
			hg_del_plan_dom();
		}
	}
}

function hg_call_plan_dom()
{
	
}

function hg_del_plan_dom()
{
	$("#plan_form").html('').css('display','none');
	$("#show_bg").css('display','none');
}

function hg_delete_plan(id)
{
	if(confirm("确认删除该记录？！"))
	{
		var url = './run.php?'+'mid=' + gMid + '&a=delete&channel_id=' + $("#hg_channel").val() + "&id=" + id;
		hg_request_to(url);
	}
	else
	{
		hg_del_plan_dom();
	}
}

function hg_call_submit_plan(data)
{
	$(".channel_table tr.default").remove();
	$(".channel_table tr.title").after(data);
	hg_del_plan_dom();
}

function hg_select_source()
{
	if(trim($("#down_list").html()))
	{
		if($("#down_list").css('display') == 'none')
		{
			$("#source_btn").addClass('cur');
			$("#plan_form").addClass('i');
			$("#down_list").show();
		}
		else
		{
			$("#source_btn").removeClass('cur');
			$("#down_list").hide();
			$("#plan_form").removeClass('i');
		}
	}
	else
	{
		var url = './run.php?mid=' + gMid + '&a=get_item';
		hg_request_to(url);
	}
}

function hg_call_source(html)
{
	$("#source_btn").addClass('cur');
	$("#plan_form").addClass('i');
	$("#down_list").html(html);
	$("#down_list").show();
}

function hg_form_check()
{

}

function hg_contains(str,array)
{
	for(var i=0;i<array.length;i++)
	{
		if(array[i] == str)
		{
			return true;
		}
	}
	return false;
}


$(function(){
$("#plan_form").find("input[id^=start_time],input[id^=end_time]").keydown(function(e){
		var chaCode = e.keyCode;
		var array = new Array(8,9,16,17,18,37,38,39,40,48,49,50,51,52,53,54,55,56,57,229);
		return hg_contains(chaCode,array);
	});
});
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
/*秒转化为格式化时间*/
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
/*调用来源数据*/
var gType = '';
var gChannel2_id = '';
var gChannel2_name = '';
function hg_type_source_plan()
{
	var channel_id = $('#channel_id').val();
	var type = $('#type').val();
	var channel2_id = $('#channel2_ids').val();
	var audio_only = $('#audio_only').val();
	var server_id = $('#server_id').val();
	gType = type;
	gChannel2_id = channel2_id;
	gChannel2_name = $('#channel2_name').val();
	var url = './run.php?mid='+gMid+'&a=type_source&channel_id=' + channel_id + '&type='+ type +'&channel2_id=' + channel2_id + '&channel2_name=' + $('#channel2_name').val() + '&program_start_time=' + $('#program_start_time').val() + '&program_end_time=' + $('#program_end_time').val() + '&audio_only=' + audio_only + '&server_id=' + server_id;
	hg_ajax_post(url);
	
}
/*回调函数*/
function hg_type_source(html)
{
	$('#change_type_source').html(html);
//	hg_backupPage();
	if($.trim($('#change_type_source').html(html)))
	{
		gType = gType ? gType : 4;
		$('#change_type_source').find('#type_'+gType).click(change(gType));
		if(gType == 1 || gType == 4)
		{
			$('#type_content_'+gType).find('li a').removeClass('type_source');
			$('#type_content_'+gType).find('#live_'+gChannel2_id+' a').addClass('type_source');
		}
		else if(gType == 2)
		{
			$('#type_content_'+gType).find('li').removeClass('cur');
			$('#type_content_'+gType).find('#file_'+gChannel2_id).addClass('cur');
		}
		else if (gType == 3)
		{
			$('#start_times').val($('#program_start_time').val());
			$('#end_times').val($('#program_end_time').val());
			if($('#type_content_'+gType).find('#channel_list #item_shows_ li a').attr('attrid') == gChannel2_id)
			{
				$('#type_content_'+gType).find('#channel_list #item_shows_ li a').click(hg_select_channel_plan(obj, 1));
			}
		}
	}

	var h = $('#plan_form').position().top;
	var top_id=window.parent.document.getElementById('livnodewin');
	if(h>300)
	{
		var s = $(top_id).height()+$('#plan_form').height()+60;
		$(top_id).height(s);
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
		var channel_name = $(e).html();
		var url = "./run.php?mid="+gMid+"&a=chg_record_list&channel_id=" + channel_id+"&channel_name="+channel_name;
		hg_ajax_post(url);
	}
}
function hg_copy_record_plan(id)
{
	//alert(id);
	//alert($("#starts_"+id).val());
}
function hg_channel_live(obj,type,ids,backup_id)
{
	if(type==1 || type==2 || type == 4)
	{
		var channel2_id = obj.value;
		if(type==1 || type == 4)
		{
			var channel2_name = $(obj).find('a').html();
			$('#channel2_name_info').html('<span class="chg_type_fir">信号</span>'+'<span class="title">'+channel2_name+'</span>');
		}
		else
		{
			var channel2_name = $('#_title_'+backup_id).val();
			var toff = $('#_toff_'+backup_id).html();
		//	var channel2_name = $(obj).find('span').html();
			$('#channel2_name_info').html('<span class="chg_type_fir">文件</span>'+'<span class="title">'+channel2_name+'&nbsp;'+toff+'</span>');
		}
		$('#program_start_time').val('');
	}
	else
	{
		var channel2_id = $('#channel_id_plan_' + ids).val();
		var channel2_name = $('#channel_name_plan_'+ids).val();
		if(channel2_name == 'null')
		{
			var channel2_name = gChannel2_name;
		}
		/*里面的时间*/
		var start_times = $('#starts_'+ids).val();
		var end_times = $('#ends_'+ids).val();
		var stime = start_times.substr(5);
		var etime = end_times.substr(11);
		var in_toff = decode_time(etime) - decode_time(start_times.substr(11));
		/*外面的时间*/
		var start_time = $('#start_time').val();
		var end_time = $('#end_time').val();
		var out_toff = decode_time(end_time) - decode_time(start_time);
		/**/
		$('#channel2_name_info').html('<span class="chg_type_fir">时移</span>'+'<span class="title">'+channel2_name+'</span>'+'<span class="time_style">'+stime+' - '+etime+'</span>');
		$('#start_times').val(start_times);
		$('#end_times').val(end_times);
		$('#program_start_time').val(start_times);
		if(out_toff != in_toff)
		{
			$('#end_time').val(encode_time(decode_time(start_time) + in_toff));
			//$('#end_times').val(start_times.substr(0,10) + ' ' + encode_time(decode_time(start_times.substr(11)) + out_toff));
		}
	}

	$('#channel2_ids').val(channel2_id);
	$('#channel2_name').val(channel2_name);
	$('#type').val(type);
}
function hg_channel_chg_plan_start_time(obj,type)
{
	/*
	if($.trim($('#info_live').html()) === '')
	{
		alert('请从时移里面选择节目');
	}
	else
	{
		var channel2_name = $('#display_item_shows_').html();
		var s_time = $("#start_times").val();
		var e_time = $("#end_times").val();
		var stime = s_time.substr(5);
		var etime = e_time.substr(11);
		$('#channel2_name_info').html('<span class="chg_type_fir">时移</span>'+'<span class="title">'+channel2_name+'</span>'+'<span class="time_style">'+stime+' - '+etime+'</span>');
	}
	*/
}
