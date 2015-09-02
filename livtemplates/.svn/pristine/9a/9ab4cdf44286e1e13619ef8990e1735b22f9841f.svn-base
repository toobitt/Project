(function ($) {
	
	var publis_li_tpl = 
		'<li _id="${id}" _fid="${id}" title="{{if $item.showName}}${$item.showName} > {{/if}}${name}" _name="${name}" _showName="{{if $item.showName}}${$item.showName} > {{/if}}${name}" class="one-column {{if +is_last}}no-child{{/if}}">'+
			'<input type="radio" class="publish-radio" {{if $data.is_auth == 2}}style="visibility: hidden;"{{/if}} name="radio"/ > '+
			'<span class="publish-name">${name}</span>'+
			'<span class="publish-child">&gt;</span>'+
		'</li>';
	var result_tpl =
		'<li _id="${id}" _name="${name}" _siteid="${$item.siteid}" title="${showName}" class="result_show" {{if $data.is_auth == 2}}style="visibility: hidden;"{{/if}}>'+
			'<input type="radio" checked="checked" class="publish-checkbox" />'+
			'<span>${showName}</span>'+
		'</li>';
	var load_more_tpl = 
		'<li class="load-more-column">' +
			'更多'+
			'<span class="load-more-column-icon"></span>' +
			'<img class="load-more-column-loading" src="' + RESOURCE_URL +'loading2.gif">' +
		'</li>'		
    var defaultOptions = {
    	maxColumn: 2,
    	eachWidth: 165,
    	php: 'fetch_column.php',
    	change: $.noop,
    	count: 20,
    	getUrl : function(nodevar ,node){
    		if(nodevar){
    			return 'route2node.php?mid=' + gMid + '&nodevar=' +nodevar+ '&fid='+ 0 + '&ac=publish';
    		}else{
    	        return 'route2node.php?mid=' + gMid + '&nodevar=' +node+ '&fid='+ 0 + '&ac=publish';		
    		}
	}
    };
	function move_Publish(el, options) {
		var self = this;
		this.options = options = $.extend({}, defaultOptions, options);
		this.cache = new AjaxCache;
		this.el = el;
		this.result = el.find('.publish-result ul');
		this.innerList = el.find('.publish-inner-list');
		this.hiddenInput = el.find('.publish-hidden');
		this.hiddenNameInput = el.find('.publish-name-hidden');
		this.nodevar = '';
		this.gAjaxId = 0;
		
		this.innerList
			.on('click', 'li.one-column', function (e) {
				var li = $(this), checked, id, name, showName, siteName;
				//点击时input的状态，如果点的是input要取反
				checked = li.find('input').prop('checked');
				$(e.target).is('input') && (checked = !checked);
				
				id = li.attr('_id');
				name = li.attr('_name');
				showName = li.attr('_showName') ||  name;
				if ( $(e.target).is('input') || li.hasClass('no-child') ) {
					if ( li.find('input').css('visibility') == "hidden" ) return;//没有权限return
					//更新result区域
					li.find('input').prop('checked', !checked);
					siteName = el.find('.publish-site-current').text();
					if(li.attr('_showName')){
						showName = siteName +  li.attr('_showName');
					}else{
						showName = siteName  + name;
					}
					checked ? 
						self.removeResult(id) : 
						self.addResult(id, name, showName);
				} else {
					//加子分类
					li.closest('.publish-each').nextAll('.publish-each').remove();
					if ( !li.hasClass('open') ) {
						li.siblings().removeClass('open').end().addClass('open');
						self.loadNewColumns(self.siteid, id, showName);
					} else {
						li.removeClass('open');
						self.adjustView();
					}
				}
			})
			
			.on('click', '.load-more-column', $.proxy(this.loadMore, this));
		this.result
			.on('click', 'li', function (e) {
				self.removeResult( $(this).attr('_id') );
				self.syncSelected();
			});
		
		this.syncSelected();
		this.reinitView();
		this.checkAndAddmore();
		this.options.change.call(self.el);
	}
	$.extend(move_Publish.prototype, {
		unique: 0,
		currentGuard: function(fetch) {
				//为ajax请求生成当时的一个哨兵
			if (!fetch) ++this.ajaxid;
			return this.ajaxid + '-' + this.depth;
		},
		reinit: function(ids , nodevar) {
			this.nodevar = nodevar;
			this.hiddenNameInput.val(ids);
			var model = this.model, _this = this;
			this.cache.get(this.options.getUrl(nodevar ,this.model.node), {
				after: function(data) {
					_this.result.closest('.publish-result').addClass('empty');
					if (_this.model !== model) return;
					_this.addChild(data || [], '', { reset: true });
				},
				type: 'json'
			});
		},
		syncSelected: function () {
			var self = this;
			this.innerList.find('li').find('input').prop('checked', false);
			this.result.find('li').each(function () {
				var id = $(this).attr('_id');
				self.innerList.find('li[_id="'+ id +'"] input').prop('checked', false);
				self.result.find('li[_id="'+ id +'"] input').remove();
				self.result.closest('.publish-result').addClass('empty');
			});
		},
		removeResult: function (id) {
			this.result.find('li[_id="'+ id +'"]').remove();
			if ( this.result.find('li').size() == 0 ) {
				this.result.closest('.publish-result').addClass('empty');
			}
			this.saveResult();
		},
		addResult: function (id, name, showName, options) {
			var data;
			if (Array == id.constructor) {
				data = id;
				options = name;
			} else {
				data = {
					id: id,
					name: name,
					showName: showName
				};
			}
			options = options || {};
			if (options.reset) {
				this.result.empty();
			}
			
			this.result.append($.tmpl(result_tpl, data, { siteid: this.siteid }));
			if ( this.result.find('li').size() == 0 ) {
				this.result.closest('.publish-result').addClass('empty');
			} else if(this.result.find('li').size() > 1){
				this.result.find('li').prevAll().remove();
				this.result.closest('.publish-result').removeClass('empty');
			}
			else {
				this.result.closest('.publish-result').removeClass('empty');
			}
			this.saveResult();
		},
		saveResult: function () {
			var ids = [], names = [];
			this.result.find('li').each(function () {
				ids.push($(this).attr('_id'));
				names.push($(this).attr('_name'));
			});
			this.hiddenInput.val(ids.join(','));
			
			this.options.change.call(self.el);
		},
		loadMore: function(e) {
			var _this = this;
			var more = $(e.currentTarget);
			var fid = more.prev().attr('_fid');
			var offset = more.siblings('.one-column').length;
			var name = more.prev().attr('_name');
			var showName = more.prev().attr('_showName') || name;
			showName = showName.replace(name, '');
			
			this.cache.get(this.getUrl(this, fid, offset), {
				type: 'json',
				after: after
			});
			more.addClass('loading');
			function after(data) {
				more.removeClass('loading');
				var len = data.length;
				var els = $.tmpl(publis_li_tpl, data, {
					showName: showName,
					fid: fid
				});
				more.before(els);
				_this.syncSelected();
				if ( _this.options.count > len ) {
					more.remove();
				}
			}
		},
		loadNewColumns: function (siteid, id, showName, cb) {
			var self = this, ajaxId = ++this.gAjaxId;
			var url = this.getUrl(siteid, id);
			this.cache.get(url, {
				before: function() {
					self.innerList.append('<div class="publish-each publish-wait"></div>')
				},
				after: function(data) {
					if ( self.gAjaxId != ajaxId ) return;
					self.innerList.find('.publish-wait').remove();
					self.addChild(data, showName, { fid: id });
					cb && cb();
				},
				type: 'json'
			});
		},
		addChild: function (data, showName, options) {
			if ( (options || {}).reset ) {
				this.innerList.empty();
			}
			var each_ = $('<div class="publish-each column_show"><ul></ul></div>');
			options.showName = showName;
			each_.find('ul').append($.tmpl(publis_li_tpl, data, options));
			this.innerList.append(each_);
			if ( this.options.count <= data.length ) {
				each_.find('ul').append(load_more_tpl);
			}
			this.adjustView();
			this.syncSelected();
		},
		
		checkAndAddmore: function() {
			var each = this.el.find('.publish-each:eq(0)');
			
			if (each.length) {
				var lis = each.find('.one-column');
				if ( this.options.count <= lis.length ) {
					each.find('ul').append(load_more_tpl);
				}
			}
		},
		
		getUrl: function(id1 , id2 ,offset) {
			if(this.nodevar){
				return  'route2node.php?mid=' + gMid + '&nodevar=' + this.nodevar + '&fid='+ id2 + '&ac=publish';
			}else{
				return  'route2node.php?mid=' + gMid + '&nodevar=' + this.model.node + '&fid='+ id2 + '&ac=publish';
			}
		},
		
		adjustView: function () {
			var total = this.innerList.find('.publish-each').size(), hideNum;
			hideNum = total <= this.options.maxColumn ? 0 : total - this.options.maxColumn;
			this.innerList.css('margin-left', -hideNum * this.options.eachWidth + 'px');
		},
		
		reinitView: function () {
			//css中写的宽度默认3列，一列165
			var defw = 165 * 3,
				realw = this.options.eachWidth * this.options.maxColumn;
			this.el.css('width', 705 - defw + realw);
			$('.publish-list', this.el).css('width', realw);
			
		},
		
		setMaxColumn: function (value) {
			this.options.maxColumn = value;
			this.reinitView();
		},
		
		setOptions: function (options) {
			$.extend(this.options, options);
			options.change && this.options.change.call(self.el);
		}
	});

	var methodMap = {
		'setMaxColumn': 'setMaxColumn',
		'options': 'setOptions'
	};
	$.fn.hg_move_publish = function (options) {
		var value = arguments[1];
		return this.each(function () {
			var publish = $(this).data('publish'), method;
			if (publish) {
				method = methodMap[options];
				method && publish[method]( value );
				return;
			} else {
				$(this).data('publish', new move_Publish($(this), options));
			}
		});
	};
})($);