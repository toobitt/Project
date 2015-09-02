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
	key : '?appkey=4fJrS03Ergz9KE1ztJ5vNmrnmZgt0moU&appid=20',
	interface_method : {					//接口配置方法
		indexpic : 'indexpic.php',
		column : 'news_recomend_column.php',
		indexlist : 'indexlist.php',
		newslist : 'news.php',
		detail : 'item.php',
		tujiDetail : 'tuji_detail.php'
	},
	more_title : '点击加载更多',
	count : 20
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
		// $.getJSON( url, param, function( data ){
			// if( $.isFunction( callback ) ){
				// callback( data );
			// }
		// });
		$.ajax({
			type: "get",
            url: url,
            data : param,
            dataType: "jsonp",
            jsonp: "callback",
            timeout : 30000,
            success: function(json){
            	callback( json );
            },
        	error : function(){
        		//_this.showDialog('接口访问错误，请稍候再试');
        	}
        });
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
		'{{each column as value i}}' +
			'<a href="#view{{value.id}}" _id="{{value.id}}" class="tab-link m2o-flex-one{{if i==0}} active{{/if}}">{{value.name}}</a>' +
		'{{/each}}' +
		'',
	
	tab_tpl : '' +
		'{{each column as value i}}' + 
		'<div class="tab{{if i == 0}} active{{/if}}" id="view{{value.id}}">' +
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
          	'<div data-page="dynamic-content" class="page no-navbar">'+
	          	'<div class="page-content {{className}}">'+
	          	'</div>' +
          	'</div>' +
      	'</div>' +
		'',
	list_tpl : '' +
		'{{if nodata}}' +
			'<p class="nodata">暂无{{title}}信息<span class="refresh">刷新试试</span></p>' +
		'{{else}}' +
		'{{if type != "infinite"}}<ul class="news-list">{{/if}}' +
			'{{each list as value i}}' +
			'<li data-id="{{value.id}}" data-module="{{value.module_id}}" class="list-item-li">' +
				'<div class="list-item{{if value.module_id =="vod"}} video{{/if}} m2o-flex m2o-flex-center">' +
					'<div class="info m2o-flex-one">' +
						'<p class="title">{{value.title }}</p>' +
					'</div>' +
					'{{if value.module_id =="vod"}}<span class="flag">视频</span>' +
					'{{else if value.module_id =="tuji"}}<span class="flag">图集</span>{{/if}}' +
					'<span class="list-pic">' +
						'<img src="{{value.img}}" /> ' +
					'</span>' +
				'</div>' +
			'</li>' +
			'{{/each}}' +
		'{{if type != "infinite"}}</ul>{{/if}}' +
		'{{/if}}' +
		'',
	detail_tpl : 
		'<div class="content-box">' +
			'<div class="content-box-word">' +
				'<h1 class="title">{{title}}</h1>' + 
				'<p><span class="publish-time">{{publish_time_format}}</span></p>' + 
				'<p></p>' +
				'{{if +is_have_video}}' +
					'<div><video src="{{video_mp4}}" poster="{{poster}}" controls="controls" style="width:100%;height:330px;margin:15px auto 0;padding:0;"/></div>' +
					'<div class="video-brief {{fontsize}}">{{brief}}</div>' +
				'{{/if}}' +
				'{{if module_id == "news"}}' +
					'<article class="service {{fontsize}}">' +
						'<p>{{#content}}</p>' + 
					'</article>' +
				'{{/if}}' +
			'</div>' +
		'</div>' +
		'',
	detail_size : '' + 
		'<div class="set-font-size">' + 
		'{{each dimension as value i}}' +
			'<span class="{{i}} {{if i == fontsize}}selected{{/if}}" _attr="{{i}}">{{value}}</span>' +
		'{{/each}}' +
		'</div>' +
		'',
	
}

