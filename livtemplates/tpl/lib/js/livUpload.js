var VedioInfoCollection = (function() {
	var records = {};
	var module = {
		add: function(fileID, info) {
			info.fileID = fileID;
			records[fileID] = info;
			var App = $('#formwin')[0].contentWindow.App;
			if (App && App.trigger) {
				//通知编辑页有新的视频上传完成
				App.trigger('newVodCome', info);
			}
		},
		getAll: function () {
			return records;
		},
		reset: function(fileIDs) {
			//删除已经编辑的
			if (fileIDs && fileIDs.forEach) {
				fileIDs.forEach(function(fileID) {
					delete records[fileID];
					livUploadView.remove(fileID);
				});
			}
			//如果nodeFrame是vod，刷新Ta到第一页
			var ifr = livUpload.findNodeFrame();
			if ( ifr && (ifr.gMid == $.globalData.get('vod_mid')) ) {
				var first = ifr.$('#pagelink_1');
				if ( !first.length || first.hasClass('page_cur') ) {
					ifr.location.reload(true);
				} else {
					if( first.length && !first.hasClass('page_cur') ){
						ifr.location.href = first.find('a').attr('href');
					}
				}
			}
		},
		startEdit: function() {
			var href = 'run.php?mid=' + $.globalData.get('vod_mid') + '&a=batch_edit';
			$('#formwin').attr('src', href);
		}
	};
	return module;
})();

//livUpload_fileDialogStart = function(){
//	hg_show_upload_window();
//};

//入队
livUpload_fileQueued = function(file) {
	$('.set-upload').removeClass('disable')
	var stats = livUpload.getStats();
	if ( (stats.files_queued + stats.in_progress + stats.successful_uploads) > 0 ) {

		hg_show_upload_window();
	}
	var progress = new FileProgress(file, this.customSettings.progressTarget);
	progress.setStatus(hg_format_num(file.size));
	progress.toggleCancel(true, this);
};
//全部完成
livUpload_allComplete = function(num) {
	var i = 5,
		tip_box = $('.close-tip');
	close_interval = setInterval( function(){
		if( i == 0 ){
			livUploadView.empty();
			clearInterval( close_interval );
			return;
		}else{
			tip_box.text( i + '秒后关闭视频上传弹窗' );
		}
		i--;
	}, 1000 );

};
//有文件上传成功
livUpload_uploadSuccess = function(file, serverData) {
	var progress = new FileProgress(file, this.customSettings.progressTarget);
	var data;
	try {
		data = JSON.parse( serverData );
		data = data['return'] ? data : data[0];
	} catch(e) {
		data = { 'return': 'error' };
	}
	if (data && data['return'] == 'success' ) {
		var box = $('#livUpload_div');
		progress.setComplete( true );
		progress.setStatus( '上传完成' );
		progress.toggleCancel(false);
		box.find( '.set-upload' ).hide().next().show();
		data.title = file.name;
		VedioInfoCollection.add(file.id, data);
	} else {
		progress.setError();
		progress.setStatus( JSON.parse( serverData ).ErrorCode );
		progress.toggleCancel(false);
	}
};
//有文件上传结束
livUpload_uploadComplete = function() {
	
};
var livUploadView = (function () {
	$(function() {
		$('#livUpload_div').on('click', '.set-editor', function() {
				if( $(this).hasClass('disable') ) return;
				VedioInfoCollection.startEdit();
				$(this).addClass('disable')
				clearInterval( close_interval );
		});
		$('#livUpload_windows .close').click(function() {
			livUploadView.empty();
			$('#livUpload_windows').hide();
			$('#livUpload_div').find('.set-upload').show().next().hide();
		});
		$('#livUpload_text').toggle(function() {
			var id = $('#livUpload_div');
			id.animate({'height':'0px'},function(){
				id.hide();
				$('#livUpload_small_windows').animate({'width':'200px'},function(){
					$('.livUpload_text').removeClass('b');
				});
			});
		}, function() {
			var id = $('#livUpload_div');
			$('#livUpload_small_windows').animate({'width':'278px'},function(){
				id.show();
				id.animate({'height':'auto'});
				$('.livUpload_text').addClass('b');
				
			});
		});
	});
	return {
		empty: function() {
			var box = $('#livUpload_windows');
			box.find('#livUploadProgress').empty();
			box.hide();
			box.find('.close-tip').text('');
			box.find('.set-editor').removeClass('disable').hide();
			box.find('.set-upload').show().removeClass('disable');
		},
		remove: function(fileID) {
			$('#' + fileID).remove();	
		}
	};
})();
function hg_show_upload_window() {
	$('#livUpload_windows').show();
	$('#livUpload_div').show().css('height', 'auto');
	$('#livUpload_small_windows').css('width', 278 	);
}
function hg_open_widows(){
	var id = $('#livUpload_div');
	if(id.css('display')=='none')
	{
		
		$('#livUpload_small_windows').animate({'width':'278px'},function(){
				id.show();
				id.animate({'height':'auto'});
				$('.livUpload_text').addClass('b');
				
			});
		
	}
	else{
		id.animate({'height':'0px'},function(){
				id.hide();
				$('#livUpload_small_windows').animate({'width':'200px'},function(){
					$('.livUpload_text').removeClass('b');
				});
			});
	}
	
}

//选择文件结束
function livUpload_fileDialogComplete(numFilesSelected, numFilesQueued) {
	hg_show_upload_window();
	window.numFilesQueued = numFilesQueued;
	/*if (numFilesQueued) {
		livUpload.start();
	}*/
}

