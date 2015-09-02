;(function($){
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
	
	function News( el, options ){
		var _this = this;
		this.options = $.extend( {}, options, defaultOptions );
		this.el = el;
		this.head = el.find('.ui-bae-header');
		this.column = el.find('.subnav');
		
		this.init();
		this.template = {												//gmu的模版语法，$.parseTpl(tpl,data);来解析
			column : '<li class="item" data-id="<%= id%>"><a><%= name%></a></li>',
			refresh_wrap : '<div class="data-list-wrap">' + 
								'<ul id="thelist" class="data-list">' +
						        '</ul>' +
								'<div class="ui-refresh-down ui-refresh-btn" noevent="<%= noevent%>"></div>' +
							'</div>' +
							'',
			list :  '<li class="list-item m2o-flex m2o-flex-center" data-id="<%= id%>" >' + 
						'<span class="list-pic">' + 
							'<img src="<%= src%>"/>' + 
						'</span>' +
						'<div class="info m2o-flex-one">' + 
							'<div class="channel-name"><%= name%></div>' + 
							'<p><%= time%><span class="live-name"><%= live%></span></p>' + 
						'</div>' +
						'<a class="live-flag <%= type%>"></a>' + 
					'</li>' +
					'',
			second_body : '<div class="second-body transition">' + 
								'<div class="data-content-wrap" id="<%= id%>">' + 
									'<div class="content-box">' + 
										'<div class="content-box-word">' +
											'<ul class="list detail-list">' +
											'</ul>' +
										'</div>' +
									'</div>' +
								'</div>' +
								'<footer class="common-foot">' +
									'<div class="tabbar m2o-flex">' +
										'<div class="m2o-flex-one"></div>' +
										'<div class="m2o-flex-one icon playlist">节目单</div>' +
										'<div class="m2o-flex-one"></div>' +
									'</div>' +
								'</footer>' +
								'<div class="detail-cover"></div>' +
								'<div class="playlist-wrap">' +
									'<div class="playlist-inner">' +
										'<header class="subnav m2o-flex">' +
											'<div class="nav-box m2o-flex-one">' +
												'<ul>' +
												'</ul>' +
											'</div>' +
											'<a id="arrow"><span>>></span></a>' +
										'</header>' +
										'<div class="detail-wrapper" id="<%= detail_id%>">' +
											'<div class="detail-list-wrapper">' +
												'<ul class="list detail-list">' +
												'</ul>' + 
											'</div>' +
										'</div>' +
									'</div>' +
								'</div>' +
							'</div>' +
							'',
			audio_wrap : '<div class="data-content-wrap" id="<%= id%>">' + 
							'<div class="content-box">' + 
								'<div class="content-box-word">' +
									'<ul class="list detail-list">' +
									'</ul>' +
								'</div>' +
							'</div>' +
						'</div>' +
						'',				
			detail_wrap :'<div class="detail-wrapper" id="<%= detail_id%>">' +
							'<div class="detail-list-wrapper">' +
								'<ul class="list detail-list">' +
								'</ul>' + 
							'</div>' +
						'</div>' +
						'',
			detail_list : '<li class="list-item m2o-flex m2o-flex-center <% if(display){%>live<%}%> <% if(zhi_play){%>current<%}%>" _id=<%= id%>>' +
								'<div class="live-time"> <% if(now_play){%>直播中<%}else{%> <%= start%><%}%></div>' +
								'<div class="state"<% if(zhi_play){%>state_show<%}%>>正在播放</div>' +
								'<div class="m2o-flex-one live-name <% if(now_play){%>nowplay<%}%>" _vedio_url="<%= m3u8%>"><%= theme%></div>' +
								'<% if(display){%><a class="live-flag"></a><%}%>' +
							'</li>' +
						'',
			vedio_tpl : '<div class="player-wrap">' +
							'<video src="<%= m3u8%>" controls="controls" poster="<%= logo.rectangle.url%>" style="width:100%; height:100%; "/>' +
						'</div>' +
						'',
			audio_week : '<div class="subnav bae-head">' +
							'<div class="nav-box m2o-flex-one">' +
								'<ul>' +
								'</ul>' +
							'</div>' +
							'<a id="arrow"><span>>></span></a>' +
						'</div>',
			audio_tpl : '<div class="broadcast-play-wrap">' +
							 '<div class="broadcast-play-box">' +
							 	'<p class="flag">正在播放：<%= cur_program.program%></p>' +
								'<div class="broadcast-player">' +
									'<span class="btn pause">&nbsp;</span>' +
									'<div class="box-progress">' + 
										'<em>&nbsp;</em>' +
										'<p class="play-progress">&nbsp;</p>' +
									'</div>' +
									'<audio src="<%= m3u8%>" poster="<%= logo.rectangle.url%>" style="width:100%;"/>' +
								'</div>' +
							 '</div>' +
						'</div>' +
						'',
			style_bug : '.ui-refresh .ui-refresh-up, .ui-refresh .ui-refresh-down{background-color:transparent;}'+
						'',
			
		};
		this.el.on( 'click tap', '.subnav li', function( event ){								//栏目切换事件,根据栏目取对应的列表数据
			if( $(this).hasClass('selected') ) return;
			var id = $(this).data('id');
			$(this).addClass('selected').siblings().removeClass('selected');
			_this.showLoading();
			if( $(this).siblings().length > 5 ){
				var type = $(this).closest('.playlist-wrap').length ? true : false;
				var channel_id = _this.el.find('.playlist').data('channel_id');
				_this.program({
					channel_id : channel_id,
					zone : id,
					ismore : false
				}, null, type);
			}else{
				_this.listAjax( {
					method : 'livelist',
					param : {
						node_id : id,
						offset : _this.options.count
					}
				}, null );
			}
			event.stopPropagation();
			return false;
		} );
		
		
		this.el.on('click tap touchstart touchend', '.ui-refresh-btn', function( event ){		//屏蔽加载更多的click事件
			if( $(this).attr('noevent') ){
				return false;
			}
			event.stopPropagation();
		});
		
		this.el.on( 'click', '.data-list .list-item', $.proxy(_this.list_item_event,_this)			//列表页切换到详情页
		);
		
		this.el.on( 'click', '.goFirstPage', function(event){				//详情页切换到列表页
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
	
	$.extend( News.prototype, {
		
		style_bug : function(){
			if (this.judgePat()){
				$('<style/>').html( this.template.style_bug ).appendTo( this.el );
			}
		},
		
		judgePat : function(){
			return (/ipad|iphone|mac/i.test(navigator.userAgent)) ? true : false
		},
		
		list_item_event : function( event ){
			var _this = this;
			var box = $(event.currentTarget).closest('li'),
				id = box.data('id');
			if( this.column.find('li.selected').data('id') == 2){
				box.addClass('is-play').siblings().removeClass('is-play');
			}
			var second_body = this.goPage( id );
			setTimeout( function(){
				_this.detail( second_body,{
					channel_id : id,
					ismore : false
				});
			}, 350 );
			event.preventDefault();
		},
		
		detail_item_event : function( self ){
			var _this = this;
			var delaytime = 0, otherParent, Toggle = true,curItem,otherItem,
				id = self.attr('_id'),
				vedio_url = self.find('.live-name').attr('_vedio_url'),
				text = self.find('.live-name').html();
			if( !self.hasClass('live') ) return;
			if( self.closest('.detail-wrapper').length ){
				this.showToggle( false );
				delaytime = 1000;
				otherParent = $('.content-box-word');
				curItem = self.closest('.playlist-inner').find('.item.selected');
				otherItem = this.audionav ? this.audionav.find('.item.selected') : '';
			}else{
				otherParent = this.detailwrap;
				curItem = this.audionav ? this.audionav.find('.item.selected') : '';
				otherItem = otherParent.closest('.playlist-inner').find('.item.selected');
			}
			if( (curItem && curItem.data('id')) || (otherItem && otherItem.data('id'))){
				Toggle = false;
			}
			other = otherParent.find('li[_id="' + id +'"]');
			setTimeout(function(){
				if( self.closest('.second-body').data('id') == 2 ){
					var play_box = $('.broadcast-play-box');
					play_box.find('.flag').html('正在播放：' + text );
					if( play_box.find('.btn').hasClass('on') ){
						play_box.find('.btn').removeClass('on').addClass('pause');
					}
					play_box.find('audio').attr('src', vedio_url)[0].play();
				}else{
					$('.player-wrap').find('video').attr('src', vedio_url)[0].play();
				}
			}, delaytime);
			self.addClass('current').siblings().removeClass('current');
			
			if( Toggle ){
				other.addClass('current').siblings().removeClass('current');
			}else{
				otherParent.find('li').removeClass('current');
			}
			event.stopPropagation();
		},
		
		audioSwitch : function( self ){
			var pause = self.hasClass('pause');
			var url = self.attr('_attr');
			self[(pause ? 'add' : 'remove') + 'Class']('on')[(pause ? 'remove' : 'add') + 'Class']('pause');
			self.closest('.broadcast-player').find('audio')[0][pause ? 'pause' : 'play']();
		},
		
		detailinit : function( options ){
			var _this = this;
			this.detailinitDom( $('.second-body').find('.detail-list') );
			$('.broadcast-player .btn').click(function(){
				_this.audioSwitch( $(this) );
			});
			$('.playlist').click(function(){
				_this.showProgram( $(this) );
			});
			$('.detail-cover').click(function(){
				_this.showToggle( false );
			});
			setTimeout(function(){
				_this.initWeek( _this.detailnav );
			}, 1000);
		},
		
		detailinitDom : function( parent ){
			var _this = this;
			parent.on('click', '.list-item', function(){		//详情页切换
				_this.detail_item_event( $(this) );
			});
			this.detailwrap = $('#detail-wrapper');
			this.detailnav = $('.playlist-wrap').find('.subnav');
			this.contentwrap = $('#content-wrapper');
		},
		
		initWeek : function( dom ){		//初始化星期
			var html_str = '',
				parseTpl_func = $.parseTpl( this.template.column );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			var now = new Date();
			var num = now.getDay();
			var data = {};
			var Sweek = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];
			var Tweek = ['昨天', '今天', '明天'];
			var Rweek = new Array(7);
			for(var i=0; i<7; i++){
				if( i > 3){
					Rweek[i] = Tweek[i-4];
				}else{
					var t = (num + i > 4) ? num + i - 5 : num + i + 2;
					Rweek[i] = Sweek[t];
				}
			} 
			$.each( Rweek, function(key, value){
				var info = {};
				info.id = key - 5;
				info.name = value;
				html_str += parseTpl_func( info );
			} );
			dom.find('ul').append( html_str );
			var index = dom.find('li[data-id="0"]').addClass('selected').index();
			dom.css('display', '-webkit-box');
			var size = this.countSize(dom, 7 );
			this.instanceNavigator( dom, size, index );
		},
		
		init : function(){
			this.initDom();
			this.showLoading();
			var _this = this;
			setTimeout( function(){
				_this.initColumn();
			},1000 );
		},
		
		initDom : function(){		
			this.wrap = this.el.find('.data-list-wrap');
			this.list = this.wrap.find('.data-list');
			this.ajaxRefreshBtn = this.wrap.find('.ui-refresh-btn');
		},
		
		initColumn : function(){		//初始化栏目
			var _this = this,
				url = this.interface_tool( 'tab' );
			this.ajax( url, null, function( json ){
					var html_str = '',
					parseTpl_func = $.parseTpl( _this.template.column );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
					$.each( json, function(key,value){
						html_str += parseTpl_func( value );
					} );
					_this.column.find('ul').append( html_str );
					_this.column.css('display', '-webkit-box');
					_this.column.find('li').eq(0).addClass('selected');
					if( !_this.judgePat()  ){
						_this.column.find('.item[data-id="2"]').remove();
					}
					var size = _this.countSize( _this.column, 4 );
					_this.instanceNavigator( _this.column, size );
				_this.listAjax( {
					method : 'livelist',
					param : { node_id : 1, offset : _this.options.count}
				}, null );
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
		
		listAjax : function( options, callback ){
			var _this = this,
				url = this.interface_tool( options.method );
			this.ajax( url, options.param, function( json ){
				options.len = ( $.isArray( json ) && json.length ) || 0;
				options.callback = callback;
				_this.listAjaxCallback( json, options );
			} );
		},
		
		listAjaxCallback :function( json, options ){
			var _this = this,
				html_str = '',
				parseTpl_func = $.parseTpl( this.template.list );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			if( !options['ismore'] && this.listScroll ){			//如果是栏目切换，会根据传过来的ismore参数进行重置list的refresh组件,ismore的含义是代表是否是加载更多触发的
				this.restoreListScroll();
			}
			
			if( $.isArray(json) && json.length ){
				$.each(json, function(key, value){
					value.src = _this.createImgsrc( value['logo']['rectangle']  );
					value.live = value['cur_program']['program'];
					value.time = value['cur_program']['start_time'];
					value.type = (value['audio_only'] == '1')? 'broadcast' : 'tv';
					html_str += parseTpl_func( value );
				});
				this.list[options.dir == 'up' ? 'prepend' : 'append']( html_str );
			}else{
				this.list.append('<p class="nodata">暂无此类数据</p>');
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
		
		setRefreshBtn : function( options ){							//设置加载更多按钮的参数配置，如它的method,param等，如已没有更多，把他的参数配置置为null
			var len = options.len || 0;
			this.ajaxRefreshBtn.show().data('options',options);
			if( len < this.options.count ){
				this.ajaxRefreshBtn.eq(1).hide().data('options',null);
			}
		},
		
		initListScroll : function(){								//初始化列表页scroll
			var _this = this,
				head_height = this.head.height(),
				column_height = this.column.height(),
				window_height = window.innerHeight,
				wrap_height = window_height - head_height - column_height - 10;
			this.wrap.css( 'height', wrap_height + 'px' ).refresh();
            this.listScroll = true;
		},
		
		refreshScroll : function( options ){
            options.refreshWidget.afterDataLoading(options.dir);    	//数据加载完成后刷新refresh组件
            this.setRefreshBtn( options );
		},
		
		setRefreshBtn : function( options ){							//设置加载更多按钮的参数配置，如它的method,param等，如已没有更多，把他的参数配置置为null
			var len = options.len || 0;
			this.ajaxRefreshBtn.hide().data('options',options);
		},
		
		initDetailScroll : function( dom, type ){									 //初始化内容页滚动条
			dom.refresh();
			setTimeout( function(){
				var current = dom.find('.list-item.current');
				if( current.length ){
					var top = current.position().top,
						height = current.height(),
						len = top - 3 * (height + 10);
					var maxlen = dom.find('.list-item').length * (height + 10) - dom.parent().height();
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
		},
		
		drawDetailScroll : function( second_body ){
			var second_body = second_body || $('.second-body');
			var size = this.getSize(),
				head_height = this.el.find('.ui-bae-header').height(),
				hei = (second_body.data('id') == 2) ? 130 : 300;
			second_body.find('#content-wrapper').css( 'height', ( size['height']-head_height -hei) +'px' );
			second_body.find('#detail-wrapper').css( 'height', ( size['height']-head_height - 65) +'px' );
		},
		
		program : function( options, second_body, type){
			var _this = this,
				url = this.interface_tool( 'program' ),
				pro_Array = [];
			this.ajax( url, options, function( json ){
				_this.programBack( json, options, second_body, type );
			} );
		},
		
		programBack : function( json, options, second_body, type ){
			var html_str = '', parent,
				parseTpl_func = $.parseTpl( this.template.detail_list );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			$.each( json, function(key,value){
				value.id = key + 1;
				html_str += parseTpl_func( value );
			} );
			
			if( !second_body ){
				this.restoredetailScroll( type );
			}
			
			if( type === undefined ){
				parent = $('.second-body').find('.detail-list');
			}else if( type ){
				parent = $('.playlist-wrap').find('.detail-list');
			}else{
				parent = $('.data-content-wrap').find('.detail-list');
			}
			this.closeLoading();
			parent.append( html_str );
			if( !options['ismore']  ){								//如果是首次加载加载页面，dom渲染完后初始化list的refresh组件
				var dom = this.el.find('#content-wrapper'),
					detail = this.el.find('#detail-wrapper');
				this.drawDetailScroll( second_body );
				this.initDetailScroll( dom, true );
				this.initDetailScroll( detail, false );
			}
			$('.playlist').data('channel_id', options.channel_id);
			!second_body && this.detailinitDom( parent );
		},
		
		restoredetailScroll : function( type ){								//重置list的refresh组件，因为ajax切换栏目时要把原来的滚动条高度以及滚动条的top位置都要置为初始才能正常浏览
			if( type ){
				this.detailwrap.parent().remove();
				var refresh_wrap = $.parseTpl( this.template.detail_wrap, {detail_id : 'detail-wrapper'} );
				$( refresh_wrap ).insertAfter( this.detailnav );
			}else{
				this.contentwrap.parent().remove();
				var audio_wrap = $.parseTpl( this.template.audio_wrap, {id : 'content-wrapper'} );
				$( audio_wrap ).insertAfter( this.audionav );
			}
		},
		
		showProgram : function( dom ){
			this.showToggle( true );
		},
		
		showToggle : function( type ){
			$('.detail-cover')['fade' + (type ? 'In' : 'Out')]();
			$('.playlist-wrap')[(type ? 'add' : 'remove') + 'Class']('show');
			if( $('.player-wrap').length ){
				var delaytime = type ? 500 : 0;
				setTimeout(function(){
					$('.player-wrap')[type ? 'hide' : 'show']();
				}, delaytime);
			}
		},
		
		detail : function( second_body, options ){
			var _this = this, type,
				url = this.interface_tool( 'tvdetail' );
			var size = this.getSize();
			this.ajax( url, options, function(json){
				var data = json[0];
				var head_box = second_body.find('.ui-bae-header'),
					foot_box = second_body.find('.common-foot');
				second_body.find('.ui-bae-header-left')[0].nextSibling.nodeValue = data.name;
				var tpl =  (data.audio_only == '1') ? _this.template.audio_tpl : _this.template.vedio_tpl;
				detail_tpl = $.parseTpl( tpl, data );
				if( data.audio_only == '1' ){
					foot_box.before( detail_tpl );
					var week_tpl = _this.template.audio_week;
					head_box.after( week_tpl );
					_this.audionav = second_body.find('.bae-head');
					_this.initWeek( _this.audionav );
					type = 'audio';
				}else{
					head_box.after( detail_tpl );
					type = 'video';
					second_body.find('.player-wrap').height( size['width'] * 0.75 );
				}
				setTimeout(function(){
					second_body.find( type )[0].play();
				}, 1000);
				_this.program( options, second_body );
				_this.detailinit( options );
			});
		},
		
		instanceNavigator : function( columnEl, visibleCount, index ){				 //初始化栏目组件,第一个参数zepto对象，第二个参数栏目默认显示数
			columnEl.find('.nav-box').navigator( {
				visibleCount : visibleCount,   //配置栏目默认显示数
				index : index
			});
			columnEl.find('#arrow').on('click', function(){
				columnEl.find('.nav-box').iScroll('scrollTo', 120, 0, 400, true);
			});
		},
		
		ajax : function( url, param, callback ){		//ajax工具函数
			var _this = this;
			$.getJSON( url, param, function( data ){
				if( $.isFunction( callback ) ){
					callback( data );
				}
			});
		},
		
		interface_tool : function( name ){								//拼接接口工具函数
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
		
		showLoading : function(){										//显示加载等待
			this.loading = $.bae_progressbar({
				message:"<p>加载数据中...</p>",
				modal:true,
				canCancel : true
			});
		},
		
		closeLoading : function(){										//关闭加载等待
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
		
		goPage : function( id ){									//主页切换到详情页
			var _this = this,
				size = this.getSize(),
				first_body = this.el.find('.first-body'),
				head = first_body.find('.ui-bae-header'),
				head_height = head.height(),
				head_clone = head.clone();
			var type = first_body.find('.subnav li.selected').data('id');
			head_clone.find('a').addClass('goFirstPage');
			first_body.css( {
				height : size['height'] + 'px',
				position:'absolute',
				left : '-' + size['width'] + 'px',
				'z-index' : 10
			} );
			var second_body = $( $.parseTpl( _this.template.second_body, {id : 'content-wrapper', detail_id : 'detail-wrapper'} ) );
			second_body.prepend( head_clone );
			second_body.data('id', type).css( {
				height : size['height'] + 'px',
				position:'absolute',
				left :  size['width'] + 'px'
			} ).insertAfter( first_body ).css('left',0);
			second_body.find('.playlist-wrap').css('top', size['height'] + 'px');
			setTimeout( function(){
				_this.showLoading();
			}, 300 );
			return second_body;
		},
		
		backPage : function(){										//回退到主页
			var _this = this,
				size = this.getSize(),
				first_body = this.el.find('.first-body');
			first_body.css({left: 0});
			this.el.find('.second-body').css( {
				left : size['width'] + 'px'
			});
			setTimeout( function(){
				_this.el.find('.second-body').remove();
				first_body.removeAttr('style');
			}, 300 );
		},
	} );
	
	window.News = News;
	
})($);
	$(function(){
		var newObj = new News( $('body') );
	});