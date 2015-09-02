(function( window ){
	var utils = (function(){
		var me = {};
		me.createImgsrc  = function( data, options ){						//图片src创建
			var data = data || {},
			op_str = options ? [options.width, 'x', options.height, '/'].join('') : '';
			var src = [data.host, data.dir, op_str, data.filepath, data.filename].join('');
			return src;
		};
		me.getParam = function( type ){
			var search = location.search.substring(1),
				pairs = search.split('&');
			var args = {}, param = {};
			for(var i=0; i< pairs.length; i++){
				var pos = pairs[i].indexOf('=');
				if( pos == -1 ) continue;
				var arg = pairs[i].substring(0, pos);
				args[arg] = pairs[i].substring(pos+1);
			}
			return args;
		};
		me.doAjax = function( url, param, callback, type ){
			type = type || "get";
			$.ajax({
				type: type,
	            url: url,
	            dataType: "json",
	            data : param,
	            timeout : 60000,
	            success: function(json){
	            	callback( json );
	            },
	        	error : function(){
	        		alert('接口访问错误，请稍候再试');
	        	}
	        });
		};
		me.transferTime = function( timestamp ){
			if( timestamp && timestamp.toString().length == 10 ){
				timestamp = timestamp * 1000
			}
			var date = timestamp ? new Date( timestamp ) : new Date();
				y = date.getFullYear(),
				m = me.formate(date.getMonth() + 1),
				d = me.formate(date.getDate()),
				h = me.formate(date.getHours()),
				minus = me.formate(date.getMinutes()),
				seconds = me.formate(date.getSeconds());
			return {
				date : [y, m, d].join('-'),
				time : [h, minus, seconds].join(':'),
				day : date.getDay() || 7
            };
		},
		me.formate = function( s ){
			return (+s<10) ? '0' + s : s; 
		};
		me.prevent = function( e ){
			if ( e ) {
	            e.stopPropagation();
	            e.preventDefault();
	       	}
		};
		me.spinner = {
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
		return me;
	})();
	window.utils = utils;
})( window );

