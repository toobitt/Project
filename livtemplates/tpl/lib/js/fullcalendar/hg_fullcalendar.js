( function($){
	
	$.fn.hg_fullCalendar = function( option ){
		var defaultOptions = {
				monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
				monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
				dayNamesShort: ['日','一','二','三','四','五','六'],
				header: {
					left: '',
					center: 'title'
				},
				theme : false,
				editable: false,
				droppable: true
		};
		var options = $.extend( {}, defaultOptions, option );
		return this.each( function(){
			$(this).fullCalendar( options );
		} );
	}
	
} )($);