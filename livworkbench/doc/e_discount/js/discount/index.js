define(function( require, exports, modules ){
	var utils = require('utils/utils'),
		tpl = require('utils/index/template');
	var options = {
		count : 1,
		page : $('.page-content')
	}
	
	utils.getAjax('discount_list', {			//折扣列表
		page : 1
	}, function( json ){
		ajaxback( json, 'list' );
	});
	
	function ajaxback( data, type ){
		if( $.isArray( data ) && data[0] ){
			$.each( data, function(key, value){
				if( value.small_image_url || value.large_image_url ){
					value.image = utils.small_image_url || value.large_image_url;
				}else{
					value.image = 'images/imgdefault.png';
				}
			});
			var html = utils.render(tpl, {
				list : data
			}),
				target = $('.discount-index').find('ul');
			if( type == 'refresh' ){
				target.empty();
			}
			target.append( html );
			$( 'img' ).on('error', function( e ){
				var self = $(e.target);
				self[0].src = 'images/imgdefault.png';
			});
		}else if(type == 'list'){
			$('<p class="noShopping">暂无商品信息</p>').appendTo( $('.content-shopping') );
		}
		if( (!data || !data.length) && type == 'infinite' ){
			$('.page-content').data('offset', 'infinite');
		}
	}
});

define('utils/index/template', function( require, exports, modules ){
	var tpl = '' +
		'{{each list as value ii}}' +
			'<li _id="{{value.id}}">' +
				'<a class="m2o-flex m2o-flex-center external" href="./shopping-detail.html?team_id={{value.id}}">' +
					'<p class="img-box">' +
						'<img src="{{value.image}}">' +
						'{{if value.lottery}}<em class="icon-appoint">免预约</em>{{/if}}' +
					'</p>' +
					'<div class="info-box m2o-flex-one">' +
						'<div class="name m2o-overflow">{{value.title}}</div>' +
						'<p class="all m2o-overflow">{{value.keywords}}</p>' +
						'<div class="m2o-flex">' +
							'<div class="price-list m2o-flex-one"><span class="sale">¥<em class="num">{{value.team_price}}</em></span><em class="oldsale">{{value.market_price}}</em></div>' +
							'<span class="selled">已售{{value.current_point}}</span>' +
						'</div>' +
					'</div>' +
				'</a>' +
			'</li>' +
		'{{/each}}' +
		'';
	modules.exports = tpl;
});