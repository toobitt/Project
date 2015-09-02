define(function( require, exports, modules ){
	require('utils/api');
	function clientParam(callback){
		var user_data = false,
			system_data = false;
		var userInfo = new Api({
			onlyTokenKey : function( userInfo ){
				user_data = userInfo;
			},
			authDeviceSuccess : function( appSystemInfo ){
				system_data = appSystemInfo;
			}
		});
		if( user_data && system_data ){
			$.isFunction( callback ) && callback( user_data, system_data );
		}
	}
	modules.exports = clientParam;
});
