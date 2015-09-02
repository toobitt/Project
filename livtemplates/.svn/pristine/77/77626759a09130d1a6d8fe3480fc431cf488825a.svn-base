;(function() {

	var Record = Backbone.Model.extend({
		
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