(function($) {
	var methodMap = {
	};
	$.fn.hg_special_publish = function(options) {
		var value = arguments[1];
		options = options || {};
		options.el = $(this);
		return this.each(function() {
			var publish = $(this).data('publish'), method;
			if (publish) {
				method = methodMap[options];
				method && publish[method](value);
				return;
			} else {
				$(this).data('publish', new Special(options));
			}
		});
	};
	var node_tpl = 
		'<li data-id="${id}" data-name="${name}" {{if +is_last}}class="no-child"{{/if}}>' + '<span class="publish-name">${name}</span>' + '<span data-id="${id}" data-name="${name}" class="publish-child">&gt;</span>' + '</li>';
	var content_tpl = 
		'<div data-id="${id}" data-name="${$item.showName} > ${name}" class="no-child">' + '<span class="publish-name">${name}</span>' + '<span class="publish-child">&gt;</span>' + '</div>';
	var content2_tpl = 
		'<div _id="${column_id}" _speid="${special_id}" _name="${$item.showName} > ${name}" class="no-child">' + '<input type="checkbox" class="publish-checkbox" /> ' + '<span class="publish-name">${name}</span>' + '<span class="publish-child">&gt;</span>' + '</div>';
	var result_tpl =
		'<li _id="${column_id}" _columnid="${column_id}" _columnname=${column_name}  _speid="${special_id}" _name="${name}" title="${showName}" class="result_show">'+
			'<input type="checkbox" checked="checked" class="publish-checkbox" />'+
			'<span>${showName}</span>'+
		'</li>';
		
	var delegateEventSplitter = /^(\S+)\s*(.*)$/;
	var utils = {
		bind: function(obj, methods) {
			for (var m in methods) {
				m = methods[m];
				if (obj[m]) {
					obj[m] = $.proxy(obj[m], obj);
				}
			}
		},
		delegateEvents: function(el, events, ctx) {
			for (var key in events) {
				var method = events[key];
				if (!_.isFunction(method))
					method = ctx[events[key]];
				if (!method)
					throw new Error('Method "' + events[key] + '" does not exist');
				var match = key.match(delegateEventSplitter);
				var eventName = match[1], selector = match[2];
				method = $.proxy(method, ctx);
				eventName += '.delegateEvents';
				if (selector === '') {
					el.on(eventName, method);
				} else {
					el.on(eventName, selector, method);
				}
			}

		}
	}
	
	var Special = (function() {
		var Klass = function(options) {
			// new
			this.options = $.extend(this.options, options || {});
			this.el = $(this.options.el);
			utils.delegateEvents(this.el, this.events, this);
			this.init(options);
		};
		
		$.extend(Klass.prototype, {
			proxied: ['addNode','showResult'],
			$: function(s) {
				return this.el.find(s);
			},
			configureTemplate: function() {
				$.template("sp_node", node_tpl);
	        	this.templateNode = function(data, helper) {
	        		return $.tmpl('sp_node', data, helper);
	        	};
	        	$.template("sp_content1", content_tpl);
	        	this.templateContent1 = function(data, helper) {
	        		return $.tmpl('sp_content1', data, helper);
	        	};
	        	$.template("sp_content2", content2_tpl);
	        	this.templateContent2 = function(data, helper) {
	        		return $.tmpl('sp_content2', data, helper);
	        	};
			},
			init: function(options) {
				// 记录当前node的深度
				this.depth = options.depth || 0;
				this.hiddenInput = this.$('.publish-hidden');
				this.hiddenNameInput = this.$('.publish-name-hidden');
				this.hiddenClomnInput = this.$('.publish-column-hidden');
				this.hiddenshowNameInput = this.$('.publish-showname-hidden');
				this.htmlCache = new AjaxCache;
				this.ajaxid = 0;
				utils.bind(this, this.proxied);
				this.configureTemplate();
			},
			reinit : function(ids) {
				this.clearResult();
				if( ids && ids.split(',').length == 1){
					this.htmlCache.get(this.options.nodeapi[3] + ids , {
						after: this.showResult,
						type: 'json'
					});
				}
				this.htmlCache.get(this.options.nodeapi[0], {
					after: this.addNode,
					type: 'json'
				});
			},
			//调整下，让当前深度的可视
			adjustView : function() {
				this.el.find('.publish-inner-list').css({
					'margin-left' : -(this.depth + 1 - this.options.maxShow) * this.options.eachWidth
				});
			},
			beingCurrent : function(anchor) {
				anchor.closest('li').addClass('open').siblings().removeClass('open');
				this.el.find('.allcond').parent().removeClass('current');
			},
			addNode: function(data, ishtml) {
				if (!ishtml) { 
					var els = this.templateNode(data);
					els = $('<div class="publish-each"><ul></ul></div>').find('ul').append(els).parent();
					this.$('.publish-inner-list').append(els);
				} else {
					this.$('.publish-inner-list').append(data);
				}
				this.adjustView();
				this.showNewSpecial();
			},
			showNewSpecial : function(){
				var first = this.$('.publish-each:first').find('li:first');
				if( first.length ){
					first.trigger('click');
				}
			},
			clearResult : function(){
				this.$('.publish-result ul').html( '' );
			},
			showResult : function(data){
				this.addResult(data[0]);
			},
			events: {
				'click .publish-child': 'upDepth',
				'click .publish-node-back': 'downDepth',
				'click .publish-each li': 'showContent',
				'click .publish-content-1 > div': 'showContent2',
				'click .publish-content-2 > div': 'toggleResult',
				'click .publish-result li': 'checkRemove'
			},
			showContent: function(e) {
				if ($(e.target).is('.publish-child')) return;
				var el = $(e.currentTarget);
				
				this.beingCurrent( el.children() );
				this.addContent( el.data('id'), el.data('name'), 1 );
			},
			showContent2: function(e) {
				var el = $(e.currentTarget);
				
				el.addClass('open').siblings().removeClass('open');
				this.addContent( el.data('id'), el.data('name'), 2 );
			},
			addContent : function(id, showName, index) {
				var _this = this;
				var guardId = this.currentGuard();
				
				this.htmlCache.get(this.options.nodeapi[index].php + '&' + 
						this.options.nodeapi[index].id + '=' + id, {
					after: add,
					type: 'json'
				});
				
				function add(data) {
					if ( guardId !== _this.currentGuard(true) ) return;
					_this['addContent' + index](data, showName);
				}
			},
			addContent1: function(data, showName) {
				var conts = [];
				data = data[0];
				for (var id in data) {
					var one = data[id];
					one.id = id;
					conts.push(one);
				}
				this.$('.publish-content-1').html(
					this.templateContent1(conts, {showName: showName})
				);
				this.$('.publish-content-2').html('');
			},
			addContent2 : function(data, showName) {
				var conts = [];
				data = data[0];
				for (var id in data) {
					var one = {};
					one.name = data[id]['column_name'];
					one.column_id = id;
					one.special_id = data[id]['special_id'];
					conts.push(one);
				}
				this.$('.publish-content-2').html(
					this.templateContent2(conts, {showName: showName})
				);
				this.syncSelected();
			},
			currentGuard: function(fetch) {
				//为ajax请求生成当时的一个哨兵
				if (!fetch) ++this.ajaxid;
				return this.ajaxid + '-' + this.depth;
			},
			//增加深度
			upDepth : function(e) {
				var btn = $(e.currentTarget), 
					id = btn.data('id'), 
					name = btn.data('name');
				this.beingCurrent(btn);
				this.removeNeedless();
				this.depth += 1;
				
				var guardId = this.currentGuard();
				var _this = this;
				before();
				this.htmlCache.get(this.options.nodeapi[0] + '?fid=' + id, {
					after: after,
					type: 'json'
				});
				
				function before() {
					var html = '<div class="publish-each publish-wait">' + '<div class="publish-node-title">' + '<div class="publish-node-title-inner">' + '<a href="javascript:;" class="publish-node-back">' + name + '</a>' + '</div></div><ul></ul></div>';
					_this.addNode(html, true);
				}
				
				function after(data) {
					// 哨兵没变，修改dom
					if ( _this.currentGuard(true) !== guardId ) {
						var html = _this.templateNode(data);
						_this.$('.publish-each.publish-wait').find('ul')
							.html(html).end().removeClass('publish-wait');
						_this.displayCurNode(true);
					}
				}
			},
			//减小深度
			downDepth : function() {
				this.depth -= 1;
				this.adjustView();
				this.displayCurNode();
			},
			displayCurNode : function(first) {
				var each_ = this.el.find('.publish-each:eq(' + this.depth + ')'), cur;
				if ( (cur = each_.find('li.open')).length ) {
					cur.trigger('click');
				} else {
					each_.find('li:eq(0)').trigger('click');
				}
			},
			removeNeedless : function() {
				this.el.find('.publish-each:gt(' + this.depth + ')').remove();
			},
			toggleResult: function(e) {
				var li = $(e.currentTarget), checked, info = {}, id;

				//点击时input的状态，如果点的是input要取反
				checked = li.find('input').prop('checked');
				$(e.target).is('input') && ( checked = !checked );
				id = info.column_id = li.attr('_id');
				info.special_id = li.attr('_speid');
				info.column_name =li.find('.publish-name').text();
				info.name = li.attr('_name');
				info.showName = li.attr('_showName') || info.name;
				if (li.find('input').css('visibility') == "hidden")
					return;
				//更新result区域
				li.find('input').prop('checked', !checked);
				checked ? this.removeResult(id) : this.addResult(info);
				
				//显示一下
				this.$('.publish-result ul').addClass('hover');
				var _this = this;
				setTimeout(function() {
					this.$('.publish-result ul').removeClass('hover');
				}, 300);
			},
			checkRemove: function(e) {
				this.removeResult( $(e.currentTarget).attr('_id') );
				this.syncSelected();
			},
			syncSelected : function() {
				var self = this;
				this.$('.publish-content-2').find('>div').find('input').prop('checked', false);
				this.$('.publish-result').find('li').each(function() {
					var id = $(this).attr('_id');
					self.$('.publish-content-2').find('>div[_id="' + id + '"] input').prop('checked', true);
				});
			},
			removeResult : function(id) {
				this.$('.publish-result').find('li[_id="' + id + '"]').remove();
				if (this.$('.publish-result').find('li').size() == 0) {
					this.$('.publish-result').addClass('empty');
				}
				this.saveResult();
			},
			addResult : function(info) {
				this.$('.publish-result ul').append($.tmpl(result_tpl, info));
				this.$('.publish-result').removeClass('empty');
				this.saveResult();
			},
			resetResult : function(data) {
				var html = $.tmpl(result_tpl, data);
				this.result.html(html);
				this.result.closest('.publish-result')[html ? 'removeClass' : 'addClass']('empty');
				this.saveResult();
			},
			saveResult : function() {
				var speids = [] , column_ids = [] , column_names = [], show_names = [] ;
				this.$('.publish-result').find('li').each(function() {
					var speid = $(this).attr('_speid'),
					    columnid = $(this).attr('_columnid'),
					    showname = $(this).attr('title'),
					    columnname = $(this).attr('_columnname');
					speids.push(speid);
					column_names.push(columnname);
					column_ids.push(columnid);
					show_names.push(showname);
					
				});
				this.hiddenInput.val(speids.join(','));
				this.hiddenNameInput.val(column_names.join(','));
				this.hiddenClomnInput.val(column_ids.join(','));
				this.hiddenshowNameInput.val(show_names.join(','));
				//this.hiddenNameInput.val(names.join(','));
				this.options.change.call(self.el);
			},
			options : {
				nodeapi : {
					0 : 'get_special_column.php',
					1 : {
						php : 'get_special_column.php?a=get_special',
						id : 'sort_id'
					},
					2 : {
						php : 'get_special_column.php?a=get_special_column',
						id : 'special_id'
					},
					3 : './run.php?mid=' + gMid + '&a=get_scolumn&id='
				},
				depth : 0,
				eachWidth : 165,
				maxShow : 1,
				change: $.noop
			}
		});
		return Klass;
	})();

})($); 