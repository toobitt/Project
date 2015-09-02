
$.busInterEvent = (function(){
	var options = {
		method : {
			nearbyRoute : 'get_segment',		//附近线路
			RTBus : 'station_info_common',		//实时公交
			routeDetail : 'segment_station2',	//线路详情(包含站点列表)
			queryStation : 'query_station2',	//查站台
			stationRoute : 'station_line2',		//站台下的线路
			quertRoute : 'query_line',			//查线路
			nearbyStation : 'get_station'
		},
		baseUrl : 'http://api.139mall.net:8081/gongjiao/bus/api.php?key=&version=0.1',
		secret : '640c7088ef7811e2a4e4005056991a1f',
	}
	var Inter = {
		interface_tool : function( name ){		//拼接接口工具函数
			return (options.baseUrl + '&a=' + options.method[ name ]);
		},
		
		getReverse : function( data ){
			var reverse, treverse;
			if( $.isArray(data) && data.length ){
				if( data.length > 1 ){
					$.each(data, function(key, value){
						if( value.station_flag == 0 ){
							reverse = value;
						}else{
							treverse = value;
						}	
					});
				}else{
					reverse = data[0];
					treverse = null;
				}
			}
			return [reverse, treverse];
		},
		
		shaUrl : function( param, method ){		//sha加密
			var info = {
				a : options.method[method],
				secret : options.secret,
				key : '',
				version : '0.1',
			}
			var oSha = $.extend({}, info, param);
			var aKey = [], aSha = [], sSha;
			$.each(oSha, function(key, value){
				aKey.push( key );
			});
			var sortKey = aKey.sort();
			$.each(sortKey, function(k, v){
				aSha.push(v, oSha[v]);
			});
			sSha = aSha.join('');
			var DSha = sSha.toUpperCase();
			var signature = hex_sha1( DSha );
			return signature;
		},
		
		rankStation : function( data ){
			var Aremove = [], info = {};
			$.each(data, function(key, value){
				var removing = value['station'][0]['removing'];
				if( $.inArray(removing, Aremove) == -1){
					Aremove.push( removing );
				}
			});
			var Rremove = Aremove.sort();
			var info = $.map(Rremove, function(v){
				var param = [];
				$.each(data, function(key, value){
					if(v == value['station'][0]['removing']){
						param.push(value);
					}
				});
				return param;
			});
			return info;
		},
		
		ajax : function( url, callback ){		//ajax工具函数
			var _this = this;
			$.ajax({
				type: "get",
	            url: url,
	            dataType: "json",
	            jsonp: "callback",
	            timeout : 60000,
	            success: function(json){
	            	callback( json );
	            },
            	error : function(){
            		$.busOperEvent.closeLoading();
            		$.busOperEvent.showDialog('接口访问错误，请稍候再试');
            	}
	        });
		},
	};
	return Inter;
})();

