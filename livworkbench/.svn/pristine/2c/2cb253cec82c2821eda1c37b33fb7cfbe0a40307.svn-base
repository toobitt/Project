define(function( require, exports, modules ){
	var tpl = require('utils/detail/template');
	var utils = require('utils/utils'),
		param = {
			team_id : utils.getParam().team_id
		};
	
	require('framework7');
	var eval_star = require('discount/eval_star');		//评价
	var app = new Framework7(),
		slider = require('discount/slider'),
		browser = require('discount/browser');
	
	var isActive = true;
	utils.getAjax('team_detail', param, function( json ){
		if( json && json.status == 1 ){
			var data = json.data;
			data.detail = utils.escape2Html( data.detail );
			data.notice = utils.escape2Html( data.notice );
			data.userreview = utils.escape2Html( data.userreview );
			data.endString = utils.transdate( data.end_date );
			data.star = new Array(5);
			
			
			var html = utils.render( tpl.shoppingDetail, data ),
			 	target = $('.content-box');
			var price_html = utils.render( tpl.price_tpl, data ),
				price_target = $('.area-price'); 
			$( html ).prependTo( target );
			$( price_html ).appendTo( price_target );
			
			$('.item-block').find('.block-link').each(function(){
				$(this)[0].href += ('?team_id=' + param.team_id);
			});
			
			eval_star( true, data.score );
			require('discount/foryou');			//为您推荐
			
			$( 'img' ).on('error', function( e ){
				var self = $(e.target);
				self[0].src = 'images/imgdefault.png';
			});
			
			require('utils/api');
			var client = new Api({
				makeTelBtn : $('.tele-icon'),
				goShareBtn : $('.map-icon'),
				onlyTokenKey : function(){
					
				}
			});
			
			/*活动倒计时*/
			execute();
			setInterval(execute.bind( this ), 60000);
			
			if( $('.slider-init').length ){
				slider( app );
			}
			browser( app, $('.distance-block'), 'merchant' );

			if( $('.button-rush').length && isActive){
				var name = $('.content-detail').find('.title').html();
				$('.button-rush').removeClass('button-disable');
				$('.button-rush')[0].href += ('?team_id=' + param.team_id);
			}
			
		}else if( json && json.msg ){
			utils.showTips( json.msg );
		}
	});
	
	function execute(){
		var clock = $('.gold-clock').find('.gold-click');
		var end_str = clock.attr('end_date'),
			cur_str = Date.now();
		if( end_str.toString().length == 10 ){
			end_str = end_str * 1000;
		}
		if( end_str - cur_str < 0 ){
			isActive = false;
			$('.order-button').find('.button').addClass('button-disable').html('活动已截止');
			clock.html('活动时间已截止');
		}else{
			isActive = true;
			var limit = (end_str - cur_str) / 1000,
				date = utils.limitTime( limit );
			clock.html( date.day + '天' + date.hour + '时' + date.minute + '分钟' );
		}
	}
	
	$('.button-rush').on('click', function( event ){
		if( $(this).hasClass('button-disable') ){
			event.preventDefault();
		}
	});
	
});

define('utils/detail/template', function( require, exports, modules ){
	var tpl = {
		shoppingDetail : '' +
			'<div class="content-detail">' +
				'<div class="item-content">' +
					'<div class="inner {{if !pic[0]}}noImage{{/if}}">' +
						'{{if pic[0]}}' +
						'<div class="img-box">' +
							'<div class="slider-container slider-init">' +
							'<div class="slider-pagination"></div>' +
								'<div class="slider-wrapper">' +
									'{{each pic as value ii}}' +
									'<div class="slider-slide"><img src="{{value}}"></div>' +
									'{{/each}}' +
									'{{each pic as value ii}}' +
									'<div class="slider-slide"><img src="{{value}}"></div>' +
									'{{/each}}' +
								'</div>' +
							'</div>' +
						'</div>' +
						'{{/if}}' +
						'<div class="info m2o-flex m2o-flex-center">' +
							'<div class="m2o-flex-one item-icon tele-icon" _tel="{{merchants_call}}"><a><i class="icon icon-telorder"></i>电话预约</a></div>' +
							'{{if pic[0]}}<p class="item-info"><span class="active">1</span>/{{pic.length}}</p>' + 
							'{{else}}<p class="fenge"></p>' +
							'{{/if}}' +
							'<div class="m2o-flex-one item-icon map-icon" _address="{{merchants_address}}"><a><i class="icon icon-searchline"></i>查看线路</a></div>' +
						'</div>' +
					'</div>' +
					'<div class="item-detail">' +
						'<div class="trade">' +
							'<p class="title m2o-overflow">{{title}}</p>' +
							'<div class="item-score">' +
								'{{each star as vv i}}' +
									'{{if i + 1 > score}}' +
										'<em class="icon star {{if i < score}}half{{else}}dark{{/if}}">{{i + 1}}</em>' +
									'{{else}}' +
										'<em class="icon star">{{i + 1}}</em>' +
									'{{/if}}' +
								'{{/each}}' +
								'<em class="score">{{score}}</em>' +
							'</div>' +
						'</div>' +
						'<div class="brief">{{summary}}</div>' +
						
						'<div class="m2o-flex sale">' +
							'<div class="gold m2o-flex-one gold-sale">' +
								'<i class="icon icon-sale"></i><span class="gold-sale">已售{{current_point}}</span>' +
							'</div>' +
							'<div class="gold m2o-flex-one gold-clock">' +
								'<i class="icon icon-clock"></i><span class="gold-click" end_date={{endString}}>0</span>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		
			'{{if detail}}' +
				'<div class="content-block item-block order-block">' +
					'<div class="block-title"><span class="title-text">本单详情</span></div>' +
					'<div class="block-detail">' +
						'{{#detail}}' +
					'</div>' +
					'<a class="block-link external" href="./product-info.html">查看图文详情<i class="icon icon-next"></i></a>' +
				'</div>' +
			'{{/if}}' +
			
			'{{if userreview}}' +
				'<div class="content-block item-block distance-block">' +
					'<div class="block-title"><span class="title-text">商家信息</span></div>' +
					'<div class="block-detail">' +
						'{{#userreview}}' +
					'</div>' +
					// '<a class="block-link external" href="./merchant-info.html">查看商家详情<i class="icon icon-next"></i></a>' +
				'</div>' +
			'{{/if}}' +
			
			'{{if notice}}' +
				'<div class="content-block item-block tips-block">' +
					'<div class="block-title"><span class="title-text">购买须知</span></div>' +
					'<div class="block-detail">' +
						'{{#notice}}' +
					'</div>' +
				'</div>' +
			'{{/if}}' +
			'',
		price_tpl : '' +
			'<div class="item-price price-account"><span class="sale">¥<em class="num">{{team_price}}</em></span><em class="oldsale">{{market_price}}</em></div>' +
			''
	}
	modules.exports = tpl;
});