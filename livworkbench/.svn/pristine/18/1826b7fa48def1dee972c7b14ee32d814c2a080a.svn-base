$(function($) {
	var log = function() { if (console) console.log.apply(console, arguments); };
	var cellEls, cells, smallCellsView, selectTpl, gData;
	var ajax_urls = {
		'save': ''
	};
	var monihtml = '<div>默认样式</div>';

try {	
	gData = JSON.parse(localStorage['magic_data']);
	var Backbone = window.Backbone,
		App = Backbone,
		Collection = Backbone.Collection,
		Model = Backbone.Model,
		View = Backbone.View,
		Cell = Model.extend({
			myurl: 'run.php?mid=' + gData.gMid,
			initialize: function() {
			},
			mode_param_change: function() {
				this.mySave();
			},
			backOriginal: function() {
				var _this = this;
				$.ajax({
					url: this.myurl,
					data: {a: 'cell_cancle', id: this.id},
					dataType: 'json',
					success: function(data) {
						if (data) {
							var el = $('<div>' + data[0].rended_html + '</div>');
							var scripts = el.slice(1);//获取html中的script元素
							data[0].rended_html = el.html();
							_this.set(data[0]);
							$('head').append(scripts);//将script后于普通html放入dom
							//mm = _this;
						}
					}
				});
			},
			save: function() {
				var json = this.toJSON();
				json.rended_html = '';
				var data = JSON.stringify( [json] );
				var _this = this;
				this.trigger('saving');
				$.ajax({
					url: this.myurl,
					data: {data: data, a: 'cell_update'},
					type: 'post',
					success: function(ret) {
						var data;
						try {
							data = $.parseJSON(ret);
						} catch(e) {
							alert('something is error.');
						}
						if (data) {
							var el = $('<div>' + data[0].rended_html + '</div>');
							var scripts = el.slice(1);//获取html中的script元素
							data[0].rended_html = el.html();
							_this.set(data[0]);
							$('head').append(scripts);//将script后于普通html放入dom
							//mm = _this;
						}
					},
					complete: function() {
						_this.trigger('saved');
					}
				});
			}
		}),
		//单元集合，也负责批量请求单元的渲染html
		Cells = Collection.extend({
			model: Cell, 
			myurl: 'run.php?mid=' + gData.gMid,
			save: function(cells) {
				var data = cells.map(function(cell) {
					cell.trigger('saving');
					var ret = cell.toJSON();
					ret.rended_html = '';
					return ret;
				});
				data = JSON.stringify( data );
				var _this = this;
				$.ajax({
					url: this.myurl,
					data: {data: data, a: 'cell_update'},
					type: 'post',
					success: function(ret) {
						var data;
						try {
							data = $.parseJSON(ret);
						} catch(e) {
							alert('something is error.');
							data = null;
						}
						if (data) {
							$.each(data, function(k, v) {
								var cell = _this.get(v.id);
								var el = $('<div>' + v.rended_html + '</div>');
								var scripts = el.slice(1);//获取html中的script元素
								v.rended_html = el.html();
								cell.set(v);
								$('head').append(scripts);//将script后于普通html放入dom
							});
						}
					},
					complete: function() {
						cells.forEach(function(cell) {
							cell.trigger('saved');
						});
					}
				});
			}
		}),
		//单元视图，当单元数据改变时，这个视图要表现出来
		CellView = View.extend({
			initialize: function() {
				this.render();
				this.model.on('change:rended_html change:css', this.render, this);
			},
			render: function() {
				var newEl, html = this.model.get('rended_html');
				var css = this.model.get('css') || '';
				if (!html) return this;
				newEl = $(html);
				this.$el.replaceWith(newEl);
				this.$el = newEl;
				if ( !this.styleEl ) {
					this.styleEl = $('<style></style>').appendTo('head');
				}
				this.styleEl.html( css );
				//dom改变了需要调整缩略图的大小和位置
				App.trigger('adjustPosition.smallcell');
				return this;
			}
		}),
		//小单元视图，在缩略图上
		SmallCellView = View.extend({
			className: 'smallCellView',
			//调整显示，让其正好位于所对应的元素上面
			adjustPosition: function() {
				var _this = this;
				var boss = _this.options.bossView;
				var els = boss.$el.filter(function() {
					return this.nodeType == 1;
				});
				var offsets = els.map(function() {
					return $(this).offset();
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
				_this.$el.css({
					left: minleft,
					top: mintop,
					width: Math.max(maxleft - minleft, 100),
					height: Math.max(maxtop - mintop, 10)
				}).removeClass('onhover upother');
			},
			events: {
				'click': function() {
					//this.$el.siblings().removeClass('selected');
					this.$el.toggleClass('selected'); 
				}
			},
			initialize: function() {
				var _this = this;
				App
				.on('beginSelect adjustPosition.smallcell', _this.adjustPosition, _this)
				.on('stopSelect', function(which) {
					if (_this.$el.hasClass('onhover')) {
						//触发了此cell的编辑
						_this.$el.removeClass('onhover');
						var type = which.data('type');
						if (type) {
							var value = _this.model.get(type);
							if ( value != which.data('id') ) {
								_this.model.set(type, which.data('id'));
								_this.model.save();
							}
						}
					} else {
						_this.$el.removeClass('upother');
					}
				})
				.on('someoneonselecting', function() { 
					_this.$el.removeClass('onhover').addClass('upother');
				})
				.on('nooneonselecting', function() {
					_this.$el.removeClass('onhover upother');
				});
				this.$el.droppable({
					over: function() {
						App.trigger('someoneonselecting');
						_this.$el.addClass('onhover').removeClass('upother');
					},
					greedy: true,
					accept: '#selectSettings li'
				});
				
				//模型事件，保存中加个等待图标
				this.model
				.on('saving', function() {
					_this.$el.addClass('select-cell-waiting');
				})
				.on('saved', function() {
					_this.$el.removeClass('select-cell-waiting');
				});
				
				setTimeout(function() {
					_this.adjustPosition();
				}, 300);
			}
		}),
		//放小单元视图的div
		SmallCellsView = View.extend({
			id: 'smallCellsView',
			addOne: function(sview) {
				this.views.push(sview);
				this.$el.append( sview.el );
			},
			getSelectedModel: function() {
				return this.views.filter(function (view) {
					if (view.$el.is('.selected')) {
						return true;
					} else {
						return false;
					}
				}).map(function (view) {
					return view.model;
				});
			},
			initialize: function() {
				var _this = this;
				this.$el.appendTo('body').droppable({
					over: function() {
						App.trigger('nooneonselecting');
					}
				});
				this.views = [];
				App
				.on('beginSelect', function () {
					//_this.$el.show();
				})
				.on('stopSelect', function() {
					//_this.$el.hide();
				});
			}
			
		}),
		//设置单元信息的view
		SelectView = View.extend({
			id: 'selectSettings',
			className: "selectMenu-model",
			events: {
				'click .selectMenu-item-bg': 'toggleViewModel',
				'mouseenter .selectMenu-item-bg': 'toggleHover', 
				'mouseleave .selectMenu-item-bg': 'toggleHover',
				'click .select-cell-back,.select-cell-no-btn': function() {
					this.$el.attr('class', 'selectMenu-model');
				},
				'change .select-attr-data_source': 'changeInputParam',
				'click .select-cell-yes-btn': 'saveAttr',
				'click .select-cell-delete-btn': 'backOriginal',
				'click .close-current-page': function() {
			        if(top && top != self) {
			            top.$('#formwin').trigger('_close');
			        }
				}
			},
			backOriginal: function() {
				var yes = confirm("你确定吗？");
				if (!yes) return;
				this.forModel[0].backOriginal();
				this.$('.select-cell-back').trigger('click');
			},
			saveAttr: function() {
				/*
				var mode_param = this.forModel[0].get('mode_param'), 
					input_param = this.forModel[0].get('input_param'),
					need_change, val, data_source_change, selector;
				if ( (val = this.$('.select-attr-data_source').val().trim()) != 
						this.forModel[0].get('data_source') ) {
					this.forModel[0].set('data_source', val);
					need_change = true;
					data_source_change = true;
				}
				selector = data_source_change ? '.select-attr-list-mode_param' : 
												'.select-attr-list-data-item';
				this.$(selector).each(function() {
					var li = $(this),
						id = li.data('id'),
						new_value = li.find('input,select').val().trim(),
						param = (li.data('type') == 'mode_param' ?  mode_param : input_param),
						old_value = param[id].default_value;
					if (new_value != old_value) {
						need_change = true;
						param[id].default_value = new_value;
					}
				});*/
				var _this = this;
				var data_source = this.$('.select-attr-data_source').val();
				var input_param = {};
				var mode_param = {};
				this.$('.select-attr-list-data-item').each(function() {
					var li = $(this),
						id = li.data('id');
						try {
							var new_value = li.find('input,select').val().trim(),
							    param = li.data('type') == 'mode_param' ? mode_param : input_param;	
								info = li.data('info');
							info.default_value = new_value;
							param[id] = info;
						} catch(e) {
						}
				});
				if (this.forModel.length == 1) {
					this.forModel[0].set('data_source', data_source);
					this.forModel[0].set('input_param', input_param);
					this.forModel[0].set('mode_param', mode_param);
				} else {
					this.forModel.forEach(function(m) {
						$.extend(m.get('mode_param'), mode_param);
					});
				}
				this.forModel[0].collection.save( this.forModel );
				this.$el.attr('class', 'selectMenu-model');
			},
			toggleHover: function(e) {
				var img = $(e.currentTarget).siblings();
				var old = img.attr('xlink:href'),
					_new = img.attr('hover_href');
				img.attr({
					'xlink:href': _new,
					'hover_href': old
				});
			},
			toggleViewModel: function(e) {
				var index = $(e.currentTarget).attr('index');
				switch (+index) {
					case 0:
						this.showAttrTab();
						break;
					case 1:
						this.showModeTab();
						break;
					case 2:
						this.showLayoutTab();
						break;
				}
			},
			get_input_paramBy: function(id) {
				var ret = [];
				this.data.data_source.forEach(function(v) {
					if ( v.id == id ) {
						ret = v.input_param;
						return false;
					}
				});
				return ret;
			},
			get_mode_paramBy: function() {
				var ret = [];
				var params = this.forModel.map(function(m) { return m.get('mode_param');  });
				var canret = false;
				_.each(params, function(p, i) {
					if (!p) {
						canret = true;
						return false;
					}
				});
				if (canret) return ret;
				//排个序
				params = params.sort(function(x, y) { return x.length > y.length; });
				var first = params[0];
				var rest = params.slice(1);
				
				if (rest.length == 0) return first;
				//以最少的循环，找出公有的
				_.each(first, function(v) {
					var allhas = 0;
					_.each(rest, function(ps) {
						_.each(ps, function(p) {
							if (p.type == v.type && p.name == v.name && p.sign == v.sign ) {
								allhas += 1;
								return false;
							}
						});
					});
					if ( allhas == rest.length ) {
						ret.push(v);
					}
				});
				return ret;
			},
			changeInputParam: function(e) {
				var id = $(e.target).val();
				var data = this.forModel[0].toJSON();
				var input_param;
				if (id != data.data_source) {
					input_param = this.get_input_paramBy(id);
				}
				data = $.extend({
					title: '单元属性',
					num: this.forModel.length
				}, data, {
					all_data_source: gData.data_source,
					data_source: id,
					input_param: input_param
				});
				//为了保持已编辑的mode_param不变，只替换input_param
				var selector = '.select-attr-list-data-item:not(.select-attr-list-mode_param)';
				var new_html = $(this.template_attr(data)).find(selector);
				this.$('.select-cell-attr-box').find(selector).remove().end()
					.find('.common-button-group').before(new_html);
			},
			showAttrTab: function() {
				this.$el.attr('class', 'select-cell-tabs-model');
				this.$('.select-cell-tab-item').removeClass('current');
				var ms = smallCellsView.getSelectedModel();
				this.forModel = ms;
				var len = this.forModel.length, data = {};
				if (len == 0) {
					data.none = true;
				} else if (len == 1) {
					$.extend(data, this.forModel[0].toJSON());
					data.all_data_source = gData.data_source;
				} else {
					var mode_param = this.get_mode_paramBy();
					if (mode_param.length) {
						data.mode_param = mode_param;
					} else {
						data.none = true;
					}
				}
				data.title = len ? '单元属性' : '页面属性';
				data.num = len;
				
				this.$('.select-cell-attr-box').addClass('current')
					.html(this.template_attr(data));
			},
			showModeTab: function() {
				this.$el.attr('class', 'select-cell-tabs-model');
				this.$('.select-cell-tab-item').removeClass('current');
				this.$('.select-cell_mode-box').addClass('current');
			},
			showLayoutTab: function() {
				
			},
			initialize: function(options) {
				this.data = {};
				this.data.cell_mode = options.cell_mode;
				this.data.data_source = options.data_source;
				this.forModel = null;
				this.render();
				this.$el.appendTo('body');
				this.$el.draggable({
					//containment: 'html'
					handle: 'h3,#selectMenu'
				});
				this.$el.find('li').draggable({
					helper: 'clone',
					start: function() {
						App.trigger('beginSelect', $(this));
					},
					stop: function() {
						App.trigger('stopSelect', $(this));
					}
				});
			},
			render: function () {
				this.$el.html(this.template(
					$.extend({title: '样式'}, this.data)
				));
			},
			template: _.template(gData.js_tpls.selectSettings),
			template_attr:  _.template(gData.js_tpls["selectSettings-attr"])
			
		});
		
	//收集页面中所有单元元素，并以其名字生成map
	cellEls = {};
	$('.livcms_cell').each(function(index, el) {
		el = $(el);
		cellEls[el.text()] = el;
	});
	log(cellEls);
	cells = new Cells;
	smallCellsView = new SmallCellsView();
	new SelectView(gData);
	cells.on('add', function(cell) {
	
		var key = 'liv_' + cell.get('cell_name'),
			el = cellEls[key];
		log(key);
		if (el) {
			var view = new CellView({ el: el, model: cell });
			var sview = new SmallCellView({ bossView: view, model: cell });
			smallCellsView.addOne( sview );
		} else {
			throw '页面中存在无法匹配的单元';
		}
	}); 
	cells.add(gData['cells']);
	
} catch(e) {
	alert(e);
	log(e);
}
});