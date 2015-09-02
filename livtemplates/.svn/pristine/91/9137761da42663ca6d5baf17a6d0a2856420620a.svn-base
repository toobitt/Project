;(function() {
	// 负责显示和隐藏，从上面掉下的那种显示
	var Publish_box = Backbone.View.extend({
		initialize: function(options) {
        	var _this = this;
        	
        	options.beforeCreate && options.beforeCreate(this);
        	this.$('.publish-box')[options.plugin](
        		options.pluginOptions
        	);
        	this.$('.publish-box').data('publishBox', this);
        	App.on('openDragMode', function() { _this.close(); });
        	
        	options.initialized && options.initialized(this);
        },
        events: {
            'click .common-list-pub-close': 'close', 
            'click .publish-box-save': function(event) {
            	this.$('form').ajaxSubmit({
            		success : function(json){
            			$('.record-edit-close').trigger('click');
            		}
            	})
            	this.close();
            }
        },
        adjustPosition: function() {
        	if (!this.boss_view) return;
        	var liTop = this.boss_view.$el.offset().top;
        	var t = liTop >= 200 ? liTop - 200 : 0;
        	var pH, dH;
        	var pub = this.$el;
        	if ( t + (pH = pub.outerHeight()) > (dH = $(document).height()) ) {
				t = dH - pH - 50;
			}
			pub.css({ top: t, 'margin-left': pub.outerWidth() / -2 });
        },
        openForBatch: function(ids , nodevar) {
       		this.initPublish(ids , nodevar);
        },
        open: function(event, boss_model, boss_view) {
            this.initPublish(boss_model.id,boss_model.node);
        },
        initPublish: function(ids ,nodevar) {
        	var id = (ids + '').split(',');
        	var f, t = '';
    		f = '<p>' + _.unescape(recordCollection.get(id[0]).get('title') || recordCollection.get(id[0]).get('name') ) + '</p>';
    		
    		this.$('.common-list-pub-title > div p').replaceWith(f);
    		this.$('.common-list-pub-title > div span').text('共' + id.length + '条');
    		id.forEach(function(cid, i) {
    			t += '<p>' + _.unescape(recordCollection.get(cid).get('title') ||recordCollection.get(cid).get('name') ) + '</p>';
    		});
    		this.$('.common-list-pub-title > div div').html(t);
    		this.$('input[name="id"]').val(ids);
    		this.boss_view = recordsView.get( id[id.length - 1] );
    		this.adjustPosition();
    		var publish = this.$el.find('.publish-box').data('publish');
    		publish.model = recordCollection.get( id.pop() );
     	    publish.reinit(ids ,nodevar);
        },
        close: function() {
            this.$el.css('top', '');
            var publish = this.$el.find('.publish-box').data('publish');
            publish.disappear && publish.disappear();
        }
    });
	
    window.Publish_box = Publish_box;
})();