(function( $ ) {
	var defaultOptions = {
		dimension : {small : '小', middle : '中', large : '大'},
		count : 20
	}
	function Picture( options ){
		this.$views = $('.views');
		this.op = $.extend({}, options, defaultOptions);
	}
	$.extend( Picture.prototype, {
		init : function(){
			this.itool();
			this.loading();
			this.bindEvent();
		},
		
		loading : function(){
			this.pullRefresh();
			this.ajaxContent({
				offset : 0,
				count : this.op.count
			}, 'list');
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
				infinite : function(){
					area = news.find('.tabs');
					if( area.find('.news-list li').length < _this.op.count ){
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
					}, 'infinite' );
				}
			});
		},
		
		refreshList : function( info, type ){
			var param = {
				count : this.op.count
			}
			param.offset = info.offset;
			this.ajaxContent( param, type);
		},
		
		ajaxContent : function( param, type ){
			var _this = this, info = {},
				tool = _this.tool;
			var url = tool.interface_tool( 'tuji_list' );
			tool.ajax( url, param, function( data ){
				if( $.isArray( data ) && data.length && data[0]){
					var size_width = Math.floor((_this.size.width - 60) /3 );
					$.each(data, function(kk, vv){
						if( $.isArray( vv.childs_data ) && vv.childs_data[0] ){
							$.each(vv.childs_data, function(i, v){
								v.img  = _this.createImgsrc( v );
							});
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
				var area = _this.$views.find( '.tabs' );
				if( type == 'infinite' ){
					area = area.find('.news-list');
				}else{
					area.empty();
				}
				$( html ).appendTo( area );
				if( type == 'list' ){
					_this.initLayout();	//实例化framework
				}
				if( !data && type == 'infinite' ){
					area.data('offset', 'infinite');
				}
			} );
		},
		
		detail : function( event ){
			this.create.prevent( event );
			var _this = this,
				self = $(event.currentTarget),
				id = self.data('id'),
				module = self.data('module');
			if( module == 'tuji' ){
				this.tuji_detail(self, id);
			}
		},
		
		tuji_detail : function(self, id){
			var _this = this,
				tool = this.tool,
				url = tool.interface_tool( 'tujiDetail' );
			if( tool.hasDisable( self ) ){
				return;
			}
			tool.addDisable( self );
			tool.ajax( url, {id : id}, function( json ){
				tool.removeDisable( self );
				if( $.isArray( json ) && json[0] ){
					var data = json[0];
					_this.tuji_back( data );
				}
			});
		},
		
		tuji_back : function( data ){
			var _this = this;
			if( $.isArray(data.tuji_pics) && data.tuji_pics[0] ){
				var tujiPic = data.tuji_pics;
				$.each(tujiPic, function(ii, vv){
					vv.url = _this.createImgsrc( vv['pic'] );
					vv.caption = '<p class="title">' + data.title + '</p><p class="brief">' + vv.brief + '</p>'; 
				});
				var tujiDetailDark = $.myApp.photoBrowser({
			 		photos : tujiPic,
					type: 'page',
					captionsTheme : 'transparent',
					toolbar : tujiPic.length > 1 ? true : false,
					toolbarTemplate : '<div class="toolbar tabbar browserbar">' +
                                '<div class="toolbar-inner">' +
                                    '<a href="#" class="link photo-browser-prev"><i class="icon icon-prev-white"></i></a>' +
                                    '<a href="#" class="link photo-browser-next"><i class="icon icon-next-white"></i></a>' +
                                '</div>' +
                            '</div>'
				});
				tujiDetailDark.open();
				if( top.mainStrap ){
					top.mainStrap.switchNavStatus('back');
				}
			}
		},
		
		initDetail : function( page, type ){
			var _this = this;
			if( type == 'detail_tpl' ){
				page.on('click', '.img-box img', $.proxy(this.picBrowser, this));
				page.prev('.set-font-size').on('click', 'span', function(){
					var $this = $(this),
						attr = $this.attr('_attr');
					$this.addClass('selected').siblings().removeClass('selected');
					page.find('.service').removeClass().addClass( 'service ' + attr );
					page.find('.video-brief').removeClass().addClass( 'video-brief ' + attr );
				});
			}
		},
		
		picBrowser : function( event ){
			this.newsDetailDark.open();
			if( top.mainStrap ){
				top.mainStrap.switchNavStatus('close');
			}
		},
		
		initLayout : function(){
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
		
		showindicator : function(){
			$.myApp.showIndicator();
			this.tool.defer(500, function(){
				$.myApp.hideIndicator();
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
	window.Picture = Picture; 
})(Zepto);
$(function(){
	var Picture = new window.Picture();
	Picture.init();
});