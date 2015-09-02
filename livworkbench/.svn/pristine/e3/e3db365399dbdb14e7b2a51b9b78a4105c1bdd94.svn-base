define(function( require, exports, modules ){
	var $ = require('$');
	var utils = require('utils/utils'),
		param = utils.getParam();
	var select = $('.select-block');
	select.find('.title-text').html( decodeURI( param.name ) );
	select.find('.price-account .num').html( param.origin );
	select.find('.order-num .num').html( param.quantity );
	$('.alipay-block').find('input[name="pay_id"]').val( param.pay_id );
	
	$('.pay-button').find('.button').removeClass('button-disable');
	$('.pay-button').on('click', '.button', function(){
		console.log( $(this) );
		if( $(this).hasClass('button-disable') ){
			return;
		}
		location.href = $(this).attr('_href') + '?team_id=' + param.team_id + '&name=' + param.name;
	});
});
