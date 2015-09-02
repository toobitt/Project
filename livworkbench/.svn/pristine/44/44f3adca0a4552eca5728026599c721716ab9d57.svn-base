define(function(require, exports, modules){
	var Zepto = require('$');
	require('utils/device.min');
	require('utils/viewPC');
	(function( $ ){
		var defaultOptions = {
			appAuthTimeout : 6e3,
			goUcenterBtn : '',		//去用户中心
			goShareBtn : '',		//去分享
			makeTelBtn : '',		//打电话
			goToMapBtn : '',		//去地图
			goOutLink : '',			//去外链页
			goBackBtn : '', 		//后退
			UserInfoback : '',
			UserInfoUrl : '',
			onlyClick : false,
			
			//*callback
			onlyTokenKey : '',		//仅获得userInfo token, 不需获得用户详情信息
			authDeviceSuccess : '',			//获取设备信息	
			needLocation : $.noop
		}
		var platform = navigator.userAgent.toLowerCase(),
			isIOS= device.iphone() || device.ipod(),
			isAndroid = device.androidPhone(),
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
				!this.op.onlyClick && this.loading();
				this.bindEvent();
			},
			
			loading : function(){
				this.callSystemInfo();
				this.callUserInfo();
				this.callLocation();
			},
			
			bindEvent : function(){
				var _this = this,
					op = this.op,
					Etype = device && device.desktop() ? 'click' : 'touchstart';
				if( op.goUcenterBtn && op.goUcenterBtn.length){
					op.goUcenterBtn.on(Etype, $.proxy( this.goLogin, this ));
				}
				if( op.goShareBtn && op.goShareBtn.length){
					op.goShareBtn.on(Etype, $.proxy( this.goShare, this ));
				}
				if( op.makeTelBtn && op.makeTelBtn.length){
					op.makeTelBtn.on(Etype, $.proxy( this.makeTel, this ));
				}
				if( op.goToMapBtn && op.goToMapBtn.length){
					op.goToMapBtn.on(Etype, $.proxy( this.goToMap, this ));
				}
				if( op.goToPlayBtn && op.goToPlayBtn.length ){
					op.goToPlayBtn.on( Etype, $.proxy( this.goToPayEvent, this ));
				}
				if( op.goOutLink && op.goOutLink.length ){
					op.goOutLink.on( Etype, $.proxy( this.goOutLink, this ));
				}
				if( op.goBackBtn && op.goBackBtn.length ){
					op.goBackBtn.on( Etype, $.proxy( this.goBack, this ) )
				}
			},
			
			/*去用户中心*/
			goUcenter : function( event ){
				event && event.preventDefault();
				if( device && device.desktop() ){				//PC端使用默认数据
					console.log( '去客户端用户设置页' );
					return; 
				}
				if(isAndroid ){
					window.android.goUcenter();
				}else if( isIOS ){
					window.location.hash = "";
					window.location.hash = "#func=goUcenter"; 
				}
			},
			
			/*去登录 登陆后会跳转刷新前一个页面*/
			goLogin : function( event ){
				event && event.preventDefault();
				if( device && device.desktop() ){				//PC端使用默认数据
					console.log( '去登陆页' );
					return; 
				}
				if(isAndroid ){
					window.android.goLogin();
				}else if( isIOS ){
					window.location.hash = "";
					window.location.hash = "#func=goLogin"; 
				}
			},
			
			/*去外链页*/
			goOutLink : function( event ){
				event && event.preventDefault();
				var self = $(event.currentTarget),
					type = self.attr('_outlink') || self.data('outlink');
				if( device && device.desktop() ){				//PC端使用默认数据
					console.log( '去客户端用户设置页' );
					return; 
				}
				if(isAndroid ){
					window.android.goOutlink( type );
				}else if( isIOS ){
					type = type.replace("#", "&");
					window.location.hash = "";
					window.location.hash = "#" + type; 
				}
			},
			
			/*打电话*/
			makeTel : function( event ){
				event && event.preventDefault();
				var target = $(event.currentTarget),
					tel = target.attr('_tel');
				if( device && device.desktop() ){
					console.log( '打电话' );
					return; 
				}
				if(isAndroid ){
					window.android.makeTel( tel );
				}else if( isIOS ){
					window.location.hash = "";
					window.location.hash = "#func=makeTel&tel=" + tel; 
				}
			},
			
			/*调地图*/
			goToMap : function( event ){
				event && event.preventDefault();
				var target = $(event.currentTarget);
				if( device && device.desktop() ){
					console.log( '去地图' );
					return; 
				}
				if(  $.isFunction( this.op.getGoMapInfo ) ){
					var getGoMapInfo = this.op.getGoMapInfo();
					this.mapBack( target, getGoMapInfo );
				}
			},
			
			mapBack : function( target, data ){
				if(isAndroid ){
					window.android.goToMap( data.address, data.lat, data.lng, data.name );
				}else if( isIOS ){
					data.address = encodeURI( data.address ),
					data.name = encodeURI( data.name );
					window.location.hash = "";
					window.location.hash = "#func=goToMap&address=" + data.address + "&lat=" + data.lat + "&lng=" + data.lng + "&name=" + data.name; 
				} 
			},
			
			/*去分享*/
			goShare : function( event ){
				event && event.preventDefault();
				var target = $(event.currentTarget);
				if( device && device.desktop() ){
					console.log( '请到移动设备上测试' );
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
			
			//去支付
			goToPayEvent : function( event ){
				event && event.preventDefault();
				var target = $(event.currentTarget);
				if( device && device.desktop() ){
					console.log( '去支付' );
					return; 
				}
				if(  $.isFunction( this.op.getGoPlayInfo ) ){
					var payInfo = this.op.getGoPlayInfo();
					this.goToPay( payInfo );
				}
			},
			
			goToPay : function( data ){
				var payCollection = { appALiPay : 'appALiPay'};   //后续加入的支付类型继续扩展
				var pay_type = data.pay_type || 'appALiPay',
					order_id = data.pay_id,
					pay_method = payCollection[pay_type];
				if( isAndroid ){
					window.android[pay_method]( order_id );
				}else if( isIOS ){
					window.location.hash = "";
					window.location.hash = "#func=" + pay_method + "&order_id=" + order_id; 
				} 
			},
			
			goBack : function( event ){
				event && event.preventDefault();
				if( device && device.desktop() ){				//PC端使用默认数据
					console.log( '去后退' );
					return; 
				}
				if(isAndroid ){
					window.android.goBack();
				}else if( isIOS ){
					window.location.hash = "";
					window.location.hash = "#func=goBack"; 
				}
			},
			
			/*获取经纬度*/
			callLocation : function(){
				if( device && device.desktop() ){				//PC端使用默认数据
					this.pcUlts.callLocation.call( this );
					return; 
				}
				if(isAndroid ){
					window.android.callLocation();
				}else if( isIOS ){
					window.location.hash = "";
					window.location.hash = '#func=getLocation&parameter={"action" : "haha"}'
				}
			},
			
			getLocation : function( json ){
				var _this = $.my;
				if( $.isFunction( _this.op.needLocation ) ){
					_this.op.needLocation.call(_this, json);
				}
			},
			
			//获取设备信息
			callSystemInfo : function(){
				var _this = this;	
				if( device && device.desktop() ){				//PC端使用默认数据
					this.pcUlts.callSystemInfo.call( this );
					return; 
				}
				var duration = 0;
				var intervalId = setInterval(function() {
					if ( _this.appSystemInfo ) {
						clearInterval( intervalId );
					} else {
						duration += 500;
						if (duration >= _this.op.appAuthTimeout) {
							clearInterval( intervalId );
							if ( !_this.appSystemInfo ) {
								isWifiwx = false;
								console.log('刷新试试');
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
				var _this = $.my,
					appSystemInfo = json.device_token ? json : json.deviceInfo;
				var appDeviceToken = appSystemInfo.device_token;
				if( appDeviceToken && $.isFunction( _this.op.authDeviceSuccess ) ){
					_this.appSystemInfo = appSystemInfo;
					_this.op.authDeviceSuccess && _this.op.authDeviceSuccess.call( _this, appSystemInfo );
				}
				
			},
			
			//接获取用户登录信息
			callUserInfo : function(){	
				if( device && device.desktop() ){		//PC端使用默认数据
					this.pcUlts.callUserInfo.call( this );
					return; 
				}
				var _this = this;
				var duration = 0;
				var intervalId = setInterval(function() {
					duration += 500;
					if (duration >= _this.op.appAuthTimeout) {
						clearInterval( intervalId );
						if ( !_this.appUserInfo )
							console.log('请先登录');
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
					appUserInfo = json && (json.userinfo ? json.userinfo : json.userInfo);
				if( $.isFunction( op.onlyTokenKey ) ){
					op.onlyTokenKey( appUserInfo );
					return; 
				}
				var islogin = false;
				if( appUserInfo ){
					if( appUserInfo.userid && appUserInfo.userTokenKey ){
						_this.appUserInfo = appUserInfo;
						islogin = true;
						_this.authUser( appUserInfo );
					}
				}
				if( !islogin ){
					if( op.onlyTokenKey && $.isFunction( op.onlyTokenKey ) ){
						op.onlyTokenKey( appUserInfo );
						return; 
					}
					$('.login-info').find('.avatar-login').show();
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
	return Zepto;
});
var getSystemInfo = function( json ){
	Api.prototype.getSystemInfo( json );
};
var getUserInfo = function( json ){
	Api.prototype.getUserInfo( json );
};
var getLocation = function( json ){
	Api.prototype.getLocation( json );
};