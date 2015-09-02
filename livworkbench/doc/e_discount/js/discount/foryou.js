define(function(require, exports, modules){
	var tpl = require('utils/foryou/template'),
		utils = require('utils/utils');
	function foryou_html( data, param ){
		if( $.isArray( data ) && data[0] ){
			$.each(data, function(kk, vv){
				vv.image = vv.image || 'images/imgdefault.png';
				vv.gid = param && param.gid || 5;
			})
		}else if( !!data.ErrorText ){
			utils.showTips( data.ErrorText );
			return;
		}
		var html = utils.render(tpl, {
			list : data
		});
		return html;
	}
	modules.exports = foryou_html;
});


define('utils/foryou/template', function( require, exports, modules ){
	var tpl = '' +
		'<div class="content-block item-block recommend-block">' +
			'<div class="block-title m2o-flex"><span class="title-text">为您推荐</span></div>' +
			'<div class="block-detail">' +
			'<ul class="list clear">' +
				'{{each list as value ii}}' +
					'<li _id="{{value.id}}">' +
						'<div class="recommend-wrapper">' +
							'<p class="img-box">' +
								'<img src="{{value.image}}">' +
								'<em class="distance">{{value.distance}}km</em>' +
							'</p>' +
							'<div class="name m2o-overflow">{{value.title}}</div>' +
							'<div class="item-price"><span class="sale">¥<em class="num">{{value.team_price}}</em></span><em class="oldsale">{{value.market_price}}</em>' +
							'</div>' +
							'<a class="external" href="./shopping-detail.html?_ddtarget=push&team_id={{value.id}}&gid={{value.gid}}">' +
							'</a>' +
						'</div>' +
					'</li>' +
				'{{/each}}' +
				'{{if list.length % 2 == 1}}' +
					'<li></li>' +
				'{{/if}}' +
			'</ul>' +
			'</div>' +
		'</div>' +
		'';
	modules.exports = tpl;
});



