;(function($){
	var defaultOptions = {
		baseUrl : 'http://api.139mall.net:8081/data/cmc/',
		key : '?appkey=4fJrS03Ergz9KE1ztJ5vNmrnmZgt0moU&appid=20',
		interface_method : {					//接口配置方法
			indexpic : 'indexpic.php',
			column : 'news_recomend_column.php',
			indexlist : 'indexlist.php',
			newslist : 'news.php',
			detail : 'item.php',
			tujiDetail : 'tuji_detail.php'
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
		this.Storage = new Hg_localstorage( {key : 'fontsize'} );
		this.init();													//实例化构造函数时执行的初始操作
		this.template = {												//gmu的模版语法，$.parseTpl(tpl,data);来解析
			column : '<li data-id="<%= id%>"><a><%= name%></a></li>',
			column_default : '<li data-id="top"><a>头条</a></li>',
			refresh_wrap : '<div class="data-list-wrap">' + 
								'<div class="ui-refresh-up ui-refresh-btn hide" noevent="true"></div>' +
								'<ul id="thelist" class="data-list">' +
						        '</ul>' +
								'<div class="ui-refresh-down ui-refresh-btn" noevent="<%= noevent%>"></div>' +
							'</div>' +
							'',
			slider : '<div>' +
							'<a>' +
								'<img src="<%= src%>" data-id="<%= id%>" /> ' +
							'</a>' +
					'</div>' +
					'',
			list :  '<li data-id="<%= id%>" data-module="<%= module_id%>" class="list-item-li">' +
						'<div class="list-item <% if(module_id =="vod"){%>video<%}%>  m2o-flex m2o-flex-center">' +
							'<div class="info m2o-flex-one">' +
								'<p class="title"><%= title %></p>' +
							'</div>' +
							'<% if(module_id =="vod"){%><span class="flag">视频</span><%}%><% if(module_id =="tuji"){%><span class="flag">图集</span><%}%>' +
							'<span class="list-pic">' +
								'<img src="<%= src%>" /> ' +
							'</span>' +
						'</div>' +
					'</li>' +
					'',
			second_body : '<div class="second-body transition">' + 
								'<div class="data-content-wrap" id="<%= id%>">' + 
									'<div class="content-box"></div>' +
								'</div>' +
								'<div class="set-font-size">' + 
									'<span class="small" _attr="small" >小</span><span class="middle selected" _attr="middle" >中</span><span class="large" _attr="large" >大</span>' +
								'</div>' +
							'</div>' +
							'',
			news_detail : '<div class="content-box-word">' +
							'<h1><%= title%></h1>' +
							'<p><span class="publish-time"><%= publish_time_format%></span><span class="author"><% if( !+is_have_video){%><%= source%><%}%></span></p>' +
							'<p></p>' +
							'<% if(+is_have_video){%>' +
								'<div><video src="<%= video_url%>" poster="<%= poster%>" controls="controls" style="width:100%;height:330px;margin:15px auto 0;padding:0;"/></div>' +
								'<div class="video-brief <%= fontsize%>"><%= brief%></div>' +
							'<%}%>' +
							'<% if ( module_id == "news"){%>' +
								'<article class="<%= fontsize%>">' +
								'</article>' +
							'<%}%>' +
						'</div>' +
						'',
			detail_pic_item : '<div class="slide-item">' +
						    		'<img src="<%= src%>">' +
						    	'</div>',
			tuji_head : '<div class="tuji-head"><a class="back">返回</a></div>',
			tuji_wrap : '<div class="tuji-wrap m2o-flex m2o-flex-center">' +
							'<div class="tuji-slider-wrap">' +	
							'</div>' +
							'<div class="tuji-info">' +
								'<p class="title"><%= title%></p>' +
								'<a class="page-count"><span class="index">1</span>/<span class="totle"><%= totle%></span></a>' +
								'<p class="content"><%= brief%></p>' +
							'</div>' +
						'</div>',
			tuji_item : '<div class="tuji-item">' +
							'<img src="<%= src%>" style="width:<%= wid%>px"/>' +
						'</div>',
			style_bug : '.ui-refresh .ui-refresh-up, .ui-refresh .ui-refresh-down{background-color:transparent;}'+
						'',
			
		};
		/***
		 * 事件的绑定都在这里代理绑定，事件和具体的尽量业务逻辑分离开，把处理逻辑单独成方法，这样结构清晰也有利于调试
		 */
		this.el.on( 'click tap', '.subnav li', function(){								//栏目切换事件,根据栏目取对应的列表数据
			if( $(this).hasClass('selected') ) return;
			var id = $(this).data('id');
			$(this).addClass('selected').siblings().removeClass('selected');
			_this.showLoading();
			if( id == 'top' ){
				_this.slider.show();
				_this.listAjax( {
					method : 'indexlist',
					param : {offset : 0}
				}, null, false );
			}else{
				_this.slider.hide();
				_this.listAjax( {
					method : 'newslist',
					param : {
						column_id : id,
						offset : 0
					}
				}, null, false );
			}
		} );
		
		this.el.on('click tap touchstart touchend', '.ui-refresh-btn', function( event ){		//屏蔽加载更多的click事件
			if( $(this).attr('noevent') ){
				return false;
			}
			event.stopPropagation();
		});
		
		this.el.on( 'click', '.list-item-li', $.proxy(_this.list_item_event,_this)				//列表页切换到详情页
		);
		
		this.el.on( 'click', '.goFirstPage', function(event){									//详情页切换到列表页
			_this.backPage();
			event.stopPropagation();
			
		} );
		this.el.on( 'click', '.ui-bae-go-back', function( event ){								//退出应用事件，绑定在主页的back按钮上
			if( $(this).hasClass('goFirstPage') ) return;
			event.stopPropagation();
			Widget.close();
		} );
		this.el.on('tap','.pic-slide',function(){
			event.stopPropagation();
			$('.pic-slide').remove();
		});	
		this.el.on('click','.tuji-slider-wrap',function(event){
			$('.tuji-head').fadeToggle();
			$('.tuji-info').fadeToggle();
		});
		this.style_bug();
	};
	
	$.extend( News.prototype, {
		
		style_bug : function(){
			if (/ipad|iphone|mac/i.test(navigator.userAgent)){
				$('<style/>').html( this.template.style_bug ).appendTo( this.el );
			}
		},
		
		list_item_event : function( event ){
			var self = $(event.currentTarget),
				id = self.data('id'),
				module = self.data('module');
			this.goPage( id, module );
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
			this.slider = this.el.find('#slider');
			this.wrap = this.el.find('.data-list-wrap');
			this.list = this.wrap.find('.data-list');
			this.ajaxRefreshBtn = this.wrap.find('.ui-refresh-btn');
		},
		
		initColumn : function(){		//初始化栏目
			var _this = this,
				url = this.interface_tool( 'column' );
			this.ajax( url, null, function( json ){
				var html_str = _this.template.column_default,
					parseTpl_func = $.parseTpl( _this.template.column );	//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
				$.each( json, function(key,value){
					html_str += parseTpl_func( value );
				} );
				_this.column.find('ul').append( html_str );
				_this.column.css('display', '-webkit-box');
				var size = _this.countSize( _this.column, 4 );
				_this.instanceNavigator( _this.column, size);
				_this.initSlider();
				_this.listAjax( {
					method : 'indexlist',
					param : {offset : 0}
				}, null, false );
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
		
		instanceNavigator : function( columnEl, visibleCount ){				 //初始化栏目组件,第一个参数zepto对象，第二个参数栏目默认显示数
			columnEl.find('.nav-box').navigator( {
				visibleCount : visibleCount   //配置栏目默认显示数
			});
			columnEl.find('#arrow').on('click', function(){
				columnEl.find('.nav-box').iScroll('scrollTo', 120, 0, 400, true);
			});
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
					value.src = _this.createImgsrc( value['indexpic'] );
					if( value['module_id'] == 'news' || value['module_id'] =='vod' || value['module_id'] =='tuji' ){
						return value;
					}
				} );
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
		
		initListScroll : function(){								//初始化列表页scroll
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
                    	up_options.param.offset = 0;
                    	_this.listAjax(up_options);
                    }else{
	                    down_options.dir = dir;
                    	down_options.refreshWidget = me;
                    	down_options.ismore = true;
	                    down_options.param.offset += _this.options.count;		//加载更多，首先把offset加等到每页显示的条数加现在的offset
	                    _this.listAjax( down_options, function( obj ){
	                    		_this.refreshScroll( obj );					//_this.refreshWidget加载完列表后刷新refresh组件回调
	                    } );
                    }
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
			this.ajaxRefreshBtn.show().data('options',options);
			if( len < this.options.count ){
				this.ajaxRefreshBtn.eq(1).hide().data('options',null);
			}
		},
		
		initDetailScroll : function(){									 //初始化内容页滚动条
			var size = this.getSize(),
				head = $('.second-body').find('.ui-bae-header'),
				head_height = head.height();
				
			this.el.find('#content-wrapper').height( size['height'] - head_height ).refresh();
		},

		initSlider : function(){										//初始化首页轮转图
			var _this = this,
				url = this.interface_tool('indexpic'),
				html_str = '',
				parseTpl_func = $.parseTpl( this.template.slider );		//当某个template需要多次解析时，建议保存编译结果函数，然后调用此函数来得到结果
			this.ajax( url, null, function( json ){
				var itemWd = $(window).width();
				$.each( json, function( key, value ){
					value.src = _this.createImgsrc( value['indexpic'], {width :itemWd, height: 300 });
					html_str += parseTpl_func( value );
				} );
				_this.slider.append( html_str );
				_this.slider.slider({
					imgZoom : false,
					arrow : false
				});
			} );
		},
				
		interface_tool : function( name ){								//拼接接口工具函数
			var op = this.options,
				url = op.baseUrl + op.interface_method[name] + op.key;
			return url;
		},
		
		createImgsrc :function( data, options ){						//图片src创建
			var options = $.extend( {}, {width:80,height:50}, options ),
				data = data || {},
			src = [data.host, data.dir, options.width, 'x', options.height, '/', data.filepath, data.filename].join('');
			return src;
		},
		showLoading : function( type ){										//显示加载等待
			type = !type;
			this.loading = $.bae_progressbar({
				message:"<p>加载数据中...</p>",
				modal:false,
				canCancel : false
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
		
		detail : function( id ){										//取详情页
			var _this = this,
				url = this.interface_tool( 'detail' ),
				src_arr = [],
				number=0;
			this.ajax( url, {id :id}, function(json){
				var data = json[0],
					content_box = _this.el.find('.content-box'),
					content_box_wd = content_box.width();
				if( data['is_have_video'] ){
					data['video_url'] = data['video']['host'] + '/' + data['video']['filepath'] + data['video']['filename'];
					data['poster'] = _this.createImgsrc( data['indexpic'], {width : content_box_wd, height : 330} );
				}
				data.fontsize = _this.Storage.getItem('fontsize')[0];
				
				var	detail_tpl = $.parseTpl( _this.template.news_detail, data );
				content_box.append( detail_tpl );
				content_box.find('article').html( data['content'] );
				
				var fontsize = $('.second-body').find('.set-font-size').show()
				data.fontsize && fontsize.find('span.' + data.fontsize).addClass('selected').siblings().removeClass('selected');
				
				if( data['material'] ){
					var wid = $(window).width()-10;
					$.each( data['material'] , function( key ,value ){
						var src, picData;
						if( value['pic']['host'] && value['pic']['filename'] ){
							src = _this.createImgsrc( value['pic'], {width : 400, height : 200});
							picData = _this.createImgsrc( value['pic'], {width : wid, height : 1000});
						}else{
							var img = value['pic']['filename'];
							src = img;
							picData = img;
						}
						src_arr.push( src );
						content_box.find('div[m2o_mark="pic_' + key + '"]' ).replaceWith( '<p class="p-img"><img _key="'+ key +'" _data="'+ picData +'" src ="' + src + '" /></p>' );
					});					
				}				
				content_box.find('a').remove();
				_this.closeLoading();
				if( src_arr.length ){									
					$.each( src_arr , function( key,value ){
						var img = $('<img />').attr('src', value);
						img.on('load', function(){
							number ++ ;
							if( number == src_arr.length ){
								data.module_id == 'news' && _this.initDetailScroll();			//详情页如果有图片资源，要等到图片load完后在初始化详情页的scroll
							}
						});
					} );					
				}else{
					data.module_id == 'news' && _this.initDetailScroll();
				}
				_this.initNewsdetail();
			});
		},
		
		initNewsdetail : function(){
			var _this =  this;
			var body = $('.second-body');
			body.on( 'tap','img',function(event){	//详情页大图查看
				_this.detailPic( event );
				event.stopPropagation();
				return false;
			});
			body.on('click','.set-font-size span',function(event){
				_this.setFont( $(this) );
				event.stopPropagation();
			});
		},
		
		tujiDetail : function( id ){
			var tujiHead = $('.tuji-head');
			var _this = this,
				url = this.interface_tool( 'tujiDetail' );
			$('.tuji-head').click(function(){
				_this.backPage();
			});
			this.ajax(url,{id:id},function(json){
				var data = json[0];
				tujiHead.show();
				var content_box = _this.el.find('.content-box');
				content_box.height( $(window).height() );
				//tuji-wrapper
				var wrapData = {};
				wrapData['title'] = data['title'];
				wrapData['brief'] = data['brief'];
				wrapData['totle'] = data['tuji_pics'].length;
				var tuji_wrap_tpl = $.parseTpl( _this.template.tuji_wrap, wrapData );
				content_box.append( tuji_wrap_tpl );
				//tuji-inner
				var itemsData = data['tuji_pics'];
				var itemTpl_func = $.parseTpl( _this.template.tuji_item );
				var wid = $( window ).width(),
					hei = $( window ).height();
				var html_str = '';
				$.each(itemsData,function(k,v){
					var pic = v['pic'];
					v.src = pic['host'] + pic['dir'] + pic['filepath'] + pic['filename'];
					v.wid = pic['imgwidth'];
					// v.src = _this.createImgsrc( v['pic'], {width :wid});
					html_str += itemTpl_func(v);
				});
				content_box.find('.tuji-slider-wrap').append( html_str );
				//slide
				var slideOp = {
					autoPlay : false,
					dots : false,
					imgZoom : false,
					slideend : function(e,index){
						$('.tuji-wrap').find('.index').text( index+1 );
					}
				};
				$('.tuji-slider-wrap').slider( slideOp );
				$('.tuji-item').css({'line-height':hei+'px','height':hei+'px'});
				_this.closeLoading();
			});
		},
		detailPic : function( event ){
			var slideOption = { autoPlay : false , dots : false};
			slideOption.index = parseInt( $(event.currentTarget).attr('_key') ) || 0;
			$('<div class="pic-slide m2o-flex m2o-flex-center"></div>').appendTo('body');
			var pics = $('.content-box').find('img');
			var len = 0;
			var slideItems = '';
			var slideItemTpl = $.parseTpl( this.template.detail_pic_item );
			$.each(pics,function(k,v){
				var obj = {src: $(v).attr('_data') };
				slideItems += slideItemTpl( obj );
				len++;
			});
			$('.pic-slide').append( slideItems );
			var wid = $( window ).width();
			var wrap = $('.pic-slide');
			wrap.slider(slideOption);
			wrap.find('.ui-slider-group').addClass('m2o-flex m2o-flex-center');
		},
		
		setFont : function( self ){
			var _this = this;
			var body = self.closest('.second-body'),
				article = body.find('.content-box').find('article'),
				brief = body.find('.content-box').find('.video-brief');
			var attr = self.attr('_attr');
			self.addClass('selected').siblings().removeClass('selected');
			article.length && article.removeClass().addClass(attr);
			brief.length && brief.removeClass().addClass('video-brief').addClass(attr);
			this.Storage.resetItem('fontsize', [attr]);
			
			if( !brief.length ){
				var html = body.find('#content-wrapper').html();
				this.showLoading( true );
				body.find('#content-wrapper').parent().remove();
				body.append('<div class="data-content-wrap" id="content-wrapper">' + html + '</div>');
				setTimeout(function(){
					_this.closeLoading();
				}, 500);
				this.initDetailScroll();
			}
		},
		
		goPage : function( id, module ){									//主页切换到详情页
			var _this = this,
				size = this.getSize(),
				first_body = this.el.find('.first-body'),
				head = first_body.find('.ui-bae-header'),
				head_height = head.height(),
				head_clone = head.clone();
			head_clone.find('a').addClass('goFirstPage');
			first_body.css( {
				height : size['height'] + 'px',
				position:'absolute',
				left : '-' + size['width'] + 'px',
				'z-index' : 10
			} );
			var second_body = $( $.parseTpl( _this.template.second_body, {id : 'content-wrapper'} ) );
			if( module == 'tuji' ){
				second_body.prepend( this.template.tuji_head );
			}else{
				second_body.prepend( head_clone );
			}
			second_body.css( {
				height : size['height'] + 'px',
				position:'absolute',
				left :  size['width'] + 'px'
			} ).appendTo( 'body' ).css('left',0);
			setTimeout( function(){
				_this.showLoading();
				if( module == 'tuji' ){
					_this.tujiDetail( id );
				}else{
					//second_body.find('#content-wrapper').css( 'height', ( size['height']-head_height ) +'px'  );
					_this.detail( id );
				}
			}, 300 );
		},
		backPage : function(){										//回退到主页
			var _this = this,
				size = this.getSize(),
				first_body = this.el.find('.first-body'),
				second_body = this.el.find('.second-body');
			first_body.css({left: 0});
			second_body.css( {
				left : size['width'] + 'px'
			});
			setTimeout( function(){
				second_body.remove();
				first_body.removeAttr('style');
			}, 300 );
		}
	} );
	
	window.News = News;
	
})($);
	$(function(){
		var newObj = new News( $('body') );
	});