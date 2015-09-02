/*显示新增集合的面板*/
var gCollectId = 0;

/*切换列表显示的模式
 * gSwitchMode为true的时候为的文字的模式
 * gSwitchMode为false的时候为的不带文字的模式
 * 
 * */
var gSwitchMode = 0;
function hg_showAddCollect(flag,collect_id)
{
	if(gDragMode)
    {
	   return  false;
    }
	
	if(collect_id)
	{
		gCollectId = collect_id;
	}
	
    if($('#add_collects').css('display')=='none')
	{
	   hg_checkOpType(flag,collect_id);/*进来之前要判断操作的类型(新增集合/编辑集合)*/
	   $('#add_collects').css({'display':'block'});
	   $('#add_collects').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeCollectTpl();
	}
}

function hg_change_text_value(obj)
{
    $('#add_collect_name').val(obj[0].collect_name);
    if(obj[0].sort.sort_name)
    {
        $('#display_collect_addsort_show').text(obj[0].sort.sort_name);
        $('#add_collect_id').val(obj[0].sort.id);
    }
    else
    {
    	$('#display_collect_addsort_show').text('选择类别');
        $('#add_collect_id').val(-1);
    }
    
}

/*判断是新增视频还是编辑视频(并且对页面元素的显示做出相应的处理)*/
function hg_checkOpType(flag,collect_id)
{
	/*为true的时候是编辑*/
	if(flag)
	{
		$('#collect_title').text('编辑集合');
		$('#create_button').hide();
		$('#edit_button').show();
		
		/*如果是编辑集合,需要去接口把该集合的基本信息查找出来*/
	    var url = './run.php?mid='+gMid+'&a=edit_collect&id='+collect_id;
	    hg_ajax_post(url,'','','hg_change_text_value');
	}
	else
	{
		$('#collect_title').text('新增集合');
		$('#create_button').show();
		$('#edit_button').hide();
	}
}

/*关闭新增集合面板*/
function hg_closeCollectTpl()
{
	 $('#add_collects').animate({'right':'120%'},'normal',function(){$('#add_collects').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 $('#hg_select_all').hide();
	 hg_clearCollectData();
}

/*获取视频*/
function hg_getManyVideos(start)
{
	if($('#hg_select_all').css('display') == 'none')
	{
		$('#hg_select_all').show();
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
			$(this).remove();
		}
		else
		{
			$(this).fadeIn();
		}
		hg_scroll_left();
		hg_scroll_right();
	});
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
		if(!hg_checkAlreadyHas(id))
		{
			$('<span id=v_'+id+'   class="li"  onclick="hg_deleteSelf($(this));" ><div class="item_img"><img src='+src+'  onmousemove="hg_show_bigitem('+id+');"  onmouseout="hg_hide_bigitem('+id+');" onmousedown="hg_hide_bigitem('+id+');"  /></div><div id=s_'+id+' class="show_item"><span class="overflow"  id=s_title_'+id+' >'+title+'</span><span  id=s_duration_'+id+' >'+duration+'</span><span   id=s_totalsize_'+id+' >'+totalsize+'</span></div></span>').appendTo(helper).fadeIn();
			hg_setDragSettings('span[id^="v_"]');
		}
		
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

/*将左边所有的视频添加到右边*/
function hg_selectAllVideos()
{
	$('#video_content_ul span[id^="v_"]').each(function(){
		var drager = $(this);
		hg_deleteSelf(drager);
	});
}

/*将右边所有的视频全部返回到左边*/
function hg_goBackAllVideos()
{
	$('#selected_videos span[id^="select_"]').each(function(){
		var drager = $(this);
		hg_goback_content(drager);
	});
		
}

/*******************************************拖放的一些相关设置结束**************************************************************************/

/*提交到创建集合的接口*/
function hg_createThecollect()
{
	var collect_name   = $('#add_collect_name').val();/*获取要创建的集合的名称*/
	var collect_sort   = parseInt($('#add_collect_id').val());/*获取类别*/
    var video_ids = hg_get_selectedVidesIds();
	/*请求创建集合的接口*/
	var url = './run.php?mid='+gMid+'&a=create_collect_with&collect_name='+collect_name+'&sort_name='+collect_sort+'&ids='+video_ids;
	hg_ajax_post(url);
}

/*获取选择的视频*/
function hg_get_selectedVidesIds()
{
	/*获取要添加到该集合的视频的id(用逗号隔开)*/
	var ids = new Array();
	var video_ids = 0;
	$('#selected_videos_ul  span[id^="select_"]').each(function(){
		ids.push($(this).attr('id').substr(7));
	});
	
	if(ids)
	{
		video_ids = ids.join();
	}
	else
	{
		video_ids = 0;
	}
	
	return video_ids;
}


/*创建完集合的回调函数*/
function  hg_overCreateCollect(collect_id)
{
	var collect_id = parseInt(collect_id);
	hg_closeCollectTpl();
	hg_request_addlist(collect_id);
}

/*清空集合数据*/
function  hg_clearCollectData()
{
	$('#display_collect_addsort_show').text('选择分类');
	$('#add_collect_id').val('');
	$('#add_collect_name').val('');
	$('#selected_videos_ul').html('');
	$('#video_content').html('');
	
	/*搜索隐藏域值置空*/
	$('#sea_add_leixing_id').val(-1);
	$('#sea_add_collect_id').val(-1);
	$('#collect_trans_status').val(-1);
	$('#display_collect_leixing_show').text('全部类型');
	$('#display_sea_collect_addsort_show').text('全部分类');
	$('#display_collect_status_show').text('全部状态');
}

function hg_request_addlist(id)
{
	var url = './run.php?mid='+gMid+'&a=collect_info&id='+id+'&relate_module_id='+gRelate_module_id;
	hg_ajax_post(url);
}

function hg_addCollectlist(html)
{
	$('#collect_list').prepend(html);/*将新加入一行添加到页面*/
}

/*搜索部分*/
function hg_search_videos(obj)
{
	var obj = hg_getSearchCondition();
	var url = './run.php?mid='+gMid+'&a=get_many_videos&k='+obj.keywords+'&_id='+obj.vod_sort+'&_type='+obj.vod_leixing+'&trans_status='+obj.vod_status;
	hg_ajax_post(url);
}

/*点击编辑按钮调用数据*/
function hg_editThecollect()
{
	var collect_name   = $('#add_collect_name').val();/*获取要创建的集合的名称*/
	var collect_sort   = parseInt($('#add_collect_id').val());/*获取类别*/
	var video_ids = hg_get_selectedVidesIds();
	var url = './run.php?mid='+gMid+'&a=update_collect&collect_name='+collect_name+'&sort_name='+collect_sort+'&ids='+video_ids+'&collect_id='+gCollectId;
	hg_ajax_post(url);
}

/*编辑完的回调函数*/
function hg_overEditCollect(obj)
{
	var obj = eval('('+obj+')');
	$('#sort_'+obj.collect_id).text($('#display_collect_addsort_show').text());
	$('#collect_name_'+obj.collect_id).text(obj.collect_name);
	hg_closeCollectTpl();
}
/*删除集合*/
function hg_deleteCollect(id)
{
	if(gDragMode)
    {
	   return  false;
    }
	
	var url = "./run.php?mid="+gMid+"&a=delete&id="+id;
	return hg_ajax_post(url,'删除', 1);
}

/*查看集合内的视频*/
function hg_look_videos(obj)
{
	if(gDragMode)
    {
	   return  false;
    }
	
	window.location.href = $(obj).attr('href');
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
