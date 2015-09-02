define(function( require, exports, modules ){
	var utils = require('utils/utils'),
		tpl = require('utils/index/template'),
		config = require('config');
	var options = {
		list : $('.refer-index').find('.list-block ul'),
	};
	
	// require.async('utils/h5Client', function(){
		// hgClient.getUserInfo(function( response ){		//获取用户登录信息
			// var userInfo = response && (response.userInfo || response) || '';
			// if( userInfo && (userInfo.userid || userInfo.userId) ){
				// refer();
			// }else{
				// hgClient.goLogin();
			// }
		// });
	// });
	
	refer();
	
	function refer(){
		if( $.isArray( config.refer ) && config.refer[0] ){
			var html = utils.render(tpl, {
					list : config.refer,
					hasdata : true
				});
			options.list.append( html );
		}else{
			var html = utils.render(tpl, {
					tip : '暂无查询项',
					hasdata : false
				});
			options.list.after( html );
		}
	}
});

define('utils/index/template', function( require, exports, modules ){
	var tpl = '' +
		'{{if hasdata}}' +
			'{{each list as value ii}}' +
				'<li class="hg-flex hg-flex-center">' +
					'<div class="mark {{value.name}}">&nbsp;</div>' +
					'<div class="title hg-flex-one">{{value.title}}</div>' +
					'{{if value.src}}<a class="outlink" href="{{value.src}}?_ddtarget=push"></a>{{/if}}' +
				'</li>' +
			'{{/each}}' +
		'{{else}}' +
			'<p class="nodata">{{tip}}</p>' +
		'{{/if}}' +
		'';
	modules.exports = tpl;
});