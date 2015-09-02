function show_opration_button(btn, showElem){
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
})

/*审核微博*/
var gAuditId = '';
function hg_stateAudit(id,audit)
{
	gAuditId = id;
	var url = './run.php?mid=' + gMid + '&a=stateAudit&id=' + id + '&audit=' + audit;
	hg_ajax_post(url,'','','stateAudit_back');
}
function stateAudit_back(obj)
{
	if (obj == 1)
	{
		$('#audit_' + gAuditId).html('待审核');
	}
	else
	{
		$('#audit_' + gAuditId).html('已审核');
	}
	$('#stateAudit_' + gAuditId).removeAttr('onclick');
	$('#stateAudit_' + gAuditId).attr('onclick','hg_stateAudit('+gAuditId+','+obj+')');
}

/*删除微博*/
function hg_stateDel(id)
{
	if (confirm('确定删除该选项吗？'))
	{
//		var url = './run.php?mid=' + gMid + '&a=stateDel&id=' + id;
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id;
		hg_ajax_post(url);
	}
}

/*批量审核回调函数*/
function hg_status_audit_back(obj)
{
	var str = String(obj);
	var arr = new Array();
	arr = str.split(',');
	
	for (i=0; i<arr.length ; i++)
	{
		if ($('#audit_' + arr[i]).html() == '已审核')
		{
			$('#audit_' + arr[i]).html('待审核');
		}
		else
		{
			$('#audit_' + arr[i]).html('已审核');
		}
	}
}

/*点滴评论审核*/
function hg_commentState(id,state)
{
	var url = './run.php?mid=' + gMid + '&a=audit&id=' + id + '&audit=' + state;
	hg_ajax_post(url,'','','hg_status_audit_back');
}

/*微博发布*/
var gStateId = '';
function hg_statePublish(id)
{
	gStateId = id;
	var url = './run.php?mid=' + gMid + '&a=recommend&id=' + id;
	hg_ajax_post(url);
}
/*发布回调函数*/
function hg_show_pubhtml(html)
{
	$('#vodpub_body').html(html);
	hg_vodpub_show(gStateId);
}
function hg_vodpub_hide(id)
{
	$('#vod_fb').hide();
	$('#vodpub').animate({'top':'-440px'});
}
function hg_vodpub_show(id)
{
	var tops=t=0;
	t = $('#r_'+ id).position().top;
	if(t >= 230)
	{
		tops = t-140 ;
	}
	$('#vodpub').animate({'top':tops},
		function(){
			$('#vod_fb').css({'display':'block','top':t+11,'left':'98px'});
		}
	);
}