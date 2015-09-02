$(function(){
	(function($){
		$.widget('xml.xml_list',{
			options : {
				url : ''
			},
			
			_create : function(){
				
			},
			
			_init : function(){
				this._initSwitch();
				this._initlist();
				this._on({
					'click .batch-open': '_open'
				});
			},
			
			_initSwitch : function(){
				var switchs = this.element.find('.common-switch'),
					_this = this;
				switchs.each(function(){
					var id = $(this).closest('.m2o-each').data('id');
					var val = $(this).hasClass( 'common-switch-on' ) ? 100 : 0;
					$(this).hg_switch({
						'value' : val,
						'callback' : function( event, value ){
							var is_default = 0;
							( value > 50 ) ? is_default = 1 : is_default = 0;
							_this._onOff($(this) , id , is_default);
						}
					});
				});
			},
			
			
			_onOff : function( self ,id, is_open ){
				var _this = this;
				var url = this.options.url;
				$.getJSON( url, {id : id, is_open : is_open} ,function( json ){
					//self.closest('.m2o-each').siblings().find('.common-switch').removeClass('common-switch-on').end().find('.ui-slider-handle').css({'left': '0%'});
				})
			},
			
			_initlist : function(){
				$.extend($.geach || ($.geach = {}), {
			        data : function(id){
			            var info;
			            $.each(data, function(i, n){
			               if(n['id'] == id){
			                   info = {
			                       id : n['id']
			                   }
			                   return false;
			               }
			            });
			            return info;
			        }
			    });
			    $('.m2o-each').geach();
				$('.m2o-list').glist();
			},
			
			/*批量开启与关闭*/
			_open : function( event ){
				var self = $(event.currentTarget),
					ids = this.element.find('.m2o-each').map(function(){
						var checked = $(this).find('input[type="checkbox"]').prop('checked');
						if( checked ){
							return $(this).data('id');
						}
					}).get().join(','),
					_this = this;
				if(!ids){
					var str = '请先选择操作对象';
					this._tip( self , str );
					return false;
				}else{
					var box = this.element.find('.m2o-each-list'),
						url = this.options.url,
						is_open = self.attr('_open'),
						data = {};
					data.id = ids;
					data.is_open = is_open;
					$.globalAjax( box , function(){
						return $.post(url , data , function( json ) {
							var switchs = _this.element.find('.common-switch');
							switchs.each(function(){
								if(is_open == 1){
									self.attr('_open' , 0 ).text('关闭');
									$(this).addClass( 'common-switch-on' );
									$(this).find('.ui-slider-handle').css({'left': '100%'});
								}else{
									self.attr('_open' , 1).text('开启');
									$(this).removeClass( 'common-switch-on' );
									$(this).find('.ui-slider-handle').css({'left': '0%'});
								}
							});
						},'json');
					} );
				}
			},
			
			_tip : function( item , str ){
				item.myTip({
					string : str,
					delay: 1500,
					dleft : 80,
				});
			},
		});
	})($);
	$('.m2o-list').xml_list({
		url : './run.php?mid=' + gMid + '&a=open' 
	});
});