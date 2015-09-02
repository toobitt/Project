define(function( require, exports, modules ){
	require('spin');
	var spinner = {
		show : function( target, opts ){
			if( $.spinner ){
				return;
			}
			target = target || $('.page-content');
			opts = $.extend({
				lines : 12,
        		length : 4,
        		width : 2,
        		speed : 1.4,
        		radius : 6,
        		color : '#999'
			}, opts);
			$.spinner = new Spinner( opts ).spin( target[0] );
		},
		close : function(){
			if( $.spinner ){
				$.spinner.stop();
				delete $.spinner;
			}
		}
	}
	modules.exports = spinner;
});