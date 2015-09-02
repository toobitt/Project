function hg_showAddToCollect(video_id)
{
	if($('#add_videos').css('display')=='block')
	{
		hg_closeButtonX();
	}
	
    if($('#add_to_collect').css('display')=='none')
	{
       if($('#edit_show').css('display')=='block')
       {
    	   hg_close_opration_info();
       }
	   $('#add_to_collect').css({'display':'block'});
	   $('#add_to_collect').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 var url = './run.php?mid='+gMid+'&a=add_to_collect&id='+video_id;
		 hg_ajax_post(url);
		 hg_resize_nodeFrame();
	   });
	}
    else
    {
    	hg_closeAddToCollectTpl();
    }
}

function hg_closeAddToCollectTpl()
{
	 $('#add_to_collect').animate({'right':'120%'},'normal',function(){$('#add_to_collect').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}

//将请求过来的模板放到页面里面
function hg_putTplToAddCollect(html)
{
	$('#add_to_collect_form').html(html);
	var frame = top.document.getElementById("mainwin");
	frame = frame.contentWindow;
	$(frame).scrollTop(0);
}

//批量添加视频至集合时调用
function hg_moreVideosToCollect(obj)
{
	obj = hg_find_nodeparent(obj, 'FORM');
	var ids = hg_get_checked_id(obj);
	if(ids)
	{
		hg_showAddToCollect(ids);
		return true;
	}
	else
	{
		alert('请选择需要添加的视频');
		return false;
	}
}

//将视频添加到指定的集合中之后的回调函数
function hg_hide_tpl()
{
	hg_closeAddToCollectTpl();
	hg_clearAddCollectData();
}

//将视频添加到集合
function hg_videos_to_collect(form_id)
{
	if(!hg_checkIsCollect())
	{
		return false;
	}
	
	var ids = hg_get_checked_id('#'+form_id);
	if(ids)
	{
		$('#videos_id').val(ids);
		return hg_ajax_submit(form_id,'');
	}
	else
	{
		alert('选择需要添加的视频');
		return false;
	}	
}

//将视频添加到集合之前，需要判断所填的集合名称存不存在，如果 不存在需要创建一个集合，再将视频添加进去
function hg_checkIsCollect()
{
	//先判断用户输入的内容有没有与下拉列表中相匹配的,如果有的话就采用该集合
	var content_text = $('#get_contents').val();
	if(content_text)
	{
		$('div[name="collect_name"]').each(function(){
			var txt = $(this).text();
			if(txt == content_text)
			{
				$('#collect_id').val($(this).attr('id'));
			}
		});
		
		return true;
		
	}
	else
	{
		alert('请输入集合名称之后再添加');
		return false;
	}
}

//清除数据
function hg_clearAddCollectData()
{
	$('#get_contents').val('');
	$('#collect_id').val('');
	$('#videos_id').val('');
}

//对列表项的选中进行控制
function hg_switch_checked(obj)
{
	var obj_check = $(obj).find('input[name="videos_ids[]"]');
	var status = obj_check.attr('checked');
	if(status)
	{
		obj_check.attr('checked',false);
		$(obj).removeClass('vbg_color');
		
	}
	else
	{
		obj_check.attr('checked',true);
		$(obj).addClass('vbg_color');
	}
}
