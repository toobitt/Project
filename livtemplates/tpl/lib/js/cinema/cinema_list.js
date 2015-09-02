$(function(){
	(function($){
		$.widget('cinema.cinema_list',{
			options : {
				
			},
			
			_create : function(){
				this.status = ['待审核','已审核','已打回'];
				this.status_color = ['#8ea8c8','#17b202','#f8a6a6'];
			},
			
			_init : function(){
				this._on({
					'click .bataudit' : '_auditall',
					'click .reaudit' : '_auditPlay',
					'click .batback' : '_batback',
					'click .del' : '_delPlay',
					'click .batdelete' : '_delall',
					'click .select' : '_checkPlay',
					'click #checkAll' : '_checkall'
				})
			},	
			
			_back : function( ids, obj , self){
				var method = function(){
					var url = './run.php?ajax=1&mid=' + gMid + '&a=audit' + '&status=1';
					$.get(url,{id : ids},function( data ){
						if( data['callback']){
							eval( data['callback'] );
							return false;
						}else{
							obj.find('.reaudit').text('已打回').css({'color':'#f8a6a6'}).attr('_status',3);
						}
					} ,'json');
				};
				this._remind( '您确认批量打回选中记录吗？', '打回提醒' , method  ,self);	
			},
			
			_batback : function( event ){
				var self = $( event.currentTarget );
				var ids = this.element.find(".cinema-list li.current").map(function(){
								return $(this).attr("_id");
						  }).get().join(",");
				if( !ids ){
					this._remind( '请选择要打回的记录', '打回提醒' , null ,self);
					return;
				}
	        	var	object = this.element.find(".play-list li.current");
		        this._back( ids, object ,self );
			},	
			
			_auditall : function( event ){
				var self = $( event.currentTarget );
			 var ids = 
				 this.element.find(".cinema-list li.current").map(function(){
					return $(this).attr("_id");
				}).get().join(",");
			 if( !ids ){
				 this._remind( '请选择要审核的记录', '审核提醒' , null ,self);
				 return;
			 }
	        	var	object = this.element.find(".play-list li.current");
		          this.auditajax( ids, object ,self );
			},	
			
			auditajax : function( ids, obj ,self ){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&ajax=1&a=audit' + '&status=2';
					$.get(url,{id : ids},function( data ){
						if( data['callback']){
							eval( data['callback']);
							return false;
						}else{
							obj.find('.reaudit').text('已审核').css({'color':'#17b202'}).attr('_status',2);
						}
					} ,'json');
				};
				this._remind( '您确认批量审核选中记录吗？', '审核提醒' , method ,self );				
			},
			
			_auditPlay : function( event ){	
				var self = $(event.currentTarget),
					id = self.attr('_id'),
					status = self.attr('_status');
				this._audit( self, id, status);
				event.stopPropagation();
			},
			
			_audit : function( self, id , status ){
				var _this = this,
					url = './run.php?mid=' + gMid + '&ajax=1&a=audit';	
				 $.globalAjax( self, function(){
					 return $.getJSON( url, {id : id, status : status}, function(data){
						 if( data['callback']){
							 eval( data['callback']);
							 return false;
						 }else{
							 var data = data[0];
							 var status = data['status'],
							 	 status_text = _this.status[status],
								 status_color = _this.status_color[status];
							 self.text( status_text ).css({'color' : status_color }).attr('_status',status);
						 }
	 				});
 				});
				 
			},
			
			_delall : function( event ){
				var self = $( event.currentTarget );
				var ids = this.element.find(".cinema-list li.current").map(function(){
					return $(this).attr("_id");
				}).get().join(",");
				if( !ids ){
					 this._remind( '请选择要删除的记录', '删除提醒' , null , self);
					 return;
				 }
				var	item = this.element.find(".play-list li.current");
				this._del( ids, item , self);
				event.stopPropagation();
			},
			
			_delPlay : function( event ){
				var self = $(event.currentTarget),
					item = self.closest( '.cinema-each' ),
					id = item.attr('_id');
				this._del( id, item ,self );
				event.stopPropagation();
			},
			
			_remind : function( title , message , method , self){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{
						
					}
				}).position(self);
			},
			
			_del : function( id , item , self){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&ajax=1&a=delete';
					$.get( url, {id : id } ,function( data ){
						if( data['callback']){
							eval( data['callback']);
							return false;
						}else{
							item.remove();
						}
					} , 'json');
				};
				this._remind( '是否要删除此内容?', '删除提醒' , method , self);
			},	
			
			_checkPlay : function( event ){
				var self = $(event.currentTarget),
					item = self.closest('.cinema-each'),
					id = self.attr('_id');
				if( item.hasClass( 'current' ) ){
					item.removeClass( 'current' );
				}else{
					item.addClass( 'current' );
				}
			},
			
			_checkall : function( event ){
				var self = $( event.currentTarget ),
					item = this.element.find('.cinema-each');
				if( self.is(':checked') ){
					item.addClass( 'current' );
				}else{
					item.removeClass( 'current' );
				}
			}		
		});
	})($);
	$('.cinema-wrap').cinema_list();
});