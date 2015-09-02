$(function(){
	(function($){
		$.widget('feedback_result.feedback_result_list',{
			options : {
				process : ['待处理','已处理','未通过'],
				process_color : ['#8ea8c8','#17b202','#f8a6a6'],
			},
			
			_create : function(){
			},
			
			_init : function(){
				this._on({
					'click .m2o-process span' : '_process',
					'click .m2o-replay' : '_replayinfo',
					'click .replay-close' : '_close',
					'click .replay-btn' : '_reply',
					'input input[type="text"] , textarea' : 'checkinput',
				})
			},
			
			_process : function(event){
				var self = $(event.currentTarget);
					obj = self.closest('div .m2o-each'),
					_this = this,
					id = obj.attr('_id'),
					audit = self.attr('_process')==1 ? 2 : 1,
					url = './run.php?mid=' + gMid + '&a=process&id='+ id + '&process='+ audit +'&ajax=1';
				this.ajax( self , url , null, function( data ){
					var data = data[0];	
					process = data['process'],
					process_text = _this.options.process[process],
					process_color = _this.options.process_color[process];	
					self.text( process_text )
						.css({'color' : process_color })
						.attr('_process',process)
					    .attr('title',process_text);
				})
			},
			
			_replayinfo : function( event ){
				var self = $( event.currentTarget ),
					obj = self.closest('div .m2o-each'),
					_this = this,
					id = obj.attr('_id'),
					reply = self.find('.m2o-item').attr('_reply'),
					url = './run.php?mid=' + gMid + '&a=message&id='+ id + '&ajax=1';
				this.id = id;
				this.ajax( self , url , null, function( data ){
					if( data['callback'] ){
						eval( data['callback'] );
						return;
					}
					var info = {};
					info.img = _this.createImgsrc( data[0].uavatar );
					info.title = data[0].title;
					info.username = data[0].uname;
					data[0].messages && ( info.msg = data[0].messages );
					$('#replay-tpl').tmpl( info ).appendTo( '.feedback-result-list' );
					var top = _this.getTop( self );
					_this.element.find('.replay-box').css('top' , top);
					var scrollTop = _this.element.find(".replay-body")[0].scrollHeight; 
					_this.element.find(".replay-body").scrollTop( scrollTop ); 
					( reply == 1 ) && ( self.find('.m2o-item').attr('_reply' , 0 ).css('color' , '#8ea8c8') );
				})
			},
			
			getTop : function( self ){
				var sTop = self.offset().top,
					bHeight = $('.replay-box').height(),
					wTop = $(window).height();
				if( sTop + bHeight > wTop){
					var top = wTop-bHeight-60;
				}else{
					var top = sTop;
				}
				return top;
			},
			
			_close : function(){
				this.element.find('.replay-box').remove();
			},
			
			_reply : function( event ){
				var self = $( event.currentTarget ),
					type = self.attr('_type'),
					msg = self.prev('textarea').val(),
					a = (type==1) ? 'send_message' : 'add_message',
					url = './run.php?mid=' + gMid + '&a='+ a,
					data = {
							id : this.id , 
							message : msg
					};
				this.ajax( self , url , data , function( json ){
					$('<li class="replay-list server-replay clear">'+ msg +'</li>').appendTo('.list-box');
					self.prev('textarea').val('');
					(type== 0) && self.attr('_type' , 1);
				})
			},
			
			createImgsrc :function( data){						//图片src创建
				src = [data.host, data.dir, data.filepath, data.filename].join('');
				return src;
			},
			
			checkinput : function( event ){
				var self = $(event.currentTarget),
					txt = $.trim( self.val() );
				if( txt.substr(0, 1) == "@" ){
					alert('首字符不能是@');
					self.val('');
				}
			},
			
			ajax : function( box , url , parm , callback){
				$.globalAjax( box, function(){
					return $.getJSON( url, parm , function( data ){
						if( data['callback'] ){
							eval( data['callback'] );
							return;
						}
						else{
							callback( data );
						}
					});
				} );
			},
		});
	})($);
		$('.feedback-result-list').feedback_result_list();
});
	


	