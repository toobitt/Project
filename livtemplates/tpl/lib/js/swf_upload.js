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
			var str = '<img src="' + obj[0].host + obj[0].dir + '100x75/' + obj[0].filepath + obj[0].filename + '" alt="' + obj[0].name + '" width="100" height="75" />';
			//var string = obj.toJSONString();
			str += "<input type='hidden' name='log' value = '"+serverData+"' />";
			$("#log_box").html(str);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccessTemplateStyle(file, serverData) {
	try {
		//var progress = new FileProgress(file, this.customSettings.progressTarget);
		//progress.setComplete();
		if (serverData.replace(/\s+/g,""))
		{
			var obj = eval('('+serverData+')');
			var str = '<div id="material_' + obj[0]['id'] +'" class="material_log"><img src="' + obj[0].host + obj[0].dir + '100x75/' + obj[0].filepath + obj[0].filename + '" alt="' + obj[0].name + '" width="100" height="75"><span class="material_del">X</span>';
			str += "<input type='hidden' name='log[]' value = '"+serverData+"' /></div>";
			str += "<input type='hidden' name='history[]' value='"+serverData+"' />";
			add_close_hover( str = $(str) );
			$("#log_box").append(str);
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


