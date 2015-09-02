var hg_pro = 0;
var mouse_x=mouse_y=0;
function program_form(mid,id,action, date,i,channel_id,event)
{
	mouse_x=event.clientX;
	mouse_y=event.clientY;
	hid();
	$("#item_num").val(id);
	$("#item_" + id).show();
	$("#all_bg").css('display','block');
	hg_pro = 1;
	switch(action)
	{
		case 'create':
			var url = './run.php?mid=' + mid + '&a=form&channel_id=' + channel_id + '&date=' + date + '&i=' + i ;
			hg_ajax_post(url,'添加','');
			break;
		case 'update':
			var url = './run.php?mid=' + mid + '&a=form&id=' + id + '&i=' + i +'&channel_id=' + channel_id;
			hg_ajax_post(url,'编辑');
			break;
		default:
			break;
	}
}


function hg_valid_program_data(html,id,i)
{
	if(id)
	{
		$("#day_" + i).html(html);
		hid();
	}
}
function hg_valid_program_create(html,i)
{
	$("#day_" + i).html(html);
	hid();
}

function hg_encode_unix(str)
{
	var new_str = str.replace(/:/g,'-');
	new_str = new_str.replace(/ /g,'-');
	var arr = new_str.split("-");
	var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
	return datum.getTime()/1000;
}


function hg_decode_unix(str)
{
	return new Date(parseInt(str) * 1000).toLocaleString().substr(-8,18);
}

function hg_program_show(html,id, channel_id, date,i)
{
	
	if(date)
	{
		date = date.split(' ');
		$("#p_day_ul").after(html);
		$("#stime").val(date);
		var str = hg_decode_unix(date);
		$("#start_time").val(str);
		ul_last_show(date);

	}
	else
	{
		$("#p_day_ul").after(html);
		ul_show(id);
	}
	$("#i").val(i);
	$("#program_item").attr('class','dis');
}
var hg_scrollLeft_time= 800;//滚动条时间
function ul_last_show(obj)
{
	var scroll = $(".p_item");
	var scroll_left = scroll.scrollLeft();
	var e = $("#pro_"+obj);
	var item_width=$("#program_item").width();
	var x = e.offset().left;
	var y = e.position().top;
	var xx = e.position().bottom;
	var z = e.height();
	var h = $("#program_item").height();
	var scroll_width=scroll.width();
	
	if(y > h)
	{
		var d = 58 ;
		y = $("#p_day_ul").height() - 64 - h;
		y = 'bottom:'+ y +'px;';
	}
	else if(y <= z)
	{
		var d = 19;
	}
	else
	{
		var d = 29 ;
		y = 'top:' + y + 'px;'
	}
	if( scroll_left == 0 && mouse_x > scroll_width/2)
	{
		var ss = mouse_x - scroll_width/2;
		scroll.animate({scrollLeft:ss},hg_scrollLeft_time,function(){scroll_left = scroll.scrollLeft();s =	mouse_x + 10;hg_scroll_left_show(s,d,y);});
		
	}
	else if(scroll_left > 0 && mouse_x > scroll_width/2)
	{
		var ss = mouse_x - scroll_width/2;
		scroll.animate({scrollLeft:scroll_left+ss},hg_scrollLeft_time,function(){scroll_left = scroll.scrollLeft();s =	mouse_x + 10 + scroll_left - ss;hg_scroll_left_show(s,d,y);});
		
	}
	else
	{
		s =	mouse_x + 10 + scroll_left;
		hg_scroll_left_show(s,d,y);
	}
	
	
}


