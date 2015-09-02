(function($){
	$(function(){
		(function(){
			var root = $('#hg_node_node');
			if(!root.get(0)) return;
			//$('#append_menu').hide();
			var next = root.next(), cname = '.each-node';
			root.css('position', 'relative').bind('_setwidth', function(event, width){
				width = width || $(this).find(cname).length * 137;
				$(this).css('width', width + 'px');
			}).bind('_move', function(event, direct, time, callback){
				//$(this).height($(this).find(cname).last().outerHeight(true)).css('overflow', 'hidden');
				time = time || 300;
				$(this).animate({
					left : (direct < 0 ? '+' : '-') + '=' + Math.abs(direct) * 137 + 'px'
				}, time, function(){
					callback && callback();
					/*if(direct < 0){
						root.animate({
							height : $(this).find(cname).last().outerHeight(true) + 'px'
						}, 300);
					}*/
				});
			}).bind('_go', function(event, id, name, type, depth, level, url){
				$(this).data('load-title',name);
				var cache = $(this).data('cache');
				cache && (cache = cache[id]);
				var clone = next.clone();
				if(cache){
					clone.html(cache);
				}else{
					clone.find('.back').html(name);
				}
				$(this).append(clone).trigger('_setwidth');
				/*暂时把cache关了*/
				cache = 0;
				if(cache){
					$(this).trigger('_loadtitle')/*.find(cname).last().find('.i').removeAttr('onclick')*/;
				}else{
					data = {
						'fid' : id, 
						'objname' : type, 
						'depth' : depth, 
						'level' : level,
						'node_callback' : 'hg_show_child_node_list',
						'node_template' : '_nodelist'
					};
					/*点击更多的接口地址，与最近的这个函数产生的一致*/
					globalData.url = url;
					globalData.data = data;
					hg_request_to(url, data);
				}
				clone.show();
				$(this).trigger('_move', [1]);

			}).bind('_back', function(){
				var marked = root.find(cname);
				if(marked.length == 1) return;
				$(this).trigger('_move', [-1, 300, function(){
					marked.last().remove();
					root.find(cname).last().find('.cur').find('.l').click();
				}]);
			}).bind('_loadtitle', function(){
				var title = $(this).data('load-title');
				var node = $(this).find(cname).last();
				node.find('.back').html(title);
				node.find('.l').eq(0).click();
				$(this).data('load-title', '');
			}).bind('_cache', function(event, id, cache){
				var _cache = $(this).data('cache') || {};
				_cache[id] = cache;
				$(this).data('cache', _cache);
			});
			root.delegate('.first', 'click', function(){
				root.trigger('_back');
			});
			root.delegate('.i', 'click', function(){
				var parent = $(this).parent();
				root.trigger('_go', [parent.attr('nodeid'), parent.attr('nodename'), parent.attr('nodetype'), parent.attr('nodedepth'), parent.attr('nodelevel'), parent.attr('nodeapi')]);
			});
			
			root.on('click', '.a', function(){
				if(!$(this).hasClass('cur')){
					var node = $(this).parents('.each-node').eq(0);
					node.find('.first').removeClass('current');
					node.find('.cur').removeClass('cur');
					$(this).addClass('cur');
				}
				$(this).find('.l').removeClass('hover');
			});
			
			root.on('click', '.top', function(){
				if(!$(this).hasClass('current')){
					$(this).addClass('current');
					var node = $(this).parents('.each-node').eq(0);
					node.find('.cur').removeClass('cur');
				}
			});
			
			root.on('mouseenter', '.l', function(){
				$(this).addClass('hover');
			}).on('mouseleave', '.l', function(){
				$(this).removeClass('hover');
			});
			
			root.on('mouseenter', '.i', function(){
				$(this).addClass('hover');
			}).on('mouseleave', '.i', function(){
				$(this).removeClass('hover');
			});
		})();
	});

	(function(){		
		var old = window.onload;
		window.onload = function(){
			old && typeof old == 'function' && old();
			hg_show_child_node_list = function(html, objname, id, depth, nodata, level){
				var cname = '.each-node';
				var root = $('#hg_node_node');
				root.find(cname).last()/*.css('opacity', 0)*/.html(html).trigger('_loadtitle')/*.animate({
					opacity : 1
				}, 500, function(){
					root.animate({
						height : root.find(cname).last().outerHeight(true) + 'px'
					}, 300);
				})*/;
				root.trigger('_cache', [id, html]);
			};
			hg_showmainwin = function(html, selfurl, nodetype){
				var cur = window.self;
				if(window.parent != window.self){
					cur = window.parent;
				}
				nodetype = parseInt(nodetype);
				var cname = '.each-node';
				cur.$('#append_menu')[nodetype ? 'hide' : 'show']();
				var all = cur.$('#hg_node_node').css('left', '0').find(cname);
				all.not(all.first().get(0)).remove();
				all.first().html(html);
				cur.$('#nodeFrame').attr('src',selfurl);
			};
		}
	})();
})(jQuery);

