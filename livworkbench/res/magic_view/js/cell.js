(function(exports) {
	
	var config = exports.config;
	
	var Cell = Backbone.Model.extend({
		save: function() {
			this.collection.save([this]);
		},
		destroy: function(cells) {
			var _this = this;
			this.trigger('saving');
			$.ajax({
				url: this.collection.options.url,
				data: { a: 'cell_cancle', id: this.id },
				dataType: 'json',
				success: function(data) {
					if (data && data[0]) {
						_this.set(data[0]);
					}
				},
				complete: function() {
					_this.trigger('saved');
				}
			});
		}
	});
	
	var Cells = Backbone.Collection.extend({
		initialize: function(models, options) {
			this.options = options;
		},
		model: Cell,
		destroy: function(cells) {
			cells[0] && cells[0].destroy();
		},
		save: function(cells) {
			cells[0].trigger('saving');
			//收集数据
			var data = cells.map(function(cell) {
				cell.trigger('saving');
				var ret = cell.toJSON();
				ret.rended_html = '';
				return ret;
			});
			data = JSON.stringify( data );
			var _this = this;
			$.ajax({
				url: this.options.url,
				data: { a: 'cell_update', data: data },
				type: 'post',
				success: function(ret) {
					var data;
					try {
						data = $.parseJSON(ret);
					} catch(e) {
						alert(config.message.ret_error);
						data = null;
					}
					if (data) {
						$.each(data, function(k, v) {
							var cell = _this.get(v.id);
							cell && cell.set(v);
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
	});
	
	exports.Cells = Cells;
	
})(window.App);
