define(function(require, exports, modules){
	var tpl = require('utils/evalstar/template'),
		utils = require('utils/utils');
	var my = this;
	var options = {
		count : 1,
		tid : utils.getParam().team_id,
		page : $('.content-box')
	};
	var eval_star = function( style, score ){
		var score = score || utils.getParam().score || 0;
		if( !style ){
			my.spinner = require('utils/spinner');
			my.spinner.show();
		}
		utils.getAjax('eval_star', {tid : options.tid, page : 1}, function( json ){		//折扣列表
			if( !!json.ErrorText ){
				utils.showTips( json.ErrorText );
				return;
			}
			ajaxback( json, 'list', {
				style : style,
				score : score
			} );
		});
		
		options.page.on({
			'infinite' : function(){
				var page = options.page;
				if( page.find('.estimate-block .item').length < options.count ){
					return;
				}
				var offset = page.data('offset') || 1;
				
				if( offset == 'infinite' ){
					return;
				}
				offset += options.count;
	
				page.data('offset', offset);
				my.spinner.show();
				utils.getAjax('eval_star', {
					tid : options.tid,
					page : offset
				}, function( json ){
					ajaxback( json, 'infinite', {
						style : style,
						score : score
					} );
				});
			}
		});
	}
	
	function ajaxback( data, type, addition ){
		if( !addition.style ){
			my.spinner.close();
		}
		if( $.isArray( data ) && data[0] ){
			$.each(data, function(key, value){
				value.time = utils.transferTime( value.eva_time ).date;
			});
			
			if( addition.style && data.length > 5 ){
				data = data.slice(0, 5);
			}
		}
		var html = utils.render(tpl, {
			list : data,
			star : new Array(5),
			allstar : addition.score || 0,
			type : type, 
			style : addition.style
		}), 
			target = options.page, add_type = 'appendTo';
		if( type == 'list' && options.page.find('.recommend-block').length ){
			target = options.page.find('.recommend-block');
			add_type = 'insertBefore';
		}else if( type == 'infinite' ){
			target = options.page.find('.estimate-block .block-detail');
		}
		$( html )[add_type]( target );
		
		if( type == 'list' && addition.style ){
			if( options.page.find('.estimate-block a').length ){
				options.page.find('.estimate-block a')[0].href += ('&team_id=' + utils.getParam().team_id + '&score=' + addition.score);
			}
		}
		
		$( 'img' ).on('error', function( e ){
			var self = $(e.target);
			self[0].src = 'images/imgdefault.png';
		});
		if( (!data || !data.length) && type == 'infinite' ){
			options.page.data('offset', 'infinite');
		}
	}
	modules.exports = eval_star;
});


define('utils/evalstar/template', function( require, exports, modules ){
	var tpl = '' +
		'{{if type == "list"}}' +
		'<div class="content-block item-block estimate-block">' +
			'<div class="block-title m2o-flex"><span class="title-text">评价</span>' +
				'<div class="item-score">' +
					'{{each star as vv i}}' +
						'{{if i + 1 > allstar}}' +
							'<em class="icon star {{if i < allstar}}half{{else}}dark{{/if}}">{{i + 1}}</em>' +
						'{{else}}' +
							'<em class="icon star">{{i + 1}}</em>' +
						'{{/if}}' +
					'{{/each}}' +
					'<em class="score">{{allstar}}</em>' +
				'</div>' +
			'</div>' +
			'<div class="block-detail">' +
		'{{/if}}' +	
		'{{if list.length}}' +
			'{{each list as value ii}}' +
			'<div class="item">' +
				'<div class="brief m2o-flex">' +
					'<div class="telephone m2o-overflow">{{value.username}}</div>' +
					'<div class="time m2o-flex-one">{{value.time}}</div>' +
					'<div class="item-score">' +
						'{{each star as vv i}}' +
							'{{if i + 1 > value.star}}' +
								'<em class="icon star {{if i < value.star}}half{{else}}dark{{/if}}">{{i + 1}}</em>' +
							'{{else}}' +
								'<em class="icon star">{{i + 1}}</em>' +
							'{{/if}}' +
						'{{/each}}' +
					'</div>' +
				'</div>' +
				'<div class="content">{{value.eva_text}}</div>' +
				// '<ul class="list clear">' +
					// '<li><img src="images/shopping1.png"></li>' +
				// '</ul>' +
			'</div>' +
			'{{/each}}' +
		'{{else if type == "list"}}' +
		'<p class="noShopping">暂无评价信息</p>' +
		'{{/if}}' +
		'{{if type == "list"}}' +
			'</div>' +
			'{{if style && list.length}}' +
			'<a class="block-link external" href="./eval_star.html?_ddtarget=push">查看更多评价<i class="icon icon-next"></i></a>' +
			'{{/if}}' +
		'</div>' +
		'{{/if}}' +
		'';
	modules.exports = tpl;
});



