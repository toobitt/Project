define(function( require, exports, modules ){
	var template = require('module/template'),
		getUrl = require('config').getUrl;
		require('spin');
	
	var me = {};
	
	me.createImgsrc  = function( data, options ){						//图片src创建
		var data = data || {},
		op_str = options ? [options.width, 'x', options.height, '/'].join('') : '';
		var src = [data.host, data.dir, op_str, data.filepath, data.filename].join('');
		return src;
	};
	
	me.getParam = function( key ){
		var search = location.search.substring(1),
			pairs = search.split('&');
		var args = {}, param = {};
		for(var i=0; i< pairs.length; i++){
			var pos = pairs[i].indexOf('=');
			if( pos == -1 ) continue;
			var arg = pairs[i].substring(0, pos);
			if( typeof key !== 'undefined' && key == arg){
				return pairs[i].substring(pos+1);
			}
			args[arg] = pairs[i].substring(pos+1);
		}
		return args;
	};
	
	me.render = function(source, info){
		var render = template.compile( source );
		return render( info );
	};
	
	me.getAjax = function( label, param, callback, type ){
		var type = type || 'get';
		if( typeof param == 'string' && param == 'url' ){
			return getUrl( label );
		}
		$.ajax({
			type: type,
            url: getUrl( label ),
            dataType: "json",
            data : param,
            cache : true,
            timeout : 60000,
            success: function(json){
            	callback( json );
            },
        	error : function(){
        		callback( { ErrorText : '接口访问错误，请稍候再试' } );
        	}
        });
	};
	
	me.getRootUrl = function(){
		var rootUrl = document.location.protocol+'//'+(document.location.hostname||document.location.host);
		if ( document.location.port||false ) {
			rootUrl += ':'+document.location.port;
		}
		rootUrl += '/';

		return rootUrl;
	};
	
	me.spinner = {
		show : function( target, opts ){
			if( $.spinner ){
				return;
			}
			target = target || $('body');
			opts = $.extend({
				lines : 12,
        		length : 2.6,
        		width : 1.4,
        		speed : 1.4,
        		radius : 4,
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
	};
	
	me.prevent = function( e ){
		if ( e ) {
            e.stopPropagation();
            e.preventDefault();
       	}
	};
	
	me.find = function( data, name, value, cbk ){
		$.each( data, function( _, vv ){
			if( vv[name] == value ){
				$.isFunction( cbk ) && cbk( vv );
			}
		});
	};
	
	me.escape2Html = function(str) {
	 	var arrEntities={
		 	'lt':'<',
		 	'gt':'>',
		 	'nbsp':' ',
		 	'amp':'&',
		 	'quot':'"',
		 	'#039' : '"'
	 	};
	 	return str.replace(/&(lt|gt|nbsp|amp|quot|#039);/ig,function(all,t){return arrEntities[t];});
	};
	
	me.html2Escape = function(sHtml) {
 		return sHtml.replace(/[<>&"]/g,function(c){
 			return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&#039;'}[c];
		});
	}
	modules.exports = me;
})

