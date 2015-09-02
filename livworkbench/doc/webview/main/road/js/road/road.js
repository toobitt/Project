(function( $ ) {
	var defaultOptions = {
		status : [{color : '#f30400', title : '拥堵'}, {color : '#ffa200', title : '缓行'}, {color : '#10d878', title : '畅通'}],
		city_name : '济南',
		count : 20
	}
	function Road( options ){
		this.$views = $('.views');
		this.point = {
			lng : 118.79687700000001,
			lat: 32.060255,
		};
		this.op = $.extend({}, options, defaultOptions);
	}
	$.extend( Road.prototype, {
		init : function(){
			this.itool();
			this.loading();
			this.bindEvent();
		},
		
		loading : function(){
			this.initColumn();
			//this.getlocation();
		},
		
		getlocation : function(){
			var _this = this;
			if( top.mainStrap ){
				top.mainStrap.geolocation(function( position ){
					var currentLat = position.coords.latitude;
					var currentLon = position.coords.longitude;
					_this.point = {
						lng : position.coords.longitude,
						lat :  position.coords.latitude
					}
				}, {maximumAge:60*1000*2, timeout : 15000});
			}
		},
		
		bindEvent : function(){
			var _this = this,
				startEV = this.startEv ? 'click' : 'click';
			this.$views.on('click', '.list-item-li', $.proxy(this.detail, this));
			this.$views.on('click', '.nodata .refresh', function(){
				_this.refreshList({
					offset : 0
				}, 'refresh');
			})
			this.$views.on( startEV, '.map-icon', $.proxy(this.maplist, this));
		},
		
		initColumn : function(){		//路况分类
			var _this = this,
				tool = _this.tool,
				views = _this.$views;
			var url = tool.interface_tool( 'roadsort' );
			tool.ajax( url, null, function( data ){
				if( $.isArray( data ) && data[0]){
					data.unshift({id : 'top', title : '全部', color : '#000'});
					$.each(data, function(kk, vv){
						vv.isCurrent = (kk == 0);
					});
					var info = {
						column : data, 
						type : 'main',
						navbar : 'columnbar'
					}
					var column_html = tool.render(tool.tpl.column_tpl, info),
						tab_html = tool.render(tool.tpl.tab_tpl, info);
					$( column_html ).insertAfter( views.find('.navbar') );
					$( tab_html ).appendTo( views.find('.tabs') );
					var icon_html = tool.render( tool.tpl.map_tpl, {type : 'icon'} );
					_this.mapIcon = $( icon_html ).insertBefore( views.find('.page-content') ).data('list', data);
					_this.initLayout( data );	//实例化framework
					_this.initContent();
					_this.pullRefresh();
					_this.columnTab('column', views);		//tab切换事件
				}
			} );
		},
		
		/*广播列表*/
		initContent : function(){
			var active = this.$navbar.find('.tab-link.active');
			this.settleAjax( active );
		},
		
		settleAjax : function( $this, type, callback ){
			var id = $this.attr('_id');
			id = (id == 'top') ? '' : id;
			type = type || 'list';
			var info = {
				title : $this.html(),
				method : 'roadcondition',
				type : type
			}
			this.ajaxContent({
				sort_id : id,
				count : this.op.count,
				offset : 0
			}, info, callback);
		},
		
		pullRefresh : function(){
			var _this = this,
				news = this.$views;
			news.on({
				refresh : function( event ){
					_this.refreshList( {
						offset : 0,
					},  'refresh');
					setTimeout(function(){
						event.detail.done();
					}, 1000);
				},
				infinite : function(){
					area = news.find('.tab.active');
					if( area.find('.news-list li').length < _this.op.count ){
						return;
					}
					var offset = area.data('offset') || 20;
					if( offset == 'infinite' ){
						return;
					}
					offset += _this.op.count;
					area.data('offset', offset);
					_this.refreshList( {
						offset : offset
					}, 'infinite' );
				}
			});
		},
		
		refreshList : function( info, type ){
			var active = this.$navbar.find('.tab-link.active'),
				id = active.attr('_id');
			var param = {
				sort_id : (id == 'top') ? '' : id,
				count : this.op.count
			}
			param.offset = info.offset;
			this.ajaxContent( param, {
				title : active.html(),
				method : 'roadcondition',
				type : type
			});
		},
		
		ajaxContent : function( param, info, callback ){
			var _this = this,
				tool = _this.tool,
				service = _this.$service;
			var url = tool.interface_tool( info.method );
			info.sort_id = param.sort_id || 'top';
			var data = _this.mapIcon.data('list' + info.sort_id);
			if( $.isArray( data ) && data[0] ){
				this.ajaxBack( data, info );
				return;
			}
			tool.ajax( url, param, function( data ){
				if( $.isArray( data ) && data[0]){
					$.each(data, function(kk, vv){
						if( vv.datetime.indexOf(':') > -1 ){
							vv.create_time = vv.datetime;
						}else{
							vv.create_time = tool.transferTime( vv.create_time );
						}
						if( vv.pic instanceof Object && vv.pic.filename ){
							vv.index = _this.createImgsrc( vv.pic );
							(_this.roadPicDark || (_this.roadPicDark = {}))[vv.id] = $.myApp.photoBrowser({
						 		photos : [vv.index],
								theme:'dark',
								toolbar : false
							});
						}
						vv.img = _this.createImgsrc( vv.icon, {
							width : 35, height : 50
						} );
						vv.lng = vv.baidu_longitude;
						vv.lat = vv.baidu_latitude;
					});
					info.nodata = false;
				}else if( info.type != 'infinite' ){
					info.nodata = true;
				}
				var Sdata = data.slice(0, 20) || 'nodata';
				_this.mapIcon.data('list' + info.sort_id, Sdata);
				if( info.type == 'map' ){
					callback && $.isFunction( callback ) && callback( Sdata );
					return;
				}
				_this.ajaxBack( data, info );
			} );
		},
		
		ajaxBack : function( data, info ){
			var tool = this.tool;
			info.list = data;
			info.nodata = info.nodata || false;
			var html = tool.render(tool.tpl.list_tpl, info),
				area = this.$views.find( '#main' + info.sort_id );
			if( info.type == 'infinite' ){
				area = area.find('.news-list');
			}else{
				area.empty();
			}
			$( html ).appendTo( area );
			if( !data && info.type == 'infinite' ){
				area.data('offset', 'infinite');
			}
		},
		
		columnTab : function( type, page ){
			var _this = this;
			if( type == 'column' ){
				page.find('.tab').on({
					'show' : function( event ){
						var view = $(event.currentTarget);
						if( view.find('.live-list').length ){
							_this.showindicator();
							return;
						}
						var id = view[0].id.substring(4), 
							column = _this.$navbar.find('.tab-link[_id="' + id + '"]');
						_this.settleAjax( column );
					}
				});
			}
		},
		
		maplist : function( event ){
			var _this = this,
				tool = this.tool;
			var self = $(event.currentTarget);
			if( self.hasClass('map') ){
				var list = self.data('list'); 
				var map_html = tool.render( tool.tpl.map_tpl, {type : 'map', column : list, status : this.op.status} );
				var page = this.create.createContent( $.view.mainView, {
					title : '实时路况',
					className : 'roadMap'
				});
				$( map_html ).appendTo( page );
				var data = this.mapIcon.data('listtop');
				// var gpsPoint = new BMap.Point( this.point.lng, this.point.lat );
				// BMap.Convertor.translate( gpsPoint, 0, function( point ){
					// console.log( point );
					// //_this.initBmap( 'roadMap', point );
				// } );
				if( $.isArray( data ) && data[0] ){
					_this.initBmap( 'roadMap', {
						lng : data[0].lng,
						lat : data[0].lat
					}, function(){
						_this.drawRoad( data );
					} );
				}
				_this.initDetail( page, 'detail_tpl' );
			}
		},
		
		/*路况详情*/
		detail : function( event ){
			this.create.prevent( event );
			var _this = this,
				self = $(event.currentTarget),
				id = self.data('id');
			if( $(event.target).is('.pic') || $(event.target).closest('.pic').length ){
				if( top.mainStrap ){
					top.mainStrap.switchNavStatus('close');
				}
				_this.roadPicDark[id].open();
				return;
			}
			this.road_detail(self, {
				'road_id' : id
			});
		},
		
		road_detail : function( self, info ){
			var _this = this,
				tool = this.tool;
			var	url = tool.interface_tool( 'roaddetail' );
			if( tool.hasDisable( self ) ){
				return;
			}
			tool.addDisable( self );
			tool.ajax( url, info, function( json ){
				tool.removeDisable( self );
				if( $.isArray( json ) && json[0] ){
					var data = json[0];
					data.create_time = tool.transferTime( data.create_time );
					
					var html = tool.render( tool.tpl.list_tpl, {
						list : json,
						type : 'detail'
					} );
					var page = _this.create.createContent( $.view.mainView, {
						title : '路况详情',
						className : 'roadDetail'
					});
					if( top.mainStrap ){
						top.mainStrap.switchNavStatus('back');
					}
					$( html ).appendTo( page );
					_this.initBmap( 'mapDiv', {
						lat : data.baidu_latitude, 
						lng : data.baidu_longitude
					} );
				}
			});
		},

		initBmap : function( dom, point, callback ){
			var map = new BMap.Map( dom );
			if( point instanceof Object && point.lng && point.lat ){
				var point = new BMap.Point( point.lng, point.lat );
				map.centerAndZoom( point, 16 );
			}else{
				map.centerAndZoom(this.op.city_name,14);
			}
			map.addControl( new BMap.ZoomControl() ); 
			if( dom == 'roadMap'){
				this.roadMap = map;
				var rttCtrl = this.rttCtrl = new BMapLib.TrafficControl();
				map.addControl(rttCtrl);
			}else{
				var marker = new BMap.Marker(point);
				map.addOverlay(marker);
			}
			$.isFunction( callback ) && callback();
		},

		initDetail : function( page, type ){
			var _this = this,
				startEV = this.startEv ? 'click' : 'click';
			if( type == 'detail_tpl' ){
				page.on( startEV, '.map-road', function(){
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
				page.on( startEV, '.map-nav a', $.proxy(this.getRoad, this) );
			}
		},
		
		getRoad : function( event ){
			var _this = this,
				$this = $(event.currentTarget),
				id = $this.attr('_id');
			$.myApp.showIndicator();
			$this.addClass('selected').siblings().removeClass('selected');
			var data = this.mapIcon.data('list' + id );
			if( !data ){
				this.settleAjax( $this, 'map', function( data ){
					$.isArray( data ) && data[0] && _this.drawRoad( data );
				} );
			}else if( $.isArray( data ) && data[0] ){
				this.drawRoad( data );
			}else{
				$.myApp.hideIndicator();
			}
		},

		drawRoad : function( data ){
			var _this = this,
				map = this.roadMap;
			var point = new BMap.Point( data[0].lng, data[0].lat );
			map.centerAndZoom(point, 16 );
			map.clearOverlays();
			$.each(data, function(key, value){
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
			var _this = this,
				map = this.roadMap;
			$.myApp.hideIndicator();
			marker.addEventListener('click', function( e ){
				var center = new BMap.Point(param.lng, param.lat);
				map.panTo(center);
				_this.showmySquare( param );
				current = $('.roadMap').find('.map_move'); 
				current.find('.map_route').find('p').html( param.content );
				current.find('.map_route').find('em').html( param.create_time );
				current.data('point', param);
			});
		},
		
		showmySquare : function( param ){
			var _this = this,
				map = this.roadMap;
			this.mySquare && map.removeOverlay( this.mySquare );
			var mySquare = this.mySquare = new SquareOverlay( param, 350, 100 );
			map.addOverlay(mySquare);
			mySquare.addEventListener('click', function( e ){
				var target = $( e.target );
				_this.PopTips( target );
			});
		},
		
		PopTips : function( target ){
			var parent = target.closest('.map_move'),
				point = parent.data('point');
			this.road_detail( parent, {
				'road_id' : point.id
			});
		},
		
		showindicator : function(){
			$.myApp.showIndicator();
			this.tool.defer(500, function(){
				$.myApp.hideIndicator();
			});
		},
		
		initLayout : function( data ){
			var _this = this;
			this.$navbar = this.$views.find('.tabbar');
			var myApp = $.myApp = new Framework7({
				controlnavbar : true
			});
			var $$ = Framework7.$;
			$$(document).on('ajaxStart',function(){
				$.myApp.showIndicator();
			});
			$$(document).on('ajaxComplete',function(){
				$.myApp.hideIndicator();
			});
			$$(document).on('click', '.preloader-indicator-overlay', function () {
			    $.myApp.hideIndicator();
			});
			$.view = {};
			$.view['mainView'] = $.myApp.addView('.view-main', {
				dynamicNavbar: true
			});
		},
		
		itool : function(){
			var _this = this;
			var tool = this.tool = $.tool( this.$views );
			this.size = tool.getSize();
			this.create = $.create;
			this.startEv = this.create.isPC();
		},
		
		createImgsrc :function( data, options ){						//图片src创建
			var data = data || {},
			op_str = options ? [options.width, 'x', options.height, '/'].join('') : '';
			var src = [data.host, data.dir, op_str, data.filepath, data.filename].join('');
			return src;
		},
		
	})
	window.Road = Road; 
})(Zepto);
$(function(){
	var Road = new window.Road();
	Road.init();
});