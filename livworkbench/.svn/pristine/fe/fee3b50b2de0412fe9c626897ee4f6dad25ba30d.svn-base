;(function($){
	var defaultOptions = {
			mapIcon : {
				current : 'bus_map_mark_myloc.png',
				interval : 'bus_map_mark_bus.png',
				spot :  'bus_map_mark_spot.png',
				origin : 'mark_traffic_red.png'
			},
			setmapIcon : function( type ){
				return 'images/bus/' + defaultOptions.mapIcon[ type ];
			},
			point : {lng : '120.310796499252', lat : '31.490620682283'}
	};
	function Bus( el, options ){
		var _this = this;
		this.op = $.extend( {}, options, defaultOptions );
		this.el = el;
		this.subnav = $('.first-body .subnav');
		this.head = el.find('.ui-bae-header');
		this.size = $.busOperEvent.getSize();
		this.imgLoading = $('<img src="images/bae_progress_loading.gif" class="loading2" style="width:40px; height:40px;"/>');
		this.MapWrapper = $('.getloc-wrap');
		this.tpl = $.templete;
		this.init();
	}
	$.extend( Bus.prototype, {
		/** 初始化，进行事件绑定 */
		init : function(){
			var _this = this;
			this.wrap = this.el.find('.tab-wrap');

			this.el.on( 'click tap', '.subnav li', function(){	//栏目切换事件,根据栏目取对应的列表数据
				var self = $(this);
				if( self.hasClass('selected') ) return;
				self.addClass('selected').siblings().removeClass('selected');
				if( self.is('.route-terminal') ){
					_this.routeColumn( self );				//切换线路首末站，刷新实时公交
				}else if( self.is('.classify-item') ){
					_this.collectColumn( self );
				}else{
					_this.tabColumn( self.data('type') );
				}
			} );
			
			this.el.on( 'click', '.nearby-inner .bus-item', $.proxy(_this.routeDetail,_this) );	//请求线路详情
			this.el.on( 'focus input propertychange', '.common-search-input', $.proxy(_this.searchLine,_this));//监听线路查询 input
			this.el.on('tap', '.handle .btn', function(){
				var self = $(this);
				if( self.is('.reverse') ){
					_this.reverseRoute( self );
				}else if( self.is('.favor') ){
					_this.favorCollect( self );	
				}
			});
			
			
			/*查询线路、站点、换乘*/
			this.el.on( 'click', '.route-search-result', $.proxy( _this.searchRoute,_this ));	//查询线路结果跳至线路详情
			this.el.on( 'click', '.station-search-result', $.proxy( _this.stationRoute,_this ));//请求该站点下的线路
			this.el.on( 'click', '.bus-inner', $.proxy(_this.cancelLine,_this));
			//清空最近查询
			this.el.on( 'click', '.clear-btn', function( event ){
				var $this = $(this);
				var type = _this.subnav.find('li.selected').data('type');
				_this.Storage.removeItem( type );
				var parent = $this.closest('.bus-inner');
				$this.closest('.clear-inner').empty();
				parent.find('.common-search-input').val('').attr('_id', '');
				event.stopPropagation();
			});
			
			
			/*地图页面*/
			this.el.on( 'click', '.service-search-result', $.proxy( _this.serviceDetail,_this ));//请求该地图上该服务
			this.el.on( 'focus', '.common-service-input', $.proxy(_this.searchService,_this));//监听服务查询 input
			this.el.on( 'blur', '.common-service-input', $.proxy(_this.blurService,_this));//监听服务查询 input
			this.el.on('click', '.map_position .sure-btn', $.proxy(_this.sureMap,_this));
			this.el.on('click', '.bus-map-icon', $.proxy(_this.mapList, _this));
			this.el.on( 'click', '.service-item', $.proxy( _this.serviceStation,_this ));//请求地图列表页附近站点
			
			this.el.on( 'click', '.goPrevPage', function(event){				//详情页切换到列表页
				_this.backPage( $(this) );
				event.stopPropagation();
			} );
			
			this.el.on( 'click', '.ui-bae-go-back', function( event ){								//退出应用事件，绑定在主页的back按钮上
				if( $(this).hasClass('goPrevPage') ) return;
				if( $(this).hasClass('goClose') ){
					_this.MapWrapper.css({visibility : 'hidden'});
					_this.elementToggle( false );
					return; 
				}
				event.stopPropagation();
				Widget.close();
			} );
			
			this.initMap();
			this.initDialog();
			this.initStorage('Croute');
			/** 换乘模块 事件绑定 */
			this.el.on('click', '.transfer-exchange-btn', $.proxy( _this.exchangePoints, _this ) );
			this.el.on('click', '.transfer-fuzzy-item', $.proxy( _this.transferFuzzySelect, _this ));
			//this.el.on('blur', '.transfer-starting-input', $.proxy( _this.hideFuzzyBox ));
			//this.el.on('blur', '.transfer-terminal-input', $.proxy( _this.hideFuzzyBox ));
			
			this.style_bug();
		},
		
		style_bug : function(){
			if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				$('<style/>').html( this.tpl.style_bug ).appendTo( this.el );
			}
		},
		
		tabColumn : function( type ){
			var theMatch = this.wrap.data('type', type);
			switch( type ){
				case 'nearby':
					$.busOperEvent.showLoading();
					this.tplDom( theMatch, this.tpl.nearby_wrap_tpl, 1);
					this.point && this.nearbyRoute( 'nearbyRoute', this.point, this.el.find('.first-body') );
					break;
				case 'route':
				case 'station':
				case 'transfer':
					this.initStorage( type );
					this.tplDom( theMatch, this.tpl[ type +'_search_tpl']);
					this.initSearchDom( type );
					break;
				case 'collect':
					this.tplDom( theMatch, this.tpl[ type +'_search_tpl'], 2);
					this.collect( theMatch, 'route' );
					break;
			}
		},
		
		toggleTab : function( self ){
			var type = self.data('type');
			var obj = this.subnav.find('li[data-type=' + type + ']');
			obj.addClass('selected ui-state-active').siblings().removeClass('selected ui-state-active');
			this.tabColumn( type );
		},
		
		initStorage : function( type ){
			this.Storage = new Hg_localstorage( {key : type} );
		},
		
		tplDom : function( dom, tpl, type ){
			var info = (type == 1) ?  {noevent : true} : {};
			var size = this.size,
				nav_height = this.subnav.height(),
				head_height = this.head.height();
			var inner_height = size.height - nav_height - head_height;
			if( type == 1 && !dom.data('routeinit')){
				this.subnav.show();
				$.busOperEvent.instanceNavigator(this.subnav, 5);
				dom.data('init', true);
			}
			$( $.parseTpl( tpl, info ) ).appendTo( dom.empty() ); 
			if( type == 1 ){
				if( this.point == null || this.point == undefined ){
					dom.find('.nearby-route-list').hide();
					dom.find('.nearby-route-failed').show();
					$.busOperEvent.closeLoading();
					this.routeFailed( dom );
					return; 
				}else{
					dom.find('.nearby-route-list').show();
					dom.find('.nearby-route-failed').hide();
				}
				dom.find('.data-list').before( this.tpl.data_offer );
				this.ajaxRefreshBtn = this.wrap.find('.ui-refresh-btn');
			}else if( type == 2 ){
				dom.find('.secondnav').show();
				$.busOperEvent.instanceNavigator(dom.find('.secondnav'), 3);
				dom.find('.secondnav').find('.classify-item').first().addClass('selected');
				dom.find('.collect-wrap').append( this.tpl.collect_wrap_tpl );
			}else{
				//dom.find('.bus-inner').height( inner_height - 20 );
				var storage = this.Storage.getItem( type );
				if( storage.length ){
					var storage_box = this.tpl.storage_box;
					dom.find('.clear-inner').append( storage_box );
					this.updateStorage(dom, type);
				}
				//dom.find('.bus-inner').append( this.tpl.data_offer );
				this.initbaiduMap();
			}
		},
		
		routeFailed : function( dom ){
			var _this = this;
			/*附近线路*/
			dom.find('.nearby-route-failed .common-btn').click(function(){		//重新定位
				$.busOperEvent.showLoading('请求定位中...');
				_this.callLocation();
				//_this.relocationPos();
			});
			
			dom.find('.nearby-route-failed .square-btn').click(function( event ){	//重新切换目录
				_this.toggleTab( $(this) );
				event.stopPropagation();
			});
		},
		
		updateStorage : function(dom, type){
			var storage = this.Storage.getItem( type );
			var html_str = '';
				parseTpl_func = $.parseTpl( this.tpl.storage_tpl );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			//this.Storage.removeItem('route');
			$.each( storage, function( key, value ){
				value.content = value.content || value.key;
				value.lat = value.lat || false;
				html_str += parseTpl_func( value );
			} );
			dom.find('.recent-query').empty().append( html_str );
			this.restoreStorage();			//实例化本地存储, 搜索最近查询
		},
		
		initSearchDom : function(){
			var _this = this;
			//点击按钮查询
			$('.common-btn').click(function(){
				if( $(this).hasClass('query-transfer-btn') ){
					_this.queryTransfer( $(this) );
				}else{
					_this.searchBtn( $(this) );
				}
			});
			
			//点击操作按钮地图
			$('.handle-btn').click(function(){
				var $this = $(this);
				if( $this.is('.location') ){
					var box = _this.MapWrapper,
						dom = $this.closest('.input-item');
					if( !box.find('.ui-bae-header').length ){
						var head = _this.el.find('.ui-bae-header').clone( true );
						box.prepend( head );
						box.find('.ui-bae-go-back').addClass('goClose');
						box.find('.ui-bae-header-left')[0].nextSibling.nodeValue = '地图选位置';
					}
					var attr = dom.attr('_attr');
					box.css({visibility : 'visible'});
					_this.elementToggle( false );
					box.find('.fuzzy-matching ul').hide();
					box.data('type', attr);
					var oldval = $this.prev().val();
					box.find('.common-service-input').val( oldval );
					if( oldval ){
						box.find('.map_position').removeClass('tips_show');
						box.find('.common-service-input').focus();
						//box.find('.search').click();
						box.find('.tip').html('您可以点击按钮搜索位置');
					}else{
						box.find('.map_position').addClass('tips_show').find('.m2o-flex-one').html(_this.address);
						box.find('.tip').html('您可以拖动图片选择位置');
					}
					_this.restoreMap(false);
				}else if( $this.is('.common-empty-input')  ){
					 _this.emptyTheInput( $this );
				}else if( $this.is('.search') ){
					var val = $this.closest('.input-item').find('.input').val();
					if( !val ){
						$.busOperEvent.showDialog('请先输入要搜索的名称');
						return false;
					}else{
						$.busOperEvent.showLoading();
						_this.localSearch( val );
						_this.MapWrapper.find('.map_position').removeClass('tips_show');
						_this.MapWrapper.find('.fuzzy-matching ul').hide();
					}
				}
			});
			
		}, 
		
		clearBtn : function( event ){
			var self = $(event.currentTarget),
				parent = self.closest('.transition'),
				type = parent.find('.subnav').find('li.selected').data('type');
			this.Storage.removeItem( type );
			self.closest('.query-area').remove();
			parent.find('.common-search-input').val('').attr('_id', '');
		},
		
		sureMap : function( event, type ){
			var val = $('.map_tips.tips_show').find('.addressMap').html(), str;
			if( !type && !val.match("无锡市") ){
				$.busOperEvent.showDialog('呃，我们只能查询无锡市区的公交车');
				str = '无锡广电';
			}else{
				str = val;
			};
			this.MapWrapper.find('.common-search-input').val( str );
			if( type ){
				this.MapWrapper.find('.map_move.tips_show').remove();
			}
			this.MapWrapper.css({visibility : 'hidden'});
			var type = this.MapWrapper.data('type'), point;
			if( str == '无锡广电' ){
				point = {lat : '31.570037' , lng : '120.305456'};
			}else{
				point = this.MapWrapper.data('point');
			}
			if( type == 'station' ){
				this.getnearbyStation( point, str, this.el.find('.station-point') );
			}else{
				this.el.find('.' + type + '-point').find('.input').val( str ).attr({
					_lat : point.lat,
					_lng : point.lng
				});
			}
		},
		
		initDetailtab : function( page ){
			var _this = this;
			page.find('.route-list').find('li').click(function(){
				var $this = $(this);
				$this.addClass('myloc').siblings().removeClass('myloc');
				var current = $this.index();
				var param = {};
				param.options = {
					routeid : $this.attr('_routeid'),
					stationseq : $this.attr('_stationseq'),
					segmentid : $this.attr('_segmentid'),
				};
				param.argum = {
					station : $this.find('.station-name').html()
				};
				var father = page.find('.detail-wrap');
				if( father.data('id') == father.data('index') ){
					page.find('.detail-wrap').data('param', param);
				}else{
					page.find('.detail-wrap').data('siblingparam', param);
				}
				
				clearInterval( _this.IntervalId );
				setTimeout(function(){
					_this.intervalTab( param, page, current );
				}, 350);
				_this.IntervalId = setInterval(function(){
					if( page.find('.route-detail').length ){
						_this.intervalTab( param, page, current );
					}
				}, 60000);
			});
		},
		
		initBusDetail : function( page ){
			var _this = this;
			if( !page.find('.listCollect').data('init') ){
				page.find('.listCollect').click(function( event ){
					var self = $(this),
						type = self.data('type');
					var father = $('.detail-wrap').last();
					var param = {
						'route' : 'Croute',
						'station' : 'Cstation',
						'transfer' : 'Ctransfer'
					};
					var bool = self.hasClass('favored') ? false : true;
					self[(bool ? 'add' : 'remove') + 'Class']('favored');
					if( type == 'route' ){
						var info = father.data('param'),
							direction = father.data('direction');
						var str = bool ? '收藏线路成功' : '删除线路收藏'; 
						$.busOperEvent.showDialog( str );
						var index = father.data('index');
						//存储
						_this.Storage.updateItem({key: info.options.segmentid, content : info, title : direction, stationf : index }, bool, param[type]);
					}else if( type == 'station' ){
						var info = father.data('station'),
							flag = father.data('flag');
						var str = bool ? '收藏站点' : '删除站点'; 
						$.busOperEvent.showLoading( str );
						setTimeout(function(){
							$.busOperEvent.closeLoading();
						}, 500);
						_this.Storage.updateItem({key: info.options.stationid, content : flag }, bool, param[type]);
					}else{
						//收藏换乘
						var info = father.data('transfer'),
							index = self.data('index'),
							title = info.starting.key + ' - ' + info.terminal.key + index;
						var str = bool ? '收藏换乘方案' : '删除换乘方案'; 
						$.busOperEvent.showLoading( str );
						setTimeout(function(){
							$.busOperEvent.closeLoading();
						}, 500);
						_this.Storage.updateItem({
							key: title, content : index, 
							starting : info.starting.key, 
							terminal : info.terminal.key,
							startinglat : info.starting.lat,
							startinglng : info.starting.lng,
							terminallat : info.terminal.lat,
							terminallng : info.terminal.lng
						}, bool, param[type]);
					}
					event.stopPropagation();
				});
				page.find('.listCollect').data('init', true);
			}
			
			page.find('.route-interval').click(function(){
				var $this = $(this);
				$(this).fadeOut();
			});
			
			page.find('.tabbar .icon').click(function(){
				var $this = $(this),
					page = $this.closest('.transition'),
					father = page.find('.detail-wrap');
				if( $this.is('.list') ){
					_this.routeMap( $this );				//打开地图
				}else if( $this.is('.refresh') ){
					var param = (father.data('id') == father.data('index')) ? father.data('param') : father.data('siblingparam');
					setTimeout(function(){
						param && _this.intervalTab( param, father, $this );
					}, 350);
				}else if( $this.is('.reverse') ){
					var current = father.find('.route-terminal.selected');
					current = current.length ? current : father.find('.route-terminal.ui-state-active');
					if( current.siblings().length ){
						current.removeClass('ui-state-active').siblings().addClass('ui-state-active');
						_this.routeColumn( current.siblings() );	
					}
					
				}
			});
			
			page.find('.transfer-detail-title a').click(function(){
				var $this = $(this),
					info = page.find('.detail-wrap').data('transfer');
				var checkResult = {
					'starting' : info.terminal,
					'terminal' : info.starting
				}
				_this.getTransferData( checkResult, function( RouteResult ){
					_this.tplTransfer({
						info : checkResult,
						type : true,
						list : 'list'
					}, RouteResult, page);
				});
			});
			
			page.find('.bus-map-icon').click(function(){
				if( $(this).hasClass('current') ){
					_this.initPoint = false;
					_this.initpos = true;
					setTimeout(function(){
						if( !_this.initPoint ){
							_this.initpos = false;
							_this.currentPoint();
							return false;
						}
					}, 15000);
					if( _this.initpos ){
						_this.location( function(){
							_this.currentPoint();
						});
					}
				}
			});
		},
		
		restoreStorage : function(){
			var _this = this;
			$('.recent-query-item').click(function(){
				var $this = $(this);
				var param = {};
				param.options = {
					segmentid : $this.attr('_id'),
				}
				param.argum = {
					flag : $this.html(),
				}
				var parent = $this.closest('.bus-inner');
				if( parent.hasClass('bus-route') ){
					var page = _this.goNext(param.argum.flag, parent);
					setTimeout(function(){
						_this.ajaxRoute( param, page );
					}, 350);
					_this.wrap.find('.common-search-input').val( param.argum.flag ).attr('_id', param.options.segmentid);
				}else if( parent.hasClass('bus-transfer') ){
					var start = parent.find('.starting-point').find('.common-search-input'),
						end = parent.find('.terminal-point').find('.common-search-input');
					if( !start.val() ){
						start.val( param.argum.flag );
						start.attr({
							'_lat' : $this.attr('_lat'),
							'_lng' : $this.attr('_lng')
						});
					}else{
						end.val( param.argum.flag );
						end.attr({
							'_lat' : $this.attr('_lat'),
							'_lng' : $this.attr('_lng')
						});
					}
				}else{
					var page = _this.goNext(param.argum.flag, parent);
					param.options.stationid = param.options.segmentid;
					var father = $('.detail-wrap').data('station', param ).data('flag', param.argum.flag);
					father.prepend( $.parseTpl( _this.tpl.station_wrap_tpl, {nearStation : false} ) );
					setTimeout(function(){
						_this.StationNearby( param.options, page );
					}, 350);
					_this.wrap.find('.common-search-input').val( param.argum.flag ).attr('_id', param.options.segmentid);
				}
			});
		},
		
		//收藏
		initCollect : function(){
			var _this = this;
			$('.collect-inner').find('.collect-item').click(function( event ){
				var self = $(this),
					_id = self.attr('_id');
				if( $(event.target).is('.del-btn') ){
					return;
				}
				var type = self.find('.del-btn').data('type');
				switch( type ){
					case 'route' : {
						//缓存
						var param = {};
						param.options = {
							routeid : self.attr('_routeid'),
							stationseq : self.attr('_stationseq'),
							segmentid : self.attr('_id'),
						}
						param.argum = {
							flag : self.attr('_flag')
						}
						self.attr('_station') && (param.argum.station = self.attr('_station'));
						var stationf = self.attr('_stationf') ? self.attr('_stationf') : 0;
						var page = _this.goNext( param.argum.flag, self );
						//获取线路列表
						setTimeout(function(){
							_this.ajaxRoute( param, page, stationf );
						}, 350);
						break;
					};
					case 'station' : {
						var flag = self.find('.info').html();
						var param = {};
						param.options = {
							stationid : _id
						};
						var page = _this.goNext( flag, self );
						var father = page.find('.detail-wrap').data('station', param ).data('flag', flag);
						father.prepend( $.parseTpl( _this.tpl.station_wrap_tpl, {nearStation : false} ) );
						
						param.options.stationid = unescape( param.options.stationid );
						
						setTimeout(function(){
							_this.StationNearby( param.options, page );
						}, 350);
						break;
					};
					case 'transfer' : {
						var dom = self.find('.icons');
						var checkResult = {
							starting : {
								key : self.attr('_station'),
								lat : dom.attr('_startinglat'),
								lng : dom.attr('_startinglng')
							},
							terminal : {
								key : self.attr('_stationseq'),
								lat : dom.attr('_terminallat'),
								lng : dom.attr('_terminallng')
							}
						}
						var page = _this.goNext( '换乘方案', self, true );
						setTimeout(function(){
							_this.getTransferData( checkResult, function( RouteResult ){
								_this.tplTransfer({
									info : checkResult,
									type : false,
									list : self.attr('_id')
								}, RouteResult, page);
							});
						}, 350);
						break;
					}
				}
			});
			
			//左滑 swipeLeft
			$('.collect-inner').find('.collect-item').swipeLeft(function(){
				var $this = $(this);
				if( !$this.hasClass('current') ){
					$this.addClass('current');
				}
			});
			
			$('.collect-inner').find('.collect-item').swipeRight(function(){
				var $this = $(this);
				if( $this.hasClass('current') ){
					$this.removeClass('current');
				}
			});
			
			$('.collect-inner').find('.del-btn').click(function(){
				var $this = $(this), key,
					parent = $this.closest('.collect-item'),
					type = $this.data('type');
				var param = {
					'route' : 'Croute',
					'station' : 'Cstation',
					'transfer' : 'Ctransfer'
				}
				if( type != 'transfer' ){
					key = parent.attr('_id');
				}else{
					key = parent.attr('_station') + ' - ' + parent.attr('_stationseq') + parent.attr('_id');
				}
				_this.Storage.updateItem({key : key}, false, param[type]);
				var storage = _this.Storage.getItem( param[type] );
				if( !storage.length ){
					var str = '';
					if( type == 'route' ){
						str = 'proir';
					}
					parent.closest('.tab-wrap').find('.collect-inner').append( $.parseTpl( _this.tpl.collect_no_tpl, {nopos : str} ) );
				}
				parent.remove();
			});
		},
		
		favorCollect : function( self ){		//点击收藏
			var parent = self.closest('.bus-item'), bool;
			var direction = parent.find('.bus-end').find('.blue').html();
			var info = {};
			var bool = parent.find('.reverse').hasClass('on-reverse');
			info.options = {
				routeid : parent.attr('_routeid'),
				stationseq : bool ? parent.attr('_tstationseq') : parent.attr('_stationseq'),
				segmentid : parent.attr('_segmentid'),
			}
			info.argum = {
				flag : parent.attr('_flag'),
				station : parent.find('.nearest').find('span').html(),
				endstation : bool ? parent.find('.bus-end span').attr('_tend') : parent.find('.bus-end span').attr('_end')
			}
			var stationf = bool ? parent.attr('_tstationf') : parent.attr('_stationf')
			var bool = self.hasClass('favored') ? false : true;
			self[(bool ? 'add' : 'remove') + 'Class']('favored');
			//缓存
			this.Storage.updateItem({key: info.options.segmentid, content : info, title : direction, stationf : stationf }, bool, 'Croute');
			event.stopPropagation();
		},
		
		collect : function( dom, type ){		//收藏列表
			var html_str = '';
			var info = {
				'route' : 'Croute',
				'station' : 'Cstation',
				'transfer' : 'Ctransfer'
			}
			//缓存
			//this.Storage.removeItem( info[type] );
			var storage = this.Storage.getItem( info[type] );
			if( storage.length ){
				var parseTpl_func = $.parseTpl( this.tpl.collect_list_tpl );
				if( type == 'route' ){
					$.each( storage, function( key, value ){
						var argum = value.content.argum,
							options = value.content.options;
						value.type = type;
						value.flag = argum.flag;
						value.station = null;
						value.routeid = options.routeid;
						value.stationseq = options.stationseq;
						value.startinglat = null;
						value.title = value.title + '方向'; 
						value.stationf = value.stationf ? value.stationf : null;
						html_str += parseTpl_func( value );
					});
				}else if( type == 'station' ){
					$.each( storage, function( key, value ){
						value.type = type;
						value.title = value.content;
						value.flag = value.station = value.stationseq = value.routeid = null;
						value.startinglat = value.stationf = null;
						html_str += parseTpl_func( value );
					});
				}else{
					$.each( storage, function( key, value ){
						value.type = type;
						value.key = value.content;
						value.title = value.starting + '，' + value.terminal;
						value.station = value.starting;
						value.stationseq = value.terminal;
						value.flag = value.routeid = value.stationf = null;
						html_str += parseTpl_func( value );
					});
				}
				dom.find('.collect-list').append( html_str );
				this.initCollect();
			}else{
				var str = ''
				if( type == 'route' ){
					str = 'proir'
				}
				dom.find('.collect-inner').append( $.parseTpl( this.tpl.collect_no_tpl, {nopos : str} ) );
			}
		},
		
		collectColumn : function( self ){
			var type = self.data('type');
			var box = $('.tab-wrap');
			box.find('.collect-wrap').empty().append( this.tpl.collect_wrap_tpl );
			this.collect( box, type );
		},
		
		hadStorage : function( title ){
			var storage = this.Storage.getItem( 'Ctransfer' );
			if( storage.length ){
				$.each(storage, function(key, value){
					if( value.key == title + value.content ){
						var obj = $('.transfer-list-wrap').find('.bae-collapse').eq(value.content);
						obj.length && obj.find('.listCollect').addClass('favored');
					}
				});
			}
		},
		
		/*搜索线路*/
		searchRoute : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			var param = {};
			param.options = {
				routeid : self.attr('_routeid'),
				segmentid : self.attr('_segmentid'),
			}
			param.argum = {
				flag : self.attr('_flag'),
			}
			var page = this.goNext(self.attr('_flag'), self);
			this.wrap.find('.common-search-input').val( param.argum.flag )
				.attr('_id', param.options.segmentid)
				.attr('_routeid', param.options.routeid);
			
			var storage = this.Storage.getItem( 'route' );
			if( !storage.length ){
				var storage_box = this.tpl.storage_box;
				this.wrap.find('.clear-inner').append( storage_box );
			}
			this.Storage.updateItem({key: param.options.segmentid, content : param.argum.flag }, true, 'route');
			this.updateStorage($('.bus-route'), 'route');
			setTimeout(function(){
				_this.ajaxRoute( param, page );
			}, 350);
			
		},
		
		routeDetail : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			if( $(event.target).is('.favor') || $(event.target).is('.reverse') ){
				return; 
			}
			var bool = self.find('.reverse').hasClass('on-reverse');
			var param = {};
			param.options = {
				routeid : self.attr('_routeid'),
				stationseq : bool ? self.attr('_tstationseq') : self.attr('_stationseq'),
				segmentid : self.attr('_segmentid'),
			}
			param.argum = {
				flag : self.attr('_flag'),
				station : self.hasClass('station-route') ? self.find('.bus-end').attr('_stationname') : self.find('.nearest').find('span').html(),
				endstation : bool ? self.find('.bus-end span:last-child').attr('_tend') : self.find('.bus-end span:last-child').attr('_end')
			}
			var stationf = bool ? self.attr('_tstationf') : self.attr('_stationf')

			var page = this.goNext( param.argum.flag, self );
			
			//获取线路列表
			setTimeout(function(){
				_this.ajaxRoute( param, page, stationf );
			}, 350);
		},
		
		//切换线路首末站，刷新实时公交
		routeColumn : function( self ){
			self.addClass('selected').siblings().removeClass('selected');
			var page = self.closest('.transition'),
				father = page.find('.detail-wrap');
			var i = self.index(),
				param = (i == father.data('index')) ? father.data('param') : father.data('siblingparam'),
				json = father.data('json'),
				len = json.length;
			$.busOperEvent.showLoading();
			
			if( page.find('.tabbar .list').hasClass('map') ){
				this.routeInfo(page, i);
			}else{
				var info = {
					page : page,
					index : i,
					eq : father.data('index'),
				}
				param && param.argum.station && (info.station = param.argum.station);
				param && (info.stationseq = param.options.stationseq);
				father.data('id', i);
				this.restoreRouteMap( json, info );
			}
		},
		
		routeMap : function( self ){
			var type = self.hasClass('map') ? true : false,	//true表示列表页,false表示地图页
				str = type ? '列表' : '地图';
			self[(type ? 'remove' : 'add') + 'Class']('map');
			self.html( str );
			var page = self.closest('.transition'),
				father = page.find('.detail-wrap');
			var json = father.data('json'),
				len = json.length,
				index = father.data('index'),
				param = (father.data('id') == father.data('index')) ? father.data('param') : father.data('siblingparam'),
				height = father.find('.ui-refresh-wrapper').height();
			father.find('.route-map').height( height );
			father.find('.route-detail')[type ? 'hide' : 'show']();
			father.find('.route-map')[type ? 'show' : 'hide']();
			if( type ){
				$.busOperEvent.showLoading();
				var info = {
					page : page,
					index : father.data('id'),
					eq : father.data('index')
				}
				param && param.argum.station && (info.station = param.argum.station);
				param && (info.stationseq = param.options.stationseq);
				if( self.data('init') ){
					this.restoreRouteMap( json, info);
				}else{
					this.initRouteMap( json, info);
					self.data('init', true);
				}
				if( !param  ){
					clearInterval( this.IntervalId );
				}
				father.find('.route-detail').addClass('detail-rotate');
			}else{
				this.routeInfo( page, father.data('id') );
			}
		},
		
		ajaxRoute : function(param, page, type){
			this.restoreDetail( param.options.segmentid, page, 'route' );
			var _this = this;
			var nonce = Math.random()*10000;
			var signature = $.busInterEvent.shaUrl( {id : param.options.segmentid, nonce : nonce}, 'routeDetail' );
			
			var ceshilocal = '&id=' + param.options.segmentid + '&nonce=' + nonce + '&signature=' + signature;
			var url = $.busInterEvent.interface_tool('routeDetail') + ceshilocal;

			$.busInterEvent.ajax( url, function(json){
//			$.busInterEvent.ajax('./json/route_detail.php', function(json){
				var father = page.find('.detail-wrap'),
					len = json.length;
				father.prepend( $.parseTpl( _this.tpl.route_detail_subnav_tpl, {} ) );
				$.each(json, function(k,v){
					var listLen = v['list'].length,
						endstation = v['list'][listLen-1]['stationname'];
					father.find('.route-subnav').find('ul').append( $.parseTpl( _this.tpl.route_detail_terminal_tpl,{ terminal : endstation } ) );
					if( param.argum.endstation == endstation ){
						type = k;
					}
				});
				father.data('json', json).data('param', param);
				
				var index = (type > 0) && (len>1) ? 1 : 0;
				father.data('index', index);
				_this.routeInfo( page, index );
				
				$.busOperEvent.instanceNavigator( father.find('.route-subnav'), len, index );
				_this.initBusDetail( page );
			});
		},
		
		routeInfo : function( page, index ){
			var _this = this;
			page.find('.route-list-wrap').parent().remove();
			var father = page.find('.detail-wrap'),
				json = father.data('json'),
				len = json.length,
				param = (index == father.data('index')) ? father.data('param') : father.data('siblingparam');
			index = index ? (len - 1) : 0;
			father.data('id', index);
			father.find('.route-detail').append( $.parseTpl( this.tpl.route_detail_info_tpl, {info:json[index]['starttime']} ) );
			var func = $.parseTpl( this.tpl.route_detail_list_tpl );
			var html = '';
			$.each( json[index]['list'],function(kk,vv){
				html += func( vv );
			} );
			father.find('.route-list').append( html );
			$.busOperEvent.closeLoading();
			
			var detail = json[index]['list'],
				detaillen = detail.length;
			father.data('direction', detail[detaillen-1]['stationname']);
			
			if( param && param.argum.station ){
				var currentDom = page.find('.route-list-wrap').find('.item').filter(function(){
					return ($(this).find('.station-name').html() == param.argum.station && $(this).attr('_stationseq') == param.options.stationseq)
				});
				currentDom.addClass('myloc');
				var current = currentDom.index();
			}
			
			if( param ){
				if( !param.options.stationseq ){
					param.options.routeid = detail[detaillen-1]['routeid'];
					param.options.stationseq = detail[detaillen-1]['stationseq'];
				}
				clearInterval(this.IntervalId);
				
				setTimeout(function(){
					_this.intervalTab( param, father, current );
				}, 350);

				this.IntervalId = setInterval(function(){
					if( father.find('.route-detail').length ){
						_this.intervalTab( param, father, current );
					}
				}, 60000);
			}else{
				father.find('.route-interval').fadeOut();
				clearInterval( this.IntervalId );
			}
			
			var input_height = page.find('.tabbar').height();
			this.initListScroll( father.find('.route-list-wrap'), 'routeDetail', input_height );
			this.initDetailtab( page );
		},

		intervalTab : function( param, page, current ){
			var _this = this;
			$.busOperEvent.showLoading();
			var type = page.find('.list').hasClass('map') ? true : false;
			param.options.nonce = Math.random()*10000;
			var signature = $.busInterEvent.shaUrl( param.options, 'RTBus' );
			
			var ceshilocal = '&routeid=' + param.options.routeid + '&stationseq=' + param.options.stationseq + '&segmentid=' + param.options.segmentid + '&nonce=' + param.options.nonce + '&signature=' + signature;
			var url = $.busInterEvent.interface_tool('RTBus') + ceshilocal;
			if( type ){
				page.find('.route-list-wrap').find('.item').each(function(){
					var $this = $(this);
					if($this.hasClass('bus')){
						$this.removeClass('bus');
						$this.find('.getTime').length && $this.find('.getTime').remove();
					}
					$this.hasClass('g4') && $this.removeClass('g4');
				});
			}

			//去取实时公交信息
			$.busInterEvent.ajax( url, function(json){
//			$.busInterEvent.ajax('./json/interval.php', function(json){
				var data = json.result;
				var func = $.parseTpl( _this.tpl.interval_list_tpl ), html = '';
				var len = 0, Aintervar = [];
				if( data && $.isArray( data ) && data.length){
					$.each(data, function(i, j){
						var name = j.stationname,
						time = parseInt(j.actdatetime.split(':')[1]);
						var minutes = $.busOperEvent.getCurrentMunites();
						var currentDom = page.find('.route-list-wrap').find('.item').filter(function(){
							return ((minutes - time < 5) && len < 4 && $(this).find('.station-name').html() == name)
						});
						var index = currentDom.index();
						if( current && (index > -1) && index < current ){
							_this.intervalAfter( currentDom, j );
						}else if( !current && (index > -1) ){
							_this.intervalAfter( currentDom, j );
						}
						if( (index > -1) && len < 2 ){
							if( param.argum.station ){
								len ++;
								html += func( j );
							}
							Aintervar.push(name);
						}
					});
				}else if( type && data.constructor == String && data.length){
					$.busOperEvent.showDialog( data );
				}else if( type && !data ){
					var message = json.message;
					html += func( {message : message, busselfid : ''} );
				}
				page.data('interval', Aintervar);
				if( type ){
					var interval = page.find('.route-interval');
					interval.fadeOut();
					setTimeout(function(){
						interval.empty().append( html );
						interval.show();
					}, 500);
				}else{
					_this.interval && _this.removeInterval(Aintervar.length);
					_this.mapinterval();
				}
				$.busOperEvent.closeLoading();
			} );
		},
		
		intervalAfter : function( dom, j ){
			dom.addClass('bus');
			if( j.flag_title ){
				dom.addClass('g4');
			}
			var obj = dom.find('.detail');
			if( !obj.find('.getTime').length ){
				$('<p class="getTime">到站时间 ' + j.actdatetime + '</p>').appendTo( obj );
			}
		},
		
		getIntervalBus : function( dom ){
			var $this = dom;
			var param = {
				routeid : $this.attr('_routeid'),
				stationseq : $this.find('.reverse').hasClass('on-reverse') ? $this.attr('_tstationseq') : $this.attr('_stationseq'),
				segmentid : $this.attr('_segmentid') 
			}
			param.nonce = Math.random()*10000;
			var signature = $.busInterEvent.shaUrl( param, 'RTBus' );
			param.signature = signature;
			
			var ceshilocal = '&routeid=' + param.routeid + '&stationseq=' + param.stationseq + '&segmentid=' + param.segmentid + '&nonce=' + param.nonce + '&signature=' + param.signature;
			var url = $.busInterEvent.interface_tool('RTBus') + ceshilocal;

			$.busInterEvent.ajax( url, function(json){
//			$.busInterEvent.ajax('./json/interval.php', function(json){
				var distance = $this.find('.distance');
				if( json.result && $.isArray(json.result) && json.result.length ){
					var data = json.result;
					if(distance.find('span').length){
						distance.find('span').html( data[0].stationnum );
					}else{
						distance.html('最近一班车距离<span class="red">' + data[0].stationnum + '</span>站');
					}
					
				}else{
					distance.html( json.message );
				}
			} );
		},
		
		/*站点下线路*/
		stationRoute : function(event){
			var self = $(event.currentTarget),
				_this = this;
			var param = {};
			param.options = {
				stationid : self.attr('_stationid')
			};
			var flag = self.attr('_flag');
			var page = this.goNext( flag, self );
			
			this.wrap.find('.common-search-input').val( flag ).attr('_id', param.options.stationid );
			var storage = this.Storage.getItem( 'station' );
			if( !storage.length ){
				var storage_box = this.tpl.storage_box;
				this.wrap.find('.clear-inner').append( storage_box );
			}
			this.Storage.updateItem({key: param.options.stationid, content : flag}, true, 'station');
			this.updateStorage($('.bus-station'), 'station');
			
			var father = $('.detail-wrap').data('station', param ).data('flag', flag);
			father.prepend( $.parseTpl( this.tpl.station_wrap_tpl, {nearStation : false} ) );
			setTimeout(function(){
				_this.StationNearby( param.options, page );
			}, 350);
		},
		
		StationNearby : function( param, page ){
			var _this = this;
			var options = {};
			options.method = 'stationRoute',
			options.param = param,
			options.tpl = 'station_route_tpl';
			options.json_handle = function( func, json ){
				var html = '';
				var size = 0;
				$.each( json, function(k,v){
					var data = {};
					data.routeid = v['routeid'];
					data.segmentid = v['segmentid'];
					data.name = v['segmentname'].replace(/[^0-9]/g,'');
					data.flag = v['segmentname'].replace(/[0-9路]/g,'');
					
					data.stationname = v['station'][0]['stationname'];
					data.stationseq = v['station'][0]['stationseq'];
					data.end = v['station'][0]['end_station'];
					data.start = v['station'][0]['start_station'];
					data.starttime = v['station'][0]['starttime'];
					data.stationf = v['station'][0]['station_flag'];
					
					data.tstationseq = v['station'][1] ? v['station'][1]['stationseq'] : '';
					data.tend = v['station'][1] ? v['station'][1]['end_station'] : '';
					data.tstart = v['station'][1] ? v['station'][1]['start_station'] : '';
					data.tstarttime = v['station'][1] ? v['station'][1]['starttime'] : '';
					data.tstationf = v['station'][1] ? v['station'][1]['station_flag'] : '';
					
					size++;
					html += func( data );
				});
				options.len = size;
				return html;
			};
		 	options.dom = page.find('.nearby-route-list'); 
			this.headhadCollect( escape(param.stationid), page, 'station' );
			this.initBusDetail( page );
			this.listAjax(options, page);	//站点线路
		},
		
		searchBtn : function( self ){
			var parent = self.closest('.bus-inner'), type, _this = this,
				input = parent.find('.common-search-input');
			var param = {};
			param.options = {
				segmentid : input.attr('_id'),
			}
			param.argum = {
				flag : input.val(),
			}
			var bool = parent.hasClass('bus-station') ? true : false;
			if( param.argum.flag && param.options.segmentid ){
				var page = this.goNext(param.argum.flag, parent);
				if( bool ){
					param.options.stationid = param.options.segmentid;
					var father = $('.detail-wrap').data('station', param ).data('flag', param.argum.flag);
					father.prepend( $.parseTpl( this.tpl.station_wrap_tpl, {nearStation : false} ) );
					
					setTimeout(function(){
						_this.StationNearby( param.options, page);
					}, 350);
					type = 'station';
				}else{
					setTimeout(function(){
						_this.ajaxRoute( param, page );
					}, 350);
					type = 'route';
				}
				this.Storage.updateItem({key: param.options.segmentid, content : param.argum.flag}, true, type);
				this.updateStorage(parent, type);
			}else{
				$.busOperEvent.showDialog('请输入正确的' + (bool ? '站台' : '线路') + '名称');
			}
			
		},
		
		headhadCollect : function( id, page, type  ){
			var info = {
				'route' : 'Croute',
				'station' : 'Cstation'
			}
			var secondhead = page.find('.ui-bae-header-list');
			if( !secondhead.find('.listCollect').length ){
				$('<span class="listCollect" data-type="' + type + '"></span>').appendTo( secondhead );
			}else{
				secondhead.find('.listCollect').data('type', type);
			}
			var storage = this.Storage.getItem( info[type] );
			var storeId = $.map(storage, function(value){
				return value.key;
			});
			if( $.inArray(id, storeId) > -1 ){
				secondhead.find('.listCollect').addClass('favored');
			}
		},
		
		
		restoreDetail : function( id, page, type ){
			var page = page || $('.second-body');
			this.headhadCollect( id, page, type );
			page.find('.detail-wrap').empty();
		},
		
		//附近站点
		getnearbyStation : function( point, str, dom ){
			var _this = this;
			var page = this.goNext('附近站台', dom);
			var father = page.find('.detail-wrap');
			father.prepend( $.parseTpl( _this.tpl.station_wrap_tpl, {nearStation : true} ) );
			father.find('.common-station').html( str );
			setTimeout(function(){
				_this.nearbyRoute('nearbyStation', point, page);
			}, 350);
		},
		
		serviceStation : function( event ){
			var self = $(event.currentTarget),
				str = self.find('.segmentroute').html();
			var type = this.MapWrapper.data('type');
			this.el.find('.goClose').click();
			if( type == 'station' ){
				var point = {lat : self.attr('_lat') , lng : self.attr('_lng')};
				this.getnearbyStation( point, str, this.el.find('.station-point') );
			}else{
				this.el.find('.' + type + '-point').find('.input').val( str );
			}
		},
		
		initNearbyStation : function( box ){
			var _this = this;
			box.find('.station-item').click(function(e){
				var $this = $(this),
					flag = $this.find('.stationname').html();
				var param = {};
				param.options = {
					stationid : $this.attr('_stationid')
				};
				var page = _this.goNext(flag, $this);
				
				_this.wrap.find('.common-search-input').val( flag ).attr('_id', param.options.stationid );
				var storage = _this.Storage.getItem( 'station' );
				if( !storage.length ){
					var storage_box = _this.tpl.storage_box;
					_this.wrap.find('.clear-inner').append( storage_box );
				}
				_this.Storage.updateItem({key: param.options.stationid, content : flag}, true, 'station');
				_this.updateStorage($('.bus-station'), 'station');
				
				var father = page.find('.detail-wrap').data('station', param ).data('flag', flag);
				father.prepend( $.parseTpl( _this.tpl.station_wrap_tpl, {nearStation : false} ) );
				
				setTimeout(function(){
					_this.StationNearby( param.options, page );
				}, 350);
			});
		},
		
		/*附近线路*//*附近站台*/
		nearbyRoute : function( method, point, page ){
			var _this = this;
			var Methodtpl = {
				'nearbyRoute' : 'nearby_route_tpl',
				'nearbyStation' : 'nearby_station_tpl',
			}
			var options = {};
				options.method = method;
				options.param = {
						rad : '1000.0000',
						lng : point ? point.lng : this.point.lng,
						lat : point ? point.lat : this.point.lat,
						type : '1'
					};
				options.tpl = Methodtpl[method];
				options.json_handle = function( func, json ){
						var html = '';
						var size = 0;
						if( method == 'nearbyRoute' ){
							$.each( json, function(k,v){
								var data = {};
								data.routeid = v['routeid'];
								data.segmentid = v['segmentid'];
								data.name = v['segmentname2'].replace(/[^0-9]/g,'');
								data.flag = v['segmentname2'].replace(/[0-9路]/g,'');
								
								var station = v.station;
								data.stationname = v['station'][0]['stationname'];
								data.stationseq = v['station'][0]['stationseq'];
								data.end = v['station'][0]['end_station'];
								data.stationf = v['station'][0]['station_flag'];
								
								data.tstationname = v['station'][1] ? v['station'][1]['stationname'] : '';
								data.tstationseq = v['station'][1] ? v['station'][1]['stationseq'] : '';
								data.tend = v['station'][1] ? v['station'][1]['end_station'] : '';
								data.tstationf = v['station'][1] ? v['station'][1]['station_flag'] : '';
								
								data.collect = v['collect'];
								
								size++;
								html += func( data );
							});
						}else{
							$.each( json, function(k,v){
							var data = {};
								data.stationname = v['stationname'];
								data.stationid = v['stationid'];
								data.segmentroute = $.busReverseEvent.getsegment( v['segment'] );
								size++;
								html += func( data );
							});
						}
						options.len = size;
						return html;
					};
				 options.dom = page.find('.nearby-route-list');
				_this.listAjax(options, page);	//附近路线列表
		},
		
		listAjax : function( options, page ){
			var _this = this, signature, ceshilocal;
			var localurl = (options.method == 'nearbyStation') ? './json/station.php': './json/data.php';
			
			options.param.nonce =  Math.random()*10000;

			if( options.method == 'stationRoute' ){
				options.param.stationid = escape(options.param.stationid);
				signature = $.busInterEvent.shaUrl( {stationid : options.param.stationid, nonce : options.param.nonce}, options.method );
				ceshilocal = '&stationid=' + options.param.stationid + '&nonce=' + options.param.nonce + '&signature=' + signature;
			}else{
				signature = $.busInterEvent.shaUrl( options.param, options.method );
				ceshilocal = '&rad=' + options.param.rad + '&lng=' + options.param.lng + '&lat=' + options.param.lat + '&type=' + options.param.type + '&nonce=' + options.param.nonce + '&signature=' + signature;
			}
			var url = $.busInterEvent.interface_tool( options.method ) + ceshilocal;
			

			$.busInterEvent.ajax( url, function( json ){
//			$.busInterEvent.ajax( localurl, function( json ){
				if( (options.method == 'nearbyRoute') && $.isArray( json ) && !json.length){
					$.busOperEvent.showDialog('呃，我们只能查询无锡市区的公交车');
					_this.nearbyRoute('nearbyRoute', _this.op.point, page);
					return false;
				}else{
					_this.listAjaxCallback( json, options, page );
				}
				
			} );
		},
		
		listAjaxCallback :function( json, options, page ){
			var _this = this,
				html_str = '',
				parseTpl_func = $.parseTpl( this.tpl[options.tpl] );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			if( options.dir ){
				this.restoreListScroll( page );
			}
			
			//请求的数据是否需要自己处理,如果需要自己处理，拼好后返回字符串
			if( ($.isArray(json) && !json.length) || !json ){
				if( options.method == 'nearbyRoute' ){
					var str = '暂无附近线路';
				}else if( options.method == 'nearbyStation' ){
					var str = '暂无附近站点';
				}else{
					var str = '暂无线路经过该站点';
				}
				page.find('.nearby-route-list').append('<p class="no-transfer">' + str + '</p>');
				$.busOperEvent.closeLoading();
				return; 
			}else{
				if(options.method == 'nearbyRoute' ){			//收藏的数据优先显示
					var storage = this.Storage.getItem( 'Croute' );
					var collectJson = [], unCollectJson = [], collectRank = [], uncollectRank = [];
					if( storage.length ){
						$.each(json, function(key, value){
							value.collect = 0;
							$.each(storage, function(k, v){
								if( value.segmentid == v.key ){
									value.collect = 1;
									collectJson.push( value );
								}
							});
							if( !value.collect ){
								unCollectJson.push( value );
							}
						});
					}
					collectJson.length && (collectRank = $.busInterEvent.rankStation( collectJson ));
					unCollectJson.length && (uncollectRank = $.busInterEvent.rankStation( unCollectJson ));
					json = (uncollectRank.length || collectRank.length) ? collectRank.concat( uncollectRank ) : json;
				}
				if( options.json_handle && $.isFunction( options.json_handle ) ){
					html_str = options.json_handle( parseTpl_func, json );
				}
			}

			page.find('.data-list')[options.dir == 'up' ? 'prepend' : 'append']( html_str );
			
			if( options.method != 'nearbyStation' ){
				/*实时公交*/
				$('.data-list').find('.bus-item').each(function(){
					_this.getIntervalBus( $(this) );
				});
			}
			setTimeout(function(){
				$.busOperEvent.closeLoading();
			}, 500);
			
			if( options.method == 'nearbyRoute'  ){
				options.dom = page.find('.nearby-route-list');
				this.ajaxRefreshBtn.data('options', options);
			}
			this.initListScroll( options.dom, options.method );
			
			 (options.method == 'nearbyStation') && _this.initNearbyStation( page );
			 if( options.method == 'nearbyRoute' ){
			 	this.initbaiduMap();
			 }
		},
		
		restoreListScroll : function( page ){
			this.wrap.find('.nearby-inner').remove();
			var refresh_wrap = $.parseTpl( this.tpl.nearby_wrap_tpl, {noevent : true} );
			$( refresh_wrap ).appendTo( this.wrap );
			this.wrap.find('.data-list').before( this.tpl.data_offer );
			this.ajaxRefreshBtn = this.wrap.find('.ui-refresh-btn');
		},
		
		initListScroll : function( dom, method, input_height ){								//初始化列表页scroll
			var info = {};
			var _this = this, wrap_height,
				head_height = this.head.height(),
				column_height = this.subnav.height(),
				window_height = window.innerHeight;
			if( method == 'stationRoute' ){
				wrap_height = window_height - head_height;
			}else if( method == 'serviceDetail' ){
				wrap_height = window_height - head_height - input_height;
			}else if( method == 'routeDetail' ){
				wrap_height = window_height - head_height - column_height - input_height;
			}else{
				wrap_height = window_height - head_height - column_height;
			}
			if( method == 'nearbyRoute' ){
				info = {
					load: function (dir, type) {							
		                var me = this,
		                	up_btn = _this.wrap.find('.ui-refresh-up'),
		                	up_options = up_btn.data('options');
		                _this.refreshWidget = this;
		                if( dir == 'up' ){
		                	up_options.dir = dir;
		                	up_options.refreshWidget = me;
		                	up_options.ismore = false;
		                	up_options.bool = false;
		                	_this.listAjax(up_options, dom.closest('.transition'));
		                }
		              }
				}
			}
			dom.css( 'height', wrap_height + 'px' ).refresh( info );
			if( method == 'serviceDetail'){
				dom.css({'height': '100%' });
			}
			if( method == 'nearbyRoute' ){
				this.address && this.el.find('.current-address').show().find('span').html( this.address );
				setTimeout( function(){
					dom[0].style.webkitTransition = '-webkit-transform 200ms cubic-bezier(0.33, 0.66, 0.66, 1)';
					dom[0].style.webkitTransform = 'translate(0, -60px)';
					_this.el.find('.current-address').fadeOut();
				}, 1000 );
			}
			if( method == 'routeDetail' ){
				setTimeout( function(){
					var current = dom.find('.myloc');
					if( current.length ){
						var top = current.position().top,
							height = current.height(),
							len = top - 5 * height - 41;
						var maxlen = dom.find('.item').length * height + 40 - dom.parent().height();
						if( len > maxlen ){
							len = maxlen;
						}else if( len < 0 ){
							len = 0;
						}
						var tlen = len ? ('-' + len + 'px') : 0;
						dom[0].style.webkitTransition = '-webkit-transform 200ms cubic-bezier(0.33, 0.66, 0.66, 1)';
						dom[0].style.webkitTransform = 'translate(0, ' + tlen + ')';
					}
				}, 1000 );
			}
		},
		
		reverseRoute : function( self ){
			var parent = self.closest('.bus-item'),
				bool = self.hasClass('on-reverse'),
				type = parent.hasClass('station-route');
			var str = bool ? '' : 't';
			
			self[(bool ? 'remove' : 'add') + 'Class']('on-reverse');
			if( type ){
				var start = parent.find('.bus-end').find('.start'),
					end = parent.find('.bus-end').find('.end'),
					starttime = parent.find('.nearest');
				start.html( start.attr('_'+ str + 'start') );
				end.html( end.attr('_'+ str + 'end') );
				starttime.html( '首末班车 ' + starttime.attr('_' + str + 'starttime') );
			}else{
				var nearest = parent.find('.nearest').find('span'),
					end = parent.find('.bus-end').find('span');
				nearest.html( nearest.attr('_'+ str + 'stationname') );
				end.html( end.attr('_'+ str + 'end') );
			}
			this.getIntervalBus( parent );
		},
		
		/** 百度地图定位 */
		initbaiduMap : function(){
			if( this.onceMap ){
				return;
			}
			var size = this.size,
				box = this.MapWrapper,
				head_height = this.head.height();
			box.css({width : size.width, height : size.height});
			box.find('.map_position').css({left : size.width/2 - 16, top : size.height/2 - 55});
			box.find('#mapDiv').show().css({width : size.width, height : size.height - head_height});
			box.find('.map-wrapper').css({top : head_height + 75})
			this.map = new BMap.Map("mapDiv");            // 创建Map实例
			this.map.addControl(new BMap.ZoomControl());
			this.restoreMap(false);
			this.onceMap = true;
		},
		
		initRouteMap : function(json, info){
			var routemap = this.routemap = new BMap.Map("route-map");
			routemap.addControl(new BMap.ZoomControl());
			this.restoreRouteMap(json, info);
		},
		
		restoreRouteMap : function( json, info ){
			var page = info.page, currentPoint, intervarPoint = [],
				index = info.index;
			var value = json[index]['list'], 
				_this = this;
				
			this.current = false, this.interval = false;
			var equal = (index == info.eq), Adata = [];
			$.each(value, function(k, v){
				var latitude = v.bgps.split(',');
				v.lat = latitude[0];
				v.lng = latitude[1];
				if( (v.lat > 0) && (v.lng > 0) ){
					Adata.push( v );
				}
			});
			var len = Adata.length;
			$.each(Adata, function(k, v){
				if( info.station && (v.stationname == info.station) && (v.stationseq == info.stationseq) ){
					_this.current = true;
					_this.k = k;
					currentPoint = v;
					v.current = 1;
				}else if( !_this.current && k == (len - 1) ){
					_this.k = k;
					currentPoint = v;
					v.current = 1;
				}else{
					v.current = 0;
				}
					
			});
			this.routemap.clearOverlays();
			$.busOperEvent.closeLoading();
			this.serviceToPoint( {
				value : value,
				current : currentPoint,
			}, false );
		},
		
		currentPoint : function(){
			var map = this.routemap;
			var point = this.point || this.op.point;
			var point = new BMap.Point(point.lng, point.lat);
			map.centerAndZoom(point, 16);
			map.clearOverlays();
			var marker = new BMap.Marker(point);
			map.addOverlay(marker);
		},
		
		restoreMap : function( type ){
			var _this = this,
				map = this.map;
			var point = type ? this.point : this.op.point;
			var point = new BMap.Point(point.lng, point.lat);
			map.centerAndZoom(point, 16);
			map.clearOverlays();

			var box = this.MapWrapper.find('.map_tips.tips_show');
			map.addEventListener('dragend', function(e){
				if( box.is('.map_position') ){
					_this.getLocation(map.getCenter(), null, box);
				}
			});	//地图加载绑定事件
			
			this.getLocation( point, function( result ){	//反向地理编码
				_this.address = result.address;
				if( !type && !_this.address.match('无锡市') ){
					_this.address = '江苏省无锡市滨湖区立信大道'
					_this.point = _this.op.point;
				}
			}, box );
		},
		
		//反向地理编码
		getLocation : function( point, callback, box ){
			var _this = this;
			this.imgLoading.appendTo( box.find('.sure-btn') );
			//创建地理编码实例
			var myGeo = new BMap.Geocoder();
			//根据坐标得到地址描述
			
			myGeo.getLocation(new BMap.Point(point.lng, point.lat), function( result ){
				if( result ){
					if( callback ){
						callback( result );
					}
					box.find('p').html( result.address );
					_this.MapWrapper.data('point', point);
					box.find('.sure-btn').find('.loading2').detach();
				}
			});
		},
		
		localSearch : function( val, type, dom ){
			var _this = this, searchResult = [];
			(type !== 'down') && this.map.clearOverlays();
			var options = {
				onSearchComplete : function( results ){
					if( local.getStatus() != BMAP_STATUS_SUCCESS ){
						if( type ){
							dom.closest('.common-input-box').find('.fuzzy-matching ul').hide();
						}else{
							$.busOperEvent.showDialog('搜索不成功', 1500);
						}
					}else{
						for( var i=0; i<results.getCurrentNumPois(); i++ ){
							var param = results.getPoi(i).point;
							param.address = results.getPoi(i).address;
							param.title = results.getPoi(i).title;
							searchResult.push( param );
						}
						if( searchResult.length && type !== 'down' ){
							_this.serviceToPoint( searchResult, true );
							_this.MapWrapper.find('.map-wrapper').data('list', searchResult);
						}
						(type === 'list') && $.busOperEvent.showLoading();
						type && _this.drawResult( searchResult, type, dom );
					}
				}
			}
			var local = new BMap.LocalSearch("无锡市", options);
			local.search( val );
			$.busOperEvent.closeLoading();
		},
		
		serviceToPoint : function(searchResult, type){	//true表示选择位置，false表示线路图
			var _this = this;
			var map = type ? this.map : this.routemap;
			var result = type ? searchResult : searchResult.value,
				current = type ? searchResult[0] : searchResult.current;
			
			var center = new BMap.Point(current.lng, current.lat);
			map.centerAndZoom(center, 14);
			
			for(var i=0; i<result.length; i++){
				var img;
				if( type ){
					img = this.op.setmapIcon('origin'); 
				}else{
					img = this.op.setmapIcon('spot');
				}
				var markerIcon = this.addMarkerIcon( result[i], type, img );
				this.initMarker( markerIcon, result[i], type );
			}
			
			if( !type ){
				this.mapinterval();
				var currentImg = this.op.setmapIcon('current');
				this.current && (this.currentIcon = this.addMarkerIcon( current, type, currentImg ));
			}
			
			if( !type && this.current ){
				this.showmySquare( current, type );
				$('.route-map').find('.map_move').find('.map_route').html( current.stationname );
			}
		},
		
		addMarkerIcon : function( data, type, img ){
			var map = type ? this.map : this.routemap;
			var myIcon = new BMap.Icon(img, new BMap.Size(40, 45), {
				anchor : new BMap.Size(10, 30)
			});
			var point = new BMap.Point(data.lng, data.lat);
			var marker = new BMap.Marker( point, {icon : myIcon });
			map.addOverlay(marker);
			return marker;
		},
		
		searchService : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			var pos = this.MapWrapper.find('.map_position');
			pos.removeClass('tips_show');
			if( self.val() ){
				this.localSearch( self.val(), 'down', self );
				self.attr('_val', self.val());
			}
			this.service = setInterval(function(){
				var val = self.val(),
				oldval = self.attr('_val');
				if( val && val != oldval){
					_this.localSearch( val, 'down', self );			//true表示需要数据渲染
					self.attr('_val', val);
				}
			}, 1000);
		},
		
		blurService : function(){
			clearInterval( this.service );
			var pos = this.MapWrapper.find('.map_position');
			pos.addClass('tips_show');
		},
		
		drawResult : function( searchResult, type, self ){
			if( type === 'down' ){
				var parent = self.closest('.common-input-box'), html = '';
				var func = $.parseTpl( this.tpl['service_search_result_tpl'] );
				$.each(searchResult, function(k,v){
					html += func(v);
				});
				if( !parent.find('.fuzzy-matching').length ){
					$( $.parseTpl( _this.tpl.transfer_fuzzy_ul_tpl, {} ) ).insertAfter( before );
				}
				$.isArray( searchResult ) && searchResult.length && parent.find('.fuzzy-matching ul').empty().prepend( html ).show();
			}else{
				var input_height = this.MapWrapper.find('.map-wrapper').find('.mask').height();
				this.serviceList( searchResult, input_height );
			}
			
		},
		
		serviceDetail : function( event ){
			var self = $(event.currentTarget), str,
				_this = this,
				parent = self.closest('.mask'),
				val = self.attr('title');
			if( !this.MapWrapper.hasClass('map-list') ){
				this.MapWrapper.find('.bus-map-icon.map').show();
				parent.addClass('transpa-mask');
				str = false;
			}else{
				str = 'list';
			}
			$.busOperEvent.showLoading();
			parent.find('.common-service-input').val( val );
			parent.find('.fuzzy-matching ul').hide();
			setTimeout(function(){
				_this.localSearch( val, str, self );
				_this.MapWrapper.find('.map_position').removeClass('tips_show');
			}, 300);
			event.stopPropagation();
		},
		
		mapList : function( event ){
			var self = $(event.currentTarget),
				_this = this,
				box = this.MapWrapper;
			if( self.hasClass('getloc') ){
				_this.initPoint = false;
				_this.initpos = true;
				setTimeout(function(){
					if( !_this.initPoint ){
						_this.initpos = false;
						_this.restoreMap(false);
						return false;
					}
				}, 15000);
				if( _this.initpos ){
					this.location( function(){
						_this.restoreMap(true);
					});
				}
				box.find('.mask').removeClass('transpa-mask');
				box.find('.map_position').addClass('tips_show').find('.m2o-flex-one').html(this.address);
				box.find('.tip').html('您可以拖动图片选择位置');
			}else if(self.hasClass('map')){
				var type = box.hasClass('map-list') ? false : true;	//false表示列表页, true表单页
				this.elementToggle( type );
				type ? this.showMapList( self ) : this.showMapPage( self );
			}
		},
		
		showMapList : function( self ){
			var map_box = this.MapWrapper.find('.map-wrapper');
			var input_head = this.MapWrapper.find('.mask'),
				input_height = input_head.height();
			$.busOperEvent.showLoading();
			this.serviceList( map_box.data('list'), input_height );
		},
		
		serviceList : function( searchResult, input_height ){
			var map_box = this.MapWrapper.find('.map-wrapper'), html = '';
			var func = $.parseTpl( this.tpl.nearby_service_tpl );
			map_box.find('.map-address-list').empty();
			map_box.find('.map-address-list').prepend( $.parseTpl( this.tpl.service_box, {} ) );
			$.each(searchResult, function(k,v){
				html += func(v);
			});
			map_box.find('.data-list').empty().prepend( html ).show();
			$.busOperEvent.closeLoading();
			this.initListScroll( map_box.find('.map-list'), 'serviceDetail', input_height );	
		},
		
		showMapPage : function( self ){
			this.MapWrapper.find('.bus-map-icon.map').show();
		},
		
		elementToggle : function( bool ){	/*true表示列表页，false表示点图页*/
			this.MapWrapper[(bool ? 'add' : 'remove') + 'Class']('map-list');
			this.MapWrapper.find('.map-wrapper')[bool ? 'show' : 'hide']();
			this.MapWrapper.find('.bus-map-icon.map')[bool ? 'show' : 'hide']();
			this.MapWrapper.find('.mask')[(bool ? 'add' : 'remove') + 'Class']('transpa-mask');
		},
		
		removeInterval : function(len){
			for( var i=0; i<len; i++ ){
				this.routemap.removeOverlay( this.intervalIcon[i] );
				this.intervalIcon[i] = null;
			}
		},
		
		initMarker : function( marker, param, type ){
			var _this = this, current,
				map = type ? this.map : this.routemap;
			marker.addEventListener('click', function(e){
				var center = new BMap.Point(param.lng, param.lat);
				map.panTo(center);
				
				if( !type ){
					_this.current && map.removeOverlay( _this.currentIcon );
				}
				
				_this.showmySquare( param, type );
				
				var current = type ? _this.MapWrapper.find('.map_move').addClass('tips_show') : $('.route-map').find('.map_move'); 
				if( type ){
					current.find('.map_info').find('p').html( param.title );
					current.find('.map_info').find('span').html( param.address );
					_this.MapWrapper.data('point', param);
				}else{
					current.find('.map_route').html( param.stationname );
					var currentImg = _this.op.setmapIcon('current');
					_this.currentIcon = _this.addMarkerIcon( center, type, currentImg );
					_this.current = true;
					_this.currentIcon.setPosition( center );
				}
				
				if( !type ){
					var info = {
						argum : {
							station : param.stationname,
						},
						options : {
							routeid : param.routeid,
							segmentid : param.segmentid,
							stationseq : param.stationseq
						}
					}
					var page = $('.transition'),
						father = page.find('.detail-wrap');
					var i = page.find('.route-subnav').find('.route-terminal.ui-state-active').index();

					if( i == father.data('index') ){
						father.data('param', info);
					}else{
						father.data('siblingparam', info);
					}
					
					clearInterval( _this.IntervalId );
					
					setTimeout(function(){
						_this.intervalTab( info, page, current );
					}, 350);
					_this.IntervalId = setInterval(function(){
						if( page.find('.route-detail').length ){
							_this.intervalTab( info, page, current );
						}
					}, 60000);
				}
			});
		},
		
		mapinterval : function(){
			var _this = this, intervalPoint = [];
			var father = $('.transition').last().find('.detail-wrap'),
				json = father.data('json'),
				interval = father.data('interval'),
				index = father.data('id'),
				len = interval.length;
			var intervalImg = this.op.setmapIcon( 'interval' );
			var value = json[index]['list'],
				param = (index == father.data('index')) ? father.data('param') : father.data('siblingparam');
			
			
			this.interval = false;
			if( len && param){
				$.each(value, function(k, v){
					var latitude = v.bgps.split(',');
					v.lat = latitude[0];
					v.lng = latitude[1];
					if( len ){
						if( $.inArray(v.stationname, interval) > -1 ){
							_this.interval = true;
							intervalPoint.push(v);
						}
					}
				});
				$.each(intervalPoint, function(k, v){
					var intervalIcon = (_this.intervalIcon || (_this.intervalIcon = {}))[k] = _this.addMarkerIcon( v, false, intervalImg );
					_this.initMarker( intervalIcon, v, false, len);
				});
			}
		},
		
		showmySquare : function( param, type ){
			var _this = this;
			var map = type ? this.map : this.routemap;
			map.removeOverlay( this.mySquare );
			var mySquare = this.mySquare = new SquareOverlay( param, 200, 100, type );
			map.addOverlay(mySquare);
			if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				tmpfun = map.onclick;
				map.onclick = null;
				mySquare.addEventListener('touchstart', function(){
					_this.map.onclick = tmpfun;
					_this.sureMap( null, true );
				});
			}else{
				mySquare.addEventListener('click', function(){
					_this.sureMap( null, true );
				});
			}
			// mySquare.addEventListener('click', function(){
				// _this.sureMap( null, true );
			// });
		},
		
		callLocation : function(){
			callLocation();
			this.locationTimeout();
		},
		
		locationTimeout : function(){
			var _this = this;
			setTimeout(function(){
				if( !_this.initPoint ){
					$.busOperEvent.closeLoading();
					$.busOperEvent.showDialog('定位接口异常');
					_this.initPos = false;
					_this.subnav.find('li').eq(0).click();
					return false;
				}
			}, 15000);
		},
		
		
		relocationPos : function( point ){
			var _this = this;
			this.initPos = true;
			if( this.initPos ){
				if( point ){
					this.point = {};
					this.point.lat = point['latitude'];
					this.point.lng = point['longitude'];
				}
				_this.location( function(){
					//_this.point = _this.op.point;
					$.busOperEvent.closeLoading();
					_this.subnav.find('li').eq(0).click();
				} );
			}
		},
		
		
		initMap : function(){
			var _this = this;
			$.busOperEvent.showLoading('请求定位中...');
			this.initPoint = false;
			// _this.relocationPos();
			this.callLocation();				//调用手机客户端提供的发起定位请求
			//Widget.ready = function(){
			//_this.relocationPos();
			//};
		},
		
		location : function( callback ){
			var _this = this;
			this.point = this.point || {lat : '31.561094', lng : '120.277359'};
			_this.initPoint = true;
			callback && callback();
			// if( !this.wirelessmap ){
				// this.wirelessmap = new Widget.CMap.Map('wirelessDiv', 'baidu');
			// }
			// var map = this.wirelessmap;
	        // Widget.CMap.Location.requestMyLocation(map);
			// Widget.CMap.Location.onMyLocationComplete = function (point) {
				// if( !point ){
					// _this.location( callback );
					// return false;
				// }
				// _this.initPoint = true;
				// _this.point = point;
				// callback && callback();
			// };
		},
		
		searchLine : function( event ){
			var self = $(event.currentTarget),
				_this = this,
				type = this.subnav.find('li.selected').data('type'),
				val = self.val();
			var parent = self.closest('.common-input-box'),
				before = self.closest('.input-item');
			var bool = (type == 'route') ? true : false;
			var param = {
				k : encodeURI(val)
			};
			var method = bool ? 'quertRoute' : 'queryStation';
			var localurl = bool ? './json/query_line.php' : './json/query_station2.php';
			
			param.nonce = Math.random()*10000;
			var signature = $.busInterEvent.shaUrl( param, method );
			param.signature = signature;
			
			var ceshilocal = '&k=' + param.k + '&nonce=' + param.nonce + '&signature=' + param.signature;
			var url = $.busInterEvent.interface_tool( method ) +  ceshilocal ;

			if( type != 'route' ){
				before.addClass('edit').siblings().removeClass('edit');
				before.siblings().next().find('.fuzzy-matching ul').hide();
			}
			if( !before.next().is('.fuzzy-matching') ){
				$( $.parseTpl( _this.tpl.transfer_fuzzy_ul_tpl, {} ) ).insertAfter( before );
			}

			$.busInterEvent.ajax( url, function(json){
//			$.busInterEvent.ajax( localurl, function(json){
				var html = '';
				var func = $.parseTpl( _this.tpl[type + '_search_result_tpl'] );
				
				if( $.isArray( json ) && json.length ){
						$.each(json, function(k,v){
						if( type != 'route' ){
							v.match = '';
						}
						html += func(v);
					});
					before.next().find('.fuzzy-matching ul').empty().prepend( html ).show();
				}else{
					if( bool ){
						self.attr('_id', '');
					}else{
						if( val ){
							var str = '暂无"' + val + '"站台，试试在地图中搜索吧';
							var noResult = $.parseTpl( _this.tpl.transfer_search_result_tpl, {stationname : str, match : 'no-match'} );
							before.next().find('.fuzzy-matching ul').empty().prepend( noResult ).show();
						}else{
							before.next().find('.fuzzy-matching ul').hide()
						}
						
					}
				}
			} );
			event.stopPropagation();
		},
		
		cancelLine : function( event ){
			var my = $(event.target);
			if( my.is('.common-search-input') ){
				return;
			}else{
				this.wrap.find('.fuzzy-matching ul').empty().hide();
			}
			event.stopPropagation();
		},
		
		hideFuzzymatch : function( event ){
			$(event.currentTarget).closest('.search-area').find('ul').hide();
		},
		
		emptyTheInput : function( dom ){
			var box = dom.closest('.input-item');
			box.find('.input').val('').attr('_id', '');
		},
		
		/** 换乘-点击查询按钮 */
		queryTransfer : function( self ){
			var _this = this;
			var parent = self.closest('.bus-inner');
			var checkResult = this.checkInputVal( parent );
			if( checkResult ){
				if( checkResult.starting.key == checkResult.terminal.key ){
					$.busOperEvent.showDialog('起始点相同，请重新输入',1500);
					return false;
				}
				
				var storage = this.Storage.getItem( 'transfer' );
				if( !storage.length ){
					var storage_box = this.tpl.storage_box;
					this.wrap.find('.clear-inner').append( storage_box );
				}
				
				this.Storage.updateItem({key : checkResult.starting.key, lat : checkResult.starting.lat, lng : checkResult.starting.lng}, true, 'transfer');
				this.Storage.updateItem({key : checkResult.terminal.key, lat : checkResult.terminal.lat, lng : checkResult.terminal.lng}, true, 'transfer');
				
				this.updateStorage(parent, 'transfer');
				var page = this.goNext('换乘方案', parent, true);
				setTimeout(function(){
					_this.getTransferData(checkResult, function(RouteResult){
						_this.tplTransfer({
							info : checkResult,
							type : true,
							list : 'list'
						}, RouteResult, page);
					});
				}, 500);
			}
		},
		
		
		/** 换乘-请求数据 */
		getTransferData : function( points, callback){		//this.wirelessmap
			return;
			var map = this.wirelessmap;
			var _this = this;
			var ptStart = new Widget.CMap.Point(points.starting.lat, points.starting.lng),
				ptEnd = new Widget.CMap.Point(points.terminal.lat, points.terminal.lng);
			Widget.CMap.RouteSearch.routeQuery(map, Widget.CMap.RouteSearch.TransitMode, ptStart, ptEnd);
			Widget.CMap.RouteSearch.onRouteSearchComplete = function(RouteResult){
				callback && callback( RouteResult );
			}
		},
		
		getRouteHtml : function(RouteResult, json, i){
			var DataResult = RouteResult.getRoute(i);
			var getSegments = DataResult.getSegments();
			var tplFun = $.parseTpl( this.tpl.transfer_item_tpl );
			var detail = '', 
				info = json.info;
			$.each(getSegments, function(key, value){
				detail += value.getActionDescription();
			});
			var tplData = {
					index : i + 1,
					k : i,
					detail : detail,
					type : json.type,
					startinglat : info.starting.lat,
					startinglng : info.starting.lng,
					terminallat : info.terminal.lat,
					terminallng : info.terminal.lng,
			};
			return tplFun( tplData );
		},
		
		tplTransfer : function(json, RouteResult, page){
			page.find('.detail-wrap').empty();
			var info = json.info;
			var title = info.starting.key + ' - ' + info.terminal.key;
			var _this = this, html_str = '';
			page.find('.detail-wrap').data('transfer', json.info).prepend( $.parseTpl( _this.tpl.transfer_detail_tpl,{ title : title, type : json.type } ) );
			if( !RouteResult ){
				page.find('.transfer-list-inner').append( $.parseTpl( _this.tpl.collect_no_tpl, {nopos : 'search'} ) );
				return false;
			}
			var len = RouteResult.getRouteResultSize();
			if( json.type ){
				for(var i=0; i<len; i++){
					html_str += $.busReverseEvent.getRouteHtml(RouteResult, json, i);
				}
			}else{
				html_str += $.busReverseEvent.getRouteHtml(RouteResult, json, json.list);
			}
			page.find('.transfer-list-inner').append( html_str );
			this.hadStorage(title);
			var maxheight = this.getMaxHeight( page );
			this.initCollapse( page, maxheight );
			setTimeout(function(){
				$(".bae-collapse").eq(0).trigger('tap');
				_this.initListScroll( page.find('.transfer-list-wrap'), 'Transfer');
				_this.initBusDetail( page );
			},500);
		},
		
		/** 换乘-检查input非空，返回 F/input值 */
		checkInputVal : function(wrap){
			var start = wrap.find('.starting-point').find('input'),
				end = wrap.find('.terminal-point').find('input');
			if( !start.val() || !end.val() ){
				$.busOperEvent.showDialog('请输入正确的站台名称',1500);
				return false;
			}
			var info = {
				starting : {
					key : start.val(),
					lat : start.attr('_lat'),
					lng : start.attr('_lng')
				},
				terminal : {
					key : end.val(),
					lat : end.attr('_lat'),
					lng : end.attr('_lng')
				}
			}
			return info;
		},
		/** 换乘-选择模糊查询的结果 */
		transferFuzzySelect : function( event ){
			var self = $(event.currentTarget), name,
				box = $('.input-item.edit');
			if( self.hasClass('no-match') ){
				name = box.find('input').val();
				box.find('.location').click();
			}else{
				name = self.text();
				var lat = self.attr('_lat'),
					lng = self.attr('_lng');
				box.find('input').attr({
					'_lat' : lat,
					'_lng' : lng
				});
			}
			box.find('input').val( name );
			this.hideFuzzyBox();
		},
		hideFuzzyBox : function(){
			setTimeout(function(){
				$('.fuzzy-matching').find('ul').hide();
			},200);
		},
		
		/** 换乘-交换起终点 */
		exchangePoints : function( event ){
			var self = $(event.currentTarget),
				parent = self.closest('.search-area');
			var obj = this.checkInputVal( parent ),
				cache = '';
				start = parent.find('.starting-point').find('.common-search-input'),
				end = parent.find('.terminal-point').find('.common-search-input');
			cache = obj.starting;
			start.val( obj.terminal.key ).attr({
				'_lat' : obj.terminal.lat,
				'_lng' : obj.terminal.lng
			});
			end.val( cache.key).attr({
				'_lat' : cache.lat,
				'_lng' : cache.lng
			});
		},
		
		getMaxHeight : function( page ){
			var maxHeight = 0;
			page.find('.bae-collapse').each(function(){
				var height = $(this).find('.bae-body')[0].scrollHeight;
				maxHeight = (height > maxHeight) ? height : maxHeight;
			});
			return maxHeight;
		},
		
		initCollapse : function( page, maxheight ){
			page.find('.bae-collapse').on('tap', function( event ){
				if( $(event.target).is('.listCollect') ){
					return; 
				}
				var $this = $(this),
					bool = $this.hasClass('current') ? true : false;
				$this[(bool ? 'remove' : 'add') + 'Class']('current');
				var height = bool ? 0 : maxheight,
					handle_height = $this.find('.handle').height();
				$this.find('.bae-body').height( height );
				height && $this.find('.detail').height( height - handle_height - 20 );
				if( !bool ){
					$this.siblings().removeClass('current');
					$this.siblings().find('.bae-body').height( 0 );
				}
			});
		},
		
		backPage : function( self ){										//回退到主页
			var _this = this,
				size = this.size;
			clearInterval( this.IntervalId );
			
			var current_body = self.closest('.transition[_attr]'),
				attr = current_body.attr('_attr');

			this.el.find('.transition[_attr]').each(function(){
				var oldattr = $(this).attr('_attr'),
					interval = attr - oldattr - 1;
				interval = (interval < 0) ? Math.abs(interval) : '-' + interval;
				$(this).css({left : interval * size['width'] + 'px'});
				setTimeout( function(){
					current_body.remove();
					if( oldattr == 1 ){$(this).removeAttr('style');}
				}, 300 );
			});
		},
		
		initDialog : function(){
			this.wrap.append( this.tpl.dialog_tpl );
			$('#dialog').dialog({
				autoOpen : false,
				content : '',
				mask : false,
				width : 'auto'
			});
		},
		
		goNext : function( str, dom, type ){
			var _this = this,
				size = this.size,
				current_body = dom.closest('.transition[_attr]'),
				head = current_body.find('.ui-bae-header'),
				head_clone = head.clone();
			head_clone.find('a').addClass('goPrevPage');
			var attr = current_body.attr('_attr');
			this.el.find('.transition[_attr]').each(function(){
				var $this = $(this),
					oldattr = $this.attr('_attr'),
					iterval = parseInt(attr - oldattr) + 1;
				$this.css({
					height : size['height'] + 'px',
					position:'absolute',
					left : '-' + iterval * size['width'] + 'px',
					'z-index' : 10
				});
			});
			
			var param = ['first', 'second', 'third', 'fourth']; 
			var next_body = $( $.parseTpl( this.tpl.next_body_tpl, {page : param[ attr ], id : 'content-wrapper', index : parseInt(attr) + 1} ) );
			next_body.prepend( head_clone );
			next_body.css( {
				height : size['height'] + 'px',
				position:'absolute',
				width :  size['width'] + 'px'
			} ).insertAfter( current_body ).css('left',0);
			str && (next_body.find('.ui-bae-header-left')[0].nextSibling.nodeValue = str);
			setTimeout( function(){
				!type && $.busOperEvent.showLoading();
			}, 300 );
			return next_body;
		},
	});
	
	window.Bus = Bus;
	
})($);
	$(function(){
		var busObj = new Bus( $('body') );
		window.getLocation = function( json ){	//向手机端发起callLocation请求获得经纬度后触发的回调
			busObj.relocationPos( json );
		};
	});
