(function($){
	var defaultOption = {
		time : 500,
		loadid : 'iframe-loading',
		loadimg : '',
		loadleft : 100,
		loadtop : 100,
		loadwidth : 50,
		loadheight : 50,
		initwidth : 0,
		initheight : 0,
		auto : true,
		delegate : true
	};
	$.fn.iframeAnimate = function(options){
		options = $.extend({}, defaultOption, options);
		return this.each(function(){
			var self = $(this);
			var left = options['loadleft'];
			var load = $('#' + options['loadid']);
			if(!load[0]){
				load = $('<div id="'+ options['loadid'] +'"><img src="'+ options['loadimg'] +'"/></div>').appendTo(self.parent()).css({
					position : 'absolute',
					'z-index' : 1000,
					left : 0,
					top : 0,
					background : '#fff'
				}).bind('_show', function(){
					/*var first = $(this).data('fisrt');
					if(!first){
						$(this).data('first', true);
					}
					var p, pw, ph;
					if(options['initwidth'] && options['initheight'] && !first){
						pw = options['initwidth'];
						ph = options['initheight'];
					}else{
						p = self.parent().css('position', 'relative');
						pw = p.width();
						ph = p.height();
					}
					$(this).stop().css({
						opacity : 1,
						width : pw + 'px',
						height : ph + 'px' 
					}).show();*/
					$(this).show();
				}).bind('_hide', function(){
					/*$(this).fadeOut(options['time'], function(){
						$(this).css('opacity', 1);
					});*/
					$(this).hide();
				});
				load.find('img').css({
					position : 'absolute',
					left : options['loadleft'] + 'px',
					top : options['loadtop'] + 'px',
					width : options['loadwidth'] + 'px',
					height : options['loadheight'] + 'px'
				});
			}
			$(this).bind('load', function(){
				load.trigger('_hide');
				$(this).trigger('_ishow');
			}).bind('go', function(event, src){
				load.trigger('_show');
				var me = $(this);
				//此处延迟一秒加载iframe，为了给左边节点动画留点渲染时间，因为iframe的http请求太多了，导致浏览器瞬间CPU高了，页面卡，！！无语！！先这样吧，以后需要优化！！！
				setTimeout(function(){
					me.attr('src', src).trigger('_ihide');
				}, 800);
			}).bind('_ishow', function(){
				$(this).animate({
					opacity : 1
				}, options['time']);
			}).bind('_ihide', function(){
				$(this).css('opacity', 0);
			});
			if(options['auto']){
				$(this).trigger('go', [$(this).attr('_src')]);
			}
			if(options['delegate']){
				var name = $(this).attr('name');
				$('body').delegate('a', 'click', function(){
					if($(this).attr('target') == name){
						self.trigger('go', [$(this).attr('href')]);
						return false;
					}
				});
			}
		});
	}
})(jQuery);