(function( $ ) {
	var defaultOptions = {
		Sweek : ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
		Tweek : ['昨天', '今天', '明天'],
		count : 20
	}
	function Live( options ){
		this.$views = $('.views');
		this.op = $.extend({}, options, defaultOptions);
	}
	$.extend( Live.prototype, {
		init : function(){
			this.itool();
			this.loading();
			this.bindEvent();
		},
		
		loading : function(){
			this.initColumn();
			this.insertPop();
		},
		
		bindEvent : function(){
			var _this = this,
				startEV = this.startEv ? 'click' : 'touchstart';
			this.$views.on('click', '.live-list .list-item', $.proxy(this.detail, this));
			this.$views.on('click', '.nodata .refresh', function(){
				_this.refreshList({
					offset : 0
				}, 'refresh');
			})
		},
		
		initColumn : function(){		//直播分类
			var _this = this,
				tool = this.tool;
			var url = tool.interface_tool( 'tab' );
			tool.ajax( url, null, function( data ){
				if( $.isArray( data ) && data.length && data[0]){
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
					$( column_html ).insertAfter( _this.$views.find('.navbar') );
					$( tab_html ).appendTo( _this.$views.find('.main-content') )
					_this.initLayout( data );	//实例化framework
					_this.initContent();
					_this.columnTab('column', _this.$views);		//tab切换事件
				}
			} );
		},
		
		/*广播列表*/
		initContent : function(){
			var _this = this;
			var active = this.$navbar.find('.tab-link.active');
			this.settleAjax( active, 0 );
		},
		
		settleAjax : function( $this, i ){
			var id = $this.attr('_id');
			var info = {
				title : $this.html(),
				method : 'livelist'
			}
			this.ajaxContent({
				node_id : id,
				offset : 0,
				count : this.op.count
			}, info, 'list');
		},
		
		ajaxContent : function( param, info, type ){
			var _this = this,
				tool = _this.tool,
				service = _this.$service;
			var url = tool.interface_tool( info.method );
			tool.ajax( url, param, function( data ){
				if( $.isArray( data ) && data.length && data[0]){
					var size_width = Math.floor(_this.size.width /2 - 40);
					var filter_data = [];
					$.each(data, function(kk, vv){
						vv.live = vv['cur_program']['program'];
						if( !vv.live.match('精彩节目') ){
							if( $.isPlainObject(vv['logo']['rectangle']) ){
								vv.img  = _this.createImgsrc( vv['logo']['rectangle'], {
									height : 50,
									width : 80
								} );
							}
							vv.time = vv['cur_program']['start_time'];
							vv.type = (vv['audio_only'] == '1')? 'broadcast' : 'tv';
							filter_data.push( vv );
						}
					});
					info.list = filter_data;
					info.type = type;
					info.nodata = false;
				}else{
					info.nodata = true;
				}
				
				var html = tool.render(tool.tpl.list_tpl, info),
					area = _this.$views.find( '#main' + param.node_id );
				$( html ).appendTo( area );
			} );
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
						_this.settleAjax(column, 0);
					}
				});
			}else if( type == 'weekbar' ){
				page.find('.tab').on({
					'show' : function( event ){
						var view = $(event.currentTarget);
						if( view.find('.detail-list').length ){
							_this.showindicator();
							return;
						}
						var id = view[0].id.substring(5);
						var weekbar = page.find('.tabs'),
							channel_id = weekbar.attr('_channel_id'),
							type = weekbar.attr('_type');
						var playlist = page.find( '#' + view[0].id );
						_this.program( playlist, {
							zone : id,
							channel_id : channel_id
						}, type, 'playlist' );
					}
				})
			}
		},
		
		detail : function( event ){
			this.create.prevent( event );
			var _this = this,
				self = $(event.currentTarget),
				id = self.data('id');
			this.live_detail(self, {
				'channel_id' : id
			});
		},
		
		live_detail : function( self, info ){
			var _this = this,
				tool = this.tool;
			var	url = tool.interface_tool( 'tvdetail' );
			if( tool.hasDisable( self ) ){
				return;
			}
			tool.addDisable( self );
			tool.ajax( url, info, function( json ){
				tool.removeDisable( self );
				if( $.isArray( json ) && json[0] ){
					var data = json[0];
					var tpl = (data.audio_only == '1') ? tool.tpl.audio_tpl : tool.tpl.vedio_tpl;
					var html = tool.render( tpl, data );
					var page = _this.create.createContent( $.view.mainView, {
						title : data.name,
						className : 'liveDetail',
						toolBar : (data.audio_only == '1') ? false : true,
						defineBar : (data.audio_only == '1') ? true : false,
					});
					var type = (data.audio_only == '1' ? 'audio' : 'video');
					if( type == 'video' ){
						var html_tabbar = tool.render( tool.tpl.program_tabbar, {
							channel_id : info.channel_id, type : type
						} );
						$( html_tabbar ).insertAfter( page );
						$( html ).appendTo( page );
					}else if( type == 'audio'){
						$( html ).insertAfter( page );
					}
					if( top.mainStrap ){
						top.mainStrap.switchNavStatus( 'back' );
					}
					if( type == 'video' ){
						_this.program( page, info, type, 'detail' );
					}else{
						var currentDom = _this.initWeek( page, info.channel_id, type );
						_this.program( currentDom, info, type, 'detail' );
					}
				}
			});
		},
		
		program : function( page, info, type, style ){
			var _this = this,
				tool = this.tool;
			var url = tool.interface_tool( 'program' );
			tool.ajax( url, info, function( json ){
				if( $.isArray( json ) && json[0] ){
					var html = tool.render( tool.tpl.program_tpl, {
						list : json, type : type
					} );
					$( html ).appendTo( page );
					if( style == 'detail' ){
						var wrap = (type == 'video') ? page : page.closest('.page-content');
						_this.initDetail(wrap, 'detail_tpl');
						if( type == 'video' ){
							_this.initPopUp( html, info.channel_id, type );
							_this.initDetail(_this.$playlist, 'pop_tpl');
						}else{
							_this.columnTab('weekbar', wrap );
						}
					}
				}
			})
		},
		
		initPopUp : function( html, channel_id, type ){
			var playwrap = this.$playlist.find('.playlist-wrap').empty();
			this.$playlist.find('.weekbar').remove();
			var currentDom = this.initWeek( playwrap, channel_id, type );
			$( html ).appendTo( currentDom );
			this.columnTab('weekbar', this.$playlist);
		},
		
		
		/*节目单弹框*/
		insertPop : function(){
			var tool = this.tool;
			var pop_html = tool.render(tool.tpl.pop_tpl, {
				type : 'playlist'
			});
			this.$playlist = $( pop_html ).insertAfter( this.$views );
			this.initPop();
		},
		
		initPop : function(){
			var _this = this;
			this.$playlist.on({
				'open' : function(){
					if( top.mainStrap ){
						top.mainStrap.switchNavStatus( 'close' );
					}
					$.myApp.showIndicator();
				},
				'opened' : function(){
					$.myApp.hideIndicator();
				},
				'closed' : function(){
				}
			})
		},
		
		initWeek : function( page, channel_id, type ){
			var tool = this.tool,
				op = this.op;
			var now = new Date(),
				num = now.getDay();
			var Rweek = new Array(7),
				aWeek = [];
			for(var i=0; i<7; i++){
				if( i > 3){
					Rweek[i] = op.Tweek[i-4];
				}else{
					var t = (num + i > 4) ? num + i - 5 : num + i + 2;
					Rweek[i] = op.Sweek[t];
				}
			} 
			$.each( Rweek, function(key, value){
				aWeek.push({
					id : key - 5,
					name : value,
					isCurrent : (key == 5)
				})
			} );
			var info = {
				column : aWeek, 
				type : 'popup',
				navbar : 'weekbar',
				channel_id : channel_id,
				style : type
			}
			var column_html = tool.render(tool.tpl.column_tpl, info),
				tab_html = tool.render(tool.tpl.tab_tpl, info);
			$( column_html ).insertBefore( page );
			$( tab_html ).appendTo( page );
			return page.find('.tab.active');
		},
		
		initDetail : function( page, type ){
			var _this = this;
			if( type == 'pop_tpl' ){
				page.on('click', '.columnbar .tab-link', function(){
					var $this = $(this);
					$.myApp.showTab( $this.attr('href'), $this );
				});
			}
			if( type == 'detail_tpl' ){
				this.$views.on('click', '.broadcast-player .btn', function(){
					var $this = $(this);
					$this.toggleClass('on pause');
					var audio = $this.closest('.broadcast-player').find('audio');
					var pause = $this.hasClass('pause');
					audio[0][pause ? 'pause' : 'play']();
				});
			}
			page.on('click', '.list-item', function(){
				var $this = $(this),
					listwrap = $this.closest('.content-box');
				if( !$this.hasClass('live') ){
					return; 
				}
				listwrap.hasClass('video') ? _this.videoBack( $this ) : _this.audioBack( $this );
				page.find('.list-item').removeClass('selected');
				$this.addClass('selected')
			});
		},
		
		videoBack : function( self ){
			var video_url = self.find('.live-name').attr('_vedio_url'),
				video = this.$views.find('.player-wrap').find('video');
			video[0].src = video_url;
			video[0].play();
		},
		
		audioBack : function( self ){
			var live = self.find('.live-name'),
				video_url = live.attr('_vedio_url'),
				title = live.html();
			var audioWrap = this.$views.find('.broadcast-play-wrap');
			audioWrap.find('audio')[0].src = video_url;
			audioWrap.find('audio')[0].play();
			audioWrap.find('.flag').html( '正在播放：' + title );
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
	window.Live = Live; 
})(Zepto);
$(function(){
	var Live = new window.Live();
	Live.init();
});