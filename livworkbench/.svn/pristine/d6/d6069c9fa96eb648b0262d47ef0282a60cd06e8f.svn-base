$.tool = function( dom, option ) {
        var data = dom.data('tool')
        , options = $.extend({}, typeof option == 'object' && option);
      if (!data) dom.data('tool', (data = new Tool(options)));
      return data;
};

$.create = (function(){
	var create = {};
	create.createContent = function( app, param, callback ){
		var render = template.compile($.templete.page_tpl);
		var html = render( param );
		app.loadContent( html );
		$.isFunction( callback ) && callback();
		return $('.' + param.className);
	};
	create.prevent = function( e ){
		if ( e ) {
            e.stopPropagation();
            e.preventDefault();
       	}
	};
	create.isPC = function(){
		var userAgentInfo = navigator.userAgent; 
		var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
		var flag = true; 
		for (var v = 0; v < Agents.length; v++) { 
			if (userAgentInfo.indexOf( Agents[v] ) > 0) 
			{ flag = false; break; } 
		} 
		return flag; 
	};
	return create;
})();




var defaultOptions = {
	baseUrl : 'http://pmobile.ijntv.cn/ijntv/',
	key : '?appkey=8aUP07YvdSL1CraMm3OS9iZKycBEiMhg&appid=6',
	interface_method : {					//接口配置方法
		roadcondition : 'road.php',
		roaddetail : 'road_detail.php',
		roadsort : 'road_sort.php'
	},
};
	
function Tool( options ){
	this.size = this.getSize();
	this.options = $.extend( {}, options, defaultOptions );
	this.tpl = $.templete;
}
$.extend( Tool.prototype, {
	getSize : function(){
		var doc = document;
		return {
			width : doc.documentElement.clientWidth,
			height : doc.documentElement.clientHeight
		}
	},
	
	interface_tool : function( name ){
		var op = this.options,
			url = op.baseUrl + op.interface_method[name] + op.key;
		return url;
	},
	
	ajax : function( url, param, callback ){	//ajax工具函数，在pc上测试时可以用下面的注释的jsonp请求，如不能正确返回在移动设备上测试
		var _this = this;
		$.getJSON( url, param, function( data ){
			if( $.isFunction( callback ) ){
				callback( data );
			}
		});
		// $.ajax({
			// type: "get",
            // url: url,
            // data : param,
            // dataType: "jsonp",
            // jsonp: "callback",
            // timeout : 30000,
            // success: function(json){
            	// callback( json );
            // },
        	// error : function(){
        		// //_this.showDialog('接口访问错误，请稍候再试');
        	// }
        // });
	},
	
	formatFloat : function( num, digital ){
    	var m = Math.pow(10, digital);
    	return parseInt( num * m, 10 ) / m; 
	},
	
	getHeight : function( ele ){
		if( ele.length ){
			ele = ele.length > 1 ? $( ele[0] ) : ele;
			return ele.height();
		}
	},
	
	showDialog : function( msg ){
		var tipDom = $('.views').find('.popDiv');
 		tipDom.removeClass('fadeOut').addClass('fadeIn').html( msg );
 		var setTime = setTimeout(function(){
 			tipDom.removeClass('fadeIn').addClass('fadeOut');
 		}, 800);
	},
	
	transferTime : function( timestamp ){
		var date = new Date( timestamp *1000 ),
			y = date.getFullYear(),
			m = date.getMonth() + 1,
			d = date.getDate(),
			h = date.getHours(),
			seconds = date.getSeconds();
		m = ( +m <10 ) ? '0' + m : m;
		d = ( +d <10 ) ? '0' + d : d;
		h = ( +h <10 ) ? '0' + h : h;
		seconds = ( +seconds <10 ) ? '0' + seconds : seconds;
		return y + '-' + m + '-' + d + ' ' + h + ':' + seconds;
	},
	
	appendDom : function(){
		return $('<div class="popDiv fadeOut"/>').appendTo( $('.views') ).css({
 			position : 'absolute',
 			left : '50%',
 			top : '50%',
 			height : '46px',
 			color : '#fff',
 			padding : '0 20px',
 			'border-radius' : '3px',
 			'z-index' : 999999,
 			'margin-top' : '-50px',
 			'line-height' : '46px',
 			'font-size' : '1.6em',
 			'margin-left' : '-15%',
 			'background-color':'rgba(0, 0, 0, 0.6)',
 			'transition' : 'opacity 0.3s'
 		});
	},
	
	render : function( tpl, data ){
		var render = template.compile( tpl );
		return render( data );
	},
	
	defer : function(delay, fn){
		setTimeout(fn, delay || 0); 
	},
	
	hasDisable : function( btn ){
		return btn.hasClass('btn-disable');
	},
	
	addDisable : function( btn ){
		btn.addClass('btn-disable');
	},
	
	removeDisable : function( btn ){
		btn.removeClass('btn-disable');
	},
	
	find: function(name, fn){
	    var obj
	    $.each(this.called, function(i, item){
	      if (item[0] == name) {
	        obj = fn(item)
	        return false
	      }
	    })
	    return obj
	},
	
	toggle : function(){
		$('.delete').hide()
  		$('.delete', this).show()
	},
	
	handlers: function(){
        var that = this, hash = {}
        $.each(arguments, function(i, name){
          hash[name] = that.handler(name)
        })
        return hash
  	},
	
	toArray : function( list ){
		if ($.type(list) == "string") return list.split(/(?:\s*,\s*|\s+)/)
      else return list
	},
});

