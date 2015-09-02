(function( $ ){
	var defaultdata = {
		systemInfo : {
			parameter : 'hahahha',
			deviceInfo : {
				debug : 1,
				types : "x86_64",
				system : 'iPhone OS7.1',
				device_token : '111',
				program_name : 'zhuihuiwuxi',
				program_version : '2.4.0',
				appid : 55,
				appkey : 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7'
			}
		},
		userInfo : {
			parameter : 'hahaha',
			userInfo : {
				userid : 18,
				telephone : 13770834057,
				username : '这你都能猜到',
				userTokenKey : '',
				picurl : 'http://img.wifiwx.com/material/members/img/2013/08/8b223b1467b38415d63f58257cac5a76.jpg'
			}
		},
		locationInfo : {
			longitude : 118.824952,
			latitude : 31.97819
		}
	}
	$.pcUlts = (function(){
		var func = {};
		func.callSystemInfo = function(){
			this.getSystemInfo( defaultdata.systemInfo );
		};
		func.callUserInfo = function(){
			this.getUserInfo( defaultdata.userInfo );
		};
		func.callLocation = function(){
			this.getLocation( defaultdata.locationInfo );
		};
		return func;
	})();
})( window.jQuery || window.Zepto );


/*
 * 调用方法
 *  
 *	var getSystemInfo = function( json ){		//用户数据回调方法
		console.log( json );
	}
	$.pcUlts.callSystemInfo.call( this );		//this当前环境作用域, 也是需要回调数据的作用域
*/