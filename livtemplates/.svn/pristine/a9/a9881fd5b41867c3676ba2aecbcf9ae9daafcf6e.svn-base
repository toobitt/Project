$(function(){
	(function($){
		$.widget('market.market_list',{
			options : {
				'market-list' : '.market-list',				
				'market-each' : '.market-each',
				'selected' : 'selected',
				'reaudit':'.reaudit',				
				'del' : '.del',
				'edit' : '.edit',
				'save' : '.save',
				'market-default' : '.market-default',
				'market-add' : '.market-add',
				'image-file' : '.image-file',
				'market-img' : '.market-img',
				'mk-logo' : '.mk-logo',
				'market-name' : '.market-name',
				'market-head' : '.market-head',
				'market-logo' : '.market-logo',
				'mkt-save' : '.mkt-save',
				'add-head' : '.add-head',
				'market-logoid' : '.market-logoid',
				'add-market-tpl' : '#add-market-tpl',
				'checkAll' : '#checkAll',
				'bataudit' : '.bataudit',
				'batback' : '.batback',
				'batdelete' : '.batdelete',
			},
			_create : function(){
				this.status = ['','待审核','已审核','已打回'];
				this.status_color = ['','#8ea8c8','#17b202','#f8a6a6'];
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['reaudit'] ] = '_auditMarket';
				handlers['click ' + op['bataudit'] ] = '_auditall';
				handlers['click ' + op['del'] ] = '_delMarket';			
				handlers['click ' + op['market-each'] ] = '_checkMarket';
				handlers['click ' + op['market-default'] ] = '_addLogo';
				handlers['click ' + op['mk-logo'] ] = '_editLogo';
				handlers['click ' + op['edit'] ] = '_editMarket';
				handlers['click ' + op['save'] ] = '_saveMarket';
				handlers['focus ' + op['add-head'] ] = '_showButton';
				handlers['click ' + op['mkt-save'] ] = '_addMarket';
				handlers['click ' + op['checkAll'] ] = '_checkAll';
				handlers['click ' + op['batback'] ] = '_batback';
				handlers['click ' + op['batdelete'] ] = '_delall';
				this._on(handlers);
				this._initUpload();
			},
			_initUpload : function(){
				var op = this.options,
					_this = this;
				op['url'] = "./run.php?mid=" + gMid + "&a=upload_img";
				this.uploadLogo = this.element.find( op['image-file'] );
				this.uploadLogo.ajaxUpload({
					url : op['url'],
					phpkey : 'logo',
					after : function( json ){
						_this._uploadIndexAfter(json);
					}
				});   
			},	
			_uploadIndexAfter : function(json){
				var op = this.options;
				var data = json['data'];
		 	 	this.box.find( 'img' ).attr('src',data['img_info']);
		 	 	this.box.find( op['market-logoid'] ).val( data['id'] );
			},
			
			_showButton : function(){
				var op = this.options;
					$( op['market-add'] ).find( op['mkt-save'] ).show();
			},
			
			_auditall : function(){
				var op = this.options,
					obj = this.element.find( op['market-each'] + '.selected' ),
					ids = obj.map(function(){
						return $(this).attr("_id");
					}).get().join(",");
				 if( !ids ){
					 this._remind( '请选择要审核的记录', '审核提醒');
					 return;
				 }
		          this.auditajax( ids, obj);
			},	
			auditajax : function( ids, obj){
				var op = this.options;
				var method = function(){
					var url = './run.php?mid=' + gMid + '&a=audit' + '&op=2';
					$.get(url,{id : ids},function(){
						obj.find( op['reaudit'] ).text('已审核').css({'color':'#17b202'}).attr('_status',2);
					});
				};
				this._remind( '您确认批量审核选中记录吗？', '审核提醒' , method );				
			},
			
			_auditMarket : function( event ){		
				var op = this.options,
					self = $(event.currentTarget),
					id = self.attr('_id'),
					status = self.attr('_status');
				this._audit( self, id, status );
				event.stopPropagation();
			},
			
			_audit : function( self, id , status ){
				var _this = this,
					url = './run.php?mid=' + gMid + '&a=audit';	
				$.getJSON( url, {id : id, status : status} ,function( data ){	
					var data = data[0];
						status = data['status'],
						status_text = _this.status[status],
						status_color = _this.status_color[status];
					self.text( status_text ).css({'color' : status_color }).attr('_status',status);
				});
			},
			_delall : function( event ){
				var op = this.options,
					obj = $( op['market-each'] + '.selected' ),		
					ids = obj.map(function(){
						return $(this).attr("_id");
					}).get().join(",");
				if( !ids ){
					 this._remind( '请选择要删除的记录', '删除提醒');
					 return;
				 }
				this._del( ids, obj );
				event.stopPropagation();
			},
			
			_delMarket : function( event ){
				var op = this.options,
					widget = this.element;
				var self = $(event.currentTarget),
					item = self.closest( op['market-each'] ),
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
			
			_del : function( id , item ){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&a=delete';
					$.get( url, {id : id } ,function(){
						item.remove();
					});
				};
				this._remind( '是否要删除此内容?', '删除提醒' , method );
			},	
			
			_back : function( ids, obj){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&a=audit' + '&op=3';
					$.get(url,{id : ids},function( data ){
						obj.find('.reaudit').text('已打回').css({'color':'#f8a6a6'}).attr('_status',3);
					});
				};
				this._remind( '您确认批量打回选中记录吗？', '打回提醒' , method );	
			},
			_batback : function(){
				var op = this.options,
				 	obj = this.element.find( op['market-each'] + '.selected' ),
					 ids = obj.map(function(){
						return $(this).attr("_id");
					 }).get().join(",");
				 if( !ids ){
					 this._remind( '请选择要打回的记录', '打回提醒');
					 return;
				 }
		          this._back( ids, obj);
			},	
			
			_editMarket : function( event ){
				var op = this.options,
					widget = this.element;
				var self = $(event.currentTarget),
					item = self.closest( op['market-each'] );
				var name = item.find( op['market-name'] ),
					box = item.find( op['market-img'] );
				name.find('a').hide();
				name.find( op['market-head'] ).show().focus();
				name.find( op['save'] ).show();
				box.find('a').hide();
				self.hide();
			},		
			
			_saveMarket : function( event ){
				var op = this.options,
					widget = this.element,
				    self = $(event.currentTarget),
					item = self.closest( op['market-each'] );
				var name = item.find( op['market-name'] ),
					box = item.find( op['market-img'] ),
					url = './run.php?mid=' + gMid + '&a=update';
				var info = {};
				info.id = item.attr('_id');
				info.market_name = name.find( op['market-head'] ) .val();
				info.logo_id = box.find( op['market-logoid'] ).val();
				if(!info.market_name || info.logo_id == 0){
					jAlert('请填写新增完整','商超提醒');
					return false;
				}
				if(info.market_name){
					$.getJSON( url, info, function(data){
						var data = data[0];
						var market_name = data.market_name;
							logo = data.logo;
							if(logo.length){
								box.find( op['mk-logo'] ).attr('src',logo);
							}else{
								box.find( op['mk-logo'] ).attr('src', RESOURCE_URL + 'market/default_logo.png');
							}
							name.find('a').show().html(market_name);
							name.find( op['market-head'] ).hide();
							item.find( op['edit'] ).show();
							box.find('a').show();
							self.hide();
					});
				}
				event.stopPropagation();
			}, 
			
			_addMarket : function( event ){
				var op = this.options,
				    self = $(event.currentTarget),
					item = self.closest( op['market-add'] );
				url = './run.php?mid=' + gMid + '&a=create';
				var info = {};
				info.market_name = item.find( op['market-head'] ) .val();
				info.logo_id = item.find( op['market-logoid'] ).val();
				if(!info.market_name || !info.logo_id){
					jAlert('请填写新增完整','商超提醒');
					return false;
				}
				if(info.market_name){
					$.getJSON( url, info, function(data){
						var data = data[0];
						var param = {};
						logol = data.logo;
						param.market_name = data.market_name;
						param.id = data.id;
						param.order_id = data.order_id;
						param.logo_id = data.logo_id;
						param.user_name = data.user_name;
						param.create_time = data.create_time;
						if(logol){
							param.logo = logol.host + logol.dir + logol.filepath + logol.filename;
						}
						$( op['add-market-tpl'] ).tmpl( param ).insertAfter( op['market-add'] );
						item.find( op['market-head'] ) .val('');
						item.find( op['mkt-save'] ).hide();
						item.find( op['market-logoid'] ).val('');
						$( op['market-default'] ).find('img').remove();
					});
				}
				event.stopPropagation();
			},
			
			_checkMarket : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					id = self.attr('_id'),
					url = './run.php?mid=' + gMid + '&a=form&id=' + id; 
				if( self.hasClass( op['selected'] ) ){
					self.removeClass( op['selected'] );
				}else{
					self.addClass( op['selected'] );
				}
			},
			
			_checkAll : function( event ){
				var widget = this.element,
					op = this.options,
					self = $(event.currentTarget),
					isSelected = self.prop('checked');
				widget.find( op['market-each'] ).each(function(){
					$(this)[(isSelected ? 'add': 'remove') + 'Class' ]( op['selected'] );
				});
			},
			
			_addLogo : function( event ){
				var op = this.options,
				    self =$(event.currentTarget);
				    img = self.find('img');
					this.box = self;
					if(!img.length){
						var strHtml = "<img src='' />";
						$(strHtml).appendTo( op['market-default'] );
					}
					self.find('img').css({height:'155px', width:'273px'});
				    this.uploadLogo.click();
				    event.stopPropagation();
			},
			
			_editLogo : function( event ){
				var op = this.options,
				    self =$(event.currentTarget),
			    	item = self.closest( op['market-each'] );
					this.box = self.closest( op['market-img'] );
				    this.uploadLogo.click();
				    event.stopPropagation();
			},
			
			
		});
	})($);
	$('.market-wrap').market_list();
});