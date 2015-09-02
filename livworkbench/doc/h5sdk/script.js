$(function(){
	$(window).on('scroll', function(){
		var _top = $(this).scrollTop(),
			go_to_top = $('.go-to-top');
		go_to_top[ _top > 300 ? 'show' : 'hide' ]();
	});
})
