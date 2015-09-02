(function(){
	var config = {
		api : {
			'getUserInfo' : 'callUserInfo',
			'getSystemInfo' : 'callSystemInfo',
			'getLocation' : 'callLocation'
		},
		defaultOp : {
			'getUserInfo' : {
				userInfo : {
					userid : 18,
					telephone : 13770834057,
					username : '这你都能猜到',
					userTokenKey : '',
					m2ouid: "ios.app241178",
					picurl : 'http://img.wifiwx.com/material/members/img/2013/08/8b223b1467b38415d63f58257cac5a76.jpg'
				}
			},
			'getSystemInfo' : {
				deviceInfo : {
					debug : 1,
					types : "x86_64",
					system : 'iPhone OS7.1',
					device_token : '',
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
		interval : 500
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
			
			callApiCenter : function( api, callback, param ){
				var mbldevice = utils.getMobileDevice();
				
				if( _this.response[api] ){
					$.isFunction( callback ) && callback( _this.response[api] );
					return false;
				}
				
				_this.cache[ api ] = callback || '';
				
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
						window.location.hash = "";
						window.location.hash = "#func=" + api;
					}
				}else if( mbldevice == 'Android' ){
					try{
						var response = window.android[ api ]();
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
				if( _this.entryPlat || timeout > 2000 ){
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
		goBack : function(){
			return this.callApiCenter( 'goBack' );
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
