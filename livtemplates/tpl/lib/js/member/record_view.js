;(function() {
	var oneliProto = {
		// 初始化函数
		initialize : function(options) {
			this.parentView = options.parentView;
		},
		tagName: 'li',
		// 绑定dom事件
		events : {
			'click input:checkbox': 'toggle'
		},
		//切换选中 
		toggle: function(e) {
			$(e.target).prop('checked') ? this.check() : this.uncheck();
		},
		// 选中
		check : function() {
			this.$('input:checkbox').prop('checked', true);
			this.$el.addClass('selected');
		},
		// 取消选中
		uncheck : function() {
			this.$('input:checkbox').prop('checked', false);
			this.$el.removeClass('selected');
		},
		// 成为当前元素
		beCurrent : function() {
			// 让父视图，将其他的置为非当前
			this.parentView.currentChild(this);
			this.$el.addClass('current');
		},
		// 变为非当前元素
		unbeCurrent : function() {
			this.$el.removeClass('current');
		},
	};
	
    
	var listProto = {
		initialize: function() {
			this.collection.on('add', this.addOne, this);
			this.views = {};
			this.checkAllElement = this.$('.common-list-bottom input:checkbox');
		},
		events: {
			// 全选事件
			'click .common-list-bottom input:checkbox': 'toggleCheckAll'
		},
		addOne: function(record) {
			var id, li, view;

			id = record.get('id');
			li = this.options.getLiById(id);
			view = new RecordView({
				el : li[0],
				model : record,
				parentView: this
			});

			// view的元素还没加入页面，则加入
			if (!li.length) {
				this.ul.append(li.render().el);
			}
			this.views[id] = view;
		},
		get: function(id) {
			return this.views[id];
		},
		getViewByEl: function(el) {
			var id = el.id.slice(2);
			return this.get(id);
		},
		// 切换全选
		toggleCheckAll: function() {
			var checked = this.checkAllElement.prop('checked');
			checked ? this.checkAll() : this.uncheckAll();
		},
		// 全选
		checkAll: function() {
			this.checkAllElement.prop('checked', true);
			_.each(this.views, function(view) {
				view.check();
			});
		},
		// 取消全选
		uncheckAll: function() {
			this.checkAllElement.prop('checked', false);
			_.each(this.views, function(view) {
				view.uncheck();
			});
		},
		// 确保childView之外所有children为非当前元素
		currentChild: function(childView) {
			_.each(this.views, function(view) {
				if (view !== childView) {
					view.unbeCurrent();
				}
			});
		},
		options : {
			getLiById : function(id) {
				return $('#r_' + id);
			}
		}
	};
    
    var RecordView = Backbone.View.extend(oneliProto);
	var RecordsView = Backbone.View.extend(listProto);

	window.RecordsView = RecordsView;
    
})();