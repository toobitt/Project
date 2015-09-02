$(function(){
	(function($){
		//实例化flash
    	this.SWF = new SWFUpload({
			upload_url: tv_play_params.upload_url,
			button_window_mode : SWFUpload.WINDOW_MODE.OPAQUE, 
			prevent_swf_caching:true,
			file_size_limit : '2 GB',	
			file_types : tv_play_params.file_types?tv_play_params.file_types:'*.*',
			file_types_description : '电视剧剧集上传',
			file_upload_limit : "0",
			file_queue_limit : 20,
			file_post_name : 'videofile',
			file_queue_error_handler : fileQueueError,
    		file_dialog_complete_handler : fileDialogComplete,
    		upload_progress_handler : uploadProgress,
    		upload_error_handler : uploadError,
    		upload_success_handler : uploadSuccess,
    		upload_start_handler   : uploadStart,
			
			button_text : "",
			button_text_style : ".white{cursor: pointer;text-align:center;color:#FFFFFF;font-family:sans-serif;font-size:12px;font-weight:bold;}",
			button_image_url : RESOURCE_URL + 'upload_tv.png',
			button_placeholder_id : 'tvPlayUploadPlace',
			button_width: 215,
			button_height:150,
			button_action:SWFUpload.BUTTON_ACTION.SELECT_FILES,
			flash_url : RESOURCE_URL+"swfupload/swfupload.swf",
			post_params: {
				admin_name: tv_play_params.admin_name,
				admin_id: tv_play_params.admin_id,
				access_token: tv_play_params.token,
				callback_url:tv_play_params.callback_url,
				after_callback_url:tv_play_params.callback_url,
				callback_data:tv_play_params.callback_data,
				vod_sort_id:tv_play_params.vod_sort_id,
				app_uniqueid:tv_play_params.app_uniqueid,
				mod_uniqueid:tv_play_params.mod_uniqueid
			},
			debug: false
		});
	})($);
})

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

//上传之前的事件
function uploadStart(file)
{
	var server_id = $('#transcode_server').val() || 0;
	var title = $('.m2o-m-title').val() || '';
	var len = $('.tele-total').find('.teleplay').length;
	this.addFileParam(file.id,'server_id',server_id);
	this.addFileParam(file.id,'title', title + '_第' + len + '集');
	return true;
}

function uploadProgress(file, bytesLoaded) {

	try {
	   	$(".loading").show();
	   	$(".prevent-do").show();
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

function uploadSuccess(file, serverData) {
	try {
		if (serverData)
		{
			var data = $.parseJSON( serverData );
			if( data['ErrorCode'] ){
				var error = data['ErrorText'];
				jAlert( error, '上传提醒' );
			}else{
				var data = data[0];
				var tv_data = data['callback_return'];
				$('.m2o-form').tv_form( 'uploadVodAfter' , tv_data );
			}
			$(".loading").hide();
		   	$(".prevent-do").hide();			
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

/* ******************************************
 *	FileProgress Object
 *	Control object for displaying file info
 * ****************************************** */

function FileProgress(file, targetID) {
	this.fileProgressID = "divFileProgress";

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "adv_progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("div");
		progressText.className = "adv_progressName";
		progressText.appendChild(document.createTextNode(file.name));

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";

		var progressStatus = document.createElement("div");
		progressStatus.className = "adv_progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
		fadeIn(this.fileProgressWrapper, 0);

	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}