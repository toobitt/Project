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

/*删除*/
function hg_Delete(id)
{
	if (confirm('确定删除该选项吗？'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
		hg_ajax_post(url);
	}
}

/*审核*/
var gId = '';
var gType = '';
var gDomId = '';
function hg_Audit(id, status, type, domId)
{
	gId = id;
	gType = type;
	gDomId = domId;
	var url = './run.php?mid=' + gMid + '&a=audit&id=' + id + '&type=' + type;
	hg_ajax_post(url,'', '', 'Audit_back');
}
function Audit_back(obj)
{

	if (obj == 1)
	{
		$('#' + gDomId + gId).html('已审核');
		if (gDomId == 'm_status_')
		{
			$('#audit_' + gId).html('已审核');
		}
	}
	else if (obj ==2)
	{
		$('#' + gDomId + gId).html('待审核');
		if (gDomId == 'm_status_')
		{
			$('#audit_' + gId).html('待审核');
		}
	}
	else
	{
		return false;
	}
}















