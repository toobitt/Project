define(function( require, exports, modules ){
	var utils = require('utils/utils');
		config = require('config');
		require('toast');
		require('store');
	var options = {
		form : $('.rebindForm'),
		rebind : $('.btn-area').find('.rebind'),
		config : {
			accname : '联名卡卡号',
			fullName : '姓名',
			idcardNumber : '证件号码',
			deviceToken : '设备信息号'
		},
		pattern : {
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
	
	var userId = utils.getParam('userId');
	if( userId ){
		options.form.find('input[name="userId"]').val( userId );
	}
	
	var systemInfo = store.get('cpf.system');
	if( systemInfo ){
		options.form.find('input[name="deviceToken"]').val( systemInfo.device_token || systemInfo.devicesToken || 11 );
	}
	
	options.rebind.on('click', function(){
		var $this = $(event.currentTarget),
			form = options.form.serializeArray();
		
		var param = doBefore( form );
		if( typeof param == 'string' ){
			options.toast.show( param );
			return false;
		}
		utils.spinner.show( $this );
		$this[0].disabled = true;
		utils.getAjax('rebind', param, function( data ){
			$this[0].disabled = false;
			utils.spinner.close();
			if( data && (data.ErrorText || data.recode && data.recode !== '000000') ){
				options.toast.show( data.ErrorText || data.msg );
				return false;
			}
			if( data ){
				options.toast.show( '绑定账号成功', function(){
					location.href="./index.html?_ddtarget=push&userId=" + param.userId;
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