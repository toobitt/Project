/*显示面板*/
var gAddVideoTaskId;
var gCount = 0;
function hg_show_upload(mid)
{
   /*var height = $(window).height();*/
	/*隐藏新增视频的框子*/
	if($('#add_to_collect').css('display')=='block')
	{
		 hg_closeAddToCollectTpl();
	}
	
	/*隐藏操作框*/
    if($('#edit_show').css('display')=='block')
    {
 	   hg_close_opration_info();
    }
	
   if($('#add_videos').css('display')=='none')
	{
	   $('#add_videos').css({'display':'block'});
	   $('#add_videos').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 if(hg_selectItem())
		 {
			 hg_add_single_video();
		 }
		 else
		 {
			 hg_load_timeShift('#live_select');
		 }
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeButtonX();
	}
   
}

/*根据页面选择的状态，来选择打开上传面板时是显示单视频新增还是选择直播时移*/
function hg_selectItem()
{
	if(_type == 3 || _type == 4)
	{
		return false;
	}
	else
	{
		return true;
	}
}

/*获取页面当中类型与分类的信息*/
function hg_getTypeSort(id)
{
	if(parent.$('#hg_childnode_' + id).attr('id'))
	{
		var id = parent.$('#hg_childnode_' + id).parent().attr('id');
		return id[id.length-1];
	}
}

/*选项卡之间切换的时候，做的一些处理*/
var old_temp = 1;
var old_item = 1;
var new_item = 0;
/* 0==>初始状态什么都没点
 * 1==>点击单视频选项卡
 * 2==>点击多视频选项卡
 * 3==>点击时移选项卡
 * */
function hg_switchItem()
{
	/*只有不相同时才进行处理(相同就表示两次点击同一个选项卡)并且old_item不等于3的时候*/
	if(top.isVideo)
	{
		if(old_item != new_item && old_item != 3 )
		{
			if(!confirm('您确定要切换吗?切换将放弃此次上传'))/*放弃切换*/
			{
				hg_goBackFlag();
				return  true;
			}
			else/*切换*/
			{
				top.isVideo = false;
				hg_clearAllWait();
				if(old_item == 1)
				{
					hg_resetSingleData();
				}
				else if(old_item == 2)
				{
					hg_removeAllQueue();
				}
				
				return false;
			}
		}

	}
	
	return false;
	
	
}

/*切换时改变两个标志位的值*/
function hg_changeFlag(currentFlag)
{  
	old_temp = old_item;
	old_item = new_item;
	new_item = currentFlag;
}

/*若用户点击取消，则两个标志位返回前一个状态*/
function hg_goBackFlag()
{
	new_item = old_item;
	old_item = old_temp;
}

/*关闭上传模板之前所作的一些操作*/
function hg_beforeClose(flag)
{
	if(flag)
	{
		if(top.livUpload.checkQueue())
		{
			return hg_askExit();
		}
	}
	else
	{
		if(top.isVideo)
		{
			return hg_askExit();
		}
	}

	return true;

}

function hg_askExit()
{
	if(!confirm('您确定要退出吗?退出将放弃此次上传'))
	{
		return  false;
	}
	else
	{
		top.isVideo = false;
		hg_clearAllWait();
		hg_resetSingleData()
		hg_removeAllQueue();
		return true;
	}
}

function hg_closeButtonX(flag)
{
	 if(hg_beforeClose(flag))
	 {
		   hg_closeUploadTpl();/*关闭模板*/
	 }
}


/*将三个显示内容的div都隐藏*/
function hg_hideContentDiv(e)
{
	$('#single_select').removeClass('current');
	$('#hg_single_select').hide();

	$('#more_select').removeClass('current');
	$('#hg_more_select').hide();

	$('#live_select').removeClass('current');
	$('#hg_live_select').hide();
	if(!e)
	{
		$("#single_select").addClass('current');
	}
	else
	{
		$(e).addClass('current');
	}
}

