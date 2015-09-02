//依赖jquery，加到了jQuery的命名空间下

(function($){
	$.extend($.easing, {
		easeInOutCubic: function (x, t, b, c, d) {
			if ((t/=d/2) < 1) return c/2*t*t*t + b;
			return c/2*((t-=2)*t*t + 2) + b;
		},
		easeOutBounce: function (x, t, b, c, d) {
			if ((t/=d) < (1/2.75)) {
				return c*(7.5625*t*t) + b;
			} else if (t < (2/2.75)) {
				return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
			} else if (t < (2.5/2.75)) {
				return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
			} else {
				return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
			}
		}
	});

	$.iframeAnimate = (function(){
		function it(){
			this.iframes = [];
			this.time = 600;
			this.current = 0;
			this.loading = '';
			this.type = 'down';
			this.now = null;
		}
		it.fn = it.prototype;
		it.fn.init = function(config){
			if(!config['iframes']) return;
			this.type = config['type'] || this.type;
			this.bind(config['iframes']);
			this.loading = config['loading'];
			var self = this;
			if(typeof config['init'] == 'undefined' || config['init']){
				this.mask(true);
				var first = $(this.iframes[this.current]);
				first.bind('load', function(){
					self.mask(false);
					$(this).trigger('_show').unbind('load');
				}).attr('src', first.attr('_src'));
			}
			if(typeof config['delegate'] != 'undefined'){
				this.delegate(config['delegate']);
			}
			return this;
		};
		it.fn.bind = function(iframes){
			var self = this;
			$.each((this.iframes = iframes), function(i, n){
				switch(self.type){
					case 'down' : 
						$(n).bind('_show', function(e){
							$(this).css({
								position : 'absolute',
								left : '1500px',
								display : 'block'
							});
							var height = this.contentWindow.jQuery('html').height();
							height < 580 && (height = 580);
							$(this).css({
								top : -(height + 100) + 'px',
								left : 0
							});
							$(this).animate({
								top : '0px'
							}, self.time, 'easeInOutCubic', function(){
								$(this).css('position', 'static').parent().height(height);
							});
						}).bind('_hide', function(e){
							$(this).hide();
						});
						break;
					case 'left' :
						$(n).bind('_show', function(e){
							$(this).fadeIn(self.time);
						}).bind('_hide', function(e){
							$(this).css('position', 'relative').animate({
								left : -$(this).outerWidth() + 'px'
							}, self.time, 'easeInOutCubic', function(){
								$(this).hide().css('left', 0);		
							});
						});
						break;
				}
				
			});
			return this;
		};
		it.fn.open = function(src){
			this.changeCurrent();
			this.mask(true);
			this.now = $(this.iframes[this.current]);
			this.before();
			var self = this;
			this.now.unbind('load').bind('load', function(){
				self.after();
			}).attr('src', src);
		};
		it.fn.before = function(){
			var self = this;
			this.mask(true);
			switch(this.type){
				case 'down':
					break;
				case 'left':
					$.each(this.iframes, function(i, n){
						if(i != self.current)
							$(n).trigger('_hide');
					});
					break;
			}	
		};
		it.fn.after = function(){
			var self = this;
			switch(this.type){
				case 'down':
					$.each(this.iframes, function(i, n){
						if(i != self.current)
							$(n).trigger('_hide');
					});					
					break;
				case 'left':
					break;
			}
			this.mask(false);
			this.now.trigger('_show');
		};
		it.fn.changeCurrent = function(){
			this.current++;
			if(this.current == this.iframes.length){
				this.current = 0;
			}
		};
		it.fn.mask = function(state){
			if(!this.maskdiv){
				this.maskdiv = $('<img src="'+ this.loading +'">').appendTo($(this.iframes[0]).parent()).css({
					display : 'none',
					position : 'absolute',
					top : '100px',
					left : '300px',
					height : '50px',
					width : '50px',
					'z-index' : 10000
				});
			}
			this.maskdiv[state ? 'show' : 'hide']();
		};
		it.fn.delegate = function(biaoshi){
			var self = this;
			$('body').delegate('a', 'click', function(){
				if($(this).attr('target') && $(this).attr('target') == biaoshi){
					self.open($(this).attr('href'));
					return false;
				}
			});
		};
		return new it;
	})();
})(jQuery);