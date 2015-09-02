define(function( require, exports, modules ){
	var utils = require('utils/utils');
		config = require('config');
		require('toast');
		require('store');
	var options = {
		form : $('.registerForm'),
		register : $('.btn-area').find('.register'),
		config : {
			userId : '账号',
			password : '密码',
			repassword : '确认密码',
			mobileNumber : '手机号码',
			email : '电子邮件',
			checkid : '验证码',
			surplusAccount : '联名卡卡号',
			fullName : '姓名',
			idcardNumber : '证件号码',
			deviceToken : '设备信息号'
		},
		pattern : {
			userId : /^\w{6,16}$/,
			password : /^\w{6,16}$/,
			repassword : /^\w{6,16}$/,
			mobileNumber : /^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/,
			email : /^([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/,
			fullName : /^[\u4E00-\u9FA5]{1,}$/,
			idcardNumber : /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
			checkid : /^\w{4}$/
		}
	};
	require('base64');
	options.base64 = new Base64(); 
	
	options.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	if( utils.getMobileDevice() == 'iOS' ){
		$('html').addClass('ios');
	}
	
	require('store');
	
	var systemInfo = store.get('cpf.system');
	
	if( systemInfo ){
		options.form.find('input[name="deviceToken"]').val( systemInfo.device_token || systemInfo.devicesToken || 11 );
					
		var codeImg = options.form.find('.code-img').show().find('img');
		var vertifyUrl = utils.getAjax('vertify', 'url');
		codeImg[0].src = vertifyUrl + ('&deviceToken=' + systemInfo.device_token || systemInfo.devicesToken || 11);
		codeImg.on('click', function(){
			$(this)[0].src = vertifyUrl + ('&deviceToken=' + systemInfo.device_token || systemInfo.devicesToken || 11) + '&hash=' + Math.random();
		});
	}
	
	options.register.on('click', function(){
		var $this = $(event.currentTarget),
			form = options.form.serializeArray();
		
		var param = doBefore( form );
		if( typeof param == 'string' ){
			options.toast.show( param );
			return false;
		}
		utils.spinner.show( $this );
		$this[0].disabled = true;
		utils.getAjax('register', param, function( data ){
			$this[0].disabled = false;
			utils.spinner.close();
			if( data && (data.ErrorText || data.recode && data.recode !== '000000') ){
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
				options.toast.show( '注册成功', function(){
					location.href="./index.html?_ddtarget=push";
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
			if( vv.name == 'repassword' ){
				if( vv.value !== param.password ){
					errorTip = '确认密码请和密码一致';
					return false;
				}
			}
		});
		if( errorTip ){
			return errorTip;
		}
		return param;
	}
});