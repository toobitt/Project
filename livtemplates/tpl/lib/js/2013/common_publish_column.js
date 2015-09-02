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
					'<div class="new-search-box show advance-search-box clear">' +
					'</div>' +
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
								'<input {{if $item.type == "radio"}}type="radio" name="column"{{else}}type="checkbox"{{/if}}" class="publish-checkbox" /> '+
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
			css : '' +
				'.new-search-box{min-height:265px;z-index:11;-moz-transition:top 0.3s;-webkit-transition:top 0.3s;transition:top 0.3s;margin-left:0;width:495px;padding:8px;border:5px solid #6ba4eb;background:#fff;}' +
				'.new-search-box.show{1opacity:1;top:180px;}' +
				'.new-search-box .new-search-head{height:30px;line-height:26px;border-bottom:1px dotted #e7e7e7;text-indent:5px;position:relative;}' +
				'.new-search-box .new-search-head .close{display:none;background:url(' + RESOURCE_URL +'buttons/close4.png) center no-repeat;background-size:12px 12px; position:absolute;width:30px;height:30px;right:0;top:0;cursor:pointer;}' +
				'.new-column-box{width:100%;height:100%;}' +
				'.new-column-box .search-submit-btn{position:absolute;top:0;width:50px;right:20px;}' +
				'.new-search-box .search-submit-btn,.new-search-box .search-delete-btn{margin-left:53px;border-radius:2px; display:inline-block; height:26px; line-height: 26px;padding:0;color:#fff; font-size:12px; cursor: pointer; border: none; width:70px;text-align:center;background:-webkit-linear-gradient(#6EA5E8, #5192E2); background:-moz-linear-gradient(#6EA5E8, #5192E2); background:linear-gradient(#6EA5E8, #5192E2); color:#fff; }' +
				'.new-search-box .search-submit-btn:hover,.new-search-box .search-delete-btn:hover{background:-webkit-linear-gradient(#629EE7, #357ed3); background:-moz-linear-gradient(#629EE7, #357ed3); background:linear-gradient(#629EE7, #357ed3); border:none;}' +
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
			site_url : './get_publish_content.php?a=get_site',					//获取站点接口
			column_url : './fetch_column.php'									//获取栏目接口
		},
		_create : function(){
			this._super();
			this._template( 'search_pop', pluginInfo.template, pluginInfo, this.element );
			this.search_pop = this.element.find('.new-search-box');
		},
		
		_init : function(){
			var _this = this;
			this._super();
			this._initColumnWidget( 'hg_search_column', {
				columnNameInput : _this.options.columnNameInput || null,
				columnIdHidden : _this.options.columnIdHidden || $({}),
				site_url : _this.options.site_url,
				column_url : _this.options.column_url,
				type : _this.options.type || 'checkbox',
				saveCallback : _this.options.saveCallback || $.noop
			} );
		},
		
		_initColumnWidget : function( widget, options  ){
			this.column_widget = $('<div class="new-column-box"/>').appendTo( this.search_pop );
			this.column_widget[widget](options);
		},
		
		refresh : function(){
			this.column_widget.hg_search_column('instanceData');
		}
		
	});
	
	
	/*栏目搜索组件*/
	$.widget('new_search.hg_search_column', $.new_search.base, {
		options : {
			maxColumn : 3,
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
			this.instanceData();
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
					key && _this.column_inner.find('li[data-id="' + key + '"] input').prop( 'checked', true );
				} );
			}
		},
		
		_saveResult : function(){
			var op = this.options,
				ids = [],
				names = [];
			if( this.columnData ){
				$.each( this.columnData, function( key, value ){
					if( key ){
						ids.push( key );
						names.push( value );
					}
				} );
			}
			op.columnNameInput && op.columnNameInput.val( names.join() ).attr('title',names.join() );
			op.columnIdHidden.val( ids.join() );
			this._trigger('saveCallback',null,[ids]);
		},
		
		instanceData : function(){
			var _this = this,
				op = this.options,
				ids = op.columnIdHidden.val();
			ids && ( ids = ids.split(',') );
			var names = op.columnNameInput ? op.columnNameInput.val().split(',') : '';
			if( $.isArray( ids ) && ids.length ){
				$.each( ids, function( key, value ){
					_this._handleData( value, names ? names[key] : names );
				} );
			}
			this._syncChecked();
		},
		
		_handleData : function( key, value ){
			this.columnData = this.columnData || {};
			if( this.options.type == 'radio' ){
				this.columnData = {};
			}
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
						showName : param.showName,
						type : _this.options.type,
					} ).appendTo( _this.column_inner );
					$.isFunction( callback ) && $.proxy( callback, _this )();
					_this._syncChecked();
				} );
			} );
		}

	});
	
})($);
