(function($) {
	
var utils = {
	//转换链接的搜索部分为map
	search2map: function(search) {
		var map = {};
		search = search + '';
		if (search) {
			search.split('&').forEach(function(item) {
				item = item.split('=');
				map[item[0]] = item[1];
			});
		}
		return map;
	},
	map2search: function(map) {
		var key, value, search = '';
		for (key in map) {
			value = map[key];
			if (value) {
				search += ['&', key, '=', value].join('');
			}
		}
		return search ? search.slice(1) : '';
	}
};
/**
 * m2o节点树类，用于树形分类
 */
var NodeTree = (function() {
	var Klass = function(options) {
		this.el = options.el;
		this.depth = options.depth || 0;
		this.options = $.extend({}, this.options, options);
		this.htmlCache = {
			cache: {},
			set: function(id, data) { this.cache[id] = data; },
			get: function(id) { return this.cache[id]; }
		};
		this.ajaxid = 0;
		this.bindDomEvents();
	};
	$.extend(Klass.prototype, {
		//绑定dom事件
		bindDomEvents: function() {
			var _this = this;
			this.el
			.on('click', '.allcond', function() {
				_this.el.find('li').removeClass('cur');
				$(this).parent().addClass('current');
				_this.clearSearch();	//如果头部是高级搜索，需要清空高级搜索条件
			})
			.on('click', '.i', $.proxy(this.upDepth, _this))
			.on('click', '.first.normal', $.proxy(this.downDepth, _this))
			.on('click', '.l', function(e) {
				var a = $(this), href, search, searchB;
				_this.beingCurrent(a);
				if (a.data('_href')) {
					href = a.data('_href');
				} else {
					href = a.attr('href');
					a.data('_href', href);
				}
				search =  utils.search2map($('#nodeFrame')[0].contentWindow.location.search.slice(1));
				searchB = utils.search2map(href.split('?')[1]);
				$.extend(search, {a: 'show'}, searchB, {pp: ''});
				search = utils.map2search(search);
				href =  href.split('?')[0] + '?' + search;
				a.attr('href', href);
			})
			.on("click", ".addMoreNode", function() {
				var btn = $(this), ul = btn.prev();
				if (btn.hasClass("loading")) return;
				var cb = function (data) {
					if (!btn.parent().length) return;//说明btn已经不在文档中了
					var html = $(data);
					html = html.find("ul").children().removeClass("cur");
					ul.append(html);
					btn.removeClass("loading");
					if ( ul.children().size() >= btn.data("total") ) {
						btn.remove();
					}
				};
				
				btn.addClass("loading");
				$.get(_this.options.nodeapi, {
					fid: btn.data('fid'),
					offset: ul.children().size(),
					count: 30
				}, cb);
			});
		},
		options: {
			depth: 0,
			eachWidth: 137,
			maxShow: 1
		},
		
		//高级搜索模式切换到全部时，清空头部搜索条件
		clearSearch : function(){
			if( $.advanceSearchWidget && !$.advanceSearchWidget.data('isclose') ){
				$.advanceSearchWidget.search_pop('clearAllCondition');
			}
		},
		
		//调整下，让当前深度的可视
		adjustView: function() {
			this.el.css({
				'left': -(this.depth + 1 - this.options.maxShow) * this.options.eachWidth
			});
		},
		beingCurrent: function(anchor) {
			anchor.closest('li').addClass('cur').siblings().removeClass('cur');
			this.el.find('.allcond').parent().removeClass('current');
		},
		//增加深度
		upDepth: function(e) {
			var btn = $(e.currentTarget),
			    id = btn.data('id'),
			    name = btn.data('name'),
			    html = this.htmlCache.get(id),
			    needRequest = !html;
			this.beingCurrent(btn);
			this.removeNeedless();
			this.depth += 1;
			if (!html) {
				html = '<div class="each-node with-loading">' +
							'<div class="been_marked_second">' + 
								'<div class="first normal">' + 
									'<a href="javascript:;" class="back ">' + name +'</a>' +
						'</div></div></div>';
			}
			this.el.append(html);
			this.adjustView();
			this.displayCurNode(true);
			
			if (!needRequest) return;
			var depth = this.depth;
			var _ajaxid = ++this.ajaxid;
			var _this = this;
			$.get(this.options.nodeapi, {
				fid: id
			}, function(html) {
				html = $(html).find('.back').text( name ).end()[0].outerHTML;
				html = '<div class="each-node">' + html + '</div>';
				_this.htmlCache.set(id, html);
				//深度和请求都没改变，将请求到的html放进dom
				if (_ajaxid == _this.ajaxid && depth == _this.depth) {
					_this.el.find('.each-node:last').replaceWith(html);
					_this.displayCurNode(true);
				}
			});
		},
		//减小深度
		downDepth: function(e) {
			var btn = $(e.currentTarget);
			if ( btn.data('backing') ) {
				return;
			}
			btn.data('backing', true);
			this.depth -= 1;
			this.adjustView();
			this.displayCurNode();
		},
		//改变右侧的nodeFrame的链接，以显示当前的node
		displayCurNode: function(first) {
			var cond = first ? ':first' : '.cur';
			var a = this.el.find('.each-node:eq(' + this.depth + ') li' + cond + ' .l');
			a[0] && a[0].click();//.trigger('click');
		},
		removeNeedless: function() {
			this.el.find('.each-node:gt(' + this.depth + ')').remove();
		}
	});
	return Klass;
})();

window.NodeTree = NodeTree;


})(jQuery);