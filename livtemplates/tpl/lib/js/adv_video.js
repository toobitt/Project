function  show_video()
{
	var state = $("#add_collect_form").css('display');
	if (state == 'block')
	{		
		$('#add_collect_form').animate({'height':'0px'},'normal',function(){
			 $("#add_collect_form").css('display','none');
		 });	
	}else if(state == 'none')
	{
		hg_getVideo(0);
		$('#add_collect_form').animate({'height':'422px'},'normal',function(){
			$("#add_collect_form").css('display','block');
		 });
	}
    //setTimeout(hg_resize_nodeFrame,500);
}

/*显示新增集合的面板*/
var gCollectId = 0;

/*切换列表显示的模式
 * gSwitchMode为true的时候为的文字的模式
 * gSwitchMode为false的时候为的不带文字的模式
 *
 * */
var gSwitchMode = 0;
/*获取视频*/
function hg_getVideo(start)
{
	var obj = hg_getCondition();
	var url = './run.php?mid='+gMid+'&a=get_videos&start='+start+'&g_switch_mode='+gSwitchMode+'&k='+obj.keywords+'&_id='+obj.vod_sort+'&_type='+obj.vod_leixing+'&trans_status='+obj.vod_status;
	hg_ajax_post(url);
}
/*搜索部分*/
function hg_search_k()
{	
	
	var obj = hg_getCondition();
	var url = './run.php?mid='+gMid+'&a=get_videos&k='+obj.keywords+'&_id='+obj.vod_sort+'&_type='+obj.vod_leixing+'&trans_status='+obj.vod_status+'&g_switch_mode='+gSwitchMode;
	hg_ajax_post(url);
}
/*获取查询条件*/
function hg_getCondition()
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
/*回调视频放入*/
function hg_putVideos(html)
{
	$('#video_content').html(html);
	$('#add_all_videos').hide();
	//hg_setDragSettings('#video_content_ul span[id^="s_"]');/*让这些图片可以拖动*/
	//hg_scroll_left();
	//hg_scroll_right();
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
    src = src.replace('/80x60/', '/');
	url2preview(src, 'video');
	var id  =  drager.attr('id').substr(2);	
	var title =  $('#s_title_'+id).text();
	var duration = $('#s_duration_'+id).text();
	var totalsize = $('#s_totalsize_'+id).text();
	var helper = $('#selected_videos_ul  .jscroll-c');
	$('input[name=mtype]').val('video');
	$("#material_url").val(id);
	$('#add_collect_form').hide();
}

/*判断右边视频存储区域是不是只有一个*/
function hg_changeOneVideo()
{
   var num = $('#selected_videos span[id^="select_"]').length;
   if(num)
   {
	  $('#selected_videos span[id^="select_"]').remove();
   }
   
}

/*返回到原来的地方*/
function hg_goback_content(drager)
{
	var src = drager.find('img').attr('src');
	var id  =  drager.attr('id').substr(7);
	var title =  $('#info_title_'+id).text();
	var duration = $('#info_duration_'+id).text();
	var totalsize = $('#info_totalsize_'+id).text();
	var helper = $('#video_content_ul .jscroll-c');
	drager.fadeOut(function(){
		$(this).remove();
		hg_scroll_left();
		hg_scroll_right();
	});
	
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
		
		hg_check_order(9,'s_','#video_content_ul');
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

/*获取选择的视频*/
function hg_get_selectedVidesId()
{
	/*获取要添加到该集合的视频的id(用逗号隔开)*/
	var video_id = 0;
	if($('#selected_videos_ul  span[id^="select_"]').length)
	{
	    video_id = $('#selected_videos_ul  span[id^="select_"]').attr('id').substr(7);
	}
	
	return video_id;
}


/*清空集合数据*/
function  hg_clearTplData()
{	
	/*搜索隐藏域值置空*/
	$('#sea_add_leixing_id').val(-1);
	$('#sea_add_collect_id').val(-1);
	$('#collect_trans_status').val(-1);
	$('#display_collect_leixing_show').text('全部类型');
	$('#display_sea_collect_addsort_show').text('全部分类');
	$('#display_collect_status_show').text('全部状态');
	$('#selected_videos_ul').html('');
	$('#video_content').html('');
}




/*搜索部分*/
function hg_search_videos_backup()
{
	var obj = hg_getSearchCondition();
	var url = './run.php?mid='+gMid+'&a=get_many_videos&k='+obj.keywords+'&_id='+obj.vod_sort+'&_type='+obj.vod_leixing+'&trans_status='+obj.vod_status;
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


/*视频显示模式的切换*/
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
