;(function() {
	var ActionBox = Backbone.View.extend({
        initialize: function() {
            var old_hg_ajax_post, _this = this;
			this.configureTemplate();
            App
            .on('openDragMode', function() {
                _this.close();
                _this.disabled = true;
            })
            .on('closeDragMode', function() {
                _this.disabled = false;
            })
            .on('toggleActionBox', this.toggle, this)
            .on('closeActionBox', this.close, this);
        },
        events: {
            'click .record-edit-close': 'close',
            'click .record-edit-confirm a,.record-edit-confirm-close': function(e) {
                var _base;
                this.$el.removeClass('confirm-model');
                if (typeof(_base = this.$(e.target).data('callback')) === "function") {
                    _base(true);
                }
                this.$(e.target).data('callback', null);
                this.adjustLook();
            },
            'click .push-edit-confirm a,.push-edit-confirm-close': function(e) {
                var _base;
                this.$el.removeClass('push-model');
                if (typeof(_base = this.$(e.target).data('callback')) === "function") {
                    _base(true);
                }
                this.$(e.target).data('callback', null);
                this.adjustLook();
            },
            'click .record-edit-back-close': function() {
                this.$('.record-edit-play').empty();
                this.$('.record-edit-more-info').empty();
                this.$el.attr('class', '');
                this.adjustLook();
            },
            'click .record-edit-play-shower': 'render_play',
            'click .record-edit-info-shower': 'render_info',
            'click a': 'handleOperation'
        },
        handleOperation: function(e) {
        	var a = $(e.target);
            var text = a.text().trim();
            var _this = this;
            if (a.text() == '专题') {
            	App.trigger('openSpecial_publish', e, this.boss_model, this.boss_view);
                e.preventDefault();
            } else if (a.text() == '区块') {
            	App.trigger('openBlock_publish', e, this.boss_model, this.boss_view);
                e.preventDefault();
            } else if (text == '签发') {
            	App.trigger('openColumn_publish', e, this.boss_model, this.boss_view);
            	e.preventDefault();
            } else if (text == '删除') {
            	e.preventDefault();
            	this.myConfirm(function(ok) {
                    if (ok) {
                       _this.boss_model.destroy();
                    }
                });
            } else if (text == '审核' || text == '打回') {
            	_this.boss_model.audit( a.attr('href') + '&ajax=1' );
            	e.preventDefault();
            } else if (text == '分享'){
            	e.preventDefault();
            	App.trigger('openShare_box', e, this.boss_model, this.boss_view);
            } else if ( a.data('handler') == 'move' ){
            	//App.trigger('openMove_box', e, this.boss_model, this.boss_view);
            } else if (text == '移动') {
            	this.boss_model.node = a.data('node');
            	App.trigger('moveColumn_publish', e, this.boss_model, this.boss_view);
            	e.preventDefault();   //移动
            }else if( a.hasClass('sync_letv') ){
            	if( !a.data('ajax') ){
            		_this.boss_model.sync_letv( a );
            	}
            	a.data('ajax',true);
            	return false;
            }else if (text == '推送') {
                e.preventDefault();
                this.pushConfirm(function(ok) {
                    if (ok) {
                       _this.boss_model.propell( a );
                    }
                });
                return false;
            }
        },
        myConfirm: function(callback) {
            this.$el.addClass('confirm-model');
            this.$('.record-edit-confirm-btn a:first').data('callback', callback);
            this.adjustLook();
        },
        pushConfirm: function(callback) {
            this.$el.addClass('push-model');
            this.$('.push-btn').data('callback', callback);
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
        toggle: function(event, boss_model, boss_view) {
        	if (this.boss_model == boss_model) {
        		this.close();
        	} else {
        		this.open(event, boss_model, boss_view);
        	}
        },
        adjustLook: function(animate) {
        	var _this, iOffset, iconH, r, t, wh, w, h, limitH, needUpd, stopfn;
        	
        	_this = this;
        	iOffset = this.iOffset || this.boss_view.getPositionElForAction().offset();
        	iconH = this.iconH || this.boss_view.getPositionElForAction().height();
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
        open: function(event, boss_model, boss_view) {
        	if (this.disabled) return;
            if (!this.boss_model && !boss_model) return;
            
            this.boss_model = boss_model;
            this.boss_view = boss_view;
            this.boss_view.beCurrent();
            
            this.iOffset = null;
            this.iconH = null;
            this.render().removeAttr('class');
            this.adjustLook(true);
            
            //去掉a链接上陈旧的onclick
            this.$('.record-edit-btn-area a').removeAttr('onclick');
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