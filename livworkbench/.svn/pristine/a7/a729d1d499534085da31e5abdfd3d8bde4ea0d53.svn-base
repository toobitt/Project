$(function(){
	$(window).on('scroll', function(){
		var _top = $(this).scrollTop(),
			go_to_top = $('.go-to-top');
		go_to_top[ _top > 300 ? 'show' : 'hide' ]();
	});
	$('body').on('click','.nav-link',function(){
		var self = $(this),
			target = self.data('href'),
			target_body = $('#'+target);
		if( target_body.length ){
			var offset_top = target_body.offset().top - 90;
			$('body,html').animate({scrollTop: offset_top + 'px'},500);
		}
	});
})
