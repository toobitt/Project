/*显示select菜单*/
function hg_show_sel(id,val){
	$("#text_"+id).hide();
	$("#showsel_"+id).show();
	$("#sel_"+id).focus();
}
/*改变角色时调用的方法*/
function hg_onchange_showtext(id,val)
{
	var value=$("#sel_"+id).find('option:selected').text();	
	$("#text_"+id).html(value);
	$("#text_"+id).show();
	$("#showsel_"+id).hide();
	var url = "./run.php?mid="+gMid+"&a=change_group&id="+id+"&role="+val;
	hg_ajax_post(url);
	
}
/*修改完成后返回原来状态*/
function hg_onblur_showtext(id)
{
	$("#text_"+id).show();
	$("#showsel_"+id).hide();
	
}
/*改变用户组后的回调*/
function hg_auth_back(json)
{
	var json_data = $.parseJSON(json);
	if (json_data.auth)
	{
		$("#auth_"+json_data.id).html(json_data.auth);
	}else{
		
		$("#auth_"+json_data.id).html('暂无角色');
	}
	
}
$(function ($) {
	$('#get_user_box').draggable();
});
function hg_showUser(id)
{
	var offset=0;
	var url = "./run.php?infrm=1&mid="+gMid+"&a=showUser&id="+id+"&offset="+offset;
	$('#livwindialogbody').find('iframe').attr('src', url);
	hg_voteOtherInfoShow();
	
	//hg_ajax_post(url);
}
function showUser_callback(html)
{
	if (html)
	{
		$('#livwindialogbody').html(html);
		hg_voteOtherInfoShow();
	}
}



/*关闭其他选项窗口*/
function hg_otherClose()
{
	//$('#livwindialog').hide();
	$('#get_user_box').animate({'top':'-421px'});
}
/*滑动效果*/
function hg_voteOtherInfoShow()
{
	//var off = $(top.document.getElementById('mainwin').contentWindow.document.getElementsByTagName('body')).scrollTop();
	var off = 0;
	off = off + 120;

	$('#get_user_box').animate({'display':'block','top':'20%'});
}
/*添加用户*/
function hg_addUser(id,interview_id)
{
	var url = "./run.php?mid="+gMid+"&a=addUser&id="+id+"&interview_id="+interview_id;
	hg_ajax_post(url);
	$('#user_frame')[0].contentWindow.$('#row_'+id).remove();
}
/*添加用户返回*/
function hg_addUser_back(html)
{
	$("#user_list").prepend(html);
	//var json_data = $.parseJSON(json);
	
}
function hg_userSearch(interview_id)
{
	k = $('#user_frame')[0].contentWindow.$("#search_list").val();
	var url = "./run.php?mid="+gMid+"&a=showUser&id="+interview_id+"&k="+k;
	hg_ajax_post(url);
}
function hg_alluser(obj)
{
	if (obj.checked==true)
	{
		var length = $('input[name="userlist[]"]').length;
		for (var i =0; i<length; i++)
		{
			$('input[name="userlist[]"]')[i].checked=true;
		}
	}else
	{
		var length = $('input[name="userlist[]"]').length;
		for (var i =0; i<length; i++)
		{
			$('input[name="userlist[]"]')[i].checked=false;
		}
	}
}
/*添加多个用户到访谈中去*/
function hg_add_more(interview_id)
{
	var length = $('input[name="userlist[]"]').length;
	var checked = false;
	var num = 0;
	var id_arr = new Array();
	for (var i=0; i<length; i++)
	{
		var e = $('input[name="userlist[]"]')[i];
		if(e.checked)
		{
			checked = true;
			var id= e.value;
			id_arr.push(id);
	
		}
	}
	var ids = id_arr.join(',');
	if (ids !='')
	{
		var url = "./run.php?mid="+gMid+"&a=addUser&id="+ids+"&interview_id="+interview_id;
		parent.hg_ajax_post(url);
	}
	for (var j=0; j<id_arr.length; j++)
	{
		$("#row_"+id_arr[j]).remove();
	}
	if(!checked)
	{
		alert('请选需要加入访谈的用户！');
		return false;
	}
	return true;

}