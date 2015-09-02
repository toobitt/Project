function initSlide(){
        var myswiper = myswiper || null;
        if( myswiper ){
            myswiper.removeAllSlides();
        }
        var slider_el = $('.swiper-container');
        if( slider_el.length ){
            myswiper = new Swiper( '.swiper-container' ,{
	          pagination: '.pagination',
	          loop:false,
	          grabCursor: true,
	          paginationClickable: true
           });
        }
}

function initScrollEvent(){
	$(window).on('scroll',function(){
		var top = $(this).scrollTop(),
			indexpic_head_h = $('.contentpic－placeholder').height(),
			real_h = indexpic_head_h,
			price_dom = $('.price-placeholder');
		if( top >= real_h ){
			price_dom.addClass('fixed');
		}else{
			price_dom.removeClass('fixed');
		}
	});
}


function clearIOSStyle(){
	if (window.news) {
		var iosstyle = $('#iosstyle');
		if( iosstyle.length ){
			iosstyle.remove();
		}
	}
}

$( function(){
	initSlide();
	clearIOSStyle();
} );

