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
	key : '?appkey=d0WTCC30fX1FRwUD5XYtjKLTQtnE8Kwb&appid=28',
	interface_method : {					//接口配置方法
		tab : 'channel_node.php',
		livelist : 'channel.php',
		tvdetail : 'channel_detail.php',
		program : 'program.php',
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
				'<a href="#{{type}}{{value.id}}" _id="{{value.id}}" class="tab-link m2o-flex-one{{if value.isCurrent}} active{{/if}}">{{value.name}}</a>' +
			'{{/each}}' +
		'</div>' +
		'',
	
	tab_tpl : '' + 
		'<div class="tabs" _channel_id="{{channel_id}}" _type="{{style}}">' +
			'{{each column as value i}}' + 
				'<div class="tab{{if value.isCurrent}} active{{/if}}" id="{{type}}{{value.id}}">' +
				'</div>' +
			'{{/each}}' +
		'</div>' +
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
	pop_tpl : '' + 
		'<div class="popup {{type}}-popup">' + 
			'<div class="view navbar-fixed">' + 
				'<div class="pages">' + 
					'<div class="page">' + 
						'<div class="navbar">' +
				          	'<div class="navbar-inner">' +
				          		'<div class="left"><a href="#" class="link photo-browser-close-link close-popup">Close</a></div>' +
				          		'<div class="center sliding">{{title}}</div>' +
				          	'</div>' +
				      	'</div>' +
				      	'<div class="page-content {{type}}-wrap">'+
          				'</div>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>' +
		'',
	list_tpl : '' +
		'{{if nodata}}' +
			'<p class="nodata">暂无{{title}}信息<span class="refresh">刷新试试</span></p>' +
		'{{else}}' +
		'{{if type != "infinite"}}<ul class="live-list">{{/if}}' +
			'{{each list as value i}}' +
			'<li data-id="{{value.id}}" class="list-item m2o-flex m2o-flex-center">' +
				'<span class="list-pic">' + 
					'<img src="{{value.img}}"/>' + 
				'</span>' +
				'<div class="info m2o-flex-one">' + 
					'<div class="channel-name">{{value.name}}</div>' + 
					'<p>{{value.time}}<span class="live-name">{{value.live}}</span></p>' + 
				'</div>' +
				'<a class="live-flag {{value.type}}"></a>' + 
			'</li>' +
			'{{/each}}' +
		'{{if type != "infinite"}}</ul>{{/if}}' +
		'{{/if}}' +
		'',
		
	audio_tpl : '<div class="broadcast-play-wrap">' +
			 '<div class="broadcast-play-box">' +
			 	'<p class="flag">正在播放：{{cur_program.program}}</p>' +
				'<div class="broadcast-player">' +
					'<span class="btn pause">&nbsp;</span>' +
					'<div class="box-progress">' + 
						'<em>&nbsp;</em>' +
						'<p class="play-progress">&nbsp;</p>' +
					'</div>' +
					'<audio src="{{m3u8}}" poster="{{logo.rectangle.url}}" style="width:100%;"/>' +
				'</div>' +
			 '</div>' +
		'</div>' +
		'',
	vedio_tpl : '' +
		'<div class="player-wrap">' +
			'<video src="{{m3u8}}" controls autoplay style="width:100%; height:100%; "/>' +
		'</div>' +
		'',
	program_tpl : '' +
		'<div class="content-box {{type}}">' + 
			'<ul class="list detail-list">' +
				'{{each list as value ii}}' +
					'<li class="list-item m2o-flex m2o-flex-center{{if value.display}} live{{/if}}{{if value.zhi_play}} selected{{/if}}" _id={{ii + 1}}>' +
						'<div class="live-time"> {{if value.now_play}}直播中{{else}}{{value.start}}{{/if}}</div>' +
						'<div class="state{{if value.zhi_play}} state_show{{/if}}">正在播放</div>' +
						'<div class="m2o-flex-one live-name{{if value.now_play}} nowplay{{/if}}" _vedio_url="{{value.m3u8}}">{{value.theme}}</div>' +
						'{{if value.display}}<a class="live-flag"></a>{{/if}}' +
					'</li>' +
				'{{/each}}' +
			'</ul>' +
		'</div>' +
		'',
	program_tabbar : '' + 
		'<footer class="footbar m2o-flex" _id="{{channel_id}}" _type={{type}}>' +
			'<a class="m2o-flex-one"></a>' + 
			'<a class="m2o-flex-one icon playlist open-popup" data-popup=".playlist-popup">节目单</a>' + 
			'<a class="m2o-flex-one"></a>' + 
		'</footer>' +
		'',
	
}

