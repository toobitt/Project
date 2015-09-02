$(function(){
	(function($){
		$.widget('vote.vote_list',{
			options : {
			},
			
			_create : function(){
				this.status = ['待审核','已审核','已打回'];
				this.status_color = ['#8ea8c8','#17b202','#f8a6a6'];  
			},
			
			_init : function(){
				this._on({
					'click .common-status span' : '_status',
//					'click .m2o-switch .default-switch' : '_tip',
					'click .audit span' : '_audit',
				})
				this._initSwitch();
			},
			
			_initSwitch : function(){
				var switchs = this.element.find('.common-switch'),
					_this = this;
				switchs.each(function(){
					var id = $(this).closest('li').attr('_id');
					var val = $(this).hasClass( 'common-switch-on' ) ? 100 : 0;
					$(this).hg_switch({
						'value' : val,
						'callback' : function( event, value ){
							var is_on = 0;
							( value > 50 ) ? is_on = 1 : is_on = 0;
							_this._onOff(id, is_on);
						}
					});
				});
			},
			
			_onOff : function( id, is_on ){
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=open&ajax=1&id=' + id ;
				$.getJSON( url, {id : id, is_on : is_on} ,function( data ){
					if( data['callback'] ){
						eval( data['callback'] );
						return;
					}
				})
			},
			
			_audit : function(event){
				var self = $(event.currentTarget);
					obj = self.closest('li'),
					_this = this,
					id = obj.attr('_id'),
					audit = self.attr('_state'),
					url = './run.php?mid=' + gMid + '&a=audit&id='+ id + '&audit='+ audit +'&ajax=1';
				$.globalAjax( self, function(){
					return $.getJSON( url,function( data ){
						if( data['callback'] ){
							eval( data['callback'] );
							return;
						}else{
							var data = data[0];	
							status = data['status'],
							status_text = _this.status[status],
							status_color = _this.status_color[status];	
							self.text( status_text )
								.css({'color' : status_color })
								.attr('_state',status);
							var obj = self.closest('li').find('.default-switch');
							if(status == 2){
								obj.show().attr('title' , '请先审核');
							}else{
								obj.hide();
							}
						}
					});
				} );
			},
			
		
//			_tip : function(event){
//				var self = $(event.currentTarget);
//				self.myTip({
//					string : '请先审核',
//					delay: 1000,
//					dtop : 0,
//					dleft : -120,
//				});
//				event.stopPropagation();
//				return false;
//			},
			
//			_audit : function(event){
//				var self = $(event.currentTarget);
//				var status = self.attr('_status');
//				var obj = self.closest('li').find('.default-switch');
//				if(status == 2){
//					obj.hide();
//				}else{
//					obj.show().attr('title' , '请先审核');
//				}
//				
//			},
			
		});
	})($);
		$('.v_list_show').vote_list();
});
	


	