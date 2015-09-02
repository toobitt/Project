define(function(require, exports, module) {
	var $ = require('$');
	var _ = require( 'underscore');
	var Backbone = require( 'Backbone' );
	require( 'uploadify/uploadify' );
	require( './placeholder' );
	
	$(function () {
		$('#url').placeholder({useNative: false});
		var vodTitle = $('#vodTitle');
		var vodPic = vodTitle.next();
		vodPic.on( 'click', '.vod-shwo-close', function () {
			vodPic.hide();
			vodTitle.show().find('input').val('');
		});
		vodTitle.find('input').blur(lookUrl).trigger('blur');
		function lookUrl (e) {
			var url = $.trim($(e.target).val());
			if ( !url ) {
				return;
			}
			if ( url.substr(0, 7) != 'http://' ) {
				url = 'http://' + url;
				$(e.target).val( url );
			}
			
			vodTitle.find('.error').text('解析中,请等待...').css('color', 'black').show();
			
			$.get('run.php?mid=305&a=video_parse&ajax=1', {url: url}, $.proxy(function (data) {
				if (!data || data == 'false' ) {
					vodTitle.find('.error').text('不支持的视频地址').css('color', 'red').show();
					return;
				}
				vodTitle.find('.error').hide();
				addVod(data);
			}), 'json');
		}
		function addVod (data) {
			vodPic.show().find('img').attr('src', data[0].img);
			vodTitle.hide();
		}
	});
	return Backbone.View.extend({
		events: {
			'click .upload-pic-close': 'removePic'
		},
		initialize: function (options) {
			//编辑器
			this.ueditor = new UE.ui.Editor({
				focus: false,
				minFrameHeight: '170',
				//initialContent: '<p style="font-size:12px;color:#aaa;">对本行动的过程和成果进行详尽地描述，如参与人数、行动计划的执行情况、参与者的感受、行动取得的成绩、媒体报道情况、经验及不足等。</p>',
				toolbars: options.slideBar || [["Bold","Underline","StrikeThrough","InsertOrderedList","InsertUnorderedList","BlockQuote","Link","Unlink","Source"]]
			});
			this.ueditor.render('textEditor');
			//图片上传
			this.$('#file-upload').uploadify({
				//auto: false,
				formData: {
					img_size: '100x100'
				},
				buttonText: '+ 添加图片',
				height: '30',
				swf: JS_PATH + 'modules/uploadify/uploadify.swf',
				uploader: options.url || 'action.php?a=uploadReview',
				queueID: 'pic-list',
				removeCompleted: false,
				fileTypeExts: '*.jpg; *.jpeg; *.gif; *.png; *.bmp',
				fileTypeDesc: '请选择图片',
				multi: true,
				fileSizeLimit: '2MB',
				onUploadSuccess: $.proxy(this.createPicOnBack, this)
			});
			//更新模式，初始化图片数据
			this.picList = this.$('#pic-list');
			if ( this.options.data ) {
				_.each(this.options.data, _.bind(function (n) {
					var el;
					this.picList.append( el = $(this.template_pic(n)) );
					el.find('textarea').placeholder({useNative: false});
				}, this));
			}
		},
		createPicOnBack: function (file, data, response) {
			try {
				var el;
				data = $.parseJSON(data)[0];
				$('#SWFUpload_' + (this.options.index || '0')  + '_' + file.index).remove();
				data.info = '';
				el = $(this.template_pic(data));
				this.picList.append(
					el
				);
				el.find('textarea').placeholder({useNative: false});
			} catch(e) {
			}
		},
		removePic: function (e) {
			$(e.target).parent().slideUp(function () { $(this).remove() } );
		},
		template_pic: _.template( $('#template_pic').html() )
	})
})