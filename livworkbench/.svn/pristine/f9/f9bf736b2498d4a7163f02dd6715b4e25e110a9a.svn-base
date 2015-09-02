(function($){
	window.RESOURCE_URL = '../res/magic/image/';
    var dataInfo = {
		list_box : '' + 
				'<div class="publish-nav-box"></div>' +
				'<div class="publish-list-area">' + 
					'<div class="publish-list-head m2o-flex m2o-flex-center">' + 
						'<div class="publish-chk"></div>' + 
						'<div class="publish-title m2o-flex-one">标题</div>' + 
						'<div class="publish-column">发布栏目</div>' + 
						'<div class="publish-weight">权重</div>' + 
						'<div class="publish-type">类型</div>' + 
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
					 '<div class="publish-list-data m2o-flex m2o-flex-center" data-id="${$value.id}">' +
						'<div class="publish-chk"><input type="checkbox" /></div>' + 
						'<div class="publish-title m2o-flex-one">' +
							'<div class="biaoti-transition">' + 
								'<div class="max-200 overflow">' + 
									'{{if $value.url}}<img src="${$value.url}" class="title-img" />{{/if}}' + 
									'<span title="${$value.title}">${$value.title}</span>' + 
								'</div>' + 
							'</div>' + 
						'</div>' +
						'<div class="publish-column">${$value.column_info.name}</div>' + 
						'<div class="publish-weight">' +
							'<div class="publish-weight-box">' + 
								'<div class="weight-inner" _weight="${$value.weight}">' + 
									'<div class="weight-label">${$value.weight}</div>' + 
								'</div>' + 
							'</div>' + 
						'</div>' + 
						'<div class="publish-type">${$value.module_name}</div>' + 
						'<div class="publish-person">' +
							'<div>${$value.publish_user}</div>' + 
							'<span class="time">${$value.publish_time}</span>' + 
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
						'<div class="search-select-item">' +
							'<div class="current-select">发布库</div>' +
							'<ul class="select-list">' +
								'<li _type="publish" class="select-each-item">发布库</li>' +
								'<li _type="custom" class="select-each-item">自定义添加</li>' +
							'</ul>' + 
						'</div>' +
						'<div class="search-select-item" id="site-box"></div>' + 
						'<div class="search-select-item" id="module-box"></div>' + 
					'</div>' +
					'<div class="search-weight-area"></div>' +
					'<input type="text" name="k" class="search-k" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索">' +
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
			'.publish-list-con{margin:0 10px;}' +
			'.publish-list-bottom{padding:6px 20px 0;}' +
			'.publish-list-data{padding:0 10px;color:#8fa8c6;cursor:pointer;}' +
			'.publish-list-data.current,.publish-list-data:hover{background:#EEEFF1;}' +
			'.publish-list-data:active{background:#ddeefe;}' +
			'.max-200{max-width:200px;}' +
			'.publish-list-data .overflow{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:inline-block;vertical-align:middle;}' +
			'.publish-list-data .publish-title{font-size:14px;color:#282828;position:relative;z-index:100000;}' +
			'.publish-list-data .time{color:#888;padding-top:4px;display:inline-block;}' +
			'.publish-list-data .title-img{vertical-align:middle;padding-right:10px;}' + 
			'.publish-list-data .publish-controll{height:40px;}' +
			'.biaoti-transition{display:inline-block;-moz-transition:all 0.15s ease-in 0s;-webkit-transition:all 0.15s ease-in 0s;-o-transition:all 0.15s ease-in 0s;-ms-transition:all 0.15s ease-in 0s;transition:all 0.15s ease-in 0s;}' + 
			'.biaoti-transition:hover{padding-left:8px;}' + 
			'.publish-add-btn{display:none;width:50px;height:50px;vertical-align:middle;cursor:pointer;background:url(' + RESOURCE_URL+'datasource/addlist.png) no-repeat left center;}' + 
			'.publish-controll:hover .publish-add-btn,.publish-list-data:hover .publish-add-btn{display:block;}' + 
			'.publish-list-area .publish-chk{width:25px;}' + 
			'.publish-list-area .publish-chk input{display:none;}' + 
			'.publish-list-area .publish-weight{width:50px;}' + 
			'.publish-list-area .publish-weight-box{position:relative; width:40px;}' + 
			'.publish-list-area .weight-inner{-webkit-text-size-adjust: none; border-radius: 17px; font-size:10px; color:#000; position:relative; text-align:center; width:24px; height:24px; line-height: 24px; z-index: 1; cursor: default;}' + 
			'.publish-list-area .weight-label{display: inline-block; width: 23px; height: 23px; line-height: 23px;color: #666; border-radius: 12.5px; background: #fff; opacity: 0.9;}' + 
			'.publish-list-area .publish-type{width:70px;}' + 
			'.publish-list-area .publish-controll{width:50px;}' + 
			'.publish-list-area .publish-column{width:80px;}' + 
			'.publish-list-area .publish-person{width:150px;}' + 
			'.publish-list-bottom .left{float:left;display:none;}' +
			'.publish-list-bottom input{vertical-align:middle;}' +
			'.publish-list-bottom .batch-add-btn{padding-left:20px;vertical-align:middle;cursor:pointer;}' + 
			'.publish-empty{padding:10px;font-size:14px;}' +
			'.page-area{float:right;padding:4px 20px 0 0;}' +
			'.page-area span{margin:0 4px;}' +
			'.page-area span a{display:inline-block;height:22px;line-height:22px;padding:0 8px;border:1px solid #ccc;cursor:pointer;}' +
			'.page-area span a:hover{background:#498adb;color:#fff;}' +
			'.page-area span.page_cur{display:inline-block;background:#d8e8f5;color:#666;height:22px;line-height:22px;padding:0 8px;border:1px solid #ccc;}' +
			'.page-area span.page-prev a,.page_area span.page-next a{padding:0 14px;}' +
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
    
    $.widget('pop.pubLib', $.pop.pop, {
    	options : {
    		title : '',
    		className : 'pubLib-pop-box',
    		list_url : '../get_publish_content.php?a=get_content', 	//取发布库数据列表接口
    		column_url : '../fetch_column.php', //取栏目数据接口
    		clickCall : $.noop,
    		current : 'current',
    		drag : false,	//默认拖动操作关闭click进行添加
    		handlerName : '',
    		need_define : false, // 是否需要自定义添加
    		module_url : '../get_publish_content.php?a=get_bundles',
    		site_url : '../get_publish_content.php?a=get_site',
    	},
    	
    	_create : function(){
    		this._super();
    		this._template( 'list_box', dataInfo.list_box, dataInfo, this.body );
    		this.list_box = this.body.find( '.publish-list-con' );
    		this.nav_box = this.body.find( '.publish-nav-box' );
    		this.page_box = this.body.find( '.page-area' );
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
    					_this._clickCall( id )
    				}
    			} );
    		} );
    	},
    	
    	_initPublish : function(){
    		
    	},
    	
    	_initPage : function( option ){
    		var _this = this,
    			pagebox = this.page_box;
    		if( pagebox.data('init') ){
    			pagebox.page('refresh',option);
    			return;
    		}
    		option['page'] = function( event, val ){
    			_this.refresh({
    				page : val
    			})
    		}
    		pagebox.page( option );
    		pagebox.data( 'init', true );
    	},
    	
    	_initWeightColor : function(){
    		var weight_items = this.list_box.find( '.weight-inner' );
    		weight_items.each( function(){
    			var weight = $(this).attr( '_weight' ),
    				rgb_color = $.create_rgb_color( weight );
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
			this.globalInfo = this.globalInfo || {};
			$('<img src=" ' + RESOURCE_URL + 'datasource/loading.gif" width="64" height="64" style="position:absolute;top:50%;left:50%;margin:-32px 0 0 -32px;"  />').appendTo( this.list_box );
    		$.post( url, param, function( data ){ 
    			var data = data[0] || data,
					tmpl_data = {};
				if( !data['info'] ){
					tmpl_data.list = null;
				}else{
	    			tmpl_data.list = $.map( data['info'], function( value, key ){
	    				if( !$.isArray( value['indexpic'] ) ){
	        				value['url'] = $.globalImgUrl( value['indexpic'], '40x30' );
	    				}
	    				_this.globalInfo[ value['id'] ] = value;
	    				return value;
	    			} );
				}
    			_this._clear();
    			_this._template( 'list_each', dataInfo.list_each, dataInfo ,_this.list_box, tmpl_data );
    			_this._initPage( data['page_info'] );
    			_this._setConfig();
    		}, 'json' );
    	},
    	
    	refresh : function( option ){
    		var options = {},
    			search_info = this.search_box.search('getFormParam'),
    			column_id = 0;
    		if( option.site_id ){
				this.nav_box.nav('refresh',option.site_id);
			}else{
				column_id = this.nav_box.nav('getColumnid');
			}
			options['info'] = search_info;
			options['column_id'] = column_id;
			$.extend( options, option );
			this.show();
			this._getData( options );
			
    	},
    	
    	_clear : function(){
    		this.list_box.html( '' );
    		this.page_box.html( '' );
    	},
    	
    	_click : function( event ){
    		var self = $( event.currentTarget );
    			checkbox = self.find( 'input[type="checkbox"]' ),
    			ischeck = checkbox.prop( 'checked' );
    		checkbox.prop( 'checked', !ischeck );
    		this._toggle( !ischeck, self );
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
    	
    	_clickCall : function( ids ){
    		var _this = this,
    			info = [],
    			ids = ids.split(',');
    		$.each( ids, function( key, value ){
    			info.push( _this.globalInfo[ value ]  );
    		} );
    		this._trigger( 'clickCall', null, [ info, this ] );
    	},
    	
    	_add : function( event ){
    		var self = $( event.currentTarget ),
    			id = self.closest('.publish-list-data').data( 'id' ).toString();
    		this._clickCall( id );
    		event.stopPropagation();
    	},
    	
    	_batchAdd : function(){
    		var _this = this,
    			items = this.element.find( '.publish-list-data' ).filter( function(){
    			return $(this).hasClass( _this.options.current );
    		} );
    		var	ids = items.map( function(){
    			return $(this).data( 'id' );
    		} ).get().join(',');
    		if( !ids ){
    			jAlert('请选择要添加的记录','添加提醒');
    		}else{
        		this._clickCall( ids );
    		}
    	},
    	
    	_customForm : function(){
    		var _this = this,
    			op = _this.option;
    		$.pop( {
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
    	
    	_initSearch : function(){
    		var op = this.options;
    		this._initUnit( this.search_box, 'search', {
    			config_search : op['config_search'],
				need_define : op['need_define'],
				module_url : op['module_url'],		// 取类型数据接口
    			site_url : op['site_url'],
    			user_url : op['user_url'],
    			className : op['className']
    		} );
    	},
    	
    	_initNav : function(){
    		var	op = this.options;
    		this._initUnit( this.nav_box, 'nav', {
    			url : op['column_url'],
    			className : op['className'],
    			site_id : 0
    		} );
    	}
    });
    
    $.widget('pop.custom', $.pop.pop, {
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
    		this.input_file.ajaxUpload( {
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
    
    $.widget('pop.search', $.pop.base, {
    	options : {
    		config_search : { 
				select_data : [ {name: 'special_date_search',default_value : 1, config_data: {1: "所有时间段", 2: "昨天", 3: "今天", 4: "最近3天", 5: "最近7天", other: "自定义时间"} , type : 1 }
							  ],
				weight_data : { start_weight: 'start_weight', end_weight: 'end_weight', config_weight: [ {begin_w:90, end_w: 100, title: '首页头条'}, {begin_w:80, end_w: 90, title: '二级首页头条'},{begin_w:70, end_w: 80, title: '首页显示'},{begin_w:60, end_w: 70, title: '二级首页'}] },
				user_data : [{name : 'user_name', placeholder : '添加人'}]
			},
			module_data : [ {name: 'special_modules',default_value : 0, config_data: {0:'全部'} , type : 0 }
			  			  ],
  			site_data : [ {name: 'site_id',default_value : 1, config_data: {} , type : 'site' }
  			  ],
			module_url : '',
			site_url : '',
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
    			if( type=='site' ){
    				form.trigger('submit',[id]);
    			}else{
    				form.trigger('submit');
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
    	
    	_hideDefine : function( select_box ){
    		select_box.find( '.search-select-item:first' ).find( 'ul' ).remove();
    	},
    	
    	_initModuleData : function( url, arr, select_box ){
    		var _this = this,
    			module_data = arr;
    		if( url ){
        		$.getJSON( url, function( data ){
        			$.extend( module_data[0]['config_data'], data );
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
                	site_box = select_box.find('#site-box'),
                	module_box = select_box.find('#module-box'),
                    weight_box = this.element.find( '.search-weight-area' );
                this._template( 'select_tpl', dataInfo.select_tpl, dataInfo,select_box, select_data );
                this._template( 'weight_tpl', dataInfo.weight_tpl, dataInfo,weight_box, weight_data );
                this._template( 'user_tpl', dataInfo.user_tpl, dataInfo,select_box, user_data );
                this._initDatepicker();
                this._initSlider();
                this._initModuleData( this.options.site_url, this.options.site_data, site_box );
                this._initModuleData( this.options.module_url, this.options.module_data, module_box );
                this._initAutocomplete();
                !this.options['need_define'] && this._hideDefine( select_box );
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
    		$.pop( {
    			className : _this.options['className'],
    			widget : 'pubLib',
    			site_id : id
    		} );
    		return false;
    	},
    	
    	refresh : function(){
    		this.element.html( '' );
    		this._template( 'search_tpl', dataInfo.search_tpl, dataInfo,this.element );
    		this._initData();
    	}
    	
    });
    
    $.widget('pop.page',{
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
							'<span class="title">栏目<a></a></span>' +
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
	$.widget('pop.nav', $.pop.base, {
        options : {
        	url : '',
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
            this._ajax( this.options.site_id, 0, this.column_list );
    		this._initSelect();
        },
        
        getColumnid : function(){
        	var column_id = this.element.find('.title.selected').closest('li').data('fid');
        	return column_id;
        },
        
        _ajax : function( site_id, fid, parent ){
            var _this = this;
            var url = this.options.url;
            $.getJSON( url, { siteid : site_id, fid : fid }, function( json ){
        		var data = json;
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
                		site_id = _this.options.site_id;
                    item.data('ajax', true);
                    this._appendBox(item);
                    this._ajax( site_id, fid, item.find('ul') );
                }
            }
        },

        _appendBox : function(parent){
            $('<ul><li class="no-child"><img src="' + RESOURCE_URL + 'datasource/loading.gif" style="width:30px;"/></li></ul>').appendTo(parent);
        },
        _selected : function(event, site_id){
            var _this = this,
            	self = $(event.currentTarget);
            var	item = self.closest('li'),
            	all = this.element.find('.title'),
            	id = item.data('id'),
            	cname = 'selected';
            all.removeClass( cname );
            self.addClass( cname );
            if( !site_id ){
                $.pop( {
        			className : _this.options['className'],
        			widget : 'pubLib'
        		} );
            }
        },

        refresh : function( site_id, fid ){
        	var fid = fid || 0,
        		site_id = site_id;
        	this.options.site_id = site_id;
        	this._ajax( site_id, fid, this.column_list.empty() );
    		this._initSelect( site_id );
        }
    });
	
})(jQuery);


(function($){ 
    
    $.pop = function( option ){
    	var className = option.className,
    		widget = option.widget,
    		refresh = option.refresh || 'refresh';
    	var el = $( '.' + className );
		if( el.length ){
			el[ widget ]( refresh, option );
			return;
		}
		$('<div></div>').appendTo('body')[widget]( option );
    };
    
})(jQuery);