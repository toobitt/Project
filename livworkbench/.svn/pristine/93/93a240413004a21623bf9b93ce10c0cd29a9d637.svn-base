(function(exports) {
	
	var config = exports.config;
	var __slice = Array.prototype.slice;
	var count = 0;
	var CellView = Backbone.View.extend({
		initialize: function() {
			App.on('CellView:position', this.position, this);
			
			//绑定，html改变后重新渲染
			this.model.on('change:rended_html', this.render, this);
			var _this = this;			
			this.model
				.on('saving', function() {
					_this.small_el.addClass('select-cell-waiting');
				})
				.on('saved', function() {
					_this.small_el.removeClass('select-cell-waiting');
				});
				
			this.emptyHTML = this.el.outerHTML;
			this.small_el = $('<div></div>').addClass('smallCellView').appendTo('body');
			
			var node = this.options.document.createElement('style');
			this.options.document.getElementsByTagName('head')[0].appendChild(node);
			this.style_el = $(node);
			
			this.bindSmallElEvents();
		},
		render: function(silent) {
			var html = this.model.get('rended_html') || '';
			html = html.trim() || this.emptyHTML;
			var doc = this.options.document;
			
			//将渲染后的html放入页面
			var node = doc.createElement('div');
			node.innerHTML = html.trim();
			var nodes = __slice.call(node.childNodes);
			// 过滤掉是Text node且都是空白的
			nodes = _.filter(nodes, function(n) {
				return !(n.nodeType == 3 && !n.nodeValue.trim());
			});
			var plain_nodes = [],	//普通的node
				scripts = [];		//脚本
			_.each(nodes, function(n) {
				if ( (n.nodeName.toLowerCase() == 'script') && (!n.type || n.type == "text/javascript") ) {
					scripts.push(n);
				} else {
					plain_nodes.push(n);
				}
			});
			
			//普通node和script要分开放入页面，script后放
			var new_el = $(plain_nodes);
			this.$el.replaceWith(new_el);
			this.$el = new_el;
			try { 
				//放script
				$('head', doc).append(scripts);
			} catch(e) {}
			//将关联的css放入页面
			this.style_el.html( this.model.get('css') || '' );
			
			// 通知大家重新计算位置，除非silent = true
			(silent === true) || App.trigger('CellView:position');
			return this;
		},
		position: function() {
			// 调整小视图的位置，用于跟踪$el的位置
		
			//调整前，要让html的高度不小于展示框架的文档
			$('html').css('height', $(this.options.document).height());
			
			//过滤掉Text node
			var els = this.$el.filter(function() {
				return this.nodeType == 1;
			});
			var offsets = els.map(function() {
				return $(this).topOffset();
			}).get();
			var lefts = _.pluck(offsets, 'left'),
				tops = _.pluck(offsets, 'top'),
				leftsWithw = els.map(function(i) {
					return $(this).outerWidth() + lefts[i];
				}).get(),
				topsWithh = els.map(function(i) {
					return $(this).outerHeight() + tops[i];
				}).get(),
				maxleft = Math.max.apply(Math, leftsWithw),
				maxtop = Math.max.apply(Math, topsWithh),
				minleft = Math.min.apply(Math, lefts),
				mintop = Math.min.apply(Math, tops);
			this.small_el.css({
				left: minleft,
				top: mintop,
				width: Math.max(maxleft - minleft, 20),
				height: Math.max(maxtop - mintop, 20)
			});
		},
		bindSmallElEvents: function() {
			_.bindAll(this, 'toggle', 'modeCome');
			this.small_el
			.on('click', this.toggle)
			.droppable({
				accept: '.mode-list li',
				hoverClass: 'onhover',
				drop: this.modeCome
			});
		},
		toggle: function() {
			//切换选中
			this.small_el.toggleClass('selected');
			this.trigger('toggle');
		},
		isSelected: function() {
			return this.small_el.hasClass('selected');
		},
		modeCome: function(e, ui) {
			var id = ui.draggable.data('id');
			this.setMode(id);
		},
		setMode: function(modeId) {
			this.model.set('cell_mode', modeId);
			this.model.save();
			this.trigger('toggle');
		}
	});
	
	var CellsView = Backbone.View.extend({
		initialize: function() {
			var cellEls;
			this.document = $('#html_iframe')[0].contentDocument;
			this.window = this.document.defaultView;
			this.views = []; 
			this.collectCellPlaceholder();
			this.collection.on('add', this.addOne, this);
			this.beautifyCellPlaceholder();
			this.dragBecomeFlow();
		},
		pubSelectedChange: function() {
			this.trigger('changeSelected');
		},
		getCells: function() {
			return this.views.filter(function (view) {
				return view.isSelected();
			}).map(function (view) {
				return view.model;
			});
		},
		save: function(cells) {
			this.collection.save(cells);
		},
		destroy: function(cells) {
			this.collection.destroy(cells);
		},
		addOne: function(cell) {
			var key, el, view;
			
			key = 'liv_' + cell.get('cell_name'),
			el = this.cellPlaceholder[key];
			if (el) {
				view = new CellView({ el: el, model: cell, document: this.document });
				view.on('toggle', this.pubSelectedChange, this);
				this.views.push(view);
				view.render(true);
			} else {
				throw { msg: config.message.match_error, detail: key };
			}
		},
		collectCellPlaceholder: function() {
			var cellPlaceholder = this.cellPlaceholder = {};
			$('.livcms_cell', this.document).each(function(index, el) {
				el = $(el);
				cellPlaceholder[el.text()] = el;
			});
		},
		beautifyCellPlaceholder: function() { 
			//给展示页加点css，让空的cell好看点
			var node = this.document.createElement('style');
			this.document.getElementsByTagName('head')[0].appendChild(node);
			this.style_el = $(node);
			this.style_el.html('.livcms_cell {display: inline-block;width: 100px;height: 50px;line-height:' +
				' 50px;margin: 20px;text-align: center;background: green !important;}');
		},
		dragBecomeFlow: function() {
			//为了拖动能够顺畅，搞个全屏的div把iframe挡住
			App.on('SelectView:dragstart', function() {
				$('#operate_box').css({
					width: $(document).width(),
					height: $(document).height()
				});
			});
			App.on('SelectView:dragend', function() {
				$('#operate_box').css({
					width: '',
					height: ''
				});
			});
		}
	});
	
	exports.CellsView = CellsView;
	
})(window.App);
