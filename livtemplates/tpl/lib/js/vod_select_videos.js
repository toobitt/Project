var gSwitchMode = 0;/*用于切换数据*/
function hg_showAddVideos()
{
    if($('#add_collects').css('display')=='none')
	{
    	hg_getManyVideos(0);
    	$('#add_collects').css('display','block');
	}
}

/*以当前的视频作为基础，添加一个视频片段*/
function hg_addCurrentMark()
{
	var time = getTime();
	current_id = hg_getCurrentId();
	var max_duration = parseInt($('#max_duration_'+current_id).val());
	var original_id = parseInt($('#original_id_'+current_id).val());
	var duration = parseInt(time.end) - parseInt(time.start);
	var endTime =  parseInt(time.end) + duration;
	
	if(!duration)
	{
		alert('标注的时长不能为空');
		return;
	}
	
	if(parseInt(time.end) >= max_duration)
	{
		add_mark_one_video(original_id);
		hg_loadVideoFlash(original_id);
		return;
	}

	add_mark_one_video(original_id,time.end,duration);
	hg_loadVideoFlash(original_id,time.end,duration);
}


/*关闭新增集合面板*/
function hg_closeVideosTpl()
{
	$('#add_collects').css('display','none');
	hg_clearCollectData();
}

/*获取视频*/
function hg_getManyVideos(start)
{
	if($('#hg_select_all').css('display') == 'none')
	{
		$('#hg_select_all').show();
	}
	var current_id = hg_getCurrentId();
	var video_id = $('#original_id_'+current_id).val();
	var obj = hg_getSearchCondition();
	var url = './run.php?mid='+gMid+'&a=get_many_videos&start='+start+'&k='+obj.keywords+'&_id='+obj.vod_sort+'&_type='+obj.vod_leixing+'&trans_status='+obj.vod_status+'&g_switch_mode='+gSwitchMode+'&video_id='+video_id;
	hg_ajax_post(url);
}

/*获取查询的条件*/
function hg_getSearchCondition()
{
	var keywords = $('#search_list_vk').val();
	if(keywords == '关键字')
	{
		keywords = '';
	}
	
	var vod_sort = 0;
	if($('#sea_add_collect_id').length)
	{
		vod_sort = parseInt($('#sea_add_collect_id').val());
		if(vod_sort == -1)
		{
			vod_sort = 0;
		}
	}

	vod_leixing = parseInt($('#sea_add_leixing_id').val());
	if(vod_leixing == -1)
	{
		vod_leixing = 0;
	}

	var vod_status = parseInt($('#collect_trans_status').val());
	return {'keywords':keywords,'vod_sort':vod_sort,'vod_leixing':vod_leixing,'vod_status':vod_status};
}



/*将返回的视频放置到指定的地方*/
function hg_putVideos(html)
{
	$('#video_content').html(html);
	$('#add_all_videos').hide();
	hg_setDragSettings('#video_content_ul span[id^="v_"]');/*让这些图片可以拖动*/
	hg_scroll_left();
	hg_scroll_right();
}

/*******************************************拖放的一些相关设置开始**************************************************************************/

/*设置拖动的参数*/
function hg_setDragSettings(condition)
{
	$(condition).draggable({
		revert: "invalid", 
		helper: "clone",
		cursor: "move",
		revertDuration:500,
		opacity:0.8,
		containment:'document'
	});
}

/*设置拖放的参数*/
function hg_setDropSettings()
{
	$('#video_content_ul').draggable({
		revert: "invalid", 
		helper: "clone",
		cursor: "move",
		revertDuration:500,
		opacity:0.8,
		containment:'document'
	});
}


