(function($){
 	$.fn.hg_switchable = function( option ){
 		var defaultOption = {
 			next : '.next',      //后一个按钮元素
 			prev : '.prev',      //前一个按钮元素
 			list : '.switch_list',
 			effect : 'scrollLeft',   //效果
 			easing: 'ease-in-out',
 			panels: 'li',
 			triggers:null,     //触发方式
			autoplay:false,   //自动运行	
			loop:true,   
			end2end:true,
		    steps: 1,    
			visible: 4, // important可视范围个数，当内容少于可视个数的时候不执行效果
			interval:3,				    
 		};
 		var options = $.extend( defaultOption,option );
 		var switch_list = $(this).find( options['list'] ),
 			switch_prev = $(this).find( options['prev'] ),
 			switch_next = $(this).find( options['next'] );
 		switch_list.switchable({
 			triggers:options['triggers'], 
		    effect: options['effect'],   
		    steps: options['steps'],    
		    panels: options['panels'],
		    easing: options['easing'],
		    visible: options['visible'], 
		    autoplay: options['autoplay'], 
		    loop: options['loop'],
		    interval: options['interval'],
		    end2end: options['end2end'],
		    prev: switch_prev,    
		    next: switch_next,
 		});
 	};
})($);
