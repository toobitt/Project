;(function() {
	
	var WeightBox = Backbone.View.extend({
		initialize: function() {
			this.disabled = false; //表示是否禁用的状态变量
			this.opening = false;
			this.createdUI = false; 
			// boss_view引用的对象要实现getPositionElForWeight和weightChange方法
			this.boss_view = null;
			
			// 在全局App上绑定事件，这样别的对象不持有this引用时，仍可以使用this的功能 
            App
          	.on('openDragMode', this.disable, this)
            .on('closeDragMode', this.enable, this)
			.on('toggleEditWeight', this.toggle, this);
            
            
        },
        events: {
            'click': '_passMe',
            'click li': function(e) {
            	this.pubValue( $(e.currentTarget).data('weight') );
                this.close();
            }
        },
        disable: function() {
        	this.disabled = true;
        	if (this.opening) this.close();
        },
        enable: function() {
        	this.disabled = false;
        },
        toggle: function(event, weight, boss_view) {
        	if ( this.boss_view == boss_view ) {
        		this.close();
        	} else {
        		this.close();
        		this.open(event, weight, boss_view);
        	}
        },
        open: function(event, weight, boss_view) {
            if (this.disabled) return;
            this.opening = true;
            this._createUI();
            this.boss_view = boss_view;
            this.setValue(weight);
            this._show();
            //跳过这次事件冒泡再给boss绑定事件
            setTimeout(_.bind(function() {
            	$('body').on('click.weight', this.clickOtherHideFunc);
            }, this), 0);
        },
        close: function() {
        	this.opening = false;
            this.boss_view = null;
            this._hide();
            $('body').off('click.weight', this.clickOtherHideFunc);
        },
        pubValue: function(val) {
        	this.boss_view.weightChange( val );
        },
        setValue: function(value) {
        	this.slide.slider('value', value || 0);
        },
        _show: function() {
        	var position_el = this.boss_view.getPositionElForWeight(),
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