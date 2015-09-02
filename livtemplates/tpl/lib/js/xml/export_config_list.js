$(function(){
	(function($){
		$.widget('xml.config_list',{
			options : {
			},
			
			_create : function(){
				
			},
			
			_init : function(){
				this._initSwitch();
				this._initlist();
				this._initpercent();
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
			
			_onOff : function( self ,id, is_default ){
				var _this = this;
				var url = './run.php?mid=' + gMid + '&a=default' ;
				$.getJSON( url, {id : id, is_default : is_default} ,function( json ){
					self.closest('.m2o-each').siblings().find('.common-switch').removeClass('common-switch-on').end().find('.ui-slider-handle').css({'left': '0%'});
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
			    $('.m2o-each').geach({
			    	 custom_delete : true,
			    	 deleteCallback : function(event){
			        	var self = $(event.currentTarget),
			        		url = './run.php?mid=' + gMid + '&a=delete&ajax=1',
			        		checked = self.closest('.m2o-option').find('input[type="checkbox"]').prop('checked'),
			        		obj = self.closest('.m2o-each'),
			        		id = obj.data('id');
			    		var data={};
			    		data.id = id;
			    		data.is_delete_file = checked ? 1 : 0;
			    		$.globalAjax( obj , function(){
			        		return $.getJSON( url, data ,function( json ){
									if(json['callback']){
										eval( json['callback'] );
										return;
									}else{
										obj.remove();
									}
								});
						});

			       },
			    });
				$('.m2o-list').glist();
			},
			
			_initpercent : function(){
				var _this = this;
				setInterval(function(){
					$.get('./run.php?mid=' + gMid + '&a=get_percent',function( json ){
						_this.element.find('.m2o-each').each(function(key , value){
							if( $(value).find('.common-switch').hasClass('common-switch-on')){
								$(value).find('.progress').css('width' , json + '%');
								$(value).find('.precent').text( json + '%') ;
							}
						})
					},'json')
				},'2000');
			}
		});
	})($);
	$('.m2o-list').config_list();
});