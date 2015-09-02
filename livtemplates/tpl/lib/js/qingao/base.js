/*
*			$("#render1").tabs({
*				'tabNav': '.tabs_nav',
*				'tabPanel': '.tabs_panel',
*				'start': 0,
*				'autoplay' :false,
*				'trigger': 'mouseover',
*				'active': 'active',
*				'delay': 5000
*			});
*/
;(function($){
	$.fn.tabs=function(options){
		var settings=$.extend({			
			'tabNav': '.tabs_nav',
			'tabPanel': '.tabs_panel',
			'trigger': 'click',
			'active': 'active',
			'start': 0,
			'autoplay': false,
			'delay': 5000
		}, options || {});

		return this.each(function(){
				var _this=$(this), current=index=0;
				 
				
				_this.tabNav=_this.find(settings.tabNav).children();
				_this.tabPanel=_this.find(settings.tabPanel);
				
				var totalNav =_this.tabNav.size();	
				
			
				if(totalNav != _this.tabPanel.size()){
					return false;
				}				
				
				_this.tabNav.css({"cursor": "pointer"});
				
				function ishow(index){	
				   if(index>=totalNav){
						index=0;
					}
					_this.tabNav.eq(index).addClass(settings.active).siblings().removeClass(settings.active);
					_this.tabPanel.eq(index).show().siblings().hide();
					current=index; 
					
					if(settings.autoplay){
						_this.tick=setTimeout(function(){ishow(current+1);},settings.delay);
					}				
				}
								
				if(settings.trigger =='click' || settings.trigger =='mouseover'){
					_this.tabNav.bind(settings.trigger,function(){	
						if(settings.autoplay){clearTimeout(_this.tick);}					
						index=$(this).index();
						ishow(index);					
					});
				}else{
					console.log("Event is not define");
				}				

				ishow(settings.start);			
		
		})				

	};
})(jQuery);