/*删除被拖动的对象*/
function hg_deleteSelf(drager)
{
	var src = drager.find('img').attr('src');
	var id  =  drager.attr('id').substr(2);
	var title =  $('#s_title_'+id).text();
	var duration = $('#s_duration_'+id).text();
	var totalsize = $('#s_totalsize_'+id).text();
	var helper = $('#selected_videos_ul  .jscroll-c');
	drager.fadeOut(function(){
		if(!hg_checkAlreadyHas(id,true))
		{
			$('<span id=select_'+id+'   class="li"  onclick="hg_goback_content($(this));" ><div class="item_img"><img src='+src+'  onmousemove="hg_show_bigitem('+id+',true);"  onmouseout="hg_hide_bigitem('+id+',true);" onmousedown="hg_hide_bigitem('+id+',true);"  /></div><div id=info_'+id+' class="show_item"><span class="overflow"  id=info_title_'+id+' >'+title+'</span><span  id=info_duration_'+id+' >'+duration+'</span><span   id=info_totalsize_'+id+' >'+totalsize+'</span></div></span>').appendTo(helper).fadeIn();
			hg_setDragSettings('span[id^="select_"]');
		}
		$(this).fadeIn();
		hg_closeVideosTpl();
		add_mark_one_video(id);
		hg_loadVideoFlash(id);
		hg_scroll_left();
		hg_scroll_right();
	});
}

/*第一次加载进来的时候,要将原视频放在右边的区域*/
function hg_putSourceVideo(id)
{
	var url = "./run.php?mid="+gMid+"&a=get_video&id="+id;
	hg_ajax_post(url);
}

/*请求原视频的信息的回调函数*/
function hg_OverGetVideoInfo(obj)
{
	var obj = eval('('+obj+')');
	var helper = $('#selected_videos_ul');
	$('<span id=select_'+obj.id+'   class="li"  onclick="hg_goback_content($(this));" ><div class="item_img"><img src='+obj.img+'  onmousemove="hg_show_bigitem('+obj.id+',true);"  onmouseout="hg_hide_bigitem('+obj.id+',true);" onmousedown="hg_hide_bigitem('+obj.id+',true);"  /></div><div id=info_'+obj.id+' class="show_item"><span class="overflow"  id=info_title_'+obj.id+' >名称：'+obj.title+'</span><span  id=info_duration_'+obj.id+' >时长：'+obj.duration+'</span><span   id=info_totalsize_'+obj.id+' >大小：'+obj.totalsize+'</span></div></span>').appendTo(helper);
}

/*左边添加右边之前，要判断右边是不是已经有了*/
function hg_checkAlreadyHas(id,flag)
{
   if(flag)
   {
	   if($('#select_'+id).length)
	   {
		   return true;
	   }
	   else
	   {
		   return false;
	   }
   }
   else
   {
	   if($('#v_'+id).length)
	   {
		   return true;
	   }
	   else
	   {
		   return false;
	   }
   }

}

/*返回到原来的地方*/
function hg_goback_content(drager)
{
	var id  =  drager.attr('id').substr(7);
	hg_closeVideosTpl();
	add_mark_one_video(id);
	hg_loadVideoFlash(id);
}


/*显示视频信息*/
function hg_show_bigitem(obj,right)
{
	if(right)
	{
		hg_check_order(2,'info_','#selected_videos_ul');
		var v = $('#select_'+obj);
		var s = $('#info_'+obj);
		var top = v.height();
		s.css({'top':top+'px'});
		s.fadeIn('fast');
	}
	else
	{
		if(gSwitchMode)
		{
			return;
		}
		
		hg_check_order(6,'s_','#video_content_ul');
		var v = $('#v_'+obj);
		var s = $('#s_'+obj);
		var top = v.height();
		s.css({'top':top+'px'});
		s.fadeIn('fast');
	}
	
}

function hg_check_order(num,name,content_div)
{
	var i=1;
	$(content_div).find("div[id^="+name+"]").each(function(){
		if(i%num == 0)
		{
			$(this).css({'left':'auto','right':'0'});
		}
		else
		{
			$(this).css({'left':'0','right':'auto'});
		}
		i++;
	});
}

function hg_hide_bigitem(obj,right)
{
	if(right)
	{
		var s = $('#info_'+obj);
		s.fadeOut('fast');
	}
	else
	{
		if(gSwitchMode)
		{
			return;
		}
		
		var s = $('#s_'+obj);
		s.fadeOut('fast');
	}
	
}


