define(function( require, exports, modules ){
	var tpl = require('utils/detail/template'),
		spinner = require('utils/spinner');
	var utils = require('utils/utils'),
		param = utils.getParam();
	param.gid = param.gid || 5;
	var isActive = true;
	spinner.show();
	
	utils.getAjax('team_detail', {team_id : param.team_id}, function( json ){
		var target = $('.content-box'),
			detail_str = detailInfo( json ) || '';
		
		if( detail_str ){
			var newDom = $('<div class="newDom">' + detail_str + '</div>');
			if( newDom.find('img').length ){
				newDom.find('.distance-block').find('img').each(function(){
					var $this = $(this).addClass('imgDetail'),
						father = $this.closest('p').length ? $this.closest('p') : $this.closest('div');
					father.addClass('makeImgCenter');
				});
				// var lazyload = require('discount/img_lazyload');			//懒加载
				// lazyload( newDom.find('img') );
			}	
			target[0].innerHTML = newDom[0].innerHTML;
		}
		$('.order-button').show();
				
		if( target.find('table').length ){
			target.find('table').removeAttr('style')
				  .removeAttr('width').find('td')
				  .removeAttr('width');
			target.find('table').wrap('<div class="e_table" />').find('td span').each(function(){
				var $this = $(this);
				if( !$this.find('span').length ){
					$this.text( $this.text().trim() )
				}
			});	 
		}
		
		if( $('.button-rush').length && isActive){
			var name = $('.content-detail').find('.title').html();
			$('.button-rush').removeClass('button-disable');
			$('.button-rush')[0].href += ('&team_id=' + param.team_id + '&gid=' + param.gid);
		}
		
		spinner.close();
		
		require('module/iscroll');
		var myScroll = new IScroll('.page-content', {
			mouseWheel: true,
			interactiveScrollbars: true,
			shrinkScrollbars: 'scale',
			fadeScrollbars: true,
			preventDefaultException: {tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/}
		});
		
		require.async('discount/eval_star', function( eval_star ){		//评价
			eval_star( true, json.data.score );
			!!myScroll && myScroll.refresh();
		});
		
		
		
		
		if( target.find('.item-info').attr('_num') > 1 ){
			// require('framework7');
			// var app = new Framework7();
			// require.async('discount/slider', function( slider ){
				// slider( app );
			// });
			
			require.async('module/swipe', function(){
				var swiper = new Swipe($('.slider-init')[0], {
					speed: 400,
					stopPropagation: true,
					disableScroll: true,
					continuous: true,
					auto: 3000,
					callback:function(pos) {
						if( target.find('.item-info').attr('_num') == 2 ){
							if( pos > 1 ){
								pos = pos - 2;
							}
						}
						$('.item-info').find('.active').html( pos + 1 );
					}
				});
			})
		}

		/*活动倒计时*/
		execute();
		setInterval(execute.bind( this ), 60000);
		
		var foryou = require('discount/foryou');
		require.async('utils/api', function(){		//打电话，调地图
			var goMapBtn = $('.map-icon');
			var client = new Api({
				makeTelBtn : $('.tele-icon'),
				goToMapBtn : goMapBtn,
				getGoMapInfo : function(){
					return {
						address : goMapBtn.attr('_address'),
						lng : goMapBtn.attr('_lng'),
						lat : goMapBtn.attr('_lat'),
						name : target.find('.item-content .title').html()
					}
				},
				goOutLink : $('.content-button').find('.button'),
				onlyTokenKey : function(){
					
				},
				needLocation : function( location ){
					var tujian = {};
					if( location.longitude && location.latitude ){
						tujian.longlat = [location.longitude, location.latitude].join(',');
					}
					if( json && json.data ){
						tujian.team_id = json.data.team_id || param.team_id;
					}
					utils.getAjax('foryou', tujian, function( data ){
						var tuijian_str = foryou( data, param ) || '';
						if( tuijian_str ){
							target.append( tuijian_str );
						}
						!!myScroll && myScroll.refresh();
					});
				}
			});
		});

	});
	
	function detailInfo( detail ){
		if( detail && detail.status == 1 ){
			var data = detail.data;
			data.detail = utils.escape2Html( data.detail );
			data.notice = utils.escape2Html( data.notice );
			data.userreview = utils.escape2Html( data.userreview );
			data.endString = utils.transdate( data.end_date );
			data.star = new Array(5);
			var longlat = data.longlat.split(',');
			data.lng = longlat[0];
			data.lat = longlat[1];
			data.gid = data.gid || param.gid;
			
			// data.pic = data.pic.concat( data.pic );
			// data.pic = data.pic.concat( data.pic );
			
			var price_html = utils.render( tpl.price_tpl, data ),
				price_target = $('.area-price'); 
			$( price_html ).appendTo( price_target );
			
			var html = utils.render( tpl.shoppingDetail, data );
			return html;
		}else if( detail && detail.msg ){
			utils.showTips( detail.msg );
			return;
		}
	}
	
	function execute(){
		var clock = $('.gold-clock').find('.gold-click');
		if( clock.length ){
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
								'<div class="swipe-wrap 1slider-wrapper">' +
									'{{each pic as value ii}}' +
									'<div class="wrap 1slider-slide"><img src="{{value}}"></div>' +
									'{{/each}}' +
								'</div>' +
							'</div>' +
						'</div>' +
						'{{/if}}' +
						'<div class="info m2o-flex m2o-flex-center">' +
							'<div class="m2o-flex-one item-icon tele-icon" _tel="{{merchants_call}}"><a><i class="icon icon-telorder"></i>电话预约</a></div>' +
							'{{if pic[0]}}<p class="item-info" _num="{{pic.length}}"><span class="active">1</span>/{{pic.length}}</p>' + 
							'{{else}}<p class="fenge"></p>' +
							'{{/if}}' +
							'<div class="m2o-flex-one item-icon map-icon" _address="{{merchants_address}}" _lng="{{lng}}" _lat="{{lat}}"><a><i class="icon icon-searchline"></i>查看线路</a></div>' +
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
		
			'{{if notice}}' +
				'<div class="content-block item-block tips-block">' +
					'<div class="block-title"><span class="title-text">购买须知</span></div>' +
					'<div class="block-detail">' +
						'{{#notice}}' +
					'</div>' +
				'</div>' +
			'{{/if}}' +
		
			'{{if detail}}' +
				'<div class="content-block item-block order-block">' +
					'<div class="block-title"><span class="title-text">本单详情</span></div>' +
					'<div class="block-detail">' +
						'{{#detail}}' +
					'</div>' +
					'<a class="block-link external" href="./product-info.html?_ddtarget=push&team_id={{id}}&gid={{gid}}">' + 
						'查看图文详情' +
						'<i class="icon icon-next"></i>' +
					'</a>' +
				'</div>' +
			'{{/if}}' +
			
			'{{if userreview}}' +
				'<div class="content-block item-block distance-block">' +
					'<div class="block-title"><span class="title-text">商家信息</span></div>' +
					'<div class="block-detail">' +
						'{{#userreview}}' +
					'</div>' +
					// '<a class="block-link external" href="./merchant-info.html?_ddtarget=push&team_id={{id}}&gid={{gid}}">查看商家详情<i class="icon icon-next"></i></a>' +
				'</div>' +
			'{{/if}}' +
			
			'',
		price_tpl : '' +
			'<div class="price-account"><span class="sale">¥<em class="num">{{team_price}}</em></span><em class="oldsale">{{market_price}}</em></div>' +
			''
	}
	modules.exports = tpl;
});