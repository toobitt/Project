(function($){
    /*取色器组件 
     * 此插件依赖于jqueryfn/colorpicker
     */
	$.fn.hg_colorpicker = function( option ){
		var options = $.extend( {
			color : '',	//取色器初始颜色
			history : false,	//显示历史纪录色
			strings : '主色调,标准色,打开取色板,返回,返回取色板,历史纪录,无历史纪录',
			callback : null
		}, option );
		return this.each( function(){
			var color = $(this).data('color') || options.color;
			var	initBgcolor = function( el,color ){
				el.css( {'background-color' : color} );
			};
			initBgcolor( $(this), color );
			$(this).colorpicker( {
				color : color,
				history : options.history,
				strings : options.strings
			} ).on('change.color', function( event, color ){
				initBgcolor( $(this), color );
				if( options.callback ){
					options.callback && options.callback.call( this, color );
				}
			});
		} );
	}
})(jQuery);