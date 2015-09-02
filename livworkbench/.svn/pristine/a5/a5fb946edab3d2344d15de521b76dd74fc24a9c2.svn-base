define(function( require, exports, modules ){
	var utils = require('utils/utils'),
		param = utils.getParam(),
		tpl = require('utils/pingjia_submit/template'),
		spinner = require('utils/spinner');
	spinner.show();
	
	if( param.eval == 'Y' ){
		utils.getAjax('coupon_pj', {cid : param.coupon_id}, function( json ){
			spinner.close();
			if( json.status == 1 ){
				if( $.isArray( json.data ) && json.data[0] ){
					var data = json.data[0];
				    data.score = data.star;
				    data.star = new Array(5);
				    data.eval_title = '评价内容';
				    data.time = utils.transferTime( data.eva_time );
				    data.eval_style = 'Y';
				    data.team_id = data.tid;
					var html = utils.render( tpl.shopping, data ),
					 	target = $('.content-box');
					 $( html ).prependTo( target );
				}else{
					utils.showTips( '无评价信息' );
				}
			}else{
				utils.showTips( json.msg );
			}
		});
	}else{
		utils.getAjax('team_detail', {team_id : param.team_id}, function( json ){	//订单信息
			spinner.close();
			if( json.status == 1 ){
				var data = json.data;
				data.image = data.large_image_url || 'images/imgdefault.png';
				data.eval_title = '写评价';
				data.eval_style = 'N';
				data.score = 0;
				data.price = data.team_price;
				data.team_id = data.id;
				data.star = new Array(5);
				var html = utils.render( tpl.shopping, data ),
				 	target = $('.content-box');
				 $( html ).prependTo( target );
				 $('.submit-button').show();
				 touch_pingjia();
				 submitAjax();
			}else{
				utils.showTips( json.msg );
			}
		});	
	}
	
	function touch_pingjia(){
		require('utils/device.min');
		var starBox = $('.item-unscore'),
			Etype = device && device.desktop() ? 'click' : 'touchstart';
		starBox.find('.star').on( Etype, function( event ){
			var self = $(event.currentTarget),
				index = self.index();
			starBox.find('.star').each(function( i ){
				if( i <= index ){
					$(this).removeClass('dark');
				}else{
					$(this).addClass('dark');
				}
			});
			starBox.attr('_star', index + 1);
		});
	}
	
	function submitAjax(){
		require('utils/api');
		$('.submit-button').on('click', '.button', function(){
			var $this = $(this);
			if( $this.hasClass('button-disable') ){
				return;
			}
			if( $('.item-unscore').attr('_star') == 0 ){
				utils.showTips('请先进行评价');
				return;
			}
			spinner.show( $this );
			$this.addClass('button-disable');
			var info = {
				id : param.coupon_id,
				star : $('.item-unscore').attr('_star'),
				eva_text : $('.page-content').find('.remark-block textarea').val()
			};
			$.goBack = new Api({
				onlyClick : true
			});
			utils.getAjax('submit_comment', info, function( json ){		//提交
				$this.removeClass('button-disable');
				spinner.close();
				if( json.status == 1 ){
					if( json.data && json.data.id ){
						utils.showTips( '评价成功' );
					}
				}else{
					utils.showTips( json.msg );
				}
				setTimeout(function(){
					$.goBack.goBack();
				}, 1500);
			}, 'post');
			$this.addClass('button-disable');
		});
	}
});

define('utils/pingjia_submit/template', function( require, exports, modules ){
	var tpl = {
		shopping : '' +
			'<div class="content-block pingjiainfo-block">' +
				'<a class="block-single m2o-flex" href="./shopping-detail.html?_ddtarget=push&team_id={{team_id}}">' +
					'<p class="img-box"><img src="{{image}}"></p>' +
					'<div class="info-box m2o-flex-one">' +
						'<div class="name m2o-overflow">{{title}}</div>' +
						'<div class="price-account"><span class="sale">¥<em class="num">{{price}}</em></span></div>' +
						'{{if eval_style == "Y"}}<div class="eval_time">{{time.date}} {{time.time}}</div>{{/if}}' +
					'</div>' +
				'</a>' +
			'</div>' +
			'<div class="content-block item-block goestimate-block">' +
				'<div class="block-single m2o-flex m2o-flex-center">' +
					'<div class="info">总体评价</div>' +
					'<div class="item-unscore m2o-flex-one" _star="{{score}}">' +
						'{{each star as vv i}}' +
							'{{if i + 1 > score}}' +
								'<em class="icon star {{if i < score}}half{{else}}dark{{/if}}">{{i + 1}}</em>' +
							'{{else}}' +
								'<em class="icon star">{{i + 1}}</em>' +
							'{{/if}}' +
						'{{/each}}' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div class="content-block item-block remark-block">' +
				'<div class="block-title"><span class="title-text">{{eval_title}}</span></div>' +
				'<div class="list-block">' +
					'<textarea name="remark" placeholder="可为空" {{if eval_style == "Y"}}disabled{{/if}}>{{eva_text}}</textarea>' +
				'</div>' +
			'</div>' +
			'',
	};
	modules.exports = tpl;
});