function ul_show(obj)
{
	var scroll = $(".p_item")
	var scroll_left = scroll.scrollLeft();
	var e = $("#pro_"+obj);
	var x = e.offset().left-10;
	var y = e.position().top;
	var xx = e.position().bottom;
	var z = e.height();
	var h = $("#program_item").height();
	var item_width=$("#program_item").width();
	var ww = e.width();
	var w =	$("#item_3_"+obj).width();
	var	s =0;
	var ss = 0 ;
	var scroll_width=scroll.width();
	
	if(y > h)
	{
		var d = 58 ;
		y = $("#p_day_ul").height() - 64 - h;
		y = 'bottom:'+ y +'px;';
	}
	else if(y <= z)
	{
		var d = 19;
	}
	else
	{
		var d = 29 ;
		y = 'top:' + y + 'px;';
	}
	if( scroll_left == 0 && x + ww > scroll_width/2)
	{
		
		ss = x + ww - scroll_width/2;
		scroll.animate({scrollLeft:ss},hg_scrollLeft_time,function(){scroll_left = scroll.scrollLeft();s =	x + w;});
		
		
	}
	else if(scroll_left > 0 && x + ww > scroll_width/2)
	{
		ss = x + ww - scroll_width/2;
		scroll.animate({scrollLeft:scroll_left+ss},hg_scrollLeft_time,function(){scroll_left = scroll.scrollLeft();s =	x + w + scroll_left - ss;});
	}
	else
	{
		s =	x + w + scroll_left;
		
	}
	
	
}
function scroll_show(id)
{
	var scroll = $(".p_item")
	var scroll_left = scroll.scrollLeft();
	var e = $("#pro_"+id);//节目列表ID
	var ww = e.width();//节目列表宽度
	var w =	420;//录播框宽度
	var x = e.offset().left;//节目列表距左
	var	s =0;
	var ss = 0 ;
	var scroll_width=scroll.width();//可视区宽度
	ss = x + w - scroll_width+200;
	if( scroll_left == 0 && x + w > scroll_width/2+100)
	{
		scroll.animate({scrollLeft:ss},hg_scrollLeft_time,function(){
			upload_item_show(id);
		});
		
		
	}
	else if(scroll_left > 0 && x + w > scroll_width/2+100)
	{
		scroll.animate({scrollLeft:scroll_left+ss},hg_scrollLeft_time,function(){
			upload_item_show(id);
		});
	}
	else
	{
		upload_item_show(id);
		
	}
}
/*节目录制弹出框显示*/
function upload_item_show(id)
{
	$(".update_item").hide();
	$("#update_item_" + id).show();
	
}
/*原始录制弹出框显示*/
function hg_scroll_left_show(s,d,y)
{
	$("#program_item").animate({'left' : s + 'px','top': d+'px'},300,function(){$("#left_arrow").attr('style',y);});
}
function decode_time(str)
{	
	var arr = str.split(':');
	var hours = parseInt(arr[0])*3600;
	var mins = parseInt(arr[1])*60;
	var seconds = parseInt(arr[2]);
	var stime = hours + mins + seconds;
	return stime;
}

function encode_time(total,type)
{	
	total = parseInt(total);
	if(total >= 24*3600)
	{
		total = 24*3600 - 1;
	}
	var hours = parseInt(total/3600);
	var mins = parseInt((total - hours*3600)/60);
	var toff = hours + '小时' + mins + '分';
	return toff;
}


function toff()
{
	var s_time = decode_time($("#start_time").val());
	var e_time = decode_time($("#end_time").val());
	var offset_time = e_time - s_time;
	var toffs = encode_time(e_time - s_time);
	if(offset_time > 0)
	{
		$("#toff").html(toffs);
	}
	else
	{
		$("#toff").html('0小时0分钟');
	}

}
function is_start_time(str)
{
	var str = $("#start_time").val();
	var a = str.match(/^(\d{1,2})(:)?(\d{1,2})\2(\d{1,2})$/);
	if(a == null)
	{
		alert('输入的参数不是时间格式');
		return false;
	}
	if(a[1]>23 || a[3]>59 || a[4]>59)
	{
		alert("时间格式不对");
		return false
	}
}
function isTime(str)
{
	//短时间，形如 (13:04:06)
	var str = $("#end_time").val();
	var a = str.match(/^(\d{1,2})(:)?(\d{1,2})\2(\d{1,2})$/);
	if(a == null)
	{
		alert('输入的参数不是时间格式');
		return false;
	}
	if(a[1]>23 || a[3]>59 || a[4]>59)
	{
		alert("时间格式不对");
		return false
	}
	var s_time = decode_time($("#start_time").val());
	var e_time = decode_time($("#end_time").val());
	if(e_time < s_time)
	{
		alert("结束时间不对");
		return false;
	}
	return true;
} 

