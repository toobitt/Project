(function( $ ){
	var defaultOptions = {
		login : '',
		authUrl : "app.wifiwx.com",
		
		//*callback
		systemCallback : '',				
		userCallback : ''
	}
	var platform = navigator.userAgent.toLowerCase(),
		isIOS= (/iphone|ipod|ipad/gi).test(platform),
		isIPad = (/ipad/gi).test(platform),
		isAndroid = (/android/gi).test(platform),
		isAndroidOld = (/android 2.3/gi).test(platform) || (/android 2.2/gi).test(platform),
		isSafari = (/safari/gi).test(platform) && !(/chrome/gi).test(platform),
		isWechat = /micromessenger/gi.test(platform);
	function Credit( options ){
		this.op = $.extend( {}, defaultOptions, options );
		this.pcUlts = $.pcUlts;
		this.init();
	}
	$.extend(Credit.prototype, {
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
			var op = this.op;
			if( op.login && op.login.length ){
				op.login.on('click', function( event ){
					event.preventDefault();
					if( device.desktop() ){				//PC端使用默认数据
						console.log( '去客户端用户设置页' );
						return; 
					}
					if(isAndroid ){
						window.android.goUcenter();
					}else if( isIOS ){
						window.location.hash = "";
						window.location.hash = "#func=goUcenter"; 
					}
				});
			}
		},
		
		callSystemInfo : function(){		//获取设备信息
			if( device.desktop() ){				//PC端使用默认数据
				this.pcUlts.callSystemInfo.call( this );
				return; 
			}
			if(isAndroid ){
				window.android.callSystemInfo();
			}else if( isIOS ){
				window.location.hash = "";
				window.location.hash = '#func=getSystemInfo&parameter={"action" : "haha"}'
			}
		},
		
		getSystemInfo : function( json ){			//接收设备信息
			this.systemInfo = json;
			this.op.systemCallback && this.op.systemCallback.call( this, json );
		},
		
		callUserInfo : function(){			//接获取用户登录信息
			if( device.desktop() ){		//PC端使用默认数据
				this.pcUlts.callUserInfo.call( this );
				return; 
			}
			if(isAndroid ){
				window.android.callUserInfo();
			}else if( isIOS ){
				window.location.hash = "";
				window.location.hash = '#func=getUserInfo&parameter={"action" : "haha"}'
			}
		},
		
		getUserInfo : function( json ){			//接收获取用户登录信息
			var op = this.op,
				appUserInfo = json.userInfo || json.userinfo;
			if( appUserInfo && appUserInfo.userid && appUserInfo.userTokenKey ){
				this.userInfo = json;
				op.authUser && this.authUser( appUserInfo );
			}else{
				op.authUser && op.authUser.find('.avatar-login').addClass( op.authShow );
				this.op.userCallback && this.op.userCallback.call( this, json );
			}
		},
		
		authUser : function( appUserInfo ){
			var op = this.op,
				url = op.authUrl;
			if( device.desktop() ){
				op.authUser.find('.user-login').addClass( op.authShow );
				return;
			}
			$.getJSON(url, {access_token : appUserInfo.userTokenKey, userid : appUserInfo.userid}, function( data ){
				if( data && data.member_id ){
					op.authUser.find('.user-login').addClass( op.authShow );
				}
			});
		},
	});
	window.Credit = Credit;
})(Zepto)
$(function(){
	$.credit = new Credit({
		authUser : $('.login-info'),
		authShow : 'm2o-flex',
		login : $('.login-info').find('.goUcenter'),		//去用户中心
		authUrl : 'http://www.cztv.tv/m2o/getUserinfo.php',
	});
	var getSystemInfo = function( json ){
		$.credit.getSystemInfo( json );
	}
	var getUserInfo = function( json ){
		$.credit.getUserInfo( json );
	}
});
				
				
