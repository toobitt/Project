define(function( require, exports, modules ){
	require('utils/zepto.numSelect');
	var utils = require('utils/utils'),
		param = utils.getParam(),
		tpl = require('utils/order_submit/template'),
		spinner = require('utils/spinner');
	param.gid = param.gid || 5;
	spinner.show();
	utils.getAjax('team_detail', param, function( json ){	//订单信息
		if( json.status == 1 ){
			var data = json.data;
			data.price = data.team_price; 
			var html = utils.render( tpl.shopping, data ),
			 	target = $('.content-box');
			 $( html ).prependTo( target );
			 asyncApi();
			 $('.num-select-box').numBox().on({
				update : function( event, num ){
					var point_dom = $('.price-account').find('.num'),
						sing_sale = point_dom.attr('_single');
					var all_price = (sing_sale * num).toFixed(2);
					point_dom.html( all_price );
					$('.price-account').find('.sale').attr('_sale', all_price);
				}
			});
		}else{
			utils.showTips( json.msg );
		}
	});	
	
	function asyncApi(){
		require.async('utils/api', function(){
			var target = $('.remark-block').show();
				submitBtn = $('.submit-button').show().find('.button');
			$.userInfo = new Api({
					onlyTokenKey : function( userInfo ){
						 submitBtn.removeClass('button-disable');
						 spinner.close();
						if( userInfo && userInfo.userid && userInfo.userTokenKey ){
							var user_id = userInfo.userid;
							submitAjax( user_id );
							if( userInfo.telephone ){
								userInfo.mobile = userInfo.telephone;
								userTelBack( userInfo );
								return;
							}
							utils.getAjax('user', {uid : user_id}, function( json ){			//个人信息
								if( json.status == 1 ){
									var data = json.data;
									if( $.isArray( data ) && data[0] ){
										userTelBack( data[0] );
									}
								}else{
									utils.showTips( json.msg );
								}
							});
						
						}else{
							submitBtn.addClass('goCenter').removeClass('button-disable').html( '请登录后再抢购' );
							require.async('utils/api', function(){
								var client = new Api({
									goUcenterBtn : $('.submit-button').find('.goCenter'),
									onlyClick : true
								});
							});
						}
					}
			});
		});
	}
	
	function userTelBack( data ){
		var html = utils.render( tpl.usertpl, data ),
		 	target = $('.remark-block');
		 var telephone = $( html ).insertBefore( target );
		 telephone.on('click', '.touch-modify', function(){
		 	var input = $(this).closest('.item-content').find('#M_buyer');
			input[0].disabled = false;
		 });
	}
	
	require('framework7');
	var app = new Framework7();
	
	function submitAjax( user_id ){
		$('.submit-button').on('click', '.button', function(){
			var $this = $(this);
			if( $this.hasClass('button-disable') || $this.hasClass('goCenter')){
				return;
			}
			var selectBox = $('.page-content').find('.select-block'),
				tel = $('.telephone-block').find('input[name="mobile"]').val();
			var reg = /^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/,
				str;
			if( !tel ){
				str = '请输入手机号码';
			}else if( !reg.test(tel.trim()) ){
				str = '手机号码格式不正确';
			}
			if( str ){
				utils.showTips( str );
				return;
			}
			spinner.show( $this );
			$this.addClass('button-disable');
			var info = {
				item_id : param.team_id,
				quantity : selectBox.find('.num-select-box').find('input[name="goods_number"]').val(),
				user_id : user_id,
				mobile : tel,
				remark : $('.page-content').find('.remark-block textarea').val()
			},
				single = selectBox.find('.price-account .num').attr('_single');
			utils.getAjax('submit_order', info, function( json ){		//提交
				$this.removeClass('button-disable');
				spinner.close();
				if( json.status == 1 ){
					var order = json.data && json.data.order;
					if( order ){
						var data = {
							title : selectBox.find('.title-text').html(),
							price : order.origin,
							product : selectBox.find('.title-text').attr('_des'),
							team_price : order.price,
							quality : order.quantity,
							type : 'confirm',
							service : order.service || 'alipay',
							pay_id : order.order_id 
						}
						var html = utils.render( tpl.shopping, data ),
						 	target = $('.popup-confirm');
						target.find('.pay-button').show().find('.button').removeClass('button-disable');
					 	target.find('.select-block').remove();
					 	target.find('.alipay-block').remove();
						$( html ).prependTo( target );
						utils.showTips('订购成功');
						setTimeout(function(){
							if( single == 0 ){
								location.href = $this.attr('_href') + '&order_id=' + order.order_id + '&gid=' + param.gid;
							}else{
								app.popup('.popup-confirm');
							}
						}, 1000);
					}
				}else{
					utils.showTips( json.msg );
				}
			}, 'post');
			$this.addClass('button-disable');
		});
	}
	
	$('.popup-confirm').find('.pay-button').on('click', '.button', function(){
		var target = $('.popup-confirm');
		$.userInfo.goToPay( {
			pay_type : 'appALiPay',	//支付宝类型
			pay_id : target.find('input[name="pay_id"]').val(),
			backUrl : $(this).attr('_href') + '?team_id=' + param.team_id
		} );
	});
});

define('utils/order_submit/template', function( require, exports, modules ){
	var tpl = {
		shopping : '' +
			'<div class="content-block item-block select-block">' +
				'<div class="block-title m2o-overflow"><span class="title-text" _des="{{product}}">{{title}}</span>{{if type == "confirm"}}<i class="icon icon-close close-popup modal-close" style="display:none; ">关闭</i>{{/if}}</div>' +
				'<div class="block-detail">' +
				'<ul class="list clear">' +
					'<li class="m2o-flex">' +
						'<div class="label m2o-flex-one">数量</div>' +
						'{{if type == "confirm"}}' +
							'<div class="num">{{quality}}</div>' +
						'{{else}}' +
							'<div class="num-select-box"></div>' +
						'{{/if}}' +
					'</li>' +
					'<li class="m2o-flex">' +
						'<div class="label m2o-flex-one">总价</div>' +
						'<div class="price-account"><span class="sale" _sale="{{price}}">¥<em class="num" _single="{{team_price}}">{{price}}</em></span></div>' +
					'</li>' +
				'</ul>' +
				'</div>' +
			'</div>' +
			'{{if type == "confirm"}}' +
				'<div class="list-block item-block alipay-block">' +
					'<ul>' +
						'<li> ' +
						'<label class="label-radio">' +
						'<input type="radio" name="alipay" value="支付宝" {{if service == "alipay"}}checked="checked"{{/if}}>' +
						'<div class="item-inner">' +
							'<i class="icon icon-alipay"></i>' +
						'</div>' +
						'</li>' +
						'<input type="hidden" class="pay_id" name="pay_id" value="{{pay_id}}" />' +
					'</ul>' +
				'</div>' +
			'{{/if}}' +
			'',
		usertpl : '' +
			'<div class="content-block item-block telephone-block">' +
				'<div class="list-block">' +
				'<div class="item-content">' +
					'<div class="item-title label">手机号码</div>' +
					'<div class="item-input">' +
						'<input id="M_buyer" disabled="" name="mobile" type="tel" placeholder="手机号码" value="{{mobile}}" />' +
					'</div>' +
					'<div class="item-after touch-modify">轻触修改</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
			''
	};
	modules.exports = tpl;
});
