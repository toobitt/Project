;(function() {

	var RecordView = Backbone.View.extend({
		initialize: function(options) {
            var _this = this;
          
            App.on('beCurrentRecord', function(view) {
            	if (view == this) {
            		this.$el.addClass('current');
            	} else {
            		this.$el.removeClass('current');
            	}
            }, this);
            
            this.model.on('destroy', function() {
            	App.trigger('closeActionBox');
            	this.$el.remove();
            }, this);
            
            this.model.on('change:state',
            function(model, status) {
                changeStatusLabel(status, model);
                App.trigger('closeActionBox');
            });
            
            this.model.on('change:is_link',
            function(model, is_link) {
                changeSynletv_state(is_link, model);
            });
            
            this.deferSaveWeight = hg_defer(function(weight) {
		    	this.model.saveWeight(weight);
		    }, 500, true, this);
        },
        events: {
            'click .common-quanzhong': 'toggleEditWeight',
            'click ': 'needToggleActionBox'
        },
        tagName: 'li',
        toggleEditWeight: function(event) {
        	App.trigger('toggleEditWeight', event, this.model.get('weight'), this);
        },
        toggleActionBox: function(event) {
        	App.trigger('toggleActionBox', event, this.model, this);
        },
        needToggleActionBox: function(event) {
        	var target = $(event.target);
        	if ( target.is('.common-list-biaoti') ) {
        		this.toggleActionBox(event);
        	} else if ( target.parents('.common-list-right').length ) {
        		var text = target.text().trim();
        		if (!text && !target.find('img').size()) {
                    if ( target.find('.need-switch').size() 
                    		|| target.hasClass('need-switch')
                    		|| target.parents('.need-switch').length ) return;
                    this.toggleActionBox(event);
                }
        	}
        },
        weightChange: function(new_weight) {
        	this.$('.common-quanzhong')
        		.css('background', create_color_for_weight(new_weight)).find('span')
        		.text(new_weight);
        	this.deferSaveWeight(new_weight);
        },
        getPositionElForWeight: function() {
        	return this.$('.common-quanzhong');
        },
        getPositionElForAction: function() {
        	return this.$('.common-list-i');
        },
        beCurrent: function() {
        	App.trigger('beCurrentRecord', this);
        },
        unbeCurrent: function() {
        	this.$el.removeClass('current');
        }
    });
    
    
	var RecordsView = Backbone.View.extend({
		initialize: function() {
			var _this = this;
			this.collection.on('add', this.addOne, this);
			this.views = {};
			// 为了兼容以前的，增加一种打开action_boss的方式
			// 增加打开发布框的方式
			if ($('#record-edit').length) {
				window.hg_show_opration_info = function(id) {
					var model = _this.collection.get(id),
						view = _this.views[id];
					App.trigger('toggleActionBox', {}, model, view);
				};
				var old_hg_close_opration_info = window.hg_close_opration_info;
				window.hg_close_opration_info = function() {
					App.trigger('closeActionBox');
					old_hg_close_opration_info && old_hg_close_opration_info();
				};
			} else {
				setTimeout(function() {
					window.hg_show_pubhtml = function(html, id) {
						var model = _this.collection.get(id),
							view = _this.views[id];
						App.trigger('openColumn_publish', {}, model, view);
					}
				}, 10);
			}
		},
		get: function(id) {
			return this.views[id];
		},
		addOne: function(record) {
			var id, li, view;
			id = record.get('id');
			li = $("#r_" + id);
			if (li.length) {
			    view = new RecordView({
			        el: li,
			        model: record
			    });
			 	this.views[id] = view;   
			}
		}
	});
    
    window.RecordsView = RecordsView;
    
})();