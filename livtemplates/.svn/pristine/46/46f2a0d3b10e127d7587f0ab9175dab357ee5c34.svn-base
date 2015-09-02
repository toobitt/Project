(function ($) {
	var publis_li_tpl = 
		'<li data-id="${id}" {{if +is_last}}class="no-child"{{/if}}>'+
			'<span class="publish-name">${name}</span>'+
			'<span data-id="${id}" data-name="${name}" class="publish-child">&gt;</span>'+
		'</li>';
	var block_tpl = 
		'<li _id="${id}" _name="${name}" class="no-child">'+
			'<input type="checkbox" class="publish-checkbox" /> '+
			'<span class="publish-name">${name}</span>'+
			'<span class="publish-child">&gt;</span>'+
		'</li>';
	var result_tpl =
		'<li _id="${id}" _name="${name}" title="${name}" class="result_show">'+
			'<input type="checkbox" checked="checked" class="publish-checkbox" />'+
			'<span>${name}</span>'+
		'</li>';
	
	
var NodeTree = (function() {
	var Klass = function(options) {
		var _this = this;
		this.el = options.el;
		this.listEl = this.el.find('.publish-inner-list');
		this.listContent = this.el.find('.publish-content');
		this.result = this.el.find('.publish-result ul');
		this.hiddenInput = this.el.find('.publish-hidden');
		this.hiddenNameInput = this.el.find('.publish-name-hidden');
		this.depth = options.depth || 0;
		this.options = $.extend({}, this.options, options);
		this.htmlCache = {
			cache: {},
			set: function(id, data) { this.cache[id] = data; },
			get: function(id) { return null;//this.cache[id]; 
			}
			
		};
		
		this.ajaxid = 0;
		this.bindDomEvents();
		this.reinitView();
	};
	$.extend(Klass.prototype, {
		reinit: function(ids, model) {
			if( this.loaded ){
				this.reset();
				this.initResult( model );
				return;
			}
			this.loaded = true;
			var _this = this;
			$.getJSON(this.options.nodeapi, function(data) {
				html = $.tmpl(publis_li_tpl, data);
				html = $('<div class="publish-each"></div>').append('<ul></ul>').find('ul').append(html).parent();
				_this.listEl.html( html );
			});
			this.initResult( model );
		},
		reset : function(){
			this.result.empty();
			this.result.closest('.publish-result').addClass('empty');
		},
		initResult : function( model ){
			var _this = this,
				data = model['attributes']['block'];
			if( !data ) return;
			if( $.isArray( data )  && !data.length ) return;
			$.map( data, function( value, key ){
				_this.addResult( value['id'], value['name'] )
			} );
			
		},
		syncSelected: function () {
			var self = this;
			this.listContent.find('li').find('input').prop('checked', false);
			this.result.find('li').each(function () {
				var id = $(this).attr('_id');
				self.listContent.find('li[_id="'+ id +'"] input').prop('checked', true);
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
			this.options.change && this.options.change.call(this.el);
		},
		//绑定dom事件
		bindDomEvents: function() {
			var _this = this;
			var self = this;
			this.el
			.on('click', '.publish-child', $.proxy(this.upDepth, this) )
			.on('click', '.publish-node-back', $.proxy(this.downDepth, this))
			.on('click', '.publish-each li', function(e) {
				if ( $(e.target).is('.publish-child') ) return;
				_this.beingCurrent($(this).children());
				var id = $(this).data('id');
				_this.get_block(id, $(e.target));
			})
			.on('click', '.publish-content li', function(e) {
				var li = $(this), checked, id, name, showName;
				
				//点击时input的状态，如果点的是input要取反
				checked = li.find('input').prop('checked');
				$(e.target).is('input') && (checked = !checked);
				
				id = li.attr('_id');
				name = li.attr('_name');
				showName = li.attr('_showName') || name;
				if ( $(e.target).is('input') || li.hasClass('no-child') ) {
					if ( li.find('input').css('visibility') == "hidden" ) return;
					//更新result区域
					li.find('input').prop('checked', !checked);
					checked ? 
						self.removeResult(id) : 
						self.addResult(id, name, showName);
				} 
			});
			this.result
			.on('click', 'li', function (e) {
				self.removeResult( $(this).attr('_id') );
				self.syncSelected();
			});
		},
		options: {
			depth: 0,
			eachWidth: 165,
			maxShow: 1,
			nodeapi: 'get_block.php'
		},
		get_block: function(id, target) {
			var _this = this;
			$.globalAjax( target, function(){
				return $.get(_this.options.nodeapi, {
					a: 'get_block',
					id: id
				}, function(data) {
					_this.addBlockContent(data);
				}, 'json');
			} );
		},
		addBlockContent: function(data) {
			var data = data.block;
			var html = data.length ? ( $.tmpl(block_tpl, data) ) : '<p>暂无数据</p>';
			this.listContent.html( html );
		},
		reinitView: function () {
			//css中写的宽度默认3列，一列165
			var defw = 165 * 3,
				realw = this.options.eachWidth * this.options.maxShow;
			this.el.css('width', 705 - defw + realw + 200);
			$('.publish-list', this.el).css('width', realw);
			
		},
		//调整下，让当前深度的可视
		adjustView: function() {
			this.el.find('.publish-inner-list').css({
				'margin-left': -(this.depth + 1 - this.options.maxShow) * this.options.eachWidth
			});
		},
		beingCurrent: function(anchor) {
			anchor.closest('li').addClass('open').siblings().removeClass('open');
			this.el.find('.allcond').parent().removeClass('current');
		},
		//增加深度
		upDepth: function(e) {
			var btn = $(e.currentTarget),
			    id = btn.data('id'),
			    name = btn.data('name'),
			    html = this.htmlCache.get(id),
			    needRequest = !html;
			this.beingCurrent(btn);
			this.removeNeedless();
			this.depth += 1;
			if (!html) {
				html = '<div class="publish-each publish-wait">' +
							'<div class="publish-node-title">' + 
								'<div class="publish-node-title-inner">' + 
									'<a href="javascript:;" class="publish-node-back">' + name +'</a>' +
						'</div></div><ul></ul></div>';
			}
			this.listEl.append(html);
			this.adjustView();
			this.displayCurNode(true);
			
			if (!needRequest) return;
			var depth = this.depth;
			var _ajaxid = ++this.ajaxid;
			var _this = this;
			$.get(this.options.nodeapi, {
				fid: id
			}, function(html) {
				_this.htmlCache.set(id, html);
				html = $.tmpl(publis_li_tpl, html);
				//深度和请求都没改变，将请求到的html放进dom
				if (_ajaxid == _this.ajaxid && depth == _this.depth) {
					_this.listEl.find('.publish-each:last').find('ul').html(html).end().removeClass('publish-wait');
					_this.displayCurNode(true);
				}
			}, 'json');
		},
		//减小深度
		downDepth: function() {
			this.depth -= 1;
			this.adjustView();
			this.displayCurNode();
			this.removeEach();
		},
		//改变右侧的nodeFrame的链接，以显示当前的node
		displayCurNode: function(first) {
			
		},
		removeNeedless: function() {
			this.el.find('.each-node:gt(' + this.depth + ')').remove();
		},
		removeEach : function(){
			this.listEl.find('.publish-each:last').remove();
		}
	});
	return Klass;
})();

	var methodMap = {
		'setMaxColumn': 'setMaxColumn',
		'options': 'setOptions'
	};
	$.fn.hg_block_publish = function (options) {
		var value = arguments[1];
		return this.each(function () {
			var publish = $(this).data('publish'), method;
			if (publish) {
				method = methodMap[options];
				method && publish[method]( value );
				return;
			} else {
				$(this).data('publish', new NodeTree($.extend({el: $(this)}, options)));
			}
		});
	};
})($);