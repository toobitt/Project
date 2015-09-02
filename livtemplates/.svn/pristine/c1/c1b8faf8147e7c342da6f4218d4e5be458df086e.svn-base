/* 模板
 *  <div class="customSelect">
 *		<label class="customSelect-label"><%= label %></label>
 *		<ul class="customSelect-options">
 *			<% for (var key in options) { %>
 *			<li data-value="<%= key %>"><%= options[key] %></li>
 *			<% } %>
 *		</ul>
 *	</div>
 */
(function ($) {
	function CustomSelect(options, el) {
		this.options = $.extend({}, this.options, options);
		this.el = el;
		this.initialize(options);
	}
	$.extend(CustomSelect.prototype, {
		options: {
			ns: '.customSelect',
			onchange: $.noop,
			hoverModel: true,
			slide: false,
			slideDuration: 'fast'
		},
		initialize: function () {
			this.value = this.el.val();
			var choice = this.choice = {}; 
			this.el.find('option').each(function () {
				choice[this.value] = $(this).html();
			});
			_.bindAll(this, 'change');
			this.render();
			this.bindEvent();
			this.options.onchange.call(this.el, this.value, this._el);
			this.resize();
		},
		render: function () {
			this._el = $(this.template({
				options: this.choice,
				label: this.choice[this.value]
			}));
			
			this.el.after( this._el ).hide();
		},
		bindEvent: function () {
			var ns = this.options.ns;
		 	var self = this;
		 	var show = function () {
		 		self.show();
		 	};
		 	var hide = function () {
		 		self.hide();
		 	};
			if (!this.options.slide) {
				this.show = this._show;
				this.hide = this._hide;
		 	} else {
		 		this.show = this._slideShow;
				this.hide = this._slideHide;
		 	}
			if ( this.options.hoverModel ) {
				this._el.on('mouseenter' + ns, show).on('mouseleave' + ns, hide);
			} else {
				var isShow = false;
				this._el.on('click' + ns, function () {
					isShow ? hide() : show();
					isShow = !isShow;
					return false;
				});
				$(window).on('click' + ns, function () {
					if (isShow) {
						hide();
						isShow = !isShow;
					}
				});
			}
			this._el.on('click' + ns, '.customSelect-options li', this.change);
		},
		unbindEvent: function () {
			this._el.off(this.options.ns);
			$(window).off(this.options.ns);
		},
		_show: function () {
			this._el.find('ul').show();
		},
		_hide: function () {
			this._el.find('ul').hide();
		},
		_slideShow: function () {
			this._el.find('ul').slideDown(this.options.slideDuration);
		},
		_slideHide: function () {
			this._el.find('ul').slideUp(this.options.slideDuration);
		},
		change: function (e) {
			var value = $(e.target).data('value');
			if (value == this.value) return;
			this.value = value;
			var text = this.choice[value];
			this.el.val( value );
			this._el.find('.customSelect-label').html(text);
			this.hide();
			this.options.onchange.call(this.el, value, this._el);
			this.resize();
		},
		resize: function () {
			this._el.find('.customSelect-options').css('top', this._el.find('.customSelect-label').outerHeight() );
		},
		template: _.template( '<div class="customSelect" style="display:inline-block;position:relative;cursor:pointer;"><label class="customSelect-label"><%= label %></label><ul class="customSelect-options" style="position:absolute;left:0;display:none;"><% for (var key in options) { %><li data-value="<%= key %>"><%= options[key] %></li><% } %></ul></div>' )		
	});
	$.fn.customSelect = function (options) {
		if (!this.length) return this;
		
		this.each(function () {
			var select = $(this);
			var selector = select.data('selector');
			/*不是select元素或者已经调用过了则退出*/
			if ( !select.is('select') || selector ) return;
			
			if ( select.prop('multiple') ) {
				
			} else {
				selector = new CustomSelect(options, select);
				select.data('selector', selector);
			}
		});
		
		return this;
	};
})($);