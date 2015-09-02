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
	 				currentText: '当前',
	 				closeText: '确定'			    
	 		};
	 		var options = $.extend( defaultOption,option );
	 		return this.each( function(){
		 		var hasTime = $(this).attr( '_time' );
		 		if( hasTime ){
		 			$(this).datetimepicker( options );
		 		}else{
		 			$(this).datepicker( options );
		 		}
	 		} );
	 	};
	})($);
	
});