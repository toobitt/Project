;(function($){
	var defaultOptions = {
		baseUrl : 'http://fapi.wifiwx.com/mobile/api/cmc/',
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
								'<div class="ui-refresh-up ui-refresh-btn" noevent="<%= noevent%>"></div>' +
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
										'<div class="m2o-flex-one icon playlist"></div>' +
										'<div class="m2o-flex-one"></div>' +
									'</div>' +
								'</footer>' +
								'<div class="detail-cover"></div>' +
								'<div class="playlist-wrap">' +
									'<div class="playlist-inner">' +
									'<header class="subnav">' +
										'<ul>' + 
										'</ul>' +
									'</header>' +
									'<ul class="list detail-list" id="<%= detail_id%>">' +
									'</ul>' +
									'</div>' +
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
							'<video src="<%= m3u8%>" controls="controls" poster="<%= logo.rectangle.url%>" height="240"; style="width:100%;height:240px;margin:0 auto"/>' +
						'</div>' +
						'',
			audio_week : '<div class="subnav bae-head">' +
						'<ul></ul>' +
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
									'<audio src="<%= m3u8%>" autoplay="autoplay" poster="<%= logo.rectangle.url%>" height="300"; style="width:100%;height:300px;margin:0 auto"/>' +
								'</div>' +
							 '</div>' +
						'</div>' +
						'',
			
		};
		this.el.on( 'click tap', '.subnav li', function(){								//栏目切换事件,根据栏目取对应的列表数据
			if( $(this).hasClass('selected') ) return;
			var id = $(this).data('id');
			$(this).addClass('selected').siblings().removeClass('selected');
			_this.showLoading();
			_this.listAjax( {
				method : 'livelist',
				param : {
					node_id : id,
					offset : _this.options.count
				}
			}, null );
		} );
		
		
		this.el.on('click tap touchstart touchend', '.ui-refresh-btn', function( event ){		//屏蔽加载更多的click事件
			if( $(this).attr('noevent') ){
				return false;
			}
			event.stopPropagation();
		});
		
		this.el.on( 'click', '.data-list .list-item', $.proxy(_this.list_item_event,_this)			//列表页切换到详情页
		);
		
		this.el.on( 'click', '.detail-list .list-item', $.proxy(_this.detail_item_event,_this)			//详情页切换
		);
		
		this.el.on( 'click', '.goFirstPage', function(event){				//详情页切换到列表页
			_this.backPage();
			
		} );
		this.el.on( 'tap', '.ui-bae-go-back', function( event ){								//退出应用事件，绑定在主页的back按钮上
			if( $(this).hasClass('goFirstPage') ) return;
			event.stopPropagation();
			Widget.close();
		} );
	};
	
	$.extend( News.prototype, {
		
		list_item_event : function( event ){
			var box = $(event.currentTarget).closest('li'),
				id = box.data('id');
			this.goPage( id );
		},
		
		detail_item_event : function( event ){
			var self = $(event.currentTarget), delaytime = 0,
				id = self.attr('_id'),
				vedio_url = self.find('.live-name').attr('_vedio_url'),
				text = self.find('.live-name').html();
			var other = $('#detail-wrapper').find('li[_id="' + id +'"]');
			if( !self.hasClass('live') ) return;
			if( self.closest('ul').is('#detail-wrapper') ){
				this.showToggle( false );
				delaytime = 1000;
				other = $('.content-box-word').find('li[_id="' + id +'"]');
			}
			setTimeout(function(){
				if( self.closest('.second-body').data('id') == 2 ){
					var play_box = $('.broadcast-play-box');
					play_box.find('.flag').html('正在播放：' + text );
					play_box.find('audio').attr('src', vedio_url);
				}else{
					$('.player-wrap').find('video').attr('src', vedio_url);
				}
			}, delaytime);
			self.addClass('current').siblings().removeClass('current');
			other.addClass('current').siblings().removeClass('current');
			event.stopPropagation();
		},
		
		audioSwitch : function( self ){
			var pause = self.hasClass('pause');
			self[(pause ? 'add' : 'remove') + 'Class']('on')[(pause ? 'remove' : 'add') + 'Class']('pause');
			self.closest('.broadcast-player').find('audio')[pause ? 'play' : 'pause']();
		},
		
		detailinit : function( second_body, options ){
			var _this = this;
			$('.broadcast-player .btn').click(function(){
				_this.audioSwitch( $(this) );
			});
			$('.playlist').click(function(){
				_this.showProgram();
			});
			$('.detail-cover').click(function(){
				_this.showToggle( false );
			});
			_this.initWeek( second_body.find('.subnav'), options );
		},
		
		initWeek : function( dom, options ){		//初始化星期
			var _this = this;
			//	url = this.interface_tool( 'week' );
			//this.ajax( url, null, function( json ){
				var html_str = '',
					parseTpl_func = $.parseTpl( _this.template.column );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
				var json = [{id : '1', name : '周一'}, 
							{id : '2', name : '周二'}, 
							{id : '3', name : '周三'}, 
							{id : '4', name : '周四'}, 
							{id : '5', name : '周五'}, 
							{id : '6', name : '周六'}, 
							{id : '7', name : '周日'}, 
				]
				$.each( json, function(key,value){
					html_str += parseTpl_func( value );
				} );
				dom.find('ul').append( html_str );
				dom.find('li').eq(0).addClass('selected');
				_this.instanceNavigator( dom, 5 );
			//} );
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
				url = this.interface_tool( 'tab' );
			this.ajax( url, null, function( json ){
				var html_str = '',
					parseTpl_func = $.parseTpl( _this.template.column );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
				$.each( json, function(key,value){
					html_str += parseTpl_func( value );
				} );
				_this.column.find('ul').append( html_str );
				_this.column.find('li').eq(0).addClass('selected');
				_this.instanceNavigator( _this.column, 2 );
				_this.listAjax( {
					method : 'livelist',
					param : { node_id : 1, offset : _this.options.count}
				}, null );
			} );
		},
		
		listAjax : function( options, callback ){
			var _this = this,
				url = this.interface_tool( options.method );
			this.ajax( url, options.param, function( json ){
				options.len = ( $.isArray( json ) && json.length ) || 0;
				options.callback = callback;
				_this.closeLoading();
				_this.listAjaxCallback( json, options );
			} );
		},
		
		listAjaxCallback :function( json, options ){
			var _this = this,
				html_str = '',
				parseTpl_func = $.parseTpl( this.template.list ),	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
				data = $.map( json, function( value ){
					value.src = _this.createImgsrc( value['logo']['rectangle']  );
					value.live = value['cur_program']['program'];
					value.time = value['cur_program']['start_time'];
					value.type = (value['audio_only'] == '1')? 'broadcast' : 'tv';
					return value;
				} );
				
				/*pc端测试数据*/
				data = [
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '1', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '2', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '3', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '7', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'tv' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '8', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '9', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '1', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '2', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '3', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '1', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '2', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '3', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'broadcase' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '7', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'tv' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '8', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'tv' },
					{ src : 'http://img.wifiwx.com/material/live/img/2013/03/20130301114124hNjV.png', id : '9', name : '新闻综合频道', live : '电视剧', time : '05:00', type : 'tv' },
				]

			if( !options['ismore'] && this.listScroll ){			//如果是栏目切换，会根据传过来的ismore参数进行重置list的refresh组件,ismore的含义是代表是否是加载更多触发的
				this.restoreListScroll();
			}
			$.each( data, function( key, value ){
				html_str += parseTpl_func( value );
			} );
			this.list[options.dir == 'up' ? 'prepend' : 'append']( html_str );
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
			if( len >= this.options.count ){
				this.ajaxRefreshBtn.show().data('options',options);
			}else{
				this.ajaxRefreshBtn.hide().data('options',null);
			}
		},
		
		initListScroll : function(){								//初始化列表页scroll
			var _this = this,
				head_height = this.head.height(),
				column_height = this.column.height(),
				window_height = window.innerHeight,
				wrap_height = window_height - head_height - column_height - 10;
			this.wrap.css( 'height', wrap_height + 'px' ).refresh({
                load: function (dir, type) {							
                    var more_btn = _this.wrap.find('.ui-refresh-down'),
                    	options = more_btn.data('options');
                    options.ismore = true;
                    options.param.offset += _this.options.count;		//加载更多，首先把offset加等到每页显示的条数加现在的offset
                    _this.refreshWidget = options.refreshWidget = this;
                    options.dir = dir;
                    _this.listAjax( options, function( obj ){
                    		_this.refreshScroll( obj );					//_this.refreshWidget加载完列表后刷新refresh组件回调
                    } );		
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
			if( len >= this.options.count ){
				this.ajaxRefreshBtn.show().data('options',options);
			}else{
				this.ajaxRefreshBtn.hide().data('options',null);
			}
		},
		
		initDetailScroll : function( dom ){									 //初始化内容页滚动条
			dom.refresh();
		},
		
		instanceNavigator : function( columnEl, visibleCount ){				 //初始化栏目组件,第一个参数zepto对象，第二个参数栏目默认显示数
			columnEl.navigator( {
				visibleCount : visibleCount   //配置栏目默认显示数
			});
		},
		
		ajax : function( url, param, callback ){		//ajax工具函数
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
			if( this.loading ){
				$('#bae_progress_box').show();
				return;
			}
			this.loading = $.bae_progressbar({
				message:"<p>加载数据中...</p>",
				modal:true,
				canCancel : true
			});
		},
		
		closeLoading : function(){										//关闭加载等待
			this.loading.close();
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
			} ).appendTo( 'body' ).css('left',0);
			var options = {
				channel_id : id,
				ismore : false
			}
			setTimeout( function(){
				_this.showLoading();
				var hei = (type == 2) ? 130 : 300
				second_body.find('#content-wrapper').css( 'height', ( size['height']-head_height -hei) +'px'  );
				second_body.find('#detail-wrapper').css( 'height', ( size['height']-head_height - 65) +'px'  );
				_this.detail( second_body, options );
			}, 300 );
		},
		
		program : function( second_body, options ){
			var _this = this,
				url = this.interface_tool( 'program' ),
				pro_Array = [];
			this.ajax( url, options, function( json ){
				var html_str = '',
					parseTpl_func = $.parseTpl( _this.template.detail_list );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
				$.each( json, function(key,value){
					value.id = key + 1;
					html_str += parseTpl_func( value );
				} );
				var content_box = $('.content-box'),
					content_box_wd = content_box.width();
				$('.detail-list').append( html_str );
				content_box.find('.content-box-word').css( {
					'width' :  content_box_wd + 'px'
				} );
				_this.closeLoading();
				if( !options['ismore']  ){								//如果是首次加载加载页面，dom渲染完后初始化list的refresh组件
					var dom = _this.el.find('#content-wrapper'),
						detail = _this.el.find('#detail-wrapper');
					_this.initDetailScroll( dom );
					_this.initDetailScroll( detail );
				}
				_this.detailinit( second_body, options );
			} );
		},
		
		showProgram : function(){
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
			var _this = this,
				url = this.interface_tool( 'tvdetail' );
			this.ajax( url, options, function(json){
				var data = json[0];
				var head_box = second_body.find('.ui-bae-header'),
					foot_box = second_body.find('.common-foot');
				second_body.find('.ui-bae-header-left')[0].nextSibling.nodeValue = data.name;
				_this.program( second_body, options );
				var tpl =  (data.audio_only == '1') ? _this.template.audio_tpl : _this.template.vedio_tpl;
				detail_tpl = $.parseTpl( tpl, data );
				if( data.audio_only == '1' ){
					var week_tpl = _this.template.audio_week;
					foot_box.before( detail_tpl );
					head_box.after( week_tpl );
					var dom = second_body.find('.bae-head');
					_this.initWeek( dom, options );
				}else{
					head_box.after( detail_tpl );
				}
			});
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