/**
 * DingDone Webview API
 *
 * @author	zhangzhen
 * @version	beta
 */

( function(){

	/*Dingdone class*/
	var Dingdone = (function(){
		/*私有属性*/
		var _version = 'beta';
		var _bridgeInit = false;

		/*私有api方法配置*/
		var _api = {
			dingdone_device : 'getSystemInfo',
			dingdone_user : 'getUserInfo',
			dingdone_gologin : 'goLogin',
			dingdone_goToContent : 'goToContent',
			dingdone_goToRichText : 'goToRichText',
			dingdone_goToCommentList : 'goToCommentList',
			dingdone_goToLink : 'goToLink'
		};

		/*私有方法*/
		var _privateMethod = {
			/*检测平台*/
			getPlatform : function(){
				var platform = navigator.userAgent.toLowerCase();
				if (/iphone|ipod|ipad/gi.test(platform))
				{
					return "iOS";
				}
				else if (/android/gi.test(platform))
				{
					return "Android";
				}
				else
				{
					return "Unsupport Platform";
				}
			},

			/*IOS connectWebViewJavascriptBridge*/
			connectWebViewJavascriptBridge : function(callback) {
				if (window.WebViewJavascriptBridge) {
					callback(WebViewJavascriptBridge);
				} else {
					document.addEventListener('WebViewJavascriptBridgeReady', function() {
						callback(WebViewJavascriptBridge);
					}, false);
				}
			},

			/*封装调用操作*/
			callApiCenter : function( apikey, callback, param ){
				var platform = this.getPlatform(),
					api = _api[apikey],
					param = param || null;
				if (platform == 'iOS') {
					this.connectWebViewJavascriptBridge(function(bridge) {
						if( !_bridgeInit ){
							bridge.init(function(message, responseCallback) {
							});
							_bridgeInit = true;
						}
						bridge.callHandler( api, param, function(response) {
							(typeof callback =="function") && callback(response);
							return response;
						});
					});
				} else if (platform == 'Android') {
					try{
						var response =  param ? window.android[api](param)  : window.android[api]() ;
						(typeof callback =="function") && callback(response);
						return response;
					}catch(e){
						console.log(api + '_error');
					}
				} else {
					(typeof callback =="function") && callback(platform);
					return platform;
				}
			}
		};

		return function(){
			/*特权方法*/

			/*获得版本信息*/
			this.getVersion = function() {
		        return _version;
		   };

		    /*封装的调用api中心 与私有方法通信*/
		    this.callApiCenter = function( apikey, callback, param  ){
		    	return _privateMethod.callApiCenter( apikey, callback, param );
		    };
		};

	})();

	/*Dingdone 对外公用api方法*/
	Dingdone.prototype = {
		getSystemInfo : function( callback ){
			return this.callApiCenter( 'dingdone_device', callback );
		},
		getUserInfo : function( callback ){
			return this.callApiCenter( 'dingdone_user', callback );
		},
		goLogin : function(){
			return this.callApiCenter( 'dingdone_gologin' );
		},
		goToContent : function(){
			return this.callApiCenter( 'dingdone_goToContent' );
		},
		goToCommentList : function(){
			return this.callApiCenter( 'dingdone_goToCommentList' );
		},
		goToRichText : function(param){
			if( typeof param !="function" ){
				return this.callApiCenter( 'dingdone_goToRichText',null,param );
			}
		},
		goToLink : function( param ){
			if( typeof param !="function" ){
				return this.callApiCenter( 'dingdone_goToLink',null,param );
			}
		}
	};

	/*暴露出来的全局 Dingdone实例对象*/
	window.dingdone = new Dingdone();

} )();
