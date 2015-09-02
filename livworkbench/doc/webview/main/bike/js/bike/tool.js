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
	baseUrl : 'http://api.139mall.net:8081/data/cmc/',
	key : '?appkey=CshXUoKcUZrBc0OheGbJ7UWgv6b2MSjf&appid=21',
	interface_method : {					//接口配置方法
		nearnest : 'get_station.php',
		region : 'get_region.php',
		notice : 'get_notice.php',
		detail : 'get_station_detail.php',
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
	tab_tpl : '' +
		'<div class="tabbar tabbar-labels m2o-flex {{if navbar}}{{navbar}}{{/if}}" {{if dataid}}data-id="{{dataid}}"{{/if}}>' +
			'{{each column as value i}}' +
				'<a href="#{{type}}{{value.id}}" _id="{{value.id}}" _attr="{{type}}" class="tab-link m2o-flex-one{{if value.isCurrent}} active{{/if}}">{{value.title}}</a>' +
			'{{/each}}' +
		'</div>' +
		'<div class="pull-to-refresh-content">' +
			'<div class="pull-to-refresh-layer">' +
				'<div class="preloader"></div>' +
				'<div class="pull-to-refresh-arrow"></div>' +
			'</div>' +
			'<div class="tabs" data-page={{num}}>' + 
				'{{each column as value i}}' + 
					'<div class="tab{{if value.isCurrent}} active{{/if}}" id="{{type}}{{value.id}}">' +
					'</div>' +
				'{{/each}}' +
			'</div>' +
		'</div>' +
		'',
		
	page_tpl : '' +
		'<div class="navbar">' +
          	'<div class="navbar-inner" data-num={{num}}>' +
          		'<div class="left"><a href="#" class="back link">Back</a></div>' +
          		'<div class="center sliding">{{title}}</div>' +
          	'</div>' +
      	'</div>' +
		'<div class="pages">'+
          	'<div data-page="dynamic-content" class="page no-navbar{{if defineBar}} define-navbar{{/if}}{{if toolBar}} toolbar-through{{/if}}">'+
	          	'<div class="page-content {{className}} {{if infinite}}infinite-scroll{{/if}}">'+
	          	'</div>' +
          	'</div>' +
      	'</div>' +
		'',

	nearnest_tpl : '' +
		'{{if nodata}}' +
			'<p class="nodata">暂无{{title}}信息<span class="refresh">刷新试试</span></p>' +
		'{{else}}' +
		'{{if type != "infinite"}}<ul class="bike-list">{{/if}}' +
			'{{each list as value i}}' +
			'<li class="nearest-item m2o-flex m2o-flex-end" data-id="{{value.id}}" _link="{{method}}">' + 
				'<div class="pic">' + 
					'<img src="{{value.img}}" alt="无锡太科园西站"/>' + 
				'</div>' +
				'<div class="info m2o-flex-one">' +
					'<div class="name"><a>{{value.name}}</a></div>' +
					'<div class="num">' + 
						'<span class="bikenum">可借车数:{{value.currentnum}}</span>' + 
						'<span class="carportnum">可停车位:{{value.park_num}}</span>' + 
					'</div>' + 
					'<div class="address">{{value.address}}</div>' +
				'</div>' +
				'<div class="detail">' +
					'<div class="distance">{{value.distance}}</div>' + 
				'</div>' +
			'</li>' + 	
			'{{/each}}' +
		'{{if type != "infinite"}}</ul>{{/if}}' +
		'{{/if}}' +
		'',
	region_tpl : '' +
		'{{if nodata}}' +
			'<p class="nodata">暂无{{title}}信息<span class="refresh">刷新试试</span></p>' +
		'{{else}}' +
		'{{if type != "infinite"}}<ul class="bike-list">{{/if}}' +
			'{{each list as value i}}' +
			'<li class="area-item m2o-flex m2o-flex-end" data-id="{{value.id}}" _link="{{method}}">' +
		  		'<div class="info m2o-flex-one">' + 
		  			'<div class="name"><a>{{value.name}}</a></div>' + 
		  			'{{if value.station}}<div class="address"><span>{{value.station}}</span>离你最近</div>{{/if}}' + 
		  		'</div>' + 
		  		'<div class="detail">' + 
		  			'<div class="num">共{{value.station_num}}个站点</div>' + 
		  			'{{if value.distance}}<div class="distance">{{value.distance}}</div>{{/if}}' +
		  		'</div>' + 
		  	'</li>'	+
			'{{/each}}' +
		'{{if type != "infinite"}}</ul>{{/if}}' +
		'{{/if}}' +
		'',
	notice_tpl : '' +
		'{{if nodata}}' +
			'<p class="nodata">暂无{{title}}信息<span class="refresh">刷新试试</span></p>' +
		'{{else}}' +
		'{{if type != "infinite"}}<ul class="bike-list">{{/if}}' +
			'{{each list as value i}}' +
			'<li class="area-item m2o-flex m2o-flex-end" data-id="{{value.id}}" _link="{{method}}">' + 
				'<div class="info m2o-flex-one">' + 
					'<h3 class="name"><a>{{value.title}}</a></h3>' + 
					'<div class="addition">' +
						'<span class="address">{{value.station_name}}</span>' +
						'<span class="carportnum">{{value.region_name}}</span>' + 
					'</div>' + 
				'</div>' + 
				'<div class="detail">' + 
					'<div class="date">{{value.create_time}}</div>' + 
				'</div>' +
			'</li>' +
			'{{/each}}' +
		'{{if type != "infinite"}}</ul>{{/if}}' +
		'{{/if}}' +
		'',
	station_detail : '<div class="stationinfo">' +
		  '<div class="general m2o-flex">' + 
	  			'<div class="pic transition"><img src="{{img}}" /></div>'+
	  			'<div class="m2o-flex-one">' + 
	  				'<p class="web-item bikenum">可借车数: <span>{{currentnum}}</span></p>' + 
	  				 '<p class="web-item carportnum">可停车位: <span>{{park_num}}</span></p>' +
	  				 '<p class="update">数据更新于：{{dateline}}</p>' + 
	  				 '<p class="address">地址：{{address}}</p>' + 
	  				'<div class="distance"></div>' + 
	  			'</div>'+
	      '</div>'+
	      '<div id="mapDiv"></div>'+
	      '<div class="gohere" data-lat="{{latitude}}" data-lng="{{longitude}}"><a>到这里去</a></div>' + 
		  '<p class="business">运营单位：{{company_name}}</p>' + 
		  '<p class="hotline">服务热线：{{customer_hotline}}</p>' + 
		  '<p class="reminding">{{company_brief}}</p>' + 
     '</div>' + 
	 '',
	 goHere_tpl : '<div id="{{idName}}"></div>',
	 notice_detail : '' +
	 	'<div class="content-box">' +
		 	'<div class="content-box-word">' +
				 '<h1>{{title}}</h1>' +
				 '<p><span class="publish-time">{{create_time}}</span></p>' +
				 '<p></p>' +
				 	'<article>' + 
				 	'{{content}}' + 
				 	'</article>' +
			'</div>' +
		'</div>' +
		'',
}

