;(function($){
	
	/*基础组件*/
    $.widget('new_search.base', {
    	/*基于jquery tmpl渲染模版功能函数*/
        _template : function( tname, tpl, dataInfo, container, data, direction ){
        	var direction = direction || 'appendTo';
        	$.template( tname, tpl );
        	$.tmpl( tname, data )[direction]( container );
        	if( !dataInfo.cssInited && dataInfo.css ){
        		dataInfo.cssInited = true;
        		this._addCss( dataInfo.css );
        	}
        },
        
        _addCss : function(css){
            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
        }
    });
    
    /*高级搜索弹窗dom及css样式信息*/
	var pluginInfo = {
			template : '' + 
					'<div class="new-search-box advance-search-box clear">' +
						'<form target="${target}" name="searchform" method="get">' +
							'<div class="new-search-form-content">' +
								'<div class="new-search-head">' +
									'<span>高级搜索</span>' +
									'<span class="close"></span>' +
								'</div>' +
								'<div class="new-search-content"></div>' +
								'<div class="new-search-controll">' +
									'<span><input type="submit" class="search-submit-btn save-button" value="搜索" /></span>' +
									'<span class="search-clear-btn search-text-btn">清空搜索条件</span>' +
									'<span class="search-create-btn search-text-btn">创建搜索标签</span>' +
								'</div>' +
								'<div class="advance-search-hidden"></div>' +
							'</div>' +
						'</form>' +
						'<div class="new-search-deletearea">' +
							'<form target="${target}">' +
								'<div class="new-search-controll new-search-delete">' +
									'<span class="search-delete-btn">按条件删除</span>' +
								'</div>' +
								'<div class="advance-searchdelete-hidden"></div>' +
							'</form>' +
						'</div>' +
					'</div>' +
					'',
			simple_tpl : '' + 
					'<div class="simple-search-box">'+
						'<form target="${target}" name="searchform" method="get">' +
							'<div class="m2o-flex">' +
								'<div class="simple-search-title">' +
									'<input type="text" class="simple-input-title" name="key" />' +
									'<input type="submit"  value="" /> ' +
								'</div>' +
								'<div class="simple-search-advance">高级搜索</div>' +
								'<div class="my-label-area">' +
										'<div class="current-label-show">我的标签</div>' +
										'<ul class="defer-hover-target"></ul>' +
										'<input type="hidden" name="searchtag_id" class="label-id-hidden" />' +
								'</div>' +
							'</div>' +
							'<div class="simple-search-hidden"></div>' +
							'<div class="searchtag-search-hidden"></div>' +
						'</form>' +
					'</div>' +
					'',
			input_hidden_tpl : '' + 
						'<input type="hidden" name="a" value="show" />' +
						'<input type="hidden" name="mid" value=${mid} />' +
						'<input type="hidden" name="node_en" value="${node_en}" />' +
						'<input type="hidden" name="_id" value="${_id}" />' +
						'<input type="hidden" name="infrm" value="${infrm}" />' +
						'',
			searchtag_hidden_tpl : ''+
						'{{if searchtag_hiddens}}' +
                        '{{each searchtag_hiddens}}' +
						'<input type="hidden" name="${$index}" value="${$value}" />'+
						'{{/each}}' +
						'{{/if}}'+
						'',
			site_tpl : '' +
					'<li data-id="${id}" data-name="${name}">' +
						'<input type="radio" name="site_radio" />${name} ' +
		 			'</li>' +
					'',
			column_tpl : '' +
					'<div class="new-column-each">' +
						'<ul>' +
							'{{each list}} ' +
							'<li data-id="{{= $value.id}}" data-fid="${$item.fid}" title="{{if $item.showName}}${$item.showName} > {{/if}}${name}" _name="{{= $value.name}}" class="one-column {{if +$value.is_last}}no-child{{/if}}">'+
								'<input type="checkbox" class="publish-checkbox" /> '+
								'<span class="publish-name">${name}</span>'+
								'<span class="publish-child">&gt;</span>'+
							'</li>' +
							'{{/each}}' +
						'</ul>' +
					'</div>' +
					'',
			column_box : '' +
					'<div class="new-search-head">' +
						'<div class="new-site-box">' +
							'<span class="current-site"></span>' +
							'<span class="site-switch-btn">切换<img class="switch-loading" src="'+ RESOURCE_URL + 'loading2.gif" width="30" height="30" /></span>' +
							'<ul></ul>' +
						'</div>' +
						'<span><input type="button" class="search-submit-btn save-button" value="保存" /></span>' +
						'<span class="close"></span>' +
					'</div>' +
					'<div class="new-column-content">' +
						'<div class="new-column-innerList"></div>' +
					'</div>' +
					'',
			label_box : '' +
					'<div class="search-label-box">' +
						'<div class="new-search-head">' +
							'<span>创建搜索标签</span>' +
							'<span class="close"></span>' +
						'</div>' +
						'<div class="search-label-content clear"></div>' +
						'<div class="search-label-controll">' +
							'<p>按照以上条件设置标签名</p>' +
							'<div><input class="search-label-input" /></div>' +
							'<div><input type="button" class="search-submit-btn save-label" value="保存"></div>' +
						'</div>' +
					'</div>' +
					'',
			label_tpl : '' +
					'<div class="search-label-item search-label-${type}">'+
						'<span class="label-name">${name}：</span>' +
						'{{if id == "other"}}<span class="label-value">${start}至${end}</span>{{/if}}' +
						'{{if id != "other"}}<span class="label-value" title="${value}">${value}</span>{{/if}}' +
					'</div>' +
					'',
			label_item : '' +
					'<li data-id="${id}" title="${title}" data-value="${tag_val}">'  +
						'<a>${title}</a>' +
						'<span class="label-del"></span>' +
					'</li>' +
					'',
			css : '' +
				'.new-nav-box #hg_info_list_search{display:none;}' +
				'.simple-search-hidden,advance-search-hidden,.searchtag-search-hidden{display:none;}' +
				'.m2o-flex{display:-webkit-box;display:-moz-box;display:box;}' +
				'.ui-autocomplete{max-height:183px;}' +
				'.ui-autocomplete .ui-menu-item:first-child{border-top:0;}' +
				'.ui-autocomplete .ui-menu-item a{line-height:22px;text-indent:8px;text-align:left;}' +
				'.simple-search-box{height:43px;}' +
				'.simple-search-box .simple-search-title{width:150px;position:relative;background:#fff;border-right:1px solid #ccc;}' +
				'.simple-search-box .simple-input-title{margin:8px 10px;width:130px;height:18px;}' +
				'.simple-search-box input[type="submit"]{background:url(' + RESOURCE_URL + 'menu2013/search-2x.png) no-repeat center;border:0;width:24px;height:24px;position:absolute;top:7px;right:6px;background-size:14px 14px;cursor:pointer;}' +
				'.simple-search-box input[type="submit"]:focus{-webkit-box-shadow:none!important;-moz-box-shadow:none!important;}' +
				'.simple-search-box .simple-search-advance,.simple-search-box .my-label-area{width:105px;cursor:pointer;text-align:center;line-height:43px;background:#fff;border-right:1px solid #ccc;}' +
				'.simple-search-box .my-label-area{display:none;position:relative;}' +
				'.simple-search-box .my-label-area ul{display:none;text-align:left;text-indent:10px;z-index:9;position:absolute;width:105px;border:1px solid #ccc;left:-1px;background:#fff;}' +
				'.simple-search-box .my-label-area li{border-bottom:1px solid #ccc;line-height:30px;height:30px;position:relative;}' +
				'.simple-search-box .my-label-area li:hover{background:#ddeefe;}' +
				'.simple-search-box .my-label-area li a{text-indent:5px;display:inline-block;width:75px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}' +
				'.simple-search-box .my-label-area li .label-del{display:none;position:absolute;top:3px;right:3px;width:20px;height:25px;background:url('+ RESOURCE_URL + 'del-2x.png) no-repeat center;background-size:18px 18px;}' +
				'.simple-search-box .my-label-area li:hover .label-del{display:block;}' +
				'.simple-search-box .my-label-area li:last-child{border-bottom:0;}' +
				'.new-search-box{min-height:265px;z-index:11;-moz-transition:top 0.3s;-webkit-transition:top 0.3s;transition:top 0.3s;position:absolute;1opacity:0;top:-2000px;left:50%;margin-left:-123px;width:330px;padding:8px;border:5px solid #6ba4eb;background:#fff;}' +
				'.new-search-box.show{1opacity:1;top:105px;}' +
				'.new-search-box .new-search-head{height:30px;line-height:26px;border-bottom:1px dotted #e7e7e7;text-indent:5px;position:relative;}' +
				'.new-search-box .new-search-head .close{background:url(' + RESOURCE_URL +'buttons/close4.png) center no-repeat;background-size:12px 12px; position:absolute;width:30px;height:30px;right:0;top:0;cursor:pointer;}' +
				'.new-search-box .new-search-item{float:left;width:164px;padding-top:10px;}' +
				'.new-search-box .condition-area{display:inline-block;position:relative;vertical-align:middle;}' +
				'.new-search-box .label{display:inline-block;vertical-align:middle;width:45px;white-space:nowrap;overflow:hidden;padding-right:3px;text-align:right;}' +
				'.new-search-box input[type="text"]{width:100px;height:16px;border:1px solid #cfcfcf;line-height:16px;padding:2px;}' +
				'.new-search-box input.open-column-input{cursor:pointer;}' +
				'.new-search-box .condition-area .current-condition-show{text-indent:5px;cursor:pointer;display:block;width:86px;border:1px solid #cfcfcf;line-height:22px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;position:relative;}' +
				'.new-search-box .condition-area .current-condition-show:after{position:absolute;top:10px;right:8px;content:"";border:3px solid transparent;border-top-color:#000;}' +
				'.new-search-box .condition-area ul{max-height:207px;overflow-y:auto;position:absolute;width:100%;background:#fff;display:none;z-index:10;}' +
				'.new-search-box .condition-area:hover ul{display:block;}' +
				'.new-search-box .condition-area li{padding-right:5px;line-height:22px;border:1px solid #cfcfcf;text-indent:8px;border-top:0;font-size:12px;}' +
				'.new-search-box .condition-area li:hover{background:#ddeefe;cursor:pointer;}' +
				'.new-search-box .condition-area li a{display:block;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;}' +
				'.new-search-box .new-search-controll{padding-top:10px;clear:left;}' +
				'.new-search-box .search-submit-btn,.new-search-box .search-delete-btn{margin-left:53px;border-radius:2px; display:inline-block; height:26px; line-height: 26px;padding:0;color:#fff; font-size:12px; cursor: pointer; border: none; width:70px;text-align:center;background:-webkit-linear-gradient(#6EA5E8, #5192E2); background:-moz-linear-gradient(#6EA5E8, #5192E2); background:linear-gradient(#6EA5E8, #5192E2); color:#fff; }' +
				'.new-search-box .search-submit-btn:hover,.new-search-box .search-delete-btn:hover{background:-webkit-linear-gradient(#629EE7, #357ed3); background:-moz-linear-gradient(#629EE7, #357ed3); background:linear-gradient(#629EE7, #357ed3); border:none;}' +
				'.new-search-box .search-text-btn{cursor:pointer;color:#8bb5db;text-decoration:underline;font-size:12px;margin-left:10px;font-family:Arial, Helvetica, sans-serif;} ' +
				'.new-search-box .search-text-btn:hover{color:#5899d3;}' +
				'.new-search-box .new-search-deletearea{display:none;margin-top:10px;border-top:1px dotted #e7e7e7;}' +
				'.new-search-box .search-delete-btn{width:100px;}' +
				'.new-search-box .new-search-title,.new-search-box .new-search-time,.new-search-box .new-search-weight{width:100%;}' +
				'.new-search-box .new-search-other,.new-search-box .new-search-time,.new-search-box .new-search-weight{border-bottom:1px dotted #e7e7e7;padding-bottom:10px;}' +
				'.new-search-box .new-search-title input[type="text"]{width:265px;}' +
				'.new-search-box .define-condition-area{display:none;}' +
				'.new-search-box .define-condition-area.show{display:inline-block;}' +
				'.new-search-box .define-condition-area .start-time{margin-right:3px;}' +
				'.new-search-box .define-condition-area input{width:76px;margin-left:3px;}' +
				'.new-search-box .define-weight-input{display:inline-block;}' +
				'.new-search-box .define-weight-input input{width:24px;text-align:center;margin-right:2px;vertical-align:middle;}' +
				'.new-search-box .define-weight-box{display:inline-block;font-family:Arial, Helvetica, sans-serif;}' +
				'.new-search-box .define-weight-box i{font-style:normal;padding-left:2px;}' +
				'.new-search-box .define-weight-box i:first-child{padding:0;padding-right:4px;}' +
				'.new-search-box .define-weight-slider{display:inline-block;width:65px;vertical-align:middle;height:6px;background:#6d6d6d;}' +
				'.new-search-box .ui-widget-content{border:0!important;}' + 
				'.new-search-box .ui-widget-content .ui-state-default{border:0!important;top:-3px!important;width:12px!important;height:12px!important;background:-webkit-linear-gradient(#d0cfcf,#9d9d9d)!important;background:-moz-linear-gradient(#d0cfcf,#9d9d9d)!important;border-radius:50%!important;}' +
				'.new-search-box .ui-slider-horizontal .ui-slider-range{background:#6d6d6d;}' +
				'.new-search-box .search-label-content{background:#f8f8f8;padding:5px 10px;}' +
				'.new-search-box .search-label-item{float:left;min-width:150px;color:#868686;padding-bottom:5px;}' +
				'.new-search-box .search-label-item .label-name{display:inline-block;text-align:right;width:50px;padding-right:3px;}' +
				'.new-search-box .search-label-item .label-value{max-width: 250px;height:20px;overflow: hidden;display: inline-block;white-space: nowrap;vertical-align: middle;text-overflow: ellipsis;}' +
				'.new-search-box .search-label-title{}' +
				'.new-search-box .search-label-controll{padding:10px;}' +
				'.new-search-box .search-label-controll .search-label-input{width:100%;margin-top:5px;}' +
				'.new-search-box .search-label-controll .search-submit-btn{margin-left:0;margin-top:10px;}' +
				'.new-column-box{width:100%;height:100%;display:none;}' +
				'.new-column-box .search-submit-btn{position:absolute;top:0;width:50px;right:35px;}' +
				'.new-column-box .new-site-box{position:relative;}' +
				'.new-column-box .new-site-box ul{z-index:10;background:#f2f2f2;position:absolute;top:31px;display:none;width:170px;max-height:240px;overflow:hidden;overflow-y:auto;}' +
				'.new-column-box .new-site-box li{border-bottom:1px dotted #ccc;line-height:29px;}' +
				'.new-column-box .new-site-box li.current{}' +
				'.new-column-box .new-site-box label{display:block;}' +
				'.new-column-box input[type="radio"],.new-column-box .publish-checkbox{vertical-align:middle;margin-right:8px;}' + 
				'.new-column-box input[type="radio"]:focus,.new-column-box .publish-checkbox:focus{box-shadow:none;}' + 
				'.new-column-box .site-switch-btn{position:relative;cursor:pointer;display:inline-block;margin-left:8px;text-decoration:underline;}' +
				'.new-column-box .switch-loading{position:absolute;right:-2px;top:-2px;display:none;}' +
				'.new-column-box .switch-loading.show{display:block;}' +
				'.new-column-box .new-column-content{width:100%;overflow:hidden;border-left:1px dotted #ccc;border-right:1px dotted #ccc;}' +
				'.new-column-box .new-column-innerList{height:100%;width:2000px;-webkit-transition:margin-left .3s;transition:margin-left .3s;-moz-transition:margin-left .3s;}' + 
				'.new-column-box .new-column-each{width:165px;height:237px;float:left;}' +
				'.new-column-box .new-column-each ul{height:100%;border:1px dotted #ccc;border-top:0;border-left:0;padding-left:4px;max-height:236px;overflow:hidden;overflow-y:auto;}' +
				'.new-column-box .new-column-each:last-child ul{border-right:0;}' +
				'.new-column-box .new-column-each li{cursor:pointer;border-bottom:1px dotted #ccc;line-height:28px;} ' +
				'.new-column-box .new-column-each li:hover{background:#F9F9F9;}' +
				'.new-column-box .new-column-each li.open{background:#e6f3fc;}' +
				'.new-column-box .new-column-each li:last-child{border-bottom:0;} '+ 
				'.new-column-box .publish-name{display:inline-block;width:100px;overflow:hidden;white-space:nowrap;text-overflow: ellipsis;vertical-align:middle;}' +
				'.new-column-box .no-child .publish-child{display:none;}' +
				'.new-column-box .publish-child{float:right;margin-right:10px;font-size:0px;width:5px;background:url(' + RESOURCE_URL + 'arrow_list.gif) no-repeat center;} ' +
				'', 
			cssInited : false
	};
	
	/*高级搜索弹窗组件*/
	$.widget('new_search.search_pop', $.new_search.base,{
		options : {
			target : 'nodeFrame',
			cache_key : 'advanceSearch'
			/*
			search_ajax_url : './run.php?mid=' + gMid + '&a=advanced_search',	//配置高级搜索接口
			get_label_url : './run.php?mid=' + gMid + '&a=get_searchtag',		//获取标签列表接口
			save_label_url : './run.php?mid=' + gMid + '&a=save_searchtag',		//保存标签接口
			del_label_url : './run.php?mid=' + gMid + '&a=delete_searchtag',	//删除标签接口
			site_url : './get_publish_content.php?a=get_site',					//获取站点接口
			column_url : './fetch_column.php'									//获取栏目接口
			*/
		},
		_create : function(){
			this._super();
			this._template( 'search_pop', pluginInfo.template, pluginInfo, this.element, { target : this.options.target } );
			this.search_pop = this.element.find('.new-search-box');
			this.search_pop_form = this.search_pop.find('form:first');
			this.search_pop_form_content = this.search_pop_form.find('.new-search-form-content');
			this.content_box = this.search_pop.find('.new-search-content');
			this.advance_hidden_box = this.search_pop.find('.advance-search-hidden');
			this.controll_box = this.search_pop.find('.new-search-controll');
			this.search_deletearea = this.search_pop.find('.new-search-deletearea');
			this.search_deletearea_form = this.search_deletearea.find('form');
		},
		
		_init : function(){
			this._super();
			this._on( {
				'click .condition-area li ' : '_click',
				'click .simple-search-advance' : '_openAdvance',
				'click .new-search-head .close' : '_close',
				'submit .simple-search-box form' : '_simpleSubmit',
				'submit .advance-search-box form' : '_advanceSubmit',
				'click .open-column-input' : '_openColumnBox',
				'click .search-clear-btn' : '_clearConditionEvent',
				'click .search-create-btn' : '_createLabel',
				'click .search-delete-btn' : '_deleteByCondition'
			} );
			this._AdvanceSearchAjax();
		},
		
		_openAdvance : function( event ){
			var self = $(event.currentTarget);
			this.show();
			this._clearfixendtime();
		},
		
		_initHover : function(){
			this.element.find('.condition-area').deferHover();
		},
		
		_click : function( event ){
			var self = $( event.currentTarget ).find('a'),
				id = self.data('id'),
				text = self.text(),
				condition_area = self.closest('.condition-area'),
				define_area = condition_area.next(),
				current_show = condition_area.find('.current-condition-show'),
				ul = condition_area.find('ul'),
				input_hidden = condition_area.find('input[type="hidden"]');
			current_show.text( text );
			ul.hide();
			this._isOpenDefine( id, define_area );
			input_hidden.eq(0).val( id );
			this._isWeightOrTime( self );
		},
		
		_simpleSubmit : function( event, source, key ){
			if( source != 'label' ){
				this.searchtag_hidden_box.empty();
				this.nav_box.find('.label-id-hidden').val('');
			}else{
				this.simple_input_title.val(key);
			}
			this._resetLabelSearch();
			this._setInputHidden( this.simple_hidden_box );
			this._isCommentModule( this.simple_hidden_box );
		},
		
		_advanceSubmit : function(){
			if( this.options.target == 'nodeFrame' ){
				this._setInputHidden( this.advance_hidden_box );
				this._isCommentModule( this.advance_hidden_box );
			}
			this._fixendtime();
			this._resetLabelSearch();
			this._syncTitle('advance');
			this._close();
		},
		
		/*搜索条件中结束日期，根据后台现有逻辑修复搜索不到结束日期那天的数据 在年-月-日 后面加入时分秒23:59:59*/
		_fixendtime : function(){
			this.end_time_inputs = this.search_pop_form_content.find('.end-time');
			this.end_time_inputs.length && this.end_time_inputs.each( function(){
				var is_second = $(this).attr('_second'),
					value = $(this).val();
				$(this).data('oldtime',value);
				value && !is_second && (value += ' 23:59:59');
				$(this).val( value );
			} );
		},
		
		_clearfixendtime : function(){
			if( this.end_time_inputs && this.end_time_inputs.length ){
				this.end_time_inputs.each( function(){
					var value = $(this).data('oldtime');
					$(this).val( value );
				} );
			}
		},
		
		_isCommentModule : function( hidden_box ){
			top['tableName' + gMid ] && $('<input name="tablename" type="hidden" />').val( top['tableName' + gMid ] ).appendTo( hidden_box );
			top['comment_year' + gMid ] && $('<input name="comment_year" type="hidden" />').val( top['comment_year' + gMid ] ).appendTo( hidden_box );
		},
		
		clearAllCondition : function(){
			this._clearCondition();
			this._resetLabelSearch();
			this._syncTitle();
		},
		
		instancePopCondition : function( condition ){
			var _this = this;
			this._clearCondition( this.search_deletearea_form );
			$.each( condition, function( key, value ){
				var input = _this.search_pop_form.find( 'input[name="' + key + '"]' );
				if( input.attr('type') == 'hidden' ){
					var parent = input.closest('.new-search-item'),
						ul = parent.find('.condition-area-list');
					if( ul.length ){
						var current_li = ul.find('li').filter( function(){
							var id = $(this).find('a').data('id');
							return ( id == value );
						} );
						current_li.length && current_li.trigger('click');
					}
					input.val( value );
				}else{
					input.val( value );
					( key == 'pub_column_name' ) && input.attr('title',value);
				}
			} );
			
			/*权重*/
			if( condition['weight_hidden'] && condition['weight_hidden'] == 'other'  ){
				this.element.find( '.new-search-weight' ).hg_search_weight( 'instanceWeight', condition );
			}
		},
		
		_resetLabelSearch : function(){
			this.labelList_box.hg_search_labelList( 'resetLabel' );
		},
		
		_openColumnBox : function(){
			this._toggleColumnBox( true );
		},
		
		_clearConditionEvent : function(){
			this._clearCondition();
		},
		
		_clearCondition : function( form ){
			var _this = this,
				form_area = form || this.search_pop_form;
			form_area.find('.new-search-item').each( function(){
				var list = $(this).find('.condition-area-list li');
				if( list.length ){
					_this._resetInput( $(this) );
					_this._resetSelect( list.eq(0) );
				}else{
					_this._resetInput( $(this) );
				}
			} );
			this.column_widget && this.column_widget.hg_search_column('reset');
		},
		
		_createLabel : function(){
			var _this = this;
			this.search_pop_form_content.hide();
			this.searchDeleteArea && this.search_deletearea.hide();
			if( this.label_widget ){
				this.label_widget.data('show',true).hg_search_labelCreate( 'refresh' );
				return;
			}
			this.label_widget = $('<div />').appendTo( this.search_pop_form ).data('show',true).hg_search_labelCreate({
				form : this.search_pop_form,
				save_label_url : _this.options.save_label_url,
				labelList_widget : _this.labelList_box
			});
		},
		
		_isHasDeleteByCondition : function(){
			var delete_btn_flag = this.content_box.find('.search-delete-conditions');
			if( delete_btn_flag.length ){
				var clone_conditions = delete_btn_flag.clone( true ).show();
				delete_btn_flag.remove();
				this.searchDeleteArea = this.element.find('.new-search-deletearea').show();
				this.searchDeleteArea.find('form').prepend( clone_conditions[0] );
			}
		},
		
		_deleteByCondition : function( event ){
			var self = $( event.currentTarget ),
				form = self.closest('form');
			this._deleteByConditionAjax( form, self );
			
		},
		
		_deleteByConditionAjax : function( form, target ){
			var _this = this;
				search_delete_hidden_box = form.find('.advance-searchdelete-hidden');
			this._setInputHidden( search_delete_hidden_box );
			search_delete_hidden_box.find('input[name="a"]').val('delete');
			var params = form.serializeArray();
			top['tableName' + gMid ] && params.push( { name : 'tablename', value : top['tableName' + gMid ] } );
			top['tableName' + gMid ] && params.push( { name : 'ajax', value : 1 } );
			$.globalAjax( target, function(){
				return $.post( './run.php', params, function( data ){
					data['msg'] && _this._deleteByConditionCallback();
					if( !data['msg'] && data['callback'] ){
						eval( data['callback'] );
					}
				}, 'json' );
			} );
		},
		
		_deleteByConditionCallback : function(){
			var iframe = this.element.find('#' + this.options.target);
			this._close();
			this.clearAllCondition();
			iframe.length && iframe[0].contentWindow.location.reload();
		},
		
		_resetSelect : function( target ){
			target.trigger('click');
		},
		
		_resetInput : function( item ){
			item.find('input[type="text"]').val('');
			item.find('input[type="hidden"]').val('');
		},
		
		_toggleColumnBox : function( bool ){
			var isshow = bool ? true : false,
				column_action = bool ? 'show' : 'hide';
				search_form_action = !bool ? 'show' : 'hide';
			this.search_pop_form[search_form_action]();
			this.column_widget.data('show',isshow)[column_action]();
			this.column_widget.hg_search_column('instanceData');
		},
		
		_closeLabel : function(){
			this.label_widget.data('show',false).hg_search_labelCreate('hide');
			this.search_pop_form_content.show();
			this.searchDeleteArea && this.search_deletearea.show();
		},
		
		_syncTitle : function( currentType ){
			var value = '',
				simple_input = this.simple_input_title,
				advance_input = this.advance_input_title;
			if( currentType == 'simple' ){
				value = simple_input.val( );
				advance_input.length  && advance_input.val( value );
			}else{
				value = ( advance_input.length  && advance_input.val() ) || '';
				simple_input.val( value );
			}
		},
			
		_initSimpleSearch : function(){
			this.nav_box = $('.nav-box:first').addClass('new-nav-box');
			this._template( 'simple_search', pluginInfo.simple_tpl, pluginInfo, this.nav_box, { target : this.options.target }, 'prependTo' );
			this.simple_search_box = this.nav_box.find('.simple-search-box');
			this.simple_hidden_box = this.nav_box.find('.simple-search-hidden');
			this.searchtag_hidden_box = this.nav_box.find('.searchtag-search-hidden');
			this.labelList_box = this.nav_box.find('.my-label-area');
			this.simple_input_title = this.nav_box.find('.simple-input-title');
			this._initLabelSearch();
		},
		
		_initLabelSearch : function(){
			var _this = this;
			this.labelList_box.hg_search_labelList( {
				get_label_url : this.options.get_label_url,
				del_label_url : this.options.del_label_url,
				parent_widget : _this.element
			} );
		},
		
		_setcacheAdvanceSearchAjax : function( result ){
			top.$.globalData.set( this.options.cache_key + gMid, result );
		},
		
		_getcacheAdvanceSearchAjax : function(){
			return top.$.globalData.get( this.options.cache_key + gMid );
		},
		
		_AdvanceSearchAjax : function(){
			var _this = this,
				cache_html = this._getcacheAdvanceSearchAjax();
			if( cache_html ){
				_this._AdvanceSearchAjaxCallback( cache_html );
				return;
			}
			$.get( this.options.search_ajax_url, function( html ){
				_this._AdvanceSearchAjaxCallback( html );
				_this._setcacheAdvanceSearchAjax( html );
			} );
		},
		
		_AdvanceSearchAjaxCallback : function( html ){
			var $result = $(html);
			if( $result.hasClass('new-search-item') ){
				this._initSimpleSearch();
			 	this._initAdvanceSearch( html );
			 	this._initHover();
			 	if( $result.data('nosearch') ){
			 		this.controll_box.hide();
			 	}
			 }else{
			 	this.element.data('isclose', true ); //模块没有配置高级搜索
			 }
		},
		
		_initAdvanceSearch : function( html ){
			this.content_box.html( html );
			this.advance_input_title = this.content_box.find('.new-search-title input');
			this._initWidget();
			this._isHasDeleteByCondition();
		},
		
		_initWidget : function(){
			var _this = this;
			this.element.find('.new-search-item').each( function(){
				var widget = $(this).data('widget');
				if( widget == 'hg_search_column' ){
					var columnNameInput = $(this).find('input[type="text"]'),
						columnIdHidden = $(this).find('input[type="hidden"]');
					_this._initColumnWidget( widget, {
						columnNameInput : columnNameInput,
						columnIdHidden : columnIdHidden,
						site_url : _this.options.site_url,
						column_url : _this.options.column_url
					} );
				}else{
					widget && $(this)[widget]();
				}
			} );
		},
		
		_initColumnWidget : function( widget, options  ){
			this.column_widget = $('<div class="new-column-box"/>').appendTo( this.search_pop );
			this.column_widget[widget](options);
		},
		
		_setInputHidden : function( hidden_box ){
			var input_hiddens = this._getInputHidden();
			hidden_box.empty();
			input_hiddens && this._template( 'input_hidden_tpl', pluginInfo.input_hidden_tpl,pluginInfo,hidden_box, input_hiddens );
		},
		
		setSearchtagInputHidden : function( searchtag_input_hiddens ){
			var searchtag_hiddens = { searchtag_hiddens : searchtag_input_hiddens };
			this.searchtag_hidden_box.empty();
			searchtag_input_hiddens && this._template( 'searchtag_hidden_tpl', pluginInfo.searchtag_hidden_tpl,pluginInfo,this.searchtag_hidden_box, searchtag_hiddens );
		},
		
		_getInputHidden :function(){
			var input_hiddens = null,
				nodeFrame = this.element.find('#' + this.options.target);
			if( nodeFrame.length ){
				var src = nodeFrame.attr('src');
				input_hiddens = this._search2map( src );
			}
			return input_hiddens;
		},
		
		_search2map : function( src ){
			var map = {};
			if( src ){
				var index = src.indexOf('?'); 
				( index > 0 ) && (  src = src.slice( index+1 ) );
				src.split('&').forEach( function( value ){
					value = value.split('=');
					map[value[0]] = value[1];
				} );
			}
			return map;
		},
		
		show : function(){
			this.search_pop.addClass( 'show' );
			this._syncTitle('simple');
			this._createMask();
		},
		
		_close : function(){
			if( this.column_widget && this.column_widget.data('show') ){
				this._toggleColumnBox( false );
				return;
			}
			if( this.label_widget && this.label_widget.data('show')  ){
				this._closeLabel();
				return;
			}
			this.search_pop.removeClass( 'show' );
			this.mask.remove();
		},
		
		_createMask : function(){
			this.mask = $('<div />').css({
				position: 'absolute',
				'z-index': 10,
				top : 0,
				width : '100%',
				height : '100%',
				background : 'rgba(0,0,0,0.1)'
			}).appendTo( 'body' );
		},

		_isOpenDefine : function( id, define_area ){
			if( id == 'other' ){
				define_area.addClass('show');
			}else{
				define_area.removeClass('show');
			}
		},
		
		_isWeightOrTime : function( target ){
			var type = target.data('type'),
				values = target.data('id'),
				el = target.closest('.new-search-item'),
				widget = el.data('widget');
			if( type == 'weight' && values !='other' ){
				var values = values.split(',');
				el[widget]( 'refreshValue',values );
			}
			if( type == 'time' && values != 'other'  ){
				el[widget]( 'reset' );
			}
		},
		
		destroy : function(){
			this.simple_search_box && this.simple_search_box.remove();
			this.search_pop && this.search_pop.remove();
			$.Widget.prototype.destroy.call(this);
		},
		
	});
	
	/*标签列表组件*/
	$.widget( 'new_search.hg_search_labelList', $.new_search.base, {
		options : {
			
		},
		
		_create : function(){
			this.list = this.element.find('ul');
			this.current_show = this.element.find('.current-label-show');
		},
		
		_init : function(){
			this._on( {
				'click .label-del' : '_delete',
				'click li' : '_click'
			} );
			this._initHover();
			this._ajaxLabel();
		},
		
		_initHover : function(){
			this.element.deferHover();
		},
		
		_click : function( event ){
			var self = $(event.currentTarget),
				id = self.data('id'),
				name = self.attr('title'),
				value = self.data('value'),
				key = '';
			if( value &&  ( value.key || value.k ) ){
				key = value.key || value.k;
			}
			self.addClass('current').siblings().removeClass('current');
			this._labelSearch( id, key, value );
			this.current_show.text( name );
			this._instancePopCondition( value );
			this.list.hide();
		},
		
		_instancePopCondition : function( condition ){
			this.options.parent_widget.search_pop( 'instancePopCondition', condition );
		},
		
		resetLabel : function( target ){
			if( !target || ( target && target.hasClass('current') ) ){
				this.current_show.text('我的标签');
			}
			this.list.hide();
			if( !this.list.find('li').length ){
				this.element.hide();
			}
		},
		
		_labelSearch : function( id, key, searchtaginputhiddens ){
			var form = this.element.closest('form');
			this.element.find('.label-id-hidden').val( id );
			this.options.parent_widget.search_pop( 'setSearchtagInputHidden', searchtaginputhiddens );
			form.trigger('submit', ['label',key]);
		},
		
		_delete : function( event ){
			var _this = this,
				item = $( event.currentTarget ).closest('li'),
				id = item.data('id');
			$.globalAjax( item, function(){
				return $.getJSON( _this.options.del_label_url, { id : id }, function(){
					item.remove();
					_this.resetLabel( item );
				} );
			} );
			event.stopPropagation();
		},
		
		_ajaxLabel : function(){
			var _this = this;
			$.getJSON( this.options.get_label_url, function( data ){
				if( $.isArray( data ) && data[0].length ){
					_this.show();
					_this._renderLabel( data[0] );
				}
			} );
		},
		
		_renderLabel : function( data ){
			this._template( 'label_item', pluginInfo.label_item, pluginInfo, this.list, data, 'prependTo' );
		},
		
		addLabel : function( data ){
			this._renderLabel( data );
			this.show();
		},
		
		show : function(){
			this.element.show();
		},
		
		hide : function(){
			this.element.hide();
		}
	} );
	
	/*创建标签组件*/
	$.widget('new_search.hg_search_labelCreate',$.new_search.base,{
		options : {
			form : null,
			condition_item : '.new-search-item',
			labelList_widget : null   
		},
		
		_create : function(){
			this._template( 'label_box', pluginInfo.label_box,pluginInfo,this.element,null );
			this.label_content = this.element.find('.search-label-content');
			this.label_input = this.element.find('.search-label-input');
		},
		
		_init : function(){
			this.refresh();
			this._on( {
				'click .save-label' : '_saveLabel'
			} );
		},
		
		_saveLabel : function( event ){
			var self = $(event.currentTarget),
				form_array = this._handleSaveData(),
				label_value = $.trim( this.label_input.val() );
			if( !label_value ){
				self.myTip( {
					color : 'red',
					string : '请填写标签名'
				} );
				return;
			}
			this._ajaxLabel( self, {
				title : label_value,
				tag_val : JSON.stringify( form_array )
			} );
		},
		
		_ajaxLabel : function( target, param, callback ){
			var _this = this;
			$.globalAjax( target, function(){
				return $.post( _this.options.save_label_url, param, function( data ){
					var data = data[0];
					if( data['errno'] ){
						target.myTip( {
							string : data['errmsg'],
							color : 'red'
						} );
					}else{
						target.myTip( {
							string : '搜索标签创建成功'
						} );
						_this._addLabel( data );
					}
				}, 'json' );
			} );
		},
		
		_addLabel : function( data ){
			var _this = this,
				labelList_widget = this.options.labelList_widget;
			data.id = data.tag_id;
			labelList_widget.hg_search_labelList( 'addLabel', data );
			setTimeout( function(){
				_this.element.find('.close').trigger('click');
			}, 1000 );
		},
		
		_renderLabel : function(){
			var labels = this._handelRenderData();
			this.label_content.empty();
			this._template( 'label_tpl', pluginInfo.label_tpl, pluginInfo, this.label_content, labels );
		},
		
		_handleSaveData : function(){
			var form = this.options.form,
				form_array = form.serializeArray();
			form_array = $.map( form_array , function( value ){
				if( value['name'] != 'a' && value['name'] != 'mid' && value['name'] != '_id' ){
					return value;
				}
			} );
			return form_array;
		},
		
		_handelRenderData : function(){
			var op = this.options,
				items = op.form.find(op.condition_item);
			var labels = items.map( function(){
				var obj = {},
					type = $(this).data('type') || 'normal',
					name = $(this).find('.label').text();
				if( type == 'time' || type == 'weight' || type == 'select' ){
					var id = $(this).find('input[type="hidden"]').val();
					if( id == 'other' && type != 'select' ){
						var start_class = ( type == 'time' ) ? '.start-time' : '.start-weight-input';
							end_class = ( type == 'time' ) ? '.end-time' : '.end-weight-input';
						obj.start = $(this).find( start_class ).val();
						obj.end = $(this).find( end_class ).val();
					}else{
						obj.value = $(this).find('.current-condition-show').text();
					}
					obj.id = id;
				}else{
					obj.value = $(this).find('input[type="text"]').val();
					obj.id = 'normal';
				}
				obj.name = name;
				obj.type = type;
				return obj;
			} ).get();
			return labels;
		},
		
		show : function(){
			this.element.show();
		},
		
		hide : function(){
			this.element.hide();
		},
		
		refresh : function(){
			this.label_input.val('');
			this._renderLabel();
			this.show();
		}
		
	});
	
	/*栏目搜索组件*/
	$.widget('new_search.hg_search_column', $.new_search.base, {
		options : {
			maxColumn : 2,
			eachWidth : 165,
		},
		_create : function(){
			this._template( 'column_box',pluginInfo.column_box, pluginInfo, this.element, null );
			this.site_box = this.element.find('.new-site-box');
			this.site_loading = this.site_box.find('.switch-loading');
			this.site_list = this.site_box.find('ul');
			this.current_site = this.site_box.find('.current-site');
			this.column_inner = this.element.find('.new-column-innerList');
		},
		_init : function(){
			this._on( {
				'click .site-switch-btn' : '_switch',
				'click .new-site-box li' : '_selectSite',
				'click .new-column-each li' : '_click',
				'click .save-button' : '_saveResult'
			} );
			this._initSite();
		},
		
		_switch : function( event ){
			var _this = this,
				self = $( event.currentTarget );
			if( self.data('loading') ) return;
			self.data('loading',true);
			this.site_loading.addClass('show');
			setTimeout( function(){
				_this.site_list.slideToggle( 100, function(){
				self.data('loading', false);
				_this.site_loading.removeClass('show');
			} );
			}, 10 );
		},
		
		_selectSite : function( event ){
			var self = $( event.currentTarget ),
				name = self.data('name'),
				id = self.data('id');
			self.addClass( 'current' ).siblings().removeClass('current');
			self.find('input').attr( 'checked', true );
			this.site_list.hide();
			this._setCurrentSite( name, id );
			this.column_inner.css('margin-left',0);
		},
		
		_click : function( event ){
			var self = $( event.currentTarget ),
				fid = self.data('id'),
				name = self.attr('title'),
				checked = self.find('input').prop('checked');
			$(event.target).is('input') && (checked = !checked);
			if( $(event.target).is('input') || self.hasClass('no-child') ){
				this._columnNormal( { target : self, showName : name, id : fid, checked : checked } );
			}else{
				this._columnDeep( {
					target : self,
					fid : fid,
					showName : name
				} );
			}
		},
		
		_syncChecked : function(){
			var _this = this;
			if( this.columnData ){
				$.each( this.columnData, function( key, value ){
					value && _this.column_inner.find('li[data-id="' + key + '"] input').prop( 'checked', true );
				} );
			}
		},
		
		_saveResult : function(){
			var op = this.options,
				ids = [],
				names = [];
			if( this.columnData ){
				$.each( this.columnData, function( key, value ){
					if( value ){
						ids.push( key );
						names.push( value );
					}
				} );
			}
			op.columnNameInput.val( names.join() ).attr('title',names.join() );
			op.columnIdHidden.val( ids.join() );
			this.element.find('.close').trigger('click');
		},
		
		instanceData : function(){
			var _this = this,
				op = this.options,
				ids = op.columnIdHidden.val().split(','),
				names = op.columnNameInput.val().split(',');
			if( $.isArray( ids ) && ids.length ){
				$.each( ids, function( key, value ){
					_this._handleData( value, names[key] );
				} );
			}
			this._syncChecked();
		},
		
		_handleData : function( key, value ){
			this.columnData = this.columnData || {};
			key && ( this.columnData[key] = value || '' );
		},
		
		reset : function(){
			this.columnData = {};
			this.site_list.find('li:first').trigger('click');
		},
		
		_columnNormal : function( options ){
			var self = options.target,
				showName = options.showName,
				id = options.id;
			self.find('input').prop( 'checked', !options.checked );
			showName = !options.checked ? showName : null;
			this._handleData( id, showName );
		},
		
		_columnDeep : function( options ){
			var self = options.target;
			options.siteid = this.current_site.data('id');
			self.closest('.new-column-each').nextAll('.new-column-each').remove();
			if( self.hasClass('open') ){
				self.removeClass( 'open' );
				this._adjustView();
			}else{
				self.siblings().removeClass('open').end().addClass('open');
				this._ajaxColumn( self, options, this._adjustView );
			}
			this._syncChecked();
		},
		
		_adjustView : function(){
			var total = this.column_inner.find('.new-column-each').size(), hideNum;
			hideNum = total <= this.options.maxColumn ? 0 : total - this.options.maxColumn;
			this.column_inner.css('margin-left', -hideNum * this.options.eachWidth + 'px');
		},
		
		_initSite : function(){
			var _this = this;
			$.getJSON( this.options.site_url, function( json ){
				var data = [];
				$.each( json, function( key, value ){
					var obj = {};
					obj['id'] = key;
					obj['name'] = value;
					data.push( obj );
				} );
				_this._template( 'site_tpl', pluginInfo.site_tpl, pluginInfo, _this.site_list, data );
				_this._setCurrentSite();
			} );
		},
		
		_setCurrentSite : function( value, siteid ){
			var name = value,
				siteid = siteid;
			if( !name ){
				var default_site = this.site_list.find('li:first');
				name = default_site.data('name');
				siteid = default_site.data('id');
			}
			this.current_site.text( name ).data('id',siteid );
			this._initColumn( siteid, 0, name );
		},
		
		_initColumn : function( siteid, fid, name ){
			this.column_inner.empty();
			this._ajaxColumn( this.column_inner, { siteid : siteid, fid : fid, showName : name } );
		},
		
		_ajaxColumn : function( target, param, callback ){
			var _this = this,
				url = this.options.column_url + '?siteid=' + param.siteid + '&fid=' + param.fid;
			$.globalAjax( target, function(){
				return $.getJSON( url, function( json ){
					var data = {};
					data.list = json;
					$.template( 'column_tpl', pluginInfo.column_tpl );
					$.tmpl( 'column_tpl', data, {
						fid : param.fid,
						showName : param.showName
					} ).appendTo( _this.column_inner );
					$.isFunction( callback ) && $.proxy( callback, _this )();
					_this._syncChecked();
				} );
			} );
		}

	});
	
	/*添加人搜索组件*/
	$.widget('new_search.hg_search_creater', {
		options : {
			
		},
		_create : function(){
			
		},
		_init : function(){
			this._initautocomplete();
		},
		_initautocomplete : function(){
			var autoInput = this.element.find('input');
			autoInput.hg_autocomplete();
			autoInput.on( 'autocompleteselect', function( event, ui ){
				$(this).val( ui.item.label );
			} );
		}
	});
	
	/*日期搜索组件*/
	$.widget('new_search.hg_search_time', {
		options : {
			
		},
		_create : function(){
			
		},
		_init : function(){
			this._initDatePicker();
		},
		
		reset : function(){
			this.element.find('input[type="text"]').val('');
		},
		
		_initDatePicker : function(){
			this.element.find('.search-date-picker').hg_datepicker();
		}
	});
	
	/*权重搜索组件*/
	$.widget('new_search.hg_search_weight', {
		options : {
			
		},
		_create : function(){
			this.slider = this.element.find('.define-weight-slider');
			this.start_weight_hidden = this.element.find('.start-weight-hidden');
			this.end_weight_hidden = this.element.find('.end-weight-hidden');
			this.start_weight_input = this.element.find('.start-weight-input');
			this.end_weight_input = this.element.find('.end-weight-input');
		},
		_init : function(){
			this._on( {
				'blur .define-weight-input input' : '_blur'
			} );
			this._initWeightSlider( [0 ,100] );
			this.refreshValue( [0 ,100] );
		},
		
		instanceWeight : function( value ){
			var values = [];
			values[0] = value['start_weight'];
			values[1] = value['end_weight'];
			this.refreshValue( values );
		},
		
		_blur : function( event ){
			var self = $( event.currentTarget ),
				value = parseInt(  self.val() ),
				min = 0,
				max = 0,
				type = 'min';
			if( self.hasClass( 'start-weight-input' ) ){
				min = value;
				max = parseInt( this.end_weight_input.val() );
			}else{
				min = parseInt( this.start_weight_input.val() );
				max = value;
				type = 'max';
			}
			var values = this._validateValue( [min, max], type );
			this.refreshValue( values );
			this._refreshWeightSlider( values );
		},
		
		/*验证权重值是否合法，如不合法置为起始默认值*/
		_validateValue : function( values, type ){
			var min = values[0],
				max = values[1];
			if( type == 'min' ){
				if( !isNaN( min ) && min >0 && min <= max ){
				}else{
					min = 0;
				}
			}else{
				if( !isNaN( max ) && max >min && max <= 100 ){
				}else{
					max = 100;
				}
			}
			return [ min, max ];
		},
		
		/*刷新文本框与隐藏域权重值*/
		refreshValue : function( values ){
			this.start_weight_hidden.val( values[0] );
			this.end_weight_hidden.val( values[1] );
			this.start_weight_input.val( values[0] );
			this.end_weight_input.val( values[1] );
			this._refreshWeightSlider( values );
		},
		
		_refreshWeightSlider : function( values ){
			this.slider.slider( 'values', values ).trigger('slide');
		},
		
		_initWeightSlider : function( values ){
			var _this = this;
			this.slider.slider( {
				animate : true,
				range : true,
				max : 100,
				min : 0,
				values : values,
				slide : function( event, ui ){
					_this.refreshValue( ui.values );
				}
			} );
		}
	});
	
	
	
	
})($);
