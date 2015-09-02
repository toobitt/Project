(function(){
	var config = {
		api : {
			'getUserInfo' : 'callUserInfo',
			'getSystemInfo' : 'callSystemInfo',
			'getLocation' : 'callLocation',
			'goLogin' : 'goUncenter'
		},
		defaultOp : {
			'getUserInfo' : {
				userInfo : {
					userid : 18,
					telephone : 13770834057,
					username : '这你都能猜到',
					userTokenKey : '97f855fc100e1b61004205baabbe576e',
					m2ouid: "ios.app241178",
					picurl : 'http://img.wifiwx.com/material/members/img/2013/08/8b223b1467b38415d63f58257cac5a76.jpg'
				}
			},
			'getSystemInfo' : {
				deviceInfo : {
					debug : 1,
					types : "x86_64",
					system : 'iPhone OS7.1',
					device_token : '111',
					program_name : 'zhuihuiwuxi',
					program_version : '2.4.0',
					appid : '',
					appkey : ''
				}
			},
			'getLocation' : {
				longitude : 118.824952,
				latitude : 31.97819
			}
		},
		error : {
			getUserInfo_error : '不能获取用户信息',
			getSystemInfo_error : '不能获取设备信息',
			getLocation_error : '不能获取经纬度'
		},
		interval : 1000
	};
	
	function Plugin(){
		var _this = this;
		
		_bridgeInit = false;
		
		_this.response = {};
		
		_this.cache = {};
		
		_this.entryPlat = '';
		
		var utils = {
			getMobileDevice : function(){				//获取移动设备类型
				var mbldevice = navigator.userAgent.toLowerCase();
				if (/iphone|ipod|ipad/gi.test( mbldevice ))
				{
					return "iOS";
				}
				else if (/android/gi.test( mbldevice ))
				{
					return "Android";
				}
				else
				{
					return "Unknow Device";
				}
			},
			
			callApiCenter : function( api, callback ){
				var mbldevice = utils.getMobileDevice(),
					param = '';
				
				if( _this.response[api] ){
					$.isFunction( callback ) && callback( _this.response[api] );
					return false;
				}

				if( !$.isFunction( callback ) && typeof callback == 'object'  ){
					param = callback;
				}else{
					_this.cache[ api ] = callback || '';
				}
				
				if( mbldevice == 'iOS' ){
					var webviewBridge = window.WebViewJavascriptBridge;
					if( webviewBridge ){
						if( !_bridgeInit ){
							webviewBridge.init(function(message, responseCallback) {
							});
							_bridgeInit = true;
						}
						webviewBridge.callHandler( api, param, function(response) {
							response = typeof response == 'string' ? JSON.parse( response ) : response;
							
							_this.entryPlat = _this.entryPlat || 'dingdone';
							_this.response[ api ] = response;
							
							$.isFunction( callback ) && callback( response );
						});
					}else{
						var iosHref = '';
						if( typeof param == 'object' ){
							$.each( param, function( kk, vv ){
								iosHref += ('&' + kk + '=' + vv);
							} );
							window.location.hash = "";
							window.location.href = "#func=" + api + "&param=" + iosHref.valueOf();
						}else if( param ){
							type = param.replace("#", "&");
							window.location.hash = "";
							window.location.hash = "#" + type
						}else{
							window.location.hash = "";
							window.location.hash = "#func=" + api;
						}
					}
				}else if( mbldevice == 'Android' ){
					try{
						var needParticular = utils.particular( api, param );
						if( needParticular ){
							return false;
						}
						var response = param ? window.android[ api ]( param ) : window.android[ api ]();
						response = typeof response == 'string' ? JSON.parse( response ) : response;
						
						_this.entryPlat = _this.entryPlat || 'dingdone';
						_this.response[ api ] = response;
						
						$.isFunction( callback ) && callback( response );
					}catch(e){
						try{
							_this.entryPlat = _this.entryPlat || 'app';
							window.android[ config.api[ api ] ]();
						}catch( e ){
							_this.entryPlat = 'other';
							_this.response[ api ] = '';
							
							$.isFunction( callback ) && callback( config.error[ api + '_error'] );
						}
					}
				}else if( !!config.defaultOp[api] ){
					_this.entryPlat = _this.entryPlat || 'pc';
					_this.response[ api ] = config.defaultOp[api];
					
					$.isFunction( callback ) && callback( config.defaultOp[api] );
				}else{
					console.log('请到移动设备上测试~');
				}
			},
			
			particular : function( api, param ){
				switch( api ){
					case 'makeTel' : {
						window.android.makeTel( param.tel );
						return true;
					}
					case 'appALiPay' : {
						window.android.appALiPay( param.order_id );
						return true;
					}
					case 'sharePlatsAction' : {
						window.android.sharePlatsAction( param.content, param.content_url, param.pic );
						return true;
					}
					case 'goToMap' : {
						window.android.goToMap( param.address, param.lat, param.lng, param.name );
						return true;
					}
					case 'goOutlink' : {
						window.android.goOutlink( param );
						return true;
					}
					default : {
						return false;
					}
				}
			}
		}
		
		_this.callApiCenter = function( api, callback ){
			return utils.callApiCenter( api, callback );
		}
		
	}
	$.extend( Plugin.prototype , {
		constructor : Plugin,
		
		getClient : function( api, json ){		//获取app工厂数据
			this.entryPlat = this.entryPlat || 'app';
			this.response[ api ] = json;
			this.cache[ api ]( json );
		},
		
		getPlat : function( callback ){		//检测客户端平台- app/dingdone/other/pc
			var _this = this;
			if( _this.entryPlat && _this.entryPlat !== 'pc' ){
				$.isFunction( callback ) && callback( _this.entryPlat );
				return false;
			}
			var timeout = 0;
			var interval = setInterval(function(){
				timeout += config.interval;
				if( _this.entryPlat || timeout > 4000 ){
					_this.entryPlat = _this.entryPlat || 'pc';
					$.isFunction( callback ) && callback( _this.entryPlat );
					clearInterval( interval );
					interval = null;
				}
			}, config.interval );
		},
		getUserInfo : function( callback ){
			return this.callApiCenter( 'getUserInfo', callback );
		},
		getSystemInfo : function( callback ){
			return this.callApiCenter( 'getSystemInfo', callback );
		},
		getLocation : function( callback ){							//dingdone没该功能
			return this.callApiCenter( 'getLocation', callback );
		},
		goLogin : function(){
			return this.callApiCenter( 'goLogin' );
		},
		goHome : function(){
			return this.callApiCenter( 'goHome' );
		},
		goShare : function( param ){
			return this.callApiCenter( 'sharePlatsAction', param );
		},
		goBack : function(){
			return this.callApiCenter( 'goBack' );
		},
		goToMap : function( param ){
			return this.callApiCenter( 'goToMap', param );
		},
		makeTel : function( param ){
			return this.callApiCenter( 'makeTel', param );
		},
		goOutlink : function( param ){
			return this.callApiCenter( 'goOutlink', param );
		},
		appALiPay : function( param ){
			return this.callApiCenter( 'appALiPay', param );
		}
	} );
	
	window.hgClient = new Plugin();
	
	window.getUserInfo = function( json ){
		Plugin.prototype.getClient.call( hgClient, 'getUserInfo', json );
	};
	
	window.getSystemInfo = function( json ){
		Plugin.prototype.getClient.call( hgClient, 'getSystemInfo', json );
	};
	
	window.getLocation = function( json ){
		Plugin.prototype.getClient.call( hgClient, 'getLocation', json );
	}
	
})();
