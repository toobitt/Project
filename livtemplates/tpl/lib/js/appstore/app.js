(function() {
	
	function Nav(options) {
		this.$el = options.el;
		this.init(options);
	}
	$.extend(Nav.prototype, {
		init: function(options) {
			this.bindEvents();
		},
		bindEvents: function() {
			var _this = this;
			this.$el.on('click', 'li', function() {
				$(this).addClass('current').siblings().removeClass('current');
				_this.showGroup( $(this).data('id') );
			});
		},
		showGroup: function(id) {
			if (!id) {
				$('.app-group').show();				
			} else {
				$('.app-group').hide();
				$('#group_' + id).show();	
			}
		}
	});
	
	function Edit(options) {
		this.$el = options.el;
		this.init(options);
	}
	$.extend(Edit.prototype, {
		init: function(options) {
			this.bindEvents();
			this.template = _.template( $('#edit-info-tpl').html() );
		},
		bindEvents: function() {
			var _this = this;
			this.$el
			.on('click', '.edit-close', function() {
				_this.close();
			})
			.on('click', function(e) {
				if (e.target == this) {
					_this.close();
				}
			})
			.on('click', '.btn', function() {
				var href = $(this).attr('href');
				_this.open(href);
				return false;
			})
			.on('click', '.iframe-back', function() {
				_this.back();
			});
		},
		open: function(href) {
			this.$('iframe').attr('src', href);
			this.$el.addClass('iframe-open');
			this.$('#features').hide();
		},
		close: function() {
			if (!this.showing) return;
			this.showing = false;
			this.back();
			this.$el.animate({ opacity: 0 }, 300, function() { $(this).hide(); });
			this.$('#features').show();
		},
		back: function() {
			this.$('iframe').attr('src', '');
			this.$el.removeClass('iframe-open');
			this.$('#features').show();
		},
		$: function(s) {
			return this.$el.find(s);
		},
		show: function(data) {
			this.render(data);
			if (this.showing) return;			
			this.$el.show().css('opacity', 0).animate({ opacity: 1 }, 300);
			this.showing = true;
		},
		render: function(appinfo) {
			this.$el.find('.edit-content').html( this.template(appinfo) );
			var html = '';
			if(!appinfo.wait)
			{
				var version_features = appinfo.appinfo.version_features;
				for (var i in version_features)
				{
					html = html + '<h3>' + i +'</h3><div>' + version_features[i];
				}
				this.$('#features').html(html);
			}
		}
	});
	
	function AppList(edit) {
		$('.app-main').on('click', 'li', function() {
			//var url = 'appstore.php?app=' + $(this).data('app_uniqueid');
			var url = 'appstore.php?a=getAppInfo&app=' + $(this).data('app_uniqueid');
			edit.show({ wait: true });
			$.getJSON(url, function(data) {
				edit.show({ appinfo: data });
			});
		});
	}
	
	$(function() {
		$('.nav-bg .close').on('click', function() {
			top.$('#formwin').trigger('_close');
		});
		
		new Nav({ el: $('.nav'), });
		AppList(new Edit({ el: $('#edit-area') }));

        app && $('.app-main li[data-app_uniqueid="'+ app +'"]').trigger('click');
        
        $('.app-item img').lazyload({
        	effect: 'fadeIn',
        	effectspeed: 1000
        });
        
        // domready时，window高度为0，导致初始显示失败，延迟下
        setTimeout(function() {
        	$(window).trigger('scroll');
        }, 500);
	});
	
})();
