$(function() {
	  var s=$('#slider');
	   s.slider({
			value:0,
			step:100,
			min: 0,
			max: 500,
			animate: true,
			slide: function( event, ui ) {
				$('#info-'+ui.value).show().siblings().hide();
				$('#time-'+ui.value).addClass('hover').siblings().removeClass('hover');
			}
		});
		$('#info-'+s.slider('value')).show();
		$('#time-'+s.slider('value')).addClass('hover');
});