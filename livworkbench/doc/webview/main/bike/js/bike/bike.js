(function( $ ) {
	var defaultOptions = {
		tabs : [{id : 'nearnest', title : '离我最近'}, {id : 'region', title : '区域筛选'}, {id : 'notice', title : '站点公告'}],
		detail : [{id : 'detail', title : '概括'}, {id : 'notice', title : '公告'}],
		label : ['main', 'second', 'third', 'fourth'],
		count : 20
	}
	function Bike( options ){
		this.$views = $('.views');
		this.point = {
			lng : 120.310796499252,
			lat: 31.490620682283,
		};
		this.op = $.extend({}, options, defaultOptions);
	}
	$.extend( Bike.prototype, {
		init : function(){
			this.itool();
			this.loading();
			this.bindEvent();
		},
		
		loading : function(){
			this.initColumn();
			this.getlocation();
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
				}, {maximumAge:60*1000*2, timeout : 3000});
			}
		},
		
		bindEvent : function(){
			var _this = this,
				startEV = this.startEv ? 'click' : 'touchstart';
			this.$views.on('click', '.bike-list li', $.proxy(this.detail, this));
			this.$views.on('click', '.nodata .refresh', function(){
				_this.refreshList({
					offset : 0
				}, 'refresh');
			})
		},
		
		initColumn : function(){		//自行车分类
			var _this = this,
				tool = _this.tool,
				views = _this.$views;
				
			var data = this.op.tabs;
			$.each(data, function(kk, vv){
				vv.isCurrent = (kk == 0);
			});
			var info = {
				column : data, 
				type : 'main',
				navbar : 'columnbar',
				num : 1
			}
			var tab_html = tool.render(tool.tpl.tab_tpl, info);
			//$( column_html ).insertAfter( views.find('.navbar') );
			$( tab_html ).appendTo( views.find('.page-content') );
			_this.initLayout( data );	//实例化framework
			_this.initContent();
			_this.pullRefresh();
			_this.columnTab('column', views);		//tab切换事件
		},
		
		/*广播列表*/
		initContent : function(){
			var active = this.$navbar.find('.tab-link.active');
			this.settleAjax( active, {
				baidu_latitude : this.point.lat,
				baidu_longitude : this.point.lng
			} );
		},
		
		settleAjax : function( $this, attr ){
			var info = {
				title : $this.html(),
				method : $this.attr('_id'),
				type : 'list',
				attr : $this.attr('_attr')
			};
			var param = $.extend({
				count : this.op.count,
				offset : 0
			}, attr);
			this.ajaxContent(param, info);
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
				infinite : function( event ){
					var target = $(event.target),
						area = target.find('.tab.active');
					if( area.find('.bike-list li').length < _this.op.count ){
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
					}, 'infinite', target );
				}
			});
		},
		
		refreshList : function( info, type, target ){
			var active = target.find('.tabbar').find('.tab-link.active'),
				method = active.attr('_id'),
				attr = active.attr('_attr');
			var param = {
				count : this.op.count,
				baidu_latitude : this.point.lat,
				baidu_longitude : this.point.lng
			}
			param.offset = info.offset;
			this.ajaxContent( param, {
				title : active.html(),
				method : method,
				type : type,
				attr : attr
			});
		},
		
		ajaxContent : function( param, info ){
			var _this = this,
				tool = _this.tool,
				service = _this.$service;
			var url = tool.interface_tool( info.method );
			
			tool.ajax( url, param, function( data ){
				if( $.isArray( data ) && data[0]){
					$.each(data, function(kk, vv){
						if( vv.station_icon ){
							vv.img = _this.createImgsrc( vv.station_icon, {
								width : 80, height : 50
							} );
						}
						if( vv.baidu_longitude && vv.baidu_latitude ){
							vv.lng = vv.baidu_longitude;
							vv.lat = vv.baidu_latitude;
						}
						if( vv.create_time ){
							vv.create_time = tool.transferTime( vv.create_time );
						}
					});
					info.nodata = false;
				}else if( info.type != 'infinite' ){
					info.nodata = true;
				}
				_this.ajaxBack( data, info );
			} );
		},
		
		ajaxBack : function( data, info ){
			var tool = this.tool;
			info.list = data;
			info.nodata = info.nodata || false;
			var html = tool.render(tool.tpl[info.method + '_tpl'], info),
				area = this.$views.find( '#' + info.attr + info.method );
			if( info.type == 'infinite' ){
				area = area.find('.bike-list');
			}else{
				area.empty();
			}
			$( html ).appendTo( area );
			if( !data && info.type == 'infinite' ){
				area.data('offset', 'infinite');
			}
		},
		
		columnTab : function( type, page, style ){
			var _this = this;
			if( type == 'column' ){
				page.find('.tab').on({
					'show' : function( event ){
						var view = $(event.currentTarget);
						page.find('.page-content')[0].scrollTop = 0;
						if( view.find('.bike-list').length ){
							_this.showindicator();
							return;
						}
						var id = view[0].id.substring(4), 
							column = _this.$navbar.find('.tab-link[_id="' + id + '"]');
						_this.settleAjax( column, {
							baidu_latitude : _this.point.lat,
							baidu_longitude : _this.point.lng
						} );
					}
				});
			}else if( type == 'detail' ){
				page.find('.tab').on({
					'show' : function( event ){
						var view = $(event.currentTarget),
							info = {};
						page[0].scrollTop = 0;
						if( view.find('.data-list').length ){
							_this.showindicator();
							return;
						}
						var id = view[0].id.substring(6), 
							column = _this.detailnav.find('.tab-link[_id="' + id + '"][_attr="' + type + '"]');
						if( id != 'notice' ){
							return;
						}
						var did = _this.detailnav.data('id');
						info[style] = did; 
						_this.settleAjax( column, info);
					}
				});
			}
		},
		
		/*路况详情*/
		detail : function( event ){
			this.create.prevent( event );
			var self = $(event.currentTarget),
				link = self.attr('_link'),
				id = self.data('id');
			switch( link ){
				case 'nearnest' : {
					var current = this.secondColumn( self, this.op.detail, 'station_id' );
					this.road_detail(current, {
						'station_id' : id
					});
					break;
				}
				case 'region' : {
					var region = this.op.tabs.concat();
					region.splice(1, 1);
					var current = this.secondColumn( self, region, 'region_id' );
					this.settleAjax(this.detailnav.find('.tab-link.active'), {
						'region_id' : id,
						baidu_latitude : this.point.lat,
						baidu_longitude : this.point.lng
					} );
					break;
				}
				case 'notice' : {
					var idName = self.closest('.tab.active')[0].id,
						num = (idName == 'mainnotice') ? 1 : 2;
					this.news_detail(self, id );
					break;
				}
			}
		},
		
		secondColumn : function( self, data, style ){
			var tool = this.tool;
			var title = self.find('.name a').html(),
				id = self.data('id');
			var className = (style == 'station_id') ? 'stationDetail' : 'stationList',
				num = self.closest('.tabs').data('page');
			var page = this.create.createContent( $.view.mainView, {
				title : title,
				className : className,
				infinite : true,
				defineBar : true,
				num : parseInt(num) + 1
			});
			$.each(data, function(kk, vv){
				vv.isCurrent = (kk == 0);
			});
			var info = {
				column : data, 
				type : 'detail',
				navbar : 'detailbar',
				dataid : id,
				num : parseInt(num) + 1
			}
			var tab_html = tool.render(tool.tpl.tab_tpl, info);
			// this.detailnav = $( column_html ).insertBefore( page );
			// $( '<div class="tabs" data-page="' + (parseInt(num) + 1) + '">' + tab_html + '</div>' ).appendTo( page );
			$( tab_html ).appendTo( page );
			this.detailnav = page.find('.detailbar');
			this.columnTab( 'detail', page, style );
			var current = page.find('.tab.active');
			if( top.mainStrap ){
				top.mainStrap.switchNavStatus('back');
			}
			return current;
		},
		
		road_detail : function( active, info ){
			var _this = this,
				tool = this.tool;
			var	url = tool.interface_tool( 'detail' );
			tool.ajax( url, info, function( json ){
				if( $.isArray( json ) && json[0] ){
					var data = json[0];
					data.img = _this.createImgsrc( data.indexpic, {
						width : 120, height : 150
					} );
					
					var html = tool.render( tool.tpl.station_detail, data);
					$( html ).appendTo( active );
					_this.initBmap( 'mapDiv', {
						lat : data.latitude,
						lng  :data.longitude
					} );
					_this.initDetail( active, 'detail_tpl' );
				}
			});
		},

		news_detail : function( self, id ){
			var _this = this,
				tool = this.tool;
			var	url = tool.interface_tool( 'notice' );
			tool.ajax( url, {id : id}, function( json ){
				if( $.isArray( json ) && json[0] ){
					var data = json[0],
						num = self.closest('.tabs').data('page');
					var page = _this.create.createContent( $.view.mainView, {
						title : '公告',
						className : 'noticeDetail',
						num : parseInt( num ) + 1
					});
					data['create_time'] = tool.transferTime( data['create_time'] );
					var html = tool.render( tool.tpl.notice_detail, data);
					if( top.mainStrap ){
						top.mainStrap.switchNavStatus('back');
					}
					$( html ).appendTo( page );
				}
			});
		},
		
		initBmap : function( dom, point ){
			var map = new BMap.Map( dom );
			var point = new BMap.Point( point.lng, point.lat );
			map.centerAndZoom( point, 16 );
			if( dom == 'mapDiv' ){
				var marker = new BMap.Marker(point);
				map.addOverlay(marker);
			}else{
				var bpoint = new BMap.Point(this.point.lng , this.point.lat);
				var driving = new BMap.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});
				driving.search( bpoint , point);
			}
		},

		initDetail : function( page, type ){
			var _this = this,
				tool = _this.tool,
				startEV = this.startEv ? 'click' : 'touchstart';
			if( type == 'detail_tpl' ){
				page.on( startEV, '.gohere', function(){
					var $this = $(this),
						num = page.closest('.tabs').data('page');
					var wrap = _this.create.createContent( $.view.mainView, {
						title : '到这里去',
						className : 'goHere',
						num : parseInt( num ) + 1
					});
					var html = tool.render( tool.tpl.goHere_tpl, {idName : 'goHere'});
					if( top.mainStrap ){
						top.mainStrap.switchNavStatus('back');
					}
					$( html ).appendTo( wrap );
					_this.initBmap( 'goHere', {
						lat : $this.data('lat'),
						lng  :$this.data('lng')
					} );
				});
			}
		},
		
		initMarker : function(marker, param){
			var _this = this, current,
				map = this.roadMap;
			marker.addEventListener('click', function(e){
				var center = new BMap.Point(param.lng, param.lat);
				map.panTo(center);
				var opts = {
					width : 350,
					height : 60,
					title : param.create_time,
					enableAutoPan : true,
					enableCloseOnClick : true
				};
				var infoWindow = new BMap.InfoWindow( param.content, opts );
				map.openInfoWindow( infoWindow, center );
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
	window.Bike = Bike; 
})(Zepto);
$(function(){
	var Bike = new window.Bike();
	Bike.init();
});