$(function(){
	var my = {
		form : $('.form-block').find('form'),
		submitBtn : $('.form-to-json'),
		hiddenBox : $('.hiddenBox'),
		deviceLimit : $('.btn-area').find('.limit'),
		baseUrl : $('.form-block').find('input[name="submitUrl"]').val()
	};
	
	my.limitBox = $('<div class="limitBox"/>').prependTo( 'body' ).css({
		padding : '15px',
		color : '#dd4a68',
		'font-size' : '15px',
		'text-align' : 'center',
		'line-height' : '22px',
		'display' : 'none'
		});
	
	/*提示框*/
	my.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	(function(){
		$.cookie.raw = true;
		$.cookie.json = true;
		
		utils.spinner.show( $('body') );
		hgClient.getUserInfo(function( response ){		//获取用户登录信息
			var userInfo = response && (response.userInfo || response) || '';
			if( utils.getMobileDevice() == "Unknow Device" ){
				return false;
			}
			if( userInfo && (userInfo.userid || userInfo.userId) ){
				var cookie_token = $.cookie('token');
				var memberParam = {
					a : 'check_members',
					system : (userInfo.m2ouid || '').replace(userInfo.userid || userInfo.userId, '' ).toLowerCase() || 'dingDone',
					user_name : userInfo.username || userInfo.userName,
					user_id : userInfo.userid || userInfo.userId,
					tel : userInfo.telephone
				};
				if( cookie_token ){
					utils.doPost( my.baseUrl, {
						a : 'check_token',
						access_token : cookie_token,
						user_id : userInfo.userid || userInfo.userId
					}, function( data ){
						if( data.ErrorCode || data.ErrorText || data[0].success == 0 ){
							checkMembers( memberParam );
						}else{
							my.hiddenBox.find('input[name="access_token"]').val( cookie_token );
						}
					});
				}else{
					checkMembers( memberParam );
				}
			}else{
				hgClient.goLogin();
			}
			
		});
		
		hgClient.getSystemInfo(function( response ){	//获取设备信息
			var systemInfo = response.deviceInfo || response;
			my.hiddenBox.find('input[name="device_token"]').val( systemInfo.device_token || systemInfo.devicesToken );
		});

		var deviceLimit = my.deviceLimit.html(),		//设备限制信息
			is_limit = false;
		if( deviceLimit ){
			hgClient.getPlat(function( response ){
				if( response == 'other' || (response == 'pc' && $.inArray( utils.getMobileDevice(), ['iOS', 'Android'] ) > -1 )){
					deviceLimit = typeof deviceLimit == 'string' ? JSON.parse( deviceLimit ) : deviceLimit;
					if( deviceLimit.is_login == 1 || deviceLimit.is_device == 1 ){
						is_limit = true;
						$('.page-content, .fixed-area').removeClass('hide');
						my.limitBox.show().html( '该表单需要设备登录信息才能操作' );
					}
				}else if( response == 'pc' ){
					is_limit = true;
					my.limitBox.show().html( '目前只支持移动端设备，请到移动端查看。。。' );
				}
				if( !is_limit ){
					$('.page-content, .fixed-area').removeClass('hide');
					my.submitBtn.removeClass('btn-disable');
				}
				utils.spinner.close();			//限制信息加载完全
			});
		}
	

		function checkMembers( param ){
			utils.spinner.show( $('body') );
			utils.doPost( my.baseUrl, param, function( data ){
				utils.spinner.close();
				if( !data ){
					my.toast.show('暂无数据返回');
					return false;
				}
				if( data.ErrorCode || data.ErrorText ){
					my.toast.show( data.ErrorCode || data.ErrorText );
					return false;
				}
				if( $.isArray( data ) && data[0] ){
					var access_token = data[0]['access_token'] || '';
					my.hiddenBox.find('input[name="access_token"]').val( access_token );
				}
			} );
		}
	})();
	
	/*表单处理*/
	my.hg_submit = $.hg_submit({
		form : my.form,
		errorCallback : function( str ){
			my.toast.show( str );
		},
		submitBtn : my.submitBtn,
		submitUrl : my.baseUrl,
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
				}, 2400);
			}
		}
	});
	setTimeout(function(){
		if( utils.spinner ){
			utils.spinner.close();
		}
	}, 8000);
});