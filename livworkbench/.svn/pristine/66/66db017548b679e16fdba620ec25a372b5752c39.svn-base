define(function( require, exports, modules ){
	var utils = require('utils/utils');
	utils.spinner.show();
	var config = require('config');
	var ajaxResult = require('refer/index/list');
		
	var options = {
		formbox : $('.login-form'),
		form : $('.loginForm'),
		login : $('.btn-area').find('.login'),
		config : {
			userId : '账号',
			password : '密码',
			deviceToken : '设备信息'
		},
		pattern : {
			userId : /^\w{6,16}$/,
			password : /^\w{6,16}$/
		}
	};
	
	require('base64');
	options.base64 = new Base64(); 
	
	require('toast');
	options.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	if( utils.getMobileDevice() == 'iOS' ){
		$('html').addClass('ios');
	}
	require('store');
	require('utils/h5Client');
	
	hgClient.getSystemInfo(function( response ){	//获取设备信息
		var systemInfo = response.deviceInfo || response;
		options.deviceToken = systemInfo.device_token || systemInfo.devicesToken || 11;
		store.set('cpf.system', systemInfo);
		options.form.find('input[name="deviceToken"]').val( options.deviceToken );
	});
	
	var cpfuser = store.get('cpf.user', '');
	if( cpfuser ){
		cpfuser = JSON.parse(options.base64.decode( cpfuser ));
		var curTime = new Date().getTime(),
			interval = curTime - cpfuser.sTime;
		var second = parseInt(interval/1000);
		if( second < 1200 ){
			ajaxResult( cpfuser, options.deviceToken );
			return false;
		}
	}
	
	var userId = utils.getParam('userId');
	if( userId ){
		options.form.find('input[name="userId"]').val( userId );
	}
	
	options.formbox.show();
	utils.spinner.close();
	options.login.on('click', function(){		//登录
		var $this = $(event.currentTarget),
			form = options.form.serializeArray();
		
		var param = doBefore( form );
		if( typeof param == 'string' ){
			options.toast.show( param );
			return false;
		}
		utils.spinner.show( $this );
		$this[0].disabled = true;
		utils.getAjax('login', param, function( data ){
			$this[0].disabled = false;
			utils.spinner.close();
			
			if( data && data.recode === '999997' ){
				location.href="./rebind.html?_ddtarget=push&userId=" + param.userId;

			}else if( data && (data.ErrorText || data.recode !== '000000' ) ){
				options.toast.show( data.ErrorText || data.msg );
				return false;
			}
			if( data && data.result && data.result.accname ){
				var userInfo = {
					accname : data.result.accname,
					accnum : data.result.accnum,
					userId : param.userId,
					sTime : new Date().getTime()
				};
				store.set('cpf.user', options.base64.encode( JSON.stringify( userInfo ) ));
				options.toast.show( '登录成功', function(){
					options.formbox.hide();
					options.form[0].reset();
					utils.spinner.show();
					ajaxResult( userInfo, options.deviceToken );
				} );
			}
		});
	});
	
	function doBefore( form ){
		var errorTip = '',
			param = {};
		$.each(form, function( _, vv ){
			if( !vv.value ){
				errorTip = '请输入' + options.config[ vv.name ];
				return false;
			}
			if( options.pattern[vv.name] && !options.pattern[vv.name].test( vv.value ) ){
				errorTip = '请正确填写' + options.config[ vv.name ];
				return false;
			}
			param[vv.name] = vv.value;
		});
		if( errorTip ){
			return errorTip;
		}
		return param;
	}
});

define('refer/index/list', function( require, exports, modules  ){	//请求公积金详情
	var options = {
			resultbox : $('.cpf-result'),
			unbindbtn : $('.btn-unbind'),
			formbox : $('.login-form')
		};
	var utils = require('utils/utils'),
		tpl = require('utils/index/template');
	
	require('toast');
	options.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	require('store');	
		
	function result( userInfo, deviceToken ){
		var param = {
			accname : userInfo.accname,
			accnum : userInfo.accnum,
			userId : userInfo.userId
		};		
		param.deviceToken = deviceToken;
		
		options.param = param;
		utils.getAjax('search', param, function( data ){
			var html = '';
			if( data && (data.ErrorText || data.recode && data.recode !== '000000') ){
				html = utils.render( tpl, {
					tip : data && (data.ErrorText || data.msg || '暂无公积金信息'),
					hasdata : false
				});
			}else if( data && $.isArray( data.result ) && data.result[0] ){
				var info = {};
				$.each( data.result, function( _, vv ){
					if( vv.title == '姓名' ){
						info.fullName = vv.info;
					}
					if( vv.title == '缴存余额' ){
						info.remaining = vv.info;
					}
				} );
				html = utils.render( tpl, {
					list : [info],
					hasdata : true
				});
			}
			options.resultbox.show().find('.list-block ul').append( html );
			utils.spinner.close();
		});
	}
	
	options.unbindbtn.bind('click', function(){
		var $this = $(this);
		var cb = function(){
			utils.spinner.show( $this );
			utils.getAjax('unbind', options.param, function( data ){
				if( data && (data.ErrorText || data.recode && data.recode !== '000000') ){
					options.toast.show( data.ErrorText || data.msg || '解绑失败' );
				}else if( data ){
					location.href="./rebind.html?_ddtarget=push&userId=" + options.param.userId;
					store.remove('cpf.user');
					
				}
				utils.spinner.close();
			});
		};
		options.toast.confirm('您确定要解除该绑定账号？', function( type ){
			type && cb();
		});
	});
		
	modules.exports = result;
});

define('utils/index/template', function( require, exports, modules ){
	var tpl = '' +
		'{{if hasdata}}' +
			'{{each list as value ii}}' +
			'<li class="hg-flex hg-flex-center">' +
				'<div class="title">{{value.fullName}}</div>' +
				'<div class="name hg-flex-one">缴存余额：{{value.remaining}}元</div>' +
				'<a class="outlink" href="./result.html?_ddtarget=push"></a>' + 
			'</li>' +
			'{{/each}}' +
		'{{else}}' +
			'<p class="nodata">{{tip}}</p>' +
		'{{/if}}' +
		'';
	modules.exports = tpl;
});