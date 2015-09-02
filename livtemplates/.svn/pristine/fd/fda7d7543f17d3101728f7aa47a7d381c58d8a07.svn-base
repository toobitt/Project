var App = Backbone;
(function () {
	var App = Backbone;
	var data;
	data = top.VedioInfoCollection.getAll();
	data = _.values(data);
	var OneLi = Backbone.View.extend({
		initialize: function() {
			this.model.on('change', this.render, this);
		},
		tagName: 'li',
		render: function() {
			var html = this.options.template(this.model.toJSON());
			this.$el.html(html);
			return this;
		},
		options: {
			template: function() { return '默认模板'; }
		}
	});
	var ListView = Backbone.View.extend({
		events: {
			'click .my-list': 'toggleCur',
			'click .play-box-next': 'next',
			'click .play-box-prev': 'prev',
			'click .play-box-close': 'cancelEdit'
		},
		toggleCur: function(e) {
			this.beCur( $(e.currentTarget) );
		},
		beCur: function(el) {
			this.$('.my-list').removeClass('selected');
			this.cur = el.addClass('selected').data('wrapperView');
			//this.initNextPrevBtn();
			//this.$el.addClass('open');
			//var src = $.createImgSrc( this.cur.model.get('img'), { width: 500, height: 375 } );
			//this.$('.play-box').html( '<img src=' + src + ' />' );
			App.trigger('edit.vod', this.cur.model);
			$('#edit_info_area').find('.form-dioption-keyword').hg_keywords();
		},
		initNextPrevBtn: function() {
			if ( this.cur.$el.next().size() ) {
				this.$('.play-box-next').show();
			} else {
				this.$('.play-box-next').hide();
			}
			if ( this.cur.$el.prev().size() ) {
				this.$('.play-box-prev').show();
			} else {
				this.$('.play-box-prev').hide();
			}
		},
		next: function(e) {
			var next = this.cur.$el.next();
			if (next.size()) {
				this.beCur(next);
			}
		},
		prev: function(e) {
			var prev = this.cur.$el.prev();
			if (prev.size()) {
				this.beCur(prev);
			}
		},
		cancelEdit: function() {
			this.$el.removeClass('open');
			this.$('.my-list').removeClass('selected');
			this.cur = null;
			App.trigger('cancelEdit.vod');
		},
		initialize: function() {
			this.collection.on('add', this.addOne, this);
			App.on('save-success', function(model, formdata) {
				model.set(formdata);
			});
		},
		addOne: function(one) {
			var li = new OneLi({ model: one, template: this.options.subTpl });
			this.$('ul').append( li.render().$el.addClass('my-list m2o-flex').data('wrapperView', li) );
		}
	});
	var EditBox = Backbone.View.extend({
		initialize: function() {
			var _this = this;
			App.on('edit.vod', this.edit, this);
			App.on('cancelEdit.vod', this.cancel, this);
			this.saveBtn = $('.common-form-save');
			this.saveBtn.on('click', function(e) {
				var formdata = _this.$('form').formToArray();
				_this.formdata = {};
				formdata.forEach(function(v) {
					_this.formdata[v.name] = v.value;				
				});
				if (_this.status == 'saving') return;
				_this.$('form').ajaxSubmit({
					dataType: 'json',
					beforeSend: function() {
						_this.status = 'saving';
						App.trigger('saving', _this.model);
					},
					success: function(data) {
						_this.status = 'save-success';
						App.trigger('save-success', _this.model, _this.formdata);
					},
					error: function(data) {
						_this.status = 'save-error';
						App.trigger('save-error', _this.model);
					},
					timeout: 10 * 1000
				});
			});
			this.cancel();
		},
		render: function() {
			var data = {};
			if (this.model) {
				data.info = this.model.toJSON();
			} else {
				data.info = {};
			}
			this.$('.ul').html(this.options.template(data));
			return this;
		},
		edit: function(model) {
			this.model = model;
			this.$el.addClass('open');
			this.saveBtn.show();
			this.render();
		},
		cancel: function(model) {
			this.model = null;
			this.$el.removeClass('open');
			this.saveBtn.hide();
			this.render();
		}
	});
	$(function () {
		var videos = new Backbone.Collection;
		var list = new ListView({
			el: $('#new_vod_list'),
			collection: videos,
			subTpl: _.template($('#new_vod_list_tpl').html())
		});
		videos.add(data);
		App.on('newVodCome', function(info) {
			videos.add(info);
		});
		new EditBox({ el: $('#edit_info_area'), template:  _.template($('#edit_info_area_tpl').html()) });
		
		var tip = (function() {
			var el = $('<div class="global-tip"></div>').appendTo('body');
			return {
				show: function(text) {
					el.show().html(text);
				},
				hide: function(text) {
					el.stop(true, true).fadeOut(600).html(text);
				}
			};
		})();
		App.on('saving', function(model) {
			tip.show('保存中，请等待...');
		});
		App.on('save-success', function(model) {
			tip.hide('保存成功！');
		});
		App.on('save-error', function(model) {
			tip.hide('保存失败！');
		});
		$(window).on('unload', function() {
			var fileIDs = videos.map(function(mode) {
				return mode.get('fileID');
			});
			top.VedioInfoCollection.reset(fileIDs);
			top.livUploadView.empty();
		});
	});
	
})();