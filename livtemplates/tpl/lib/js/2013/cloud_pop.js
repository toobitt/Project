;(function($){
    $.widget('cloud_pop.base', {
        _template : function( tname, tpl, dataInfo, container, data ){
        	$.template( tname, tpl );
        	$.tmpl( tname, data ).appendTo( container );
        	if( !dataInfo.cssInited && dataInfo.css ){
        		dataInfo.cssInited = true;
        		this._addCss( dataInfo.css );
        	}
        },
        
        _addCss : function(css){
            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
        }
    });
	
	var pluginInfo = {
			template : '' + 
			'<div id="pop-box" class="pop-area">' + 
				'<div class="pop-head">' + 
					'<div class="pop-title"></div>' + 
					'<div class="pop-site"></div>' + 
					'<div class="pop-close"></div>' + 
				'</div>' +
				'<div class="pop-search"><span class="custom-btn"></span></div>' + 
				'<div class="pop-body"></div>' + 
			'</div>' +
			'',
			css : '' +
			'.pop-area{position:absolute;top:50%;left:50%;margin-left:-430px;margin-top:-300px;width:860px;height:600px;padding:10px;background:#6ba4eb;z-index:10010;}' +
			'.pop-head{height:38px;padding:5px 0;font-size:24px;color:#fff;font-size:24px;}' +
			'.pop-head .pop-title{float:left;}' + 
			'.pop-head .pop-close{display:block;float:right;width:26px;height:26px;border-radius:2px;background:url(' + RESOURCE_URL+'datasource/close4.png) no-repeat center,-webkit-linear-gradient(#f3f3f3,#dedede);background:url(' + RESOURCE_URL+ 'datasource/close4.png) no-repeat center,-moz-linear-gradient(#f3f3f3,#dedede);cursor:pointer;}' + 
			'.pop-search{height:43px;border:1px solid #ccc;border-left:none;border-right:none;background:url('+  RESOURCE_URL + 'datasource/nav-bg.png) repeat-x;position:relative;}' +
			'.pop-body{height:500px;background:#fff;}' + 
			'.m2o-flex{display:-webkit-box;display:-moz-box;display:box;width:100%;}' + 
			'.m2o-flex-center{-webkit-box-align:center;-moz-box-align:center;box-align:center;}' +
			'.m2o-flex-one{-webkit-box-flex:1;-moz-box-flex:1;box-flex:1;}' +
			'',
			cssInited : false
	};
    $.widget('cloud_pop.cloud_pop', $.cloud_pop.base, {
        options : {
        	pop_drag : true,
        	css : '' //弹窗位置样式
        },
        _create : function(){
        	var root = this.element;
        	root.addClass( this.options.className );
        	this._template( 'template', pluginInfo.template, pluginInfo, this.element );
        	this.search_box = root.find( '.pop-search' );
        	this.body = root.find( '.pop-body' );
        	this.title = root.find( '.pop-title' );
        	this.options.pop_drag && this._setPopDrag();
        },
        _init : function(){
        	this._on( {
        		'click .pop-close' : '_close'
        	} );
        	this.show( this.options.css );
        	this._initTitle( this.options.title || '添加内容' );
        },
        _setPopDrag : function(){
        	var box = this.element.find('.pop-area');
        	box.draggable().css( 'cursor', 'move' );
        },
        _initTitle : function( title ){
        	this.title.html( title );
        },
        _close : function(){
        	this.hide();
        },
        show : function(option ){
        	this.element.show();
        	option && this.element.find('.pop-area').css( option )
        	this._createMask();
        },
        hide : function(){
        	this.element.hide();
        	this._clearMask();
        },
        refresh : function(option){
        	$.extend( this.options,option );
        	this.show(this.options.css)
        },
        _createMask : function(){
        	if( this.mask ) return;
        	var height = $('body').outerHeight(true);
        	this.mask = $('<div/>').css( {
        		position:'absolute',
        		width : '100%',
        		height : height + 'px',
        		background : 'black',
        		opacity : 0.1,
        		'z-index' : 10001
        	} ).prependTo( 'body' );
        },
        _clearMask : function(){
        	if( this.mask ){
        		this.mask.remove();
        		this.mask = null;
        	}
        }
    });
    
    

})($);

