$.tabSelect = function( index, tabnav ){
	var selected = tabnav.find('.item').eq( index ).addClass('selected');
	selected.siblings().removeClass('selected');
	tabnav.find('.magic-line')
	.width(selected.width())
	.css('left', selected.position().left)
	.data('id', selected.attr('_id'));
}

$.slideBox = function( point, dom ){
	var num = (point == 'left' ? '-100%' : '0');
	$.each(dom, function(key, value){
		value.css( 'margin-left', num );
	});
}

$.getBack = function(){
	$.slideBox('right', dom)
}
