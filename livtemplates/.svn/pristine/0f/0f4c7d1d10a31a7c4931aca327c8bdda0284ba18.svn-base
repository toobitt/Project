jQuery(function(){
	
    $('#seekhelp-pic a').lightBox({width:1000});
	
	$(".choose-area .choose").click(function(){
		var url = 'run.php?mid=' + gMid + '&a=show_account';
		getList("#add-img",url);
		$(".source-img-box").slideToggle(600);
    });
	
	function hg_account_callback(html)
	{
		alert(html);
	}
	// 选择求助对象
	$("#add-img").on('click','.snap-img',function(event){
		var self = $(event.currentTarget),
			imgSrc = self.find("img").attr("src"),
			dataId = self.data("id"),
			userName = self.find('.middle-img-wrap').find('span').text();
		
		$(".choose img").attr("src",imgSrc);
		$(".choose").find('.info').text(userName);
		$("input[name='account_id']").val(dataId);
		$(".source-img-box").hide(600);
	});
	// 显示列表
	$(".show-list").click(function(){
		var self = $(this),
			flag = self.data("name"),
			id = self.data("id");
		if (flag=='pl'){
			var url = 'run.php?mid=' + gMid + '&a=show_comment&status=1&cid='+id;
		}
		if (flag=='lm'){
			var url = 'run.php?mid=' + gMid + '&a=show_joint&cid='+id;
		}
		getList('.list',url, $(this));
		if($(".seekhelp-list").css("opacity") == 0){
			$(".seekhelp-list").addClass("list-show");
		}else if(flag !== $(".seekhelp-list").attr("_name")){
			$(".seekhelp-list").removeClass("list-show");
			setTimeout(function(){
				$(".seekhelp-list").addClass("list-show");
				},500);
		}else{
			$(".seekhelp-list").removeClass("list-show");
		}
		$(".seekhelp-list").attr("_name",flag);
		// 设置列表高度
		var descHeight = $(".help-desc").outerHeight(true) - $(".list-title").outerHeight();
		$(".list").css("max-height",descHeight);
		// 设置列表标题
		setTimeout(function(){
			$(".list-title p").text( self.find(".title").text() );
		},500);
	});
	// 获取列表内容
	var getList = function(box,url, target){
		var load = target ? $.globalLoad( target ) : $.noop;
		$.get(url,function(html){
			load();
			$(box).html(html);
		});
	}
	// 推荐答案
	$('.list').on('click','.recommend-icon',function(event){
		var self = $(event.currentTarget),
			parents = self.closest('.m2o-item');
		var listId = parents.attr("_id"),
			text = parents.find(".comment-content").text();
		$(".recommend-answer").val(text);
		$('input[name="comment_id"]').val( listId );
		parents.remove();
		setTimeout(function(){
			$(".seekhelp-list").removeClass("list-show");
		},200);
		
	});
	// 列表关闭按钮
	$(".list-title .close").click(function(){
		$(".seekhelp-list").removeClass("list-show");
	});
	// 删除推荐
	$(".rec-del").click(function(){
		$(".recommend-answer").attr("placeholder","推荐答案").val('');
		$('input[name="comment_id"]').val("");
	});
	
	$.widget('seek.seekform',{
		options : {
			back : '.option-iframe-back',
 			video_item : '.video-item',
			media_box : '.media-box',
			'vedio-back-close' : '.vedio-back-close',
			'media-box-show' : 'media-box-show',
			'vedio-tpl' : '#vedio-tpl',
			pic_list : '.pic-list',
			pic_button : '.pic-upload',
			pic_item : '.pic-item',
			pic_del : '.pic-del',
			pic_file : '#pic-file',
			'add-pic-tpl' : '#add-pic-tpl',
			
			'vod-list' : '.vod-list',
			'vod-audio-list': '.vod-audio-list',
			video_file : '#video-file',
			vod_button : '.video-upload',
			vod_item : '.vod-item',
			'add-vod-tpl' : '#add-vod-tpl',
			'add-vod-audio-tpl' : '#add-vod-audio-tpl',
			'prevent-do' : '.prevent-do',
			'del-vod' : '.vod-del',
			addFormPicTpl : $('#addform-pic-tpl'),
			addFormVodTpl : $('#addform-vod-tpl'),
		},
		_create : function(){
			this.id = this.element.data('id');
			this.options.url = "./run.php?mid=" + gMid + "&a=upload_img&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass + "&id=" + this.id,
			this.options.vodUrl = "run.php?mid="+ gMid + "&a=upload_video&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass + "&id=" + this.id;
		},
		_init : function(){
			this._on({
				'click .pic-upload' : '_upload',
				'click .pic_del' : '_delPic',
				'click .vod-del' : '_delVod',
				'click .video-item' : '_playVideo',
				'click .vedio-back-close' : '_closeBox',
				'click .video-upload' : '_uploadvod',
				'click .prevent-do' : '_preventInit',
				'click .trigger-btn' : '_triggerFile',
				'change .pic-file' : '_prevPic',
				'change .vod-file' : '_prevVod',
				'click .material-item .del' : '_delMaterial'
			});
			var op = this.options,
				_this = this;
			this._trigger('init',null,[this]);
			this.uploadFile = this.element.find('#pic-file');
			this.uploadVod = this.element.find('#video-file');
			this.uploadFile.ajaxUpload({
				url : _this.options.url,
				phpkey : 'Filedata',
				before : function( info ){
					_this._uploadBefore(info['data']['result']);
				},
				after : function( json ){
					_this._uploadAfter(json);
				}
			});
			this.uploadVod.ajaxUpload({
				url : this.options.vodUrl,
				phpkey : 'videofile',
				type : 'video',
				before : function(){
					$(".loading").prependTo( op['vod-list'] ).show();
					$(".prevent-do").show();
				},
				after : function( json ){
					$(".loading").hide();
					$(".prevent-do").hide();
					_this._uploadVodAfter(json);
				}
			});
		},
		_triggerFile : function( e ){
			var self = $(e.currentTarget),
				type = self.attr('_type'),
				isPic = type == 'pic',
				tpl = this.options[ isPic ? 'addFormPicTpl' : 'addFormVodTpl' ].tmpl(),
				dom = tpl.appendTo( '.material-list[_type="'+ type +'"]' );
			dom.find('[type="file"]').click();
		},
		_prevPic : function( e ){
			var target = e.currentTarget,
				file = target.files[0],
				reader = new FileReader(),
				self = $( target );
			reader.readAsDataURL(file);
            reader.onloadend = function(event){
                var target = event.target,
                	result = target.result;
                self.siblings('img').attr('src', result);
                self.closest('.material-item').show();
            };
		},
		_prevVod : function( e ){
			var target = e.currentTarget,
				file = target.files[0],
				filename = file.name,
				self = $( target );
			self.siblings('p').attr('title', filename).text( filename );
			self.closest('.material-item').show();
		},
		_preventInit: function(){
			var _this = this;
			jConfirm( '文件正在上传,你确定离开?', '上传提醒',function( result ){
				if( result ){
					$( _this.options['back'] ).trigger( 'click' );
				}
			} );
		},
		_delMaterial : function( e ){
			var self = $(e.currentTarget);
			jConfirm('确定要删除么？', '删除提示', function( result ){
				if( result ){
					var type = self.closest('.material-list').attr('_type');
					if( type == 'pic' ){
						$('.material-lists [name="photos"]').val('');
					}else{
						$('.material-lists [name="video"]').val('');
					}
					var parent = self.closest('.material-item');
					parent.slideUp(function(){
						parent.remove();
					});
				}
			});
		},
		_upload : function(event){
			var op = this.options;
			$( op['pic_file'] ).click();
		},
		_uploadvod : function(event){
			var op = this.options;
			$( op['video_file'] ).click();
		},
		_uploadBefore : function( src ){
			this.src = src;
		},
		_uploadAfter : function( json ){
			var data = json['data'];
			var op = this.options,
				info = {};
			info.pic_src = data.img;
			info.id = data.id;
			$( op['add-pic-tpl'] ).tmpl( info ).prependTo( op['pic_list'] );
		},
		_uploadVodAfter : function( json ){
			var data = json['data'];
			var op = this.options,
				info = {};
			info.vod_url = data.vod_url;
			info.vod_src = data.img;
			info.id = data.id;
			$( op['add-vod-tpl'] ).tmpl( info ).prependTo( op['vod-list'] );
		},
		_delPic : function( event ){
			var op = this.options,
				self = $(event.currentTarget);
			self.closest( op['pic_item'] ).remove();
			event.stopPropagation();
		},
		_delVod : function( event ){
			var op = this.options,
			self = $(event.currentTarget);
			self.closest( op['vod_item'] ).remove();
			event.stopPropagation();
		},
		_playVideo : function( event ){
			var op =this.options,
				self = $(event.currentTarget),
				url = self.data('url');
			var box = $( op['media_box'] );
			/*
			 * if( box.attr('_type') == 'm_video' && box.css('opacity') == 1 ){
			 * this._closeVideo(); }else{ var info = { video_url : url }; $(
			 * op['vedio-tpl'] ).tmpl(info).prependTo(op['media_box'] );
			 * box.addClass( op['media-box-show'] ).attr({'_type':'m_video'}); }
			 */
			box.removeClass( op['media-box-show'] );
			box.html('');
			var info = { video_url : url };
			$( op['vedio-tpl'] ).tmpl(info).prependTo(op['media_box'] );
			box.addClass( op['media-box-show'] ).attr({'_type':'m_video'});
		},
		
		_closeBox : function(){
			this._closeVideo();
		},
		_closeVideo : function(){
			var op = this.options,
				box = $( op['media_box'] );
			box.removeClass( op['media-box-show'] );
			setTimeout(function(){
				box.html('');
			},500)
		}
	});
	

});
