$(function() {
	var els = $('input[name="thread_title"]');
	var _v = els.val();
	els.focus(function() {
		if ($(this).val() == _v)
		{
			$(this).val('');
		}
	}).blur(function() {
		if ($(this).val() == '')
		{
			$(this).val(_v);
		}
	});
});
var swfu;
window.onload = function() {
	var settings = {
		flash_url : flash_url,
		upload_url : "/thread.php",
		post_params : {"a" : "material", "group_id" : gid, "action_id" : aid, "access_token" : access_token},
		file_size_limit : "2 MB",
		file_types : "*.jpg;*.jpeg,*.png,*.gif",
		file_types_description : "Image Files",
		file_upload_limit : 20,  //配置上传个数
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "fsUploadProgress",
			cancelButtonId : "btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url : button_image_url,
		button_width : "100",
		button_height : "24",
		button_placeholder_id : "spanButtonPlaceHolder",
		button_text : '',
		//button_text : '<span class="theFont">浏览</span>',
		//button_text_style : ".theFont { font-size: 14; }",
		//button_text_left_padding : 12,
		//button_text_top_padding : 3,
		
		file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		queue_complete_handler : queueComplete
	};
	
	swfu = new SWFUpload(settings);
};
