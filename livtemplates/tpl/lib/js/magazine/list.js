function changeDescWithId ( ids, desc, msg ) {
	var idArray;
	ids = $.parseJSON( ids );
	idArray = (typeof ids === 'number' ? [ids] : ids);
	$.each( idArray, function (i, n) {
		$('#r_' + n).find('[desc=' + desc + ']').text( msg );
	});
}
function hg_audit_callback (jsonStr) {
	changeDescWithId( jsonStr, 'audit', '已审核' );
}
function hg_back_callback (jsonStr) {
	changeDescWithId( jsonStr, 'audit', '打回' );
}
/*控制悬停出现浮动框*/
$(function ($) {
	var timer = null;
	var width = 502; /*信息浮动框的宽度*/
	var delay = 500;

	function showInfo (event) {
		var li = $(this);
		timer = setTimeout( function () {
			timer = null;
			var offsetRight;
			li.addClass( 'hover' );
			offsetRight = $(window).width() - li.offset().left;
			if ( offsetRight < width  ) {
				li.find( '.record-info-box' ).addClass( 'align-right' );
			}
			hg_resize_nodeFrame();
		}, delay );
	}
	function hideInfo (event) {
		if ( timer ) {
			clearTimeout( timer );
		} else {
			$(this).removeClass( 'hover' )
				.find( '.record-info-box' ).removeClass( 'align-right' );
		}
	}
	$('.one-record').hover( showInfo, hideInfo );
});
/*控制点击选中*/
$(function ($) {
	var recordSet = $('.record-set'),
		records = $('.record-set .one-record'),
		checkbox = $('.record-set input:checkbox'),
		batCheckbox = $('.common-list-bottom input:checkbox');
	
	recordSet
		.on( 'click', '.one-record', function () {
			var input = $(this).toggleClass( 'current' ).find( 'input:checkbox' );
			input.prop( 'checked', !input.prop('checked') );
		})
		.on( 'click', '.record-add-btn', function () {
			location.href = $('#hg_page_menu').find('a').attr( 'href' )
		}); 
	batCheckbox.click(function () {
		var isChecked = batCheckbox.prop('checked');
		checkbox.prop('checked', isChecked);
		records[ (isChecked ? 'add' : 'remove') + 'Class' ]('current');
	});
});