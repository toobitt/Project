
function input_content_color(i)
{
	if(!$("#required_" + i).val())
	{
		$('#important_' + i).addClass('i');
		$('#sub').attr('disabled','disabled')
	}
	else
	{
		$('#important_' + i).removeClass('i');
		$('#sub').removeAttr('disabled');
	}
	
}
function hg_change_comment_status(obj)
{
   var obj = obj[0];
   var status_text = "";
   if(obj.status == 1)
   {
	   status_text = '已审核';
   }
   else if(obj.status == 2)
   {
	   status_text = '已打回';    
   }
   for(var i = 0;i<obj.id.length;i++)
   {
   	   var color = globalData.status_color[status_text];
	   $('#statusLabelOf'+obj.id[i]).text(status_text).css('color', color);
	   recordCollection.get(obj.id[i]).set('status', obj.status);
   }

   if($('#edit_show'))
   {
	   hg_close_opration_info();
   }
}
function hg_remove_row(ids) {
	ids = ids.split(',');
	ids.forEach(function(id) {
		recordViews.get(id).remove();
	});
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
    		$.getJSON(a.href).done(function(data) {
    			hg_change_comment_status(data);
    		});
    	}
    });
    
    var loading = '<img src="' + RESOURCE_URL + 'loading2.gif" style="width:25px;position:absolute;left:7px;top:-2px;" />';
	$(".common-list").on("click", '.common-switch-status span', function() {
        if($(this).data('ajax')) return;
        
        var id = $(this).attr('_id');
        var status = recordCollection.get(id).get('status');
        var tablename = recordCollection.get(id).get('tablename');
        var me = $(this), url, load;
        $(this).data('ajax', true);
        load = $(loading).appendTo( $(this).parent() );

        url = './run.php?mid=' + gMid + '&a=audit&audit='+ 
        		(status == 1 ? 2 : 1) + '&id=' + id + '&ajax=1&tablename=' + tablename;
       
        hg_ajax_post(url, '', 0, function (data) {
        	setTimeout(function () {
                me.data('ajax', false);
                hg_change_comment_status(data);
                load.remove();
            }, 200);
        }, false);
    }).find('.common-list-biaoti .common-title').add('.reviews-object>a').tooltip();
});  