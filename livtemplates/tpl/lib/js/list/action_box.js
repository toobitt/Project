
;(function() {
	var ActionBox = Backbone.View.extend({
        initialize: function() {
        	var _this = this;
			this.configureTemplate();
            window.hg_show_opration_info = function(id) {
				_this.open(recordCollection.get(id), recordViews.get(id));
			};
			window.hg_close_opration_info = function() {
				_this.close();
			};
			
			// 给li绑定事件，触发actionBox的open/close
			this.listenToRecordViews();
        },
        events: {
            'click .record-edit-close': 'close',
            'click .record-edit-confirm a,.record-edit-confirm-close': 'getConfirmRet',
            'click .record-edit-back-close': 'backMainPanel',
            'click .record-edit-play-shower': 'render_play',
            'click .record-edit-info-shower': 'render_info',
        },
        listenToRecordViews: function() {
        	var _this = this;
        	var events = recordViews.events || {};
        	events['click .common-list-data'] = function(event) {
				var target = $(event.target);
	        	if ( target.is('.common-list-biaoti') ) {
	        		_this.openForEl(event.currentTarget);
	        	} else if ( target.parents('.common-list-right').size() ) {
	        		var text = target.text().trim();
	        		if (!text && !target.find('img').size()) {
	                    if (target.find('.need-switch').size()) return;
	                    _this.openForEl(event.currentTarget);
	                }
	        	}
			};
        	recordViews.delegateEvents(events);
        },
        openForEl: function(el) {
        	var view = recordViews.getViewByEl(el);
        	this.toggle(view.model, view);
        },
        backMainPanel: function() {
			this.$('.record-edit-play').empty();
       		this.$('.record-edit-more-info').empty();
            this.$el.attr('class', '');
            this.adjustLook();        	
        },
        getConfirmRet: function(e) {
        	var cb = this.confirmCallBack;
        	
        	this.confirmCallBack = null;
            if ( $(e.target).text().trim() == '确定' ) {
            	cb(true);
            } else {
            	cb(false);
            }
            this.backMainPanel();
        },
        confirm: function(callback) {
            this.$el.addClass('confirm-model');
            this.confirmCallBack = callback || $.noop;
            this.adjustLook();
        },
        render_info: function() {
        	this.$el.addClass('info-model');
        	this.$('.record-edit-more-info').html(this.template_info(this.boss_model.toJSON()));
        	this.adjustLook();
        },
        render_play: function() {
            this.$el.addClass('play-model');
            this.$('.record-edit-play').html(this.template_play(this.boss_model.toJSON()));
            this.adjustLook();
        },
        render: function() {
            return this.$el.html(this.template(this.boss_model.toJSON()));
        },
        toggle: function(boss_model, boss_view) {
        	if (this.boss_model == boss_model) {
        		this.close();
        	} else {
        		this.open(boss_model, boss_view);
        	}
        },
        getPositionEl: function() {
        	return this.boss_view.$('.common-list-i');
        },
        adjustLook: function(animate) {
        	var _this, iOffset, iconH, r, t, wh, w, h, limitH, needUpd, stopfn;
        	
        	_this = this;
        	iOffset = this.iOffset || this.getPositionEl().offset();
        	iconH = this.iconH || this.getPositionEl().height();
        	this.iOffset = iOffset;
        	this.iconH = iconH;
        	r = $(window).width() - iOffset.left;
        	t = iOffset.top;
        	wh = this.getWH();
        	w = wh[0];
        	h = wh[1];
        	limitH = $(window).height();
        	needUpd = (t + h >= limitH); //是否需要updown
			this.$el[needUpd ? 'addClass' : 'removeClass']('upmodel'); 
			this.topForHide = t + iconH;
        	if (animate) {
        		stopfn = function() { _this.$el.css({ width: '', height: '' }); };
        		this.$el.css({
	                height: 0,
	                width: 0,
	                right: r,
	                top: needUpd ? (t + iconH) : t
	            }).stop().show().animate({
	            	width: w,
	            	height: h,
	            	right: r,
	            	top: needUpd ? (t - h + iconH) : t
	            }, 200, stopfn);
        	} else {
        		this.$el.css({
	            	top: needUpd ? (t - h + iconH) : t,
	            	right: r
        		});
        	}
        },
        open: function(boss_model, boss_view) {
        	if (this.disabled) return;
            if (!this.boss_model && !boss_model) return;
            
            this.boss_model = boss_model;
            this.boss_view = boss_view;
            this.boss_view.beCurrent();
            this.iOffset = null;
            this.iconH = null;
            this.render().removeAttr('class');
            this.adjustLook(true);
        },
        close: function() {
        	if (this.$el.is(':hidden')) return;
        	
        	
        	if (this.boss_view) {
            	this.boss_view.unbeCurrent();
            }
            this.boss_model = null;
            this.boss_view = null;
            this.iOffset = null;
            this.iconH = null;
            
            var stopfn, _this = this, wh, attrs;
            
            wh = this.getWH();
            this.$el.css({ width: wh[0], height: wh[1] });
            this.$('.record-edit').hide();
            this.$('.record-edit-play').empty();
            
            stopfn = function() { _this.$el.removeAttr('style'); };
            attrs = { width: 0, height: 0 };
            if ( this.$el.hasClass('upmodel') ) {
            	attrs.top = this.topForHide;
            }
            this.$el.stop().animate(attrs, 200, stopfn);
        },
        configureTemplate: function() {
        	$.template("info", $('#record-edit').html());
        	this.template = function(data) {
        		return $.tmpl('info', data);
        	};
        	$.template("play", _.unescape($('#vedio-tpl').html()));
        	this.template_play = function(data) {
        		return $.tmpl('play', data);
        	};
        	if ( !$("#record-info-tpl").size() ) return;
        	$.template("record-info", _.unescape($("#record-info-tpl").html()));
        	this.template_info = function(data) {
        		return $.tmpl('record-info', data);
        	};
        },
        getWH: function() {
            this.$el.show().css({width: '', height: ''});
            return [this.$el.width(), this.$el.height()];
        },
        ICON_HEIGHT: 28
    });
    
    window.ActionBox = ActionBox;
})();