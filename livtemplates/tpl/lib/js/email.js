function show_opration_button(btn, showElem)
{
	for(var i = 0; i < btn.length; i++){
		btn[i].onmouseover = function(){
			$(this).find(showElem).show();
		};
		btn[i].onmouseout = function(){
			$(this).find(showElem).hide();
		};

	}
}

$(function(){
	show_opration_button($(".cz"), ".show_opration_button");
});

/*邮件发送方式*/
function hg_mailsend(type)
{
	switch (type)
	{
		case '2':
			$('#mail_server_port').show();
			$('#mail_auth_from').show();
			$('#mail_server_port').find('input').removeAttr('disabled');
			$('#mail_auth_from').find('input').removeAttr('disabled');
			break;
		default:
			$('#mail_server_port').hide();
			$('#mail_auth_from').hide();
			$('#mail_server_port').find('input').attr('disabled', 'disabled');
			$('#mail_auth_from').find('input').attr('disabled', 'disabled');
			break;
	}
	hg_resize_nodeFrame();
}


function hg_emailSettingsDel(id)
{
	if (confirm('确定删除该选项吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
		hg_ajax_post(url);
	}
}

/*审核*/
var gEmailSettingsId = '';
var gType = '';
var gDomId = '';
function hg_emailSettingsAudit(id, status, type, domId)
{
	gEmailSettingsId = id;
	gType = type;
	gDomId = domId;
	var url = './run.php?mid=' + gMid + '&a=audit&id=' + id + '&type=' + type;
	hg_ajax_post(url,'', '', 'emailSettingsAudit_back');
}
function emailSettingsAudit_back(obj)
{

	if (obj == 1)
	{
		$('#' + gDomId + gEmailSettingsId).html('已审核');
		if (gDomId == 'm_status_')
		{
			$('#audit_' + gEmailSettingsId).html('已审核');
		}
	}
	else if (obj ==2)
	{
		$('#' + gDomId + gEmailSettingsId).html('待审核');
		if (gDomId == 'm_status_')
		{
			$('#audit_' + gEmailSettingsId).html('待审核');
		}
	}
	else
	{
		return false;
	}
}

/*手动发送*/
var gEmailLogId = '';
function hg_email_manually_send (id)
{
	gEmailLogId = id;
	var url = './run.php?mid=' + gMid + '&a=email_manually_send&id=' + id;
	hg_ajax_post(url, '', '', 'email_manually_send_back');
}

function email_manually_send_back (obj) 
{
	if (obj == gEmailLogId)
	{
		$('#audit_' + gEmailLogId).html('<font style="color: #3EC100;">发送成功</font>');
		$('#manually_send_' + gEmailLogId).removeAttr('onclick');
		$('#manually_send_' + gEmailLogId).html('');
	}
}

function hg_manuallySendShow (id, type)
{
	if (type == 'on')
	{
		$('#manually_send_' + id).show();
	}
	else
	{
		$('#manually_send_' + id).hide();
	}
}

/*开启邮件头尾内容配置*/
function hg_emailIsHeadFoot(type)
{
	if (type == 'on')
	{
		$('#header_footer').show();
		$('textarea[name^="header"]').removeAttr('disabled');
		$('textarea[name^="footer"]').removeAttr('disabled');
	}
	else
	{
		$('#header_footer').hide();
		$('textarea[name^="header"]').attr('disabled', 'disabled');
		$('textarea[name^="footer"]').attr('disabled', 'disabled');
	}
	hg_resize_nodeFrame();
}













