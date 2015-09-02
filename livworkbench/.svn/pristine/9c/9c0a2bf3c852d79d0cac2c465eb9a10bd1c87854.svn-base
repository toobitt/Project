define(function( require, exports, modules ){
	var utils = require('utils/utils');
	utils.spinner.show();
	var tpl = require('utils/index/template'),
		config = require('config');
		
	var options = {
		resultbox : $('.cpf-result'),
	};
	
	require('base64');
	options.base64 = new Base64(); 
	
	require('toast');
	options.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	require('store');
	var systemInfo = store.get('cpf.system');
	
	if( systemInfo ){
		options.deviceToken = systemInfo.device_token || systemInfo.devicesToken || 11;
	}
	
	
	var cpfuser = store.get('cpf.user', '');

	if( cpfuser ){
		cpfuser = JSON.parse(options.base64.decode( cpfuser ));
		ajaxResult( cpfuser );
	}
	
	function ajaxResult( userInfo ){	//请求公积金详情
		var param = {
			accname : userInfo.accname,
			accnum : userInfo.accnum,
			userId : userInfo.userId
		};		
		param.deviceToken = options.deviceToken;
		utils.getAjax('search', param, function( data ){
			var html = '';
			if( data && (data.ErrorText || data.recode && data.recode !== '000000') ){
				html = utils.render( tpl, {
					tip : data && (data.ErrorText || data.msg || '暂无公积金信息'),
					hasdata : false
				});
			}else if( data && $.isArray( data.result ) && data.result[0] ){
				html = utils.render( tpl, {
					list : data.result,
					hasdata : true
				});
			}
			options.resultbox.show().find('.list-block ul').append( html );
			utils.spinner.close();
		});
	}
});

define('utils/index/template', function( require, exports, modules ){
	var tpl = '' +
		'{{if hasdata}}' +
			'{{each list as value ii}}' +
			'<li class="hg-flex hg-flex-center">' +
				'<div class="title">{{value.title}}</div>' +
				'<div class="name hg-flex-one">{{value.info}}</div>' +
			'</li>' +
			'{{/each}}' +
		'{{else}}' +
			'<p class="nodata">{{tip}}</p>' +
		'{{/if}}' +
		'';
	modules.exports = tpl;
});