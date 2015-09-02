$( function(){
	(function($){
		var doms = $('.foot-icon');
		doms.each(function(){
			var dom = $(this)[0];
			dom.addEventListener('touchstart', function(e) {
//				doms.removeClass('selected');
				$(this).addClass('touched');
			});
			dom.addEventListener('touchend', function(e) {
				$(this).removeClass('touched');
			});
		});
	})($);
} );
