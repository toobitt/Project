;(function($){
	var defaultOptions = {
		baseUrl : 'http://api.139mall.net:8081/data/cmc/',
		key : '?appkey=4fJrS03Ergz9KE1ztJ5vNmrnmZgt0moU&appid=20',
		interface_method : {					//接口配置方法
			roadcondition : 'road.php',
			roaddetail : 'road_detail.php',
			roadsort : 'road_sort.php'
		},
		more_title : '点击加载更多',
		count : 20
	};
	
	function Road( el, options ){
		var _this = this;
		this.options = $.extend( {}, options, defaultOptions );
		this.el = el;
		this.wrap = el.find('.list-wrap');
		this.head = el.find('.ui-bae-header');
		this.column = el.find('.subnav');
		this.MapWrapper = el.find('.getloc-wrap');
		this.refer = {
				'全部' : 'all',
				'事故' : 'accident',
				'拥堵' : 'congestion',
				'施工' : 'construct',
				'管制' : 'regulate',
				'其他' : 'other'
		}
		this.initMap();													//实例化构造函数时执行的初始操作
		this.template = {												//gmu的模版语法，$.parseTpl(tpl,data);来解析
			column : '<li data-id="<%= id%>"><a><%= title%></a></li>',
			maptab : '<a class="<%=className%>" data-id="<%= id%>"><%= title%></a>',
			refresh_wrap : '<div class="data-list-wrap">' + 
								'<div class="ui-refresh-up ui-refresh-btn hide" noevent="<%= noevent%>"></div>' +
								'<ul id="thelist" class="data-list">' +
						        '</ul>' +
								'<div class="ui-refresh-down ui-refresh-btn" noevent="<%= noevent%>"></div>' +
							'</div>' +
							'',
			list :  '<li data-id="<%= id%>" class="list-item-li road-item">' +
						'<div class="info">' +
							'<div class="title"><%= content%></div>' +
						'</div>' +
						'<div class="detail">' +
							'<span class="type" style="background:<%= color%>"><%= sort_name%></span> ' +
							'<span class="ntime"><%= create_time %></span> ' +
						'</div>' +
					'</li>' +
					'',
			second_body : '<div class="second-body transition">' + 
								'<div class="data-content-wrap" id="<%= id%>">' + 
										'<div id="mapDiv" style="text-align: center; width: 100%; height:500px;z-index:-56"></div>' +
										'<div class="content-box"></div>' +
								'</div>' +
							'</div>' +
							'',
			style_bug : '.ui-refresh .ui-refresh-up, .ui-refresh .ui-refresh-down{background-color:transparent;}'+
						'',
		};
		
		this.el.on( 'click tap', '.subnav li', function(){								//栏目切换事件,根据栏目取对应的列表数据
			if( $(this).hasClass('selected') ) return;
			var id = $(this).data('id');
			$(this).addClass('selected ui-state-active').siblings().removeClass('selected ui-state-active');
			_this.showLoading();
			var param = {offset : 0};
			if( id != 'top' ){
				param.sort_id = id;
			}
			_this.listAjax( {
				method : 'roadcondition',
				param : param,
				type : 'list'
			});
		});
		
		this.el.on( 'click tap', '.map-nav a', function(){								//栏目切换事件,根据栏目取对应的列表数据
			var $this = $(this),
				id = $this.data('id');
			$this.addClass('selected').siblings().removeClass('selected');
			var options = _this.el.find('.road-map-icon').data('options');
			options.type = 'map';
			options.param.sort_id = (id == 'top') ? '' : id
			_this.listAjax( options );
		});
		
		this.el.on( 'click tap', '.map-road', function(){								//栏目切换事件,根据栏目取对应的列表数据
			var $this = $(this);
			if( $this.hasClass('map-open') ){
				_this.rttCtrl.hideTraffic();
				$this.find('p').html('显示实时路况');
				$this.removeClass('map-open');
			}else{
				_this.rttCtrl.showTraffic({predictDate:{hour:15, weekday: 5}});
				$this.find('p').html('关闭实时路况');
				$this.addClass('map-open');
			}
		});
		
		this.el.on('click', '.road-map-icon', $.proxy(_this.mapList, _this));
		
		this.el.on('click tap touchstart touchend', '.ui-refresh-btn', function( event ){		//屏蔽加载更多的click事件
			if( $(this).attr('noevent') ){
				return false;
			}
			event.stopPropagation();
		});
		
		this.el.find('.first-body').on( 'click', '.list-item-li', $.proxy(_this.list_item_event,_this)				//列表页切换到详情页
		);
		
		this.el.on( 'click', '.goFirstPage', function(event){									//详情页切换到列表页
			_this.backPage();
			event.stopPropagation();
			
		} );
		this.el.on( 'click', '.ui-bae-go-back', function( event ){								//退出应用事件，绑定在主页的back按钮上
			if( $(this).hasClass('goFirstPage') ) return;
			event.stopPropagation();
			Widget.close();
		} );
		
		this.style_bug();
	};
	
	$.extend( Road.prototype, {
		style_bug : function(){
			if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				$('<style/>').html( this.template.style_bug ).appendTo( this.el );
			}
		},
		
		list_item_event : function( event ){
			var self = $(event.currentTarget),
				id = self.data('id');
			this.goPage( id );
		},
		
		init : function(){
			this.initDom();
			var _this = this;
			setTimeout( function(){
				_this.initColumn();
			},1000 );
			this.showLoading();
		},
		
		initDom : function(){												
			this.wrap = this.el.find('.data-list-wrap');
			this.list = this.wrap.find('.data-list');
			this.ajaxRefreshBtn = this.wrap.find('.ui-refresh-btn');
		},
		
		initColumn : function(){		//初始化栏目
			var _this = this,
				url = this.interface_tool( 'roadsort' );
			this.ajax( url, null, function( json ){
				var html_str = '',html_map = '',
					parseTpl_func = $.parseTpl( _this.template.column );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
					parseTpl_map = $.parseTpl( _this.template.maptab );
				json.unshift({id : 'top', title : '全部'});
				$.each( json, function(key,value){
					value.className = _this.refer[value.title];
					html_str += parseTpl_func( value );
					html_map += parseTpl_map( value );
				} );
				_this.column.find('ul').append( html_str );
				_this.MapWrapper.find('.map-nav').append( html_map );
				_this.column.css('display', '-webkit-box');
				var size = _this.countSize( _this.column, 4 );
				_this.instanceNavigator( _this.column, size );
				
				_this.column.find('li').first().addClass('selected');
				
				_this.listAjax( {
					method : 'roadcondition',
					param : {offset : 0},
					type : 'list'
				}, null, false );
			} );
			
		},
		
		countSize : function( dom, count ){
			var size = dom.find('li').length;
			if( size > count ){
				size = count;
			}else{
				dom.find('#arrow').hide();
			}
			return size;
		},
		
		instanceNavigator : function( columnEl, visibleCount ){				 //初始化栏目组件,第一个参数zepto对象，第二个参数栏目默认显示数
			columnEl.find('.nav-box').navigator( {
				visibleCount : visibleCount   //配置栏目默认显示数
			});
			columnEl.find('#arrow').on('click', function(){
				columnEl.find('.nav-box').iScroll('scrollTo', 100, 0, 400, true);
			});
		},
		
		listAjax : function( options, callback ){
			var _this = this,
				url = this.interface_tool( options.method );
			var param = {
				offset : options.param.offset
			}
			options.param.sort_id && (param.sort_id = options.param.sort_id);
			this.ajax( url, param, function( json ){
				options.len = ( $.isArray( json ) && json.length ) || 0;
				options.callback = callback;
				if( options.type === 'list' ){
					_this.listAjaxCallback( json, options );
				}else{
					_this.mapAjax( json );
				}
			} );
		},
		
		listAjaxCallback :function( json, options ){
			var _this = this,
				html_str = '',
				parseTpl_func = $.parseTpl( this.template.list );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			if( !options['ismore'] && this.listScroll ){			//如果是栏目切换，会根据传过来的ismore参数进行重置list的refresh组件,ismore的含义是代表是否是加载更多触发的
				this.restoreListScroll();
			}
			if( !options.param.offset && (!json || !json.length)){
				this.list.append('<p class="nodata">暂无此类路况信息</p>');
			}else if( json ){
				$.each( json, function( key, value ){
					value['create_time'] = _this.getTime( value['create_time'] );
					html_str += parseTpl_func( value );
				} );
				this.list[options.dir == 'up' ? 'prepend' : 'append']( html_str );
				if( !this.el.find('.road-map-icon').data('init') ){
					this.el.find('.road-map-icon').show();
				}
				this.el.find('.road-map-icon').data('options', options);
			}
			this.closeLoading();
			
			if( $.isFunction( options.callback ) ){					//如果渲染完列表数据后，需要执行回调，执行回调函数，比如加载更多后会执行刷新组件回调
				options.callback( options );
			}else{
				this.setRefreshBtn( options );						//如果是正常页面加载，根据options里的参数来设置加载更多按钮的参数以及决定它是否显示
			}
			if( !options['ismore']  ){								//如果是首次加载加载页面，dom渲染完后初始化list的refresh组件
				this.initListScroll();
			}
		},
		
		restoreListScroll : function(){								//重置list的refresh组件，因为ajax切换栏目时要把原来的滚动条高度以及滚动条的top位置都要置为初始才能正常浏览
			this.wrap.parent().remove();
			var refresh_wrap = $.parseTpl( this.template.refresh_wrap, {noevent : true} );
			$( refresh_wrap ).insertAfter( this.column );
			this.initDom();											//重置list的refresh组件时会把list的外围dom移除掉，所以要调用initDom重新设置一次
		},
		
		initListScroll : function(){								//初始化列表页scroll
			var _this = this,
				head_height = this.head.height(),
				column_height = this.column.height(),
				window_height = window.innerHeight,
				wrap_height = window_height - head_height - column_height;
			this.el.find('.first-body').height( window_height );
			this.wrap.css( 'height', wrap_height + 'px' ).refresh({
                load: function (dir, type) {	
                    var me = this,
                    	up_btn = _this.wrap.find('.ui-refresh-up'),
                    	down_btn = _this.wrap.find('.ui-refresh-down'),
                    	up_options = up_btn.data('options'),
                    	down_options = down_btn.data('options');
                    if( !down_options && dir !='up' ) return;
                    _this.refreshWidget = this;
                    if( dir == 'up' ){
                    	up_options.dir = dir;
                    	up_options.refreshWidget = me;
                    	up_options.ismore = false;
                    	up_options.param.offset = 0;
                    	_this.listAjax(up_options);
                    }else{
	                    down_options.dir = dir;
                    	down_options.refreshWidget = me;
                    	down_options.ismore = true;
	                    down_options.param.offset += _this.options.count;		//加载更多，首先把offset加等到每页显示的条数加现在的offset
	                    _this.listAjax( down_options, function( obj ){
	                    		_this.refreshScroll( obj );					//_this.refreshWidget加载完列表后刷新refresh组件回调
	                    } );
                    }
                }
            });
            this.listScroll = true;
		},
		
		refreshScroll : function( options ){
            options.refreshWidget.afterDataLoading(options.dir);    	//数据加载完成后刷新refresh组件
            this.setRefreshBtn( options );
		},
		
		setRefreshBtn : function( options ){							//设置加载更多按钮的参数配置，如它的method,param等，如已没有更多，把他的参数配置置为null
			var len = options.len || 0;
			this.ajaxRefreshBtn.show().data('options',options);
			if( len < this.options.count ){
				this.ajaxRefreshBtn.eq(1).hide().data('options',null);
			}
		},
		
		initDetailScroll : function(){									 //初始化内容页滚动条
			this.el.find('#content-wrapper').refresh();
		},
		
		mapAjax : function( json ){
			var _this = this;
			$.each(json, function(key, value){
				value.create_time = _this.getTime( value['create_time'] );
				value.lng = value.baidu_longitude;
				value.lat = value.baidu_latitude;
				value.img = _this.createImgsrc( value.icon, {
					width:35, height:50
				} );
			});
			this.listMap( json );
		},
		
		mapList : function( event ){
			var self = $(event.currentTarget), obj,
				type = self.hasClass('list') ? false : true;	//true表示列表页,false表示地图页
			self[(type ? 'add' : 'remove') + 'Class']('list');
			var opacity = type ? -1 : 1;
			if( !self.data('init') ){
				this.MapWrapper.css({'visibility' : 'visible'});
				self.data('init', true);
			}
			this.el.find('.road-wrap').css({'opacity' : opacity});
			this.MapWrapper[type ? 'show' : 'hide']();
			
			var options = self.data('options');
			options.type = type ? 'map' : 'list';
			var sort_id = options.param.sort_id || 'top';
			if( type ){
				obj = this.MapWrapper.find('.map-nav').find('a');
			}else{
				obj = this.column.find('li');
			}
			obj.each(function(){
				if( $(this).data('id') == sort_id ){
					selected = $(this);
					return false;
				}
			});
			selected.addClass('selected ui-state-active').siblings().removeClass('selected ui-state-active');
			this.listAjax( options );
		},
		
		listMap : function( result ){
			var map = this.map,
				_this = this;
			var center = new BMap.Point(result[0].lng, result[0].lat);
			map.centerAndZoom(center, 14);
			map.clearOverlays();
			$.each(result, function(key, value){
				var myIcon = new BMap.Icon(value.img, new BMap.Size(30, 40), {
					imageOffset: new BMap.Size(0, 8),
				});
				var point = new BMap.Point(value.lng, value.lat);
				var marker = new BMap.Marker( point, {icon : myIcon });
				map.addOverlay(marker);
				_this.initMarker( marker, value );
			});
		},
		
		initMarker : function(marker, param){
			var _this = this, current,
				map = this.map;
			marker.addEventListener('click', function(e){
				var center = new BMap.Point(param.lng, param.lat);
				map.panTo(center);
				_this.showmySquare( param );
				var current = $('.getloc-wrap').find('.map_move'); 
				current.find('.map_route').find('p').html( param.content );
				current.find('.map_route').find('em').html( param.create_time );
				_this.MapWrapper.data('point', param);
			});
		},
		
		showmySquare : function( param, type ){
			var _this = this;
			var map = this.map;
			map.removeOverlay( this.mySquare );
			var mySquare = this.mySquare = new SquareOverlay( param, 350, 100 );
			map.addOverlay(mySquare);
			if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				tmpfun = map.onclick;
				map.onclick = null;
				mySquare.addEventListener('touchstart', function(){
					_this.map.onclick = tmpfun;
					_this.sureMap();
				});
			}else{
				mySquare.addEventListener('click', function(){
					_this.sureMap();
				});
			}
		},
		
		ajax : function( url, param, callback ){							//ajax工具函数，在pc上测试时可以用下面的注释的jsonp请求，如不能正确返回在移动设备上测试
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
	            // timeout : 60000,
	            // success: function(json){
	            	// callback( json );
	            // },
            	// error : function(){
            		// _this.showDialog('接口访问错误，请稍候再试');
            	// }
	        // });
		},
		
		
		sureMap : function(){
			var param = this.MapWrapper.data('point'),
				id =  param.id;
			this.goPage( id );
		},
		
		/*路况地图*/
		initbaiduMap : function(){
			var size = this.getSize(),
				box = this.MapWrapper,
				head_height = this.head.height();
			box.css({width : size.width, height : size.height - head_height});
			var map = this.map = new BMap.Map("roadMap");            // 创建Map实例
			map.addControl(new BMap.ZoomControl());
			var rttCtrl = this.rttCtrl = new BMapLib.TrafficControl();
			map.addControl(rttCtrl);
			var pos = this.point || {lat : '31.561094', lng : '120.277359'};
			var point = new BMap.Point(pos.lng, pos.lat);
			map.centerAndZoom(point, 14);
			map.clearOverlays();
			var marker = new BMap.Marker(point);
			map.addOverlay(marker);
		},
		
		initMap : function(){
			var _this = this;
			this.showLoading();
			this.callLocation();				//调用手机客户端提供的发起定位请求
			// Widget.ready = function(){
			// _this.location( function(){
				// _this.point = {lat : '31.561094', lng : '120.277359'};
				// _this.closeLoading();
				// _this.init();
				// _this.initbaiduMap();
			// } );
			// };
		},
		
		callLocation : function(){
			callLocation();
			this.locationTimeout();
		},
		
		locationTimeout : function(){
			var _this = this;
			setTimeout(function(){
				if( !_this.initPoint ){
					_this.closeLoading();
					_this.showDialog('定位接口异常');
					_this.initPos = false;
					return false;
				}
			}, 15000);
		},
		
		relocationPos : function( point ){
			var _this = this;
			if( point ){
				this.point = {};
				this.point.lat = point['latitude'];
				this.point.lng = point['longitude'];
				this.location( function(){
					_this.closeLoading();
					_this.init();
					_this.initbaiduMap();
				} );
			}
		},
		
		location : function( callback ){
			// var _this = this;
			// var map = new Widget.CMap.Map('wirelessDiv');
	        // Widget.CMap.Location.requestMyLocation(map);
			// Widget.CMap.Location.onMyLocationComplete = function (point) {
				// if( $.isFunction( callback ) ){
					// _this.point = point || {lat : '31.561094', lng : '120.277359'};
					// callback();
				// }
			// };
			if( this.point ){
				this.initPoint = true;
				$.isFunction( callback ) && callback();
			}
		},
		
		interface_tool : function( name ){	//拼接接口工具函数
			var op = this.options,
				url = op.baseUrl + op.interface_method[name] + op.key;
			return url;
		},

		createImgsrc :function( data, options ){						//图片src创建
			var options = $.extend( {}, {width:80,height:50}, options ),
				data = data || {},
				src = '';
			if( !options ){
				src = [data.host, data.dir, data.filepath, data.filename].join('');
			}else{
				src = [data.host, data.dir, options.width, 'x', options.height, '/', data.filepath, data.filename].join('');
			}
			return src;
		},

		showLoading : function(){
			this.loading = $.bae_progressbar({
				message:"<p>加载数据中...</p>",
				modal:false,
				canCancel : false
			});
		},
		
		closeLoading : function(){
			this.loading.close();
			$('#bae_progress_box').remove();
		},
		
		getSize : function(){
			var size = {},
				body = $(window),
				wd = body.width(),
				hd = body.height();
			size['width'] = wd;
			size['height'] = hd;
			return size;
		},
		
		getTime : function( timestamp ){
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
		
		goPage : function( id ){									//主页切换到详情页
			var _this = this,
				size = this.getSize(),
				first_body = this.el.find('.first-body'),
				head = first_body.find('.ui-bae-header'),
				head_height = head.height(),
				head_clone = head.clone();
			first_body.find('#arrow').hide();
			head_clone.find('.ui-bae-header-left')[0].nextSibling.nodeValue = '路况详情';
			head_clone.find('a').addClass('goFirstPage');
			first_body.css( {
				height : size['height'] + 'px',
				position:'absolute',
				left : '-' + size['width'] + 'px',
				'z-index' : 10
			} );
			var second_body = $( $.parseTpl( _this.template.second_body, {id : 'content-wrapper'} ) );
			second_body.prepend( head_clone );
			second_body.css( {
				height : size['height'] + 'px',
				position:'absolute',
				left :  size['width'] + 'px'
			} ).appendTo( 'body' ).css('left',0);
			setTimeout( function(){
				_this.showLoading();
				second_body.find('#content-wrapper').css( 'height', ( size['height']-head_height ) +'px'  );
				_this.detail( id );
			}, 200 );
		},
		
		detail : function( id ){
			var _this = this,
				url = this.interface_tool( 'roaddetail' );
			this.ajax( url, {road_id :id}, function(json){
				var data = json[0];
				data['create_time'] = _this.getTime( data['create_time'] );
				var parseTpl_func = $.parseTpl( _this.template.list , data),
					content_box = _this.el.find('.content-box').empty();
				content_box.append( parseTpl_func );
				_this.initBmap(data.baidu_longitude , data.baidu_latitude);/*实例化地图*/
				_this.closeLoading();
				_this.initDetailScroll();
			});
		},
		
		/*实例化地图*/
		initBmap : function(lat , lng){
			var map = new BMap.Map("mapDiv");
			var point = new BMap.Point(lat,lng);
			map.centerAndZoom(point, 14);
			map.addControl(new BMap.ZoomControl()); 
			var marker = new BMap.Marker(point);
			map.addOverlay(marker);
		},
		
		backPage : function(){										//回退到主页
			var _this = this,
				size = this.getSize(),
				first_body = this.el.find('.first-body');
			first_body.find('#arrow').show();
			first_body.css({left: 0});
			this.el.find('.second-body').css( {
				left : size['width'] + 'px'
			});
			setTimeout( function(){
				_this.el.find('.second-body').remove();
				first_body.removeAttr('style');
			}, 200 );
		}

	} );
	window.Road = Road;
	
})($);
	$(function(){
		var roadObj = new Road( $('body') );
		window.getLocation = function( json ){	//向手机端发起callLocation请求获得经纬度后触发的回调
			roadObj.relocationPos( json );
		};
	});