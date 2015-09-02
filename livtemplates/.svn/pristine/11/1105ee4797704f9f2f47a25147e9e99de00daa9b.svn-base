
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
	var url = './run.php?mid=' + gMid + '&a='+ audit+'&id=' + id;
	hg_ajax_post(url,'','','hg_audit_callback');

}

function hg_audit_callback(json)
{
	var obj = eval("("+json+")");
	var con = '';
	if(obj.status == 'audit')
	{
		con = '已打回';
		color = '#f8a6a6';
	}
	else if(obj.status == 'back')
	{
		con = '已审核';
		color = '#17b202';
	}
	
	for(var i = 0;i<obj.id.length;i++)
	{
		$('#audit_' + obj.id[i]).text(con);
		$('#audit_' + obj.id[i]).removeAttr('onclick');
		$('#audit_' + obj.id[i]).attr('onclick','hg_stateAudit('+obj.id[i]+',"'+obj.status+'")');
		$('#audit_' + obj.id[i]).removeAttr('title');
		$('#audit_' + obj.id[i]).attr('title', con );
		$("#audit_" + obj.id[i]).css('color', color);
	
	}
	
}
