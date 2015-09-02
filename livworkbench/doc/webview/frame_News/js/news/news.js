(function( $ ) {
	var defaultOptions = {
		dimension : {small : '小', middle : '中', large : '大'},
		count : 20
	}
	function News( options ){
		this.$views = $('.views');
		this.$navbar = this.$views.find('.tabbar');
		this.op = $.extend({}, options, defaultOptions);
	}
	$.extend( News.prototype, {
		init : function(){
			this.itool();
			this.loading();
			this.bindEvent();
		},
		
		loading : function(){
			this.fontStorage = new Hg_localstorage( {key : 'fontsize'} );
			this.initColumn();
		},
		
		bindEvent : function(){
			var _this = this,
				startEV = this.startEv ? 'click' : 'touchstart';
			this.$views.on('click', '.news-list li', $.proxy(this.detail, this));
			this.$views.on('click', '.nodata .refresh', function(){
				_this.refreshList({
					offset : 0
				}, 'refresh');
			})
		},
		
		initColumn : function(){
			var _this = this,
				tool = this.tool;
			var url = tool.interface_tool( 'column' );
			tool.ajax( url, null, function( data ){
				if( $.isArray( data ) && data.length && data[0]){
					data.unshift({
						id : 'top',
						name : '头条'
					});
					var render = template.compile( tool.tpl.column_tpl ),
						html = render({column : data});
					var tab_render = template.compile( tool.tpl.tab_tpl ),
						tab_html = tab_render({column : data});
					$( html ).appendTo( _this.$navbar );
					$( tab_html ).appendTo( _this.$views.find('.tabs') );
					_this.initLayout( data );
					_this.initContent();
					_this.pullRefresh();
					_this.showTab();
				}
			} );
		},
		
		initContent : function(){
			var _this = this;
			var active = this.$navbar.find('.tab-link.active');
			this.settleAjax( active, 0 );
		},
		
		settleAjax : function( $this, i ){
			var id = $this.attr('_id');
			var info = {
				idName : $this.attr('href').substring(1),
				isCurrent : (i == 0) ? true : false,
				title : $this.html(),
				method : id == 'top' ? 'indexlist' : 'newslist'
			}
			this.ajaxContent({
				column_id : id,
				offset : 0,
				count : this.op.count
			}, info, 'list');
		},
		
		pullRefresh : function(){
			var _this = this,
				news = this.$views;
			$.myApp.initPullToRefresh('.newsBox');
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
					var offset = area.data('offset') || 280;
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
			var active = this.$navbar.find('.tab-link.active');
			var param = {
				column_id : active.attr('_id'),
				count : this.op.count
			}
			param.offset = info.offset;
			this.ajaxContent( param, {
				type : active.attr('_type'),
				idName : '',
				isCurrent : '',
				title : active.html(),
				method : param.column_id == 'top' ? 'indexlist' : 'newslist'
			}, type);
		},
		
		ajaxContent : function( param, info, type ){
			var _this = this,
				tool = _this.tool,
				service = _this.$service;
			var url = tool.interface_tool( info.method );
			tool.ajax( url, param, function( data ){
				if( $.isArray( data ) && data.length && data[0]){
					var size_width = Math.floor(_this.size.width /2 - 40);
					$.each(data, function(kk, vv){
						if( $.isPlainObject(vv.indexpic) ){
							vv.img  = _this.createImgsrc( vv['indexpic'], {
								height : 50,
								width : 80
							} );
						}
					});
					info.list = data;
					info.type = type;
					info.nodata = false;
				}else if( type != 'infinite' ){
					info.nodata = true;
				}
				var render = template.compile( tool.tpl.list_tpl ),
					html = render( info );
				var area = _this.$views.find( '#view' + param.column_id );
				if( type == 'infinite' ){
					area = area.find('.news-list');
				}else{
					area.empty();
				}
				$( html ).appendTo( area );
				if( !data && type == 'infinite' ){
					area.data('offset', 'infinite');
				}
			} );
		},
		
		initDetail : function( page, type ){
			var _this = this;
			if( type == 'detail_tpl' ){
				page.on('click', '.img-box img', $.proxy(this.picBrowser, this));
				page.prev('.set-font-size').on('click', 'span', function(){
					var $this = $(this),
						attr = $this.attr('_attr');
					$this.addClass('selected').siblings().removeClass('selected');
					_this.fontStorage.resetItem('fontsize', [attr]);
					page.find('.service').removeClass().addClass( 'service ' + attr );
					page.find('.video-brief').removeClass().addClass( 'video-brief ' + attr );
				});
			}
		},
		
		showTab : function(){
			var _this = this;
			this.$views.find('.tab').on({
				'show' : function( event ){
					var view = $(event.currentTarget);
					$('.page-content')[0].scrollTop = 0;
					if( view.find('.news-list').length ){
						return;
					}else{
						var id = view[0].id.substring(4), 
							column = _this.$navbar.find('.tab-link[_id="' + id + '"]');
						_this.settleAjax(column, 0);
					}
				}
			})
		},
		
		picBrowser : function( event ){
			this.newsDetailDark.open();
			if( top.mainStrap ){
				top.mainStrap.switchNavStatus('close');
			}
		},
		
		detail : function( event ){
			this.create.prevent( event );
			var _this = this,
				self = $(event.currentTarget),
				id = self.data('id'),
				module = self.data('module');
			var current = this.$navbar.find('.tab-link.active'),
				title = current.html();
			this.news_detail(self, id, module);
		},
		
		tuji_back : function( data ){
			var _this = this;
			if( $.isArray(data.tuji_pics) && data.tuji_pics[0] ){
				var tujiPic = data.tuji_pics;
				$.each(tujiPic, function(ii, vv){
					vv.url = _this.createImgsrc( vv['pic'] );
					vv.caption = vv.brief; 
				});
				var tujiDetailDark = $.myApp.photoBrowser({
			 		photos : tujiPic,
					theme:'dark'
				});
				tujiDetailDark.open();
				if( top.mainStrap ){
					top.mainStrap.switchNavStatus('close');
				}
			}
		},
		
		news_detail : function( self, id, module ){
			var _this = this,
				tool = this.tool,
				appName = (module == 'tuji' ? 'tujiDetail' : 'detail');
			var	url = tool.interface_tool( appName );
			if( tool.hasDisable( self ) ){
				return;
			}
			tool.addDisable( self );
			tool.ajax( url, {id : id}, function( json ){
				tool.removeDisable( self );
				if( $.isArray( json ) && json[0] ){
					var data = json[0];
					if( module == 'tuji' ){
						_this.tuji_back(data);
						return;
					}
					var fontsize = _this.fontStorage.getItem();
					if( $.isArray( fontsize ) && fontsize[0] ){
						fontsize = fontsize[0]
					}else{
						fontsize = 'small';
					}
					data.fontsize = fontsize;
					if( data['is_have_video'] ){
						data['video_mp4'] = data['hostwork'] + '/' + data['video_path'] + data['video_filename'];
						data['poster'] = _this.createImgsrc( data['indexpic'], {width : _this.size.width - 40, height : 330} );
					}
					var render = template.compile( tool.tpl.detail_tpl ),
						html = render( data );
					var render_size = template.compile(tool.tpl.detail_size),
						html_size = render_size({dimension : _this.op.dimension, fontsize : fontsize});
					var page = _this.create.createContent( $.view.mainView, {
						title : '锡城新闻',
						className : 'newsDetail'
					});
					if( top.mainStrap ){
						top.mainStrap.switchNavStatus('back');
					}
					$( html ).appendTo( page );
					$( html_size ).insertBefore( page);
					_this.initDetail(page, 'detail_tpl');
					if( $.isArray(data.material) && data.material[0] ){
						_this.picDetail( data.material, page );
					}
				}
			});
		},
		
		picDetail : function( material, page ){
			var _this = this;
			var wid = this.size.width - 100,
				hei = this.size.height - 50;
			$.each(material, function(kk, vv){
				vv.url = _this.createImgsrc( vv['pic'] );
				page.find('.service').find('div[m2o_mark="pic_' + kk + '"]' ).replaceWith( '<div class="img-box"><img src ="' + vv.url + '" _key="'+ kk +'" _size="' + wid + 'x'+ hei +'"/></div>' );
			});
			this.newsDetailDark = $.myApp.photoBrowser({
		 		photos : material,
				theme:'dark'
			});
		},
		
		initLayout : function( data ){
			var _this = this;
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
	window.News = News; 
})(Zepto);
$(function(){
	var News = new window.News();
	News.init();
});