/*关闭模板*/
function hg_closeUploadTpl()
{
	 hg_initFlashPosition();
	 $('#add_videos').animate({'right':'120%',},'normal',function(){$('#add_videos').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 /*隐藏开始上传按钮|取消按钮*/
	 $('#uploadStatus_content').hide();
	 top.livUpload.currentFlagId = '';/*退出的同时清空当前的currentFlagId*/
	 hg_taskCompleted(gAddVideoTaskId);/*清除task*/
}

/*设置flash位置归0*/
function hg_initFlashPosition()
{
	top.livUpload.SWF.setButtonDimensions(1,1);
    top.$('#flash_wrap').css({'left':'0px','top':'0px','position':'absolute'});
}

/*请求单视频模板*/
function  hg_add_single_video(e)
{
	/*选项卡之间切换处理*/
	hg_changeFlag(1);
	if(hg_switchItem())
	{
		return;
	}
	
	top.livUpload.uploadMode = true;
	//top.livUpload.SWF.setButtonText("<span class='white'>选择视频文件</span>");
	if(hg_hasLoaded('hg_single_select'))
	{
		hg_hideContentDiv(e);
		$('#hg_single_select').css('display','block');
		/*清空类别*/
		$('#display_sort_show').text('选择分类');
		$('#vod_sort_id').val('');
		
		top.livUpload.showTemplate(params_s);
		return;
	}
	hg_hideContentDiv(e);/*先把所有都隐藏*/
	var url = "./run.php?mid="+gMid+"&a=add_single_video";
	hg_ajax_post(url);
}
/*将请求的单视频模板放入页面中*/
function hg_single_upload(html)
{
   $('#hg_single_select').css('display','block').html(html);
}

/*请求多视频上传模板*/
function hg_add_more_videos(e)
{
	/*选项卡之间切换处理*/
	hg_changeFlag(2);
	if(hg_switchItem())
	{
		return;
	}
	top.livUpload.uploadMode = false;
	top.livUpload.SWF.setButtonText("<span class='white'>选择视频文件</span>");
	if(hg_hasLoaded('hg_more_select'))
	{
		hg_hideContentDiv(e);
		$('#hg_more_select').css('display','block');
		top.livUpload.showTemplate(params);
		return;
	}
	
	hg_hideContentDiv(e);/*先把所有都隐藏*/
	var url = "./run.php?mid="+gMid+"&a=add_more_videos";
	hg_ajax_post(url);
}

/*将多视频模板放入页面中*/
function hg_more_upload(html)
{
	$('#hg_more_select').css('display','block').html(html);
}

/*多视频中取消某个队列*/
function hg_removeQueue(file_id)
{
	$('#vup_info_'+file_id).remove();
	top.livUpload.SWF.cancelUpload(file_id);
    hg_deleteValue(top.gMoreFileIds,file_id);
    $('#uploadStatus').text('您选择了' + top.gMoreFileIds.length + '个文件');/*设置选择文件的个数*/
    //top.$('#livUpload_text').text(top.livUpload.displayStatus());/*顶级状态显示*/
    hg_hideBar();
    if(!top.livUpload.checkQueue())
    {
    	 top.isVideo = false;
    	 /*隐藏开始上传按钮|取消按钮*/
    	 $('#uploadStatus_content').hide();
    	 hg_taskCompleted(gAddVideoTaskId);/*清除task*/
    }
}

/*取消多视频中的所有队列*/
function hg_removeAllQueue()
{
   $('div[id^="vup_info_"]').remove();
   top.gMoreFileIds = new Array();
   $('#display_msort_show').text('选择分类');
   $('#vod_sort_ids').val('');
   $('#uploadStatus').text('您选择了' + top.gMoreFileIds.length + '个文件');/*设置选择文件的个数*/
   //top.$('#livUpload_text').text(top.livUpload.displayStatus());/*顶级状态显示*/
   hg_hideBar();
   top.isVideo = false;
   $('#uploadStatus_content').hide();
   hg_taskCompleted(gAddVideoTaskId);/*清除task*/
}

/*单视频中取消队列以及表单数据重置*/
function hg_resetSingleData()
{
	/*重置单视频表单数据*/
	$('#single_video_form')[0].reset();
	$('#video_localurl').text('');
	$('#title_vod').val('在这里添加标题').addClass("t_c_b");
	$('#comment_vod').val('这里输入描述').addClass("t_c_b");
	$('#display_sort_show').text('选择分类');
	$('#vod_sort_id').val('');
	$('#display_source_show_vod').text('自动');
	$('#source_show_vod').val('');
	top.gOldFileId = '';
	hg_hideBar();
	top.isVideo = false;
	hg_taskCompleted(gAddVideoTaskId);/*清除task*/
	$('#uploadStatus').text('您选择了' + top.gMoreFileIds.length + '个文件');/*设置选择文件的个数*/
	//top.$('#livUpload_text').text(top.livUpload.displayStatus());/*顶级状态显示*/
}


/*删除数组中某个指定值*/
function hg_deleteValue(arr,value)
{
	var index = $.inArray(value,arr);//返回索引位置
	arr.splice(index,1); //删除数组中的元素
}

/*检测有没有队列，没有队列隐藏进度条,并且取消task任务*/
function hg_hideBar()
{
	if(!top.livUpload.checkQueue())
	{
	   top.$('#livUpload_windows').hide();
	}
}

/*清空所有正在等待上传中的队列*/
function hg_clearAllWait()
{
   /*清空掉可能在单视频过程中添加的视频，但是此时还没有点击确定*/
   if(top.gOldFileId)
   {
	   top.livUpload.SWF.cancelUpload(top.gOldFileId);
   }
   
   /*清除掉可能在多视频过程中添加的视频，但是此时还没有点击确定*/
   if(top.gMoreFileIds)
   {
	   	for(var i=0;i<top.gMoreFileIds.length;i++)
	   	{
	   		top.livUpload.SWF.cancelUpload(top.gMoreFileIds[i]);
	   	}
   }
   
   /*清除掉已经放入DataObject对象中的文件(点击确定按钮后的文件)*/
   if(top.hg_ObjProNum(top.DataObject))
   {
	   for(var fileid in top.DataObject)
	   {
		   top.livUpload.SWF.cancelUpload(fileid);
	   }
	   
	   top.DataObject = {};/*清空DataObject对象*/
	   top.clearInterval(top.timeTip);
	   top.hg_goToTop();
	   top.timeTip = 0;
   }
  
   hg_taskCompleted(gAddVideoTaskId);/*清除task*/
}

/***********************************************************************************************************************************/

/*载入直播时移模板*/
function hg_load_timeShift(e)
{
	/*选项卡之间切换处理*/
	hg_changeFlag(3);
	if(hg_switchItem())
	{
		return;
	}
	hg_initFlashPosition();
	
	if(hg_hasLoaded('hg_live_select'))
	{
		hg_hideContentDiv(e);
		$('#hg_live_select').css('display','block');
		return;
	}
	hg_hideContentDiv(e);/*先把所有都隐藏*/
	var url = "./run.php?mid="+gMid+"&a=load_time_shift";
	hg_ajax_post(url);
}

/*将多视频模板放入页面中*/
function hg_time_shift(html)
{
	$('#hg_live_select').css('display','block').html(html);
}

var flags = 1;
function hg_show_channel()
{
	$("#other_list").slideUp();
	if(flags)
	{	
		$("#channel_list").slideDown();
		flags = 0;
	}
	else
	{
		$("#channel_list").slideUp();
		flags = 1;
	}
}

function hg_channel_show(offset,count)
{
	if(flags)
	{
		var url = "./run.php?mid=" + gMid + "&a=channel_show&counts="+count+"&offset="+offset;
		hg_ajax_post(url);
		flags = 0;
	}
	else
	{
		$("#channel_list").html();
		$("#channel_list").slideUp();
		flags = 1;
	}
}

function hg_channel_show_page(offset,count)
{
	var url = "./run.php?mid=" + gMid + "&a=channel_show&counts="+count+"&offset="+offset;
	hg_ajax_post(url);	
}

function hg_channel_show_back(html)
{
	if(html)
	{
		$("#channel_list").html(html);
		$("#channel_list").slideDown();		
	}
}



function hg_select_channel(e,id,mid,save_time)
{
	$("#channel_list").slideUp();
	$("#default_value").slideDown();
	$("#select_repeat").slideDown();
	$("#channel_name").html($(e).html()+"&nbsp;&nbsp;<span class='col_c'>("+ save_time + "小时时移)</span>");
	$("#channel_id").val(id);
	$("#show_span").html("重新选择频道");
	
	flags = 1;

	var url = "./run.php?mid="+mid+"&a=record_list&channel_id=" + id;
	hg_ajax_post(url);
}

function hg_record_list(html)
{
	if(html)
	{
		$("#info_live").html(html);
		$("#info_live").slideDown();
	}
}

function hg_show_record_list()
{
	if($("#info_live").css('display') == 'none')
	{
		$("#info_live").slideDown();
	}
	else
	{
		$("#info_live").slideUp();
	}
}

function hg_copy_record(id)
{
	$("#titlerecord").val($("#theme_"+id).val());
	$("#subtitlerecord").val($("#subtopic_"+id).val());
	$("#start_times").val($("#starts_"+id).val());
	$("#end_times").val($("#ends_"+id).val());
	$("#info_live").slideUp();
}


function hg_clear_record()
{
	$("#display_item_show").html('所有栏目');
	$("#item").val(-1);
	$("#show_span").html('选择频道');
	$("#channel_id").val('');
	$("#default_value").hide();
	$("#select_repeat").hide();
	$("#start_times").val('');
	$("#end_times").val('');
	$("#titlerecord").val('在这里添加标题');
	$("#commentrecord").val('这里输入描述');
	$("#subtitlerecord").val('');
	$("#keywordsrecord").val('');
	$("#authorrecord").val('');
	$("#display_source_showrecord").html('自动');
	$("#source_idrecord").val(-1);
}

function hg_check_record(is_go)
{
	if($("#item").val()<0)
	{
		hg_check_error('栏目不为空！');
		return false;
	}

	if(!$("#channel_id").val())
	{
		hg_check_error('请选择频道！');
		return false;
	}

	if(!$("#start_times").val())
	{
		hg_check_error('请选择开始时间！');
		return false;
	}

	if(!$("#end_times").val())
	{
		hg_check_error('请选择结束时间！');
		return false;
	}

	if(!$("#titlerecord").val())
	{
		hg_check_error('请填写标题！');
		return false;
	}
/*
	if($("#source_idrecord").val() < 0)
	{
		hg_check_error('请选择来源！');
		return false;
	}
*/
	if(is_go)
	{
		$("#goon").removeAttr("disabled");
	}
	else
	{
		$("#goon").attr("disabled","disabled");
	}

	if($("#commentrecord").val() == '这里输入描述')
	{
		$("#commentrecord").val('');
	}

	return hg_ajax_submit('theme_form');
}

function hg_check_error(tips)
{
	$("#error_tips_1").removeClass('success_tips');
	$("#error_tips_1").addClass('error_tips');
	$("#error_tips_1").html(tips);
	$("#error_tips_1").fadeIn(1500);
	$("#error_tips_1").fadeOut(1500);
}

function hg_call_record(html,is_go)
{
	//hg_get_trans_id();
	hg_clear_record();
	if(!is_go)
	{
		hg_closeUploadTpl();	
	}
	$("#vodlist").prepend(html);
}

/*判断某一选项下的内容有没有加载*/
function hg_hasLoaded(id)
{
	if($('#'+id).text() != '')
	{
		return true;
	}
	else
	{
		return false;
	}
}