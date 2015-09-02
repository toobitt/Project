$(function(){
	utils.spinner.show();
	var my = {
		form : $('.form-block').find('form'),
		submitBtn : $('.form-to-json'),
		hiddenBox : $('.hiddenBox'),
		deviceLimit : $('.btn-area').find('.limit'),
		loading : true
	};
	
	/*提示框*/
	my.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	hgClient.getUserInfo(function( response ){		//获取用户登录信息
		var userInfo = response.userInfo || response;
		my.hiddenBox.find('input[name="access_token"]').val( userInfo.accessToken || userInfo.userTokenKey );
	});
	
	hgClient.getSystemInfo(function( response ){	//获取设备信息
		var systemInfo = response.deviceInfo || response;
		my.hiddenBox.find('input[name="appid"]').val( systemInfo.appid || systemInfo.appId );
		my.hiddenBox.find('input[name="device_token"]').val( systemInfo.device_token || systemInfo.devicesToken );
		systemInfo.appkey && my.hiddenBox.find('input[name="appkey"]').val( systemInfo.appkey );
	});
	
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
						hgClient.goLogin();
					}, 2400);
				}
			}else{
				my.toast.show( '提交成功' );
				setTimeout(function(){
					hgClient.goBack();
				}, 2000);
			}
		},
		deviceLimit : my.deviceLimit.html(),			//限制数据
		deviceLoaded : function( loaded ){				//验证信息是否加载完全
			loaded && (my.loading = false);
			utils.spinner.close();
		}
	});
	!my.loading && utils.spinner.close();
});