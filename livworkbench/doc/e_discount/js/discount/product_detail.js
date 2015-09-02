define(function( require, exports, modules ){
	var tpl = require('utils/detail/template'),
		spinner = require('utils/spinner');
	var utils = require('utils/utils'),
		param = {
			team_id : utils.getParam().team_id
		};
	// require('framework7');
	// var app = new Framework7();
	spinner.show();
	utils.getAjax('team_detail', param, function( json ){
		if( json.status == 1 ){
			var data = json.data;
			data.systemreview = utils.escape2Html( data.systemreview );
			var html = utils.render( tpl.shoppingDetail, data ),
				target = $('.content-box');
			
			var newDom = $('<div class="newDom">' + html + '</div>')
			if( newDom.find('img').length ){
				newDom.find('.distance-block').find('img').each(function(){
					var $this = $(this).addClass('imgDetail'),
						father = $this.closest('p').length ? $this.closest('p') : $this.closest('div');
					father.addClass('makeImgCenter');
				});
				var lazyload = require('discount/img_lazyload');			//懒加载
				lazyload( newDom.find('img') );
			}	
			
			target[0].innerHTML = newDom[0].innerHTML;
			spinner.close();
			
			// require.async('discount/browser', function( browser ){		//预览
				// browser( app, $('.distance-block'), 'product' );
			// });
		}else{
			utils.showTips( json.msg );
		}
	});
});

define('utils/detail/template', function( require, exports, modules ){
	var tpl = {
		shoppingDetail : '' +
			'{{if systemreview}}' +
				'<div class="content-block item-block distance-block">' +
					'<div class="block-title"><span class="title-text">产品介绍</span></div>' +
					'<div class="block-detail">' +
						'{{#systemreview}}' +
					'</div>' +
			'{{/if}}' +
			'',
	}
	modules.exports = tpl;
});