define(function( require, exports, modules ){
	var tpl = require('utils/you_coupon/template'),
		utils = require('utils/utils');
	var you_coupon = function( target, oid ){
		utils.getAjax('my_coupon', { oid : oid }, function( json ){		//折扣列表
			if( json.status == 1 ){
				var data = json.data;
				if( data.page > 0 ){
					if( data.list.length ){
						$.each(data.list, function(key, value){
							value.end_time = utils.transferTime( value.expire_time ).date;
							value.consume_text = (value.consume == 'N') ? '未使用' : (value.consume == 'Y' ? '已使用' : '退款');
						});
						var html = utils.render( tpl, data );
						$( html ).insertAfter( target );
					}
				}
			}else{
				utils.showTips( json.msg );
			}
		});
	};
	modules.exports = you_coupon;
});

define('utils/you_coupon/template', function( require, exports, modules ){
	var tpl = '' +
		'<div class="content-block item-block discount-block">' +
			'<div class="block-title m2o-flex">' +
				'<span class="title-text">您的折扣券({{list.length}})</span>' +
			'</div>' +
			'<div class="block-detail">' +
			'<ul class="list clear">' +
				'{{each list as value ii}}' +
					'<li _id="{{value.id}}">' +
						'<div class="m2o-flex">' +
							'<div class="m2o-flex-one{{if value.consume == "Y"}} aluse{{/if}}">' + 
								'<p><label>券号：</label><em class="stylus">{{value.coupon_id}}</em></p>' +
								'<p><label>密码：</label><em class="stylus">{{value.secret}}</em></p>' +
							'</div>' +
							'<div class="end_time">' +
								'<p>{{value.consume_text}}</p>' +
								'<p>{{value.end_time}}</p>' +
							'</div>' +
						'</div>' +
					'</li>' +
				'{{/each}}' +
			'</ul>' +
			'</div>' +
		'</div>' +
		'';
	modules.exports = tpl;
});