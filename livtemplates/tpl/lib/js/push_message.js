
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
var  gAuditId = '';
function hg_stateAudit(id,audit)
{
	gAuditId = id;
	var url = './run.php?mid=' + gMid + '&a=audit&id=' + id + '&audit=' + audit;
	hg_ajax_post(url,'','','hg_audit_callback');
	
}


function hg_audit_callback(json)
{
	var obj = JSON.parse(json);
	var con = '';
	var send = '';
	var color = globalData.status_color[obj.status];
	if(obj.status == 2)
	{
		con = '已打回';
	}
	else if(obj.status == 1)
	{
		con = '已审核';
		send = '已发送';
	}
	if(obj.status == 1)
	{
		for(var i = 0;i<obj.id.length;i++)
		{
			var id = obj.id[i].id;
			$('#audit_' + id).text(con).css({'color':color});
			$('#audit_' + id).removeAttr('onclick');
			$('#audit_' + id).attr('onclick','hg_stateAudit('+obj.id[i].id+','+2+')');
			$('#audit_' + id).removeAttr('title');
			$('#audit_' + id).attr('title', con );
			if(obj.id[i].send_way == 1)
			{
				$('#send_' + id).text(send).css({'color':color});
				$('#update_' + id).removeAttr('href');
				// 发送了就不让审核
				$('#audit_' + id).attr('onclick', '');
				recordCollection.get(id).set('is_send', 1);
			}
			recordCollection.get(id).set('state', obj.status);
		}
	}
	else
	{
		for(var i = 0;i<obj.id.length;i++)
		{
			$('#audit_' + obj.id[i]).text(con).css({'color':color});
			$('#audit_' + obj.id[i]).removeAttr('onclick');
			$('#audit_' + obj.id[i]).attr('onclick','hg_stateAudit('+obj.id[i]+','+1+')');
			$('#audit_' + obj.id[i]).removeAttr('title');
			$('#audit_' + obj.id[i]).attr('title', con );
			recordCollection.get(obj.id[i]).set('state', obj.status);
		}
	}
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
    		hg_ajax_post(a.href,'','','hg_audit_callback');
    	}
    });
});  
	
