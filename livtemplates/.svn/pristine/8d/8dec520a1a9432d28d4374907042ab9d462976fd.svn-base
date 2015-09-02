(function($) {
	var defaultOption = {
	};
	$.fn.statusSwitcher = function(option) {
		option = $.extend({}, defaultOption, option);
		return this.each(function() {
			var me = $(this);
			var data = option.data;
			var currentStatus = option.status;
			var changingStatus = option.changingStatus;
			var changeStatus = ($.type(option.changeStatus) == 'function' ? option.changeStatus : function() {});
			var statusView = ($.type(option.statusView) == 'function' ? option.statusView : function() {});

			statusView(data, currentStatus);
			me.on('switch', function() {
				statusView(data, changingStatus);
				changeStatus(data, currentStatus);
				me.data('switching', true);
			}).on('switched', function(event, status) {
				currentStatus = status;
				statusView(data, currentStatus);
				me.data('switching', false);
			}).on('click', function() {
				if( me.data('switching') ) {
					return;
				}
				me.trigger('switch');
			});
		});
	};
})(jQuery);