(function(){
	var form = $('#greeting-form'),
		toast = $.h5_toast({
			content : '请填写完整信息',
			appendTo : $('.page-content'),
			delay : 1500
		});
	var param = {},
		i = 0;
	var h5app = $.Hg_h5app({
		needUserInfo : function( json ){
			var userInfo = json && json.userid ? json :  json.userInfo || '';
			$.extend(param, {
				access_token : userInfo.userTokenKey,
//				access_token : '',
			});
			bindEvent();
		},
		needSystemInfo : function( json ){
			var deviceInfo = json && json.appid ? json :  json.deviceInfo || '';
			$.extend(param, {
				appid : deviceInfo.appid,
				appkey : deviceInfo.appkey,
				device_token : deviceInfo.device_token
//				appid : 55,
//				appkey : 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7',
//				device_token:'09745ef33d232752fc995d797c3348e4',
			});
			bindEvent();
		}
	});
	function bindEvent(){
		i++;
		if( i==2 ){
			isLoad = true;
			
			ajaxForm();
			
			$('.form-to-json').on('click', function(){
				var $this = $(this),
					formData = form.serializeArray();
				if( $this[0].disabled ){
					return false;
				}
				$this[0].disabled = true;
				var liLimited = false;
				
				var form_unit = param,
					i = 0;
				$.each(formData, function(nn, vv){
					if( !vv['value'] ){
						liLimited = true;
						return false;
					}
					i++
					form_unit[ vv['name'] ] = vv['value'];
				});
				if( liLimited && i < formData.length ){
					toast.show('请填写完整信息');
					$this[0].disabled = false;
					return;
				}
				ajax( $this, {
						url : form.attr('action'),
						data : form_unit,
						type : 'post'
					}, function( data ){
					if( data && data.ErrorCode ){
						var err = data.ErrorText ? data.ErrorText : data.ErrorCode;
						toast.show( err );
					}
					if( $.isArray( data ) && data[0] ){
						data = data[0];
						toast.show(data.notice);
						setTimeout(function(){
							h5app.callneedParamMethod('sharePlatsAction', {
								content_url : data.url,
								content : data.name,
								pic : data.pic
							});
						}, 2000);
					}
				});
			});
		}
	}
	
	function ajaxForm(){
		var option = {
			id : form.find('input[name="id"]').val(),
			access_token : param.access_token,
			appid : param.appid,
			appkey : param.appkey,
			device_token : param.device_token,
			a : 'filtinfo'
		}
		ajax( null, {
					url : form.find('input[name="filturl"]').val(),
					data : option,
				}, function( data ){
				if( data && data.ErrorCode ){
					var err = data.ErrorText ? data.ErrorText : data.ErrorCode;
					toast.show( err );
				}
				if( $.isArray( data ) && data[0] ){
					$.each( data, function( kk, vv ){
						form.find('.item-input').find('[name="' + vv.unique_name + '"]').val( vv.default_value );
					});
				}
			});
	}
	
	function ajax(dom, param, callback){
		$.ajax({
			url : param.url,
			data : param.data,
			cache : true,
        	timeout : 60000,
        	type : param.type || 'get',
			dataType : 'json',
			success : function( data ){
				$.isFunction( callback ) && callback( data );
				dom && dom.length && (dom[0].disabled = false);
			},
			error : function(){
        		toast.show('接口访问错误，请稍候再试');
        		dom && dom.length && (dom[0].disabled = false);
        	}
		});
	}
	
})();