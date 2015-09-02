$(function(){
	(function($){
		var doms = $('.drawer-list');
		doms.each(function(){
			var dom = $(this)[0];
			dom.addEventListener('touchstart', function(e) {
				$(this).addClass('touched');
			});
			dom.addEventListener('touchend', function(e) {
				$(this).removeClass('touched');
			});
		});
	})($);
} );