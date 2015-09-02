define(function( require, exports, modules ){
	var template = require('module/template'),
		getUrl = require('config');
	var me = {};
	me.getSize = function(){
		var doc = document;
		return {
			width : doc.documentElement.clientWidth,
			height : doc.documentElement.clientHeight
		}
	};
	me.createImgsrc  = function( data, options ){						//图片src创建
		var data = data || {},
		op_str = options ? [options.width, 'x', options.height, '/'].join('') : '';
		var src = [data.host, data.dir, op_str, data.filepath, data.filename].join('');
		return src;
	};
	me.getParam = function(){
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
	me.render = function(source, info){
		var render = template.compile( source );
		return render( info );
	};
	me.getAjax = function( label, param, callback, type ){
		var type = type || 'get';
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
        		me.showTips('接口访问错误，请稍候再试');
        	}
        });
	};
	me.transferTime = function( timestamp ){				//时间戳转换为时间
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
			day : date.getDay()
		};
	};
	
	me.formate = function( s ){
		return (+s<10) ? '0' + s : s; 
	};
	
	me.transdate = function( endTime ){			//时间转化为时间戳
		var date = new Date( endTime );
		return (date.getTime() / 1000);
	};
	
	me.limitTime = function( timestamp ){
		var day = Math.floor( timestamp/(24*60*60) ),
			hour = Math.floor( timestamp%(24*60*60)/(60*60) ),
			minute = Math.floor( timestamp%(24*60*60)%(60*60)/60 ),
			second = Math.floor( timestamp%(24*60*60)%(60*60)%60 );
		return {
			day : day,
			hour : hour,
			minute : minute,
			second : second
		}
	};
	
	me.prevent = function( e ){
		if ( e ) {
            e.stopPropagation();
            e.preventDefault();
       	}
	};
	
	me.showTips = function( msg ){
		var tipDom = $('<div class="popBox fadeOut"><span class="popDiv">接口访问错误</span></div>')
			.insertBefore( '.page-content' );
 		tipDom.removeClass('fadeOut').addClass('fadeIn').find('.popDiv').html( msg );
 		var setTime = setTimeout(function(){
 			tipDom.removeClass('fadeIn').addClass('fadeOut');
 			setTimeout(function(){
 				tipDom.remove();
 			}, 800);
 		}, 1500);
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

