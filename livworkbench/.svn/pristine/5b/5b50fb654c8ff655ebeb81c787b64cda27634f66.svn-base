;(function($){
	var defaultOptions = {
		baseUrl : 'http://api.139mall.net:8081/data/cmc/',
		key : '?appkey=4fJrS03Ergz9KE1ztJ5vNmrnmZgt0moU&appid=20',
		interface_method : {					//接口配置方法
			subwayList : 'subwayList.php',		//线网规划,站点信息
			subwaySite : 'subwaySite.php',		//线网规划定位信息, 站点信息附近, 关键字查询站点
			subwaySiteInfo : 'subwaySiteInfo.php',		//站点详情
			subwayInfo : 'subwayInfo.php',				//线路列表
			subwayServiceSort : 'subwayServiceSort.php', 	//服务资讯
			subwayServiceList : 'subwayServiceList.php',	//通知个数, 服务资讯列表
			subwaySiteGate : 'subwaySiteGate.php',			//站点出入口信息
			subwaySiteService : 'subwaySiteService.php',	//站点服务设施
			item : 'item.php'								//服务资讯详情页
		},
		more_title : '点击加载更多',
		count : 20,
		defaultSize : [3407, 2977],
		scales : {
			1 : [1500,1311],
			2 : [2000,1748],
			3 : [2400,2097]
		}
	};
	
	function Subway( el, options ){
		var _this = this;
		this.options = $.extend( {}, options, defaultOptions );
		this.el = el;
		this.head = el.find('.ui-bae-header');
		this.subnav = el.find('.subnav');

		this.showLoading();
		
		this.tpl = $.templete;
		// this.point = {
			// x : 118.787401,
			// y: 31.985913,
		// };
		
		this.mapArea_box = el.find('.subway-map-box');
		this.mapArea_inner = this.mapArea_box.find('.subway-map-inner');
		this.mapAreaImg = this.mapArea_box.find('img');
		this.mapArea = this.mapArea_box.find('#subway-mapArea');
		
		this.init();
	}
	
	Subway.prototype = new Layout();
	$.extend( Subway.prototype, {
		init : function(){
			var _this = this;
			this.wrap = this.el.find('.tab-wrap');
			
			this.initnavigator(this.subnav, 3);
			this.initMetroMap();
			this.initMapArea(1);
			this.initIscroll();
			this.getNoticeNum();
			this.initDialog();
			
			setTimeout(function(){
				_this.rootNetwork();
			}, 100);
			setTimeout(function(){
				_this.rootFirst();
				_this.rootSecond();
			}, 200);
		},
		
		rootNetwork : function(){
			var _this = this;
			this.el
				.on('touchstart', '.subnav li', function(){	//栏目切换事件,根据栏目取对应的列表数据
					var self = $(this);
					if( $(this).hasClass('selected') ) return;
					$(this).addClass('selected').siblings().removeClass('selected');
					if( self.is('.route-terminal') ){
						_this.routeColumn( self );
					}else if( self.is('.classify-item') ){
						_this.serviceColumn({
							type : self.data('type'),
							param : {offset : 0, sort_id : self.data('id')}
						}, null);
					}else{
						var index = self.index();
						_this.tabColumn( self.data('type'), index);
					}
				});
			this.mapArea_box
				.on( 'touchend', '.area-circle', function( event ){
					event.stopPropagation(); 
					var self = $(this);
					_this.scaleMapArea('stationClick', function(){
						_this.stationClickCallback( self, true );
					} );
					return false;
				} );

			this.mapArea_box.on('touchstart', '.network-nearby', function( event ){
				event.stopPropagation(); 
				$(this).fadeOut();
			});

			// 线网规划
			this.mapArea_box.on('touchstart', '.pic-btns a', function( event ){
				var self = $(event.currentTarget),
					className = self[0].className;
				switch( className ){
					case 'fullpic-add' : {
						_this.controlArea('add');
						break;
					}
					case 'fullpic-reduction' : {
						_this.controlArea('reduce');
						break;
					}
					case 'fullpic-location' : {
						_this.location_nearby();
						break;
					}
					case 'fullpic-notice' : {
						var service = _this.subnav.find('li[data-type="service"]');
						service.addClass('selected').siblings().removeClass('selected');
						_this.tabColumn( 'service', 2);
						self.closest('.pic-btns').hide();
						break;
					}
				}
			});
			this.mapArea_box.on( 'touchend', '.map-area-box',function( event ){
				//_this.tip( $(event.target)[0].className );
				if( $(event.target).is('.area-circle') || $(event.target).is('.station-pop')  || $(event.target).closest('.station-pop').length ){
					return false;
				}
				var station_pop = $(this).next('.map-pop-box').find('.station-pop.open');
				if( station_pop.length ){
					station_pop.removeClass('open');
				}
			} );
			this.mapArea_box.on('touchstart', '.station-pop', function( event ){
				event.stopPropagation();
				var target = $(event.target);
				if( target.is('.title') ){
					_this.stationInfo( $(this) );
				}else if( target.is('.access') ){
					_this.skipThird( target, 'access' );
				}else{
					_this.skipThird( target, 'facilities' );
				}
				
			});
			this.el.on( 'click', '.ui-bae-go-back', function( event ){			//退出应用事件，绑定在主页的back按钮上
				if( $(this).hasClass('goPrevPage') ) return;
				event.stopPropagation();
				Widget.close();
			} );
		},
		
		rootFirst : function(){
			var _this = this;
			this.el					//站点信息
				.on( 'focus input propertychange', '.common-search-input', $.proxy(_this.searchStation,_this))	//监听站点查询 input
				.on('touchstart', '.handle-btn', function( event ){		//清空输入框
					var self = $(event.currentTarget),
						input = self.prev('.common-search-input');
					input.val('');
				})
				.on('touchstart', '.item-station', function( event ){	//附近站点，线路查询
					var self = $(event.currentTarget),
						area = self.closest('.item-area');
					if( area.hasClass('line-area') ){
						return;
					}
					if( area.length && area.hasClass('station-area') ){
						_this.stationInfo( self );
					}else if(area.length && area.hasClass('route-area')){
						_this.routeList( self );
					}
				})
				.on( 'click', '.station-search-result', $.proxy( _this.stationRoute,_this ));	//请求该站点下的线路;	
			this.el					//服务资讯
				.on('click' , '.ticket-list li' , function( event ){
					var self = $(event.currentTarget);
					_this.servicedetail( self );
					event.stopPropagation();
				})
				.on('click', '.pic-slider-box',function( event ){
					event.stopPropagation();
					$('.pic-slider-box').remove();
				})
		},
		
		rootSecond : function(){
			var _this = this;
			this.el
				.on('click', '.item-detail', function( event ){
					var self = $(event.currentTarget);
					_this.stationInfo( self );
					event.stopPropagation();
				})
				.on('touchstart','.set-font-size span',function(event){           /*设置字体*/
					var self = $(event.currentTarget);
					_this.setFont( self );
				});
			
			this.el
				.on( 'click', '.goPrevPage', function( event ){				//详情页切换到列表页
					_this.backPage( $(this) );
					event.stopPropagation();
				} );
			this.style_bug();	
			this.Storage = new Hg_localstorage( {key : 'fontsize'} );
		},
		
		style_bug : function(){
			if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				$('<style/>').html( this.tpl.style_bug ).appendTo( this.el );
			}
		},
		
		/*main layout*/
		tabColumn : function( type, index ){
			var theMatch = this.wrap;
			var current = theMatch.children().eq( index );
			this.showLoading();
			theMatch.children().removeClass('visible');
			current.addClass('visible');
			switch( type ){
				case 'network' : {
					if( this.networkInit ){
						this.closeLoading();
					}else{
						this.initMapArea(1, true);	//第一次进来没来得及加载线网规划，重新加载
					}
					break;
				}
				case 'station' : {
					if( this.lineInit && this.nearbyInit ){
						theMatch.find('.fuzzy-matching ul').hide();
						this.closeLoading();
					}else{
						this.stationBox();
					}
					break;
				}
				case 'service' : {
					if( this.serviceInit ){
						this.closeLoading();
					}else{
						this.serviceBox();
					}
					break;
				}
			}
		},
		
		/*站点信息 start*/
		stationBox : function(){
			this.wrap.find('.station-wrap').empty().append( this.tpl.station_tpl );
			this.stationline();
		},
		
		stationnearby : function(){			//附近站点数据
			var _this = this;
			if( !_this.globalNearbyInfo ){
				this.location(function( point, callback ){
					_this.getNearbyInfo( point, callback );
				}, function( json ){
					_this.showNearby( json );
				});
			}else{
				_this.showNearby();
			}
		},
		
		getNearbyInfo : function( point, callback ){
			var _this = this;
			var url = this.interface_tool( 'subwaySite' );
			this.ajax( url, point, function( json ){
				if( $.isArray( json ) && json.length && json[0] ){
					_this.globalNearbyInfo = json;
					callback && callback( json );
				}
			} );
		},
		
		showNearby : function( json ){
			var _this = this;
			json = json || this.globalNearbyInfo; 
			var tpl = this.tpl['station_area_tpl'];
			this.wrap.find('.station-area').show().append( $.parseTpl(tpl, json[0]) );
			this.nearbyInit = true;
			setTimeout(function(){
				_this.closeLoading();
			}, 500);
		},
		
		stationline : function(){			//地铁线路数据
			var _this = this;
			var url = this.interface_tool( 'subwayList' );
			
			if( !this.globalStationList ){
				this.ajax( url, null, function( json ){
					_this.lineBack( json );
				} );
			}else{
				this.lineBack();
			}
		},
		
		lineBack : function( json ){
			var html = '';
			var func = $.parseTpl( this.tpl.route_area_tpl );
			var json = json || this.globalSubWayData;
			if( $.isArray( json ) && json.length && json[0] ){
				$.each(json, function(k,v){
					v.line = v.title.substring(0, 1);
					html += func(v);
				});
				this.wrap.find('.route-area').append( html );
				this.lineInit = true;
				!this.nearbyInit && this.stationnearby();
			}
		},
		
		stationInfo : function( self, callback ){		//站点详情
			var _this = this;
			var page = this.goNext( self.attr('_title'), self );
			var url = this.interface_tool( 'subwaySiteInfo' ),
				id = self.attr('_id');
			this.ajax( url, {id : id}, function( json ){
				var tpl = _this.tpl['station_info'];
				if( $.isArray( json ) && json.length && json[0]){
					var data = json[0];
					var slider_size = _this.size.width - 30;
					if( data.indexpic && $.isArray(data.indexpic) && data.indexpic[0] ){
						$.each(data.indexpic, function(k, v){
							v.src = _this.createImgsrc(v, {
								width : slider_size,
								height : 240
							});
						});
					}
					page.find('.detail-wrap').append( $.parseTpl(tpl, data) );
					callback && $.isFunction( callback ) && callback( page );
					page.find('.slider').length && page.find('.slider').slider( { 
						imgZoom: true,
						viewNum  :1,
						travelSize : 1,
					});
					var slider_input = page.find('.station-pic').height() - 30;
					slider_input = slider_input > 0 ? slider_input : 0;
					_this.initdetail( page.find('.detail-wrap') );
					_this.initListScroll( page.find('.station-plat'), slider_input);
				}
				_this.closeLoading();
			} );
		},
		
		routeList : function( self ){		//线路列表
			var _this = this,
				id = self.attr('_id');
			var page = this.goNext( '地铁', self ),
				detailwrap = page.find('.detail-wrap');
			var url = this.interface_tool( 'subwayInfo' );
			
			this.ajax( url, {id : id, need_site : 1}, function( json ){
				if( $.isArray( json ) && json.length ){
					detailwrap.data('json', json[0]);
					detailwrap.append( $.parseTpl(_this.tpl.route_detail, json[0]) );
					_this.initnavigator(detailwrap.find('.route-subnav'), 2);
					_this.routeInfo(detailwrap, 0);
				}
			});
		},
		
		routeInfo : function( detailwrap, index ){	//线路列表tpl
			var html = '', _this = this,
				json = detailwrap.data('json');
			var func = $.parseTpl( this.tpl.route_detail_tpl ),
				data = index ? json['site_info']['end'] : json['site_info']['start'];
			$.each( data, function(kk,vv){
				vv.linecolor = _this.handleLinecolor( vv.sub_color, json['title'] );
				html += func( vv );
			} );
			detailwrap.find('.route-list').append( html );
			var routeinfo = detailwrap.find('.route-info').height() - 20;
			this.initListScroll( detailwrap.find('.route-list-wrap'), routeinfo);
			this.closeLoading();
		},
		
		handleLinecolor : function( color, title ){
			var colorline = [];
			if( title && color.length == 1){
				colorline.push(title.substring(0, 1));
			}else{
				$.each(this.linecolor, function(k, v){
					if($.inArray(v, color) > -1){
						colorline.push(k);
					}
				});
			}
			return colorline;
		},
		
		routeColumn : function( self ){
			var detailwrap = self.closest('.detail-wrap'),
				index = self.index();
			this.showLoading();
			$('<div class="route-list-wrap"><ul class="route-list"></ul></div>').appendTo( detailwrap.find('.line-area').empty() );
			this.routeInfo(detailwrap, index);
		},
		
		searchStation : function( event ){		//搜索站点
			var _this = this,
				self = $(event.currentTarget),
				area = self.closest('.search-area');
			var val = self.val(), list_html = '',
				url = this.interface_tool( 'subwaySite' );
			var ulBox = area.find('.fuzzy-matching ul').show();
			if( val ){
				this.ajax( url, {title : val}, function( json ){
					if( $.isArray( json ) && json.length && json[0]){
						var list_func = $.parseTpl( _this.tpl.station_result_tpl );
						$.each(json, function(k, v){
							list_html += list_func( v );
						});
						ulBox.empty().append( list_html );
					}
				});
			}else{
				var stationitem = this.wrap.find('.station-area').find('.item-station');
				ulBox.empty().append( $.parseTpl( _this.tpl.station_result_tpl, {
					title : stationitem.attr('_title'),
					id : stationitem.attr('_id')
				} ) );
			}
		},
		
		stationRoute : function(event){
			var _this = this,
				self = $(event.currentTarget),
				area = self.closest('.search-area');
			area.find('.fuzzy-matching ul').hide();
			this.stationInfo( self );
		},
		
		/*实例化*/
		initdetail : function( detailwrap ){
			var _this = this;
			detailwrap.find('.station-btn a').click(function( event ){
				var $this = $(this),
					type = $this.attr('_attr');
				if( type == 'streetscape' ){
					var tpl = '<div class="mapbox">' + 
						'<div class="map-innner" id="panorama"></div>' +
						'<div class="map-innner" id="normal_map"></div>' +
						'</div>'; 
					_this.init_map($this, tpl, [0.7, 0.3])
				}else{
					_this.typeInfo( $this, type )
				}
				event.stopPropagation();
			});
			
			detailwrap.find('.metro_map_btn').on('touchstart', function(){			//地图去这里
				var tpl = '<div class="mapbox"><div class="map-innner" id="gomap"></div></div>'; 
				_this.init_map( $(this), tpl, [1]);
			});
		},
		
		event_Access : function( detailwrap ){
			var _this = this;
			var delaytime = (/ipad|iphone|mac/i.test(navigator.userAgent)) ? 0 : 500; 
			/*出入口信息*/
			detailwrap.find('.around-item .item-title').on('click', function(){
				var $this = $(this),
					item = $this.closest('.access-item');
				setTimeout(function(){
					var isadd = item.hasClass('toggle-add');
					if( isadd ){
						item.closest('.m2o-flex').siblings().find('.around-item')[(isadd ? 'add' : 'remove') + 'Class']('toggle-add');
					}
					item.toggleClass('toggle-add');
				}, delaytime);
			});
			
			detailwrap.find('.realpic-item .slider-item').on('click', function(){
				var $this = $(this);
				var sliderBox = $this.closest('.access-slider');
				setTimeout(function(){
					_this.sliderPic( $this.find('img'), sliderBox.find('.slider-item') );
				}, delaytime);
			});
			
			detailwrap.on('click', '.slider-icon', function( event ){
				var self = $(event.currentTarget),
					father = self.closest('.realpic-item'),
					slider = father.find('.access-slider');
				if( self.is('.slider-pre') ){
					slider.slider('prev');
					_this.showAttr( father, -1 );
				}else{
					slider.slider('next');
					_this.showAttr( father, 1 );
				}
			});
		},
		
		init_map : function( self, tpl, ratio ){
			var site = {},
				size = this.size;
			site.x = self.attr('_lng');
			site.y = self.attr('_lat');
			site.title = self.attr('_title');
			var page = this.goNext(site.title, self),
				head_height = page.find('.ui-bae-header').height();
			var mapbox = $( tpl ).appendTo( page.find('.detail-wrap') );
			mapbox.find('.map-innner').each(function(k){
				$(this).css({
					width : size.width + 'px',
					height : ratio[k] * (size.height - head_height) + 'px',
				})
			});
			(ratio[0] < 1) ? this.goPanorama( site ) : this.goHere( site );
		}, 
		
		getMaxHeight : function( page ){
			var maxHeight = 0;
			page.find('.around-item').each(function(){
				var height = $(this).find('.around-list')[0].scrollHeight;
				$(this).find('.around-list').height( height );
				maxHeight = (height > maxHeight) ? height : maxHeight;
			});
			return maxHeight;
		},
		
		typeInfo : function( self, type){		//出入口信息，//服务设施
			var _this = this, str_html = '',
				id = self.closest('.station-btn').attr('_id');
			var str = self.html(),
				page = this.goNext(str, self);
			var detailwrap = page.find('.detail-wrap'),
				tpl = (type == 'access') ? 'subwaySiteGate' : 'subwaySiteService';
			detailwrap.append( $.parseTpl( this.tpl.access_info, {type : type} ) );

			this.ajax( this.interface_tool( tpl ), {id : id}, function( json ){
				if( $.isArray( json ) && json.length ){
					var str_func = $.parseTpl( _this.tpl[type + '_tpl'] );
					$.each(json, function(k, v){
						v.title = v.title || '';
						if( v.indexpic ){
							var Srealpic = _this.getRealPic(v.indexpic); 
							v.srealpic = v.indexpic && Srealpic;
						}
						str_html += str_func( v );
					});
					detailwrap.find('.' + type + '-inner').append( str_html );
					
					if( type == 'access' ){
						setTimeout(function(){
							detailwrap.find('.realpic-item').length && _this.initAccessPic( detailwrap );
							var maxheight = _this.getMaxHeight( page );
							detailwrap.find('.access-inner').css('padding-bottom', maxheight);
							_this.event_Access( detailwrap );
						}, 100);
					}
					setTimeout(function(){
						_this.initListScroll( detailwrap.find('.' + type + '-wrap'), false);
						_this.closeLoading();
					}, 300);
				}
			});
		},
		
		getRealPic : function( indexpic ){
			var Srealpic = [];
			var _this = this;
			var size = this.size,
				img_wid = parseInt((size.width - 160)/3 - 10);
			$.each(indexpic, function(k, v){
				var srealpic = _this.createImgsrc(v, {
					width : img_wid,
					height : 78
				})
				Srealpic.push( srealpic );
			});
			return Srealpic;
		},
		
		initAccessPic : function( detailwrap ){
			detailwrap.find('.access-list').each(function( i ){
				var slider = $(this).find('.access-slider');
				if( !slider.hasClass('column-item') ){
					slider.slider({
						autoPlay : false,
						viewNum  :3,
						travelSize : 1,
						dots : false, 
					});
				}
			});
		},
		
		showAttr : function(father, num){
			var next = father.find('.slider-next'),
				next_attr = parseInt( next.attr('_attr') ),
				pre = father.find('.slider-pre'),
				pre_attr = parseInt( pre.attr('_attr') );
			next_attr = next_attr - num;
			pre_attr = pre_attr + num;
			next.attr('_attr', next_attr);
			pre.attr('_attr', pre_attr);
			if( next_attr == 2 ){
				next.hide();
			}else{
				next.show();
			}
			if( pre_attr == 0 ){
				pre.hide();
			}else{
				pre.show();
			}
		},
		
		goPanorama : function( site ){
			//全景图展示
			var panorama = new BMap.Panorama('panorama');
			panorama.setPosition(new BMap.Point(site.x, site.y)); //根据经纬度坐标展示全景图
			panorama.setPov({heading: -40, pitch: 6});
			
			panorama.addEventListener('position_changed', function(e){ //全景图位置改变后，普通地图中心点也随之改变
			    var pos = panorama.getPosition();
			    map.setCenter(new BMap.Point(pos.lng, pos.lat));
			    marker.setPosition(pos);
			});
			
			//普通地图展示
			var mapOption = {
			        mapType: BMAP_NORMAL_MAP,
			        maxZoom: 18,
			        drawMargin:0,
			        enableFulltimeSpotClick: true,
			        enableHighResolution:true
			    }
			var map = new BMap.Map("normal_map", mapOption);
			var testpoint = new BMap.Point(site.x, site.y);
			map.centerAndZoom(testpoint, 18);
			var marker=new BMap.Marker(testpoint);
			marker.enableDragging();
			map.addOverlay(marker);  
			marker.addEventListener('dragend',function(e){
			    panorama.setPosition(e.point); //拖动marker后，全景图位置也随着改变
			    panorama.setPov({heading: -40, pitch: 6});}
			);
			this.closeLoading();
		},
		
		goHere : function( site ){
			var map = new BMap.Map("gomap");
			var bpoint = new BMap.Point(site.x , site.y);
			var point = new BMap.Point(this.point.x , this.point.y);
			map.centerAndZoom(point, 16);
			map.addControl(new BMap.NavigationControl()); 
			var driving = new BMap.DrivingRoute(map, {renderOptions:
				{map: map, autoViewport: true}
			});
			driving.search( point, bpoint);
			this.closeLoading();
		},
		
		skipThird : function( self, type ){
			var _this = this;
			this.stationInfo( self.closest('.station-pop'), function( page ){
				var dom = page.find('.station-btn a[_attr="' + type + '"]');
				_this.typeInfo( dom, type );
			} );
		},
		
		
		/*站点信息 end    
		 *
		 * 服务信息start */
		serviceBox : function(){													/*二级导航*/
			var _this = this;
			var url = this.interface_tool( 'subwayServiceSort' );
			var tpl = this.tpl[ 'service_tpl']; 
			this.ajax( url, null, function( json ){
				if( $.isArray( json ) && json.length && json[0]){
					var info = {};
					info.secondnav = json;
					 _this.wrap.find('.service-wrap').append( $.parseTpl(  _this.tpl['service_tpl'], info ) );
					_this.initnavigator(_this.wrap.find('.secondnav'), 4);
					
					_this.serviceInit = true;
					
					_this.initService();
					_this.restoreListScroll( false );
					
					_this.closeLoading();
					
					var current = _this.wrap.find('.classify-item.ui-state-active');
					_this.serviceColumn({
						type : current.data('type'),
						param : {offset : 0, sort_id : current.data('id')}
					}, null)
				}
			} );
		},
		
		initService : function(){
			this.column = this.wrap.find('.secondnav');
			this.servicewrap = this.wrap.find('.ticket-wrap');
		},
		
		serviceColumn : function(options , callback){											/*实例化列表*/
			var _this = this;
			var url = this.interface_tool( 'subwayServiceList' );
			this.showLoading();
			this.ajax( url, options.param, function( json ){
				options.len = ($.isArray( json ) && json.length) || 0;
				options.callback = callback;
				options.tpl = (options.type == 'list') ?  'service_list' : 'service_img_list';
				_this.serviceBack( json, options );
			} );
		},
		
		serviceBack : function(json, options){
			var _this = this,
				html_str = '',
				parseTpl_func = $.parseTpl( this.tpl[options.tpl] );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			if( !options['ismore'] && this.listScroll ){			//如果是栏目切换，会根据传过来的ismore参数进行重置list的refresh组件,ismore的含义是代表是否是加载更多触发的
				this.restoreListScroll( true );
			}
			var size_width = Math.floor(this.size.width /2 - 40);
			if( $.isArray( json ) && json.length ){
				$.each( json, function( key, value ){
					value.img  = _this.createImgsrc( value['indexpic'], {
						height : value.indexpic.imgheight,
						width : value.indexpic.imgwidth
					} );
					value.img_width = size_width;
					html_str += parseTpl_func( value );
				} );
			}
			this.servicewrap.find('.ticket-list')[options.dir == 'up' ? 'prepend' : 'append']( html_str );
			this.closeLoading();
			if( $.isFunction( options.callback ) ){					//如果渲染完列表数据后，需要执行回调，执行回调函数，比如加载更多后会执行刷新组件回调
				options.callback( options );
			}else{
				this.setRefreshBtn( options );						//如果是正常页面加载，根据options里的参数来设置加载更多按钮的参数以及决定它是否显示
			}
			if( !options['ismore']  ){								//如果是首次加载加载页面，dom渲染完后初始化list的refresh组件
				var second_height = this.column.height();
				this.initListScroll( this.servicewrap.find('.ticket-inner'), second_height + 30, true );
			}
		},
		
		restoreListScroll : function( type ){		//重置list的refresh组件，因为ajax切换栏目时要把原来的滚动条高度以及滚动条的top位置都要置为初始才能正常浏览
			type && this.servicewrap.empty();
			var refresh_wrap = $.parseTpl( this.tpl.service_list_tpl, {noevent : true} );
			$( refresh_wrap ).appendTo( this.servicewrap );
			type && this.initService();			//重置list的refresh组件时会把list的外围dom移除掉，所以要调用initDom重新设置一次
		},
		
		setRefreshBtn : function( options ){	//设置加载更多按钮的参数配置，如它的method,param等，如已没有更多，把他的参数配置置为null
			var len = options.len || 0;
			var ajaxRefreshBtn = this.servicewrap.find('.ui-refresh-btn');
			ajaxRefreshBtn.show().data('options',options);
			if( len < this.options.count ){
				ajaxRefreshBtn.eq(1).hide().data('options',null);
			}
		},
		
		servicedetail : function( self ){													/*服务资讯详情*/
			var id = self.data('id');
			var _this = this, 
				title = _this.el.find('.classify-item.ui-state-active').text();
			var page = this.goNext( title , self ),
				content_box = page.find('.detail-wrap');
			var url = this.interface_tool( 'item' ) + '&module_id=news';
			this.ajax( url, {id : id}, function( json ){
				var data = json[0];
				data.fontsize = _this.Storage.getItem('fontsize')[0] || 'small';							/*获得本地存储字体*/
				page.find('.detail-wrap').append( $.parseTpl( _this.tpl.service_detail_tpl , data ) );
				
				var fontsize = page.find('.set-font-size').show();									/*设置字体选项*/
				data.fontsize && fontsize.find('span.' + data.fontsize).addClass('selected').siblings().removeClass('selected');
				if( data.material ){
					_this.initPic( page, data.material );
				}else{
					setTimeout(function(){
						_this.closeLoading();
					}, 300);
				}
				content_box.find('a').remove();
				var delaytime = (/ipad|iphone|mac/i.test(navigator.userAgent)) ? 0 : 500; 
				
				content_box.on( 'tap', '.img-box', function(){		//详情页大图查看
					var $this = $(this);
					var pics = content_box.find('.content-box').find('.img-box');
					setTimeout(function(){
						_this.sliderPic( $this.find('img'), pics );
					}, delaytime);
				});
			});
		},
		
		initPic : function(page, material){
			var _this = this;
			var src_arr = [], number=0;
			var wid = this.size.width - 100,
				hei = this.size.height - 50;
			$.each( material , function( key ,value ){
				var src = _this.createImgsrc( value['pic'] );
				src_arr.push( src );
				page.find('.detail-wrap').find('div[m2o_mark="pic_' + key + '"]' ).replaceWith( '<div class="img-box"><img src ="' + src + '" _key="'+ key +'" _size="' + wid + 'x'+ hei +'"/></div>' );
			});	
			if( src_arr.length ){									
				$.each( src_arr , function( key,value ){
					var img = $('<img />').attr('src', value);
					img.on('load', function(){
						number ++ ;
						if( number == src_arr.length ){
							_this.initListScroll( page.find('.content-box'), false);			//详情页如果有图片资源，要等到图片load完后在初始化详情页的scroll
							_this.closeLoading();
						}
					});
				} );					
			}else{
				_this.initListScroll( page.find('.content-box'), false);
				_this.closeLoading();
			}
		},
		
		setFont : function( self ){
			var _this = this;
			var body = self.closest('.transition[_attr]'),
				article = body.find('.content-box').find('article');
			var attr = self.attr('_attr');
			self.addClass('selected').siblings().removeClass('selected');
			article.length && article.removeClass().addClass('notice ' + attr);
			this.Storage.resetItem('fontsize', [attr]);
			if( article.length ){											/*字体改变后，内容宽度需重新实例化*/
				var html = body.find('.content-box').html();
				this.showLoading();
				body.find('.content-box').parent().remove();
				body.find('.detail-wrap').append('<div class="content-box">' + html + '</div>');
				_this.initListScroll( body.find('.content-box'), false);
				_this.initdetail( body.find('.content-box') );
				setTimeout(function(){
					_this.closeLoading();
				}, 500);
			}
		},
		
		sliderPic : function( self, sliderItem ){		//点击查看大图
			var size = this.size;
			var slideOption = { autoPlay : false , dots : false, viewNum  :1,
					travelSize : 1};
			slideOption.index = parseInt( self.attr('_key') ) || 0;
			var page = self.closest('.transition[_attr]');
			var AsliderPic = sliderItem.map(function(){
				var img = $(this).find('img'),
					size = img.attr('_size'),
					src = img.attr('src');
				return {
					src : src.replace(/\d+x\d+/g, size),
				};
			}).get();
			page.append( $.parseTpl( this.tpl.slider_bigpic, {sliderPic : AsliderPic} ) );
			
			page.find('.pic-slider-box .slider-item').css({
				'height' : size.height - 50
			});
			page.find('.pic-slider-box img').css({
				'max-height' : size.height - 50,
				'max-width' : size.width - 100
			})
			page.find('.pic-slider-box .pic-slide').slider( slideOption );
		},
		
		/*服务信息end 
		 *
		 *线网规划start*/
		initMetroMap : function(){
			var _this = this;
			var imgDom = this.mapArea_box.find('img');
			var img = new Image();
			img.src = imgDom[0].src;
			img.onload = function(){
				_this.metroMapInit = true;
			}
		},
		
		initMapArea : function( type, style ){										//初始化线网规划中地铁线路map
			var  _this = this,
				url = this.interface_tool( 'subwayList' );
			this.setmapAreaInner( type );
			this.ajax( url, { need_site : 1 },function( data ){
				_this.callLocation();									//调用手机客户端提供的发起定位请求
				var station_list = _this.handleMapSite( data );
				_this.globalSubWayData = data;
				_this.networkInit = true;
				_this.globalStationList = station_list;
				
				_this.mapAreaRender( station_list, function(){
					if( style ){
						_this.location_nearby();
					}else{
						while( _this.metroMapInit ){
							_this.location_nearby();
							_this.metroMapInit = false;
						}
					}
				} );
				
			} );
		},
		
		mapAreaRender : function( station_list, callback ){
			var _this = this, html_str = '', pop_str = '';
			var parseTpl_func = $.parseTpl( this.tpl.map_area_list ),
				parsePop_func = $.parseTpl( this.tpl.station_pop ),
				default_size = this.options.defaultSize,
				map_box = this.mapArea_box;
			if( station_list.length ){
				$.each( station_list, function( key, value ){
					value.left = (_this.formatFloat(value.site_x / default_size[0], 5)) + '%';
					value.top = (_this.formatFloat(value.site_y / default_size[1], 5)) + '%';
					value['line'] = _this.handleLinecolor( value.sub_color, value.linename );
					html_str += parseTpl_func( value );
					pop_str += parsePop_func( value );
				} );
			}
			map_box.find('.map-area-box').html( html_str );
			map_box.find('.map-pop-box').html( pop_str );
			$.isFunction( callback ) && callback();
		},
		
		chunk : function(array, process, context, callback){
			setTimeout(function(){
				var item = array.shift();
				process.call(context, item);
				if( array.length > 0 ){
					setTimeout(arguments.callee, 20);
				}else{
					$.isFunction( callback ) && callback();
				}
			}, 20);
		},
		
		formatFloat : function(num, digital){
	    	var m = Math.pow(10, digital);
	    	return parseInt( num * m * 100, 10 ) / m; 
	    },
		
		controlArea : function( type ){
			var _this = this;
			this.scaleMapArea(type);
		},
		
		scaleMapArea : function( controllType, callback ){
			var op = this.options,
				scales = op.scales,
				current_scale = this.mapArea_inner.data('type'),
				real_scale = '';

			if( controllType == 'stationClick' ){
	    		real_scale = scales[ current_scale + 1 ] ? current_scale + 1 : 3
	    	}else{
	    		real_scale = (controllType == 'add') ? 3 : 1;
	    	}
			if( current_scale != real_scale ){
				this.setmapAreaInner( real_scale );
				$.isFunction( callback ) && callback();
			}else{
				if( controllType == 'stationClick' ){
					$.isFunction( callback ) && callback();
				}
			}
		},
		
		handleMapSite : function( data ){					//根据线网规划中地铁线路返回的数据整理出来所有的站点数组
			var mapsite = [];
			var _this = this;
			if( $.isArray( data ) && data.length ){
				var station_list = [],
					tmp = {};
				$.each( data, function( k, v ){
					var site_info = v['site_info'];
					v.line = v.title.substring(0, 1);
					
					(_this.linecolor || (_this.linecolor = {}))[v.line] = v.color;
					if( $.isArray( site_info ) && site_info.length ){
						$.each( site_info, function( kk, vv ){
							tmp[vv['id']] = vv;
						} );
					}
				} );
				mapsite = $.map( tmp, function( value ){
					return value;
				} );
			}
			return mapsite;
		},
		
		setmapAreaInner : function( type ){					//设置缩放后的maparea区域的宽高
			var _this = this,
				size = this.options.scales[type];
			this.mapArea_inner.addClass('scale-transition')
			.css( {
				width : size[0] + 'px',
				height : size[1] + 'px'
			} ).data('type', type );
			
			setTimeout( function(){
				_this.mapArea_inner.removeClass('scale-transition');
				_this.mapArea_box.iScroll('refresh'); 
			}, 200 );
		},
		
		stationClickCallback : function( station_target, state ){
			var self = station_target;
			var station_info = {
				title : self.data('title'),
				istoilet : self.data('istoilet'),
				station_color : self.data('color').split(','),
				id : self.data('id'),
				line : self.data('line').toString().split(','),
				size : self.data('size').split(','),
			};
			this.moveStationToCenter( station_info, state );
		},
		
		moveStationToCenter : function( station_info, state ){						//点击站点移动线路图让该站点处于视口中心
			var _this = this;
			var type = this.mapArea_inner.data('type'),
				op = this.options;
			var stationXY = {
				x : parseFloat(station_info.size[0]) * op.scales[type][0] /100,
				y : parseFloat(station_info.size[1]) * op.scales[type][1] /100
			}
			var maparea_w = parseInt( this.mapArea_box.width() ),
				maparea_h = parseInt( this.mapArea_box.height() );
			var real_x = stationXY.x - maparea_w/2,
				real_y = stationXY.y - maparea_h/2,
				adjustXY = this.adjustTranslateXY( real_x, real_y );
			this.mapArea_box.iScroll( 'scrollTo', -adjustXY.real_x, -adjustXY.real_y, 200, false );
			state && this.renderStationPop( station_info.id );
		},
		
		location_nearby : function(){										//显示定位到的附近站点信息弹窗
			var _this = this;
			if( !this.globalNearbyInfo ){
				_this.location( function( point, callback ){
					_this.getNearbyInfo( point, callback );
				}, function( json ){
					_this.moveToCurrent( json );
				} );
			}else{
				_this.moveToCurrent();
			}
		},
		
		moveToCurrent : function( json ){
			var _this = this,
				json = json || this.globalNearbyInfo,
				id = json[0]['id'];
			this.scaleMapArea('stationClick', function(){
				var self = _this.mapArea_box.find('.map-area-box').find('#area' + id);
				_this.stationClickCallback( self, true );
				var networkNearby = _this.el.find('.network-nearby');
				if( networkNearby.length ){
					networkNearby.fadeIn();
				}else{
					var nearby_html = $.parseTpl( _this.tpl.network_nearby, json[0]);
					$( nearby_html ).appendTo( _this.mapArea_box ).fadeIn();
				}
			} );
		},
		
		adjustTranslateXY : function( real_x, real_y ){							//矫正平移的横向纵向值，向左平移值最小为0，最大为整个线路图宽度减去视口，纵向同样
			// var maparea_inner_w = +this.mapArea_inner.width(),
				// maparea_inner_h = +this.mapArea_inner.height();
			var op = this.options,
				type = this.mapArea_inner.data('type'),
				maparea_inner_w = op.scales[type][0],
				maparea_inner_h = op.scales[type][1];
				view_port_w = +this.mapArea_box.width(),
				view_port_h = +this.mapArea_box.height();
			var max_x = maparea_inner_w - view_port_w - 1,
				max_y = maparea_inner_h - view_port_h - 1;
			real_x = this.compareXY( real_x, max_x );
			real_y = this.compareXY( real_y, max_y );
			return { real_x : real_x, real_y : real_y };
		},
		
		compareXY : function( value, maxvalue ){							////矫正平移的值
			if( value < 0 ){
				value = 0;
			}else{
				value = value > maxvalue ? maxvalue : value;
			}
			return value;
		},
		
		renderStationPop : function( id ){							//渲染站点弹窗信息
			var popBox = this.mapArea_inner.find('.map-pop-box'),
				currentdot = popBox.find('#pop' + id);
			currentdot.addClass('open').siblings().removeClass('open');
			popBox.attr('_id', id);
			$('#bae_progress_box').length && this.closeLoading();
		},
		
		getCenterXY : function(){												//获得当前线路规划的中心点
			var centerXY = {x:0,y:0},
				translateXY = this.getTranslateXY(),
				maparea_w = parseInt( this.mapArea_box.width() ),
				maparea_h = parseInt( this.mapArea_box.height() );
			centerXY.x = translateXY.x + maparea_w/2;
			centerXY.y = translateXY.y + maparea_h/2;
			return centerXY;
		},
		
		getTranslateXY : function(){
			var transform_str = this.mapArea_inner.css('-webkit-transform'),	//获得当前线路规划dom的translate的值
				index = transform_str.indexOf(') '),
				transform_str = transform_str.slice(0,index),
				translateXY = {x:0,y:0};
			var	arrXY = transform_str.match(/[0-9]+(?=px)/gi);
			if( $.isArray( arrXY )  && arrXY.length ){
				translateXY.x = parseInt( arrXY[0] );
				translateXY.y = parseInt( arrXY[1] );
			}
			return translateXY;
		},	
		
		getMapareainnerCenter : function(){
			var x = parseInt(this.mapArea_inner.width() ) / 2,
				y = parseInt(this.mapArea_inner.height() ) / 2;
			return { x : x , y : y };
		},	
		
		getmapAreaPercent : function( type ){								 //根据缩放类型计算对应的比例
			var op = this.options,
				scale_w = op.scales[type][0],
				percent = scale_w / op.defaultSize[0];
			return percent;
		},
		
		getNoticeNum : function(){
			var _this = this;
			var url = this.interface_tool( 'subwayServiceList' );
			this.ajax(url, {sign : 'notice', need_count : 1}, function( json ){
				if( json && json[0] && json[0].count ){
					_this.el.find('.pic-right').show().find('.notice-num').html( json[0].count );
				}
			});
		},
		/*线网规划end
		 * 
		 * tool*/
		
		tip : function(ss){
			if(!this.ttt){
				this.ttt = $('<div/>').appendTo('body').css({
					position : 'fixed',
					right : 0,
					top :0,
					width : '100px',
					height: '100px',
					overflow : 'auto'
				});
			}
			this.ttt.append('<div>' + ss + '</div>');
		},
		
		initIscroll : function(){
			var _this = this;
			var size = this.size, id, popstate,
				head_h = this.head.height(),
				nav_h = this.subnav.height();
			var mapArea_box = this.mapArea_box;
			mapArea_box.iScroll( {
				useTransition : true,
				onRefresh : function(){
					var stationPop = mapArea_box.find('.map-pop-box'),
						stationCurret = mapArea_box.find('.station-pop.open');
						id = stationPop.attr('_id');
						popstate = stationCurret.length ? true : false;
					if( id ){
						var self = mapArea_box.find('.map-area-box').find('#area' + id);
						_this.stationClickCallback( self, popstate );
					}
			}
			} ).height(size.height - head_h - nav_h);
			this.wrap.find('.network-wrap').addClass('visible');
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
			if( point ){
				this.point = {};
				this.point.y = point['latitude'];
				this.point.x = point['longitude'];
				this.location_nearby();
			}
		},
		
		location : function( callback, func ){
			var _this = this;
			if( this.point ){
				this.initPoint = true;
				callback && callback( this.point, func );
				return; 
			}
		},
		
		initListScroll : function( dom, input_height, down ){
			var info = {},	_this = this;
			var wrap_height,
				head_height = this.head.height(),
				column_height = this.subnav.height(),
				window_height = this.size.height;
			if( input_height == false ){
				wrap_height = window_height - head_height;
			}else{
				wrap_height = window_height - head_height - column_height;
			}
			input_height && (wrap_height -= input_height);
			if( down ){
				info.load = function( dir, type ){
					var me = this,
                    	up_btn = _this.servicewrap.find('.ui-refresh-up'),
                    	down_btn = _this.servicewrap.find('.ui-refresh-down'),
                    	up_options = up_btn.data('options'),
                    	down_options = down_btn.data('options');
                    if( !down_options && dir !='up' ) return;
                    _this.refreshWidget = this;
                    if( dir == 'up' ){
                    	up_options.dir = dir;
                    	up_options.refreshWidget = me;
                    	up_options.ismore = false;
                    	up_options.param.offset = 0;
                    	_this.serviceColumn(up_options);
                    }else{
	                    down_options.dir = dir;
                    	down_options.refreshWidget = me;
                    	down_options.ismore = true;
	                    down_options.param.offset += _this.options.count;		//加载更多，首先把offset加等到每页显示的条数加现在的offset
	                    _this.serviceColumn( down_options, function( obj ){
                    		_this.refreshScroll( obj );					//_this.refreshWidget加载完列表后刷新refresh组件回调
	                    } );
                    }
				}
			}
			dom.css( 'height', wrap_height + 'px' ).refresh( info );
			this.listScroll = true;
		},
		
		refreshScroll : function( options ){
            options.refreshWidget.afterDataLoading(options.dir);    	//数据加载完成后刷新refresh组件
            this.setRefreshBtn( options );
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
            		_this.showDialog('接口访问错误，请稍候再试');
            	}
	        });
		},
		
		interface_tool : function( name ){								//拼接接口工具函数
			var op = this.options,
				url = op.baseUrl + op.interface_method[name] + op.key;
			return url;
		},
		
		createImgsrc :function( data, options ){						//图片src创建
			var data = data || {},
			op_str = options ? [options.width, 'x', options.height, '/'].join('') : '';
			var src = [data.host, data.dir, op_str, data.filepath, data.filename].join('');
			return src;
		},
	});
	
	window.Subway = Subway;
	
})( Zepto );

$(function(){
	var subwayObj = new Subway( $('body') );
	window.getLocation = function( json ){	//向手机端发起callLocation请求获得经纬度后触发的回调
		subwayObj.relocationPos( json );
	};
});