;(function($){
    var dataInfo = {
		list_box : '' + 
				'<div class="publish-nav-box"></div>' +
				'<div class="publish-list-area">' + 
					'<div class="publish-list-head m2o-flex m2o-flex-center">' + 
						'<div class="publish-chk"></div>' + 
						'<div class="publish-title m2o-flex-one">标题</div>' + 
						'<div class="publish-column">分类</div>' + 
						'<div class="publish-weight">权重</div>' + 
						'<div class="publish-type">状态</div>' + 
						'<div class="publish-person">添加人/时间</div>' + 
						'<div class="publish-controll">操作</div>' + 
					'</div>' + 
					'<div class="publish-list-con"></div>' + 
					'<div class="publish-list-bottom">' + 
						'<div class="left">' + 
							'<input type="checkbox" />' + 
							'<span class="batch-add-btn">添加</span>' + 
						'</div>' + 
						'<div class="page-area"></div>' +
					'</div>' + 
				'</div>' + 
				'',
		list_each : '' + 
				'{{if list}}' + 
				'{{each list}}' + 
					 '<div class="publish-list-data m2o-flex m2o-flex-center {{if $value.is_local}}is-add{{/if}}" {{if $value.is_local}}title="数据已添加"{{/if}} data-id="${$value.id}">' +
						'<div class="publish-chk"><input type="checkbox" /></div>' + 
						'<div class="publish-title m2o-flex-one">' +
							'<div class="biaoti-transition">' + 
								'<div class="max-200 overflow">' + 
									'{{if $value.url}}<img src="${$value.url}" class="title-img" />{{/if}}' + 
									'<span title="${$value.title}">${$value.title}</span>' + 
								'</div>' + 
							'</div>' + 
						'</div>' +
						'<div class="publish-column">${$value.sort_name}</div>' + 
						'<div class="publish-weight">' +
							'<div class="publish-weight-box">' + 
								'<div class="weight-inner" _weight="${$value.weight}">' + 
									'<div class="weight-label">${$value.weight}</div>' + 
								'</div>' + 
							'</div>' + 
						'</div>' + 
						'<div class="publish-type">${$value.audit}</div>' + 
						'<div class="publish-person">' +
							'<div>${$value.user_name}</div>' + 
							'<span class="time">${$value.create_time_show}</span>' + 
						'</div>' + 
						'<div class="publish-controll">' + 
							'<span class="publish-add-btn" title="添加"></span>' + 
						'</div>' + 
					'</div>' + 
				'{{/each}}' + 
				'{{else}}' + 
					'<div class="publish-empty">暂无内容</div>' + 
				'{{/if}}' + 
				'',
		form_tpl : '' + 
				'<div class="custom-form-area">' +
					'<form method="post" id="custom-form" enctype="multipart/form-data">' + 
						'<ul>' + 
							'<li>' + 
								'<span class="title">标题</span>' + 
								'<input name="title" value="${title}" />' + 
							'</li>' + 
							'<li>' + 
								'<span class="title">描述</span>' + 
								'<textarea name="brief">${brief}</textarea>' + 
							'</li>' +
							'<li>' + 
								'<span class="title">链接</span>' + 
								'<input name="outlink" value="${outlink}" />' + 
							'</li>' + 
							'<li>' + 
								'<div class="index-pic {{if url}}has-pic{{/if}}">' +
									'{{if url}}' + 
										'<img src="${url}" />' + 
									'{{/if}}' + 
								'</div>' + 
							'</li>' + 
						'</ul>' + 
						'<input type="file" style="display:none;" class="upload-pic-file" />' +
						'<input type="hidden" name="a" value="${a}" />' + 
					'</form>' +
				'</div>' + 
				'',
				search_tpl : '' + 
				'<form action="" method="post">' +
					'<div class="search-select-area">' +
						'<div class="search-select-item" id="site-site"></div>' + 
						'<div class="search-select-item" id="site-box"></div>' + 
					'</div>' +
					'<div class="search-weight-area"></div>' +
					'<input type="text" name="key" class="search-k" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索">' +
					'<input type="submit" class="search-button" value="" />' +
				'</form>' +
				'',
		select_tpl : '' + 
				'<div class="search-select-item">' +
					'<div class="current-select">${config_data[default_value]}</div>' +
					'{{if config_data}}' + 
					'<ul class="select-list">' +
						'{{each config_data}}' +
						'<li data-id="${$index}" class="select-each-item" data-type="${type}" title="${$value}">${$value}</li>' + 
						'{{/each}}' + 
					'</ul>' + 
					'{{/if}}' + 
					'<input type="hidden" name="${name}" value="${default_value}" />' + 
				'</div>' +
				'{{if +type==1}}' + 
				'<div class="define-time-box">' +
					'<div class="search-select-item">' + 
						'<input type="text" class="time-datepicker" name="start_time" />' +
					'</div>' + 
					'<div class="search-select-item">' + 
						'<input type="text" class="time-datepicker" name="end_time" />' +
					'</div>' + 
				'</div>' +
				'{{/if}}' + 
				'',
		weight_tpl : '' + 
				'<div class="search-select-item" _target="weight-box-outer">' +
					'<div class="current-weight">所有权重</div>' +
					'<div class="weight-box-outer">' +
						'{{if config_weight}}' + 
						'<ul class="weight-list">' + 
							'{{each config_weight}}' + 
							'<li data-weight="${$value.begin_w},${$value.end_w}">' + 
								'<span class="weight-num">>=${$value.begin_w}</span>' + 
								'<a class="weight-descr">${$value.title}</a>' + 
							'</li>' + 
							'{{/each}}' + 
						'</ul>' + 
						'{{/if}}' +
						'<div class="define-weight">' +
							'<div>' + 
								'<span>权重范围：</span>' + 
								'<span>' + 
									'<input class="weight-begin-num" value="0" /> -' + 
									'<input class="weight-end-num" value="100" />' +
								'</span>' +
								'<input type="submit" value="确定" class="weight-btn" />' +
							'</div>' +
							'<div class="slider-weight-box">' + 
								'<span class="start">0</span>' + 
								'<div class="weight-slider"></div>' + 
								'<span class="end">100</span>' + 
							'</div>' +
						'</div>' +
						'<input type="hidden" name="${start_weight}" class="start-weight"  value="0" />' + 
						'<input type="hidden" name="${end_weight}" class="end-weight"  value="100" />' + 
					'</div>' + 
				'</div>' +
				'',
		user_tpl : '' +
				'<div class="search-select-item">' +
					'<div class="current-select"><input name="${name}" class="autocomplete" placeholder="${placeholder}" /></div>' +
				'</div>' +
				'',
		css : '' + 
			'.publish-nav-box{max-height:470px;min-height:470px;overflow-y:auto;width:190px;float:left;border-right:1px solid #dbdee3;background-color:#f0f1f3;padding-bottom:30px;}' +
			'.publish-list-area{overflow:hidden;}' +
			'.publish-list-head,.publish-list-data{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;width:100%;position:relative;border-bottom:1px solid #c8d4e0;min-height:41px;}' + 
			'.publish-list-head{background:#f9f9f9;padding:0 20px;color:#939393;}' + 
			'.publish-list-con{margin:0 10px;max-height:410px;overflow-y:auto;}' +
			'.publish-list-bottom{padding:6px 20px 0;}' +
			'.publish-list-data{padding:0 10px;color:#8fa8c6;cursor:pointer;}' +
			'.publish-list-data.is-add{background:rgba(255,255,200,0.1);1cursor:default;}' +
			'.publish-list-data.current,.publish-list-data:hover{background:#EEEFF1;}' +
			'.publish-list-data:active{background:#ddeefe;}' +
			'.max-200{max-width:200px;}' +
			'.publish-list-data .overflow{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;vertical-align:middle;}' +
			'.publish-list-data .publish-title{font-size:14px;color:#282828;position:relative;z-index:100000;}' +
			'.publish-list-data .time{color:#888;padding-top:4px;display:inline-block;}' +
			'.publish-list-data .title-img{vertical-align:middle;padding-right:10px;width:40px;height:30px;}' + 
			'.publish-list-data .publish-controll{height:40px;}' +
			'.biaoti-transition{display:inline-block;-moz-transition:all 0.15s ease-in 0s;-webkit-transition:all 0.15s ease-in 0s;-o-transition:all 0.15s ease-in 0s;-ms-transition:all 0.15s ease-in 0s;transition:all 0.15s ease-in 0s;}' + 
			'.biaoti-transition:hover{padding-left:8px;}' + 
			'.publish-add-btn{display:none;width:40px;height:40px;vertical-align:middle;cursor:pointer;background:url(' + RESOURCE_URL+'datasource/addlist.png) no-repeat left center;}' + 
			'.publish-controll:hover .publish-add-btn,.publish-list-data:hover .publish-add-btn{display:block;}' + 
			'.publish-list-area .publish-chk{width:25px;}' + 
			'.publish-list-area .publish-chk input{1display:none;}' + 
			'.publish-list-area .publish-chk input:focus{outline:none;box-shadow:none;}' + 
			'.publish-list-area .publish-weight{width:50px;}' + 
			'.publish-list-area .publish-weight-box{position:relative; width:40px;}' + 
			'.publish-list-area .weight-inner{-webkit-text-size-adjust: none; border-radius: 17px; font-size:10px; color:#000; position:relative; text-align:center; width:24px; height:24px; line-height: 24px; z-index: 1; cursor: default;}' + 
			'.publish-list-area .weight-label{display: inline-block; width: 23px; height: 23px; line-height: 23px;color: #666; border-radius: 12.5px; background: #fff; opacity: 0.9;}' + 
			'.publish-list-area .publish-type{width:70px;}' + 
			'.publish-list-area .publish-controll{width:40px;}' + 
			'.publish-list-area .publish-column{width:80px;}' + 
			'.publish-list-area .publish-person{width:150px;}' + 
			'.publish-list-bottom .left{float:left;1display:none;}' +
			'.publish-list-bottom input{vertical-align:middle;1display:none;}' +
			'.publish-list-bottom input:focus{vertical-align:middle;box-shadow:none;}' +
			'.publish-list-bottom .batch-add-btn{padding-left:20px;vertical-align:middle;cursor:pointer;}' + 
			'.publish-empty{padding:10px;font-size:14px;}' +
			'.page-area{float:right;padding:4px 0px 0 0;margin-top:-14px;}' +
			'.page-area .hoge_page .page_next a{width:auto;}' +
			'.page-area .hoge_page .page_all{margin:0;}' +
			'.page-area span{margin:0 4px;}' +
			'.page-area span a{display:inline-block;height:22px;line-height:22px;padding:0 6px;border:1px solid #ccc;cursor:pointer;}' +
			'.page-area span a:hover{background:#498adb;color:#fff;}' +
			'.page-area span.page_cur{display:inline-block;background:#d8e8f5;color:#666;height:22px;line-height:22px;padding:0 8px;border:1px solid #ccc;}' +
			'.page-area span.page-prev a,.page_area span.page-next a{padding:0 6px;}' +
			'.page-area .hoge_page .page_bur,.page-area .hoge_page .page_cur{width:auto;}' +
			'.index-pic{width:100px;height:100px;background:red;}' + 
			'.pop-search{position:relative;z-index:1000000;}' +
			'.pop-search .search-select-area,.pop-search .search-weight-area{float:left;background:#fff;}' +
			'.pop-search .search-select-item{cursor:pointer;float:left;height:43px;border-right:1px solid #ccc;width:100px;text-align:center;background:#fff;}' + 
			'.search-select-item .current-select{height:43px;line-height:43px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}' +
			'.search-select-item ul{background:#fff;width:102px;display:none;margin-left:-1px;max-height:397px;overflow-y:auto;}' + 
			'.search-select-item li{line-height:32px;border:1px solid #ccc;border-bottom:0;height:32px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}' +
			'.search-select-item li:hover{background:#ddeefe;}' +
			'.search-select-item li:last-child{border-bottom:1px solid #ccc;}' +
			'.search-select-item .autocomplete{width:100%;height:100%;padding:0;border:0;background:transparent;line-height:1.5;text-align:center;}' +
			'.define-time-box{display:none;float:left;height:43px;}' +
			'.define-time-box input{width:98px;border:none;height:23px;line-height:22px;text-align:center;padding:10px 0;}' +
			'.define-time-box input:focus{outline:none;box-shadow:none;}' +
			'.pop-search .search-button{top:0;border:0;cursor:pointer;width:40px;height:43px;float:right;margin-right:30px;border-left:1px solid #ccc;border-right:1px solid #ccc;background:url(' + RESOURCE_URL+'datasource/search.png) no-repeat center #fff;}' +
			'.pop-search .search-button:hover{border:0;border-left:1px solid #ccc;border-right:1px solid #ccc;}'+ 
			'.search-k{position:absolute;top:9px;line-height:20px;right:100px;}' +
			'.search-weight-area .current-weight{line-height:43px;}' +
			'.search-weight-area .weight-box-outer{position:absolute;background:#fff;display:none;border:1px solid #ccc;margin-left:-1px;width:260px;padding:0 10px 15px 6px;}' +
			'.search-weight-area .weight-box-outer ul{position:relative;width:252px;padding:0 0 8px 0;top:8px;left:6px;display:block;}' +
			'.search-weight-area li{cursor:pointer;height:36px;width:114px;border:1px solid #d8d8d8;border-radius:2px;background:#f5f5f5;float:left;margin:3px 5px;text-align:left;}' +
			'.search-weight-area li:hover{background:#ddeefe;}' +
			'.search-weight-area .weight-num{height:24px;width:24px;line-height:16px;display:block;float:left;padding:10px 5px;text-align:center;}' +
			'.search-weight-area .weight-descr{color:#868686;font-size:12px;display:inline;line-height:36px;}' +
			'.search-weight-area .define-weight{clear:both;margin:10px 0 0 10px;border-top:1px dotted #ccc;padding-top:8px;}' +
			'.search-weight-area .define-weight input{width:34px;height:22px;text-align:center;padding:0;outline:none;}' +
			'.search-weight-area .define-weight .weight-begin-num{margin-right:6px;}' +
			'.search-weight-area .define-weight .weight-end-num{margin-left:6px;}' +
			'.search-weight-area .define-weight .weight-btn{padding:0;width:65px;height:24px;border:1px solid #adadad;border-radius:2px;margin-left:15px;background:#e2e3e5;}' +
			'.search-weight-area .slider-weight-box{padding-top:10px;padding-left:10px;}' +
			'.search-weight-area .weight-slider{float:left;width:165px;height:6px;margin:2px 15px 0 12px;border-radius:2px;background:#6d6d6d;border:0;}' +
			'.slider-weight-box .start,.slider-weight-box .end{float:left;}' +
			'.search-weight-area .ui-slider-horizontal .ui-slider-range{background:#6d6d6d;}' +
			'.search-weight-area .ui-widget-content .ui-state-default, .search-weight-area .ui-widget-content .ui-state-hover,.search-weight-area .ui-widget-content .ui-state-focus,.search-weight-area .ui-widget-content .ui-state-default{top:-3px!important;width:12px!important;height:12px!important;background:-webkit-linear-gradient(#d0cfcf,#9d9d9d)!important;background:-moz-linear-gradient(#d0cfcf,#9d9d9d)!important;border-radius:50%!important;border:0;outline:none;}' +
			'',
		cssInited : false
    };
    
    $.widget('cloud_pop.pubLib', $.cloud_pop.cloud_pop, {
    	options : {
    		title : '',
    		cloud_url : './cloud.php?mid=' + gMid + '&a=show',
    		className : 'pubLib-pop-box',
    		list_url : './cloud.php?mid=' + gMid + '&a=show', 	//取发布库数据列表接口
    		column_url : './cloud.php?mid=' + gMid + '&a=childs', //取栏目数据接口
    		clickCall : $.noop,
    		current : 'current',
    		drag : false,	//默认拖动操作关闭click进行添加
    		handlerName : '',
    		need_define : false, // 是否需要自定义添加
    		site_url : './cloud.php?mid=' + gMid + '&a=getSite',
    		hasEvent : true
    	},
    	
    	_create : function(){
    		this._super();
    		this._template( 'list_box', dataInfo.list_box, dataInfo, this.body );
    		this.list_box = this.body.find( '.publish-list-con' );
    		this.nav_box = this.body.find( '.publish-nav-box' );
    		this.page_box = this.body.find( '.page-area' );
    	},
    	
    	createMask : function(){
    		var body = this.element.find('.pop-area'),
    			height = body.outerHeight(true);
    		this.bodymask = $('<div/>').css({
    			position : 'absolute',
    			width : '100%',
    			top : 0,
    			left : 0,
    			'z-index' : 10000000000,
    			height : height + 'px',
    			background:'#000',
    			opacity : '0.1'
    		}).prependTo( body );
    	},
    	
    	clearMask : function(){
    		this.bodymask.remove();
    	},
    	
    	_init : function(){
    		this._super();
    		this._initPublish();
    		this._on( {
    			'click .publish-list-data' : '_click',
    			'click .publish-list-data input[type="checkbox"]' : '_select',
    			'click .publish-list-bottom input[type="checkbox"]' : '_batchSelect',
    			'click .publish-add-btn' : '_add',
    			'click .batch-add-btn' : '_batchAdd',
    			'click .custom-btn' : '_customForm'
    		} );
    		this._initSearch();
    		this._initNav();
    	},
    	
    	_bindDrag : function(){
    		var _this = this,
    			handlers = this.list_box.find( '.publish-add-btn' );
    		handlers.each( function(){
				//var item = $(this).closest('.publish-list-data');
				$(this).off('click');
    			$(this).draggable( {
                    connectToSortable : _this.options.connectToSortable,
    				helper : function( event ){
                        var item = $(this).closest('.publish-list-data');
    					var	target = item.find( '.publish-title' ).clone();
                        target.css({'background' : '#fff', 'display' : 'inline-block', 'height' : '30px', 'line-height' : '30px', 'width' : 'auto !important'}).find('img').remove();
                        return target[0];
    				},
                    start : function(event, ui){
                        var item = $(this).closest('.publish-list-data');
                        ui.helper.attr('info', encodeURIComponent(JSON.stringify(_this.globalInfo[item.data('id')])));
                        ui.helper.css('width', ui.helper.outerWidth() + 'px');
                    },
    				stop : function( event, ui ){
                        return;
                        var item = $(this).closest('.publish-list-data');
    					var id = item.data('id').toString();
    					_this._clickCall( id );
    				}
    			} );
    		} );
    	},
    	
    	_initPublish : function(){
    		var _this = this;
    		$.getJSON( this.options.cloud_url, function( data ){
    			var list_data = {},
    				search_data = {};
    			search_data['cloud'] = data['cloud'];
    			search_data['search'] = data['search'];
    			list_data['info'] = data['list'];
    			list_data['page_info'] = data['page'];
    			_this._drawHtml( list_data );
    		} );
    	},
    	
    	_initPage : function( option ){
    		var _this = this,
    			pagebox = this.page_box;
    		if( pagebox.data('init') ){
    			pagebox.page('refresh',option);
    			return;
    		}
    		option['page'] = function( event, page, page_num ){
    			_this.refresh({
    				page : page,
    				count : page_num
    			});
    		};
    		$.getScript( SCRIPT_URL + 'page/page.js', function(){
    			pagebox.page( option );
    			pagebox.data( 'init', true );
    		} );
    	},
    	
    	_initWeightColor : function(){
    		var weight_items = this.list_box.find( '.weight-inner' );
    		weight_items.each( function(){
    			var weight = $(this).attr( '_weight' ),
    				rgb_color = create_color_for_weight( weight );
    			$(this).css( 'background', rgb_color );
    		} );
    	},
    	
    	_initHandler : function(){
    		var handlers = this.list_box.find( '.publish-add-btn' );
    		handlers.css( { 'display' : 'table-cell', 'background' : 'none' } ).html( this.options.handlerName );
    	},
    	
    	_setConfig : function(){
    		this._initWeightColor();
			this.options.drag && this._bindDrag();
			this.options.handlerName && this._initHandler();
    	},
    	
    	_getData : function( param ){
    		var _this = this;
				url = this.options.list_url,
				param = param || '';
			$('<img src=" ' + RESOURCE_URL + 'loading2.gif" width="64" height="64" style="position:absolute;top:50%;left:50%;margin:-32px 0 0 -32px;"  />').appendTo( this.list_box );
    		$.post( url, param, function( data ){ 
    			var json = {};
    			json['info'] = data['list'];
    			json['page_info'] = data['page'];
    			_this._drawHtml( json );
    		}, 'json' );
    	},
    	
    	_drawHtml : function( data ){
    		var _this = this;
			this.globalInfo = this.globalInfo || {};
			var tmpl_data = {};
			if( $.isArray( data['info'] ) && !data['info'].length  ){
				tmpl_data.list = null;
			}else{
				tmpl_data.list = $.map( data['info'], function( value, key ){
					if( !$.isArray( value['pic'] ) && value['pic'] ){
	    				value['url'] = $.globalImgUrl( value['pic'], '40x30' );
					}
					_this.globalInfo[ value['id'] ] = value;
					return value;
				} );
			}
			this._clear();
			this._template( 'list_each', dataInfo.list_each, dataInfo ,_this.list_box, tmpl_data );
			this._initPage( data['page_info'] );
			this._setConfig();
    	},
    	
    	refresh : function( option ){
    		var options = {},
    			search_info = this.search_box.search('getFormParam'),
    			column_id = 0;
    			console.log(option);
    		if( option.cloud_id ){
				this.nav_box.nav('refresh',option.cloud_id);
			}else{
				column_id = this.nav_box.nav('getColumnid');
			}
			options['info'] = search_info;
			options['sort_id'] = column_id;
			$.extend( options, option );
			this.show();
			this._getData( options );
			
    	},
    	
    	_clear : function(){
    		this.list_box.html( '' );
    	//	this.page_box.html( '' );
    	},
    	
    	_click : function( event ){
    		var _this = this,
    			self = $( event.currentTarget );
    			checkbox = self.find( 'input[type="checkbox"]' ),
    			ischeck = checkbox.prop( 'checked' ),
    			item = self.closest('.publish-list-data');
    		checkbox.prop( 'checked', !ischeck );
			this._toggle( !ischeck, self );
    		//if( self.closest('.publish-list-data').hasClass('is-add') ) return;
    	},
    	
    	_select : function( event ){
    		var self = $( event.currentTarget ),
    			ischeck = self.prop('checked'),
    			item = self.closest( '.publish-list-data' );
    		this._toggle( ischeck, item );
    		event.stopPropagation();
    	},
    	
    	_batchSelect : function( event ){
    		var _this = this,
    			self = $( event.currentTarget ),
    			ischeck = self.prop( 'checked' ),
    			list_box = self.closest( '.publish-list-area' );
    		list_box.find( '.publish-list-data' ).each( function(){
    			$(this).find( 'input[type="checkbox"]' ).prop( 'checked', ischeck );
    			_this._toggle( ischeck, $(this) );
    		} );
    	},
    	
    	_toggle : function( ischeck, item ){
    		var current = this.options.current;
    		item[ ( ischeck ? 'add' : 'remove' ) + 'Class' ]( current );
    	},
    	
    	_clickCall : function( ids, container ){
    		if( this.options.hasEvent ){
    			this._openBox( ids, container );
    		}else{
        		var _this = this,
	    			info = [],
	    			ids = ids.split(',');
	    		$.each( ids, function( key, value ){
	    			info.push( _this.globalInfo[ value ]  );
	    		} );
	    		this._trigger( 'clickCall', null, [ info, this ] );
    		}
    	},
    	
    	_openBox : function( ids, container ){
    		var _this = this;
    		var offset = container.offset();
    		$.cloud_pop( {
    			className : 'unit-box-pop',
    			widget : 'addBox',
    			css : {
    				position : 'absolute',
    				right : '50px',
    				top : offset.top + 'px',
    				left : offset.left + 630 + 'px',
    				'z-index' : 100005,
    				width : '191px'
    			},
				list_widget : _this.element
    		} );
    	},
    	
    	_add : function( event ){
    		var _this = this,
    			self = $( event.currentTarget ),
    			container = self.closest('.publish-list-data'),
    			container_list = this.element.find('.publish-list-data');
    			id = self.closest('.publish-list-data').data( 'id' ).toString();
    		container_list.removeClass( 'current' );
    		container_list.find('input[type="checkbox"]').prop('checked',false);
    		container.addClass( 'current' ).find('input[type="checkbox"]').prop('checked',true);
    		if( container.hasClass('is-add') ){
    			jConfirm( '数据已添加过，是否重复添加?', '添加提醒', function( result ){
    				if( result ){
			    		_this._clickCall( id, container );
    				}
    			} );
    		}else{
    			this._clickCall( id, container );
    		}
    		event.stopPropagation();
    	},
    	
    	_batchAdd : function( event ){
    		var _this = this,
    			container = $(event.currentTarget).closest('.publish-list-bottom'),
    			items = this.element.find( '.publish-list-data' ).filter( function(){
    			return $(this).hasClass( _this.options.current );
    		} );
    		var	ids = items.map( function(){
    			return $(this).data( 'id' );
    		} ).get().join(',');
    		if( !ids ){
    			jAlert('请选择要添加的记录','添加提醒');
    		}else{
        		this._clickCall( ids, container );
    		}
    	},
    	
    	_customForm : function(){
    		var _this = this,
    			op = _this.option;
    		$.cloud_pop( {
    			widget : 'custom',
    			is_super : op['is_super'],
    			refresh : op['getData'],
    			submitCall : op['submitCall']
    		} );
    	},
    	
    	_initUnit : function( unit, widget, option ){
			if( unit.data( 'init' ) ){
				unit[widget]( 'refresh' );
				return;
			}
			unit[widget]( option );
    		unit.data( 'init', true );
    	},
    	
    	_initSearch : function( data ){
    		var op = this.options;
    		this._initUnit( this.search_box, 'search', {
    			config_search : op['config_search'],
				need_define : op['need_define'],
				module_url : op['module_url'],		// 取类型数据接口
    			site_url : op['site_url'],		
    			cloud_url : op['cloud_url'],
    			className : op['className'],
    			data : data
    		} );
    	},
    	
    	_initNav : function( node ){
    		var	op = this.options;
    		this._initUnit( this.nav_box, 'nav', {
    			url : op['column_url'],
    			className : op['className'],
    			aaa:1,
    			node : node
    		} );
    	}
    });
    
    $.widget('cloud_pop.custom', $.cloud_pop.cloud_pop, {
    	options : {
    		title : '编辑内容',
    		className : 'custom-pop-box',
    		editUrl : '',
    		uploadUrl : '',			//自定义form索引图接口
    		phpkey : 'Filedata', 	//文件name
    		is_super : true,
    	},
    	
    	_create : function(){
    		this.options['is_super'] && this._super(); 
    	},
    	
    	_init : function(){
    		this.options['is_super'] && this._super();
    		this._on( {
    			'click .index-pic' : '_upload',
    			'submit #custom-form' : '_submit'
    		} );
    	},
    	
        _template : function( tname, tpl,dataInfo, container, data ){
        	$.template( tname, tpl );
        	$.tmpl( tname, data ).appendTo( container );
        	if( !dataInfo.cssInited && dataInfo.css ){
        		dataInfo.cssInited = true;
        		this._addCss( dataInfo.css );
        	}
        },
    	
    	_initFile : function(){
    		var _this = this,
    			op = this.options;
        	this.input_file = this.element.find( 'input[type="file"]' );
    		this.input_file.length && this.input_file.ajaxUpload( {
    			url : op['uploadUrl'],
    			phpkey : op['phpkey'],
    			before : function( json ){
    				_this._uploadBefore( json['data']['result'] );
    			},
    			after : function( json ){
    				_this._uploadAfter( json );
    			}
    		} );
    	},
    	
    	_upload : function(){
    		this.input_file.click();
    	},
    	
    	_uploadBefore : function( src ){
    		
    	},
    	
    	_uploadAfter : function( json ){
    		var data = json['data'];
    	},
    	
    	_submit : function( event ){
    		this._trigger( 'submitCall', event, this );
    	},
    	
    	getData : function( option ){
    		var box = this.element.find( '.pop-body' );
    		this.element.find( '.custom-form-area' ).remove();
    		this._template( 'form_tpl', dataInfo.form_tpl,dataInfo, box, option );
    		this._initFile();
    	},
    	
    	refresh : function( option ){
    		this.show();
    		this.getData( option );
    	}
    	
    } );
    
    $.widget('cloud_pop.search', $.cloud_pop.base, {
    	options : {
    		config_search : { 
				select_data : [ {name: 'status_search',default_value : 1,config_data:{1:'全部状态',2:'待审核',3:'已审核',4 : '已打回'} , type : 0 },
				                {name: 'date_search',default_value : 1, config_data: {1: "所有时间段", 2: "昨天", 3: "今天", 4: "最近3天", 5: "最近7天", other: "自定义时间"} , type : 1 }
							  ],
				weight_data : { start_weight: 'start_weight', end_weight: 'end_weight', config_weight: [ {begin_w:90, end_w: 100, title: '首页头条'}, {begin_w:80, end_w: 90, title: '二级首页头条'},{begin_w:70, end_w: 80, title: '首页显示'},{begin_w:60, end_w: 70, title: '二级首页'}] },
				user_data : [{name : 'user_name', placeholder : '添加人'}]
			},
			cloud_data : [ {name: 'cloud_id',config_data: {} , type : 'cloud' }
			],
			site_data : [ {name: 'site_id',config_data: {} , type : 'site' }
			],
			site_id:'',
    		need_define : false
    	},
    	
    	_create : function(){
    		this._template( 'search_tpl', dataInfo.search_tpl,dataInfo, this.element );
    	},
    	
    	_init : function(){
    		this._on( {
    			'mouseover .search-select-item' : '_mouseover',
    			'mouseout .search-select-item' : '_mouseout',
    			'click .select-each-item' : '_select',
    			'blur .weight-begin-num' : '_setBeginWeight',
    			'blur .weight-end-num' : '_setEndWeight',
    			'click .weight-list>li' : '_selectWeight',
    			'click .weight-btn' : '_closeWeight',
    			'submit form' : '_submit'
    		} );
    		this._initData();
    	},
    	
    	_mouseover : function( event ){
    		this._onoff( event, 'over' );
    	},
    	
    	_mouseout : function( event ){
    		this._onoff( event, 'out');
    	},
    	
    	_onoff : function( e, type ){
    		var self = $(e.currentTarget),
    			target = self.attr( '_target' ),
    			box = self.find('ul');
    		target && ( box = self.find( '.' + target ) );
    		box[ type == 'over' ? 'show' : 'hide' ]();
    	},
    	
    	_select : function( event ){
    		var self = $(event.currentTarget),
				name = self.text(),
				id = self.data( 'id' ),
				type = self.data( 'type' ),
				form = this.element.find( 'form' );
			this._setValue( self, name, id );
			if( type == '1' ){
				if( id == 'other' ){
	    			this._showDefineTime();
				}else{
					this._hideDefineTime();
					form.trigger('submit');
				}
			}else{
				switch(type)
				{
					case 'cloud':
						form.trigger('submit',[id]);
					break;
					case 'site':
						this.options.site_id = id;
						form.trigger('submit',[id]);
					break;
					default:
						form.trigger('submit');
					break;
				}
			}
    	},
    	
    	_setValue : function( self, name, id ){
			var parent = self.closest( '.search-select-item' );
    		parent.find('.current-select').text( name );
    		parent.find( 'ul' ).hide();
    		parent.find( 'input[type="hidden"]' ).val( id );
    	},
    	
    	
    	_showDefineTime : function(){
    		this.element.find( '.define-time-box' ).show();
    	},
    	
    	_hideDefineTime : function(){
    		this.element.find( '.define-time-box' ).hide().find( 'input' ).val('');
    	},
    	
    	_initSiteData : function( url, arr, select_box ){
    		var _this = this,
    			module_data = arr;
    		if( url ){
        		$.getJSON( url, function( data ){
        			var cloud = data,
        				config_data = {},
        				_default = '';
        			$.each( cloud, function( key, value ){
        				if( !_default ){
        					_default = key;
        				}
        				config_data[key] = value['name'];
        			} );
        			module_data[0]['default_value'] = _default;
        			$.extend( module_data[0]['config_data'], config_data );
        			_this.options.site_id = _default;
        			_this._template( 'select_tpl', dataInfo.select_tpl,dataInfo, select_box, module_data );
        		} );
    		}
    	},
    	
    	_initModuleData : function( url, arr, select_box ){
    		var _this = this,
    			module_data = arr;
    		if( url ){
        		$.getJSON( url, function( data ){
        			var cloud = data['cloud'],
        				config_data = {},
        				_default = '';
        			$.each( cloud, function( key, value ){
        				if( !_default ){
        					_default = key;
        				}
        				config_data[key] = value['cloud_name'];
        			} );
        			module_data[0]['default_value'] = _default;
        			$.extend( module_data[0]['config_data'], config_data );
        			_this._template( 'select_tpl', dataInfo.select_tpl,dataInfo, select_box, module_data );
        		} );
    		}
    	},
    	
    	_initData : function(){
    		var searchs = this.options.config_search;
            if(searchs){

                var select_data = searchs['select_data'],
                    weight_data = searchs['weight_data'],
                    user_data = searchs['user_data'];
                var select_box = this.element.find( '.search-select-area' ),
                	cloud_box = select_box.find('#site-box'),
                	module_box = select_box.find('#module-box'),
                    weight_box = this.element.find( '.search-weight-area' ),
                    cloud_site = select_box.find('#site-site');

                this._template( 'select_tpl', dataInfo.select_tpl, dataInfo,select_box, select_data );
                this._template( 'weight_tpl', dataInfo.weight_tpl, dataInfo,weight_box, weight_data );
                this._template( 'user_tpl', dataInfo.user_tpl, dataInfo,select_box, user_data );
                this._initDatepicker();
                this._initSlider();
                
                this._initModuleData( this.options.cloud_url, this.options.cloud_data, cloud_box );
                this._initSiteData( this.options.site_url, this.options.site_data, cloud_site );
                //this._initAutocomplete();
            }
    	},
    	
    	_initDatepicker : function( ){
    		this.element.find( '.time-datepicker' ).hg_datepicker();
    	},
    	
    	_initAutocomplete : function(){
    		var autoitem = this.element.find( '.autocomplete' );
    		if( autoitem.length ){
    			autoitem.autocompleteResult();
    		}
    	},
    	
    	_initSlider : function(){
    		var _this = this,
    			weight_slider = this.element.find( '.weight-slider' );
    		weight_slider.slider( {
    			animate : true,
    			range : true,
    			min : 0,
    			max : 100,
    			values : [0,100],
    			slide : function( event, ui ){
    				_this._refreshWeightValue( ui.values );
    			}
    		} );
    	},
    	
    	_selectWeight : function( event ){
    		var self = $(event.currentTarget);
    			values = self.data('weight').split(',');
    		this._setWeightValue( values );
    		this.element.find( 'form' ).submit();
    	},
    	
    	_setWeightValue : function( values ){
    		var box = this.element.find( '.search-weight-area' ),
    			weight_slider = box.find( '.weight-slider' );
    		box.find( '.weight-box-outer' ).hide();
    		box.find( '.current-weight' ).text( '权重(' + values[0] + '-' + values[1] + ')' );
    		weight_slider.slider('values', values).trigger('slide');
    		this._refreshWeightValue( values );
    	},
    	
    	_refreshWeightValue : function( values ){
    		var box = this.element.find( '.search-weight-area' );
    		box.find( '.start-weight' ).val( values[0] );
    		box.find( '.end-weight' ).val( values[1] );
    		box.find( '.weight-begin-num' ).val( values[0] );
    		box.find( '.weight-end-num' ).val( values[1] );
    	},
    	
    	_setBeginWeight : function( event ){
    		var self = $(event.currentTarget),
    			weight_slider = this.element.find( '.weight-slider' ),
    			end_input = self.closest( '.search-weight-area' ).find( '.weight-end-num' ),
    			val = parseInt( self.val() ),
    			max = parseInt(end_input.val() );
    		if( !isNaN( val ) && val >= 0 && val <= max ){
    		}else{
    			val = 0;
    		}
    		weight_slider.slider('values', [val, max]).trigger('slide');
    		this._refreshWeightValue( [val, max] );
    	},
    	
    	_setEndWeight : function( event ){
    		var self = $(event.currentTarget),
				weight_slider = this.element.find( '.weight-slider' ),
				begin_input = self.closest( '.search-weight-area' ).find( '.weight-begin-num' ),
				val = parseInt( self.val() ),
				min = parseInt( begin_input.val() );
			if( !isNaN( val ) && val >= min && val <= 100 ){
				
			}else{
				val = 100;
			}
			weight_slider.slider('values', [min, val]).trigger('slide');
			this._refreshWeightValue( [min, val] );
    	},
    	
    	_closeWeight : function( event ){
    		var box = $( event.currentTarget ).closest( '.weight-box-outer' ),
    			weight_slider = this.element.find( '.weight-slider' );
    		var values = weight_slider.slider( 'values' );
    		this._setWeightValue( values );
    		box.hide();
    	},
    	
    	getFormParam : function(){
    		var form = this.element.find('form'),
    			search_info = form.serializeArray();
    		return search_info;
    	},
    	
    	_submit : function( event ,id ){
    		var _this = this,
    			id = id || '';
    		$.cloud_pop( {
    			className : _this.options['className'],
    			widget : 'pubLib',
    			site_id : _this.options.site_id,
    			cloud_id : id
    		} );
    		return false;
    	},
    	
    	refresh : function(){
    		this.element.html( '' );
    		this._template( 'search_tpl', dataInfo.search_tpl, dataInfo,this.element );
    		this._initData();
    	}
    	
    });
    
    $.widget('cloud_pop.page',{
		options : {
			total_page : 0,
			total_num : 0,
			page_num : 0,
			current_page : 0
		},
		_create : function(){
			
		},
		_init : function(){
			this._on({
                'click span[_page]' : '_click'
            });
			this._createPage();
		},
		_createPage : function(){
			var options = this.options;
			total_page = options.total_page;
			total_num = options.total_num;
			page_num = options.page_num;
			current_page = options.current_page;
			if(total_page < 2){
                this.element.hide();
                return;
            }
            var html = '';
            html += '<span class="page_all">共' + options['total_page'] + '页/计' + options['total_num'] + '条</span>';
            if(current_page > 1){
                html += '<span class="page-prev" _page="1"><a>|<</a></span>';
                html += '<span class="page-prev" _page="' + (current_page - 1) + '"><a><<</a></span>';
            }
            $.each([-2, -1, 0, 1, 2], function(i, n){
                var check = false;
                var val = current_page + n;
                if(n < 0){
                    if(val > 0){
                        check = true;
                    }
                }else if(n > 0){
                    if(val <= total_page){
                        check = true;
                    }
                }
                if(check){
                    html += '<span class="page-code" _page="' + val + '"><a>' + val + '</a></span>';
                }
                if(n == 0){
                    html += '<span class="page_cur">' + current_page + '</span>';
                }
            });
            if(current_page < total_page){
                html += '<span class="page-next" _page="' + (current_page + 1) + '"><a>>></a></span>';
                html += '<span class="page-next" _page="' + total_page + '"><a>>|</a></span>';
            }
            this.element.html(html);
		},
		_click : function(event){
            var page = $(event.currentTarget).attr('_page');
            this._trigger('page', null, [page]);
        },
        
        show : function(){
            this.element.show();
        },
        
        hide : function(){
            this.element.hide();
        },
        
        refresh : function( option ){
            this.show();
            $.extend( this.options, option );
            this._createPage();
        }
	});
    
})(jQuery);