/*******************************************拖放的一些相关设置结束**************************************************************************/

/*获取选择的视频id*/
function hg_get_selectedVidesIds()
{
	var video_id = 0;
	if($('#selected_videos_ul  span[id^="select_"]').length)
	{
		var obj = $('#selected_videos_ul  span[id^="select_"]');
		video_id = $(obj).attr('id').substr(7);
	}
	return video_id;
}


/*标注好一个视频,如果原来没有视频小框子就创建，有的话就改写开始时间与结束时间*/
var vcr_num = 0;
var gAddId = 0;//记录添加视频的上一个id
function add_mark_one_video(id,startTime,duration,is_current)
{
	if(gAddId != id && gAddId)
	{
		$('#unselect').hide();
	}
	gAddId = id;
	
	/*判断当前已经添加的视频片段的个数是否超过预设值*/
	if($('li[id^="video_box_"]').length >= 10)
	{
		alert('不能再添加视频片段了，请删除视频片段再添加');
		return;
	}

	/*新增标注*/
	/*在添加一个小框子之前要将其他的框子都置为非选中状态*/
	hg_doBeforeAddMark();
	var param = '';
	/*
	if(startTime && duration)
	{
		param = '&start_time='+startTime+'&duration='+duration;
	}
	*/
	
	if(!startTime)
	{
		startTime = 0;
	}
	
	if(!duration)
	{
		duration = '';
	}
	
	if(!is_current)
	{
		is_current = 0;
	}
	
	param = '&start_time='+startTime+'&duration='+duration+'&is_current='+is_current;
	vcr_num++;
	url = './run.php?mid='+gMid+'&a=mark_one_video&video_id='+id+param+'&vcr_num='+vcr_num;
	hg_ajax_post(url);
}


/*将请求过来的小盒子模板放入固定的容器中*/
function hg_put_videobox(html)
{
	$('#add_mark_videos_box').append(html);
}

/*拖动标注的指针编辑小框子的开始时间与时长(只在前台做dom处理)*/
function hg_editvideoBox(startTime,duration)
{
	current_id = hg_getCurrentId();
	$('#start_time_'+current_id).val(startTime);
	$('#duration_'+current_id).val(duration);
	//$('#start_time_box_'+current_id).text(hg_format_time(startTime));
	$('#duration_box_'+current_id).text(hg_format_time(duration));
}

/*找出当前选择的视频片段的id*/
function hg_getCurrentId()
{
	var current_id = 0;
	$('input[id^="attrflag_"]').each(function(){
		if($(this).val() == '1')
		{
			current_id = $(this).attr('id');
		}
	});
	
	if(current_id)
	{
		current_id = current_id.substr(9);
	}

	return current_id;
}


/*时间格式化函数*/
function hg_format_time(time)
{
	var num = Math.floor(time/1000);//求出总秒数
	var minute = Math.floor(num/60);//求出分钟数
	var second = Math.floor(num%60);//求出秒数
	if(second < 10)
	{
		second = '0' + second;
	}
	return  minute+"’"+second+"”";
}

/*删除视频小盒子*/
function hg_remove_videobox(id)
{
	if($('li[id^="video_box_"]').length == 1)
	{
		alert('不能再删除,至少保留一个视频');
		return;
	}
	
	$('#video_box_'+id).remove();
	$('#con_m_l_t').hide();
	if(!hg_getCurrentId())
	{
		var id = $('li[id^="video_box_"]:first').attr('id').substr(10);
		hg_display_current(id);
	}
	//判断所有的视频片段是不是来自同一个视频
	if(!hg_checkFormOneType())
	{
		$('#unselect').show();
	}
	else
	{
		$('#unselect').hide();
	}
}

//判断所有的视频片段是不是来自同一个视频,并且做相应的处理
function hg_checkFormOneType()
{
	var isame = false;
	var itemp = parseInt($('input[id^="original_id_"]:first').val());
	$('input[id^="original_id_"]').each(function(){
		 if(parseInt($(this).val()) != itemp)
		 {
			 isame = true;
		 }
	});
	return isame;
}


