define(function (require, exports, module) {
	var $ = require('$');
	var _ = require( 'underscore');
	var Backbone = require( 'Backbone' );
	
	var App = new Backbone.View; //作为全局通信信道
	
	var Tag = Backbone.Model.extend({
		sync: $.noop,
		defaults: {
			content: ''
		}
	});
	var Tags = Backbone.Collection.extend({
		model: Tag
	});
	var TagView = Backbone.View.extend({
		events: {
			'click .tag-destroy': 'destroy'
		},
		tagName: 'li',
		template: _.template('<span><%- content %></span><a class="tag-destroy">x</a>'),
		initialize: function () {
			_.bindAll(this, 'render', 'destroy', 'remove');
			this.model.bind('change', this.render);
			this.model.bind('destroy', this.remove);
		},
		render: function (tag) {
			this.$el.html( this.template(this.model.toJSON()) );
			return this;
		},
		destroy: function () {
			App.trigger('destroyFromUsr', this.model.get('content'));
			this.model.destroy();
		}
	});
	var SysTag = Backbone.Model.extend({
		sysnc: $.noop,
		defaults: {
			selected: false,
			content: ''
		}
	});
	var SysTagView = Backbone.View.extend({
		events: {
			'click': 'pub'
		},
		tagName: 'li',
		template: _.template('<span class="<%= selected ? "tags-selected" : "tags-no-selected" %>"><%- content %></span>'),
		initialize: function () {
			_.bindAll(this, 'render', 'pubBack');
			this.model.bind('change', this.render);
			App.bind('addedTag', this.pubBack);
			App.bind('addedFromUsr', _.bind(function (content) {
				if (content == this.model.get('content') ) {
				
					this.model.set({
						selected: true
					});	
				}
			}, this));
			App.bind('destroyFromUsr', _.bind(function (content) {
				if (content == this.model.get('content') ) {
					this.model.set({
						selected: false
					});	
				}
			}, this));
		},
		render: function (tag) {
			this.$el.html( this.template(this.model.toJSON()) );
			return this;
		},
		pub: function () {
			if ( this.model.get('selected') ) {
				App.trigger('destroyTag', this.model.get('content'), this);
			} else {
				App.trigger('addTag', this.model.get('content'), this);
			}
		},
		pubBack: function (success, me) {
			if (this != me) return;
			if (success) {
				this.model.set({
					selected: !this.model.get('selected')
				});					
			}
		}
	});
	var TagsView = Backbone.View.extend({
		events: {
			'focus .tag-factory input': function () {
				this.label.hide();
			},
			'blur .tag-factory input': function () {
				if (this.input.val()) return;
				this.label.show();
			},
			'keypress .tag-factory input': 'createOnEnter',
			'click .tag-add': 'createOnClick'
		},
		initialize: function () {
			this.tags = new Tags;
			this.list = this.$('.tag-items:first');
			this.syslist = this.$('.tag-items:eq(1)');
			this.add = this.$('.tag-add');
			this.input = this.$('.tag-factory input');
			this.label = this.input.siblings('label');
			this.hidden = this.$('input[type=hidden]');
			
			_.bindAll(this, 'addOne', 'addAll', 'fillHidden', 'createFromSys', 'destroyFromSys');
			this.tags.bind('add', this.addOne);
			this.tags.bind('add', this.fillHidden);
			this.tags.bind('remove', this.fillHidden)
			
			this.list.empty();
			var input = this.options.input;
			this.hidden.attr('name', input.name);
			if ( $(input).attr('required') !== undefined) {
				this.hidden.attr('required', "true");
				this.hidden.attr('agent', '.tag-factory input');
			}
			var brief = this.brief = $(input).data('brief');
			var val = input.value;
			if ( val ) {
				val = val.split(',');
				_.each(val, _.bind(function (tag) {
					this.tags.add({content: tag});
				}, this));
			}
			
			var value = $(input).attr('systags');
			if ( $(input).attr('_rangelength') ) {
				this.options.rangelength = +$(input).attr('_rangelength');
			}
			if ( value ) {
				try {
					value = $.parseJSON(value);
				} catch (e) {
					value = [];
				}
			}
			_.each(value, _.bind(function (tag) {
				this.syslist.append( (new SysTagView({model: new SysTag({
					content: tag,
					selected: $.inArray(tag, val) !== -1
				})})).render().el );
			}, this));
			App.bind('addTag', this.createFromSys);
			App.bind('destroyTag', this.destroyFromSys);
			$(input).replaceWith(this.$el);
			
			/*加个提交前，如果输入框中有数据也算进去的功能*/
			this.createOnSubmit();
			
			/*加个显示描述功能*/
			if ( brief && !val) {
				this.$('.small').html(brief);
			}
			if ( brief ) {
				this.tags.bind('add remove', function () {
					if (this.tags.length) {
						this.$('.small').empty();
					} else {
						this.$('.small').html(brief);
					}
				}, this);
			}
		},
		addOne: function (tag) {
			var view = new TagView({
				model: tag
			});
			this.list.append(view.render().el);
		},
		addAll: function () {
			this.tags.each(this.addOne);
		},
		createOnEnter: function (e) {
			if (e.keyCode !== 13) return;
			var ret = this.create({}, this.input.val() );
			if ( ret ) {
				App.trigger('addedFromUsr', ret );
			}
			return false;
		},
		createFromSys: function (content, sys) {
			App.trigger('addedTag', this.create({}, content), sys);
		},
		createOnClick: function () {
			var ret = this.create({}, this.input.val() );
			if ( ret ) {
				App.trigger('addedFromUsr', ret );
			}
		},
		createOnSubmit: function () {
			/*加个提交前，如果输入框中有数据也算进去的功能*/
			this.$el.closest('form').submit(_.bind(function () {
				this.create({}, null, true);
			}, this));
		},
		create: function (e, val, silent) {
			if (val == undefined) {
				val = this.input.val();
			}
			val = val.replace(/,/g, '');
			val = $.trim(val);
			if ( !$.trim(val) ) {
				return false;
			}
			if ( this.list.children().size() >= this.options.rangelength ) {
				silent || this.showWarn('标签不能超过' + this.options.rangelength + '个');
				return false;
			}
			var isrepeat = false, me = this;
			this.tags.each(_.bind(function (tag) {
				if ( tag.get('content') === val ) {
					silent || this.showWarn('此标签已存在');
					isrepeat = true;
					return false;
				}
			}, this));
			if ( isrepeat ) return false; 
			this.tags.add({content: val});
			this.input.val('');
			return val;
		},
		destroyFromSys: function (content, sys) {
			App.trigger('addedTag', true, sys);
			this.tags.each(function (tag) {
				if ( tag.get('content') === content ) {
					tag.destroy();
					return false;
				}
			})
		},
		fillHidden: function () {
			this.hidden.val( this.tags.map(function (tag) { return tag.escape('content'); }).join(',') );
		},
		showWarn: function (msg) {
			//hAlert(msg);
			alert(msg);
		},
		options: {
			rangelength: 6
		}
	});
	return TagsView;
})
	