function Layout(){
	this.size = this.getSize();
}
$.extend( Layout.prototype, {
	getSize : function(){
		var doc = document;
		return {
			width : doc.documentElement.clientWidth,
			height : doc.documentElement.clientHeight
		}
	},
	
	goNext : function( str, dom ){
		var _this = this,
			size = this.size,
			current_body = dom.closest('.transition[_attr]'),
			head = current_body.find('.ui-bae-header'),
			head_clone = head.clone();
		head_clone.find('a').addClass('goPrevPage');
		var attr = current_body.attr('_attr');
		$('body').find('.transition[_attr]').each(function(){
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
		var next_body = $( $.parseTpl( this.tpl.next_body_tpl, {page : param[ attr ], id : param[ attr ] + '-wrapper', index : parseInt(attr) + 1} ) );
		next_body.prepend( head_clone );
		next_body.css( {
			height : size['height'] + 'px',
			position:'absolute',
			width :  size['width'] + 'px'
		} ).insertAfter( current_body ).css('left',0);
		str && (next_body.find('.ui-bae-header-left')[0].nextSibling.nodeValue = str);
		this.showLoading();
		return next_body;
	},
	
	backPage : function( self ){										//回退到主页
		var _this = this,
			size = this.size;
		var current_body = self.closest('.transition[_attr]'),
			attr = current_body.attr('_attr');

		$('body').find('.transition[_attr]').each(function(){
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
	
	initnavigator : function( columnEl, visibleCount, index ){	 //初始化栏目组件,第一个参数zepto对象，第二个参数栏目默认显示数
		columnEl.navigator( {
			visibleCount : visibleCount,   //配置栏目默认显示数
			index : index
		}).show();
	},
	
	showLoading : function( str ){										//显示加载等待
		str = str || '加载数据中';
		this.loading = $.bae_progressbar({
			message:"<p>" + str + "...</p>",
			modal:false,
			canCancel : true
		});
	},
	
	closeLoading : function(){										//关闭加载等待
		this.loading.close();
		$('#bae_progress_box').remove();
	},
	
	initDialog : function(){
		$('body').append( $.templete.dialog_tpl );
		$('#dialog').dialog({
			autoOpen : false,
			content : '',
			mask : false,
			width : 'auto'
		});
	},
	
	/** gmu dialog弹窗 */
	showDialog : function( str, delay ){
		$('#dialog').html( str ).dialog('open');
		setTimeout(function(){
			$('#dialog').dialog('close');
		}, delay||1000);
	},
});


$.templete = { 
	dialog_tpl : 
		'<div id="dialog" title="">' +
			'<p></p>' +
		'</div>' + 
		'',
	next_body_tpl : 
		'<div class="<%=page%>-body transition" _attr="<%= index%>">' + 
			'<div class="detail-wrap" <%if( id ){%>id="<%= id%>"<%}%> ></div>' +
			'<div class="set-font-size">' + 
				'<span class="small selected" _attr="small" >小</span><span class="middle" _attr="middle" >中</span><span class="large" _attr="large" >大</span>' +
			'</div>' +
		'</div>' +
		
		'',
	
	
	/*线路规划*/
	map_area_list : 
		'<span class="area-circle" id="area<%=id%>" data-id="<%=id%>" data-line="<%=line%>" data-istoilet="<%= has_toilet%>" data-color="<%= sub_color%>" data-title="<%= title%>" data-size="<%=left%>,<%=top%>" style="left:<%=left%>; top:<%=top%>; "></span>',
	station_pop : 
		'<div class="station-pop station-btn" id="pop<%=id%>" _id="<%=id%>" _title="<%=title%>" style="left:<%=left%>; top:<%=top%>; ">' + 
			'<div class="station-pop-head <% if(!has_toilet){%>no-toilet<%}%>">' +
				'<span class="icons">' +
					'<% if( sub_color.length ){for( var key in sub_color ){%> ' +
						'<em class="fontsize16" style="background:<%= sub_color[key]%>"><%=line[key]%></em>' +
					'<%}}%>' + 
				'</span>' +
				'<span class="title"><%= title%></span>' +
				'<span class="toilet-flag"></span>' +
				'<span class="station-arrow"></span>' +
			'</div>' +
			'<div class="station-pop-body m2o-flex m2o-flex-center fontsize20">'+
				'<div class="access m2o-flex-one">出入口信息</div>' +
				'<div class="service m2o-flex-one">服务设施信息</div>' +
			'</div>' +
		'</div>' +
		'',
	/*附近站点提示*/
	network_nearby : 
		'<div class="network-nearby fontsize24">距离最近站点' + 
			'<%for( var i=0, len=subway.length; i<len; i++ ){%>' + 
			'<span class="network-line" style="color:<%=sub_color[i]%>"><%=subway[i]%></span>' +
			'<%}%>' +
			'<span class="network-station"><%=title%></span>' +
		'</div>' + 
		'',
	
	/*站点信息*/
	station_tpl : 
			'<div class="search-area">' + 
				'<div class="common-input-box">' + 
					'<div class="input-item m2o-flex m2o-flex-center station-point" _attr="station">' + 
						'<span class="icon icon-station"></span>' + 
						'<input class="common-search-input input m2o-flex-one" placeholder="输入站点名" value="">' + 
						'<span class="handle-btn clear"></span>' + 
					'</div>' + 
					'<div class="fuzzy-matching">' + 
						'<ul></ul>' + 
					'</div>' + 
				'</div>' + 
			'</div>' + 
			'<div class="item-area station-area">' + 
				'<p class="title fontsize20">您附近的站点</p>' + 
			'</div>' + 
			'<div class="item-area route-area">' + 
				'<p class="title fontsize20">按地铁线路查询</p>' + 
			'</div>' + 
		'',
	
	//查站点-结果-tpl
	station_result_tpl :
		'<li class="station-search-result" _title="<%=title%>" _id="<%=id%>"><%=title%><a class="more"></a></li>',
	station_area_tpl : 
		'<div class="m2o-flex m2o-flex-center item-station" _title="<%=title%>" _id="<%=id%>">' + 
			'<div class="icons">' + 
				'<%for(var i=0, len=sub_color.length; i<len; i++){%>' +
					'<span class="icon_line fontsize16" style="background-color:<%=sub_color[i]%>"><%=subway[i].substring(0, 1)%></span>' +
				'<%}%>' +
			'</div>' + 
			'<div class="station-name m2o-flex-one fontsize24"><%=title%></div>' + 
			'<div class="detail fontsize20">距离该站：<%=distance%></div>' + 
		'</div>' + 
		'',
	
	route_area_tpl : 
		'<div class="m2o-flex m2o-flex-center item-station" _id="<%=id%>">' + 
			'<div class="mark" style="background-color:<%=color%>">' + 
			'</div>' + 
			'<div class="route fontsize50"><%=line%></div>' + 
			'<div class="station-name m2o-flex-one">' + 
			'<div class="fontsize22">号线</div>' + 
			'<span class="fontsize14"><%=sign%></span>' + 
			'</div>' + 
			'<div class="detail">' + 
			'<div class="fontsize22"><%=start%> - <%=end%> </div>' + 
			'<span class="fontsize14"><%=start_egname%> - <%=end_egname%></span>' + 
			'</div>' + 
		'</div>' + 
		'',
	/*站点信息*/
	station_info : 
		'<%if( indexpic ){%><div class="station-pic">' +
			'<div class="slider">' +
				'<%for(var i=0, len=indexpic.length; i<len; i++){%>' +
					'<div class="slider-item">' +
						'<a><img lazyload="<%=indexpic[i].src%>"></a>' +
					'</div>' + 
				'<%}%>' +
			'</div>' +
			'<a class="metro_map_btn" _lng="<%=longitude%>" _lat="<%=latitude%>" _title="<%=title%>">&nbsp; </a>' +
		'</div><%}%>' +
		'<div class="station-plat">' +
			'<div class="m2o-flex m2o-flex-center item-station <%if( indexpic ){%>item-notop<%}%>">' +
				'<div class="icons">' +
					'<%for(var i=0, len=sub_color.length; i<len; i++){%>' +
					'<span class="icon_line fontsize16" style="background-color:<%=sub_color[i]%>"><%=subway[i].substring(0, 1)%></span>' +
					'<%}%>' +
				'</div>' +
				'<div class="station-name m2o-flex-one fontsize24"><%=title%></div>' +
			'</div>' +
			'<div class="station-info station-list">' +
				'<p class="title fontsize20">本站首末班车时间</p>' +
				'<%for( var i=0, len=train.length; i<len; i++ ){%>' +
				'<ul class="station-detail">' +
					'<li class="m2o-flex m2o-flex-center item-station">' +
						'<div class="icons">' +
							'<span class="icon_spot" style="background-color:<%=train[i].color%>"></span>' +
						'</div>' +
						'<div class="station-name m2o-flex-one fontsize22">开往<span><%=train[i].start.station%>：</span></div>' +
						'<%if(train[i].start.start_time && train[i].start.end_time){%>' +
						'<div class="detail">' +
							'<span class="fontsize20"><%=train[i].start.start_time%><span>/</span><%=train[i].start.end_time%></span>' +
						'</div>' +
						'<%}%>' +
					'</li>' +
					'<li class="m2o-flex m2o-flex-center item-station">' +
						'<div class="icons">' +
							'<span class="icon_spot" style="background-color:<%=train[i].color%>"></span>' +
						'</div>' +
						'<div class="station-name m2o-flex-one fontsize22">开往<span><%=train[i].end.station%>：</span></div>' +
						'<%if(train[i].end.start_time && train[i].end.end_time){%>' +
						'<div class="detail">' +
							'<span class="fontsize20"><%=train[i].end.start_time%><span>/</span><%=train[i].end.end_time%></span>' +
						'</div>' +
						'<%}%>' +
					'</li>' +
				'</ul>' +
				'<%}%>' +
			'</div>' +
			'<div class="station-btn fontsize22" _id="<%=id%>">' + 
				'<a _attr="access">出入口信息</a><a _attr="facilities">服务设施信息</a>' +
			'</div>' + 
			'<%if(latitude && longitude){%>' +
			'<div class="station-btn streetscape fontsize22" _id="<%=id%>">' + 
				'<a _attr="streetscape" _lat="<%=latitude%>" _lng="<%=longitude%>" _title="<%=title%>">站点出入口街景</a>' +
			'</div>' +
			'<%}%>' +
			'<%if( peaktime && peakbrief ){%>' +
			'<div class="station-info station-peak">' + 
				'<p class="title fontsize20">客流高峰时段</p>' +
				'<div class="peak-tips">' + 
					'<p class="fontsize20"><%=peaktime%><span class="peaktime"><%=peakstart%> - <%=peakend%></span></p>' +
					'<p class="peakbrief fontsize18"><%=peakbrief%></p>' +
				'</div>' + 
			'</div>' +
			'<%}%>' +
		'</div>' +
		'',
	
	//线路详情--导航栏
	route_detail : 
		'<div class="route-info fontsize24" style="background-color:<%=color%>"><%= title%> <%= start%> - <%= end%></div>' + 
		'<header class="subnav route-subnav">' + 
			'<ul>' + 
				'<li class="route-terminal selected">' +
					'<a class="m2o-overflow fontsize24">往<span class="aim"><%= start%></span></a>' + 
				'</li>' + 
				'<li class="route-terminal">' + 
					'<a class="m2o-overflow fontsize24">往<span class="aim"><%= end%></span></a>' + 
				'</li>' +
			'</ul>' +
		'</header>' + 
		'<div class="line-area ">' +
			'<div class="route-list-wrap">' +
				'<ul class="route-list"></ul>' +
			'</div>' +
		'</div>' +
		'',
	//线路详情--单程站点列表
	route_detail_tpl : 
		'<li class="item-station m2o-flex m2o-flex-center item-detail" _title="<%=title%>" _id="<%=id%>">' +
			'<div class="icons">' +
				'<%for(var i=0,len=sub_color.length; i<len; i++){%>' +
				'<span class="icon_line fontsize16" style="background-color:<%=sub_color[i]%>"><%=linecolor[i]%></span>' +
				'<%}%>' +
			'</div>' +
			'<div class="station-name m2o-flex-one">' + 
				'<div class="fontsize24"><%=title%></div>' + 
				'<span class="fontsize14"><%=egname%></span>' +
			'</div>' +
			'<%if( has_toilet == 1 ){%>' +
			'<div class="icons icons-toilet">' +
				'<span class="toilet">&nbsp; </span>' +
			'</div>' +
			'<%}if(start_time && end_time){%>' +
			'<div class="detail">' +
				'<span class="fontsize18"><%=start_time%><span>/</span><%=end_time%></span>' +
			'</div>' +
			'<%}%>' +
			'<div class="icons icons-arrow">' +
				'<span class="arrow">&nbsp; </span>' +
			'</div>' +
		'</li>',	
	
	/*出入口信息*/
	access_info : 
		'<div class="<%=type%>-wrap">' + 
			'<div class="<%=type%>-inner">' + 
			'</div>' +
		'</div>' +
		'',
	
	access_tpl : 
		'<div class="m2o-flex access-list">' +
			'<div class="access-num">' +
				'<span class="num fontsize40"><%=sign%></span>' +
				'<span class="sign fontsize18">出入口</span>' +
			'</div>' +
			'<div class="access-info m2o-flex-one">' +
				'<div class="access-item trafic-item">' +
					'<p class="item-title fontsize24 m2o-overflow"><%=title%></p>' +
					'<div class="item-info">' +
						'<p class="fontsize24"><%=brief%></p>' +
					'</div>' +
				'</div>' +
				'<%if( indexpic && indexpic[0] ){%>' + 
				'<div class="access-item realpic-item" >' + 
					'<div class="access-slider <%if( indexpic.length < 4 ){%>column-item<%}%>">' + 
						'<%for(var i=0,len=indexpic.length; i<len; i++){%>' +
							'<div class="slider-item">' +
								'<a><img src="<%=srealpic[i]%>" _key="<%=i%>" _size="<%=indexpic[i].imgwidth%>x<%=indexpic[i].imgheight%>"></a>' +
							'</div>' + 
						'<%}%>' +
					'</div>' +
					// '<%if( indexpic.length > 3 ){%><span class="slider-icon slider-pre" _attr="0">pre</span>' + 
					// '<span class="slider-icon slider-next" _attr="<%=indexpic.length - 1%>">next</span><%}%>' +
				'</div>' +
				'<%}%>' +
				'<%if( expand && expand[0] ){%>' + 
				'<div class="access-item around-item toggle-add">' +
					'<p class="item-title fontsize24">周边公交<span class="toggle-icon"></span></p>' +
					'<ul class="around-list">' + 
					'<%for(var i=0, len=expand.length; i<len; i++){%>' +
						'<li class="item-info">' +
							'<p class="fontsize24 sign-name"><%=expand[i].station_name%></p>' +
							'<span class="info-list fontsize22 sign-num"><%=expand[i].brief%></span>' +
						'</li>' +
					'<%}%>' +
					'</ul>' +
				'</div>' +
				'<%}%>' +
			'</div>' +
		'</div>' +
		'',	
	facilities_tpl : 
		'<div class="access-item facilities-item">' +
			'<p class="item-title fontsize24" style="background-color:<%=color%>"><%if( title ){%><%=title%><%}%></p>' +
			'<div class="item-info">' +
				'<p class="fontsize20"><%=brief%></p>' +
			'</div>' +
		'</div>' +
		'',
	
	
	/*服务资讯*/
	service_tpl : 
		'<div class="subnav secondnav">' +
			'<ul>' +
				'<%for(var i=0, len=secondnav.length; i<len; i++){%>' +
				'<li class="classify-item <%if(!i){%>selected<%}%>" data-type="<%= secondnav[i].type%>" data-id="<%= secondnav[i].id%>"><a><%=secondnav[i].title%></a></li>' +
				'<%}%>' +
			 '</ul>' +
		'</div>' + 
		'<div class="ticket-wrap"></div>' +
		'', 

	service_list_tpl : 	
		'<div class="ticket-inner">' +
			'<div class="ui-refresh-up ui-refresh-btn hide" noevent="true"></div>' +
			'<ul class="ticket-list clear">' +
		
			'</ul>' +
			'<div class="ui-refresh-down ui-refresh-btn" noevent="<%= noevent%>"></div>' +
		'</div>' +
		'',
	service_list :  
		'<li class="service-item" data-id="<%= id%>">' +
			'<p class="name m2o-overflow fontsize24"><%= title%></p>' + 
			'<p class="time fontsize20"><%= create_time%></p>' + 
		'</li>' +
		'' ,
	service_img_list : 
		'<li class="ticket-item" data-id="<%= id%>">' + 
			'<div class="ticket-info" title="<%= title%>">' + 
			'<div class="info">' + 
				'<img src="<%= img%>" width="<%= img_width%>">' +
				'<p class="fontsize18 m2o-overflow"><%= title%></p>' +
			'</div>' +
		'</div>' +
		'</li>' +
		'',
	service_detail_tpl : 
		'<div class="content-box">' +
		'<div class="content-box-word">' +
			'<h1 class="title"><%= title%></h1>' + 
			'<p><span class="publish-time"><%= create_time_format%></span></p>' + 
			'<p></p>' +
			'<article class="notice <%= fontsize%>" id="notice-wrap">' +
				'<p><%= content%></p>' + 
			'</article>' +
		'</div>' +
		'</div>' +
		'',
	slider_bigpic : 
		'<div class="pic-slider-box">' + 
			// '<a class="close-pic"></a>' +
			'<div class="pic-slide m2o-flex m2o-flex-center">' +
				'<%for(var i=0, len=sliderPic.length; i<len; i++){%>' +
					'<div class="slider-item">' +
						'<a><img src="<%=sliderPic[i].src%>"></a>' +
					'</div>' + 
				'<%}%>' +
			'</div>' +
		'</div>' +
	    '',
	style_bug : 
		'.ui-refresh .ui-refresh-up, .ui-refresh .ui-refresh-down{background-color:transparent;}'+
		'',
};









