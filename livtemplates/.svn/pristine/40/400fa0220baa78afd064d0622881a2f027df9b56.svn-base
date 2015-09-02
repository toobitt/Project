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

/*会员编辑*/
function hg_memberDel(id)
{
	if (confirm('确定删除该选项吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
		hg_ajax_post(url);
	}
}

var gMemberId = '';
var gType = '';
var gDomId = '';
function hg_memberAudit(id, status, type, domId)
{
	gMemberId = id;
	gType = type;
	gDomId = domId;
	var url = './run.php?mid=' + gMid + '&a=audit&id=' + id + '&type=' + type;
	hg_ajax_post(url,'', '', 'memberAudit_back');
}
function memberAudit_back(obj)
{

	if (obj == 1)
	{
		$('#' + gDomId + gMemberId).html('已审核');
		if (gDomId == 'm_status_')
		{
			$('#audit_' + gMemberId).html('已审核');
		}
	}
	else if (obj ==2)
	{
		$('#' + gDomId + gMemberId).html('待审核');
		if (gDomId == 'm_status_')
		{
			$('#audit_' + gMemberId).html('待审核');
		}
	}
	else
	{
		return false;
	}
}




