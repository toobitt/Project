define(function( require, exports, modules ){
	var img_lazyload = function( dom, options ){
		var param = $.extend({
			srcStore : "data-original",
			cla : 'lazyload',
			scrollTarget : $('.page-content'),
			onerrorImgUrl : 'images/imgdefault.png'
		}, options);
		if( dom.length ){
			dom.each(function(){
				var $this = $(this),
					src = $this.attr('src');
				$this.addClass('lazyload').data('original', src).removeAttr('src');
			});
			require.async('lazyload', function( lazyload ){
				lazyload.init( param );
			});
		}
	};
	modules.exports = img_lazyload;
});
