(function(exports) {
	
	var SelectView = Backbone.View.extend({
		initialize: function(options) {
			App.on('SelectMenu:mode', this.mode, this);
			App.on('SelectMenu:attr', this.attr, this);
			
			this.data = options.data;
			this.cells_view = options.cells_view;
			
			this.cells_view.on('changeSelected', this.refresh, this);
			
			this.$('.select-cell-tabs').draggable({
				containment: 'html',
				handle: 'h3'
			});
			this.configureTemplate();
			this.render_mode();
			this.$('.mode-list li').draggable({
				helper: function() { return '<a>样式</a>' },
				appendTo: 'body',
				start: function(e, ui) {
					// ui.helper.css({
						// 'margin-top': e.pageY - ui.position.top - 20,
						// 'margin-left': e.pageX - ui.position.left - 30
					// });
					App.trigger('SelectView:dragstart');
				},
				stop: function() {
					App.trigger('SelectView:dragend');
				}
			});
		},
		el: '#selectSettings',
		hideAll: function() {
			this.$('.select-cell-tab-item').hide();
		},
		mode: function() {
			this.hideAll();
			this.$('.select-cell_mode-box').show();
			this.toggle_mode();
		},
		attr: function() {
			this.hideAll();
			this.render_attr();
			this.$('.select-cell-attr-box').show();
		},
		refresh: function() {
			if ( this.$('.select-cell-attr-box').is(':visible') ) {
				this.render_attr();
			} else if ( this.$('.select-cell_mode-box').is(':visible') ) {
				this.toggle_mode();				
			}
		},
		toggle_mode: function() {
			var cells = this.cells_view.getCells();
			var id;
			if (cells.length) {
				id = cells[0].get('cell_mode'); 
				_.each(cells, function(cell) {
					var _id = cell.get('cell_mode');
					if ( id != _id ) {
						id = null;
					}
				});
			}
			this.$('.mode-list li').removeClass('selected'); 
			if (id) {
				this.$('.mode-list [data-id="' + id + '"]').addClass('selected');
			}
		},
		render_mode: function() {
			var data = this.data;
			this.$('.select-cell_mode-box').append( this.template_mode(data) );
		},
		render_attr: function() {
			var cells = this.cells_view.getCells();
			var len = cells.length;
			var data;
			if (!len) {
				data = { none: true, title: '页面属性', num: 0 };
			} else if (len == 1) {
				data = {
					title: '单元属性',
					num: len,
					all_data_source: this.data.data_source
				};
				$.extend(data, cells[0].toJSON());
			} else {
				data = { title: '单元属性', num: len };
				$.extend(data, this.mergeParam());
			}
			this.$('.select-cell-attr-box').html( this.template_attr(data) );
			this.render_input_param( data.input_param );
		},
		render_input_param: function(input_param) {
			if (!input_param) return this;
			var selector = '.select-attr-list-data-item:not(.select-attr-list-mode_param)';
			try { 
				this.$('.select-cell-attr-box').find(selector).remove().end()
					.find('.common-button-group').before( this.template_input_param(input_param) );
			} catch(e) {
				log(e);
			}
			return this;
		},
		events: {
			'click .select-cell-back,.select-cell-no-btn': 'back',
			'change .select-attr-data_source': 'changeInputParam',
			'click .select-cell-yes-btn': 'saveAttr',
			'click .select-cell-delete-btn': 'backOriginal'
		},
		backOriginal: function() {
			var yes = confirm("你确定吗？");
			if (!yes) return;
			this.cells_view.destroy(this.cells_view.getCells());
			this.back();
		},
		saveAttr: function() {
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
			var cells = this.cells_view.getCells();
			if (cells.length == 1) {
				cells[0].set('data_source', data_source);
				cells[0].set('input_param', input_param);
				cells[0].set('mode_param', mode_param);
			} else {
				cells.forEach(function(m) {
					$.extend(m.get('mode_param'), mode_param);
				});
			}
			this.cells_view.save(cells);
			this.back();
		},
		back: function() {
			this.$('.select-cell-tab-item').hide();
			App.trigger('SelectMenu:close');
		},
		configureTemplate: function() {
			this.template_attr = _.template( $('#selectSettings-attr').html() );
			this.template_input_param = _.template( $('#template_input_param').html() );
			this.template_mode = _.template( $('#mode-list-tpl').html() );
		},
		changeInputParam: function(e) {
			var id = $(e.target).val();
			var cell = this.cells_view.getCells()[0];
			var input_param;
			if ( id != cell.get('data_source') ) {
				input_param = this.get_input_param_by(id);
			} else {
				input_param = cell.get('input_param');
			}
			this.render_input_param(input_param);
		},
		get_input_param_by: function(id) {
			var ret = [];
			$.each(this.data.data_source, function(i, v) {
				if ( v.id == id ) {
					ret = v.input_param;
					return false;
				}
			});
			return ret;
		},
		mergeParam: function() {
			//用jquery的each是因为return false就可以退出循环
			var cells = this.cells_view.getCells();
			var ret = [];
			var params = cells.map(function(m) { return m.get('mode_param'); });
			
			//看看有没有mode_param空的
			var canret = false;
			$.each(params, function(i, ps) {
				if (!ps) {
					canret = true;
					return false;
				}
			});
			//有的话直接返回
			if (canret) {
				return { none: true };
			}
			
			//排个序
			params = params.sort(function(x, y) { return x.length > y.length; });
			var first = params[0];
			var rest = params.slice(1);
			
			//以最少的循环，找出公有的
			_.each(first, function(v) {
				var allhas = 0;
				_.each(rest, function(ps) {
					$.each(ps, function(i, p) {
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
			return { mode_param: ret };
		}
	});
	
	exports.SelectView = SelectView;
	
})(window.App);
