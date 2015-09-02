$(function(){
	(function($){
		$.widget('tv.tv_list',{
			options : {
				'tv-list' : '.tv-list',				
				'tv-each' : '.tv-each',
				'current' : 'current',
				'reaudit':'.reaudit',				
				'del' : '.del',
				'edit' : '.edit',
				'batdelete' : '.batdelete',				
				'add-button' : '.add-button',
				'checkall' : '#checkAll',
				'batback' : '.batback',
				'bataudit' : '.bataudit',
				'video-file' : '.video-file',
				'updata-num' : '.updata-num',
				'total-num' : '.total-num',
				'num-equal' : 'num-equal',
				'tv-img' : '.tv-img',
				'tv-profile' : '.tv-profile',
				'tv-publish' : '.tv-publish',
				'loading' : '.loading'
			},
			_create : function(){
				this.status = ['','待审核','已审核','已打回'];
				this.status_color = ['','#8ea8c8','#17b202','#f8a6a6'];
				var widget = this.element;
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['bataudit'] ] = '_auditall';
				handlers['click ' + op['reaudit'] ] = '_auditPlay';
				handlers['click ' + op['batback'] ] = '_batback';
				handlers['click ' + op['del'] ] = '_delPlay';
				handlers['click ' + op['edit'] ] = '_uploadv';
				handlers['click ' + op['batdelete'] ] = '_delall';				
				handlers['click ' + op['tv-each'] ] = '_checkPlay';
				handlers['click ' + op['checkall'] ] = '_checkall';
				handlers['click ' + op['tv-publish'] ] = '_publish';
				this._on(handlers);
				this.uploadVod();          
			},	
			_publish : function( event ){
				var id = $(event.currentTarget).attr('_id');
				App.trigger('openColumn_publish', event, recordCollection.get(id), recordsView.get(id));
				event.stopPropagation();
			},
			uploadVod : function(){
				var op = this.options,
			    	widget = this.element,
				    _this= this;
				var videofile = widget.find( op['video-file'] );
				videofile.each(function(){		    
            	 var id = $(this).data('id');
            	  op['vodUrl'] = "run.php?mid="+ gMid + "&a=uploadTvEpisode&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass + "&tv_play_id=" + id;
                  $(this).ajaxUpload({
					   url : op['vodUrl'],
					   phpkey : 'episode',
					   type : 'video',
					   before: function(){
					     $(this).closest( op['tv-profile'] ).find( op ["loading"] ).show().css("left",'90px');
					    	$(".prevent-go").show();
					   },
					   after : function( json ){
					    	$(this).closest( op['tv-profile'] ).find( op ["loading"] ).hide();
					    	$(".prevent-go").hide();					    						    				   	
					    	_this._uploadVodAfter(json);
					   }
				    });
				});
            },	                
			equalNum : function(updatanum){
				var op = this.options,		
				    totalnum = this.item.find( op['total-num'] ).text();
				if(updatanum == totalnum){
					this.item.addClass( op['num-equal'] );
				}
			},	
			_uploadVodAfter : function( json ){
			   var data = json['data'];
			   var op = this.options,
			   	   num = data.index_num;
			   this.item.find( op['updata-num'] ).text(num);
			   this.equalNum( num );
		    },
			_back : function( ids, obj){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&a=audit' + '&op=3';
					$.get(url,{id : ids},function(){
						obj.find('.reaudit').text('已打回').css({'color':'#f8a6a6'}).attr('_status',3);
					});
				};
				this._remind( '您确认批量打回选中记录吗？', '打回提醒' , method );	
			},
			_batback : function(){
			 var ids = 
				$(".tv-list li.current").map(function(){
					return $(this).attr("_id");
				}).get().join(",");
				 if( !ids ){
					 this._remind( '请选择要打回的记录', '打回提醒');
					 return;
				 }
	        	var	object = $(".play-list li.current");
		          this._back( ids, object);
			},	
			_auditall : function(){
			 var ids = 
				$(".tv-list li.current").map(function(){
					return $(this).attr("_id");
				}).get().join(",");
			 if( !ids ){
				 this._remind( '请选择要审核的记录', '审核提醒');
				 return;
			 }
	        	var	object = $(".play-list li.current");
		          this.auditajax( ids, object);
			},	
			auditajax : function( ids, obj){
				var method = function(){
					var url = './run.php?mid=' + gMid + '&a=audit' + '&op=2';
					$.get(url,{id : ids},function(){
						obj.find('.reaudit').text('已审核').css({'color':'#17b202'}).attr('_status',2);
					});
				};
				this._remind( '您确认批量审核选中记录吗？', '审核提醒' , method );				
			},
			_auditPlay : function( event ){		
				var op = this.options,
					widget = this.element;		
				var self = $(event.currentTarget),
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
			_uploadv : function(event){
			   var op = this.options,
			   		self = $(event.currentTarget),
			   		item = self.closest( op['tv-each'] );
			   	this.item = item;
			   var input = item.find( op['video-file'] );
			   input.click();
			   event.preventDefault();
			   event.stopPropagation();
			   return false;
	    	},
			_delall : function( event ){
				var ids = 
				$(".tv-list li.current").map(function(){
					return $(this).attr("_id");
				}).get().join(",");
				if( !ids ){
					 this._remind( '请选择要删除的记录', '删除提醒');
					 return;
				 }
				var	item = $(".play-list li.current");
				this._del( ids, item );
				event.stopPropagation();
			},
			
			_delPlay : function( event ){
				var op = this.options,
					widget = this.element;
				var self = $(event.currentTarget),
					item = self.closest( op['tv-each'] ),
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
			_checkPlay : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					id = self.attr('_id'),
					url = './run.php?mid=' + gMid + '&a=form&id=' + id; 
				if( self.hasClass( op['current'] ) ){
					self.removeClass( op['current'] );
				}else{
					self.addClass( op['current'] );
				}
			},
			_checkall : function(){
				var widget = this.element,
					op = this.options;
				if($('#checkAll').is(':checked')){
					$( op['tv-each'] ).addClass( op['current'] );
				}else{
					$( op['tv-each'] ).removeClass( op['current'] );
				}
			}		
		});
	})($);
	$('.tv-wrap').tv_list();
});