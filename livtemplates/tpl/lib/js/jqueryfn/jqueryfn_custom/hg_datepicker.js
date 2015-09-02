/*
 * author zhangzhen
 * date 2014-1-1
 * 依赖jquery-ui、jquery-ui-timepicker-addon.js
 * 实例化调用方式 $('input').hg_datepicker();
 * 直接调用显示格式Y-m-d
 * 根据自定义属性_time为true 显示Y-m-d hh:mm; _second为true 显示Y-m-d hh:mm:ss
 * 
 * */
$(function(){
	(function($){
	 	$.fn.hg_datepicker = function( option ){
	 		var defaultOption = {
	 				monthNames : ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一','十二'],
	 				dayNamesMin : ['日','一','二','三','四','五','六'],
	 				dateFormat : "yy-mm-dd",
	 				timeText : '时间',
	 				hourText : '小时',
	 				minuteText : '分钟',
	 				secondText : '秒',
	 				currentText: '当前',
	 				closeText: '确定'			    
	 		};
	 		var datepickerCollection = { datepicker : 'datepicker', datetimepicker : 'datetimepicker' };
	 		var options = $.extend( defaultOption,option );
	 		return this.each( function(){
		 		var self = $(this),
		 			hasTime = self.attr( '_time' ),
		 			hasSecond = self.attr('_second'),
		 			datepicker = '';
		 		hasSecond && $.extend( options, { showSecond : true,timeFormat: 'hh:mm:ss' } );
		 		if( hasTime || hasSecond ){
		 			datepicker = 'datetimepicker';
		 		}else{
		 			datepicker = 'datepicker';
		 		}
		 		self[ datepickerCollection[datepicker] ]( options );
	 		} );
	 	};
	})($);
	
});