/*单击视频片段时,显示改变当前框子的颜色,并且重载flash*/
function hg_display_current(id)
{
	if(!$('#video_box_'+id).length)
	{
		return;
	}
	
	/*视频片段还剩一个，并且该视频已经处于选中状态，就退出*/
	if($('li[id^="video_box_"]').length == 1 && hg_getCurrentId())
	{
		return;
	}
	
	hg_doBeforeEditMark(id);
	var original_id = $('#original_id_'+id).val();
	var start_time = $('#start_time_'+id).val();
	var duration = $('#duration_'+id).val();
	hg_loadVideoFlash(original_id,start_time,duration);/*加载当前选中的视频flash*/
}

/*要新增标注之前需要做的一些工作*/
function hg_doBeforeAddMark()
{
	$('li[id^="video_box_"]').removeClass('h');
	$('input[id^="attrflag_"]').val(0);
}

/*在编辑标注之前做的一些工作*/
function hg_doBeforeEditMark(id)
{
	$('li[id^="video_box_"]').removeClass('h');
	$('#video_box_'+id).addClass('h');
	$('input[id^="attrflag_"]').val(0);
	$('#attrflag_'+id).val(1);/*表明当前编辑的对象*/
}


/*载入指定视频的flash*/
function hg_loadVideoFlash(video_id,start_time,duration)
{
	if(!start_time)
	{
		start_time = '';
	}
	
	if(!duration)
	{
		duration = '';
	}

	var list_url = "./run.php?mid="+gMid+"&a=load_flash_list&video_id="+video_id+"&start_time="+start_time+"&duration="+duration;
	hg_ajax_post(list_url);
}

/*重载标注flash的回调函数*/
var gLoadFlashMode = false;
function hg_put_flash_list(obj)
{
	gLoadFlashMode = true;
	var obj = eval('('+obj+')');
	document.getElementById('view').setMedia(obj.vodid,obj.video_mark,obj.start,obj.duration,obj.aspect);
	document.getElementById('view').setMedia(obj.vodid,obj.video_mark,obj.start,obj.duration,obj.aspect);
	gLoadFlashMode = false;
}

/*清空数据*/
function  hg_clearCollectData()
{	
	$('#video_content').html('');
	
	/*搜索隐藏域值置空*/
	$('#sea_add_leixing_id').val(-1);
	$('#sea_add_collect_id').val(-1);
	$('#collect_trans_status').val(-1);
	$('#display_collect_leixing_show').text('全部类型');
	$('#display_sea_collect_addsort_show').text('全部分类');
	$('#display_collect_status_show').text('全部状态');
}

/*鼠标移动到添加按钮上两秒钟弹出视频选择框子*/
var gTimeOut = 0;
var gAutoTimtOut = 0;/*打开窗口后，如果用户在3秒内没有任何操作就消失*/
function hg_mouseOverShow()
{
	gTimeOut = setTimeout(hg_add_count,1000);
}

function hg_add_count()
{
	clearTimeout(gAutoTimtOut);
	hg_showAddVideos();
	clearTimeout(gTimeOut);
	gAutoTimtOut = setTimeout(hg_hide_tpl,3000);
}

function hg_hide_tpl()
{
	hg_closeVideosTpl();
	clearTimeout(gAutoTimtOut);
}

/*鼠标离开时触发的事件*/
function hg_mouseOut()
{
	clearTimeout(gTimeOut);
}

/*鼠标移上弹出框的时候触发的事件*/
function hg_mouseOverTpl()
{
	clearTimeout(gAutoTimtOut);
}

/*鼠标移开弹出框的时候触发的事件*/
function hg_mouseOutTpl()
{
	gAutoTimtOut = setTimeout(hg_hide_tpl,3000);
}


/*鼠标点击添加视频片段*/
function hg_clickAdd()
{
	clearTimeout(gTimeOut);
	hg_addCurrentMark();
	clearTimeout(gTimeOut);
}