//文件上传完成
function livUpload_allComplete(){
	
}

(function(exports) {
	var livUpload = exports.livUpload = {
		flashWrapper: 'flash_wrap',
		createFlash: function(param, callback) {
			var url = '';
			var button_img = '';
			var button_width = '';
			var button_height = '';
			var button_mode = '';
			var queue_limit = '';
			var file_size_limit = '';
			var file_types = '';
			var file_name = '';
			var post_data = '';
			var description = '';
			
			url = param.upload_url?param.upload_url:'upload.php';
			button_img = param.button_img?param.button_img:'select_upload.png';
			button_width = param.button_width?param.button_width:1;
			button_height = param.button_height?param.button_height:1;
			button_mode = param.button_mode?SWFUpload.BUTTON_ACTION.SELECT_FILE:SWFUpload.BUTTON_ACTION.SELECT_FILES;
		   
			queue_limit = param.queue_limit?param.queue_limit:20;
			file_size_limit = param.file_size_limit?param.file_size_limit:'2 GB';
			file_types = param.file_types?param.file_types : "*.*";
			file_name = param.file_name?param.file_name : 'videofile';
			description = param.description?param.description : "Files Types";

			this.SWF = new SWFUpload({
				upload_url: url,
				button_window_mode : SWFUpload.WINDOW_MODE.OPAQUE, 
				prevent_swf_caching:true,
				file_size_limit : file_size_limit,	
				file_types : file_types,
				file_types_description : description,
				file_upload_limit : "0",
				file_queue_limit : queue_limit,
				file_post_name : file_name,
				swfupload_loaded_handler	 : callback,
				file_dialog_start_handler    : livUpload_fileDialogStart,
				file_queued_handler          : livUpload_fileQueued,
				file_queue_error_handler     : livUpload_fileQueueError,
				file_dialog_complete_handler : livUpload_fileDialogComplete,
				upload_start_handler         : livUpload_uploadStart,
				upload_progress_handler      : livUpload_uploadProgress,
				upload_error_handler         : livUpload_uploadError,
				upload_success_handler       : livUpload_uploadSuccess,
				upload_complete_handler      : livUpload_uploadComplete,
				queue_complete_handler		 : livUpload_allComplete,
				
				button_text : "<span class='white'>选择视频文件</span>",
				button_text_style : ".white{cursor: pointer;text-align:center;color:#FFFFFF;font-family:sans-serif;font-size:12px;font-weight:bold;}",
				button_image_url : RESOURCE_URL + button_img,
				button_placeholder_id : 'UploadPlace',
				button_width: button_width,
				button_height:button_height,
				button_action:button_mode,
				flash_url : RESOURCE_URL+"swfupload/swfupload.swf",
				post_params: {
					admin_name: param.admin_name,
					admin_id: param.admin_id,
					access_token: param.token
				},
			
				custom_settings : {
					progressTarget : "livUploadProgress",
					cancelButtonId : "Uploadcancel",
					startButtonId  : "Uploadstart",
					statusText     : "uploadStatus"
				},
				
				debug: DEBUG_MODE
			});
		},
		isInit: function() {
			return !!this.SWF;
		},
		rebuild: function(param) {
			if ( !this.isInit() ) return;
			livUpload.SWF.setUploadURL(param.upload_url);
			livUpload.SWF.setFileTypes(param.file_types, param.description);
			livUpload.SWF.setButtonTextPadding('','2');
			var buttonAction = livUpload.uploadMode?SWFUpload.BUTTON_ACTION.SELECT_FILE:SWFUpload.BUTTON_ACTION.SELECT_FILES;
			livUpload.SWF.setButtonAction(buttonAction);
			
			var param_obj = {};
			if(param.token)
			{
				param_obj['access_token'] = param.token;
			}
			
			if(param.mid)
			{
				param_obj['module_id'] = param.mid;
			}
			
			livUpload.SWF.setPostParams(param_obj);
			
			if(livUpload.uploadMode)
			{
				livUpload.singleFlagId = param.flagId;
			}
			else
			{
				livUpload.moreFlagId = param.flagId;
			}
			
			livUpload.upload_type = param.upload_type;
			livUpload.currentFlagId = param.flagId;
			livUpload.OpenPosition();
			livUpload.SWF.setButtonDimensions(94,24);
		},
		position: function(offset) {
			$('#' + this.flashWrapper).css({ left: offset.left, top: offset.top, 'z-index': '10000' });
			return this;
		},
		dimensions: function(w, h) {
			$('#' + this.flashWrapper).css({ width: w, height: h });
			this.SWF.setButtonDimensions(w, h);
			return this;
		},
		wraperCss: function() {
			$.fn.css.apply($('#' + this.flashWrapper), arguments);
			return this;
		},
		initPosition: function() {
			this.SWF.setButtonDimensions(1, 1);
			$('#' + this.flashWrapper).css({ 'left':'0px', 'top':'0px', 'position':'absolute',  'z-index': '-1' });
		},
		getStats: function() {
			return this.SWF.getStats();
		},
		start: function() {
			this.SWF.startUpload();
		},
		findNodeFrame: function() {
			var iframe = $('#mainwin')[0].contentWindow.$('#nodeFrame');
			if ( iframe.size() ) {
				return iframe[0].contentWindow;
			} else {
				return null;
			}
		}
	};
	
})(window);



