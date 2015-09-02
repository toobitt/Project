define(function( require, exports, modules ){
	var utils = require('utils/utils'),
		param = utils.getParam();
	param.gid = param.gid || 5;
	var tpl = require('utils/purchase_result/template'),
		foryou = require('discount/foryou'),
		spinner = require('utils/spinner');
	spinner.show();
	
	utils.getAjax('order_detail', {order_id : param.order_id}, function( json ){
		spinner.close();
		var detail_str = detailInfo( json ) || '';
		var target =$('.purchase-detail');
		target[0].innerHTML = detail_str + tpl.btn;
		$('.purchase-success').show();
		
		require.async('discount/you_coupon', function( you_coupon ){		//折扣券
			var goodblock = target.find('.good-block');
			you_coupon( goodblock, param.order_id );
		});
		
		var foryou = require('discount/foryou');
		
		require.async('utils/api', function(){
			var client = new Api({
				goOutLink : $('.content-button').find('.button'),
				onlyTokenKey : function(){
					
				},
				needLocation : function( location ){
					var tujian = {};
					if( location.longitude && location.latitude ){
						tujian.longlat = [location.longitude, location.latitude].join(',');
					}
					if( json && json.data && json.data[0] ){
						tujian.team_id = json.data[0].team_id || param.team_id;
					}
					utils.getAjax('foryou', tujian, function( data ){
						if( $.isArray( data ) && data[0] ){
							var tuijian_str = foryou( data, param ) || '';
							if( tuijian_str ){
								target.append( tuijian_str );
							}
						}
					});
				}
			});
		});
	});
	
	function detailInfo( detail ){
		if( detail && detail.status == 1 ){
			if( $.isArray( detail.data ) && detail.data[0] ){
				data = detail.data[0];
				var html = utils.render( tpl.shopping, data );
				return html;
			}
		}else if( detail && detail.msg ){
			utils.showTips( detail.msg );
			return;
		}
	}
	
});

define("utils/purchase_result/template", function( require, exports, modules ){
	var tpl = {
		shopping : '' +
			'<div class="content-block-title youshop">您的商品</div>' +
			'<div class="content-block item-block good-block">' +
				'<div class="block-single">' +
					'<a class="m2o-flex m2o-flex-center" href="./shopping-detail.html?_ddtarget=push&team_id={{team_id}}">' +
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
					'<div class="col-50"><a class="button button-border goOrder" _outlink="order#">我的订单</a></div>' +
					'<div class="col-50"><a class="button button-gradient goShopping" _outlink="zhekou#">继续看看</a></div>' +
				'</div>' +
			'</div>' +
			'',
	}
	modules.exports = tpl;
});
