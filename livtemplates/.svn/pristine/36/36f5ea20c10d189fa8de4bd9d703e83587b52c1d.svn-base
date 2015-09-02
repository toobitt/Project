var gAddImgaeTaskId;
function hg_showAddTuJipics(id,imageMode)
{
	if(!id)
	{	
		$('#tuji_pics_title').text('新增图片');
		$('#images_upload_nav').show();
		hg_single_param();/*默认显示单图片上传*/
		id = 0;
	}
	else
	{
		$('#tuji_pics_title').text('编辑图片');
		$('#images_upload_nav').hide();
	}
	
    if($('#add_tuji_pics').css('display')=='none')
	{
    	param = '';
    	if(imageMode)
    	{
    		param = "&mode="+imageMode;
    	}
    	var url = "run.php?mid="+gMid+"&a=form&id="+id+param;
    	hg_ajax_post(url);
	   $('#add_tuji_pics').css({'display':'block'});
	   $('#add_tuji_pics').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		  hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeTuJiPicsTpl();
	}
}

function hg_closeTuJiPicsTpl()
{
	 if(checkItemLabel())
	 {
		return;
	 }
	 $('#add_tuji_pics').animate({'right':'120%'},'normal',function(){$('#add_tuji_pics').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 $('#tuji_pics_form').html('');
	 top.livUpload.initPosition();
	 hg_taskCompleted(gAddImageTaskId);/*清除task*/
}

function hg_getTuJiPicForm(html)
{
	$('#tuji_pics_form').html(html);
}

//编辑完图片之后的回调函数
function hg_OverEditTujiPics(obj)
{
	var obj = eval('('+obj+')');
	for(var i = 0;i<obj.length;i++)
	{
		$('#tuji_pics_title_'+obj[i].id).text(obj[i].old_name);
	}
	hg_closeTuJiPicsTpl();//将滑出框关闭
}

//批量编辑图片
function hg_editMorePics(obj)
{
	obj = hg_find_nodeparent(obj, 'FORM');
	var ids = hg_get_checked_id(obj);
	if(ids)
	{
		hg_showAddTuJipics(ids);
	}
	else
	{
		alert('请选择要编辑的记录');
		return false;
	}
}

//上传选项卡之间的切换显示
function hg_switchNav(e)
{
	$('#single_select').removeClass('current');
	$('#more_select').removeClass('current');
	if(!e)
	{
		$("#single_select").addClass('current');
	}
	else
	{
		$(e).addClass('current');
	}
}

//在点击的同时，判断是不是点击同一个选项卡
function checkIsSameLabel(click_label)
{
	if($('#'+click_label).hasClass('current'))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*请求单图*/
function  hg_add_single_image()
{
	if(checkIsSameLabel('single_select')){return;}
	if(checkItemLabel())
	{
		return;
	}
	hg_single_param();
	var url = "run.php?mid="+gMid+"&a=form&id=0&mode=1";
	hg_ajax_post(url);
}

/*单图上传的一些设置*/
function hg_single_param()
{
	top.livUpload.uploadMode = true;
	top.livUpload.SWF.setButtonText("<span class='white'>选择图片</span>");
	hg_switchNav();
}

/*请求多图*/
function hg_add_more_image()
{
	if(checkIsSameLabel('more_select')){return;}
	if(checkItemLabel())
	{
		return;
	}
	top.livUpload.uploadMode = false;
	top.livUpload.SWF.setButtonText("<span class='white'>选择图片</span>");
	hg_switchNav('#more_select');
	var url = "run.php?mid="+gMid+"&a=form&id=0&mode=2";
	hg_ajax_post(url);
}

/*选项卡之间的切换判断*/
function checkItemLabel()
{
	//有文件等待上传
	if(top.isImage)
	{
		if(!confirm('您确定要切换吗?切换将放弃此次上传'))/*放弃切换*/
		{
			return  true;
		}
		else/*切换*/
		{	
			/*清空图片队列*/
			top.isImage = false;
			hg_clearAllImagesWait();
			return false;
		}
	}
	return false; 
}

/*多图片中取消某个队列*/
function hg_removeImagesQueue(file_id)
{
	$('#imageup_info_'+file_id).remove();
	top.livUpload.SWF.cancelUpload(file_id);
    top.hg_deleteValue(top.gMoreImageFileIds,file_id);
    $('#uploadStatus').text('您选择了' + top.gMoreImageFileIds.length + '个文件');/*设置选择文件的个数*/
    if(!top.livUpload.checkQueue())
    {
    	 /*隐藏开始上传按钮|取消按钮*/
    	 top.isImage = false;//置有无图片标志位为false;
    	 $('#uploadStatus_content').hide();
    	 hg_taskCompleted(gAddImageTaskId);/*清除task*/
    }
}

/*取消多图片中的所有队列*/
function hg_removeAllImagesQueue()
{
   $('div[id^="imageup_info_"]').remove();
   if(top.gMoreImageFileIds)
   {
	   	for(var i=0;i<top.gMoreImageFileIds.length;i++)
	   	{
	   		top.livUpload.SWF.cancelUpload(top.gMoreImageFileIds[i]);
	   	}
   }
   top.gMoreImageFileIds = new Array();
   $('#uploadStatus').text('您选择了' + top.gMoreImageFileIds.length + '个文件');/*设置选择文件的个数*/
   $('#uploadStatus_content').hide();
   top.isImage = false;//置有无图片标志位为false;
   hg_taskCompleted(gAddImageTaskId);/*清除task*/
}

/*多图上传操作*/
function hg_uploadMoreImages()
{
	if(top.gMoreImageFileIds.length)
	{
		top.livUpload.startUploadFile(true,true);
	}
}

/*清除等待中的图片队列*/
function hg_clearAllImagesWait()
{
	   /*清空掉可能在单视频过程中添加的视频，但是此时还没有点击确定*/
	   if(top.gOldFileId)
	   {
		   top.livUpload.SWF.cancelUpload(top.gOldFileId);
	   }
	   
	   /*清除掉可能在多视频过程中添加的视频，但是此时还没有点击确定*/
	   if(top.gMoreImageFileIds)
	   {
		   	for(var i=0;i<top.gMoreImageFileIds.length;i++)
		   	{
		   		top.livUpload.SWF.cancelUpload(top.gMoreImageFileIds[i]);
		   	}
	   }
	   
	   /*清除掉已经放入DataObject对象中的文件(点击确定按钮后的文件)*/
	   if(top.hg_ObjProNum(top.DataImageObject))
	   {
		   for(var fileid in top.DataImageObject)
		   {
			   top.livUpload.SWF.cancelUpload(fileid);
		   }
		   
		   top.DataImageObject = {};/*清空DataImageObject对象*/
		   top.clearInterval(top.timeTip);
		   top.timeTip = 0;
	   }
	   hg_taskCompleted(gAddImageTaskId);/*清除task*/
}

