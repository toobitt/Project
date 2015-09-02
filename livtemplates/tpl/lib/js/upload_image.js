function hg_removeImageQueue(file_id)
{
	$('#image_info_'+file_id).remove();
	top.livUpload.SWF.cancelUpload(file_id);
	top.hg_deleteValue(top.gMoreImageFileIds,file_id);
	$('#img_uploadStatus').text('您选择了' + top.gMoreImageFileIds.length + '个文件');
	if(!top.livUpload.checkQueue())
    {
    	$('#uploadImgOpration').hide();
    }
	
}

function hg_removeAllImagesQueue()
{
	 if(top.gMoreImageFileIds)//清空图片队列
     {
  	   	for(var i=0;i<top.gMoreImageFileIds.length;i++)
  	   	{
  	   		top.livUpload.SWF.cancelUpload(top.gMoreImageFileIds[i]);
  	   	}
     }
	 $('div[id^="image_info_"]').remove();
	 top.gMoreImageFileIds = new Array();//清空图片数组id
	 $('#uploadImgOpration').hide();
}

function hg_uploadMoreImages()
{
	top.livUpload.startUploadFile(0,1);//上传图片
}

