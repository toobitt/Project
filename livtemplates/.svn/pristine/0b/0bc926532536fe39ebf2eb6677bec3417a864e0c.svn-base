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


function waterfileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function wateruploadProgress(file, bytesLoaded) {

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

function wateruploadSuccess(file, serverData) {
	try{
		if (serverData.replace(/\s+/g,""))
		{
			var obj = eval('('+serverData+')');
			var str='<img src="'+ obj[0].url +'" alt="缩略图" style="width:100px;height:75px;"/>'+
				'<input type="hidden" name="tmpurl" value="'+ obj[0].tmpurl +'" />';
			$("#slt").html(str);
		}
		/**
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("上传成功");
		*/
	}catch (ex) {
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

function wateruploadError(file, errorCode, message) {
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

function uploadStart(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("正在上传...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	return true;
}

function hg_swf_affix()
{
	var vod_swfu_water;
	var url = "./run.php?mid=" + gMid + "&a=upload_affix&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass;
	vod_swfu_water = new SWFUpload({
		upload_url: url,
		post_params: {"access_token": gToken},
		file_size_limit : "50 MB",
		file_types :  "*.jpg;*.jpeg;*.png;*.gif;",
		file_types_description : "选择图片",
		file_upload_limit : "0",

		file_queue_error_handler     : fileQueueError,
		file_dialog_complete_handler : waterfileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler      : wateruploadProgress,
		upload_error_handler         : wateruploadError,
		upload_success_handler       : wateruploadSuccess,

		button_image_url : RESOURCE_URL+"news_from_cpu.png",
		button_placeholder_id : 'image_affix',
		button_width: 100,
		button_height: 75,
		button_text : '',
		button_text_style : '.button {font-family: Helvetica, Arial, sans-serif; font-size: 12pt;}',
		button_text_top_padding: 0,
		button_text_left_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_action:SWFUpload.BUTTON_ACTION.SELECT_FILES,
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",
		custom_settings : {
			upload_target : "divFileProgressContainer",
			progressTarget:"slt"
		},
		debug: false
	});
}
