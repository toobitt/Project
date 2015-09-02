(function(exports) {
	
	var SelectMenu = Backbone.View.extend({
		initialize: function() {
			this.pubHistroy = [];
			
			this.$el.draggable({
				containment: 'html',
				start: function() {
					App.trigger('SelectView:dragstart');
				},
				stop: function() {
					App.trigger('SelectView:dragend');
				}
			});
			
			App.on('SelectMenu:close', this.show, this);
		},
		el: '#selectMenu',
		events: {
			'mouseenter .selectMenu-item-bg': 'toggleHover', 
			'mouseleave .selectMenu-item-bg': 'toggleHover',
			'click .selectMenu-item-bg': 'toggleViewModel'
		},
		show: function() {
			this.$el.show();
		},
		toggleHover: function(e) {
			// hover交互，改变图片
			var img = $(e.currentTarget).siblings();
			var old = img.attr('xlink:href'),
				_new = img.attr('hover_href');
			img.attr({
				'xlink:href': _new,
				'hover_href': old
			});
		},
		toggleViewModel: function(e) {
			// 确定选择的菜单项，并发布
			var index = $(e.currentTarget).attr('index');
			switch (+index) {
				case 0:
					this.pubAttr();
					break;
				case 1:
					this.pubMode();
					break;
				case 2:
					this.pubLayout();
					break;
			}
		},
		pubAttr: function() {
			this.pub('SelectMenu:attr');
		},
		pubMode: function() {
			this.pub('SelectMenu:mode');
		},
		pubLayout: function() {
			//this.pub('SelectMenu:layout');
		},
		pub: function(event, args) {
			App.trigger.apply(App, [event].concat(args)); //发布全局事件
			this.pubHistroy.push({ event: event }); //加入历史记录
			
			this.$el.hide(); //隐藏自身
		}
	});
	
	exports.SelectMenu = SelectMenu;
	
})(window.App);
