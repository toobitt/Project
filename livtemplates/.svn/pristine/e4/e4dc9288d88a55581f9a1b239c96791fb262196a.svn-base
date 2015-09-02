;(function() {

	var Record = Backbone.Model.extend({
		saveWeight: function(weight) {
        	if ( this.set('weight', weight) ) {
        		this.collection.saveWeight(this);
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
    });
    
    window.Record = Record;
    window.Records = Records;
    
})();