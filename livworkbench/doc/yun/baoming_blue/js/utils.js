(function( $ ){
	var utils = {};
	utils.spinner = (function(){
		return {
			show : function( target, opts ){
				if( $.spinner ){
					return;
				}
				target = target || $('.page-content');
				opts = $.extend({
					lines : 12,
	        		length : 4,
	        		width : 2,
	        		speed : 1.4,
	        		radius : 6,
	        		color : '#999'
				}, opts);
				$.spinner = new Spinner( opts ).spin( target[0] );
			},
			close : function(){
				if( $.spinner ){
					$.spinner.stop();
					delete $.spinner;
				}
			}
		}
	})();
	
	utils.doPost = function( url, param, callback ){
		$.ajax({
			url : url,
			data : param,
			cache : true,
        	timeout : 60000,
        	type : 'post',
			dataType : 'json',
			success : function( data ){
				$.isFunction( callback ) && callback( data );
			},
			error : function(){
        		$.isFunction( callback ) && callback( {
        			ErrorCode : '接口访问错误，请稍候再试'
        		} );
        	}
		});
	};
	
	utils.getMobileDevice = function(){				//获取移动设备类型
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
	};
	window.utils = utils;
})( window.jQuery || window.Zepto );
