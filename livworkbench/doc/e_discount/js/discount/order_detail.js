define(function( require, exports, modules ){
	var utils = require('utils/utils'),
		param = utils.getParam();
	param.gid = param.gid || 5;
	var tpl = require('utils/order_detail/template'),
		foryou = require('discount/foryou'),
		spinner = require('utils/spinner');
	spinner.show();
	
	utils.getAjax('order_detail', {order_id : param.order_id}, function( json ){
		spinner.close();
		var detail_str = detailInfo( json ) || '';
		var target = $('.content-box');
		target[0].innerHTML = detail_str;
		require.async('discount/you_coupon', function( you_coupon ){		//折扣券
			var goodblock = target.find('.good-block');
			you_coupon( goodblock, param.order_id );
		});
		
		var foryou = require('discount/foryou');
		
		require.async('utils/api', function(){
			var good_box = $('.good-block');
			$.good_box = new Api({
				goToPlayBtn : $('.goToPlay'),
				getGoPlayInfo : function(){
					return {
						origin : good_box.attr('_origin'),
						title : good_box.find('.name').html(),
						des : good_box.find('.name').attr('_des'),
						backUrl : $('.goToPlay').attr('_href') + '?team_id=' + param.team_id,
						pay_id : good_box.attr('_pay_id')
					}
				},
				onlyTokenKey : function(){
					
				},
				needLocation : function( location ){
					var tujian = {};
					if( location.longitude && location.latitude ){
						tujian.longlat = [location.longitude, location.latitude].join(',');
					}
					if( json && json.data && json.data[0]  ){
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
		var cancel_order = $('.cancel-order'),
			but_box = cancel_order.closest('.content-button');
		if( cancel_order.length ){
			var crttime =  but_box.attr('crt_time'),
				range = ( new Date().getTime() - crttime * 1000 )/ 1000,
				day = Math.floor( range / (24 * 60 * 60) ),
				hour = Math.floor( range % (24 * 60 * 60) /(60 * 60))
				inter_hour = day * 24 + hour;
			if( inter_hour >= 2 ){
				cancel_order.removeClass('button-disable');
			}
			cancel_order.on('click', function(){
				var $this = $(this),
					father = $this.closest('.content-button');
				var userid = father.attr('userid'),
					orderid = father.attr('orderid');
				if( $this.hasClass('button-disable') ){
					utils.showTips('亲，下单2小时内，不能取消订单哦！')
					return;
				}
				utils.getAjax('order_cancel', {order_id : orderid, user_id : userid}, function( json ){
					if( json && json.status == 1 ){
						if( json.data == orderid ){
							utils.showTips( '取消订单成功' );
							setTimeout(function(){
								$.good_box.goBack();
							}, 1500);
						}
					}else if( json && json.msg ){
						utils.showTips( detail.msg );
						return;
					}
				})
			});
		}
	});
	
	function detailInfo( detail ){
		if( detail && detail.status == 1 ){
			var data = detail.data;
			if( $.isArray( data ) && data[0] ){
				data = data[0];
				
				data.pay_time = (data.pay_time == 0) ? 0 : utils.transferTime( data.pay_time ).date;
				data.star = new Array(5);
				data.allstar = 0;
				
				var html = utils.render( tpl.shopping, data );
				return html;
			}
		}else if( detail && detail.msg ){
			utils.showTips( detail.msg );
			return;
		}
	}
});

define("utils/order_detail/template", function( require, exports, modules ){
	var tpl = {
		shopping : '' +
			'<div class="content-block-title youshop">您的商品</div>' +
			'<div class="content-block item-block good-block" _pay_id="{{id}}" _origin="{{origin}}" >' +
				'<div class="block-single">' +
					'<a class="m2o-flex m2o-flex-center" href="./shopping-detail.html?_ddtarget=push&team_id={{team_id}}">' +
						'<div class="info m2o-flex-one">' +
							'<div class="name m2o-overflow" _des="{{if product}}{{product}}{{else}}{{title}}{{/if}}">{{title}}</div>' +
						'</div>' +
						'<div class="next"><i class="icon icon-next"></i></div>' +
					'</a>' +
				'</div>' +
			'</div>' +
			// '<div class="content-block item-block goestimate-block">' +
				// '<div class="block-single">' +
					// '<a class="m2o-flex m2o-flex-center" href="./pingjia-submit.html?_ddtarget=push&id={{id}}&team_id={{team_id}}">' +
						// '<div class="info">未评价</div>' +
						// '<div class="item-score item-unscore m2o-flex-one">' +
							// '{{each star as vv i}}' +
								// '{{if i + 1 > allstar}}' +
									// '<em class="icon star {{if i < allstar}}half{{else}}dark{{/if}}">{{i + 1}}</em>' +
								// '{{else}}' +
									// '<em class="icon star">{{i + 1}}</em>' +
								// '{{/if}}' +
							// '{{/each}}' +
						// '</div>' +
						// '<div class="next"><i class="icon icon-next"></i></div>' +
					// '</a>' +
				// '</div>' +
			// '</div>' +
			'<div class="content-block item-block orderInfo-block">' +
				'<div class="block-title m2o-overflow"><span class="title-text">订单信息</span></div>' +
				'<div class="list-block">' +
					'<div class="item-content">' +
						'<div class="item-title label">订单号：</div>' +
						'<div class="item-input">{{id}}</div>' +
					'</div>' +
					'<div class="item-content">' +
						'<div class="item-title label">手机号：</div>' +
						'<div class="item-input">{{mobile}}</div>' +
					'</div>' +
					'<div class="item-content">' +
						'<div class="item-title label">付款时间：</div>' +
						'<div class="item-input">{{if pay_time}}{{pay_time}}{{else}}暂未支付{{/if}}</div>' +
					'</div>' +
					'<div class="item-content">' +
						'<div class="item-title label">数量：</div>' +
						'<div class="item-input">{{quantity}}</div>' +
					'</div>' +
					'<div class="item-content">' +
						'<div class="item-title label">总价：</div>' +
						'<div class="item-input">{{origin}}元</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div class="content-block content-button" crt_time="{{create_time}}" userid="{{user_id}}" orderid="{{id}}">' +
				'{{if pay_time}}' +
					'{{if (rstate == "normal" || rstate == "norefund") && allowrefund == "Y"}}' +
						'<a href="./refund-tips.html?_ddtarget=push&team_id={{team_id}}&order_id={{id}}" class="button button-mid button-border">退款</a>' +
					'{{else}}' +
						'<a class="button button-mid button-disable">{{if rstate == "askrefund"}}退款中{{else}}已退款{{/if}}</a>' +
					'{{/if}}' +
				'{{else}}' +
					'<a _href="./purchase-result.html" class="button button-mid button-gradient goToPlay">去支付</a>' +
					'<a class="button button-mid button-border button-disable cancel-order">取消订单</a>' +
				'{{/if}}' +
			'</div>' +
			'',
	}
	modules.exports = tpl;
});