$.templete = { 
	column_tpl : '' +
		'<div class="tabbar tabbar-labels m2o-flex {{if navbar}}{{navbar}}{{/if}}">' +
			'{{each column as value i}}' +
				'<a href="#{{type}}{{value.id}}" _id="{{value.id}}" class="tab-link m2o-flex-one{{if value.isCurrent}} active{{/if}}">{{value.title}}</a>' +
			'{{/each}}' +
		'</div>' +
		'',
	
	tab_tpl : '' + 
		'{{each column as value i}}' + 
			'<div class="tab{{if value.isCurrent}} active{{/if}}" id="{{type}}{{value.id}}">' +
			'</div>' +
		'{{/each}}' +
		'',
		
	page_tpl : '' +
		'<div class="navbar">' +
          	'<div class="navbar-inner">' +
          		'<div class="left"><a href="#" class="back link">Back</a></div>' +
          		'<div class="center sliding">{{title}}</div>' +
          	'</div>' +
      	'</div>' +
		'<div class="pages">'+
          	'<div data-page="dynamic-content" class="page no-navbar{{if defineBar}} define-navbar{{/if}}{{if toolBar}} toolbar-through{{/if}}">'+
	          	'<div class="page-content {{className}}">'+
	          	'</div>' +
          	'</div>' +
      	'</div>' +
		'',

	list_tpl : '' +
		'{{if type == "detail"}}<div id="mapDiv"></div>{{/if}}' +
		'{{if nodata}}' +
			'<p class="nodata">暂无{{title}}信息<span class="refresh">刷新试试</span></p>' +
		'{{else}}' +
		'{{if type != "infinite"}}<ul class="road-list">{{/if}}' +
			'{{each list as value i}}' +
			'<li data-id="{{value.id}}" class="{{if type != "detail"}}list-item-li{{/if}} road-item">' +
				'<div class="info">' +
					'<div class="title">{{value.content}}</div>' +
				'</div>' +
				'{{if value.index}}<div class="pic"><img src="{{value.index}}"></div>{{/if}}' +
				'<div class="detail m2o-flex">' +
					'<span class="type" style="background:{{value.color}}">{{value.sort_name}}</span> ' +
					'<p class="address m2o-flex-one">{{value.address}}</p>' +
					'<span class="ntime">{{value.create_time}}</span> ' +
				'</div>' +
			'</li>' +
			'{{/each}}' +
		'{{if type != "infinite"}}</ul>{{/if}}' +
		'{{/if}}' +
		'',
	map_tpl : '' + 
		'{{if type == "icon"}}' + 
			'<a class="map-icon map"></a>' +
		'{{else}}' +
			'{{if column}}<div class="map-item map-nav">' + 
				'{{each column as value ii}}' +
					'<a class="road{{value.id}}{{if value.isCurrent}} selected{{/if}}" style="background-color:{{value.color}}" _id="{{value.id}}">{{value.title}}</a>' +
				'{{/each}}' +
			'</div>{{/if}}' +
			'<div class="map-item map-road">' + 
				'<p>显示实时路况</p>' +
				'<div class="status">' + 
					'{{each status as value ii}}' +
						'<a style="background-color:{{value.color}}">{{value.title}}</a>' +
					'{{/each}}' +
				'</div>' +
			'</div>' +
			'<a class="map-icon list back"></a>' +
			'<div id="roadMap"></div>' +
		'{{/if}}' +
		'',
}

