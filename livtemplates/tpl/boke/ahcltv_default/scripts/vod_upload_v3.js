$(document).ready(function() {

	swfu = new SWFUpload( {
		upload_url : 'upload_file_deal.php',
		flash_url : 'res/swfupload.swf',

		file_size_limit : "1999MB",

		button_placeholder_id : 'upload_button',
		button_width : 75,
		button_height : 20,
		//button_text : '上传',
		//button_text_left_padding : 24,
		//button_text_top_padding : 4,
		button_image_url : './res/img/Upload.png',

		swfupload_loaded_handler : swfupload_loaded_function,
		file_dialog_start_handler : file_dialog_start_function,
		file_queued_handler : file_queued_function,
		file_queue_error_handler : file_queue_error_function,
		file_dialog_complete_handler : file_dialog_complete_function,
		upload_start_handler : upload_start_function,
		upload_progress_handler : upload_progress_function,
		upload_error_handler : upload_error_function,
		upload_success_handler : upload_success_function,
		upload_complete_handler : upload_complete_function,

		debug: true,

		custom_settings : {
			queue_id : '#upload_queue',
			clear_id : '#upload_clear'
		}
	});

	$(window).bind('unload', function() {
		swfu.destroy();
	});

	function swfupload_loaded_function() {

			alert('swfupload_loaded');
			
	}

	function file_dialog_start_function() {
	}

	function file_queued_function(file_obj) {
		var update_file_html = "<ul class='upload_item_first upload_normal' id='" + file_obj.id + "'>" +
		                       "<li class='upload_name'>" + trunk_name(file_obj.name) + "</li>" +
		                       "<li class='upload_size'>" + get_human_file_size(file_obj.size) + "</li>" +
		                       "<li class='upload_used_time'>" + "0secs" + "</li>" +
		                       "<li class='upload_status'>" + "queued" + "</li>" +
		                       "<li class='cancel_button'><img title='cancel' class='hide' src='" +"./res/img/close-icon.png'></li>" +
				               "</ul>";

	    $('#upload_body_inner').append(update_file_html);
	    $('#' + file_obj.id).bind('mouseover', function(){
	    	$(this).find('.cancel_button img').removeClass('hide');
	    });
	    $('#' + file_obj.id).bind('mouseleave', function(){
	    	$(this).find('.cancel_button img').addClass('hide');
	    });
	    $('#' + file_obj.id + " .cancel_button img").bind('click', function(){
			var parent = $(this).parent().parent();
			swfu.cancelUpload(parent.attr('id'), false);

			parent.remove();
	    });
	}

	function file_queue_error_function(file_obj, error_code, message) {
		var error_message = '你选择的文件可能因为太大而无法加入上传队列，最大上传的文件大小约为2GB';
		$('#queue_error').html(error_message);
		$('#queue_error').show();
		$('#queue_error').delay(5000).fadeOut(300);
	}

	function file_dialog_complete_function() {
		this.startUpload();
	}

	function upload_start_function(file_obj) {

		alert('start');
		$('#' + file_obj.id).data('start_time', new Date());
		$('#' + file_obj.id).find('.upload_status').html('开始上传');
		$('#' + file_obj.id).data('error', undefined);
	}

	function upload_progress_function(file_obj, complete_bytes, total_bytes) {
		if ($('#' + file_obj.id).data('checked') == false) {
			$.post(site_name + 'admin/vod/vod_depot/is_file_existed', {
				file_name : file_obj.name
			}, function(data) {
				$('#' + file_obj.id).data('checked', true);
				if (data === false) {
					$('#' + file_obj.id).data('start_time', new Date());
					$('#' + file_obj.id).find('.state_value').html('开始上传');
					$('#' + file_obj.id).data('error', undefined);
				} else {
					$('#' + file_obj.id).data('error', "文件已经存在");
					swfu.cancelUpload(file_obj.id, true);
				}
			}, 'json');
		}
		onbeforeunload=RunOnBeforeUnload;
		var now = new Date();
		var rate = complete_bytes * 1000
				/ (now - $('#' + file_obj.id).data('start_time'));
		var process_width = complete_bytes / total_bytes * 150;
		var progress_value = Math.round(complete_bytes / total_bytes * 100) + "%";

		$('#' + file_obj.id).find('.upload_status').html('正在上传');
		$('#upload_progress').html(progress_value);
		$("#upload_rate").html(get_human_file_size(rate) + '/s');
		$("#" + file_obj.id).find('.upload_used_time').html(
				get_human_time(now - $('#' + file_obj.id).data('start_time')));
	}

	function upload_error_function(file_obj, error_code, message) {
		var error_message = '未知错误';
		// $('#' + file_obj.id).data('error') keeps user defined error
		if ($('#' + file_obj.id).data('error')) {
			error_message = $('#' + file_obj.id).data('error');
		} else {
			switch (error_code) {
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
				error_message = '上传失败, 服务器没返回确认信息';
				break;
			case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
				error_message = 'upload_url设置不正确';
				break;
			case SWFUpload.UPLOAD_ERROR.IO_ERROR:
				error_message = '传输错误, 服务器关闭了连接';
				break;
			case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
				error_message = '违反安全限制';
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:

				error_message = '文件大小超过了规定的大小';
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
				error_message = '尝试上传失败';
				break;
			case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
				error_message = '找不到file id';
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
				error_message = '无法上传';
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				error_message = '传输取消';
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				error_message = '传输停止';
				break;
			}
		}

		$('#' + file_obj.id).find('.upload_status').css('color', 'green');
		$('#' + file_obj.id).find('.upload_status').html(error_message);
	}

	function upload_success_function(file_obj, server_data, received_response) {
					//alert(server_data);
		onbeforeunload=RunGo;
		if (server_data == 'true') {
			is_any_upload_success = true;
			$('#' + file_obj.id).find('.upload_status').html('上传成功');
                        $('#' + file_obj.id).addClass('upload_success');
                        setTimeout(function() {
                                       $('#' + file_obj.id).removeClass('upload_success');
                                   },
                                   1000);
			$('#upload_progress').html('100%');
		} else {
			$('#' + file_obj.id).data('error', server_data);
			upload_error_function(file_obj, 100, '');
		}
	}

	function upload_complete_function(file_obj) {
		//$('#' + file_obj.id).delay(10000).fadeOut(300, function() {
		//	$(this).remove();
		//});
		$('#upload_progress').html('0%');
		$("#upload_rate").html('0B/s');

		$('#' + file_obj.id).addClass('update_complete');
		this.startUpload();
	}

	// regist for cancel button
	function cancel_upload() {
	}

	// private help function
	function get_human_file_size(byte_size) {
		var units = [ 'B', 'KB', 'MB', 'GB', 'TB' ];
		var index = 0;
		while (byte_size > 1024) {
			byte_size = byte_size / 1024;
			index++;
		}

		// make sure the number will not be too long
		var size_str = '' + byte_size;
		if (!(size_str.indexOf('.') == -1 || (size_str.length - 1
				- size_str.indexOf('.') < 3))) {
			size_str = size_str.substr(0, size_str.indexOf('.') + 4);
		}
		return size_str + units[index];
	}

	function get_human_time(escaped_time) {
		var units = [ '秒', '分', '时' ];
		var times = [ 0, 0, 0 ];

		escaped_time = escaped_time / 1000;

		var index = 0;
		while (escaped_time > 0) {
			times[index] = Math.floor(escaped_time % 60);
			escaped_time = Math.floor(escaped_time / 60);
			index++;
		}

		var ret = "";
		for ( var i = units.length - 1; i >= 0; i--) {
			if (times[i] != 0) {
				ret = ret + times[i] + units[i];
			}
		}

		if (ret == "") {
			ret = "1秒";
		}

		return ret;
	}

	function trunk_name(name)
	{
		if (name.length > 50)
		{
			return name.substring(0, 47) + '...';
		}
		else
		{
			return name;
		}
	}
});
