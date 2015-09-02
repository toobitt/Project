(function ($) {
	var publis_li_tpl = 
		'<li _id="${id}" title="{{if $item.showName}}${$item.showName} > {{/if}}${name}" _name="${name}" _showName="{{if $item.showName}}${$item.showName} > {{/if}}${name}" {{if !(+is_last)}}class="no-child"{{/if}}>'+
			'<input {{if $item.radioModel}}type="radio" name="tree1radio"{{else}}type="checkbox"{{/if}} class="publish-checkbox" /> '+
			'<span class="publish-name">${name}</span>'+
			'<span class="publish-child">&gt;</span>'+
		'</li>';
	var result_tpl =
		'<li _id="${id}" _name="${name}" title="${showName}" class="result_show">'+
			'<input type="checkbox" checked="checked" class="publish-checkbox" />'+
			'<span>${showName}</span>'+
		'</li>';
	var cache = {
        cache : {},
        add : function(type, id, html){
            if(!this.cache[type]){
                this.cache[type] = {};
            }
            this.cache[type][id] = html;
        },
        get : function(type, id){
            return this.cache[type] && this.cache[type][id];
        }
    };
    var defaultOptions = {
    	maxColumn: 2,
    	eachWidth: 165,
    	php: '_fetch_node.php',
    	change: $.noop
    };
	function Publish(el, options) {
		var self = this;
		this.options = options = $.extend({}, defaultOptions, options);
		this.el = el;
		this.result = el.find('.publish-result ul');
		this.innerList = el.find('.publish-inner-list');
		this.site = el.find('.publish-site');
		this.hiddenInput = el.find('.publish-hidden');
		this.hiddenNameInput = el.find('.publish-name-hidden');
		
		this.siteid = el.find('.publish-site-current').attr('_siteid');
		
		if (options.firstColumns) {
			cache.add(this.siteid, 0, options.firstColumns);
		} else {
			
		}
		
		this.gAjaxId = 0;
		
		this.innerList
			.on('click', 'li', function (e) {
				var li = $(this), checked, id, name, showName;
				
				//点击时input的状态，如果点的是input要取反
				checked = li.find('input').prop('checked');
				$(e.target).is('input') && (checked = !checked);
				
				id = li.attr('_id');
				name = li.attr('_name');
				showName = li.attr('_showName') || name;
				if ( $(e.target).is('input') || li.hasClass('no-child') ) {
					//更新result区域
					li.find('input').prop('checked', !checked);
					checked ? 
						self.removeResult(id) : 
						self[options.radioModel ? 'changeResult' : 'addResult'](id, name, showName);
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
			});
		this.result
			.on('click', 'li', function (e) {
				self.removeResult( $(this).attr('_id') );
				self.syncSelected();
			});
		this.site
			.on('click', '.publish-site-qiehuan', function (e) {
				self.el.toggleClass('onswitch');
			})
			.on('click', '.publish-site-item', function (e) {
				var item = $(this), siteid;
				
				siteid = item.attr('_siteid');
				self.el.removeClass('onswitch');
				self.el.find('.publish-each').remove();
				self.el.find('.publish-site-current').text('切换中...');
				self.loadNewColumns( siteid, 0, null, function () {
					self.siteid = siteid;
					self.el.find('.publish-site-current').text( item.attr('_name') );
				});
			});
		self.loadNewColumns( this.siteid, 0, null, function () {
			self.el.find('.publish-site-current').text( 'content.php' );
		});
		this.syncSelected();
		this.reinitView();
		this.options.change.call(self.el);
	}
	$.extend(Publish.prototype, {
		syncSelected: function () {
			var self = this;
			this.innerList.find('li').find('input').prop('checked', false);
			this.result.find('li').each(function () {
				var id = $(this).attr('_id');
				self.innerList.find('li[_id="'+ id +'"] input').prop('checked', true);
			});
		},
		removeResult: function (id) {
			this.result.find('li[_id="'+ id +'"]').remove();
			if ( this.result.find('li').size() == 0 ) {
				this.result.closest('.publish-result').addClass('empty');
			}
			this.saveResult();
		},
		addResult: function (id, name, showName) {
			this.result.append($.tmpl(result_tpl, {
				id: id,
				name: name,
				showName: showName
			}));
			this.result.closest('.publish-result').removeClass('empty');
			this.saveResult();
		},
		saveResult: function () {
			var ids = [], names = [];
			this.result.find('li').each(function () {
				ids.push($(this).attr('_id'));
				names.push($(this).attr('_name'));
			});
			this.hiddenInput.val(ids.join(','));
			this.hiddenNameInput.val(names.join(','));
			this.options.change.call(self.el);
		},
		loadNewColumns: function (siteid, id, showName, cb) {
			var self = this;
			self.requestData(siteid, id, function (data) {
				self.innerList.find('.publish-wait').remove();
				self.addChild(data, showName);
				cb && cb();
			}, function () {
				var wait = $('<div class="publish-each publish-wait"></div>');
				self.innerList.append(wait);
			});
		},
		requestData: function (siteid, fid, cb, wait) {
			var data = cache.get(siteid, fid);
			if (data) {
				cb(data);
				return;
			}
			var ajaxId = ++this.gAjaxId, self = this;
			
			wait();
			$.get(this.options.php, {
				ajax: 1,
				ban: 1,
				multi: 'data_source',
				expand_id: siteid,
				fid: fid
			}, function (data) {
				cache.add(siteid, fid, data);
				if ( self.gAjaxId != ajaxId ) return;
				cb(data);
			}, 'json');
		},
		addChild: function (data, showName) {
			var each_ = $('<div class="publish-each column_show1"><ul></ul></div>'), self = this;
			each_.find('ul').append($.tmpl(publis_li_tpl, data, {showName: showName, radioModel: self.options.radioModel}));
			this.innerList.append(each_);
			this.adjustView();
			this.syncSelected();
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
	$.fn.hg_publish = function (options) {
		var value = arguments[1];
		return this.each(function () {
			var publish = $(this).data('publish'), method;
			if (publish) {
				method = methodMap[options];
				method && publish[method]( value );
				return;
			} else {
				$(this).data('publish', new Publish($(this), options));
			}
		});
	};
})($);