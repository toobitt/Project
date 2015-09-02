/**
 * 实时字数统计插件
 * $('需要统计的dom').charCount();
 * 例子可参见m2o-移动app-添加消息-消息内容
 * */
(function($){
	$.fn.charCount = function(options){
		var defaults = {
				maxLen : 140,			//最大字符数
				wrap : '.count-info',	//提示信息的外围dom
				info : '.tip',			//当前状态提示dom
				count : '.count'		//字符数dom
		}
		
		var options = $.extend(defaults, options);
		
		function showTips(){
			$(options.wrap).show()
		}
		
		function calculate( obj ){
			var self = $(obj),
				words = self.val(),
				count = words.length,
				available = options.maxLen;
			var chi = words.replace(/[A-z]|[0-9]/g,''),
				eng = words.replace(/[^A-z|0-9]/g,'');
			var chiLen = chi.length,
				engLen = Math.ceil(eng.length / 2);
			available -= (chiLen + engLen);
			$(options.count).text(available);
			if( available < 0 ){
				var over = chiLen + engLen - options.maxLen;
				$(options.info).text('已经超过');
				$(options.count).css('color','#E44443').text(over);
			}else{
				$(options.info).text('还可以输入');
				$(options.count).css('color','#808080').text(available);
			}
		}
		
		$(this).each(function(){
			$(this).focus(function(){
				showTips();
			});
			$(this).keyup(function(){
				calculate(this);
			});
		});
	}
})($)