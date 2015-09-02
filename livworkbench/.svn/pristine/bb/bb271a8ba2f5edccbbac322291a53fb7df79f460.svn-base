define(function( require, exports, modules ){
	var you_coupon = require('discount/you_coupon');			//您的优惠券
	var utils = require('utils/utils'),
		param = {
			team_id : utils.getParam().team_id
		};
	var tpl = require('utils/refund_result/template'),
		foryou = require('discount/foryou'),
		spinner = require('utils/spinner');
	spinner.show();
	utils.getAjax('new_detail', param, function( json ){
		var detail = json && json.detail,
			tuijian = json && json.tuijian,
			target = $('.content-box');
		
		var detail_str = '',
			tuijian_str = '';
		if( !!detail ){
			var detail_str = detailInfo( detail ) || '';
		}
		if( !!tuijian ){
			var tuijian_str = foryou( tuijian ) || '';
		}
		var target = $('.refund-detail');
		target[0].innerHTML = detail_str + tpl.btn + tuijian_str;
		spinner.close();
		$('.purchase-success').show();
		
		require.async('utils/api', function(){
			var client = new Api({
				goOutLink : $('.content-button').find('.button'),
				onlyClick : true
			});
		});
	});	
	
	function detailInfo( detail ){
		if( detail && detail.status == 1 ){
			var data = detail.data;
			if( data.id ){
				var html = utils.render( tpl.shopping, data );
				return html;
			}
		}else if( detail && detail.msg ){
			utils.showTips( detail.msg );
			return;
		}
	}
});

define("utils/refund_result/template", function( require, exports, modules ){
	var tpl = {
		shopping : '' +
			'<div class="content-block-title youshop">您的商品</div>' +
			'<div class="content-block item-block good-block">' +
				'<div class="block-single">' +
					'<a class="m2o-flex m2o-flex-center" href="./shopping-detail.html?_ddtarget=push&team_id={{id}}">' +
						'<div class="info m2o-flex-one">' +
							'<div class="name m2o-overflow">{{title}}</div>' +
						'</div>' +
						'<div class="next"><i class="icon icon-next"></i></div>' +
					'</a>' +
				'</div>' +
			'</div>' +
			'',
		btn : '' +
			'<div class="content-block content-button">' +
				'<div class="row">' +
					'<div class="col-50"><a class="button button-border goOrder" _outlink="order#">返回列表</a></div>' +
					'<div class="col-50"><a class="button button-gradient goShopping" _outlink="zhekou#">继续看看</a></div>' +
				'</div>' +
			'</div>' +
			'',
	}
	modules.exports = tpl;
});
