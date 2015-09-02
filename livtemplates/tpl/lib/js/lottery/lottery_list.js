jQuery(function($){
		var MC = $('.m2o-list'),
			_this = this;
		var control = {
				init : function(){
					this.initList();
					this.initcopy();
					MC
					.on('click' , '.m2o-link' , $.proxy(this.showLink , this))
					.on('mouseleave' , '.m2o-each' , $.proxy(this.hideLink , this))
					.on('click' , '.link-box a' , $.proxy(this.newOpen , this))
					.on('click' , '.create_new_form' , $.proxy(this.generateForm , this))
				},
				
				initList : function(){
					var _this = this;
					$.extend($.geach || ($.geach = {}), {
				        data : function(id , status){
				            var info;
				            $.each(data, function(i, n){
				               if(n['id'] == id){
				                   info = {
				                       id : n['id'],
				                       status : n['status']
				                   }
				                   return false;
				               }
				            });
				            return info;
				        }
				    });
					
				    $('.m2o-each').geach({																								
					   	custom_audit : true,
					   	auditCallback : function(event){
					   		var status_text = ['待审核','已审核','已打回'],
					   			option_text = ['', '打回' ,'审核'],
					   			status_color = ['#8ea8c8','#17b202','#f8a6a6'];
					   		var self = $(event.currentTarget),
					   			id = self.data('id'),
					   			item = self.closest('.m2o-each').find('.m2o-audit'),
					   			status = item.attr('_status') == 1 ? 0 : 1,
					   			url = './run.php?mid=' + gMid + '&a=audit&ajax=1&id=' + id + '&audit=' + status;
				    		$.globalAjax( item , function(){
				        		return $.getJSON( url,function( json ){
										if(json['callback']){
											eval( json['callback'] );
											return;
										}else{
											item.text( status_text[json[0].status] ).attr('_status' , json[0].status ).css('color' , status_color[ json[0].status ] );
											self.find('.option-audit').text( option_text[json[0].status] );
											if(json[0].status == 1)
											{
												_this.createForm( self , id , false);
											}
										}
									});
							});
					    },
				    });
				    
					$('.m2o-list').glist();
				},
				
				initcopy : function(){
					$('.m2o-each .forLoadSwf').each( function(){
						var id = 'forLoadSwf' + $(this).data('index');
						var copyCon = $(this).attr('_text');
						var flashvars = {
							content: encodeURIComponent(copyCon),
							uri: RESOURCE_URL + 'flash_copy_btn.png'
						};
						var params = {
								wmode: "transparent",
								allowScriptAccess: "always"
						};
						swfobject.embedSWF( RESOURCE_URL + '/lottery/clipboard.swf' , id , "52", "25", "9.0.0", null, flashvars, params);
					});
				},
				
				createForm : function( self , id  , bool ){
					var _this = this;
					var url = './run.php?mid=' + gMid + '&a=create_form&id='+ id;
					$.globalAjax( self, function(){
						return $.getJSON( url,function( data ){
							if(bool){				/*主要是 审核之后自动生成表单 不要提示*/
								if( data['callback'] ){
									eval( data['callback'] );
									return;
								}else{
									_this.myTip( self , '表单生成成功!' );   
								}
							}else{
								if( data['callback'] ){
									eval( data['callback'] );
									return;
								}
							}
						});
					});
				},
				
				showLink : function( event ){
					var self = $( event.currentTarget );
					self.find('.link-box').css('display' , '-webkit-box');
				},
				
				newOpen : function( event ){
					var self = $( event.currentTarget ),
						href = self.attr('_href');
					window.open(href);
				},
				
				hideLink : function( event ){
					var self = $( event.currentTarget );
					self.find('.link-box').hide();
				},
				
				generateForm : function( event ){
					var self = $( event.currentTarget ),
						_this = this,
						id = self.closest('.m2o-each').data('id');
					this.createForm( self , id , true );
				},
				
				myTip : function( self , tip ){
					self.myTip({
						string : tip,
						delay: 1000,
						dtop : 0,
						dleft : 40,
						color : '#1bbc9b'
					});
				},
				
		};
		control.init();
});