var flags = 0;

function hg_week_day(obj)
{
   if(flags == 0)
   {
	  
	  var day = $(obj).attr("name");
	  $(obj).css("border","1px solid #3E9E09");
	  $("#week_day").val(day);
	  flags = 1;

   }
   else
   {
	  $(obj).css("border","");   
	  $("#week_day").val("");
	  flags = 0;
   }

   
}


function hg_start_time()
{
	var start_time = decode_time($("#start_time").val());
	var end_time = decode_time($("#end_time").val());
	var toff = $("#hid_toff").val();
	old_start_time = end_time - toff;
	if(start_time < old_start_time)
	{
		alert("请先修改前一个节目的结束时间");
	}
}
function hg_end_time()
{
	var now_end_time = decode_time($("#end_time").val());
	var next_start_time = decode_time($("#next_start_time").val());
	var next_toff = decode_time($("#next_toff").val());
	next_end_time = next_start_time + next_toff;
	if(next_start_time != 0)
	{
		if(now_end_time > next_start_time)
		{
			alert("下一个节目的开始时间将被改掉");
		}
	}
	if(next_end_time != 0)
	{
		if(now_end_time > next_end_time)
		{
			alert("结束时间不能超过下一个节目的结束时间");
		}
	}
	
}

function channel_dis()
{
	$("#channel_hid").toggle();
}

function item_div_width(id)
{
	var string_obj = $("#item_3_" + id).text();
	var length = string_obj.length * 13;
	var pro_lenght = $("#pro_" + id).width() - 10;
	if(length <= pro_lenght)
	{
		$("#item_3_" + id).width(pro_lenght);
	}
	else
	{
		$("#item_3_" + id).width(length);
	}

}
function hg_pro_show(id)
{
	
	item_div_width(id);
	$("#item_" + id).show();
	var con = $("#item_3_" + id).html();
	if(con == "")
	{
		$("#item_3_" + id).html("请添加新节目");
	}
	
}

function hg_pro_hide(id)
{
	if(hg_pro==1)
	{
		$("#item_" + id).show();
	}
	else
	{
		$("#item_" + id).hide();
	}
		
}


function hid()
{
	if($("#program_item"))
	{
		$("#program_item").remove();
	}
	var val_id = $("#item_num").val();
	$("#item_" + val_id).hide();
	$("#all_bg").css('display','none');
	hg_pro=0;
}


function subform()
{
	if($('#upload_file').val() == '')
	{
		return;
	}
	hg_ajax_submit('upload_form');
}
function hg_program_callback(data)
{
	var data = eval('('+data+')');
	console.log(data);
	if(data == 'error')
	{
		if(confirm('检测出存在相同日期的节目单!确定覆盖吗?'))
		{
			var url = 'run.php?'+'mid='+gMid+'&a=program2db';
			if(!$('#channel_id').val())
			{
				return;
			}
			var data = {channel_id:$('#channel_id').val()};
			hg_request_to(url, data,'', 'program2db_callback');
		}
		else
		{
			alert('您取消了节目单上传！');
			return;
		}
	}
	else
	{
		if(data == 'success')
		{
			program2db_callback();
			return;
		}
		alert(data);
	}
	
}

function program2db_callback()
{
	window.location.reload();
}

$(function(){
	var vid = $('#hg_channel').val();
	$('#channel_show_'+vid).css('background','#F2F2F2');

});
