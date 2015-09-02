(function( $ ){
	var defaultOptions = {
		appAuthTimeout : 60e3,
		goUcenterBtn : '',
		goShareBtn : '',
		UserInfoback : '',
		UserInfoUrl : '',
		
		//*callback
		onlyTokenKey : '',		//仅获得userInfo token, 不需获得用户详情信息
		authDeviceSuccess : '',			//获取设备信息	
		userCallback : ''
	}
	var platform = navigator.userAgent.toLowerCase(),
		isIOS= (/iphone|ipod|ipad/gi).test(platform),
		isIPad = (/ipad/gi).test(platform),
		isAndroid = (/android/gi).test(platform),
		isAndroidOld = (/android 2.3/gi).test(platform) || (/android 2.2/gi).test(platform),
		isSafari = (/safari/gi).test(platform) && !(/chrome/gi).test(platform),
		isWifiwx = true,
		isWechat = /micromessenger/gi.test(platform);
	function Api( options ){
		$.my = this;
		this.op = $.extend( {}, defaultOptions, options );
		this.pcUlts = $.pcUlts;
		this.init();
	}
	$.extend( Api.prototype, {
		constructor : Api,
		
		init : function(){
			if( isAndroid ){
				window.android.checkLoginAction();
			}
			this.loading();
			this.bindEvent();
		},
		
		loading : function(){
			this.callSystemInfo();
			this.callUserInfo();
		},
		
		bindEvent : function(){
			var op = this.op,
				Etype = device && device.desktop() ? 'click' : 'touchstart';
			if( op.goUcenterBtn && op.goUcenterBtn.length){
				op.goUcenterBtn.on(Etype, $.proxy( this.goUcenter, this ));
			}
			if( op.goShareBtn && op.goShareBtn.length){
				op.goShareBtn.on(Etype, $.proxy( this.goShare, this ));
			}
		},
		
		/*去登陆页*/
		goUcenter : function(){
			if( device && device.desktop() ){				//PC端使用默认数据
				//console.log( '去客户端用户设置页' );
				return; 
			}
			if(isAndroid ){
				window.android.goUcenter();
			}else if( isIOS ){
				window.location.hash = "";
				window.location.hash = "#func=goUcenter"; 
			}
		},
		
		/*去分享*/
		goShare : function( event ){
			var target = $(event.currentTarget);
			if( device && device.desktop() ){
				//console.log( '请到移动设备上测试' );
				return; 
			}
			if( $.isFunction( this.op.getShareInfo ) ){
				var getShareInfo = this.op.getShareInfo();
				this.shareBack( target, getShareInfo );
			}
		},
		
		shareBack : function( target, data ){
			if( isWifiwx ){
				if ( isIOS ) {
					var href = "#func=sharePlatsAction&content=" + data.title + "&content_url=" + data.website;
					if ( data.img )
						href += "&pic=" + data.img;
					window.location.hash = "";
					window.location.href = href;
					href = '';
				} else if (isAndroid) {
					window.android.sharePlatsAction(data.title, data.website, data.img);
				}
			}else{
				var tip = $("#tip-share");
				if ( tip.length > 0 ) {
					tip.show()
				} else {
					tip = $('<div id="tip-share" class="tip-share-wechat"><img src="images/tip-share-wechat.png" width="100%" alt="分享提示" /></div>');
					tip.appendTo( target.closest('.page') );
					tip.click(function() {
						$(this).hide()
					});
				}
				return;
			}
			
		},
		
		//获取设备信息
		callSystemInfo : function(){	
			var _this = this;	
			if( device && device.desktop() && this.pcUlts ){				//PC端使用默认数据
			//if( device  ){				//PC端使用默认数据
				this.pcUlts.callSystemInfo.call( this );
				return; 
			}
			var duration = 0;
			var intervalId = setInterval(function() {
				if (_this.appSystemInfo) {
					clearInterval( intervalId );
				} else {
					duration += 500;
					if (duration >= _this.op.appAuthTimeout) {
						clearInterval( intervalId );
						if ( !_this.appSystemInfo ) {
							isWifiwx = false;
						}
					}
				}
			}, 500);
			if(isAndroid ){
				window.android.callSystemInfo();
			}else if( isIOS ){
				window.location.hash = "";
				window.location.hash = '#func=getSystemInfo&parameter={"action" : "haha"}'
			}
		},
		
		//接收设备信息
		getSystemInfo : function( json ){
			var _this = $.my	
			var appSystemInfo = json.device_token ? json : json.deviceInfo;
			//var appDeviceToken = appSystemInfo.device_token;
			_this.appSystemInfo = appSystemInfo;
			_this.op.authDeviceSuccess && _this.op.authDeviceSuccess.call( _this, appSystemInfo );
			
		},
		
		//接获取用户登录信息
		callUserInfo : function(){	
			var _this = this;
			if( device && device.desktop() && this.pcUlts ){		//PC端使用默认数据
			//if( device  ){		//PC端使用默认数据
				this.pcUlts.callUserInfo.call( this );
				return; 
			}
			var duration = 0;
			var intervalId = setInterval(function() {
				duration += 500;
				if (duration >= _this.op.appAuthTimeout) {
					clearInterval( intervalId );
					if ( !_this.appUserInfo ){
					}
				}
			}, 500);
			if(isAndroid ){
				window.android.callUserInfo();
			}else if( isIOS ){
				window.location.hash = "";
				window.location.hash = '#func=getUserInfo&parameter={"action" : "haha"}'
			}
		},
		
		//接收获取用户登录信息
		getUserInfo : function( json ){	
			var _this = $.my,
				op = _this.op,
				appUserInfo = isAndroid ? (json && json.userInfo || json) : (json.userInfo || json.userinfo);
			var islogin = false;
			if( appUserInfo ){
				if( appUserInfo.userid && appUserInfo.userTokenKey ){
					_this.appUserInfo = appUserInfo;
					islogin = true;
					if( op.onlyTokenKey && $.isFunction( op.onlyTokenKey ) ){
						op.onlyTokenKey( appUserInfo );
						return; 
					}
					_this.authUser( appUserInfo );
				}
			}
			if( !islogin ){
				if( op.nologinCallback && $.isFunction( op.nologinCallback ) ){
					op.nologinCallback.call( _this );
				}
				_this.goUcenter();
			}
		},
		
		//获取用户详情
		authUser : function( appUserInfo ){
			var _this = $.my
				op = _this.op,
				url = op.UserInfoUrl || getUrl('getUserinfo');
			$.ajax({
				type: "get",
	            url: url,
	            dataType: "json",
	            data : {
	            	access_token : appUserInfo.userTokenKey, 
	            },
	            success: function(data){
					if( data && data.member_id && data.credits ){
						var user = $('.login-info').find('.user-login').show();
						user.find('.gold-num').html( data.credits );
					}else{
						$('.login-info').find('.avatar-login').show();
					}
	            },
	        	error : function(){
	        		$('.login-info').find('.avatar-login').show();
	        	}
	        });
		},
	});
	window.Api = Api;
})(Zepto)		
				
var getSystemInfo = function( json ){
	Api.prototype.getSystemInfo( json );
}
var getUserInfo = function( json ){
	Api.prototype.getUserInfo( json );
}