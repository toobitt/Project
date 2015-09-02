(function(){
	var my = {
		form : $('.main-wrap').find('form'),
		init : 0
	};
	
	my.checkStatus = function(){
		my.init++;
		if( my.init == 2 ){
			var data = {
				id : my.form.find('input[name="id"]').val(),
				device_token : my.systemInfo.device_token || my.systemInfo.devicesToken || '' ,
				access_token : my.userInfo.accessToken || my.userInfo.userTokenKey || '' ,
				appkey : my.systemInfo.appkey || '' ,
				appid : my.systemInfo.appid || my.systemInfo.appId || '' 
			}
			control.getResult( data );
		}
	};
	
	hgClient.getUserInfo(function( response ){		//获取用户登录信息
		var userInfo = my.userInfo = response && (response.userInfo || response) || {};
		my.form.find('input[name="access_token"]').val( userInfo.accessToken || userInfo.userTokenKey || '' );
		my.checkStatus();
	});
	
	hgClient.getSystemInfo(function( response ){	//获取设备信息
		var systemInfo = my.systemInfo = response && (response.deviceInfo || response) || {};
		my.form.find('input[name="appid"]').val( systemInfo.appid || systemInfo.appId || '' );
		my.form.find('input[name="device_token"]').val( systemInfo.device_token || systemInfo.devicesToken || '' );
		systemInfo.appkey && my.form.find('input[name="appkey"]').val( systemInfo.appkey || '' );
		my.checkStatus();
	});
	
	hgClient.getPlat(function( response ){
		if( response == 'other' || response == 'pc'){
			control.getInfo();
		}
	});
})();
