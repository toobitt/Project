<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>新媒体视频上传与选择组件</title>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-min.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="swfupload.js"></script>
<script type="text/javascript" src="js/swfupload.queue.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/hg_datepicker.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
<link href="css/jquery-ui-min.css" type="text/css" rel="stylesheet" />
<link href="css/reset.css" type="text/css" rel="stylesheet" />
<link href="css/main.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "Flash/swfupload.swf",
				upload_url: "index.php",
				file_size_limit : "<?php echo UPLOAD_SIZE_LIMIT;?> MB",
				file_types : "*.*",
				file_types_description : "视频文件",
				file_upload_limit : 100,
				file_queue_limit : 0,
				file_post_name  : 'videofile',
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "",
				button_width: 220,
				button_height: 50,
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '',
				button_text_style: "",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
	     var cb = "<?php echo CALLBACK;?>";
	     var settings = '<?php echo json_encode($settings);?>';
	</script>
</head>
<body>
<div class="main">
	<div class="head"></div>
	<div id="content" class="content-area">
		<div class="upload-area">
			<div id="spanButtonPlaceHolder" class="flash-btn"></div>
			<div class="upload-mask">上传视频</div>
		</div>
		<div class="fieldset flash" id="fsUploadProgress">
			<!--  <span class="legend">上传队列</span>
			<span id="divStatus">0 个文件已上传</span>-->
		</div>
		<!--  <div class="upload-right">
			<div class="upload-control">
				<input id="btnStartUpload" type="button" value="开始上传" onclick="swfu.startUpload();" disabled="disabled"/>
				<input id="btnCancel" type="button" value="取消上传" onclick="swfu.cancelQueue();" disabled="disabled" />
			</div>
		</div>-->
		
	</div>
	<div class="video-area">
		<div class="search-area">
			<form id="search-form" action="v.php" method="get" enctype="multipart/form-data" target="videoFrame">
						
					<!--输出视频分类开始-->
					<div class="sort-area">
						<?php
							if($vsort_data)
							{	
								$vsort_data[0] = array(
									'id' => 0,
									'name' => '全部视频'
								);
						?>
							<span class="current-sort">全部视频</span>
							<span class="arrow"></span>
							<ul class="sort-list">
								<?php
									foreach($vsort_data as $key=>$sval)
									{
										if($sval['id'] == $_HOGE['input']['vod_sort_id'])
										{
											$selected = "selected = 'selected'";
										}
										else
										{
											$selected  = '';
										}
								?>
									<li _id="<?php echo $sval['id'];?>" class="<?php echo $selected;?>"> <?php echo $sval['name'];?></li>
								<?php
									}
								?>
							</ul>
							<input type="hidden" name="vod_sort_id" value="0" />
						<?php
							}
						?>
					</div>
					<!--输出视频分类结束-->
					<input class="time-picker" type="text" value="<?php echo urldecode($_HOGE['input']['start_time']);?>" name="start_time" placeholder="开始时间" /> 
					<input type="text" class="time-picker" value="<?php echo urldecode($_HOGE['input']['end_time']);?>" name="end_time"  placeholder="结束时间"/>
					<input type="text" name="title" value="<?php echo urldecode($_HOGE['input']['title']);?>"  placeholder="关键字" />
					<input type="submit" value="搜索"  class="search-btn"/>
				</form>
		</div>
		<iframe src='v.php?settings=<?php echo json_encode($settings)?>' id="videoFrame" name="videoFrame" frameborder="no" scrolling="no" hidefocus="hidefocus" allowtransparency="true" ></iframe>
	</div>
</div>
</body>
<script>
$(function(){
	(function( $ ){					//placeholder
		function Placeholder( $dom ){  
			var self = this;
			this.$dom = $dom;
			this.init();
			this.$dom.on( {
				'focus' : function(){
					self.focusPlaceholder();
				},
				'blur' : function(){
					self.blurPlaceholder();
				}
			} );
		};
		$.extend( Placeholder.prototype, {
			init : function(){
				var el = this.$dom,
					info = this.getValue();
				if( !info['value'] && info['placeholder'] ){
					this.setValue( info['placeholder'] );
				}
			},
			focusPlaceholder : function(){
				var el = this.$dom,
					info = this.getValue();
				if( info['value'] == info['placeholder'] ){
					el.val( '' ).removeAttr('style');
				}
			},
			blurPlaceholder  : function(){
				var el = this.$dom,
				info = this.getValue();
				if( !info['value'] ){
					this.setValue( info['placeholder'] );
				}
			},
			getValue : function(){
				var el = this.$dom,
					info = {};
				info.value = $.trim( el.val() );
				info.placeholder = el.attr( 'placeholder' );
				return info;
			},
			setValue : function( placeholder ){
				var el = this.$dom;
				el.val( placeholder ).css( 'color','#cacaca' );
			}
		} );
		$.fn.hg_placeholder = function(){
			return this.each( function(){
				var placeholder = $(this).data( 'placeholder' );
				if( placeholder ){
					return;
				}else{
					$(this).data( 'placeholder', new Placeholder( $(this) ) );
				}
			} );
		};
	})( $ );

	var inputs = $( '.search-area' ).find( 'input[type="text"]' );
	inputs.hg_placeholder();
	$('.time-picker').hg_datepicker();
	$('#search-form').submit( function(){
		$(this).data( 'search', true  );
		inputs.each( function(){
			$(this).data('placeholder').focusPlaceholder();
		} );
	} );

	$('.sort-area').hover( function(){
		$(this).find('ul').show();
	}, function(){
		$(this).find('ul').hide();
	} );

	$('.sort-list').on('click', 'li', function(){
		var id = parseInt( $(this).attr( '_id' ) ),
			box = $(this).closest( '.sort-area'),
			hidden = $('input[name="vod_sort_id"]');
		box.find( '.current-sort' ).text( $(this).text() );
		box.find( 'ul' ).hide();
		!id ? hidden.val( '' ) : hidden.val( id );
	});
});
</script>
</html>
