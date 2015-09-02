function hg_showCreate()
{
	if(gDragMode)
    {
	   return  false;
    }

	$('#auth_title').html('创建---');

	if($('#add_auth').css('display')=='none')
	{
	   var url = "run.php?mid="+gMid+"&a=form";
	   hg_ajax_post(url);
	   $('#add_auth').css({'display':'block'});
	   $('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeAuth();
	}
}

function hg_showEditIndex(appid)
{
	if(gDragMode)
    {
	   return  false;
    }

	$('#auth_title').html('编辑---');

	if($('#add_auth').css('display')=='none')
	{
	   var url = "run.php?mid="+gMid+"&a=form&id="+appid;
	   hg_ajax_post(url);
	   $('#add_auth').css({'display':'block'});
	   $('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeAuth();
	}
}

function hg_form_tpl(html,id,error)
{
	if(error)
	{
		jAlert(error);
	}
	if(id)
	{
		$("#r_" + id).html(html);
		hg_closeAuth();
	}
	else
	{
		$("#vodlist").append(html);
		hg_closeAuth();
	}
}
function hg_back_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}

//关闭面板
function hg_closeAuth()
{
	$('#log_box').html();
	$('#add_auth').animate({'right':'120%'},'normal',function(){$('#add_auth').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}
//放入模板
function hg_putAuthTpl(html)
{
	$('#auth_form').html(html);
	swf_upload()
}

function hg_putAuthTplNoUpload(html)
{
	$('#auth_form').html(html);
}


function fileQueueError(file, errorCode, message) {
	try {
		var imageName = "error.gif";
		var errorName = "";
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert(errorName);
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			imageName = "zerobyte.gif";
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			imageName = "toobig.gif";
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		default:
			alert(message);
			break;
		}

		addImage("images/" + imageName);

	} catch (ex) {
		this.debug(ex);
	}

}


function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadStart(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("正在上传...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	return true;
}

function uploadProgress(file, bytesLoaded) {

	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		var progress = new FileProgress(file,  this.customSettings.upload_target);
		progress.setProgress(percent);
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	try {
		//var progress = new FileProgress(file, this.customSettings.progressTarget);
		//progress.setComplete();
		if (serverData.replace(/\s+/g,""))
		{
			var obj = eval('('+serverData+')');
			if(obj[0].error)
			{
				alert(obj[0].error);
				return false;
			}
			var str = '<img style="float:left;" src="' + obj[0].host + obj[0].dir + '100x75/' + obj[0].filepath + obj[0].filename + '"  width="100" height="75" />';
			//var string = obj.toJSONString();
			str += "<input type='hidden' name='name' value = '" + obj[0].name +"' />";
			str += "<input type='hidden' name='host' value = '" + obj[0].host +"' />";
			str += "<input type='hidden' name='dir' value = '" + obj[0].dir +"' />";
			str += "<input type='hidden' name='filepath' value = '" + obj[0].filepath +"' />";
			str += "<input type='hidden' name='filename' value = '" + obj[0].filename +"' />";
			$("#log_box").html(str);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	try {
		//多个文件上传排队自动上传
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.toggleCancel(false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	var imageName =  "error.gif";
	var progress;
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Cancelled");
				progress.toggleCancel(false);
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Stopped");
				progress.toggleCancel(true);
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			imageName = "uploadlimit.gif";
			break;
		default:
			alert(message);
			break;
		}

		addImage("images/" + imageName);

	} catch (ex3) {
		this.debug(ex3);
	}

}


