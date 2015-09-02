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
				addid : 20,
				appkey : '4fJrS03Ergz9KE1ztJ5vNmrnmZgt0moU'
			}
		},
		userInfo : {
			parameter : 'hahaha',
			userInfo : {
				userid : 18,
				telephone : 13770834057,
				username : '这你都能猜到',
				userTokenKey : 'ccb456f711e16c4d5d9887d3b5bc3363',
				picurl : 'http://img.wifiwx.com/material/members/img/2013/08/8b223b1467b38415d63f58257cac5a76.jpg'
			}
		}
	}
	$.pcUlts = (function(){
		var func = {};
		func.callSystemInfo = function(){
			this.getSystemInfo( defaultdata.systemInfo );
		}
		func.callUserInfo = function(){
			this.getUserInfo( defaultdata.userInfo );
		}
		return func;
	})();
})( Zepto || jQuery );

/*
 * 调用方法
 *  
 *	var getSystemInfo = function( json ){		//用户数据回调方法
		console.log( json );
	}
	$.pcUlts.callSystemInfo.call( this );		//this当前环境作用域, 也是需要回调数据的作用域
*/