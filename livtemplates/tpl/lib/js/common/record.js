;(function() {
	/*
	 *  因为将部分业务逻辑放到前端，
	 *  必须有个地方存放资源的状态，
	 *  可以将数据放到dom中，但这样
	 *  不利于扩展和维护，所以引进下面
	 *  两个类，Record用于抽象一条
	 *  资源，Records用于表示资源的集合
	 */
		
	var Record = Backbone.Model.extend({
        validate: function(attrs, options) {
        	if ( _.has(attrs, 'weight') ) {
	            var weight;
	            weight = attrs.weight;
	            weight = Math.ceil( + weight);
	            if (isNaN(weight) || weight < 0 || weight > 100) {
	                return 'weight error';
	            } else {
	                return null;
	            }
        	}
        },
        saveWeight: function(weight) {
        	if ( this.set('weight', weight) ) {
        		this.collection.saveWeight(this);
        	}
        },
        audit: function(href) {
        	if (href) {
        		$.post(href, function(data) {
        			if( data['msg'] == '' ){
        				eval( data['callback'] );
        			}else{
            			hg_change_status(data);
        			}
        		}, 'json').error(function(data){
        			hg_show_error(data.responseText);
        		});
        	}
        },
        sync_letv : function(target){
        	var href = target.attr('href') + '&ajax=1';
        	hg_synletv_progress( target );
        	$.globalAjax( target, function(){
    			return $.post(href, function(data) {
		        			if( data['msg'] == '' ){
		        				eval( data['callback'] );
		        			}else{
		            			hg_synletv_state(data);
		        			}
		        		}, 'json').error(function(data){
		        			hg_show_error(data.responseText);
		        		}).done( function(){
		        			hg_close_synletv_progress();
		        		} );
    		} );
        },

		propell:function(target){
			var href=target.attr("href") + "&ajax=1";
			$.getJSON(href,function(data){

				if(data[0]=="success"){
					console.log(target);
				}
				else{
					alert("推送失败");
				}
			})

		},

        destroy: function() {
        	this.collection.destroy(this);
        },
        share: function(href) {
        	if (href) {
        		$.post(href, function(data) {
        			$('#add_share').html(data);
        			$('#add_share').fadeIn();
        		});
        	}
        }
        
    });
	
    var Records = Backbone.Collection.extend({
        model: Record,
        options: {
        	url: {
        		weight: "./run.php?ajax=1&mid=" + gMid + "&a=update_weight",
        		'delete': "./run.php?ajax=1&mid=" + gMid + "&a=delete"
        	}
        },
        saveWeight: function(records) {
        	var data = {}, url;
        	records = _.isArray(records) ? records : [records];
        	_.each(records, function(record) {
        		data[record.id] = record.get('weight');
        	});
        	url = _.result( this.options.url, 'weight' );
        	$.post(url, {
				data: JSON.stringify(data)
            });
        },
        destroy: function(records) {
        	var ids = [], url, success;
        	records = _.isArray(records) ? records : [records];
        	_.each(records, function(record) {
        		ids.push(record.id);
        	});
        	ids = ids.join(',');
        	url = _.result( this.options.url, 'delete' );
        	
        	var _this = this;
        	success = function(data) {
        		_this.destroySuccess(data, records);
        	};
        	$.post(url, {
        		id: ids,
        		rid: ids,   //兼容发布库删除
        		ajax: 1
        	}, success);
        },
        destroySuccess: function(data, records) {
        	var cb;
        	try {
        		data = JSON.parse(data);
        		cb = data.callback || (data[0] && data[0].callback);
        		cb && eval(cb);
        		return;
        	} catch (e) {
        		
        	}
        	
        	_.each(records, function(record) {
    			record.trigger('destroy', record, record.collection);
    		});
	    	
        }
    });
    
    window.Records = Records;
    
})();