;(function($){
	var nav_info = {
		template : '' + 
			 		'<ul class="m2o-nav-list">' +
						'<li class="column-list top-item stretch-list" _ajax="false" data-fid="0">' +
							'<span class="hook"></span>' +
							'<span class="title">全部<a></a></span>' +
							'<ul></ul>' +
						'</li>' +
					'</ul>' +
					'',
		item_tpl : '' + 
				 '<li class="{{if is_last > 0}}no-child{{else}}{{/if}}" data-id="${id}" data-name="${name}" data-fid="${id}">' + 
				 '{{if !(is_last > 0)}}' + 
				    '<span class="hook"></span>' +
				 '{{/if}}' +
				    '<span class="title" title="${name}">${name}</span>' +
				'</li>' +
				'',
		css : '' +
			'.m2o-nav-list{padding:10px;}' +
			'.m2o-nav-list ul{padding-left:15px; margin-top:1px; }' +
			'.m2o-nav-list li{cursor:pointer;line-height: 30px; background:url(' + RESOURCE_URL+ 'datasource/left-list.png) no-repeat left 7px; margin-bottom:1px; position:relative;}' +
			'.m2o-nav-list li.no-child{background-image:none;}' + 
			'.m2o-nav-list li > .title{cursor:pointer;display:block; height:28px;overflow:hidden;font-size:14px; margin-left:14px; text-indent:5px;}' +
			'.m2o-nav-list li.hasset > .title{color:#96c7ef;}' +
			'.m2o-nav-list li >.title:hover, .temp-nav li > .title.on{background-color:#545454; color:#fff;}' +
			'.m2o-nav-list li > .title.selected {background-color:#5c99cf; color:#fff;}' +
			'.m2o-nav-list li > .title:hover a, .temp-nav li > .title.on a{opacity:1;cursor:pointer; -webkit-transition: opacity .3s ease-in; -moz-transition: opacity .3s ease-in; transition: opacity .3s ease-in;}' +
			'.m2o-nav-list li.stretch-list{background:url(' + RESOURCE_URL+ 'datasource/drop-list.png) no-repeat left 7px;}' +
			'.m2o-nav-list .hook{position:absolute;left:0px;top:0px;height:30px;width:14px;} ' +
			'',
		cssInited : false
 	};
	$.widget('cloud_pop.nav', $.cloud_pop.base, {
        options : {
        	url : '',
        	cloud_id : '',
        	site_id : ''
        },
        
        _create :function(){
        	this._template( 'mian-tpl',nav_info['template'],nav_info, this.element );
        	this.column_list = this.element.find('.column-list').find('ul');
        },
        
        _init : function(){
            this._on({
                'click .hook' : '_stretch',
                'click .title' : '_selected'	//刷新右侧列表
            });
            this._root();
        },
        _root : function(){
            this._ajax( this.options.cloud_id, 0, this.column_list );
    		this._initSelect();
        },
        
        getColumnid : function(){
        	var column_id = this.element.find('.title.selected').closest('li').data('fid');
        	return column_id;
        },
        
        _ajax : function( cloud_id, fid, parent ){
            var _this = this;
            var url = this.options.url;
            $.getJSON( url, { cloud_id : cloud_id, fid : fid }, function( json ){
        		var data = json;
        		if(_this.options.cloud_id)
        		{
	        		$('body').find('input[name="cloud_id"]').val(_this.options.cloud_id);
        		}
        		console.log($('body').find('input[name="cloud_id"]').val());
        		_this._template('child-tpl',nav_info['item_tpl'],nav_info, parent.empty(), data );
            } );
        },
        
        _initSelect : function( site_id ){
        	this.element.find('.title').eq( 0 ).trigger('click', [site_id]);
        },
        
        _stretch : function(event){
            var _this = this,
            	item = $(event.currentTarget).closest('li');
            var cname = 'stretch-list';
            if(item.hasClass(cname)){
                item.removeClass(cname);
                item.find('ul').hide();
            }else{
                item.addClass(cname);
                if( item.data('ajax') ){
                    item.find('ul').show();
                }else{
                	var fid = item.data('id'),
                		cloud_id = _this.options.cloud_id;
                    item.data('ajax', true);
                    this._appendBox(item);
                    this._ajax( cloud_id, fid, item.find('ul') );
                }
            }
        },

        _appendBox : function(parent){
            $('<ul><li class="no-child"><img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/></li></ul>').appendTo(parent);
        },
        _selected : function(event,site_id){
            var _this = this,
            	self = $(event.currentTarget);
            var	item = self.closest('li'),
            	all = this.element.find('.title'),
            	id = item.data('id'),
            	cname = 'selected';
            all.removeClass( cname );
            self.addClass( cname );
            if( !site_id ){
                $.cloud_pop( {
        			className : _this.options['className'],
        			widget : 'pubLib'
        		} );
            }
        },

        refresh : function( cloud_id, fid ){
        	var fid = fid || 0,
        		cloud_id = cloud_id;
        	this.options.cloud_id = cloud_id;
        	this._ajax( cloud_id, fid, this.column_list.empty() );
    		this._initSelect( cloud_id );
        }
    });
	
})(jQuery);


