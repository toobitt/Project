define(function(require, exports, modules){
	var utils = require('utils/utils'),
		param = {
			team_id : utils.getParam().team_id
		};
	var tpl = require('utils/refund_tips/template'),
		spinner = require('utils/spinner');
	spinner.show();
	utils.getAjax('order_detail', {order_id : utils.getParam().order_id}, function( json ){
		spinner.close();
		$('.refund-title').show();
		if( json.status == 1 ){
			var data = json.data;
			if( $.isArray( data ) && data[0] ){
				var html = utils.render( tpl.order_detail, data[0] ),
				 	target = $('.content-detail');
				$( html ).appendTo( target );
				$('.refund-detail').show();
				$('.refund-button').find('.button').removeClass('button-disable');
			}
		}else{
			utils.showTips( json.msg );
		}
	});


	$('.refund-button').on('click', '.button', function( event ){
		var $this = $(this);
		if( $this.hasClass('button-disable') ){
			event.preventDefault();
			return;
		}
		var info = {
			order_id : utils.getParam().order_id,
			reason : $('.remark-block').find('textarea').val(),
			num : $('.select-block').find('.num').attr('_num')
		};
		if( !info.reason ){
			utils.showTips( '请填写退款原因' );
			return;
		}
		spinner.show( $this );
		utils.getAjax('refund', info, function( json ){
			spinner.close();
			if( json.status == 1 && json.data ){
				location.href = $this.attr('_href') + '&team_id=' + param.team_id;
			}else{
				utils.showTips( json.msg );
				$this.removeClass('button-disable');
			}
		})
		$this.addClass('button-disable');
	});
});


define("utils/refund_tips/template", function( require, exports, modules ){
	var tpl = {
		order_detail : '' +
			'<div class="content-block item-block select-block">' +
				'<div class="block-title">' +
					'<a class="flex-box m2o-flex m2o-flex-center" href="./shopping-detail.html?_ddtarget=push&team_id={{team_id}}">' +
						'<div class="m2o-flex-one m2o-overflow"><span class="title-text">{{title}}</span></div>' +
						'<div class="next"><i class="icon icon-next"></i></div>' +
					'</a>' +
				'</div>' +
				'<div class="block-detail">' +
					'<ul class="list clear">' +
						'<li class="m2o-flex">' +
							'<div class="label m2o-flex-one">数量</div>' +
							'<div class="num" _num="{{quantity}}">{{quantity}}</div>' +
						'</li>' +
						'<li class="m2o-flex">' +
							'<div class="label m2o-flex-one">总价</div>' +
							'<div class="item-price price-account"><span class="sale">¥<em class="num">{{origin}}</em></span></div>' +
						'</li>' +
					'</ul>' +
				'</div>' +
			'</div>' +
			'',
	}
	modules.exports = tpl;
});
