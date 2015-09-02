/*显示新增集合的面板*/
var gCollectId = 0;

/*切换列表显示的模式
 * gSwitchMode为true的时候为的文字的模式
 * gSwitchMode为false的时候为的不带文字的模式
 * 
 * */
var gSwitchMode = 0;
function hg_showBackUp(flag,collect_id)
{	
	$('#file_input').hide();
	$('#file_text').hide();
	$('#f_file').hide();
	$('#close_file_input').show();
	$('#important_2').html('选择视频');
	$('#f_file').attr('disabled','disabled');
	if($('#f_file').val())
	{
		$('#f_file').val('');
	}
    if($('#add_collects').css('display')=='none')
	{
	   $('#add_collects').css({'display':'block'});
	   $('#add_collects').animate({'right':'10%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeBackUpTpl();
	}
}


/*关闭新增集合面板*/
function hg_closeBackUpTpl()
{
	 $('#add_collects').animate({'right':'120%'},'normal',function(){$('#add_collects').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 //$('#hg_select_all').hide();
	 hg_clearTplData();
}

/*获取视频*/
function hg_getManyVideos(start)
{
	if($('#add_collect_form').css('display') == 'none')
	{
		$('#add_collect_form').slideDown(function(){hg_resize_nodeFrame();});
	}
	var obj = hg_getSearchCondition();
	var url = './run.php?mid='+gMid+'&a=get_many_videos&start='+start+'&k='+obj.keywords+'&_id='+obj.vod_sort+'&_type='+obj.vod_leixing+'&trans_status='+obj.vod_status+'&g_switch_mode='+gSwitchMode;
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
	if(!$('#f_file').val())
	{
		$('#back_up_video_id').val(id);
	}
	var title =  $('#s_title_'+id).text();
	$('input[name="title"]').val(title.substr(3));
	var duration = $('#s_duration_'+id).text();
	var totalsize = $('#s_totalsize_'+id).text();
	var helper = $('#selected_videos_ul  .jscroll-c');
	drager.fadeOut(function(){

		hg_changeOneVideo();
	
		$('<span id=select_'+id+'   class="li"  onclick="hg_goback_content($(this));" ><div class="item_img"><img src='+src+'  onmousemove="hg_show_bigitem('+id+',true);"  onmouseout="hg_hide_bigitem('+id+',true);" onmousedown="hg_hide_bigitem('+id+',true);"  /></div><div id=info_'+id+' class="show_item"><span class="overflow"  id=info_title_'+id+' >'+title+'</span><span  id=info_duration_'+id+' >'+duration+'</span><span   id=info_totalsize_'+id+' >'+totalsize+'</span></div></span>').appendTo(helper).fadeIn();
		hg_setDragSettings('span[id^="select_"]');
		/*图片预览*/
		$('#img_span').show();
		$('#add_collect_form').slideUp();
		var imgsrc = $(this).find('img').attr('src');
		$('#vodid_img').attr('src',imgsrc);
		var name = $('#s_title_'+id).html();
		$('#vod_name').html(name);
		var toff = $('#info_duration_'+id).html();
		$('#vod_toff').html(toff);
		$(this).fadeIn();
		hg_scroll_left();
		hg_scroll_right();
	});
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
