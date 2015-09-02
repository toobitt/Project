$(function(){
	(function($){
		$.widget('global.hg_switch',{
			options:{
				'switch-slide'  : '.switch-slide',
				'switch-item' : '.switch-item',
				'active' : 'common-switch-on',
				'on' : 'on',
				'off' : 'off',
				'value' :0
			},
			_create:function(){
			},
			_init:function(){
				this.myswitch = this.element.find(this.options['switch-slide']);
				var handlers={};
				handlers['click '+this.options['switch-item']]='_click';
				this._on(handlers);
				this._initSwitch();
			},
			_initSwitch:function(){
				var _this=this;
				var op=this.options;
				this.myswitch.slider({
					value: op['value'],
					slide:function(event,ui){
						var val = ui.value;
						_this._changeStatus(val);
					}
				}).on('slidestop',function(event,ui,val){
					    if( typeof val != 'undefined'){
					    	var val=val;
					    }else{
							var val=ui.value;					    	
					    }
						_this.myswitch.slider('value', val >= 50 ? 100 : 0);
						_this._changeStatus(val);
						_this._trigger('callback',null,[val]);   //回调函数
				});
			},
			_click:function(event){
				var val=$(event.currentTarget).data('number');
				this.myswitch.trigger('slidestop',[null,val]);
			},
			_changeStatus:function(val){
				var active=this.options['active'];
				if( val >= 50 ){
					!this.element.hasClass(active) && this.element.addClass(active);
				}else{
					this.element.hasClass(active) && this.element.removeClass(active);
				}
	        },
	        refresh:function(option){
	        	$.extend(this.options,option);
	        	this.myswitch.slider('value', this.options['value'] >= 50 ? 100 : 0);
				this._changeStatus(this.options['value']);
	        }
		});
	})($);
});
	