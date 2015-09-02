$(function(){
	(function($){
		$.fn.addStackOrPage = function( option ){
			var self = this;
			var defaultOption = {
				url : './run.php?mid=' + gMid + '&a=',
				data : {},
				after : $.noop
			};
			var options = $.extend( defaultOption, option ),
				data = options['data'],
				url = options['url'];
			/*$.getJSON( url , data, function( data ){
				after.call( self, {
					data : data
				} );
			} );*/
			return this;
		};
})($);
});