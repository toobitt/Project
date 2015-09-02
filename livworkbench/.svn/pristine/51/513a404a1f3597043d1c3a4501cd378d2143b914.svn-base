define(function(require, exports, modules){
	var tpl = require('utils/myorder/template'),
		utils = require('utils/utils');
	
	var options = {
		count : 1,
		pagesize : 10,
		tid : utils.getParam().team_id,
		page : $('.page-content'),
	};
	
	require.async('utils/api', function(){
		var userInfo = new Api({
			onlyTokenKey : function( userInfo ){
				options.uid = userInfo.userid;
				utils.getAjax('my_order', {uid : options.uid, offset : 1, count : options.pagesize}, function( json ){		//折扣列表
					ajaxback( json, 'list' );
				});
		
			}
		});
	});
	
	function ajaxback( data, type ){
		if( $.isArray( data ) && data[0] ){
			$.each(data, function(key, value){
				if( value.state == 'pay' && value.rstate == 'askrefund' ){
					value.pay_text = '退款中';
				}else if( value.state == 'berefund' ){
					value.pay_text = '已退款';
				}else if( value.state == 'unpay' ){
					value.pay_text = '未支付';
				}else{
					value.pay_text = '已支付';
				}
				//value.pay_text = (value.state == 'pay') ? '已支付' : '未支付';
				value.num = Math.round(value.origin / value.team_price);
			});
			var html = utils.render(tpl, {
				list : data,
			}),
			target = options.page.find('.orderlist-block ul');
			$( html ).appendTo( target );
		}else if(type == 'list'){
			$('<p class="noShopping">暂无订单信息</p>').appendTo( options.page.find('.orderlist-block ul') );
		}
		if( (!data || !data.length) && type == 'infinite' ){
			options.page.data('offset', 'infinite');
		}
	}
});


define('utils/myorder/template', function( require, exports, modules ){
	var tpl = '' +
		'{{each list as value ii}}' +
		'<li _id="{{value.oid}}">' +
			'<a class="m2o-flex m2o-flex-center external" href="./order-detail.html?team_id={{value.tid}}&order_id={{value.oid}}">' +
				'<p class="img-box">' +
					'<img src="{{value.image}}">' +
				'</p>' +
				'<div class="info-box m2o-flex-one">' +
					'<div class="name m2o-overflow">{{value.title}}</div>' +
					'<p class="all"><span class="price">总价：{{value.origin}}元</span><span class="num">数量：{{value.num}}个</span></p>' +
					'<div class="status{{if value.state == "pay"}} consume{{/if}}">{{value.pay_text}}</div>' +
				'</div>' +
			'</a>' +
		'</li>' +
		'{{/each}}' +
		'';
	modules.exports = tpl;
});



