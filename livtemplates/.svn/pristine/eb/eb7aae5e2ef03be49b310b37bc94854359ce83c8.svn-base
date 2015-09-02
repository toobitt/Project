function hg_remove_row(ids) {
	var id = String(ids).replace(/\s/g, '').split(','),
		selector = $.map(id, function (v) { return ["#r" + v, "#r_" + v]; }).join();
	
	$(selector).remove();	
	hg_close_opration_info();
}

$(function() { 
	window.App = Backbone;
	window.recordCollection = new Records;
	window.recordViews = new RecordsView({ el: $('.common-list').parent(), collection: recordCollection });
    recordCollection.add(globalData.list);
    var ab = new ActionBox({ el: $('#record-edit') });
    ab.$el.on('click', 'a', function(e) {
    	var a = this;
    	var text = $(this).text().trim();
    	
    	if ( text == '删除' ) {
    		ab.confirm(function(yes) {
    			yes && hg_ajax_post(a);
    		});
    		return false;
    	} else if (/审核|打回/.test(text)) {
    		e.preventDefault();
    		hg_ajax_post(a, '', '', 'memberAudit_back');
    		var id = ab.boss_model.id;
    		memberAudit_back = function(data) {
  				var el = $('#audit_' + id);
  				recordCollection.get(id).set('status', data[0]);
    			el.text(   
					el.text().trim() == '待审核' ? '已审核' : '待审核');
    		};
    		ab.close();
    	}
    });
});  