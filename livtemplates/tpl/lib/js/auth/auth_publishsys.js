$(function(){
	(function($){
	var publishsysInfo = {
		template : '' + 
			 '<div class="search-box clear">' + 
			 '<div class="search-input"><input type="text" name="keys" placeholder="输入搜索站点" value=""><span class="search-icon"></span></div>' + 
			 '</div>' + 
			 '<div class="siteBox"></div>' +
			 '<div class="page_size"></div>' + 
			 '<ul class="site_result">' + 
			 	'<input type="hidden" class="site-hiddenid" value="{{= hiddenId}}">' + 
			 	'<input type="hidden" class="site-hiddenname" value="{{= hiddenName}}">' +
			 '</ul>' +
			 '',
		site_tpl : '<ul class="site-list clear">' +
				'{{each item}}' + 
					'<li class="site-item {{if is_last > 0}}no-child{{/if}}" _siteid="{{= id}}" _name="{{= name}}"><span class="check-box"><input type="checkbox" class="check-site" /></span>' +
					'<p class="siteTitle overflow">{{= name}}</p>' +
					'{{if is_last == 0}}<span class="column-option" title="设置栏目权限">设置栏目权限</span>{{/if}}' + 
					'<span class="site_status" _status="无权限">无权限</span>' + 
					'</li>' + 
			 	'{{/each}}' +
			 '</ul>',
		result_tpl : '' + 
			'<li _id="{{= id}}" _name="{{= name}}" _siteid="{{= siteId}}" >{{= name}}</li>' +
			'', 
		css: '.search-input{float:right; width:290px; height:32px; line-height:32px; border:1px solid #cfcfcf; margin:10px 16px; border-radius:3px; }' + 
			'.search-box input{width:260px; height:28px; line-height:28px; font-size:14px; text-indent:6px; border-width:0; box-shadow:none; }' +
			'.search-box .search-icon{display:inline-block; width:24px; height:24px; vertical-align:middle; cursor:pointer; background:url("' + RESOURCE_URL + 'auth/role/search.png") no-repeat center 1px; }' +  
			'.site-list{margin:0 10px; }' +
			'.site-list li{position:relative; float:left; width:104px; height:80px; box-sizing:border-box; text-align:center; margin:6px; border:1px solid #f1efef; color:#333; background-color:#f8f8f9; cursor:pointer; }' +
			'.site-list li.no-child, .site-list li.no-child:hover{border-color:#f1efef; background-color:rgba(248, 248, 249, 0.5); }' + 
			'.site-list li:hover{ background-color:#f3f7fe; border-color:#cdddfa; }' +
			'.site-list li.all, .site-list li.all:hover{background-color:#d8e8f5; border-color:#afcae2; }' +
			'.site-list .siteTitle{padding:10px; line-height:30px; }' +
			'.site-list .site_status{color:#6ea5e8; }' +
			'.site-list .column-option{position:absolute; top:0px; right:0px; display:none; width:24px; height:24px; font-size:0; background:url("' + RESOURCE_URL + 'auth/role/info.png") no-repeat center 1px; }' + 
			'.site-list li:hover .column-option{display:block; }' +
			'.site-list .check-box{position:absolute; left:5px; top:5px; display:none; }' + 
			'.site-list .check-box .check-site{height:13px; float:left; }' + 
			'.norelated-site{font-size:16px; color:#999; text-align:center; padding-top:20px; }' +
			'.overflow{text-overflow:ellipsis; overflow:hidden; white-space:nowrap; }' + 
			'.site_result{display:none; }' + 
			'',
		cssInited : false, 
	};
	
	var column_tpl = '' + 
			'<ul class="column-list ${$item.column}">' +
				'{{each item}}' + 
			 	'<li class="column-item {{if is_last > 0}}no-child{{/if}}" _columnid="{{= id}}" _name="{{= name}}">' + 
			 		'<input type="checkbox" class="column-checkbox" />' + 
			 		'{{if is_last == 0}}<span class="icons hook"></span>{{/if}}' +
			 		'<span class="title">{{= name}}</span>' +  
			 	'</li>' + 
			 	'{{/each}}' +
			 '</ul>';
	
	var publishsysColumn = {
		template : '' + 
			'<div class="column-flex m2o-flex">' + 
				'<div class="result empty" _siteid="${$item.siteId}">' + 
					'<p>已选择栏目：</p>' + 
					'<ul class="clear"></ul>' + 
					'<div class="column-empty">显示已选择的栏目</div>' + 
				'</div>' +
				'<input type="hidden" class="auth_id" value="" />' + 
				'<input type="hidden" class="auth_name" value="" />' + 
				'<div class="column-box m2o-flex-one">'  + 
					column_tpl + 
				'</div>' + 
			'</div>' + 
			'',
		
		result_tpl : '' + 
			'<li _columnid="{{= id}}" _name="{{= name}}" >{{= name}}</li>' +
			'',
		css: '.column-flex{height:380px; }' +  
			'.column-flex .result{width:180px; border-right:10px solid #6ea5e8; }' + 
			'.column-flex .result p{line-height:40px; font-size:14px; color:#888; margin-left:5px; }' + 
			'.column-flex .column-empty{line-height:260px; font-size:16px; text-align:center; color:#dedede; background:url("' + RESOURCE_URL + 'auth/role/column.png") no-repeat center 20%; }' + 
			'.column-item{line-height:38px; margin:0 10px; border-top:1px solid #dbdee3; }' +
			'.column-father > .column-item:last-child{border-bottom:1px solid #dbdee3; }' +
			'.column-father > .column-item:first-child{border-top-width:0; }' +
			'.column-item.no-child:hover{background-color:#f9f9f9; }' + 
			'.column-item .icons{width:20px; height:20px; display:inline-block; vertical-align:middle; }' + 
			'.column-item .hook{background:url("' + RESOURCE_URL + 'auth/role/arrow-left.png") no-repeat center 1px; }' + 
			'.column-item.open .hook{background-image:url("' + RESOURCE_URL + 'auth/role/arrow-bottom.png"); }' + 
			'.column-item .column-checkbox{height:13px; margin:0 4px; }' + 
			'.column-item .title{display:inline-block; padding-left:6px; }' +
			'.column-item .column-child{display:none; }' + 
			'.column-item.open .column-child{display:block }' + 
			'.column-father{margin-bottom:20px; max-height:360px; overflow-y:auto; overflow-x:hidden; }' +
			'.result ul{height:340px; overflow-y:auto; overflow-x:hidden; padding:0 10px; }' + 
			'.result.empty ul{height:auto}' + 
			'.result li{float:left; height:28px; margin:5px; padding:0 20px; line-height:28px; color:#fff; text-align:center; background-color:rgba(110, 165, 232, 0.8); border-radius:15px; }' + 
			'.result .column-empty{display:none; }' + 
			'.result.empty .column-empty{display:block; }' + 
			'',
		cssInited : false, 
	};
	
	$.widget('flatpop.publishsys', $.flatpop.base, {
		options : {
			site_url : './fetch_publishsys_column.php',
			columnId : 'column-pop',
			publishsys : '',
			unique : ''
		},
		
		_create : function(){
			this._super();
			this.auth = ['无权限', '栏目权限', '站点权限'];
		},
		
		_init : function(){
			this._super();
			this._on({
				'click .site-item' : '_options',
				'click .search-icon' : '_search',
				'click .column-option' : '_toggleSite'
			})
			this._initTmpl();
			this._initAjax();
		},
		
		_initTmpl : function(){
			var publishsys = this.options.publishsys, siteInfo = {},
				AsiteId = [], AsiteName = [];
			if( publishsys && publishsys.site ){
				$.each(publishsys.site, function(key, value){
					AsiteId.push( value.siteid );
					AsiteName.push( value.name );
				});
				siteInfo.hiddenId = AsiteId.join(',');
				siteInfo.hiddenName = AsiteName.join(',');
			}
			this._template('auth_publishsys', publishsysInfo, this.body, siteInfo);
			this.siteBox = this.body.find('.siteBox');
			this.resultBox = this.body.find('.site_result');
			this.searchBox = this.body.find('.search-input');
			this.sitehiddenId = this.body.find('.site-hiddenid');
			this.sitehiddenName = this.body.find('.site-hiddenname');
		},
		
		_toggleSite : function( event ){
			var self = $(event.currentTarget),
				box = self.closest('.site-item'),
				op = this.options, result = {},
				_this = this,
				id = box.attr('_siteid');
			if( box.hasClass('no-child') ){
				return;
			}
			var popTitle = box.attr('_name');
			
			var result_li = this.resultBox.find('li[_siteid="' + id + '"]');
			if( result_li.length ){
				var ids = [], names = []
				result_li.each(function(){
					ids.push( $(this).attr('_id') );
					names.push( $(this).attr('_name') );
				});
				result.ids = ids.join(',');
				result.names = names.join(',');
				
				if( result_li.length > 1 ){
					result_li.remove();
					this._columnData(result.ids, result.names, id);
				}
			}
			
			if( !this.element.data('init') ){
				var configInfo = {
					unique : op.unique,
					id : op.unique + '-column',
					popTitle : popTitle,
					siteId : id,
					ptop : '306',
					resultColumn : result
				};
				configInfo.savePop = function( event ){
					var dom = $(event.currentTarget);
					var ids = dom.find('.auth_id').val(),
						names = dom.find('.auth_name').val(),
						siteId = dom.find('.result').attr('_siteid');
					var my = _this.element.find('.site-item[_siteid="' + siteId + '"]');
					if( ids && my.hasClass('all') ){
						my.trigger('click');
					}
					_this._columnData( ids, names, siteId );
    			};
    			configInfo['column_url'] = op.site_url;
    			var columnPop = $.modalPop( configInfo.id );
    			columnPop.publishsysColumn( configInfo );
    			this.element.data('init', true);
			}else{
				$('#' + op.unique + '-column' ).publishsysColumn('refresh', {
					popTitle : popTitle,
					siteId : id,
					resultColumn : result
				})
			}
		},
		
		_search : function(){
			var val = this.searchBox.find('input').val();
			this._initAjax( val );
		},
		
		_options : function( event ){
			event.stopPropagation();
			var self = $(event.currentTarget),
				site_status = self.find('.site_status');
				str_status = site_status.attr('_status');
			if( $(event.target).is('.column-option') ){
				return; 
			}
			var siteId = self.attr('_siteid'),
				name = $.trim( self.find('.siteTitle').html() );
			var checked = self.hasClass('all');
			self[(checked ? 'remove' : 'add') + 'Class']('all');
			if( str_status != '站点权限' ){
				self.find('.site_status').html( checked ? str_status : '站点权限' );
			}else{
				var siteDom = this.resultBox.find('li[_siteid="' + siteId + '"]'),
					change_status = siteDom.length ? '栏目权限' : '无权限';
				self.find('.site_status').html( checked ? change_status : str_status );
			}
			
			this._siteData(siteId, name);
		},
		
		_siteData : function( siteId, name ){
			var siteid =  this.sitehiddenId.val(),
				sitename = this.sitehiddenName.val(),
				Asiteid = siteid.split(','),
				Asitename = sitename.split(',');
			var index = $.inArray(siteId, Asiteid);
			if( siteid ){
				if(  index > -1 ){
					Asiteid.splice( index, 1 );
					Asitename.splice( index, 1 );
				}else{
					Asiteid.push( siteId );
					Asitename.push( name );
				}
				this.sitehiddenId.val( Asiteid.join(',') );
				this.sitehiddenName.val( Asitename.join(',') );
			}else{
				this.sitehiddenId.val( siteId );
				this.sitehiddenName.val( name );
			}
		},
		
		_columnData : function(ids, names, siteId){
			var my_result = this.resultBox.find('li[_siteid="' + siteId + '"]');
			if( my_result.length ){
				my_result.attr({
					_id : ids,
					_name : names,
					_siteid : siteId
				}).html( names );
			}else{
				this.resultBox.append( $.tmpl( publishsysInfo.result_tpl, {
					id : ids,
					name : names,
					siteId : siteId
				} ) );
			}
			this._column_sync();
		},
		
		_resultData : function( column ){
			var _this = this, AsiteId = [];
			this.siteBox.find('li').each(function(){
				var $this = $(this), info = {}
					siteId = $this.attr('_siteid');
				var key = siteId.split('_')[0].substring(4);
				AsiteId.push({
					key : key,
					siteId : siteId
				});
			});
			$.each(column, function(k, v){
				var siteid = $.map(AsiteId, function(value){
					if( v.fegmentid == value.key ){
						return value.siteId;
					}
					return;
				});
				v.siteId = siteid[0];
			}); 
			this.resultBox.append( $.tmpl( publishsysInfo.result_tpl, column ) );
			this._column_sync();
		},
		
		_column_sync : function(){
			var _this = this;
			this.resultBox.find('li').each(function(){
				var siteId = $(this).attr('_siteid');
				var dom = _this.siteBox.find('li[_siteid="' + siteId + '"]');
				if( $(this).attr('_id') ){
					dom.addClass('selected');
					_this._auth(dom, 1);
				}else if( !dom.hasClass('all') ){
					_this._auth(dom, 0);
				}
			});
		},
		
		_site_sync : function(){
			var _this = this;
			var siteId = this.sitehiddenId.val(),
				siteName = this.sitehiddenName.val();
			if( siteId ){
				var AsiteId = siteId.split(',');
				this.siteBox.find('li').each(function(){
					var siteid = $(this).attr('_siteid');
					if( $.inArray(siteid, AsiteId) > -1){
						$(this).addClass('all');
						_this._auth($(this), 2);
					}
				});
			}
		},
		
		_auth : function( dom, code ){
			dom.find('.site_status').attr('_status', this.auth[code]).html( this.auth[code] );
		},
		
		_initAjax : function(key, page, page_num){
			var op = this.options,
				_this = this;
			var info = {};
			var publishsys = this.options.publishsys;
			page = page || 1;
			key && (info.key = key);
			info.count = page_num || 15;
			info.offset = (page - 1) * info.count;
			
			$.globalAjax(this.body, function(){
				return $.getJSON( op.site_url, info, function( data ){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}else{
						data && _this._draw( data );
						data && data.page && _this._initPage( data.page );
						if( publishsys && publishsys.column && !_this.resultBox.find('li').length){
							_this._resultData( publishsys.column );
						}
						_this._site_sync();
						_this._column_sync();
					}
				});
			});
		},
		
		_draw : function( data ){
			this._template('auth_site', publishsysInfo.site_tpl, this.siteBox.empty(), data);
			if( $.isArray( data.item ) && !data.item.length || !data.item){
				$('<p class="norelated-site">暂无相关匹配站点</p>').appendTo( this.siteBox.find('ul') );
			}
		},
		
		_initPage : function( option ){
			var page_box = this.element.find('.page_size'),
				_this = this;
			option.show_all = false;
			option.next_btn = true;
			if(page_box.data('init')){
				page_box.page('refresh',option);
			}else{
				option['page'] = function( event, page, page_num ){
					_this._refresh(page, page_num);
				}
				page_box.page( option );
				page_box.data('init', true);
			}
		},
		
		_refresh : function( page, page_num ){
			var val = this.searchBox.find('input').val();
			this._initAjax( val, page, page_num );
		},
	});
	
	$.widget('flatpop.publishsysColumn', $.flatpop.base, {
		options : {
			column_url : '',
			siteId : '',
			popTitle : ''
		},
		
		_create : function(){
			this._super();
		},
		
		_init : function(){
			this._super();
			this._on({
				'click .column-item' : '_select',
				'click .result li' : '_move',
			});
			this._ajax(null, 'father');
		},
		
		_select : function( event ){
			var op = this.options,
				_this = this;
			var self = $(event.currentTarget),
				columnid = self.attr('_columnid'),
				columnname = self.attr('_name');
			var checked = self.children('input').prop('checked');
			$(event.target).is('input') && (checked = !checked);
			if( $(event.target).is('input') || self.hasClass('no-child')){
				self.children('input').prop('checked', !checked);
				checked ? this._remove( columnid ) : this._add( columnid, columnname );
				
				if( self.find('.column-child').length && !checked ){
					self.find('.column-child').find('li').each(function(){
						var $this = $(this);
						if( $this.find('input').prop('checked') ){
							$this.find('input').prop('checked', false);
							_this._remove( $this.attr('_columnid') );
						}
					});
				}else if( self.closest('.column-list').hasClass('column-child') ){
					var father = self.closest('.column-item.open');
					if( father.children('input').prop('checked') ){
						father.children('input').prop('checked', false);
						this._remove( father.attr('_columnid') );
					}
				}
				
			}else{
				if( !self.data('init') ){
					this._ajax( columnid, 'child' );
					self.data('init', true);
				}
				if( !self.hasClass('open') ){
					self.siblings().removeClass('open').end().addClass('open');
				}else{
					self.removeClass('open');
				}
			}
			event.stopPropagation();
		},
		
		_move : function( event ){
			var self = $(event.currentTarget);
			var columnid = self.attr('_columnid');
			this._remove( columnid );
			this._sync();
		},
		
		_remove : function( id ){
			this.result.find('li[_columnid="' + id + '"]').remove();
			if( !this.result.find('li').length ){
				this.result.addClass('empty');
			}
			this._save();
		},
		
		_add : function(id, name){
			this.result.hasClass('empty') && this.result.removeClass('empty');
			var data = {
				id : id,
				name : name
			};
			this.result.find('ul').append( $.tmpl( publishsysColumn.result_tpl, data ) );
			this._save();
		},
		
		_save : function(){
			var ids = [], names = []; 
			this.result.find('li').each(function(){
				var $this = $(this);
				ids.push( $this.attr('_columnid') );
				names.push( $this.attr('_name') );
			});
			this.hiddenId.val( ids.join(',') );
			this.hiddenName.val( names.join(',') );
		},
		
		_ajax : function( siteId, type, results ){
			var op = this.options,
				_this = this;
			siteId = siteId || op.siteId;
			column_url = op.column_url + '?fid=' + siteId;
			results = results || this.options.resultColumn;
			
			var father_box = $('#' + op.unique + '-pop' ).find('li.site-item[_siteid="' + siteId+'"]');
			
			$.globalAjax(father_box, function(){
				return $.getJSON( column_url, function( data ){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}else{
						data && data.item && (type == 'father') ? _this._column( data, siteId, results ) : _this._child( data, siteId );
					}
				});
			});
		},
		
		_sync : function(){
			var _this = this;
			this.columnBox.find('li').find('input').prop('checked', false);
			this.result.find('li').each(function(){
				var id = $(this).attr('_columnid');
				_this.columnBox.find('li[_columnid="' + id + '"] > input').prop('checked', true);
			});
		},
		
		_result : function( param ){
			var arr_id = param.ids.split(','),
				arr_name = param.names.split(',');
			var arr_result = [];
			$.each(arr_id, function(k, v){
				arr_result.push({
					id : v,
					name : arr_name[k]
				});
			})
			this.result.find('ul').append( $.tmpl( publishsysColumn.result_tpl, arr_result ) );
			this.result.removeClass('empty');
			this._save();
			this._sync();
		},
		
		_column : function( data, siteId, results ){
			this._template('column_publishsys', publishsysColumn, this.body.empty(), data, {
				siteId : siteId,
				column : 'column-father'
			});
			this.result = this.body.find('.result');
			this.columnBox = this.body.find('.column-box');
			this.hiddenId = this.body.find('.auth_id');
			this.hiddenName = this.body.find('.auth_name');
			if( results && !$.isEmptyObject( results ) && results.ids){
				this._result( results );
			}
			this.show();
		},
		
		_child : function( data, siteId ){
			var father = this.columnBox.find('.column-father').children('.column-item[_columnid=' + siteId + ']');
			$.each(data.item, function(k, v){
				v.is_last = 1;
			});
			this._template('column_child', column_tpl, father, data, {
				column : 'column-child'
			});
			this._sync();
		},
		
		refresh : function( param ){
			this._ajax( param.siteId, 'father', param.resultColumn );
			this.element.find('.modal-title').html( param.popTitle );
		},
	});
})(jQuery);

});