;(function($){
	var box_info = {
		template : '' + 
					'<div class="unit-box">' +
						'<div class="unit-box-btn">' + 
							'<a class="unit-btn-sure">确定</a>' + 
							'<a class="unit-btn-cancel">取消</a>' +
						'</div>' +
						'<div class="unit-box-main">' + 
							'<div class="unit-box-item">' +
								'<span class="unit-box-title">发布至:</span>' +
								'<span class="unit-box-publish" _default="无">无</span>' +
							'</div>' +
							'<div class="unit-box-item" id="sort-box">' +
								'<span class="unit-box-title">分类:</span>' +
								'<span class="unit-box-sort sort-label">选择分类<img class="common-head-drop" src="' + RESOURCE_URL + 'tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></span>' +
								'<div class="sort-box-outer"><div class="sort-box-inner"></div></div>' +
								'<input name="sort_id" type="hidden" value="" id="sort_id" />' +
							'</div>' +
						'</div>' +
					'</div>' +
					'',
		css : '' +
			'.unit-box-pop{ -moz-transition:top 0.2s ease-in 0s;-webkit-transition:top 0.2s ease-in 0s;transition:top 0.2s ease-in 0s;}' + 
			'.unit-box{position:relative;width:191px;height:auto;border:1px solid #6ba4eb;background:#fff;}' +
			'.unit-box-main{margin-top:35px;padding:0 10px;}' +
			'.unit-box-item{height:36px;border-bottom:1px solid #ccc;line-height:36px;}' +
			'.unit-box-publish{cursor:pointer;width:80px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;height:24px;}' +
			'.unit-box-title{display:inline-block;width:70px;}' +
			'.unit-box-btn{right:10px;top:10px;position:absolute;}' +
			'.unit-box-btn a{background:#6ba4eb;color:#fff;padding:2px 8px;display:inline-block;margin-left:10px;cursor:pointer;}' +
			'.unit-box-btn a:hover{background:#2c78d6;}' +
			'.sort-box-inner{background:#fff;}' +
			'.hg-sort-box ul{width:151px;}' +
			'',
		cssInited : false
 	};
	$.widget('cloud_pop.addBox', $.cloud_pop.base, {
        options : {
        	local_url : './cloud.php?mid=' + gMid + '&a=local&ajax=1'
        },
        
        _create :function(){
        	this._super();
        	this.publishBox = $('body').find('.common-form-pop');
        	this.element.addClass( this.options.className );
        	this._template( 'box-tpl',box_info['template'],box_info, this.element );
        },
        
        _init : function(){
        	this._super();
        	this._on( {
        		'click .unit-btn-sure' : '_submit',
        		'click .unit-btn-cancel' : '_cancel',
        		'click .unit-box-publish' : '_publish'
        	} );
        	this.show( this.options.css );
        	this._initPublishbox();
        	this._initSortBox();
        },
        refresh : function( option ){
        	$.extend( this.options, option );
        	this.show( this.options.css );
        },
        _submit : function(){
        	var list_widget = this.options.list_widget,
        		param = {};
        		body = $('body');
        	/*var current_list = list_widget.find('.publish-list-data.current').filter( function(){
        		return !$(this).hasClass('is-add');
        	} );*/
        	var current_list = list_widget.find('.publish-list-data.current');
        	var ids = current_list.map( function(){
        		return $(this).data('id');
        	} ).get().join(',');
        	param['cid'] = ids;
        	param['cloud_id'] = list_widget.find('input[name="cloud_id"]').val();
        	param['column_id'] = body.find('input[name="column_id"]').val();
        	param['pub_time'] = body.find('input[name="pub_time"]').val();
        	param['sort_id'] = this.element.find('input[name="sort_id"]').val();
        	this._ajax( param );
        },
        _ajax : function( param ){
        	var _this = this;
        	$.post( this.options.local_url, param, function( data ){
        		if( data && data['callback'] ){
        			eval( data['callback'] );
        		}else{
            		_this.hide();
            		_this._reload();
            		_this._hideButton();
            		_this.publishBox.find('.publish-box-close').trigger( 'click' );
        		}
        	}, 'json' );
        },
        _errorTip : function( obj, msg ){
        	obj.myTip( {
        		string : msg,
        		'z-index' : 1000010000000
        	} );
        },
        _hideButton : function(){
        	var current_list = this.options.list_widget.find('.publish-list-data.current');
        	/*current_list.find('.publish-add-btn').remove();
        	current_list.removeClass('current').addClass('is-add').attr('title','数据已添加');*/
        	current_list.removeClass('current').addClass('is-add');
        },
        _reload : function(){
        	$('#nodeFrame')[0].contentWindow.location.reload();
        },
        _cancel : function(){
        	this.hide();
        	this.publishBox.find('.publish-box-close').trigger( 'click' );
        },
        _initPublishbox : function(){
        	var _this = this,
    			common_button = this.element.find('.unit-box-publish');
        	this.publishBox.on( 'click', '.publish-box-close', function(){
        		_this.publishBox.css({top: -450});
        		_this.element.find('.unit-box-publish').data('show',false);
        	} );
	    	var publishBox = this.publishBox.find('.publish-box').hg_publish({
		    	change: function () {
		    		common_button.html(function(){
		        		var hidden = _this.publishBox.find( '.publish-name-hidden' ).val();
		       			return hidden ? ( '<span style="color:#000;">' + hidden + '</span>') : $(this).attr('_default');
		    		 });	
		    	},
		    	maxColumn: 3
		    });
		    this.publishBoxObj = publishBox.data('publish');
        },
        _initSortBox :function(){
            var _this = this,
            	sp = $('#sort-box').find('.sort-box-inner'),
	            label = $('#sort-box .sort-label'),
	            nodevar = $('.add-yuan-btn').attr('nodevar');
            label.attr('_multi',nodevar );
	        if(sp[0]){
	            sp.hgSortPicker({
	            	site_id : label.attr('_site') || '',
	                nodevar: label.attr('_multi'),
	                width: 151,
	                change: function(id, name) {
	                    label[0].firstChild.nodeValue = name;
	                    label.prev().show();
	                    $('#sort_id').val(id);
	                    label.trigger('click');
	                },
	                getId: function() {
	                    return $('#sort_id').val();
	                },
	                baseUrl: label.attr('baseUrl') || undefined
	            });
	            sp.hide();
	        }else{
	        	
	        }
        	label.toggle(function() {
	            sortBian();
	            sp.slideDown(500, function () {  });
	        }, function() {
	            sortBian();
	            sp.slideUp(500);
	        });
	        $('#sort-box').click(function(e) {
	            if (e.target == this  ) {
	                label.trigger('click');
	            }
	        });
	        function sortBian() {
	        	var sort_box = $('#sort-box'),
	        		unit_box = _this.element.find('.unit-box');
	        	sort_box.toggleClass('sort-box-with-show');
	            if( sort_box.hasClass('sort-box-with-show') ){
	            	unit_box.css( {height : '400px'} );
	            }else{
	            	unit_box.css( {height : 'auto'} );
	            }
	        }
        },
        _publish : function( event ){
        	var self = $(event.currentTarget),
        		top = self.offset().top - 36;
        	if ( self.data('show') ) {
        		this.publishBox.find('.publish-box-close').trigger( 'click' );
            } else {
            	var real_top = this._position( top );
            	self.data('show', true);
            	this.publishBox.css({top: real_top + 'px'});	
            	this.publishBoxObj.removeResult();
            }
        },
        _position : function( top ){
        	var wrap_h = $('body').height(),
        		pop_h = this.publishBox.height(),
        		real_top = top;
        	if( top + pop_h > wrap_h ){
        		real_top = wrap_h - pop_h;
        	}
        	return real_top;
        },
        show : function( option ){
        	$.extend( option,  {opacity : 1} );
        	this.element.css( option );
        	this.options.list_widget.pubLib('createMask');
        },
        hide : function(){
        	this.element.css( { opacity : 0 } );
        	this.options.list_widget.pubLib('clearMask');
        }
    });
	
})(jQuery);


;(function($){ 
    
    $.cloud_pop = function( option ){
    	var className = option.className,
    		widget = option.widget,
    		refresh = option.refresh || 'refresh',
    		container = option.container || 'body';
    	var el = $( '.' + className );
		if( el.length ){
			el[ widget ]( refresh, option );
			return;
		}
		$('<div></div>').appendTo(container)[widget]( option );
    };
    
})(jQuery);