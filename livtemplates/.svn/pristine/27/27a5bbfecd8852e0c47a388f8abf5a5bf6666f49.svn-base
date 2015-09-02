;(function() {
	
	var WeightBox = Backbone.View.extend({
		initialize: function() {
			this.disabled = false; //表示是否禁用的状态变量
			this.isopen = false;
			this.createdUI = false; 
			// boss_view引用的对象要实现getPositionElForWeight和weightChange方法
			this.boss_view = null;
			
            App.on('openDragMode', this.disable, this).on('closeDragMode', this.enable, this);
            
            if ( recordViews ) { 
            	this.initForList();
            }
        },
        events: {
            'click': '_passMe',
            'click li': 'fastPubValue'
        },
        initForList: function() {
        	this.listenToRecordViews();
        	//延迟保存权重值
			this.deferSaveWeight = hg_defer(function(weight) {
				this.boss_view.model.saveWeight(weight);
			}, 500, true, this);
        	this.weightChange = function(new_weight) {
        		this.boss_view
        			.$('.common-quanzhong')
        			.css('background', create_color_for_weight(new_weight))
        			.find('span').text(new_weight);
        		this.deferSaveWeight(new_weight);
        	};
        },
        listenToRecordViews: function() {
        	var _this = this;
        	var events = recordViews.events || {};
        	events['click .common-quanzhong'] = function(e) {
        		var recordView = recordViews.getViewByEl( $(event.target).closest('.common-list-data')[0] ),
        			weight = recordView.model.get('weight');
        		_this.toggle(weight, recordView);
        	};
        	recordViews.delegateEvents(events);
        },
        disable: function() {
        	this.disabled = true;
        	if (this.isopen) this.close();
        },
        enable: function() {
        	this.disabled = false;
        },
        toggle: function(weight, boss_view) {
        	if ( this.boss_view == boss_view ) {
        		this.close();
        	} else {
        		this.close();
        		this.open(weight, boss_view);
        	}
        },
        open: function(weight, boss_view) {
            if (this.disabled) return;
            this.isopen = true;
            this._createUI();
            this.boss_view = boss_view;
            this.setValue(weight);
            this._show();
            //跳过这次事件冒泡再给boss绑定事件
            setTimeout(_.bind(function() {
            	$('body').on('click', this.clickOtherHideFunc);
            }, this), 0);
        },
        close: function() {
        	this.isopen = false;
            this.boss_view = null;
            this._hide();
            $('body').off('click', this.clickOtherHideFunc);
        },
        fastPubValue: function(e) {
			this.pubValue( $(e.currentTarget).data('weight') );
            this.close();
		},
        pubValue: function(val) {
        	this.weightChange && this.weightChange( val );
        },
        setValue: function(value) {
        	this.slide.slider('value', value || 0);
        },
        getPositionEl: function() {
        	return this.boss_view.$('.common-quanzhong');
        },
        _show: function() {
        	var position_el = this.getPositionEl(),
            	offset = position_el.offset(),
            	top = offset.top + position_el.height(),
            	marginValue;
            if ( top + this.$el.outerHeight() > $(window).height() ) {
                this.$el.addClass('box-updown');
                marginValue = -(this.$el.outerHeight() + position_el.height());
            } else {
                this.$el.removeClass('box-updown');
                marginValue = '';
            }
            this.$el.show().css({
                'left': offset.left,
                'top': top,
                'margin-top': marginValue
            });
        },
        _hide: function() {
        	this.$el.hide();
        },
        _createUI: function() {
        	//UI只在第一次打开时初始化一次，使用了延迟初始化技术
        	if (this.createdUI) return;
        	this.createdUI = true;
        	this.slide = this.$("#listWeightSlider").slider({
				animate: true,
				max: 100,
				min: 0,
				slide: _.bind(function(e, ui) {
					this.pubValue( ui.value );
				}, this)
			});
			this._render();
			this.clickOtherHideFunc = _.bind(function(event) {
            	var event = event.originalEvent;
            	if ( !(event && event['passHideWeightBox']) ) {
            		this.close();
            	}
           }, this);
        },
		_passMe: function(event) {
        	event.originalEvent && (event.originalEvent['passHideWeightBox'] = true);
        },
        _render: function() {
        	if ( !top.$.globalData ) return;
        	var configWeight = top.$.globalData.get('quanzhong');
        	if (configWeight) {
        		var el = $.tmpl( $('#weight_box_tpl').html(), {mydata: configWeight}, {} );
        		this.$('.weight-select').append(el);
        	}
        }
    });
    
    window.WeightBox = WeightBox;
})();