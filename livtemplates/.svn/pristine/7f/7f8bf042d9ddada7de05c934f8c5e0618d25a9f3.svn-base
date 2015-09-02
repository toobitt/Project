function hg_audit_callback(json){
	
	var json_data = $.parseJSON(json);
	for(var a in json)
	{
		
		$('#restaurant_audit_'+json_data[a]).html('已审核');
		$('#stateAudit_' + json_data[a]).removeAttr('onclick');
		$('#stateAudit_' + json_data[a]).attr('onclick','hg_stateAudit('+json_data[a]+',2)');
	}
}
function hg_stateAudit(id,audit)
{
	gAuditId = id;
	var url = './run.php?mid=' + gMid + '&a=stateAudit&id=' + id + '&audit=' + audit;
	hg_ajax_post(url,'','','stateAudit_back');

}

function hg_back_callback(json){
	var json_data = $.parseJSON(json);
    /*alert(json);
	alert(json_data);*/
	
	for(var a in json_data)
	{
		$('#restaurant_audit_'+json_data[a]).html('待审核');
		$('#stateAudit_' + json_data[a]).removeAttr('onclick');
		$('#stateAudit_' + json_data[a]).attr('onclick','hg_stateAudit('+json_data[a]+',1)');
	}	
}