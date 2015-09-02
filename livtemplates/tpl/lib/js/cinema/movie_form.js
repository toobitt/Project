jQuery(function($){
		var MC = $('.m2o-form'),
			_this = this;
		var control = {
				init : function(){
					MC
					.on('click' , '.indexpic' , $.proxy(this.indexpic , this))
					.on('change' , 'input[name="img"]' , $.proxy(this.showIndexpic , this))
					.on('click' , '.play-video' , $.proxy(this.playVideo , this))
					.on('click' , '.close-video' , $.proxy(this.closeVideo , this))
					.on('click' , '.add-still' , $.proxy(this.addPic , this))
					.on('click' , '.add-video' , $.proxy(this.addVideo , this))
					this.initDom();
				},
				
				/*图片，音频，视频上传*/
				upload : function(file ,self , a , pkey , type){
					var op = this.option;
					var _this = this;
			        file.ajaxUpload({
						url : 'run.php?mid='+ gMid +'&a=' + a,
						phpkey : pkey,
						type : type,
						before : function( info ){
							_this.loading(self);
						},
						after : function( json ){
							if(type=='image'){
								_this.getImageinfo(json.data ,self);
							}else{
								_this.getVodinfo(json.data ,self);
							}
							
						}
					});
				},
				
				getImageinfo : function( data , item ){
					if( data.error_code ){
						this.myTip(item , data.msg);
					}else{
						$('#image-add-tpl').tmpl( data ).insertBefore( item );
						var ids = this.getFileid( item );
						MC.find('input[name="still_id"]').val( ids );
					}
					MC.find('.loading').remove();
				},
				
				getVodinfo : function( data , item ){
					if( data.error_code ){
						this.myTip(item , data.msg);
					}else{
						$('#video-add-tpl').tmpl( data ).insertBefore( item );
						var ids = this.getFileid( item );
						MC.find('input[name="prevue_id"]').val( ids );
					}
					MC.find('.loading').remove();
				},
				
				getFileid : function( item ){
					var parent = item.closest('ul');
					var ids = parent.find('li:not(.add-file)').map(function(){
						return $(this).attr('_id');
					}).get().join(',');
					return ids;
				},
				
				initDom : function(){
					MC.find('.fancybox').fancybox();				//灯箱 图片大图预览
					MC.find('.date-picker').datepicker();
				},
				
				indexpic : function(){
					MC.find('input[name="img"]').trigger('click');
				},
				
				showIndexpic : function( event ){
					var file = event.currentTarget.files[0],
	 					type = file.type,
	 					reader = new FileReader();
	 				var self = $(event.currentTarget),
	 					item = MC.find('.indexpic'),
	 					img = item.find('img');
	 				var matchType = /image.*/;
	 				if(!type.match(matchType)){
	 					this.myTip( item , '请选择图片');
	 					return false;
	 				}
	 				reader.readAsDataURL(file);
	 				reader.onload =  function(e){
	 					var result = e.target.result;
	 					!img[0] && (img = $('<img style="width: 176px; height: 176px;"/>').appendTo( item ));
	 					img.attr('src',result);
	 					item.find('.indexpic-suoyin').addClass('indexpic-suoyin-current');
	 				};
				},
				
				addPic : function(event){
					var self = $(event.currentTarget),
						file = MC.find('input[name="still"]'),
						a = 'upload_image',
						pkey = 'pic',
						type = 'image';
					file.trigger('click');
					this.upload(file ,self , a , pkey , type);
				},
				
				addVideo : function(event){
					var self = $(event.currentTarget),
						file = MC.find('input[name="prevue"]'),
						a = 'upload_video',
						pkey = 'videofile',
						type = 'video';
					file.trigger('click');
					this.upload(file ,self , a , pkey , type);
				},
				
				loading : function(self){
					$('<img class="loading" src="' + RESOURCE_URL + 'loading2.gif" style="background: #fff;width: 45px;height: 45px;margin: 5px;background:#fff;"/>').appendTo(self);
				},
				
				getattachinfo : function( data , self ){
					
				},
				
				playVideo : function( event ){
					var self = $( event.currentTarget ),
						videoUrl = self.attr('_url');
					MC.find('.video-box').remove();
					$('#video-tpl').tmpl({url : videoUrl}).appendTo( MC );
					setTimeout(function(){
						MC.find('.cover').show();
						MC.find('.video-box').addClass('play');
						MC.find('.video-js')[0].play();
					})
				},
				
				closeVideo : function(){
					MC.find('.video-js')[0].pause();
					MC.find('.video-box').removeClass('play');
					MC.find('.cover').hide();
				},
				
				myTip : function(self , tip ){
	                self.myTip({
	                    string : tip,
	                    delay: 1000,
	                    width : 120,
	                    dleft : 140,
	                });
	            },
		};
		control.init();
});