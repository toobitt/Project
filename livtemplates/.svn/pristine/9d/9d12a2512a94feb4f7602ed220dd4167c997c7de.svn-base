function hg_show_water_pos(obj)
{
	var $o = $(obj).find('input');
	var id = $o.val();
	
	if(!$o.attr('checked'))
	{
		$o.attr('checked','checked');
	}
	$('div[id^="water_img_"]').css('visibility','hidden');
	$('#water_img_'+id).css('visibility','visible');
}


function water_fileQueueError(file, errorCode, message) {
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


function water_fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function water_uploadProgress(file, bytesLoaded) {
	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		var progress = new FileProgress(file,  this.customSettings.upload_target);
		progress.setProgress(percent);
		if (percent === 100) {
			progress.setStatus("正在生成预览图...");
			progress.toggleCancel(false, this);
		} else {
			progress.setStatus("上传中，请等待...");
			progress.toggleCancel(true, this);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function water_uploadSuccess(file, serverData) {
	try {
		if (serverData)
		{
			var obj = eval('('+serverData+')');
			if($('#img_preview').length)
			{
				$('#img_preview').attr('src',obj[0].url);
			}
			$('#water_filename').val(obj[0].filename);
		}

	} catch (ex) {
		this.debug(ex);
	}
}

function water_uploadComplete(file) {
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

function water_uploadError(file, errorCode, message) {
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


function addImage(src) {
	var newImg = document.createElement("img");
	document.getElementById("thumbnails").innerHTML = '';
	document.getElementById("thumbnails").appendChild(newImg);
	if (newImg.filters) {
		try {
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} else {
		newImg.style.opacity = 0;
	}

	newImg.onload = function () {
		fadeIn(newImg, 0);
	};
	newImg.src = src+'?'+Math.random();
}

function  addImage_face(src)
{
	var newImg = $('#pic_face').get(0);
	if (newImg.filters) 
	{
		try 
		{
			newImg.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 0;
		} 
		catch(e) 
		{
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			newImg.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + 0 + ')';
		}
	} 
	else 
	{
		newImg.style.opacity = 0;
	}

	newImg.onload = function () 
	{
		fadeIn(newImg, 0);
	};
	
   var src = src+'?'+Math.random(); 
   $('#pic_face').attr('src',src);
   $('#img_src_cpu').val(src);

}

function fadeIn(element, opacity) {
	var reduceOpacityBy = 5;
	var rate = 30;	// 15 fps


	if (opacity < 100) {
		opacity += reduceOpacityBy;
		if (opacity > 100) {
			opacity = 100;
		}

		if (element.filters) {
			try {
				element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
			}
		} else {
			element.style.opacity = opacity / 100;
		}
	}

	if (opacity < 100) {
		setTimeout(function () {
			fadeIn(element, opacity);
		}, rate);
	}
}



//水印上传
function hg_water_upload()
{
	var vod_swfu_pic;
	var url = "run.php?mid="+gMid+"&a=water_upload&admin_id="+gAdmin.admin_id+"&admin_pass="+gAdmin.admin_pass;
	vod_swfu_pic = new SWFUpload({
		upload_url: url,
		post_params: {"access_token": gToken},
		file_size_limit : "5 MB",
		file_types : "*.jpg;*.jpeg;*.png;*.gif;",
		file_types_description : "预览图片",
		file_upload_limit : "0",
		file_post_name : 'Filedata',
		button_placeholder_id : "waterplace",
		button_width: 94,
		button_height: 24,
		button_text : "<span class='white'>选择水印图片</span>",
		button_text_style : ".white{cursor: pointer;text-align:center;color:#FFFFFF;font-family:sans-serif;font-size:12px;font-weight:bold;}",
		button_image_url : RESOURCE_URL + 'select_upload.png',
		button_text_top_padding: 2,
		button_text_left_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_action:SWFUpload.BUTTON_ACTION.SELECT_FILE,
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainer"
		},
		
		/*事件句柄*/
		file_queue_error_handler     : water_fileQueueError,
		file_dialog_complete_handler : water_fileDialogComplete,
		upload_progress_handler      : water_uploadProgress,
		upload_error_handler         : water_uploadError,
		upload_success_handler       : water_uploadSuccess,
		
		debug: false/*调试模式*/
	});
}

