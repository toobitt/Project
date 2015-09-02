$(function ($) {
	
	var _ = window._,
		View = window.Backbone.View,
		Model = window.Backbone.Model,
		Collection = window.Backbone.Collection,
		Time_Modal = globalData.time_modal, //-1，过去；0，现在；1，未来
		App = new View;
	
	var total = new Model;
	var currentNewOffset = 0;//当前显示的最新的一条的offset
	var Total = Model.extend({
		initialize: function () {
		},
		defaults: {
			total_2: globalData.total_2 || 0,
			total_all: globalData.total_all || 0
		},
		url: function () {
			return $.format('run.php?a=total&mid={0}&channel_id={1}', gMid, globalData.channel_id);
		}
	});
	var Msg = Model.extend({
		defaults: {
			status: 0,
			is_recommend: '0',
			is_warning: '0',
			is_shield: '0'
		},
		toggleChecked: function (ischecked) {
			if (typeof ischecked == 'boolean') {
				this.checked = ischecked;
			} else {
				this.checked = !this.checked;
			}
			this.trigger('change:checked', this, this.checked);
		},
		interactive_operate: function (type, options, flag) {
			//'改变来信的状态';
			var attr, newVal;
			
			options = options || {};
			attr = this.fieldMap[type];
			newVal = this.get(attr) == '0' ? '1' : '0';
			
			if (options.notSave) {
				this.set(attr, flag || newVal);
			} else {
				this.save(attr, newVal, {
					a: 'interactive_operate',
					type: type,
					flag: newVal
				});
			}
		},
		audit: function (options, status) {
			//'审核or打回';
			var newVal;
			
			options = options || {};
			newVal = this.get('status') != 1 ? 1 : 2;
			
			if (options.notSave) {
				this.set('status', status || newVal);
			} else {
				this.save('status', newVal, {
					a: 'audit',
					audit: newVal
				});
			}
		},
		save: function (attr, newVal, data) {
			this.set(attr, newVal);
			this.sync(data);
		},
		sync: function (data, modol, options) {
			if (data == 'delete') {
				if (options.notSave) return;
				$.post('run.php', {a: data, mid: gMid, id: this.id, ajax: 1});
			} else {
				$.post('run.php', $.extend({mid: gMid, id: this.id}, data));
			}
		},
		fieldMap: {
			'1': 'is_push',		//推送
			'2': 'is_recommend',	//推荐
			'3': 'is_warning',	//警告
			'4': 'is_shield'		//屏蔽
		}
	});
	
	var MsgItem = View.extend({
		tagName: 'li',
		className: 'clearfix msgItem',
		template: _.template( $('#template_msg').html() ),
		render: function () {
			this.$el.html( this.template( this.model.toJSON() ) );
			return this;
		},
		events: {
			'click .audit': 'audit',
			'click .delete': 'destroy',
			'click': 'toggleChecked'
		},
		initialize: function (options) {
			if (!options.el) {
				this.render();
			}
			this.initElMap();
			this.bindSpecialEvents();
			this.bindModelEvents();
		},
		initElMap: function () {
			var i, l, types, map;
			types = this.$('.type');
			attrs = ['is_recommend', 'is_warning', 'is_shield'];
			this.elMap = {};
			for (i = 0; i < 3; i++) {
				this.elMap[ attrs[i] ] = types.eq(i);
			}
		},
		bindSpecialEvents: function () {
			var i, l, types, type, partial;
			
			types = this.$el.find('.type');
			for (i = 0, l = types.size(); i < l; i++) {
				type = types.eq(i);
				//使用偏函数为click回调curry第一个参数type,注意到type=i+2
				partial = $.proxy(this.interactive_operate, this, i + 2);
				type.on('click', partial);
			}
		},
		bindModelEvents: function () {
			var me = this;
			this.model.on('change:checked', function (model, isChecked) {
				me.$el[isChecked ? 'addClass' : 'removeClass']('checked');
				me.$el.find('input:checkbox').prop('checked', isChecked);
			});
			//绑定状态改变
			this.model.on('change', function (model) {
				var attrs = ['is_recommend', 'is_warning', 'is_shield'];
				$.each(model.attributes, function (k, v) {
					if ( $.inArray(k, attrs) != -1 ) {
						if ( model.get(k) == '1' ) {
							me.elMap[k].addClass('hightlight');
						} else {
							me.elMap[k].removeClass('hightlight');
						}
					}
				});
			});
			//绑定审核状态改变
			this.model.on('change:status', function (model, status) {
				me.$('.audit').text( status == 1 ? '打回' : '审核' );
			});
			this.model.trigger('change', this.model);
			this.model.on('destroy', function (model, collection, options) {
				if (options.batch) {
					me.$el.remove();
				} else {
					me.$el.slideUp(function () { me.remove(); });
				}
			});
		},
		interactive_operate: function (type, event) {
			this.model.interactive_operate(type);
			return false;
		},
		toggleChecked: function (ischecked) {
			this.model.toggleChecked(ischecked);
		},
		audit: function () {
			this.model.audit();
			return false;
		},
		destroy: function () {
			var me = this;
			jConfirm('你确定要删除这条来信吗？', '删除提醒', function (yes) {
				if (yes) me.model.destroy();
			});
			return false;
		}
	});
	var RecommendMsg = View.extend({
		tagName: 'li',
		events: {
			'click .cancel': function () {
				this.model.audit();
			}
		},
		initialize: function () {
			this.model.on('change:status change:is_recommend', function (model, value) {
				var status, is_recommend;
				status = model.get('status');
				is_recommend = model.get('is_recommned');
				if (!(status == 1 && is_recommend == 1)) {
					this.$el.remove();
				}
			}, this);
		}
	});
	var Msgs = Collection.extend({
		model: Msg,
		url: function () {
			return $.format('run.php?a=get_interactive&mid={0}&channel_id={1}&start_end={2}&dates={3}', 
				gMid, globalData.channel_id, globalData.start_end, globalData.dates);
		},
		comparator: function (m1, m2) {
			return m1.get(this.paixuAttr) > m2.get(this.paixuAttr);
		},
		parse: function (response) {
			return array_values(response.interactive);
		},
		getTotal: function () {
			return total.get('total_all');
		},
		getChecked: function () {
			var ids = [];
			this.each(function (model) {
				if (model.checked) {
					ids.push(model.id);
				}
			});
			return ids;
		},
		batDelete: function () {
			var ids = this.getChecked();
			var me = this;
			if (ids.length) {
				me.cAjax({
					id: ids.join(','),
					a: 'delete',
					ajax: 1
				});
				$.each(ids, function (k, v) {
					me.get(v).destroy({notSave: true, batch: true});
				});
				return true;
			} else {
				return false;
			}
		},
		audit: function (status) {
			var ids = [];
			this.each(function (model) {
				if (model.checked) {
					model.audit({notSave: true}, status);
					ids.push(model.id);
				}
			});
			if (ids.length) {
				ids = ids.join(',');
				this.cAjax({
					id: ids,
					a: 'audit',
					audit: status
				});
				return true;
			} else {
				return false;
			}
		},
		interactive_operate: function(type) {
			var ids = [];
			this.each(function (model) {
				if (model.checked) {
					model.interactive_operate(type, {notSave: true}, 1);
					ids.push(model.id);
				}
			});
			if (ids.length) {
				ids = ids.join(',');
				this.cAjax({
					id: ids,
					a: 'interactive_operate',
					type: type,
					flag: 1
				});
				return true;
			} else {
				return false;
			}
		},
		cAjax: function (data) {
			if (data == 'delete') {
				$.post('run.php', {a: data, mid: gMid, id: this.id, ajax: 1});
			} else {
				$.post('run.php', $.extend({mid: gMid}, data));
			}
		},
		rise: function (callback, counts) {
			//单线加载
			if (this.fetching) {
				return;
			}
			var all, length;
			all = this.getTotal();
			length = this.length;
			if (all <= length) return;
			
			this.fetching = true;
			var me = this;
			this.fetch({
				data: {
					offset_flag: 1,
					//offset: length,
					counts: counts
				},
				complete: function () {
					me.fetching = false;
				},
				add: true,
				success: callback || $.noop
			});
		},
		incrOld: function (callback) {
			if (this.incring) {
				return;
			}
			this.incring = true;
			var length = this.length,
				all = this.getTotal(),
				me = this;
			if ( all <= (currentNewOffset + length) ) return;
			this.fetch({
				data: {
					offset: currentNewOffset + length,
					counts: 20
				},
				fetchOld: true,
				complete: function () {
					me.incring = false;
				},
				add: true,
				success: callback || $.noop
			})
		}
	});
	interactive = new Msgs(globalData.interactive);
	
	var TotalView = View.extend({
		el: $('#typeToggler'),
		events: {
			'click .typeToggle': function (e) {
				return;
				var me = $(e.currentTarget);
				
				this.$('.typeToggle').removeClass('current');
				App.trigger('typeChange.msg', me.addClass('current').data('type'));
			}
		},
		initialize: function () {
			this.model.bind('change', this.change, this);
		},
		change: function () {
			this.$el.find('a:first').html( '(' + this.model.get('total_all') + ')' );
			this.$el.find('a:last').html( '(' + this.model.get('total_2') + ')' );
		}
	});
	var All = View.extend({
		el: $('#msgListAll'),
		initialize: function () {
			this.$el2 = $('#msgList');
			
			var i, l, lis;
			lis = this.$('li');
			l = interactive.length;
			for (i = 0; i < l; i++) {
				new MsgItem({
					el: lis[i],
					model: interactive.at(i)
				});
			}
			
			interactive.on('add', this.addOne, this);
			
			this.initTypeToggler();
			this.newView = $({});
			this.prevTotal = total.get('total_all');
			this.initbatBar1();
			if (Time_Modal == 0) {
				this.initNewView();
			}
			
			this.$el.parent().on('scroll', $.proxy(this.showMore, this));
		},
		showMore: function (e) {
			var w = this.$el.parent();
		
			if (w.scrollTop() == this.$el.height() - w.height()) {
				var self = this;
				interactive.incrOld(function () {
					self.showMore();
				});
			}
		},
		initTypeToggler: function () {
			var me = this;
			var totalView = new TotalView({model: total});
			App.on('typeChange.msg', function (type) {
				if (+type == -1) {
					me.$el.show();
					me.$el2.hide();
				} else {
					me.$el.hide();
					me.$el2.show();
				}
			});
		},
		initbatBar1: function () {
			var me = this;
			me.batBar1 = $('#batBar-1');
			interactive.on('change', function () {
				if (interactive.length) {
					me.batBar1.removeClass('noMsg');
				} else {
					me.batBar1.addClass('noMsg');
				}
			});
			interactive.trigger('change');
			
			me.batBar1
				.on('click', 'input', function () {
					var prop = $(this).prop('checked');
					interactive.each(function (model) {
						model.toggleChecked(prop);
					});
				})
				.on('click', '.audit', function (status) {
					var btn = $(this);
					if (!interactive.audit(btn.data('v'))) {
						jAlert('请选择要' + btn.text() + '的来信', '提醒').position(btn);
					}
					
				})
				.on('click', '.interactive_operate', function () {
					var btn = $(this);
					if (!interactive.interactive_operate(btn.data('v'))) {
						jAlert('请选择要' + btn.text() + '的来信', '提醒').position(btn);
					}
				})
				.on('click', '.delete', function () {
					var btn = $(this);
					if (!interactive.getChecked().length) {
						jAlert('请选择要' + btn.text() + '的来信', '提醒').position(btn);
					} else {
						jConfirm('你确定要删除这些记录吗？', '删除提醒', function (yes) {
							if (yes) interactive.batDelete();
						}).position(btn);
					}
				});
		},
		initNewView: function () {
			var me = this;
			var newView = this.newView = $('#newMsgNum');
			
			this.nowNum = interactive.length;
			this.prevTotal = total.get('total_all');
			
			total.on('change', this.changeNew, this);
			interactive.on('remove', function () {
				me.prevTotal -= 1;
				total.set('total_all', total.get('total_all') - 1);
			});
			
			this.newView.on('click', function () {
				var totalNum = total.get('total_all');
				
				me.fetching = true;
				interactive.rise($.noop, totalNum - me.prevTotal);
				me.prevTotal = totalNum;
			});
			this.changeNew();
		},
		changeNew: function () {
			var me = this;
			var totalNum = total.get('total_all');
			currentNewOffset = totalNum - me.prevTotal;
			me.newView.text( '有 ' + currentNewOffset + ' 条新消息, 点击查看' );
			if (totalNum - me.prevTotal) {
				me.newView.show();
				me.$('.emptyTip').remove();
			} else {
				me.newView.hide();
			}
		},
		addOne: function (model, collection, options) {
			var item = new MsgItem({
				model: model
			});
			
			this.$('.emptyTip').remove();
			if (options.fetchOld) {
				this.$el.append( item.el );
			} else {
				if (this.fetching ) {
					this.$el.prepend( 
						'<li class="clearfix cut-off-rule"><label style="color:#aaa;">刚才你看到这里</label></li>'
					);
					item.$el.addClass('no-bottom-border');
					this.fetching = false;
				} 
				this.$el.prepend( item.el )
			}
			
			this.nowNum = interactive.length;
			this.changeNew();
		}
	});
	total = new Total;	
	new All;
	
	//直播模式
	if (Time_Modal == 0) {
		fetchTotal();
		goNext();
	}

	
	function fetchTotal() {
		//定时获取来信的总数
		setInterval(function () {
			total.fetch();
		}, 5 * 1000);
	}
	//闹钟，到时间刷新页面
	function goNext() {
		var index = globalData.current_program_index,
			cur = globalData.program[index],
			next = globalData.program[index + 1];
		if (!next) {
			//最后一个了
			return;
		}
		
		var interval = function (index, programs) {
			var nextTime = next.start_time;
			var now = (new Date).getTime();
			/*给的貌似是秒*/
			nextTime *= 1000;
			
			return nextTime - now;
		} ();
		setTimeout(function () {
			location.reload(true);
		}, interval);
	}
});

/*滚动条*/
$(function ($) {
	var wrap = $('.live-program-area');
	var wrapList = $('.msgListWrap');
	var win = $(window);
	function resize() {
		var h = win.height();
		var jiuzhen = 75;
		
		wrap.css('height',  Math.abs(h - jiuzhen) );
		
		var l_jiuzhen = 117 + 30 + 40 + 20;
		wrapList.css('height', Math.abs(h - l_jiuzhen) );
	}
	$(window).resize(resize);
	resize();
});