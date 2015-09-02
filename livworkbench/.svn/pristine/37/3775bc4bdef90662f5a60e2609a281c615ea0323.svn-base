(function(){
	var opts = {
		getUrl : 'http://10.0.2.45/livsns/api/lottery/core/lottery.php',
		form : $('#formSubmit'),
		config : {
			contact_name : '姓名',
			phone_num : '手机号',
			address : '地址信息'
		}
	};
	utils.spinner.show();
	/*提示框*/
	opts.toast = $.hg_toast({
		content : '请填写完整信息',
		appendTo : 'body',
		delay : 1500
	});
	
	hgClient.getUserInfo(function( response ){		//获取用户登录信息
		var userInfo = response && (response.userInfo || response) || '';
		if( userInfo && userInfo.userid ){
			opts.form.find('input[name="access_token"]').val( userInfo.userTokenKey );
			
			opts.param = utils.getParam();
			
			if( opts.param && opts.param.id ){
				$.getJSON( opts.getUrl, { id : opts.param.id, a : 'get_address_info', 'access_token' : userInfo.userTokenKey }, function( data ){
					if( !data ){
						return false;
					}
					if( data.ErrorCode || data.ErrorText ){
						opts.toast.show( data.ErrorText || data.ErrorCode );
						return false;
					}
					
					if( $.isArray( data ) && data[0] ){
						data = data[0];
						opts.form.find('input[name="phone_num"]').val( data.phone_num );
						opts.form.find('input[name="address"]').val( data.address );
						opts.form.find('input[name="sendno"]').val( data.sendno );
					}
					utils.spinner.close();
				});
			}
			
		}else{
			hgClient.goLogin();
		}
		
	});
	
	
	
	function doAjax( btn, formdata ){
		$.ajax({
			url : opts.getUrl,
			data : formdata,
        	timeout : 60000,
        	type : 'post',
			dataType : 'json',
			processData : false,
            contentType : false,
			beforeSend : function(){
				btn[0].disabled = true;
				utils.spinner.show( btn );
			},
			complete : function(){
            	setTimeout(function(){
            		utils.spinner.close();
            		btn[0].disabled = false;
            	}, 1500);
            },
			success : function( data ){
				if( !data ){
					return false;
				}
				if( data.ErrorCode || data.ErrorText ){
					opts.toast.show( data.ErrorText || data.ErrorCode );
					return false;
				}
				
				opts.toast.show( '提交成功' );
				setTimeout(function(){
					var idlist = localStorage.getItem('lottery.idlist');
					if( idlist ){
						idlist = JSON.parse( idlist );
						if( idlist[ opts.param.id ] ){
							idlist[ opts.param.id ]['exchange_switch'] = 1;
						}
						localStorage.setItem('lottery.idlist', JSON.stringify( idlist ));
					}
					hgClient.goBack();
				}, 1500);
			},
			error : function(){
				opts.toast.show( '接口访问错误，请稍候再试' );
        	}
		});
	}
	
	$('.btn-submit').on('click', function( event ){
		event.stopPropagation();
		var self = $( event.currentTarget ),
			serialize = opts.form.serializeArray();
		var tip = '',
			formdata = new FormData();
		$.each(serialize, function( _, vv ){
			if( !vv.value && opts.config[vv.name] ){
				tip = '请填写' + opts.config[vv.name];
				return false;
			}
			formdata.append(vv.name, vv.value);
			formdata.append('a', 'update_address');
		});
		
		if( tip ){
			opts.toast.show( tip );
			return false;
		}
		doAjax( self, formdata );
	});
})();
