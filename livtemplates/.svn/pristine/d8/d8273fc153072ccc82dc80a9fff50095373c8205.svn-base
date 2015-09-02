;$(function ($) {
	//没有channel_id的情况
	if (!globalData.channel_id) {
		return;
	}
	
	var _ = window._;
	var Backbone = window.Backbone;
	var View = Backbone.View;
	var Model = Backbone.Model;
	var Collection = Backbone.Collection;
	var App = new View;
	var total,
		totalView,
		recommendList,
		allList;
	
	var infoUrl = $.format('run.php?a=total&mid={0}&channel_id={1}&start_end={2}&dates={3}', gMid, globalData.channel_id, globalData.start_end, globalData.dates);
	function fetchInfo() {
		$.getJSON(infoUrl, function (data) {
			total.set({
				total_2: data.total_2,
				total_all: data.total_all
			});
			programModel.set({
				interactive_program: data.interactive_program
			});
		});
	}
	/*用于存放当前节目两类来信的总数*/
	var Total = Model.extend({
		initialize: function () {
		},
		url: function () {
			return $.format('run.php?a=total&mid={0}&channel_id={1}', gMid, globalData.channel_id);
		}
	});
	//表示一条来信
	var Msg = Model.extend({
		sync: function (method, model) {
			$.post('run.php', {
				a: 'is_read',
				mid: gMid,
				id: model.get('id'),
				is_read: +model.get('is_read')
			}, function (data) {
			
			},'json');
		},
		toggle: function () {
			this.save('is_read', !this.get('is_read'));
		}
	});
	
	
	//来信集合
	var Msgs = Collection.extend({
		model: Msg,
		url: function () {
			return $.format('run.php?a=get_interactive_info&mid={0}&channel_id={1}&start_end={3}&dates={4}&type=1', 
				gMid, globalData.channel_id, this.type || '', globalData.start_end, globalData.dates);
		},
		comparator: function (m1, m2) {
			return m1.get(this.paixuAttr) > m2.get(this.paixuAttr);
		},
		paixuAttr: 'id',
		parse: function (response) {
			return response[0] || [];
		},
		getTotal: $.noop,
		rise: function (callback) {
			if (this.fetching) {
				return;
			}
			var all, length;
			this.fetching = true;
			var self = this;
			this.fetch({
				data: {
					id: self.last() && self.last().get(self.paixuAttr) || ''
				},
				complete: function () {
					self.fetching = false;
				},
				add: true,
				success: callback || $.noop
			});
		},
		incrOld: function () {
			if (this.incring) {
				return;
			}
			if (this.noold) return;
			var me = this;
			this.incring = true;
			this.fetch({
				data: {
					old: 1,
					id: me.first().get(me.paixuAttr),
					counts: 20
				},
				fetchOld: true,
				complete: function () {
					me.incring = false;
				},
				add: true,
				success: function (c, data) {
					if (!data[0]) {
						me.noold = true;
					}
				}
			})
		}
	});
	
	//来信集合的视图
	var MsgList = View.extend({
		initialize: function (options) {
			_.bindAll(this, 'addOne', 'toggle', 'showMore', 'reset');
			var me = this;
			this.itemCount = 0;
			this.tempItemSet = [];
			this.$el.parent().scroll(function () {
				if (me.$el.is(":hidden")) return;
				var w = me.$el.parent();
				if (w.scrollTop() == me.$el.height() - w.height()) {
					me.collection.incrOld();
				}
			});
			this.collection.bind('add', this.addOne);
			this.collection.bind('reset', this.reset);
			App.bind('typeChange.msg', this.toggle);
			App.bind('showMore.msg', this.showMore);
			
			if (this.collection.length) {
				this.reset();
			} else {
				this.$el.html('<li class="emptyTip"><p class="emptyTip">没有此类来信！</p></li>');
			}
		},
		addOne: function (msg, co, options) {
			options = options || {};
			msg.set({
				'is_read': +msg.get('is_read') ? true : false
			});
			var item = new MsgItem({
				model: msg
			});
			this.$('.emptyTip').remove();

			if (options.fetchOld) {
				this.tempItemSet.unshift( item.render().el );
				if (this.itemCount == co.length - 1) {
					this.$el.append( $(this.tempItemSet) );
					this.tempItemSet = [];
				}
			} else {
				this.tempItemSet.push( item.render().el );
				if (this.itemCount == co.length - 1) {
					this.$el.prepend( $(this.tempItemSet) );
					this.tempItemSet = [];
				}	
			}
			this.itemCount++;
		},
		reset: function () {
			this.$el.empty();
			this.collection.each(function (model) {
				this.addOne(model, this.collection);
			}, this);
		},
		showMore: function () {
			this.collection.rise($.noop);return;
		},
		toggle: function (type) {
			if (this.options.type != type) {
				this.$el.hide();
			} else {
				this.$el.show();
			}
		}
	});
	//一条来信的视图
	var MsgItem = View.extend({
		tagName: 'li',
		className: 'clearfix',
		events: {
			'click .live-readBtn': 'toggleReaded'
		},
		initialize: function (options) {
			_.bindAll(this, 'render');
			
			this.model.bind('change', this.render);
			return this;
		},
		template: _.template( $('#template_msg').html() ),
		render: function () {
			this.$el.html( this.template( this.model.toJSON() ) );
			this.$el[ this.model.get('is_read') ? 'addClass' : 'removeClass' ]('readed');
			return this;
		},
		toggleReaded: function () {
			this.model.toggle();
		}
	});
	//来信总数的视图
	var TotalView = View.extend({
		events: {
			'click .typeToggle': function (e) {
				var me = $(e.currentTarget);
				this.$('.typeToggle').removeClass('current');
				App.trigger('typeChange.msg', [me.addClass('current').data('type')]);
			}
		},
		initialize: function () {
			_.bindAll(this, 'change');
			this.model.bind('change', this.change);
		},
		change: function () {
			this.$el.find('a:first').html( '(' + this.model.get('total_2') + ')' );
			this.$el.find('a:last').html( '(' + this.model.get('total_all') + ')' );
		}
	});
	
	var programModel = new (Model.extend({
		initialize: function () {
			this.toNext();
		},
		id: globalData.in_program_id,
		mutex_audit: function (id) {
			$.post("run.php", {
				a: "mutex_audit",
				mid: gMid,
				id: id,
				in_program_id: this.id
			}, fetchInfo);
		},
		toNext: function (curShow) {
			var prgs = this.get('interactive_program');
			curShow = curShow || null;
			if (!curShow) {
				_.each(prgs, function (v, index) {
					if (v['is_now']) curShow = index;
				});
			}
			
			/*不是最后一个，加个闹钟*/
			if ( curShow && curShow != (prgs.length - 1) ) {
				var self = this;
				var interval = this.getInterval( curShow, prgs );
				this.timer = setTimeout(function () {
					prgs[curShow]['is_now'] = false;
					prgs[curShow + 1]['is_now'] = true;
					self.toNext( curShow + 1);
				}, interval);
			}
		},
		cReset: function () {
			if (this.timer) {
				clearTimeout(this.timer);
			}
			var self = this;
			this.fetch({
				success: function () {
					self.toNext();
				}
			});
		},
		defaults: {
			interactive_program: globalData.interactive_program
		},
		url: function () {
			$.format('run.php?a=get_interactive_program&mid={0}&channel_id={1}&start_end={2}', gMid, globalData.channel_id, globalData.start_end);	
		},
		getInterval: function (index, programs) {
			var nextTime = programs[index + 1].start_time;
			var now = (new Date).getTime();
			
			/*给的貌似是秒*/
			nextTime *= 1000;
			return nextTime - now;
		}
	}));
	var programView = new (View.extend({
		el: $('#programView'),
		model: programModel,
		events: {
			"click li": function (e) { 
				if (globalData.time_modal == 0) {
					this.model.mutex_audit( $(e.currentTarget).data('id') );
				}
			}
		},
		initialize: function () {
			_.bindAll(this, 'render');
			this.model.bind('change', this.render);
			this.render();
		},
		render: function () {
			this.$el.html( this.template( this.model.toJSON() )  );
			
			/*var $el = this.$el;
			var cur = $el.find('li.current');
			var list = $el.find('li');
			
			//没有当前的节目环节/
			if (!cur.length) {
				return;
			}
			if (cur[0] == list[0]) {
				$el.find('li:gt(2)').hide();
			} else if (cur[0] == list[list.length-1]) {
				list.hide();
				cur.show();
			} else {
				list.hide();
				cur.add(cur.next()).add(cur.prev()).show();
			}
			*/
		},
		template: _.template( $('#template_program').html() )
	}));
	
	total = new Total({
		total_2: globalData.total_2,
		total_all: globalData.total_all
	});
	
	var recommendCollection = new Msgs;
	recommendCollection.type = 0;
	recommendCollection.getTotal = function () {
		return total.get('total_2');
	};
	recommendCollection.url = $.format('run.php?a=get_interactive_info&mid={0}&channel_id={1}&start_end={2}&dates={3}', 
				gMid, globalData.channel_id, globalData.start_end, globalData.dates);
	recommendCollection.paixuAttr = "recommend_time";
	if (globalData.interactive_2) {
		recommendCollection.add(globalData.interactive_2);
	}
	var allCollection = new Msgs;
	allCollection.type = -1;
	allCollection.getTotal = function () {
		return total.get('total_all');
	};
	if (globalData.interactive) {
		allCollection.add(globalData.interactive);
	}
	
	
	totalView = new TotalView({
		el: $('#typeToggler'),
		model: total
	});
	recommendList = new MsgList({ 
		el: $('#msgList'),
		collection: recommendCollection,
		type: 0
	});
	allList = new MsgList({ 
		el: $('#msgListAll'), 
		collection: allCollection,
		type: -1
	});
	
	total.bind('change', function () {
		App.trigger('showMore.msg');
	});
	//列表相关end
	
	if (globalData.time_modal == 0) {
		setInterval(fetchInfo, 5000);
	}
});

/*滚动条*/
$(function ($) {
	
	var wrapList = $('.msgListWrap');
	var win = $(window);
	function resize() {
		var h = win.height();
		
		var l_jiuzhen = 117 + 30;
		wrapList.css('height', Math.abs(h - l_jiuzhen) );
	}
	$(window).resize(resize);
	resize();
});