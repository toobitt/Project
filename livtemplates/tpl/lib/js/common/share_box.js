(function ($) {
	
	function Publish(el, options) {
		var self = this;
		this.options = options = $.extend({}, this.options || {}, options);
		this.el = el;
	}
	$.extend(Publish.prototype, {
		reinit: function() {
			var url = this.options.getUrl.call(this);
			this.el.find('iframe').attr('src', url); 
			this.el.addClass('loading');
		},
		disappear: function() {
			var _this = this;
			setTimeout(
				function() {
					_this.el.find('iframe').attr('src', '')
				}, 300);
			this.el.removeClass('loading');
		},
		options: {
			getUrl: function() {
				var id = _.values(this.model.get('pub_url'))[0];
				id || ( id = this.model.get('id') );
				return 'run.php?mid=' + gMid + '&a=share_form&id=' + id;
			}
		},
		setOptions: function (options) {
			$.extend(this.options, options);
			options.change && this.options.change.call(self.el);
		}
	});

	var methodMap = {
		'options': 'setOptions'
	};
	$.fn.hg_share = function (options) {
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