/***********************************视频反选**********************/
function hg_unselect_video()
{
	var id = hg_getCurrentId();
	var original_id = parseInt($('#original_id_'+id).val());
	var iTime = getTime();
	var max_duration  = parseInt($('#max_duration_'+id).val());
	var next_duration = max_duration - iTime.end;
	var arr = new Array();
	if(parseInt(iTime.start) > 0)
	{
		arr.push({'start':0,'duration':iTime.start});
	}
		
	if(parseInt(iTime.end) < max_duration)
	{
		arr.push({'start':iTime.end,'duration':next_duration});
	}
	
	if(arr.length)
	{
		for(var i=0;i<arr.length;i++)
		{
			if(i == arr.length - 1)
			{
				add_mark_one_video(original_id,arr[i].start,arr[i].duration);/*置为当前选中状态*/
				 hg_loadVideoFlash(original_id,arr[i].start,arr[i].duration);/*加载当前选中的视频flash*/
			}
			else
			{
				add_mark_one_video(original_id,arr[i].start,arr[i].duration,1);/*不是当前选中状态*/
			}
		}
		arr = new Array();//清空数组
		$('#video_box_'+id).remove();
		$('#con_m_l_t').hide();
	}
	else
	{
		alert('请拖动时间轴之后再反选');
	}
}

/****************************反选功能************************************/
function hg_unselect_videos()
{
	var id = hg_getCurrentId();
	var original_id = parseInt($('#original_id_'+id).val());
	var iTime = new Array();
	var max_duration = parseInt($('li[id^="video_box_"]:first').find("input[id^='max_duration_']").val());
	$('li[id^="video_box_"]').each(function(){
			var sTime    = parseInt($(this).find("input[name='start_time[]']").val());
			var duration = parseInt($(this).find("input[name='duration[]']").val());
			var eTime = sTime + duration;
			iTime.push(sTime);
			iTime.push(eTime);
	});
	
	iTime.sort(function compare(a,b){return a-b;});//按时间大小排序
	var ilen = 1;
	if(iTime[0] != 0)
	{
		iTime.push(0);
		ilen = 0;
	}
	
	iTime.sort(function compare(a,b){return a-b;});//按时间大小排序
	var iright = 1;//是否顶到最右边(1:顶到了,0:没有)
	if(iTime[iTime.length - 1] < max_duration)
	{
		iTime.push(max_duration);
		iright = 0;
	}
	iTime.sort(function compare(a,b){return a-b;});//再排一次序
	var rdiff = 0;//记录右边的偏移值
	if(iright)
	{
		rdiff = iTime.length - 3;
	}
	else
	{
		rdiff = iTime.length - 2;
	}
	
	if(iTime.length == 2)
	{
		alert('请拖动时间轴之后再反选');
		return;
	}

	var un_duration = new Array();//用于存储反选之后的开始时间
	var un_start = new Array();//用于存储反选之后的时长
	var load_flash = {};//最后要加载的flash时间
	while(ilen <= rdiff)
	{
		var diff = iTime[ilen + 1] - iTime[ilen];
		if(diff == 0)
		{
			ilen+=2;
			continue;
		}
		
		if(ilen == rdiff)
		{
			 un_duration.push(diff);
			 un_start.push(iTime[ilen]);
			 load_flash.start = iTime[ilen];
			 load_flash.duration = diff;
		}
		else
		{
			un_duration.push(diff);
			un_start.push(iTime[ilen]);
		}
		ilen+=2;
	}
	if(un_duration.length > 10)
	{
		alert('反选后视频片段的数量超过10段，请重新拆条');
		return;
	}
	
	//清空原有的片段
	$('li[id^="video_box_"]').remove();
	hg_loadVideoFlash(original_id,load_flash.start,load_flash.duration);/*加载选中的视频flash*/
	var un_duration_str = un_duration.join(',');
	var un_start_str	= un_start.join(',');
	var url = "run.php?mid="+gMid+"&a=get_unselect_videos&id="+original_id+"&duration="+un_duration_str+"&start="+un_start_str;
	hg_ajax_post(url);
}

