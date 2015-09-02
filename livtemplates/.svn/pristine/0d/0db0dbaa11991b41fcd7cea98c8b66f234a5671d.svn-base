$(function($){
	(function($){
		$.widget('market.store_list',{
			options : {
				'subbranch-add' : '.subbranch-add',
				'subbranch-each' : '.subbranch-each',
				'selected' : 'selected',
				'market-edit' : '.market-edit',
				'market-init' : 'market-init',
				'add-subbranch-tpl' : '#add-subbranch-tpl',
				'del' : '.del',
				'add' : '.add',
				'market-figure' : '.market-figure',
				'image-file' : '.image-file',
				'save-pink' : '.save-pink',
				'update-method' : '.update-method',
				'tel-each' : '.tel-each',
				'tel-list' : '.tel-list',
				'add-tel-tpl' : '#add-tel-tpl',
				'add-pic-tpl' : '#add-pic-tpl',
				'tel-mode' : '.tel-mode input',
				'pic-list' : '.pic-list',
				'pic-first' : '.pic-each:first-child',
				'market-logo' : '.market-logo',
				'allmap' : '#allmap',
				'market-itmap' : '.market-itmap',
				'pic-each' : '.pic-each',
				'current' : 'current',
				'pic-del' : '.pic-del',
				'market-time' : '.market-time',
			},
			_create : function(){
				this.id = this.element.data('id');
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['subbranch-add'] ] = '_addSubbranch';
				handlers['click ' + op['subbranch-each']] = '_checkSubbranch';
				handlers['click ' + op['del'] ] = '_delSubbranch';	
				handlers['click ' + op['market-figure'] ] = '_addLogo';
				handlers['click ' + op['add'] ] = '_addContact';
				handlers['blur' + op['tel-mode'] ] = '_cancelFocus';
				handlers['click ' + op['pic-each'] ] = '_chooseLogo';
				handlers['click ' + op['pic-del'] ] = '_delPic';
				this._on(handlers);
				this._initForm();
				this.uploadLogo = this.element.find( op['image-file'] );
				op['url'] = "./run.php?mid=" + gMid + "&a=upload_img";
				this.uploadLogo.ajaxUpload({
					url : op['url'],
					phpkey : 'logo',
					after : function( json ){
						_this._uploadIndexAfter(json);
					}
				});   
			},
			
			_uploadIndexAfter : function(json){
				var op = this.options,
					data = json['data'],
					info = {};
					info.logotid = data['id'];
					info.logot = data['img_info'];
				$( op['add-pic-tpl'] ).tmpl( info ).appendTo( op['pic-list'] );
			    var img = $( op['market-figure'] ).find('img');
			    if(!img.attr('src')){
			    	var goal = $( op['pic-first'] ).addClass( op['current'] );
			    	var src  = goal.find('img').attr('src');
			    	var id = goal.find('input').val();
			    	img.attr('src',src);
			    	$( op['market-logo'] ).val(id);
			    }
			},
			
			_chooseLogo : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					img = $( op['market-figure'] ).find('img');
					if( self.hasClass( op['current'] ) ){
						return;
					}else{
						self.addClass( op['current'] )
						.siblings().removeClass( op['current'] );
					}
				var src = self.find( 'img' ).attr('src'),
					id = self.find('input').val();
					img.attr('src',src);
			    	$( op['market-logo'] ).val(id);
			},
			
			_initForm : function(){
				var op = this.options,
					info = {};
				info.opera = '新增';
				info.method = 'create';
				$( op['add-subbranch-tpl'] ).tmpl( info ).prependTo( op['market-edit'] );
			},
			_addSubbranch : function(){
				var op = this.options,
					info = {};
				info.opera = '新增';
				info.method = 'create';
				$( op['market-edit'] ).empty();
				$( op['add-subbranch-tpl'] ).tmpl( info ).appendTo( op['market-edit'] );
				$( op['market-edit'] ).removeClass( op['market-init'] );
				$( op['subbranch-each'] ).each(function(){
					$(this).removeClass( op['selected'] );
				});
				$( op['save-pink'] ).val('新增');
				$('#baidu_longitude').val('');
	        	$('#baidu_latitude').val('');
	        	getLng(0, 0);
				$( op['market-time'] ).hide();
			},
			_checkSubbranch : function( event ){
				var op = this.options,
					widget = this.element,
					_this = this,
					self = $(event.currentTarget);
				var id = self.attr('_id'),
					url = './run.php?mid=' + gMid + '&a=get_store_info&id=' + id; 
				if( self.hasClass( op['selected'] ) ){
					return;
				}else{
					self.addClass( op['selected'] )
					.siblings().removeClass( op['selected'] );
				}
				$.getJSON( url, function( data ){
					if(data){
						var obj=data[0],
					    	info={};
				    	 tel = obj.tel;
				    	 logo = obj.logo;
				    	 logo_id = obj.logo_id;
				    	 info.nname = obj.name;
				    	 baidu_longitude = obj.baidu_longitude;
				    	 baidu_latitude = obj.baidu_latitude;
				    	 info.index_pic = obj.index_pic;
				    	 info.index_pic_id = obj.index_pic_id;
				    	 info.address = obj.address;
				    	 info.opening_time = obj.opening_time;
				    	 info.parking_num = obj.parking_num;
				    	 info.brief = obj.brief;
				    	 info.traffic = obj.traffic;
				    	 info.free_bus = obj.free_bus;
				    	 info.id = obj.id;
				    	 info.order_id = obj.order_id;
				    	 info.update_user_name = obj.update_user_name;
				    	 info.update_time = obj.update_time;
				    	 info.opera = '编辑';
				    	 info.method = 'update';
				    	 $( op['market-edit'] ).empty();
				    	 $( op['add-subbranch-tpl'] ).tmpl(info).appendTo( op['market-edit'] );
				         $( op['market-edit'] ).addClass( op['market-init'] );
				         $( '#baidu_longitude' ).val(baidu_longitude);
			        	 $( '#baidu_latitude' ).val(baidu_latitude);
			        	 getLng(baidu_latitude, baidu_longitude);
			        	 $( op['save-pink'] ).val('保存');
			        	 $( op['market-time'] ).show();
				         if(tel.length>0){
					         $( op['tel-list'] ).empty();
					         var teldata = [];
					         $.each(tel,function(key,value){
					         	var info = {};
						    	info.telp = tel[key];
						    	teldata.push(info);
						      });
						      $( op['add-tel-tpl'] ).tmpl( teldata ).appendTo( op['tel-list'] );
						 }else{
						 	$( op['tel-each'] ).addClass('tel-mode');
						 }
						 index_id = info.index_pic_id;
			        	 logoid = logo_id.split(',');
						if(logoid.length>1){
							var logodata = [];
				        	for(var i=0; logo[i]; i++ ){
				        		var info = {};
				        		info.logot = logo[i];
				        		info.logotid = logoid[i];
				        		logodata.push(info);
				        	}
				        	$( op['add-pic-tpl'] ).tmpl( logodata ).appendTo( op['pic-list'] );
			        	}else{
			        		if(logo){
				        		var info = {};
				        		info.logot = logo;
				        		info.logotid = logoid;
				        		$( op['add-pic-tpl'] ).tmpl( info ).appendTo( op['pic-list'] );
			        		}else{
			        			return false;
			        		}
			        	}
			        	var pic = widget.find( op['pic-each'] ).filter(function(){
			        		return ( $(this).find('input').val() == index_id );
			        	});
			        	pic.addClass( op['current'] );
					}
				});
			},
			_delSubbranch : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['subbranch-each'] ),
					id = item.attr('_id');
				this._del( id, item );
				event.stopPropagation();
			},
			_remind : function( title , message , method ){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{
						
					}
				});
			},
			
			_delPic: function( event ){
				var op = this.options,
					widget = this.element,
					self = $(event.currentTarget);
				self.closest( op['pic-each'] ).remove();
				widget.find( op['pic-each'] ).each(function(){
					if( $(this).hasClass( op['current'] ) ){
						return;
					}else{
						$( op['pic-first'] ).addClass( op['current'] );
					}
				});
				var goal = $( op['pic-each'] + '.current'),
					src = goal.find('img').attr('src'),
					id = goal.find('input').val();
				$( op['market-figure'] ).find('img').attr('src',src);
				$( op['market-logo'] ).val(id);
				event.stopPropagation();
			},
			
			_del : function( id , item ){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&a=delete';
					$.get( url, {id : id } ,function(){
						item.remove();
					});
				};
				this._remind( '是否要删除此内容?', '删除提醒' , method );
			},	
			
			_addLogo : function(){
			    this.uploadLogo.click();
			    event.stopPropagation();
			},
			
			_addContact : function(){
				var op = this.options;
				$( op['add-tel-tpl'] ).tmpl().appendTo( op['tel-list'] );
				$( op['tel-each'] + ':last-child' ).find('input').val('');
			},
			
			_cancelFocus : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					box = self.closest( op['tel-each'] );
				if(box.siblings().length > 0){
					if(self.val()){
						box.removeClass( 'tel-mode' );
					}else{
						box.remove();
					}
				}
			}
		});
	})($);
	$('.m2o-main').store_list();
});
