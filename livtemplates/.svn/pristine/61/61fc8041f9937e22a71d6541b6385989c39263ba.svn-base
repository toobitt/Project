jQuery(function($){
		var options = {
				color : ['red' , '#8ea8c8','#17b202'] , 
				audit : ['已加入黑名单' , '待审核' , '已审核' ],
				verify : ['待认证' , '已认证']
		};
		
		var MC = $('.common-list-content'),
			_this = this;
		var control = {
				init : function(){
					MC
					.on('click' , '.verify' , $.proxy(this.verify , this))
					.on('click' , '.blacklist' , $.proxy(this.addblacklist , this))
					.on('click' , '.option-blacklist' , $.proxy(this.optionblacklist , this))
					.on('click' , '.more-info' , $.proxy(this.getmore , this))
					.on('click' , '.record-edit-back-close' , $.proxy(this.back , this))
					.on('click' , '.m2o-item-bt' , $.proxy(this.showinfo , this))
					this.initlist();
				},
				
				initlist : function(){
					 $.extend($.geach || ($.geach = {}), {
					        data : function(member_id , status){
					            var info;
					            $.each(data, function(i, n){
					               if(n['member_id'] == member_id){
					                   info = {
					                       id : n['member_id'],
					                       status : n['status'],
					                       isblack : n['blacklist']['isblack']
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
				        	var self = $(event.currentTarget),
				        		id = self.data('id'),
				        		_this = this,
				        		item = self.find('.m2o-audit'),
				        		isblack = item.attr('_black'),
				        		url = './run.php?mid=' + gMid + '&a=audit&member_id=' + id;
				        	if( isblack == 1 ){
				        		return false;
				        	}else{
				        		$.globalAjax( item , function(){
					        		return $.getJSON( url,function( json ){
										if(json['callback']){
											eval( json['callback'] );
											return;
										}else{
											var status = (json[0] == 1) ? 2 : 1;
											item.text( options.audit[status] ).attr('_status' , json[0]).css('color' , options.color[status]);
										}
									});
								});
				        	}
				       },
				     });
					 $('.m2o-list').glist();
				},
				
				verify : function( event ){
					var self = $( event.currentTarget ),
						member_id = self.data('id'),
						pass = self.attr('_isverify')==0 ? '通过' : '拒绝',
						url = './run.php?mid=' + gMid + '&a=verify&member_id=' + member_id;
					var method = function(){
						$.globalAjax( self , function(){
							return $.getJSON(url , function( json ) {
								var isverify = (json == 1) ? 2 : 1;
								self.text( options.verify[json] ).css('color' , options.color[isverify]);
							},'json');
						} );
					}
					this.remind( '是否'+ pass  +'认证？', '认证提醒' , method , self );
				},
				
				optionblacklist : function( event ){
					var self = $(event.currentTarget),
						id = self.data('id'),
						isblack = self.attr('_type'),
						status = self.attr('_status'),
						_this = this,
						url = './run.php?mid=' + gMid + '&a=blacklistset&member_id=' + id + '&deadline='+((isblack==0)?-1:0);
					$.globalAjax( self , function(){
						return $.getJSON(url , function( json ) {
							var txt = (json[0].isblack == 1) ? '取消黑名单' : '加入黑名单' ,
								type = (json[0].isblack == 1) ? 1 : 0 ;
							_this.getinfo(json[0].isblack , status , id);
							self.text( txt ).attr('_type' , type );
							self.closest('.m2o-option').find('.m2o-option-close').trigger('click');
						},'json');
					} );
					
				},
				
				addblacklist : function( event ){
					var self = $(event.currentTarget),
						txt = self.text(),
						ids = MC.find('.m2o-each').map(function(){
							var checked = $(this).find('input[type="checkbox"]').prop('checked');
							if( checked ){
								return $(this).data('id');
							}
						}).get().join(','),
						_this = this;
					if(!ids){
						var str = '请先选择操作对象';
						_this.myTip( self , str );
						return false;
					}else{
						var method = function(){
							var box = MC.find('.m2o-each-list'),
								isblack = self.data('type'),
								url = './run.php?mid=' + gMid + '&a=blacklistset&member_id=' + ids + '&deadline='+((isblack==0)?-1:0);
								var status = MC.find('.m2o-each').map(function(){
									var checked = $(this).find('input[type="checkbox"]').prop('checked');
									if( checked ){
										return $(this).find('.m2o-audit').attr('_status');
									}
								}).get();
							$.globalAjax( box , function(){
								return $.getJSON(url , function( json ) {
									var i = ids.split(',');
									$.each(i , function(key , value){
										_this.getinfo( json[0].isblack , status[key] , value);
									})
								},'json');
							} );
						}
						this.remind( '是否'+ txt +'?', '操作提醒' , method , self );
					}
				},
				
				getinfo : function(isblack , status , id){
					var audit = '';
					if( isblack == 1 ){
						audit = 0;
					}else{
						audit = ( status == 0 ) ? 1 : 2;
					}
					MC.find('.m2o-each[data-id="'+ id +'"]').find('.m2o-audit')
					.text( options.audit[audit] )
					.attr('_black' , isblack)
					.css('color' , options.color[audit])
					.attr('_status' , status )[( isblack == 1  ? 'add': 'remove') + 'Class' ]('isblack');
				},
				
				getmore : function( event ){
					var self = $( event.currentTarget ),
						item = self.closest('.m2o-option'),
						box = item.find('.record-edit-more-info'),
						index = self.closest('.m2o-each').index();
					var info = {};
					info.mobile= data[index].mobile;
					info.email = data[index].email;
					info.num = data[index].reg_device_token;
					info.last = data[index].last_login_device;
					info.inviteuser = data[index].inviteuser.member_name;
					info.spreadcode = data[index].spreadcode;
					if ( box.find('.info-list')[0] ){
						return false;
					}  
					$('#record-info-tpl').tmpl( info ).appendTo( box );
					item.find('.m2o-option-inner').hide().end().find('.record-edit-more-info').show();
					var height = item.find('.record-edit-more-info').height() + 40;
					this.adjustLook(height , false);
				},
				
				back : function( event ){
					var self = $( event.currentTarget ),
						item = self.closest('.m2o-option');
					item.find('.record-edit-more-info').hide().empty().end().find('.m2o-option-inner').show();
					var height = item.find('.m2o-option-inner').height() + 40;
					this.adjustLook(height , false);
				},
				
				showinfo : function( event ){
					var self = $( event.currentTarget ),
						item = self.closest('.m2o-each');
					var visible = item.find('.m2o-option').is(':visible');
					item.find('.m2o-ibtn').trigger('click');
					if( visible ){
						item.find('.record-edit-back-close').trigger('click' , event );
					}else{
						setTimeout(function(){
							item.find('.more-info').trigger('click' , event );
						},500);
					}
				},
				
				judgeBox : function(boxheight){
		        	var widget = $('.m2o-option'),
		        		m2o_each = widget.closest( '.m2o-each' );
		        	var wh = this.getWH(),
			    		h = boxheight,
			    		window_h = $(window).height(),
			    		top = m2o_each.offset().top;
			    	if( h+top  >= window_h ){
			    	    var btnH = parseInt(widget.find('.m2o-option-close').height() );
			    	    top = - h + btnH + 6;
			    	    widget.addClass( 'up-model') ;
			    	}else{
			    		top = '6px';
			    		widget.removeClass('up-model') ;
			    	}
			    	return top;
		       },
		        
		        adjustLook : function(boxheight, animate ){
		        	var widget = $('.m2o-option');
			    	var stopfn = function() { widget.css({ width: '', height: '' }); },
			    		top = this.judgeBox(boxheight);
			    	var wh = this.getWH(),
			    		w = wh[0],
			    		h = wh[1];
			    	if( animate ){
				    	widget.stop().css({
				    		width : 0,
				    		height : 0
				    	}).animate({ width: w, height: h, top:top }, 200, stopfn);
			    	}else{
			    		widget.css({ top:top });
			    	}
		        },
		        
		        getWH : function(){
		        	var widget = $('.m2o-option');
		        	return [widget.width() , widget.height()];
		        }, 
				
				myTip : function( self , tip ){
					self.myTip({
						string : tip,
						delay: 1000,
						dtop : 0,
						dleft : 80,
						color : '#1bbc9b'
					});
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