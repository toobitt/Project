$(function(){
	(function($){
		$.widget('member.member_medal' , {
			options : {
				
			},
			
			_create : function(){
				
			},
			
			_init : function(){
				this._on({
					'click .list-config a' : '_operate'
				})
			},
			
			_operate : function(event){
				var self = $(event.currentTarget),
					_type = self.attr('_type'),
					txt = self.text(),
					box = this.element.find('.m2o-each-list'),
					obj = this.element.find('.m2o-each'),
					url = './run.php?mid=' + gMid + '&a=audit',
					_this = this,
					ids = obj.map(function(){
						if($(this).find('input[type="checkbox"]').prop('checked')){
							return $(this).data('id');
						}
					}).get().join(','),
					data = {
						id : ids,
						type : _type
					};
				if(!ids){
					var tip = '请先选中'+ txt +'的数据';
					this._myTip( self , tip );
					return false;
				}
				var method = function(){
					$.globalAjax(box, function(){
				        return $.getJSON(url,data,function(json){
					        if(json){
					        	obj.each(function(){
									if($(this).find('input[type="checkbox"]').prop('checked')){
										return $(this).remove();
									}
								})
						    }else{
						    	var tip = txt +'失败';
								_this._myTip( self , tip );
								return false;
						    }
				        });
				    });
				}
				this._remind( '您确定要'+ txt +'此内容?', txt+'提醒' , method );
			},
			
			_myTip : function( self , tip ){
				self.myTip({
					string : tip,
					delay: 1000,
					dtop : 0,
					dleft : 80,
				});
			},
			
			_remind : function( title , message , method ){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{}
				});
			}
		});
	})($);
	$('.common-list-content').member_medal();
});