$(function(){
	var MC = $('.m2o-list'),
		options ={
			dom : ['.switch-host' , '.switch-status'],
			a : ['switch_host' , 'audit']
		},
		_this = this;
	var control = {
			init : function(){
				this.initlist();
				this.initswitch();
			},
			
			$: function(s) {
				return this.el.find(s);
			},
			
			initlist : function(){
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
			
			initswitch : function(){
				var _this = this;
				$.each( options.dom , function( key , value ){
					$(value).each(function(){
						var $this = $(this),
							obj = $this.parent();
						var id = $this.closest('.m2o-each').data('id');
						$this.hasClass('common-switch-on') ? val = 100 : val = 0;
						$this.hg_switch({
							'value' : val,
							'callback' : function( event, value ){
								var is_on = 0;
								( value > 50 ) ? is_on = 1 : is_on = 0;
								_this.onOff(id, obj, is_on , options.a[key]);
							}
						});
					});
				})
				
			},
			
			onOff : function(id, obj, is_on , a){
				var url = './run.php?mid=' + gMid + '&a=' + a;
				$.getJSON( url, {id : id, is_on : is_on} ,function( data ){
					if( data['callback']){
						eval( data['callback'] );
						return false;
					}
				});
			}
	}
	control.init();
});