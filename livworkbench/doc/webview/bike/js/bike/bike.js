;(function($){
	var defaultOptions = {
		baseUrl : 'http://api.139mall.net:8081/data/cmc/',
		key : '?appkey=CshXUoKcUZrBc0OheGbJ7UWgv6b2MSjf&appid=21',
		interface_method : {					//接口配置方法
			bikenearest : 'get_station.php',
			bikeregion : 'get_region.php',
			bikestation : 'get_notice.php',
			stationdetail : 'get_station_detail.php',
		},
		more_title : '点击加载更多',
		count : 20
	};
	
	function Bike( el, options ){
		var _this = this;
		this.options = $.extend( {}, options, defaultOptions );
		this.el = el;
		this.head = el.find('.ui-bae-header');
		this.flag = false;
		this.template = {												//gmu的模版语法，$.parseTpl(tpl,data);来解析
			refresh_wrap : '<div class="data-list-wrap">' + 
								'<div class="ui-refresh-up ui-refresh-btn hide" noevent="<%= noevent%>"></div>' +
								'<ul id="thelist" class="data-list">' +
						        '</ul>' +
								'<div class="ui-refresh-down ui-refresh-btn" noevent="<%= noevent%>"></div>' +
							'</div>' +
							'',
							
			nearlist :  '<li class="nearest-item m2o-flex m2o-flex-end" data-id="<%= id%>" data-link="stationdetail">' + 
							'<div class="pic">' + 
								'<img src="<%= icon%>" alt="无锡太科园西站"/>' + 
							'</div>' +
							'<div class="info m2o-flex-one">' +
								'<div class="name"><a><%= name%></a></div>' +
								'<div class="num">' + 
									'<span class="bikenum">可借车数:<%= currentnum%></span>' + 
									'<span class="carportnum">可停车位:<%= park_num%></span>' + 
								'</div>' + 
								'<div class="address"><%= address%></div>' +
							'</div>' +
							'<div class="detail">' +
								'<div class="distance"><%= distance%></div>' + 
							'</div>' +
						'</li>' + 	
						'',
					
			regionlist :  '<li class="area-item m2o-flex m2o-flex-end" data-id="<%= id%>" data-link="bikenearest">' +
						  		'<div class="info m2o-flex-one">' + 
						  			'<div class="name"><a><%= name%></a></div>' + 
						  			'<div class="address"><span><%= station%></span>离你最近</div>' + 
						  		'</div>' + 
						  		'<div class="detail">' + 
						  			'<div class="num">共<%= station_num%>个站点</div>' + 
						  			'<div class="distance"><%= distance%></div>' +
						  		'</div>' + 
						  	'</li>'	+
						  	'',
						  	
			citelist :  '<li class="area-item m2o-flex m2o-flex-end" data-id="<%= id%>" data-link="bikestation">' + 
							'<div class="info m2o-flex-one">' + 
								'<h3 class="name"><a><%= title%></a></h3>' + 
								'<div class="addition">' +
									'<span class="address"><%= station_name%></span>' +
									'<span class="carportnum"><%= region_name%></span>' + 
								'</div>' + 
							'</div>' + 
							'<div class="detail">' + 
								'<div class="date"><%= create_time%></div>' + 
							'</div>' +
						'</li>' + 
						'',
						
		    body : '<div class="second-body body-page transition">' + 
						  		'<div class="data-content-wrap" <%if(id){%>id="<%= id%>"<%}%> >' + 
						  			'<div class="content-box"></div>' +
						  		'</div>' +
					      '</div>' +
					    '',
					    
			station_nav : ' <div class="subnav">' + 
							'<ul>' +
								'<li class="item-near-li selected" data-link="stationdetail" data-id="<%= id%>" data-sign="station"><a>概括</a></li>'+
								'<li class="item-near-li" data-link="bikestation" data-id="<%= id%>" data-sign="station"><a>公告</a></li>' + 
							'</ul>' + 
							'</div>' + 
							'<div class="detail-wrap-list">' + 
								 '<%if( type ){%><div class="main-wrap website padding">' +
								 '</div><%}%>' + 
							'</div>' + 
							'',
			region_nav :  ' <div class="subnav">' + 
							'<ul>' +
								'<li class="item-near-li selected" data-link="bikenearest" data-id="<%= id%>" data-sign="region"><a>离我最近</a></li>'+
								'<li class="item-near-li" data-link="bikestation" data-id="<%= id%>" data-sign="region"><a>站点公告</a></li>' + 
						   '</ul>' + 
						   '</div>' + 
						   '<div class="detail-wrap-list">' + 
						   '</div>' + 
						   
						   '',
							
			station_detail : '<div class="stationinfo">' +
								  '<div class="general m2o-flex">' + 
							  			'<div class="pic transition" data-pic="<%= info%>"><img src="<%= img%>" /></div>'+
							  			'<div class="m2o-flex-one">' + 
							  				'<p class="web-item bikenum">可借车数: <span><%= currentnum%></span></p>' + 
							  				 '<p class="web-item carportnum">可停车位: <span><%= park_num%></span></p>' +
							  				 '<p class="update">数据更新于：<%= dateline%></p>' + 
							  				 '<p class="address">地址：<%= address%></p>' + 
							  				'<div class="distance"></div>' + 
							  			'</div>'+
							      '</div>'+
							      '<div id="mapDiv" style="text-align: center; height:300px;z-index:-56;background:#fff;margin-bottom:10px;"></div>'+
							      '<div class="gohere" data-lat="<%= latitude%>" data-lng="<%= longitude%>"><a>到这里去</a></div>' + 
								  '<p class="business">运营单位：<%= company_name%></p>' + 
								  '<p class="hotline">服务热线：<%= customer_hotline%></p>' + 
								  '<p class="reminding"><%= company_brief%></p>' + 
						     '</div>' + 
							 '',
					    
			notice_detail : '<div class="content-box-word">' +
							 '<h1><%= title%></h1>' +
							 '<p><span class="publish-time"><%= create_time%></span></p>' +
							 '<p></p>' +
							 	'<article>' + 
							 	'<%= content%>' + 
							 	'</article>' +
						 '</div>' +
						'',
			dialog_tpl : '<div id="dialog" title="">' +
							'<p></p>' +
						'</div>' + 
						'',
			failed_tpl : '<div class="falied-pic"></div>' + 
						'<div class="vertical-btns"><p class="common-btn">重新定位</p></div>' + 
						'',
			style_bug : '.ui-refresh .ui-refresh-up, .ui-refresh .ui-refresh-down{background-color:transparent;}'+
						'',
		};
		this.initDialog();
		this.init();													//实例化构造函数时执行的初始操作
		
		this.el.find('.first-body').on( 'click tap touchstart touchend', '.subnav li', function(){								//栏目切换事件,根据栏目取对应的列表数据
			if( $(this).hasClass('selected') ) return;
			var link = $(this).data('link');
			$(this).addClass('selected').siblings().removeClass('selected');
			_this.showLoading();
			_this.listAjax( {
				method : link,
				param : {
					baidu_latitude : _this.point.lat,
					baidu_longitude : _this.point.lng,
					offset : 0
				},
				bool : false
			}, null, $('.first-body') );
		} );
		
		this.el.on( 'click tap touchstart touchend', '.item-near-li', function(){								
			if( $(this).hasClass('selected') ) return;
			var link = $(this).data('link'),
				id = $(this).data('id'),
				sign = $(this).data('sign'),
				obj = $(this).closest('.body-page');
			$(this).addClass('selected').siblings().removeClass('selected');
			_this.showLoading();
			_this.deep_detail(id , link , sign , obj);
		} );
		
		this.el.on('click tap touchstart touchend', '.ui-refresh-btn', function( event ){		//屏蔽加载更多的click事件
			if( $(this).attr('noevent') ){
				return false;
			}
			event.stopPropagation();
		});
		
		this.el.on( 'click', '#thelist li', $.proxy(_this.list_item_event,_this)				//列表页切换到详情页
		);
		
		this.el.on( 'click', '.gohere', function(event){     //地图到这里去
			var self = $(event.currentTarget),
			obj = self.closest('.body-page'),
				site = {};
			site.lat = self.data('lat');
			site.lng = self.data('lng');
			_this.goPage(site , null ,obj ,null);
		});
		
		this.el.on( 'click', '.goFirstPage', function(event){									//详情页切换到列表页
			var self = $(event.currentTarget),
				obj = self.closest('.body-page'),
				pre = obj.prev('.body-page');
			_this.backPage(pre , obj);
			event.stopPropagation();
		} );
		
		this.el.on( 'click', '.ui-bae-go-back', function( event ){								//退出应用事件，绑定在主页的back按钮上
			if( $(this).hasClass('goFirstPage') ) return;
			event.stopPropagation();
			Widget.close();
		} );
		
		this.style_bug();
	};
	
	$.extend( Bike.prototype, {
		
		style_bug : function(){
			if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				$('<style/>').html( this.template.style_bug ).appendTo( this.el );
			}
		},
		
		list_item_event : function( event ){
			var self = $(event.currentTarget),
				id = self.data('id'),
				method = self.data('link'),
				body = self.closest('.body-page'),
				name = self.find('.name a').text(),
				distance = '';
				if(method == 'stationdetail'){
					this.distance = self.find('.distance').text();  /*详情接口返回没distance值 这里自己在dom获取*/
				}
			this.goPage( id, method , body , name);
		},
		
		init : function(){
			var _this = this;
			this.showLoading();
			this.initDom( $('.first-body') );
			this.initMap();
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
					_this.showDialog('定位接口异常',1500);
					_this.initPos = false;
					_this.list.empty().append( _this.template.failed_tpl );
					_this.routeFailed();
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
					//_this.point = {lat : '31.561094', lng : '120.277359'};
					
					_this.closeLoading();
					_this.listAjax( {
						method : 'bikenearest',
						param : {
							baidu_latitude : _this.point.lat,
							baidu_longitude :_this.point.lng,
							offset : 0
						},
						bool : true
					}, null, $('.first-body') );
				} );
			}
		},
		
		initMap : function(){
			var _this = this;
			this.showLoading('请求定位中...');
			this.initPoint = false;
			this.callLocation();				//调用手机客户端提供的发起定位请求
			//Widget.ready = function(){
			//_this.relocationPos();
			//};
		},
		
		location : function( callback ){
			var _this = this;
			this.initPoint = true;
			_this.getLocation(this.point, function( result ){
				if( !result.addressComponents.city.match('无锡') ){
					_this.showDialog('呃，我们只能查询无锡市区的自行车', 1500);
					//_this.point = {lat : '31.561094', lng : '120.277359'};
				}
				callback && callback();
			});
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
// 
				// _this.initPoint = true;
				// _this.getLocation(point, function( result ){
					// if( !result.addressComponents.city.match('无锡') ){
						// _this.showDialog('呃，我们只能查询无锡市区的自行车', 1500);
						// //_this.point = {lat : '31.561094', lng : '120.277359'};
					// }
					// _this.point = point;
					// callback && callback();
				// });
			// };
		},
		
		//反向地理编码
		getLocation : function( point, callback ){
			//创建地理编码实例
			var myGeo = new BMap.Geocoder();
			//根据坐标得到地址描述
			
			myGeo.getLocation(new BMap.Point(point.lng, point.lat), function( result ){
				result && callback && callback( result );
			});
		},
		
		routeFailed : function(){
			var _this = this;
			
			this.list.find('.common-btn').click(function(){		//重新定位
				_this.showLoading('请求定位中...');
				//_this.relocationPos();
				_this.callLocation(); 	//重新调用手机客户端请求定位
			});
		},
		
		initDom : function( page ){		
			this.column = page.find('.subnav');									
			this.wrap = page.find('.data-list-wrap');
			this.list = this.wrap.find('.data-list');
			this.ajaxRefreshBtn = this.wrap.find('.ui-refresh-btn');
			this.column.show();
			this.instanceNavigator( this.column, 3 );
		},
		
		ajax : function( url, param, callback ){							//ajax工具函数，在pc上测试时可以用下面的注释的jsonp请求，如不能正确返回在移动设备上测试
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
	              success: function(json){
	            	  callback( json );
	              }
	          });
		},
		
		listAjax : function( options, callback, page ){
			var _this = this,
				url = this.interface_tool( options.method );
			this.ajax( url, options.param, function( json ){
				options.len = ( $.isArray( json ) && json.length ) || 0;
				options.callback = callback;
				_this.listAjaxCallback( json, options, page );
			} );
		},
		
		listAjaxCallback :function( json, options, page ){
			var _this = this, len = 0,
				html_str = '',
				parseTpl_func = '';
			if(options.method == 'bikeregion'){
				parseTpl_func = $.parseTpl( this.template.regionlist );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			}else if(options.method == 'bikenearest'){
				parseTpl_func = $.parseTpl( this.template.nearlist );
			}else{
				parseTpl_func = $.parseTpl( this.template.citelist );
			}
			if( !options['ismore'] && !options.bool ){			//如果是栏目切换，会根据传过来的ismore参数进行重置list的refresh组件,ismore的含义是代表是否是加载更多触发的
				this.restoreListScroll( page );
			}
			if( !options.param.offset && (!json || !json.length)){
				this.list.append('<p class="nodata">暂无此类数据</p>');
			}else if( json ){
				$.each( json, function( key, value ){
					if(value['create_time']){
						value['create_time'] = _this.getTime( value['create_time'] );
					}
					if(value['station_icon']){
						value['icon']  = _this.createImgsrc( value['station_icon'] );
					}
					value.station_name = value.station_name || null;
					value.region_name = value.region_name || null;
					len ++;
					html_str += parseTpl_func( value );
				} );
				if( !options.param.offset ){
					this.list.empty();
				}
				this.list[options.dir == 'up' ? 'prepend' : 'append']( html_str );
			}
			options.len = len;
			this.closeLoading();
			if( $.isFunction( options.callback ) ){					//如果渲染完列表数据后，需要执行回调，执行回调函数，比如加载更多后会执行刷新组件回调
				options.callback( options );
			}else{
				this.setRefreshBtn( options );						//如果是正常页面加载，根据options里的参数来设置加载更多按钮的参数以及决定它是否显示
			}
			if( !options['ismore']  ){								//如果是首次加载加载页面，dom渲染完后初始化list的refresh组件
				this.initListScroll( page );
			}
		},
		
		instanceNavigator : function( columnEl, visibleCount ){				 //初始化栏目组件,第一个参数zepto对象，第二个参数栏目默认显示数
			columnEl.navigator( {
				visibleCount : visibleCount   //配置栏目默认显示数
			});
		},
		
		goPage : function( id , method ,body ,name ){									//主页切换到详情页
			var _this = this,
				size = this.getSize(),
				head = body.find('.ui-bae-header'),
				head_height = head.height(),
				head_clone = head.clone();
			head_clone.find('a').addClass('goFirstPage');
			body.css( {
				height : size['height'] + 'px',
				position:'absolute',
				left : '-' + size['width'] + 'px',
				'z-index' : 10
			} );
			var param = {id : null};
			!method && (param.id = 'content-wrapper')
			var second_body = $( $.parseTpl( _this.template.body, param ) );
			second_body.prepend( head_clone );
			second_body.css( {
				width : 100+'%',
				height : size['height'] + 'px',
				position:'absolute',
				left :  size['width'] + 'px'
			} ).insertAfter( body ).css('left',0);
			if( method == 'bikestation' ){
				name = '公告';
			}else if( !method ){
				name = '到这里去';
			}
			second_body.find('.ui-bae-header-left')[0].nextSibling.nodeValue = name;
			if( !method ){
				second_body.find('#content-wrapper').find('.content-box').append('<div id="gomap" style="width:100%;height:100%;z-index:99999"></div>');
				second_body.find('#gomap').css({
					height : ( size['height'] - head_height ) + 'px',
					width : size['width'] + 'px',
				})
			}
			setTimeout( function(){
				_this.showLoading();
				if(method){
					_this.detail( id , method ,second_body );
				}else{
					_this.gohere(id);
				}
				_this.initDom( second_body );
			}, 300 );
		},
		
		initdetailPic : function( box ){
			var _this = this;
			box.on('tap', '.stationinfo .pic', function( event ){
				var self = $(event.currentTarget),
					picinfo = self.data('pic'),
					obj = self.find('img').clone();
				_this.bigPic(obj ,picinfo , box);
				event.stopPropagation();
				return false;
			});
			
			box.on('tap', '.bigpic', function(){
				$(this).remove();
				_this.flag = false;
			});
		},
		
		ajaxDetailInfo : function( info, box, type ){
			var _this = this,
				url = this.interface_tool( info.method );
			var html_str = '',
				parseTpl_func = '';
			this.ajax(url, info.param, function( json ){
				if( type ){
					var data = json[0];
					var size = _this.getSize();
					var options ={};
					options.width = 120;
					options.height = 150;
					data['img'] = _this.createImgsrc( data['indexpic'] , options);
					data['info'] = _this.createImgsrc( data['indexpic'] , {'width' : size['width'] , 'height' : size['height']});
					parseTpl_func = $.parseTpl( _this.template.station_detail , data);
					box.find('.main-wrap').append( parseTpl_func );
					box.find('.distance').text(_this.distance);
					_this.initDetailScroll();
					
					// _this.gpsOffset( data.longitude, data.latitude, function( options ){
						// _this.initBmap( options.lng ,options.lat, data.company_id );/*实例化地图*/
					// } );
					
					_this.initBmap( data.longitude ,data.latitude, data.company_id );/*实例化地图*/
					
					_this.initdetailPic( box );
				}else{
					var data = json[0];
					data['create_time'] = _this.getTime( data['create_time'] );
					parseTpl_func = $.parseTpl( _this.template.notice_detail , data);
					box.append( parseTpl_func );
				}
				_this.closeLoading();
			});
		},
		
		detail : function( id , method ,body ){
			var info = {
				method : method,
				param : {
					offset : 0
				}
			}
			var content_box = body.find('.content-box').empty();
			if( method == 'bikestation'){
				info.param.id = id;
				this.ajaxDetailInfo( info, content_box, false );
			}else if( method == 'stationdetail' ){
				info.param.station_id = id;
				content_box.append($.parseTpl( this.template.station_nav , {id : id, type : true}));
				var column = content_box.find('.subnav')
				this.instanceNavigator( column, 2 );
				this.ajaxDetailInfo( info, content_box, true );
			}else{
				info.param.region_id = id;
				info.bool = true;
				content_box.append($.parseTpl( this.template.region_nav , {id : id}));
				var column = content_box.find('.subnav');
				var refresh_wrap = $.parseTpl( this.template.refresh_wrap, {noevent : true} );
				$( refresh_wrap ).appendTo( content_box.find('.detail-wrap-list') );
				this.instanceNavigator( column, 2 );
				this.listAjax( info, null, body);
			}
		},
		
		deep_detail : function(id , method , sign ,obj){
			var _this = this,
				url = this.interface_tool( method );
			var parent = obj.find('.detail-wrap-list').empty();	
			var info = {
				method : method,
				param : {
					offset : 0
				}
			}
			
			if(sign == 'region'){
				info.param.region_id = id
			}else{
				info.param.station_id = id
			}
			if( method == 'stationdetail' ){
				$('<div class="main-wrap website padding"></div>').appendTo( parent );
				info.param.station_id = id;
				this.ajaxDetailInfo( info, parent, true );
			}else{
				info.bool = false;
				this.listAjax( info, null, obj);
			}
		},
		
		/*实例化地图*/
		initBmap : function(lng, lat, id){
			var map = this.map = new BMap.Map("mapDiv");
			var point = new BMap.Point(lng, lat);
			map.centerAndZoom(point, 16);
			map.addControl(new BMap.NavigationControl());
			var img = (id == 1) ? 'images/bike/bike_current_position.png' : 'images/bike/bike_current_position2.png';
			var myIcon = new BMap.Icon(img, new BMap.Size(30, 40), {
				anchor : new BMap.Size(0, 0)
			});
			var marker = new BMap.Marker( point, {icon : myIcon });
			map.addOverlay(marker);
		},
		
		gohere : function(site){
			var map = new BMap.Map("gomap");
			var point = new BMap.Point(site.lng , site.lat);
			var bpoint = new BMap.Point(this.point.lng , this.point.lat);
			map.centerAndZoom(point, 16);
			map.addControl(new BMap.NavigationControl());
			var driving = new BMap.DrivingRoute(map, {renderOptions:{map: map, autoViewport: true}});
			driving.search( bpoint , point);
			this.closeLoading();
		},
		
		bigPic : function(obj ,picinfo , body){
			if(this.flag == false){
				obj.attr('src' , picinfo).addClass('bigpic').appendTo(body);
				this.closeLoading();
				this.flag = true;
			}
		},
		
		createImgsrc :function( data, options ){						//图片src创建
			var options = $.extend( {}, {width:80,height:50}, options ),
				data = data || {},
			src = [data.host, data.dir, options.width, 'x', options.height, '/', data.filepath, data.filename].join('');
			return src;
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
				second = date.getSeconds();
			return y + '-' + m + '-' + d + ' ' + h + ':' + second;
		},
		
		restoreListScroll : function( page ){								//重置list的refresh组件，因为ajax切换栏目时要把原来的滚动条高度以及滚动条的top位置都要置为初始才能正常浏览
			this.wrap.parent().remove();
			var refresh_wrap = $.parseTpl( this.template.refresh_wrap, {noevent : true} );
			if( page.is('.first-body') ){
				$( refresh_wrap ).insertAfter( this.column );
			}else{
				$( refresh_wrap ).appendTo(page.find('.detail-wrap-list') );
			}
			this.initDom( page );											//重置list的refresh组件时会把list的外围dom移除掉，所以要调用initDom重新设置一次
		},
		
		initListScroll : function( page ){								//初始化列表页scroll
			var _this = this,
				head_height = this.head.height(),
				column_height = this.column.height(),
				window_height = window.innerHeight,
				wrap_height = window_height - head_height - column_height;
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
                    	up_options.bool = false;
                    	up_options.param.offset = 0;
                    	_this.listAjax(up_options, null, page);
                    }else{
	                    down_options.dir = dir;
                    	down_options.refreshWidget = me;
                    	down_options.bool = true;
                    	down_options.ismore = true;
	                    down_options.param.offset += _this.options.count;		//加载更多，首先把offset加等到每页显示的条数加现在的offset
	                    _this.listAjax( down_options, function( obj ){
	                    		_this.refreshScroll( obj );					//_this.refreshWidget加载完列表后刷新refresh组件回调
	                    }, page );
                    }		
                }
            });
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
			var _this = this,
			head_height = this.head.height(),
			column_height = this.column.height(),
			window_height = this.getSize().height,
			wrap_height = window_height - head_height - column_height - 80;
			this.el.find('.main-wrap').css('height', wrap_height).refresh();
		},
		
		interface_tool : function( name ){	//拼接接口工具函数
			var op = this.options,
				url = op.baseUrl + op.interface_method[name] + op.key;
			return url;
			
		},

		gpsOffset : function(lng, lat, callback){
			var point = new Widget.CMap.Point(lat, lng);
			Widget.CMap.GPSOffsetSearch.offsetQuery(point, function( options ){
				options && callback( options );
			});
		},

		showLoading : function(){
			this.loading = $.bae_progressbar({
				message:"<p>加载数据中...</p>",
				modal:false,
				canCancel : false
			});
		},
		
		showDialog : function( str, delay ){
			$('#dialog').html( str ).dialog('open');
			setTimeout(function(){
				$('#dialog').dialog('close');
			}, delay||1000);
		},
		
		initDialog : function(){
			this.el.append( this.template.dialog_tpl );
			$('#dialog').dialog({
				autoOpen : false,
				content : '',
				mask : false,
				width : 'auto'
			});
		},
		
		closeLoading : function(){
			this.loading.close();
			$('#bae_progress_box').remove();
		},
		
		backPage : function(prev , obj){										//回退到主页
			var _this = this,
				size = this.getSize();
			prev.css({left: 0});
			obj.css( {
				left : size['width'] + 'px'
			});
			setTimeout( function(){
				obj.remove();
				prev.removeAttr('style');
				_this.initDom( prev );
			}, 300 );
		}

	} );
	window.Bike = Bike;
	
})($);
	$(function(){
		var bikeObj = new Bike( $('body') );
		window.getLocation = function( json ){	//向手机端发起callLocation请求获得经纬度后触发的回调
			bikeObj.relocationPos( json );
		};
	});