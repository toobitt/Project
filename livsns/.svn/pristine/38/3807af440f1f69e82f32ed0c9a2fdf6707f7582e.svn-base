$(function(){
	utils.spinner.show();
	var my = {
		form : $('.form-block').find('form'),
		submitBtn : $('.form-to-json'),
		hiddenBox : $('.hiddenBox')
	};
	
	/*提示框*/
	my.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	utils.spinner.close();
	my.h5app = $.hg_h5app({			/*App获取客户端数据*/
		needUserInfo : function( json ){
			if( !my.userinfo ){
				my.userinfo = json && json.userInfo || {};
				if( $.isPlainObject( my.userinfo ) ){
					my.hiddenBox.find('input[name="access_token"]').val( my.userinfo.userTokenKey );
				}
			}
		},
		needSystemInfo : function( json ){
			if( !my.systeminfo ){
				my.systeminfo = json && json.deviceInfo || {};
				if( $.isPlainObject( my.systeminfo ) ){
					my.hiddenBox.find('input[name="appid"]').val( my.systeminfo.appid );
					my.hiddenBox.find('input[name="appkey"]').val( my.systeminfo.appkey );
					my.hiddenBox.find('input[name="device_token"]').val( my.systeminfo.device_token );
				}
			}
		},
		errorTip : function( msg ){
			my.toast.show( msg );
		}
	});
	
	/*叮当获取客户端数据*/
	if( !my.userinfo && !!dingdone ){
		dingdone.getUserInfo(function( response ){	
			if( response == 'Unsupport Platform' ){
				my.toast.show( response );
				return;
			}
			my.userinfo = typeof response == 'string' ? JSON.parse(response) : response || {};
			my.hiddenBox.find('input[name="access_token"]').val( my.userinfo.accessToken );
		});
	}
	if( !my.systeminfo && !!dingdone ){
		dingdone.getSystemInfo(function(response) {
			if( response == 'Unsupport Platform' ){
				my.toast.show( response );
				return;
			}
			my.systeminfo = typeof response == 'string' ? JSON.parse(response) : response || {};
			my.hiddenBox.find('input[name="appid"]').val( my.systeminfo.appId );
			my.hiddenBox.find('input[name="device_token"]').val( my.systeminfo.devicesToken );
		});
	}
	
	
	/*表单处理*/
	my.hg_submit = $.hg_submit({
		form : my.form,
		toast : function( str ){
			my.toast.show( str );
		},
		submitBtn : my.submitBtn,
		submitUrl : my.hiddenBox.find('input[name="submitUrl"]').val(),
		submitBack : function( data ){
			if( data && (data.ErrorText || data.ErrorCode) ){
				my.toast.show( data.ErrorText || data.ErrorCode );
				if( data.ErrorCode == 'NO_ACCESS_TOKEN' ){
					setTimeout(function(){
						 my.h5app.callWebviewMethod('goUncenter');
					}, 2400);
				}
			}else{
				my.toast.show( '提交成功' );
				setTimeout(function(){
					location.reload();
				}, 2000);
			}
		}
	});
});