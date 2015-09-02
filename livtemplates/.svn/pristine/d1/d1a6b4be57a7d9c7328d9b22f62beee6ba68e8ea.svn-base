jQuery(function($){
		var MC = $('.members_form'),
			_this = this;
		var control = {
				init : function(){
					MC
					.on('click' , '.indexpic' , $.proxy(this.imgupload , this))
					.on('change' , 'input[name="avatar"]' , $.proxy(this.change , this))
					.on('click' , '.member-title' , $.proxy(this.toggle , this))
					.on('click' , '.medal-list li' , $.proxy(this.chooseMedal , this))
					.on('click' , 'input[name="blacklist"]' , $.proxy(this.radioToggle , this))
					.on('click' , '.unbind' , $.proxy(this.unbind , this))
					.on('click' , '.img-upload-btn' , $.proxy(this.idUpload , this))
					.on('change' , '.id-upload' , $.proxy(this.uploadId , this))
					.find('.date-picker').datepicker();
					this.initcopy();
				},
				
				imgupload : function(){
					MC.find('input[name="avatar"]').trigger('click');
				},
				
				change : function( e ){
					var self = e.currentTarget,
					   	file = self.files[0],
					   	type = file.type;
					var reader=new FileReader();
					reader.onload=function(event){
						imgData=event.target.result;
						var box = MC.find('.indexpic'),
							img = box.find('img');
	 	    			!img[0] && (img = $('<img />').appendTo( box ));
	 	    			img.attr('src', imgData);
	 	    			box.find('span').hide();
					}
	                reader.readAsDataURL( file );
				},

				
				toggle : function( event ){
					var self = $( event.currentTarget ),
						index = self.index();
					self.addClass('active').siblings().removeClass('active');
					MC.find('.member-info:eq('+ index +')').show().siblings('.member-info').hide();
					if( index == 4 ){
						this.getList();
					}
				},
				
				getList : function( event ,page ,page_num , bool){			
					var _this = this,
						box = MC.find('.score-list'),
						len = box.find('.score-each').length;
					if( len && !bool){
						return;
					}
					var page = page ? page : 1 ,
						id = MC.find('.score-box').data('id'),
						url =  'run.php?mid='+ gMid + '&a=getCreditLogFromMembers' + '&page=' + page + '&id=' + id;
					$.globalAjax( box, function(){
						return $.getJSON( url, null, function(data){
							_this.getInfo( data.info );
							_this.getInfopage( data.page_info );
						});
					});
				},
				
				getInfo : function( data ){
					var box = MC.find('.score-list');
					if(data){
						$('#list-tpl').tmpl(data).appendTo( box.empty() );
					}else{
						box.html('<div class="score-each no-data">没有相关数据</div>');
					}
				},
				
				getInfopage : function( option ){								/*分页*/
		        	var page_box = MC.find('.page_size'),
		                _this = this;
		            option.show_all = true;
		            if (page_box.data('init')) {
		                page_box.page('refresh', option);
		            } else {
		                option['page'] = function (event, page, page_num) {
		                    _this.refresh(event,page,page_num);
		                }
		                page_box.page(option);
		                this.page_num = option.page_num;
		                page_box.data('init', true);
		            }
			     },

			     refresh: function(event,page ,page_num) {
		             this.getList(event, page, page_num , true);
		         },
				
				chooseMedal : function( event ){
					var self = $( event.currentTarget );
					self.toggleClass('selected');
					var ids = MC.find('.medal-list li').map(function(){
						if ( $(this).hasClass('selected') ){
							return $(this).data('id');
						}
					}).get().join(',');
					MC.find('input[name="medal_id"]').val( ids );
				},
				
				radioToggle : function( event ){
					var self = $( event.currentTarget ),
						val = self.val(),
						item = MC.find('input[name="isblack"]').closest('.m2o-item');
					val == 0 ? item.hide() : item.show() ;
				},
				
				initcopy : function(){
					var uri = MC.data('btn'),
						swf = MC.data('swf');
					$('.info-list .forLoadSwf').each( function(){
						var id = 'forLoadSwf' + $(this).data('index');
						var copyCon = $(this).attr('_text');
						var flashvars = {
							content: encodeURIComponent(copyCon),
							uri: uri + 'flash_copy_btn.png'
						};
						var params = {
								wmode: "transparent",
								allowScriptAccess: "always"
						};
						swfobject.embedSWF( swf + '/members/clipboard.swf' , id , "52", "25", "9.0.0", null, flashvars, params);
					});
				},
				
				unbind : function( event ){
					var self = $( event.currentTarget ),
						item = self.closest('li'),
						id = item.data('memberid'),
						url = './run.php?mid=' + gMid + '&a=unbind&ajax=1&bind=' + id;
					var method = function(){
						$.globalAjax( item , function(){
							return $.getJSON(url, function( json ) {
								if(json['callback']){
									eval( json['callback'] );
									return;
								}else{
									item.remove();
								}
							});
						} );
					}
					this.remind( '是否'+ self.text() +'?', '操作提醒' , method , self );
				},
				
				idUpload : function( e ){
					var self = $( e.currentTarget );
					self.closest('.img-box').find('input[type="file"]').trigger('click');
				},
				
				uploadId : function( e ){
					var self = e.currentTarget,
				   		file = self.files[0],
				   		type = file.type;
					var reader=new FileReader();
					reader.onload=function(event){
						imgData=event.target.result;
						var box = $(self).closest('.img-box').find('.img'),
						img = box.find('img');
						!img[0] && (img = $('<img />').appendTo( box ));
						img.attr('src', imgData);
					}
			    	reader.readAsDataURL( file );
				},
				
				remind : function( title , message , method , self){
					jConfirm( title, message , function(result){
						if( result ){
							method();
						}else{}
					}).position( self );
				},
		};
		control.init();
});