$.busReverseEvent = (function(){
	var Collect = {
		getsegment : function( data ){
			var dataArray = [];
			if( $.isArray(data) && data.length ){
				$.each(data, function(key, value){
					dataArray.push(value.segmentname2);
				});
			}
			var strData = dataArray.join(',');
			return strData;
		},
		
		getRouteHtml : function(RouteResult, json, i){
			var DataResult = RouteResult.getRoute(i);
			var getSegments = DataResult.getSegments();
			var tplFun = $.parseTpl( $.templete.transfer_item_tpl );
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
	};
	return Collect;
})();

$.busOperEvent = (function(){
	var Oper = {
		getSize : function(){
			var size = {},
				body = $(window),
				wd = body.width(),
				hd = body.height();
			size['width'] = wd;
			size['height'] = hd;
			return size;
		},
		
		showDialog : function(str, delay){
			$('#dialog').html( str ).dialog('open');
			setTimeout(function(){
				$('#dialog').dialog('close');
			}, delay||1000);
		},
		showLoading : function( str ){
			var str = str || '加载数据中...';
			this.loading = $.bae_progressbar({
				message:"<p>"+ str +"</p>",
				modal:false,
				canCancel : true
			});
		},
		
		closeLoading : function(){
			this.loading.close();
			$('#bae_progress_box').remove();
		},
		
		instanceNavigator : function( columnEl, visibleCount, index ){	 //初始化栏目组件,第一个参数zepto对象，第二个参数栏目默认显示数
			columnEl.navigator( {
				visibleCount : visibleCount,   //配置栏目默认显示数
				index : index
			});
		},
		
		getCurrentMunites : function(){
			var now = new Date(),
				minutes = now.getMinutes();
			return (minutes > 10) ? minutes : '0' + minutes;
		},
	};
	return Oper;
})();

$.templete = {
	next_body_tpl : 
		'<div class="<%=page%>-body transition" _attr="<%= index%>">' + 
			'<div class="detail-wrap" id="<%= id%>"></div>' +
		'</div>' +
		'',
	
	nearby_wrap_tpl : 
		'<div class="nearby-inner">' +
			'<div class="nearby-route-list">' +
				'<div class="ui-refresh-up ui-refresh-btn hide" noevent="<%= noevent%>"></div>' +
				'<ul id="thelist" class="data-list">' +
					
		        '</ul>' +
			'</div>' + 
			'<div class="nearby-route-failed">' +
				'<div class="falied-pic"></div>' +
				'<div class="buttons">' +
					'<div class="vertical-btns">' +
						//'<a class="common-btn">地图选位置</a>' +
						'<a class="common-btn">重新定位</a>' +
					'</div>' +
					'<div class="m2o-flex space-between-btns">' +
						'<a class="square-btn route m2o-flex-one" data-type="route"></a>' +
						'<a class="square-btn station m2o-flex-one" data-type="station"></a>' +
						'<a class="square-btn transfer m2o-flex-one" data-type="transfer"></a>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>' + 
		'',
	data_offer : 
		'<div class="data-offer">' +
			'<p>本模块数据由 无锡市交通运输管理处</p>' +
			'<p>无锡市公共交通股份有限公司 和 无锡新区公共交通有限公司 提供</p>' +
		'</div>' +
		'',
	//附近线路
	nearby_route_tpl : 
		'<li class="bus-item" _flag="<%=name%><%=flag%>" _routeid="<%=routeid%>" _segmentid="<%=segmentid%>" _stationseq="<%= stationseq%>" _tstationseq="<%= tstationseq%>" _stationf="<%= stationf%>" _tstationf="<%= tstationf%>">' +
			'<div class="m2o-flex bus-info m2o-flex-center">' +
				'<div class="bus-base-info">' +
					'<a>' +
					'<p class="bus-num blue"><%=name%></p>' +
					'<p class="bus-special-flag blue"><%=flag%></p>' +
					'</a>' +
				'</div>' +
				'<div class="m2o-flex-one">' +
					'<p class="bus-end">开往<span class="blue" _tend="<%=tend%>" _end="<%=end%>"><%=end%></span></p>' +
					'<p class="nearest">离我最近站<a><span class="red" _tstationname="<%=tstationname%>" _stationname="<%=stationname%>"><%=stationname%></span></a></p>' +
				'</div>' +
			'</div>' +
			'<div class="handle m2o-flex m2o-flex-center">' +
				'<p class="distance m2o-flex-one m2o-overflow"></p>' +
				'<%if(tstationname){%><a class="btn reverse">反向</a><%}%>' +
				'<a class="btn favor <%if(collect){%>favored<%}%>">收藏</a>' +
			'</div>' +
		'</li>',
	
	//附近站点
	nearby_station_tpl : 
		'<li class="station-item m2o-flex m2o-flex-center" _stationid="<%=stationid%>" >' +
			'<div class="station-route m2o-flex-one">' +
				'<p class="stationname"><%=stationname%></p>' +
				'<p class="segmentroute m2o-overflow"><%=segmentroute%></p>' +
			'</div>' +
		'</li>',
	
	//附近服务
	nearby_service_tpl : 
		'<li class="station-item m2o-flex m2o-flex-center service-item" _lat="<%=lat%>" _lng="<%=lng%>">' +
			'<div class="station-route m2o-flex-one">' +
				'<p class="stationname"><%=title%></p>' +
				'<p class="segmentroute m2o-overflow"><%=address%></p>' +
			'</div>' +
		'</li>',
	//附近服务box
	service_box : 
		'<div class="map-list">' + 
			'<ul id="thelist" class="data-list">&nbsp;</ul>' +
		'</div>' +
		'',
	//查线路
	route_search_tpl : 
		'<div class="bus-inner bus-route">' +
			'<div class="search-area">' +
				'<div class="common-input-box">' +
					'<div class="input-item m2o-flex m2o-flex-center">' +
						'<span class="icon icon-route"></span>' +
						'<input class="common-search-input input m2o-flex-one" placeholder="输入线路名" value=""/>' +
						'<span class="handle-btn clear common-empty-input"></span>' +
					'</div>' +
					'<div class="fuzzy-matching">' +
						'<ul></ul>' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div class="vertical-btns">' +
				'<a class="common-btn">查询</a>' +
			'</div>' +
			'<div class="clear-inner"></div>' + 
		'</div>' + 
		'',
	/*实时公交*/
	interval_list_tpl :
		'<li class="m2o-flex m2o-flex-center">' + 
			'<div class="icons"><a class="bus"></a></div>' + 
			'<%if(busselfid){%><p class="m2o-flex-one">车辆<span><%=busselfid%></span>已于<span><%=actdatetime%></span>达到<span><%=stationname%></span>，距离本站还有<span><%=stationnum%>站</span></p><%}else{%><p><%=message%></p><%}%>' + 
		'</li>' + 
		'',
	//查站点
	station_search_tpl : 
		'<div class="bus-inner bus-station">' +
			'<div class="search-area">' +
				'<div class="common-input-box">' +
					'<div class="input-item m2o-flex m2o-flex-center station-point" _attr="station">' +
						'<span class="icon icon-station"></span>' +
						'<input class="common-search-input input m2o-flex-one" placeholder="输入站点名" value=""/>' +
						'<span class="handle-btn location"></span>' +
					'</div>' +
					'<div class="fuzzy-matching">' +
						'<ul></ul>' +
					'</div>' +
				'</div>' +
			'</div>' +
			'<div class="vertical-btns">' +
				'<a class="common-btn">查询</a>' +
			'</div>' +
			'<div class="clear-inner"></div>' + 
		'</div>' + 
		'',
	//存储box
	storage_box : 
		'<div class="query-area">' +
			'<span class="title">最近查询</span>' +
			'<a class="clear-btn">清空</a>' +
			'<div class="recent-query">' +
			'</div>' +
		'</div>' +
		'',
	//存储数据
	storage_tpl : 
		'<a class="recent-query-item" <%if( lat ){%>_lat="<%= lat%>" _lng="<%= lng%>"<%}%> _id="<%=key%>"><%=content%></a>' +
		'',
		
	station_wrap_tpl : 
		'<div class="nearby-inner">' +
			'<%if( nearStation ){%><div class="common-station m2o-overflow"></div><%}%>' +
			'<div class="nearby-route-list">' +
				'<ul id="thelist" class="data-list">' +
					
		        '</ul>' +
			'</div>' + 
		'</div>' + 
		'',
		
	//站点线路
	station_route_tpl : 
		'<li class="bus-item station-route" _flag="<%=name%><%=flag%>" _routeid="<%=routeid%>" _segmentid="<%=segmentid%>" _stationseq="<%= stationseq%>" _tstationseq="<%= tstationseq%>" _stationf="<%=stationf%>" _tstationf="<%=tstationf%>">' +
			'<div class="m2o-flex bus-info m2o-flex-center">' +
				'<div class="bus-base-info">' +
					'<a>' +
					'<p class="bus-num blue"><%=name%></p>' +
					'<p class="bus-special-flag blue"><%=flag%></p>' +
					'</a>' +
				'</div>' +
				'<div class="m2o-flex-one">' +
					'<p class="bus-end" _stationname="<%=stationname%>"><span class="start" _start="<%=start%>" _tstart="<%=tstart%>"><%=start%></span> - <span class="end" _end="<%=end%>" _tend="<%=tend%>"><%=end%></span></p>' +
					'<%if(tstart || (!tstart && starttime)){%><p class="nearest" _starttime="<%=starttime%>" _tstarttime="<%= tstarttime%>">首末班车 <%=starttime%></p><%}%>' +
				'</div>' +
			'</div>' +
			'<div class="handle m2o-flex m2o-flex-center">' +
				'<p class="distance m2o-flex-one"></p>' +
				'<%if(tstart){%><a class="btn reverse">反向</a><%}%>' +
			'</div>' +
		'</li>' + 
		'',
		
	//查换乘
	transfer_search_tpl : 
		'<div class="bus-inner bus-transfer" data-type="transfer">' +
			'<div class="search-area">' +
				'<div class="common-input-box">' +
					'<div class="input-item m2o-flex m2o-flex-center starting-point" _attr="starting">' +
						'<span class="icon icon-location"></span>' +
						'<input class="input m2o-flex-one common-search-input" placeholder="输入起始站" value=""/>' +
						'<span class="handle-btn location"></span>' +
					'</div>' +
					'<div class="input-item m2o-flex m2o-flex-center terminal-point" _attr="terminal">' +
						'<span class="icon icon-location"></span>' +
						'<input class="input m2o-flex-one common-search-input" placeholder="输入终点站" value=""/>' +
						'<span class="handle-btn location"></span>' +
					'</div>' +
				'</div>' +
				'<a class="transfer-exchange-btn icon-interchange"></a>' +
			'</div>' +
			'<div class="vertical-btns">' +
				'<a class="common-btn query-transfer-btn">查询</a>' +
			'</div>' +
			'<div class="clear-inner"></div>' + 
		'</div>' + 
		'',
	//收藏列表
	collect_search_tpl : 
		'<div class="subnav secondnav">' +
			'<ul>' +
			 	'<li class="classify-item" data-type="route"><a>线路</a></li>' +
			 	'<li class="classify-item" data-type="station"><a>站点</a></li>' +
			 	'<li class="classify-item" data-type="transfer"><a>换乘</a></li>' +
			 '</ul>' +
		'</div>' + 
		'<div class="collect-wrap"></div>' + 
		'', 
	//收藏二级tpl
	collect_wrap_tpl :
		'<div class="collect-inner">' +
			'<div class="collect-route-list">' +
				'<ul id="thelist" class="collect-list">' +
		        '</ul>' +
			'</div>' + 
		'</div>' + 
		'',
	collect_no_tpl : 
		'<p class="nodata <%= nopos%>">&nbsp;</p>' +
		'',
	//收藏列表
	collect_list_tpl :
		'<li class="collect-item m2o-flex m2o-flex-center collect-<%= type%>" <%if(flag){%>_flag="<%=flag%>" _routeid="<%=routeid%>"<%}%> _id="<%=key%>" _station="<%=station%>" _stationseq="<%=stationseq%>" <%if(stationf){%>_stationf ="<%=stationf%>"<%}%>>' +
			'<div class="icons" <%if(startinglat){%>_startinglat="<%=startinglat%>" _startinglng="<%=startinglng%>" _terminallat="<%=terminallat%>" _terminallng="<%=terminallng%>"<%}%>><span class="flag"></span></div>' +
			'<%if(flag){%><span class="name"><%=flag%></span><%}%>' + 
			'<span class="info m2o-flex-one m2o-overflow"><%=title%></span>' + 
			'<a class="handle-btn del-btn" data-type="<%= type%>"></a>' +
		'</li>',
		
	//线路详情--导航栏
	route_detail_subnav_tpl : 
		'<header class="subnav route-subnav">' + 
			'<ul>' + 
			'</ul>' +
		'</header>' + 
		'<div class="route-detail"></div>' + 
		'<ul class="route-interval"></ul>' +
		'<div class="route-map">' + 
			'<a class="bus-map-icon current">&nbsp;</a>' + 
			'<div id="route-map"></div>' +
		'</div>' + 
		'<footer class="common-foot">' + 
			'<div class="tabbar m2o-flex">' + 
				'<div class="m2o-flex-one icon refresh">刷新</div>' + 
				'<div class="m2o-flex-one icon reverse">反向</div>' + 
				'<div class="m2o-flex-one icon list map">地图</div>' + 
			'</div>' + 
		'</footer>' +
		'',
	//线路详情--终点站
	route_detail_terminal_tpl : 
		'<li class="route-terminal"><a class="m2o-overflow">开往<span class="aim"><%= terminal%></span></a></li>',
	//线路详情--单程站点
	route_detail_info_tpl : 
		'<div class="route-list-wrap">'+
			'<div class="route-info">首末班车 <%=info%></div>' +
			'<ul class="route-list"></ul>' + 
		'</div>',
	//线路详情--单程站点列表
	route_detail_list_tpl : 
		'<li class="item m2o-flex m2o-flex-center" _routeid="<%=routeid%>" _segmentid="<%=segmentid%>" _stationseq="<%=stationseq%>">' +
			'<div class="icons">' +
				'<a class="icon gg4"></a>' +
				'<a class="icon normal"></a>' +
			'</div>' +
			'<div class="detail">' +
				'<p class="station-name"><%=stationname%></p>' +
			'</div>' +
		'</li>',
	//查线路-结果-li
	route_search_result_tpl :
		'<li class="route-search-result" _flag="<%=segmentname2%>" _routeid="<%=routeid%>" _segmentid="<%=segmentid%>" ><%=segmentname2%><a class="more"></a></li>',
	
	//查站点-结果-li
	station_search_result_tpl :
		'<li class="station-search-result" _flag="<%=stationname%>" _stationid="<%=stationid%>"><%=stationname%><a class="more"></a></li>',
	
	//查地图服务-结果-li
	service_search_result_tpl :
		'<li class="service-search-result" _lat="<%=lat%>" _lng="<%=lng%>" title="<%=title%>"><%=title%><a class="more"></a></li>',
	
	dialog_tpl : 
		'<div id="dialog" title="">' +
			'<p></p>' +
		'</div>' + 
		'',
	// //换乘模糊查询ul
	transfer_fuzzy_ul_tpl : 
		'<div class="fuzzy-matching"><ul></ul></div>',
		
	//换乘模糊查询li
	transfer_search_result_tpl : 
		'<li class="transfer-fuzzy-item <%if(match){%><%=match%><%}%>" _lat="<%=blatitude%>" _lng="<%=blongitude%>"><%=stationname%><a class="more"></a></li>',
	
	//换乘方案列表
	transfer_detail_tpl : 
		'<div class="nearby-inner">' + 
			'<div class="transfer-detail-title common-station m2o-overflow">' +
				'<%= title%>' + 
				'<%if(type){%><a class="icon"></a><%}%>' +
			'</div>'+
			'<div class="transfer-list-wrap">' + 
				'<div class="transfer-list-inner"></div>' + 
			'</div>' + 
		'</div>' + 
		'',
	//换乘方案
	transfer_item_tpl : 
		'<div class="bae-collapse" _startinglat="<%= startinglat%>" _startinglng="<%= startinglng%>" _terminallat="<%= terminallat%>" _terminallng="<%= terminallng%>">' +
			'<div class="bae-head m2o-flex">' +
				'<span class="index">方案<%if(type){%><%= index%><%}%></span>' +
				'<span class="info m2o-flex-one"></span>' +
				'<span class="arrow"></span>' +
			'</div>' +
			'<div class="bae-body">' +
				'<div class="detail"><%= detail%></div>' + 
				'<%if(type){%><div class="handle"><a class="btn listCollect" data-type="transfer" data-index="<%= k%>">收藏此方案</a></div><%}%>' +
			'</div>' +
		'</div>',
	style_bug : '.ui-refresh .ui-refresh-up, .ui-refresh .ui-refresh-down{background-color:transparent;}'+
		'',
		
};
