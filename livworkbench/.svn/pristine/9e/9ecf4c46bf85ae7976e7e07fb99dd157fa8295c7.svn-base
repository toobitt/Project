define(function(require, exports, modules){
	var slider = function( app ){
		app.slider('.slider-init', {
			pagination : '.slider-pagination',
			autoplay : 3000,
			speed : 400,
			onTransitionStart : function( slider ){
				$('.content-detail').find('.active').html( slider.activeSlideIndex + 1 );
			},
			onClick : function( slider ){
				photoBrowser.open( slider.activeSlideIndex );
			}
		});

		/*幻灯预览*/
		var photos = [];
		$('.slider-init').find('.slider-slide').each(function(){
			photos.push( $(this).find('img').attr('src') );
		});
		var photoBrowser = app.photoBrowser({
			photos : photos,
			theme:'dark'
		});
	}
	modules.exports = slider;
});