/**********************************编辑部分开始************************************************/
function hg_put_vcr(html)
{
	$('#add_mark_videos_box').append(html);
	var max_num = 0;
	var min_num = $('input[name="order_id[]"]:first').val();
	$('input[name="order_id[]"]').each(function(){
		
		var video_id = $('#original_id_'+$(this).val()).val();
		var img = $('#video_box_'+$(this).val()).find('img').attr('src');
		var title = $('#name_'+$(this).val()).val().replace('(片段)','');
		var duration = hg_format_time($('#max_duration_'+$(this).val()).val());
		var totalsize = $('#totalsize_'+$(this).val()).val();
		var obj = {'id':video_id,'img':img,'title':title,'duration':duration,'totalsize':totalsize};
		if(!$('#select_'+video_id).length)
		{
			hg_createImgBox(obj);/*创建已选视频放在视频已选择区域*/
		}
		
		if(max_num < parseInt($(this).val()))
		{
			max_num = parseInt($(this).val());
		}
	});
	
	vcr_num = max_num;
	$('li[id^="video_box_'+min_num+'"]').addClass('h');
	$('input[id^="attrflag_'+min_num+'"]').val(1);
	
}

function hg_createImgBox(obj)
{
	var helper = $('#selected_videos_ul');
	$('<span id=select_'+obj.id+'   class="li"  onclick="hg_goback_content($(this));" ><div class="item_img"><img src='+obj.img+'  onmousemove="hg_show_bigitem('+obj.id+',true);"  onmouseout="hg_hide_bigitem('+obj.id+',true);" onmousedown="hg_hide_bigitem('+obj.id+',true);"  /></div><div id=info_'+obj.id+' class="show_item"><span class="overflow"  id=info_title_'+obj.id+' >名称：'+obj.title+'</span><span  id=info_duration_'+obj.id+' >时长：'+obj.duration+'</span><span   id=info_totalsize_'+obj.id+' >大小：'+obj.totalsize+'</span></div></span>').appendTo(helper);
}

/**********************************编辑部分结束************************************************/


/*搜索部分*/
function hg_search_videos(obj)
{
	var current_id = hg_getCurrentId();
	var video_id = $('#original_id_'+current_id).val();
	var obj = hg_getSearchCondition();
	var url = './run.php?mid='+gMid+'&a=get_many_videos&k='+obj.keywords+'&_id='+obj.vod_sort+'&_type='+obj.vod_leixing+'&trans_status='+obj.vod_status+'&video_id='+video_id;
	hg_ajax_post(url);
}

function hg_scroll_left()
{
		$("#video_content_ul").jscroll({ W:"4px"
			,Bg:"none"
			,Bar:{Bd:{Out:"#000",Hover:"#000"}
				 ,Bg:{Out:"#000",Hover:"#000",Focus:"#000"}}
			,Btn:{btn:false}
		});
}
function hg_scroll_right()
{
	$("#selected_videos_ul").jscroll({ W:"4px"
				,Bg:"none"
				,Bar:{Bd:{Out:"#000",Hover:"#000"}
					 ,Bg:{Out:"#000",Hover:"#000",Focus:"#000"}}
				,Btn:{btn:false}
	});
}

function  hg_switchThecollect()
{
	if(gSwitchMode)
	{
		gSwitchMode = 0;
		$('#video_content_ul').removeClass('text').addClass('img');/*切换为不带文字的模式*/
		$('div[id^="s_"]').hide();
		$('#switch_button').val('大图模式');
	}
	else
	{
		gSwitchMode = 1;
		$('#video_content_ul').removeClass('img').addClass('text');/*切换为带文字的模式*/
		$('div[id^="s_"]').show();
		$('#switch_button').val('小图模式');
	}
	hg_scroll_left();
	hg_scroll_right();
}


function hotkey(e) 
{ 
	
	var q=window.event ? e.keyCode:e.which; 
	if((q==81)&&(e.altKey)) //快速关闭编辑
	{ 
		hg_closeVideosTpl(); 
	}
	
}

document.onkeydown = hotkey; /*当onkeydown 事件发生时调用hotkey函数 */