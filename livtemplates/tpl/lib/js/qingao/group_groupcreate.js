var map_op_type;

//g_quick_add_c
function g_quick_add_c(flag)
{
	if(flag == 2)
		showElement('g_quick_add_c1','inline');
	else
		hideElement('g_quick_add_c1');
	$('move_thread_btn').onclick = function(){return move_thread(flag,'t_form_manage',$('move_to_id').value);}
}
function xajax_group_create(){return xajax.call("group_create", arguments, 1);} 
function create_group(input)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_group_create(xajax.getFormValues(input));
}

function xajax_thread_category_batch_edit(){return xajax.call("thread_category_batch_edit", arguments, 1);}
function g_thread_category_batch(confirm)
{
	if(!confirm)
	{
		showElement('gcb_edit_confirm');
		showElement('gcb_edit');
	}
	else
	{
		var form = xajax.getFormValues('gcb_edit_form');
		hideElement('gcb_edit');
		loading_cell('gcb_edit_confirm','更新分类信息中……');

		xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
		xajax_thread_category_batch_edit(form);
	}
	return false;
}

//添加收藏功能
function xajax_do_collect(){return xajax.call("do_collect", arguments, 1);}
function do_collect(cid,group_id)
{ 
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_do_collect(cid,group_id); 
}

//添加收藏功能
function xajax_del_fa(){return xajax.call("del_fa", arguments, 1);}
function del_fa(cid)
{ 
	
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_del_fa(cid); 
}

//thread -- npc0der 2009-3-13
//var gi = 0;
//var hahaggg = function(id){gi++;report_result('op_result', gi, 1, 0);var oldTN = $("ginfosel_" + tid);oldTN.removeChild($('title_color_picker'));};
function change_thread_attr(tid,e)
{
	if ($("title_color_picker")) closeColorSp();
	$('thread_li_'+ tid).style.position = 'relative';
	var oldTN = $("thread_c_b_" + tid);
	var arrColors=[["#800000","#8b4513","#006400","#2f4f4f","#000080","#4b0082","#800080","#000000"],["#ff0000","#daa520","#6b8e23","#708090","#0000cd","#483d8b","#c71585","#696969"],["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"],["#ff6347","#ffd700","#32cd32","#87ceeb","#00bfff","#9370db","#ff69b4","#dcdcdc"],["X","#ffffe0","#98fb98","#e0ffff","#87cefa","#e6e6fa","#dda0dd","#ffffff"]];
	if (!$("show" + tid)) return false;
	var title = $("show" + tid).innerHTML;
	var boldck = title.toLowerCase().lastIndexOf('<strong>') == '-1' ? '' : 'checked';
	var colorTableDiv = document.createElement('div');
	colorTableDiv.id = 'title_color_picker';
	//oldTN.onmouseout = function(){hahaggg(tid)};
	//colorTableDiv.onmouseout = function(){setTimeout("hideElement($('title_color_picker'))",2000);}
	var chDiv = document.createElement('div');
	chDiv.style.width = '130px';
	chDiv.style.padding = '0 2px';
	chDiv.innerHTML = "<span style='float:right;'><a href='javascript:resetTitleColor("+tid+");'>还原</a>&nbsp;<a href='javascript:closeColorSp();'>关闭</a></span><input id='showbb"+tid+"' style='width:12px;height:12px;position:relative;top:-1px;' type='checkbox' "+boldck+" onclick='thread_blod(event);'/>&nbsp;<strong>加粗</strong>";
	colorTableDiv.appendChild(chDiv);
	var colorTable = document.createElement('table');
	colorTable.cellPadding = "0";
	colorTable.cellSpacing = "3";
	if (!isIE)
	{
		var padding = 2;
	}
	else
	{
		var padding = 0;
	}
	for (var n = 0; n < arrColors.length; n++)
	{
		var colorTR = colorTable.insertRow(-1);
		for (var m = 0; m < arrColors[n].length; m++)
		{
			var colorTD = colorTR.insertCell(-1);
			colorTD.id = 'forecolor_' + tid + '_sp_' + arrColors[n][m] ;
			colorTD.style.padding = padding + 'px';
			var colorDiv = document.createElement('div');
			addEvent(colorDiv, 'click', chTitleColor);
			addEvent(colorDiv, 'mouseover', changeCss1);
			addEvent(colorDiv, 'mouseout', changeCss2);
			if (arrColors[n][m] == 'X')
			{
				colorDiv.innerHTML = 'X';
				colorDiv.style.font = '11px Arial';
				colorDiv.style.textAlign = 'center';
				colorDiv.style.lineHeight = '11px';
			}
			else
			{
				colorDiv.style.background = arrColors[n][m];
			}
			colorTD.appendChild(colorDiv);
		}
	}

	colorTableDiv.appendChild(colorTable);
	oldTN.appendChild(colorTableDiv);
	addEvent(colorTableDiv, 'click', function(e){stop_def(e);});
	return false;
}
function xajax_change_thread_attr(){return xajax.call("change_thread_attr", arguments, 1);}
function thread_blod(e)
{
	var el;
	if (isIE) el = window.event.srcElement;
	else el = e.target;
	eventid = el;
	var tid = eventid.id.substring(6);
	if(eventid.checked)
		$('show' + tid).innerHTML = '<strong>' +  $('show' + tid).innerHTML + '</strong>';
	else
	{
		$('show' + tid).innerHTML = $('show' + tid).innerHTML.replace(/<strong>([^>]*?)<\/strong>/ig,'$1');
	}
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_change_thread_attr(tid, eventid.checked ? 1:0,1);
}

/**
* 关闭主题颜色面板
*
*/
function closeColorSp()
{
	if ($("title_color_picker")) 
	{
		$("title_color_picker").parentNode.removeChild($("title_color_picker"));
		$('thread_li_'+ tids).style.position = 'static';
	}
}

/**
* 颜色面板鼠标事件
*
*/
function changeCss1(e)
{
	var el;
	if (isIE) el = window.event.srcElement;
	else el = e.target;
	eventid = el;
	eventid.style.borderColor = '#000080';
}

/**
* 颜色面板鼠标事件
*
*/
function changeCss2(e)
{
	var el;
	if (isIE) el = window.event.srcElement;
	else el = e.target;
	eventid = el;
	eventid.style.borderColor = '#fff';
}

/**
* 点击颜色面板中的颜色触发事件
*
*/
function chTitleColor(e)
{
	var el;
	if (isIE) el = window.event.srcElement;
	else el = e.target;
	eventid = el.parentNode;
	var text = eventid.id;
	var color = text.substr(text.lastIndexOf('_sp_') + 4);
	var tid = text.substring(10,text.lastIndexOf('_sp_'));
	var bold = $("showbb" + tid).checked ? 1 : 0;
	closeColorSp();
	$('show' + tid).style.color = color;
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_change_thread_attr(tid, bold + color);
}

/**
* 重置标题
*
*/
function resetTitleColor(tid)
{
	closeColorSp();
	$('show' + tid).style.color = '';
	if($('show' + tid).innerHTML.lastIndexOf('<strong>') != -1)
		$('show' + tid).innerHTML = $('show' + tid).innerHTML.replace(/<strong>([^>]*?)<\/strong>/ig,'$1');
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_change_thread_attr(tid, '');
}



//group-groups
function groups_friend_joined(limit)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax.call("groups_friend_joined", arguments, 1);
	return false;
}
var tids = 0;
function manage_option_all(id,type)
{
	tids = id;
	switch(type)
	{
		case 1:
			if($('thread_li_'+ id).style.position)
			{
				$('thread_li_'+ id).style.position = 'relative';
			}
			showElement($('ginfosel_'+id));
			break;
		case 2:
			if($('thread_li_'+ id).style.position)
			{
				$('thread_li_'+ id).style.position = 'static';
			}
			if($('thread_ids_'+id).checked != true)
			{
				if ($('ginfosel_'+id))
				{
					$('ginfosel_'+id).style.display='none';
				}
				if ($('title_color_picker'))
				{
					$('title_color_picker').style.display='none';
				}
			}
			break;
		/*case 3:
			//var obj = $('title_color_picker').style;
			if(obj.display!='none'||obj.visibility!= 'hidden')
			{
				obj.display = 'none';
			}
			break;*/
		case 4:
			if($('thread_ids_'+id).checked==true)
			{
				$('thread_ids_'+id).checked=false;
				$('thread_c_b_'+id).style.backgroundColor='#fff';
//				$('span1_'+id).style.backgroundColor='#fff';
			}
			else
			{
				$('thread_c_b_'+id).style.backgroundColor='#FEF8E8';
//				$('span1_'+id).style.backgroundColor='#FEF8E8';
				$('thread_ids_'+id).checked=true;
			}
			break;
		default :
			return;
	}



}
function check_all_group(form,type)
{
	if(typeof form == 'string')
		form = $(''+form+'').getElementsByTagName('input');
	for (var i=0;i< form.length;i++)
	{
		var e = form[i];
		if (e.type=='checkbox')
		{
			if(1 == type)
			{
				e.checked = true;
				e.parentNode.style.display = 'block';
				e.parentNode.parentNode.style.backgroundColor = '#FEF8E8';
				e.parentNode.parentNode.parentNode.style.backgroundColor = '#FEF8E8';
			}
			else
			{
				e.checked = false;
				e.parentNode.style.display = 'none';
				e.parentNode.parentNode.style.backgroundColor = '#fff';
				e.parentNode.parentNode.parentNode.style.backgroundColor = '#fff';
			}
		}
	}
	return false;
}
function stop_def(e)
{
	var e = e || window.event;
	if (isIE)
	{
		e.cancelBubble=true;
	}
	else
	{
		e.stopPropagation();
	}
}


function xajax_send_invite_msg(){return xajax.call("send_invite_msg", arguments, 1);}
function xajax_save_group_settings(){return xajax.call("save_group_settings", arguments, 1);}
function xajax_group_change_user_level(){return xajax.call("group_change_user_level", arguments, 1);}
function xajax_group_join_confirm(){return xajax.call("group_join_confirm", arguments, 1);}
function xajax_group_kickout_user(){return xajax.call("group_kickout_user", arguments, 1);}

function xajax_group_description_edit(){return xajax.call("group_description_edit", arguments, 1);}
function xajax_show_group_threads(){return xajax.call("show_group_threads", arguments, 1);}
function xajax_get_blog_list(){return xajax.call("get_blog_list", arguments, 1);}
function xajax_get_picture_list(){return xajax.call("get_picture_list", arguments, 1);}

function xajax_group_blacklist_user(){return xajax.call("group_blacklist_user", arguments, 1);}
function group_blacklist_user(group_id,user_id,user_name,confirm,blacklist)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_group_blacklist_user(group_id,user_id,user_name,confirm,blacklist);
}
function xajax_sa_operate(){return xajax.call("sa_operate", arguments, 1);}
function sa_operate(action,id,confirm)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_sa_operate(action,id,confirm);
}
function xajax_group_change_owner(){return xajax.call("group_change_owner", arguments, 1);}
function group_change_owner(group_id,user_id,confirm)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_group_change_owner(group_id,user_id,confirm);
}

//以下是讨论区新页面 - 展示页面要统一用到的 JS.... 公共的 提出.

//以上是讨论区新页面 - 展示页面要统一用到的 JS....
function xajax_group_show_multi_upload(){return xajax.call("group_show_multi_upload", arguments, 1);}
function group_show_multi_upload(albums_id)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax'
	xajax_group_show_multi_upload(albums_id);
}


function get_picture_list(type,pp)
{
	xajaxRequestUri = 'index.php?module=picture&is_ajax=group_ajax'
	xajax_get_picture_list(type,pp);
}

function get_blog_list(type,pp)
{
	xajaxRequestUri = 'index.php?module=blog&is_ajax=group_ajax'
	xajax_get_blog_list(type,pp);
}

/**
* 发送讨论区邀请消息
*/
function group_send_invite_msg()
{
	if(invite_friend_count == 0)
	{
		return false;
	}
	invite_friend_count = 0;
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_send_invite_msg(xajax.getFormValues('invite_form'));

}

/**
* 修改讨论区基本信息
*/
function save_group_settings(tab_id , module)
{
	if(tab_id == 'group_settings_basic')
	{
		if($('name').value=='')
		{
			alert('讨论区名称不能为空!');
			$('name').focus();
			return;
		}
		if($('description').value=='')
		{
			alert('讨论区公告不能为空!');
			$('description').focus();
			return;
		}
	}
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_save_group_settings(tab_id,module,xajax.getFormValues(tab_id+'_form'));
}

/**
* 修改讨论区成员权限
*/
function group_change_user_level(group_id,user_id,level)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_group_change_user_level(group_id,user_id,level);
}

/**
* 讨论区成员审核
*/
function group_join_confirm(group_id,user_id,state)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_group_join_confirm(group_id,user_id,state);
}

/**
* 讨论区成员踢出
*/
function group_kickout_user(group_id,user_id)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_group_kickout_user(group_id,user_id);
}

function change_joined_category(json_array,nonevalue,max_count)
{
	var label = '';
	var prefix = '';

	eval('var group_cats = '+decodeURIComponent(json_array)+';');
	for(i=0;i<group_cats.length;i++)
	{
		if($('checkbox_'+group_cats[i]).checked == true)
		{
			if(group_cats[i].length >3)
			{
				if($('checkbox_'+group_cats[i].substr(0,3)).checked == true)
				{
					label += prefix + $('checkbox_'+group_cats[i].substr(0,3)).nextSibling.innerHTML +'-'+ $('checkbox_'+group_cats[i]).nextSibling.innerHTML;
				}
			}
			else
			{
				label += prefix + $('checkbox_'+group_cats[i]).nextSibling.innerHTML;
			}
			prefix = '、';
		}
	}

	if(label == '')
	{
		label = nonevalue;
	}
	$('joined_category').innerHTML = label;
}



var old_description = '';
var old_edit = '';
function group_description_edit(type)
{
	if(type == 0)
	{
		if(!$('group_description'))
		{
			old_description = $('group_description_new').innerHTML;
			old_edit = $('group_description_edit').innerHTML;
			$('group_description_new').innerHTML = '<textarea onfocus="this.className=\'focus\';if(this.value==\'暂无描述\'){this.value=\'\'}" onblur="if(this.value==\'\'){this.value=\'暂无描述\';this.className=\'\'};" name="group_description" id="group_description" cols="50" rows="4">'+old_description.replace(/<br\/?>/ig,"\n")+'</textarea>'
			$('group_description_edit').innerHTML = '<div class="ed"><a href="#" class="button" onclick="group_description_edit(1);return false;">确定</a><a href="#" class="button" onclick="group_description_edit(2);return false;">取消</a></div>'
		}
		$('group_description').focus();
	}
	else if(type==1)
	{
		if($('group_description').value == '')
		{
			alert('请输入讨论区描述!');
			$('group_description').focus();
			return;
		}
		xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
		xajax_group_description_edit($('group_description').value);
	}
	else
	{
		$('group_description_new').innerHTML = old_description;
		$('group_description_edit').innerHTML = old_edit;
	}
	return false;
}

function show_group_threads(category_id)
{
	xajaxRequestUri = 'index.php?module=common&is_ajax=group_ajax';
	xajax_show_group_threads(category_id);
}

//上传
function xajax_add_thread(){return xajax.call("add_thread", arguments, 1);}
function xajax_edit_thread(){return xajax.call("edit_thread", arguments, 1);}
function xajax_add_post(){return xajax.call("add_post", arguments, 1);}
function xajax_edit_post(){return xajax.call("edit_post", arguments, 1);}
function xajax_add_action(){return xajax.call("add_action",arguments,1);}
function xajax_add_action_apply(){return xajax.call("add_action_apply",arguments,1);}
function xajax_verify_apply(){return xajax.call("verify_apply",arguments,1);}

function xajax_get_thread_category(){return xajax.call("get_thread_category", arguments, 1);}

function add_thread(form_name)
{
	if($('thread_title').value=='')
	{
		alert('请输入帖子标题!');
		$('thread_title').focus();
		return false;
	}

//	$('text_c').value = oEdit_text_c.getXHTML().replace(/<html>|<\/html><head>|<\/head>|<body>|<\/body>/g, "");
	$('text_c').value = editors.getData();
	if($('text_c').value == '')
	{
		alert('请输入帖子内容!');
		return false;
	}
	
 
	post_button_click('btn_sub','publish_thread_clew', '正在发表帖子.....');

	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax'
	
	xajax_add_thread(xajax.getFormValues(form_name));
	return false;
}
	//test(success) ：	alert(xajax.getFormValues(form_name));exit;
function add_action(form_name)
{
	if(!$("thread_title").value)
	{
		alert('请输入活动名');
		$("thread_title").focus();
	}
	else
	{
		
		if(!$("_set").value)
		{
			alert('请填写活动时间');
			$("_set").focus();
		} 
		else if(!$("act_place").value)
		{
			alert('请填写活动地点');
			$("act_place").focus();
		}
		else if(!$("need_time").value)
		{
			alert('请填写活动截止时间');
			$("need_time").focus();
		}
		else
		{ 
			xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
			xajax_add_action(xajax.getFormValues(form_name));
			return false;
		}
	}
}

function action_apply(form_name)
{
	//前台表单未校验
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_add_action_apply(xajax.getFormValues(form_name));
	return false;
}

function apply_verify(form_name)
{
	xajaxRequestUri = "index.php?module=thread&is_ajax=group_ajax";
	xajax_verify_apply(xajax.getFormValues(form_name));
	return false;
}
function edit_thread(form_name)
{
	if($('thread_title').value=='')
	{
		alert('请输入帖子标题!');
		$('title').focus();
		return false;
	}
//	$('text_c').value = oEdit_text_c.getXHTML().replace(/<html>|<\/html><head>|<\/head>|<body>|<\/body>/g, "");

	$('text_c').value = editors.getData();
	if($('text_c').value=='')
	{
		alert('请输入帖子内容!');
		return false;
	}

	post_button_click('btn_sub','publish_thread_clew', '正在修改帖子.....');
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax'
	
	xajax_edit_thread(xajax.getFormValues(form_name));
	return false;
}


function add_post(form_name)
{
	if($('main_text_c').value=='')
	{
		alert('请输入回复内容!');
		return false;
	}
	hideElement('btn_sub');
	document.getElementById("btn_sub_dis").style.display="inline-block";
	//post_button_click('btn_sub','publish_post_clew', '正在发表回复.....');
	report_result('op_result','正在发表回复.....');
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_add_post(xajax.getFormValues(form_name));

	return false;
}

/*
type=>0 删除单条记录 1 批量删除
complete=>0 删除到回收站 1:完全删除
op_type=> 1 删除, 0:还原
*/

function xajax_del_thread(){return xajax.call("del_thread", arguments, 1);}
function del_thread(type,form_name,complete,op_type)
{

	var ids = "";
	if(type == '0')//非批量删除
	{
		ids = form_name;
	}
	else
	{
		ids = get_checked_values(form_name);
	}

	if(!ids)
	{
		alert('请选择要操作的项');
		return false;
	}

	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_del_thread(type,ids,complete,op_type);
	return false;

}


function xajax_quintess_thread(){return xajax.call("quintess_thread", arguments, 1);}
function quintess_thread(type,form_name,op_type)
{
	var ids = "";
	if(type == '0')//非批量删除
	{
		ids = form_name;
	}
	else
	{
		ids = get_checked_values(form_name);
	}

	if(!ids)
	{
		alert('请选择要操作的项');
		return false;
	}

	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_quintess_thread(type,ids,op_type);
	return false;

}

function xajax_open_thread(){return xajax.call("open_thread", arguments, 1);}
function open_thread(type,form_name,op_type)
{

	var ids = "";
	if(type == '0')
	{
		ids = form_name;
	}
	else
	{
		ids = get_checked_values(form_name);
	}

	if(!ids)
	{
		alert('请选择要操作的项');
		return false;
	}

	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_open_thread(type,ids,op_type);
	return false;

}

function xajax_sticky_thread(){return xajax.call("sticky_thread", arguments, 1);}
function sticky_thread(type,form_name,op_type)
{

	var ids = "";
	if(type == '0')//非批量删除
	{
		ids = form_name;
	}
	else
	{
		ids = get_checked_values(form_name);
	}

	if(!ids)
	{
		alert('请选择要操作的项');
		return false;
	}
 	
	var web_range = 0;
	if(op_type < 0)
	{
		web_range= 0-(document.getElementById("web_ans").value);
		
	}
 	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
 	xajax_sticky_thread(type,ids,op_type,web_range);
 	return false;

}

function show_move_face(obj_name1,obj_name2)
{
	$(obj_name1).innerHTML = $(obj_name2).innerHTML;
	return false;
}

function xajax_move_thread(){return xajax.call("move_thread", arguments, 1);}
function move_thread(type,form_name,category_id)
{

	var ids = "";
	if(type == '0')//非批量删除
	{
		ids = form_name;
	}
	else
	{
		ids = get_checked_values(form_name);
	}

	if(!ids)
	{
		alert('请选择要操作的项');
		move_reset();
		return false;
	}
	var text = '';

	if (!confirm('您确定要转移吗？'))
	{
		move_reset();
		return false;
	}

	if(type == 2)
	{
		if($('g_quick_add_c1').value == '')
		{
			alert('请输入新的分类名称(注意分类名称不可与原有分类名重复…)');
			$('g_quick_add_c1').focus();
			return false;
		}
		category_id = $('g_quick_add_c1').value;
	}

	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_move_thread(type,ids,category_id);
	return false;

}

function move_reset()
{
	$('manage_move').innerHTML = '';
	return false;
}


function xajax_del_post(){return xajax.call("del_post", arguments, 1);}


function del_post(type,form_name,complete,op_type,thread_id)
{

	var ids = "";
	if(type == '0')//非批量删除
	{
		ids = form_name;
	}
	else
	{
		ids = get_checked_values(form_name);
	}
	if(ids)
	{
		xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
		xajax_del_post(type,ids,complete,op_type,thread_id);
	}
	return false;
}



function reply_post(post_id,stair_num)
{


	cancel_edit_post();
	stair_num = stair_num+1;
	var cut_text = $('pagetext_'+post_id).innerHTML.substr(0,67);
	var reg = /(.+)([<]b?r?\s*\\?)$/i;

	cut_text = cut_text.replace(reg,"$1");

	$('reply_post_text').innerHTML = '<div class="content"><p class="msg inline"><span><strong>回复'+stair_num+'楼&nbsp;'+$('rp_un_'+post_id).innerHTML+'</strong>&nbsp;:&nbsp;'+cut_text+'</span></p></div>';

	$('reply_des').value =  cut_text;

	$('reply_user_id').value = $('pu_'+post_id).innerHTML;

	$('reply_user_name').value = $('rp_un_'+post_id).innerHTML;

	$('stair_num').value = stair_num;

	//$('post_form')['text_c'].style.display = 'block';
	$('post_form')['text_c'].focus();
	$('reply_type').value = 1;
	return false;
}

function cancel_reply_post()
{
	$('reply_post_text').innerHTML = "";
	$('stair_num').value = 0;
}

function xajax_postedit_show(){return xajax.call("postedit_show", arguments, 1);}
var pre_edit_post_id = 0,pre_edit_html = '';
function post_edit_show(post_id)
{
	pre_edit_html = $('pagetext_'+post_id).innerHTML;
	if($('pagetext_'+pre_edit_post_id))
	{
		cancel_edit_post();
	}
	
	cancel_reply_post();
	$('pagetext_'+post_id).style.display = "none";
	$('textedit_'+post_id).innerHTML = $('edit_post_face').innerHTML;
	$('post_id').value = post_id; 
	
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_postedit_show(post_id); 
	pre_edit_post_id = post_id;
	return false;

}

function cancel_edit_post()
{
	var post_id = $('post_id').value;
	if($('pagetext_'+post_id))
	{
		$('textedit_'+post_id).innerHTML = "";
		$('pagetext_'+post_id).style.display = "block";$('pagetext_'+post_id).innerHTML = pre_edit_html;
		pre_edit_post_id = 0;
	}

	return false;
}



function edit_post(form_name)
{
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_edit_post(xajax.getFormValues(form_name));
	return false;
}

function get_thread_category(group_id)
{
	GROUP_ID = group_id;
	xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
	xajax_get_thread_category(group_id);
	return false;
}

function view_post(post_id)
{
	if(post_id)
	{
		xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
		xajax.call("view_post", arguments, 1);
	}
	return false;
}

function restore_post(post_id)
{
	if(post_id)
	{
		xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
		xajax.call("restore_post", arguments, 1);
	}
	return false;
}
function xajax_get_pic_list(){return xajax.call("get_pic_list", arguments, 1);}
function xajax_add_bulletin(){return xajax.call("add_bulletin", arguments, 1);}
function xajax_send_bulletin_content(){return xajax.call("send_bulletin_content", arguments, 1);}
function xajax_get_online_list(){return xajax.call("get_online_list", arguments, 1);}
function xajax_get_visit_list(){return xajax.call("get_visit_list", arguments, 1);}

//留言JS 部分..
function xajax_delete_bulletin(){return xajax.call("delete_bulletin", arguments, 1);}
function xajax_add_bulletin_reply(){return xajax.call("add_bulletin_reply", arguments, 1);}
function delete_bulletin(bulletin_id,reply_bulletin_id)
{
	var space_id  = 0;
	if(typeof arguments[2] != 'undefined')
		space_id = arguments[2];
	xajaxRequestUri = 'index.php?module=index&is_ajax=group_ajax'
	xajax_delete_bulletin(bulletin_id,reply_bulletin_id,space_id);
}


var old_bulletin_id;
function show_bulletin_reply(primary_bulletin_id,reply_bulletin_id)
{
	if(old_bulletin_id)
	{
		try
		{
			$('bulletin_reply_'+old_bulletin_id).innerHTML = '';
		}
		catch(e)
		{

		}
		if(primary_bulletin_id == reply_bulletin_id)
		{
			hideElement('bulletin_replys_' + old_bulletin_id);
			if(old_bulletin_id == primary_bulletin_id)
			{
				hideElement('bulletin_replys_' + old_bulletin_id);
				old_bulletin_id = null;
				return false;
			}
		}
	}

	if(primary_bulletin_id == reply_bulletin_id)
	{
		showElement('bulletin_replys_' + reply_bulletin_id);
	}

	old_bulletin_id = primary_bulletin_id;


	if($('tmp_rp_text'))
	{
		$('to_username').value = arguments[2]
		$('tmp_rp_text').innerHTML = '回复'+arguments[2];
	}
	$('bulletin_reply_'+primary_bulletin_id).innerHTML = $('bulletin_reply_div').innerHTML;
	$('primary_bulletin_id').value = primary_bulletin_id;
	$('reply_bulletin_id').value = reply_bulletin_id;
	$('reply_content').focus();
	return false;
}

function add_bulletin_reply()
{

	if ($('reply_content').value && $('reply_content').value.length < 300)
	{
		xajaxRequestUri = 'index.php?module=index&is_ajax=group_ajax'
		xajax_add_bulletin_reply(xajax.getFormValues('bulletin_reply_form'));
		$('bulletin_reply_'+old_bulletin_id).innerHTML = '';
	}
	else
	{
		if ($('reply_content').value.length > 300)
		{
			alert('留言内容太长');
		}
		else
		{
			alert('请填写留言内容。');
		}
		$('reply_content').focus();
	}
	return false;

}

function get_pic_list(albums_id,obj_name,start,per_page)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
	xajax_get_pic_list(albums_id,obj_name,start,per_page);
}


function add_bulletin(val,obj_name)
{
	var type = 0;
	if(arguments[2])
		type = arguments[2];
	var space_id = 0;
	if(typeof arguments[3] != 'undefined')
		space_id = arguments[3];

	var touser_name= '';
	if(typeof arguments[4] != 'undefined')
		touser_name = arguments[4];
	var is_quick = 0;
	if(typeof arguments[5] != 'undefined')
		is_quick = 1;

	var b_content = $(val).value;
	if (b_content && b_content.length < 300)
	{
		post_button_click('bnt_submit','sending_data');
		xajaxRequestUri = 'index.php?module=index&is_ajax=group_ajax';
		xajax_add_bulletin(b_content,obj_name,type,space_id,touser_name,is_quick);
	}
	else
	{
		if (b_content.length > 300)
		{
			alert('留言内容太长');
		}
		else
		{
			alert('请填写留言内容。');
		}
	}
	return false;
}

function write_bulletin()
{
	$('bulletin_content').focus();
	return false;
}

function send_bullutin_content()
{
	var content = $('bulletin_content').value;
	try
	{
		var tag = $('no_bulletin').parentNode;
		tag.removeChild($('no_bulletin'));
	}
	catch(e)
	{

	}
	if (content && content.length < 300)
	{
		xajaxRequestUri = 'index.php?module=index&is_ajax=group_ajax';
		xajax_send_bulletin_content(content);
	}
	else
	{

		if (content.length > 300)
		{
			alert('留言内容太长');
		}
		else
		{
			alert('请填写留言内容。');
		}
	}
}

function expend(event_src,obj_pre,f,t)
{
	for(i = f;i<t;i++)
	{
		if($(obj_pre+i))
		{
			$(obj_pre+i).style.display = 'block';
		}
	}
	$(event_src).innerHTML = '收缩';
	$(event_src).onclick = function () { return shrink(event_src,obj_pre,f,t);};
	$(event_src).className = 'close_out';
	return false;
}


function shrink(event_src,obj_pre,f,t)
{
	for(i = f+1;i<t;i++)
	{
		if($(obj_pre+i))
		{
			$(obj_pre+i).style.display = 'none';
		}
	}
	$(event_src).innerHTML = '展开';
	$(event_src).onclick = function () { return expend(event_src,obj_pre,f,t);};
	$(event_src).className = 'open_out';
	return false;
}

/*
在线 访客
*/
function get_online_list(obj,start,per_page)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_get_online_list(obj,start,per_page);
	return false;
}

function get_visit_list(obj,start,per_page)
{

	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_get_visit_list(obj,start,per_page);
	return false;
}

// JavaScript Document
function xajax_fetch_group_list(){return xajax.call("fetch_group_list", arguments, 1);}
function xajax_fetch_group_info(){return xajax.call("fetch_group_info", arguments, 1);}

function fetch_group_list(catid, pp, gname)
{
	$('group_list_page').innerHTML = '';
	loading_cell('group_list_cell');
	if (typeof gname == 'undefined')
	{
		gname = '';
	}
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_fetch_group_list(catid, pp, gname);
	return false;
}

function search_group()
{
	var gname = $('s_group_name').value;
	fetch_group_list(0, 0, gname);
}
var expand_node;
function expand_g_cat(cid,can_expand)
{
	if(!can_expand)
	{
		return;
	}
	var isexpand = $('cat_child_' + cid).style.display;
	var eimg = $('expand_node_' + cid).src;
	if (isexpand == 'none')
	{
		$('cat_child_' + cid).style.display = '';
		$('expand_node_' + cid).src = eimg.replace(/index_(\d+)/i, 'index_' + 38);
		if($('cat_child_' + expand_node) && expand_node != cid && $('cat_child_' + expand_node).style.display == '')
		{
			$('cat_child_' + expand_node).style.display = 'none';
			$('expand_node_' + expand_node).src = $('expand_node_' + expand_node).src.replace(/index_(\d+)/i, 'index_' + 39);
		}
		expand_node = cid;
	}
	else
	{
		$('cat_child_' + cid).style.display = 'none';
		$('expand_node_' + cid).src = eimg.replace(/index_(\d+)/i, 'index_' + 39);
	}
}

function fetch_group_info(gids, limit)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	gids = gids.split(',');
	for (var i = 0; i < gids.length; i++)
	{
		var group_id = gids[i];
		if (group_id)
		{
			xajax_fetch_group_info(group_id, limit);
		}
	}
	return false;
}
// JavaScript Document
function xajax_fetch_thread_list(){return xajax.call("fetch_thread_list", arguments, 1);}

function fetch_thread_list(type, pp, limit)
{
	$('thread_list_page').innerHTML = '';
	loading_cell('group_thread_lists');
	xajaxRequestUri = 'index.php?module=thread&is_ajax=home_ajax';
	xajax_fetch_thread_list(type, pp, limit);
	return false;
}
function xajax_group_delete_albums(){return xajax.call("group_delete_albums", arguments, 1);}
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function xajax_group_update_albums(){return xajax.call("group_update_albums", arguments, 1);}
function group_update_albums(albums_id,form,pp)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
	xajax_group_update_albums(albums_id,xajax.getFormValues(form),pp);
}
function xajax_group_show_create(){return xajax.call("group_show_create", arguments, 1);}
function xajax_group_albums_edit(){return xajax.call("group_albums_edit", arguments, 1);}
var albums_tmp = new Array();
function group_albums_edit(id)
{
	if($('albums_name').value=='')
	{
		alert('相册名称不能为空!');
		$('albums_name').focus();
		return false;
	}

	if($('albums_category_id').value==0)
	{
		alert('请选择相册分类!');
		$('albums_category_id').focus();
		return false;
	}
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
	xajax_group_albums_edit(id,xajax.getFormValues('albums_edit_form'));
}

function group_show_create(albums_id)
{
	if(albums_id)
	{
		loading_cell('albums_create_tab');
		xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
		xajax_group_show_create(albums_id,arguments[1]);

		window.scroll(0,0);
		$('albums_create_btn').onclick=function(){loading_cell('albums_create_tab');group_show_create();}
	}
	else
	{
		if($('album_create_form') == null)
		{
			$('albums_create_btn').className = 'open';
			loading_cell('albums_create_tab');
			xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
			xajax_group_show_create(albums_id);

			$('albums_create_btn').onclick=function(){group_show_create();}
		}
		else
		{
			if($('albums_create_btn').className == 'open')
			{
				$('albums_create_btn').className = 'close';
				$('albums_create_tab').style.display = 'none';
			}
			else
			{
				$('albums_create_btn').className = 'open';
				$('albums_create_tab').style.display = 'block';
			}
		}
	}
	return false;
}

function xajax_chang_albums_tab(){return xajax.call("chang_albums_tab", arguments, 1);}
function xajax_show_group_albums(){return xajax.call("show_group_albums", arguments, 1);}
function xajax_show_group_pictures(){return xajax.call("show_group_pictures", arguments, 1);}
function xajax_switch_albums_category(){return xajax.call("switch_albums_category", arguments, 1);}
function xajax_do_albums_create(){return xajax.call("do_albums_create", arguments, 1);}
function xajax_material_name_edit_save(){return xajax.call("material_name_edit_save", arguments, 1);}
function xajax_pictures_batch_delete(){return xajax.call("pictures_batch_delete", arguments, 1);}
function xajax_pictures_batch_move(){return xajax.call("pictures_batch_move", arguments, 1);}
function xajax_check_g_albums_upload(){return xajax.call("check_g_albums_upload", arguments, 1);}

//--------------------------------------- 相册详细页面
function xajax_group_show_album_pictures(){return xajax.call("group_show_album_pictures", arguments, 1);}
function xajax_group_show_albums(){return xajax.call("group_show_albums", arguments, 1);}

function group_show_albums(group_id,pp,albums_id)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
	xajax_group_show_albums(group_id,pp,albums_id);
}

function group_show_album_pictures(albums_id,pp,load_right)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
	xajax_group_show_album_pictures(albums_id,pp,load_right);
}
//--------------------------------------- 相册详细页面

function group_pictures_batch_delete()
{
	var tar = $("pictures_form");
	var albums_id = $("albums_id").value;
	var pictures_ids = '' ;
	var splittag = '' ;

	for (var i=0;i<tar.elements.length;i++)
	{
		var e = tar.elements[i];
		if (e.type=='checkbox' && e.checked == true && e.name !== 'checkall')
		{
			pictures_ids += splittag + e.id.substr(18);
			splittag = '/';
		}
	}
	if(pictures_ids== '')
	{
		alert('您没有选择!');
		return ;
	}
	if(confirm('确定删除所选图片?'))
	{
		xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
		xajax_pictures_batch_delete(pictures_ids,albums_id);
	}
}


var batch_move ;
function group_pictures_batch_move(type)
{
	var tar = $("pictures_form");
	var albums_id = $("albums_id").value;
	var pictures_ids = '' ;
	var splittag = '' ;
	var count = 0;
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'

	if(type == 0)
	{
		for (var i=0;i<tar.elements.length;i++)
		{
			var e = tar.elements[i];
			if (e.type=='checkbox' && e.checked == true && e.name !== 'checkall')
			{
				pictures_ids += splittag + e.id.substr(18);
				splittag = '/';
				count ++;
			}
		}
		if(pictures_ids== '')
		{
			alert('您没有选择!');
			return ;
		}
		batch_move = $("batch_move").innerHTML;
		xajax_pictures_batch_move(pictures_ids,albums_id,newalbums_id,type);
		return ;
	}
	else if(type==1)
	{
		var newalbums_id = $("newalbums_id").value;
		for (var i=0;i<tar.elements.length;i++)
		{
			var e = tar.elements[i];
			if (e.type=='checkbox' && e.checked == true && e.name !== 'checkall')
			{
				pictures_ids += splittag + e.id.substr(18);
				splittag = '/';
				count ++;
			}
		}
		if(pictures_ids== '')
		{
			alert('您没有选择!');
			return ;
		}
		if(newalbums_id == albums_id)
		{
			alert('目标相册和当前相册相同,请重新选择!');
			return ;
		}
		if(confirm('确定转移所选图片?'))
		{
			xajax_pictures_batch_move(pictures_ids,albums_id,newalbums_id,type,count);
		}
	}
	else
	{
		$("batch_move").innerHTML = batch_move;
	}
}


function group_material_name_edit(material_id,old_name)
{
	$('material_name_'+material_id).innerHTML = '<input style="width:60px" id="new_material_name_'+material_id+'" onblur="group_material_name_edit_save('+material_id+',\''+old_name+'\');"  value="'+old_name+'" />';
	$('new_material_name_'+material_id).focus();
	return false;
}

function group_material_name_edit_save(material_id,old_name)
{
	if($('new_material_name_'+material_id).value == old_name || $('new_material_name_'+material_id).value == '')
	{
		$('material_name_'+material_id).innerHTML =old_name;
		return;
	}

	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
	xajax_material_name_edit_save(material_id,$('new_material_name_'+material_id).value);
}

function group_show_material_checkbox(material_id,type)
{
	if($('material_checkbox_'+material_id).checked == true)
	{
		return;
	}
	if(type)
	{
		$('material_checkbox_'+material_id).style.display = 'block';
	}
	else
	{
		$('material_checkbox_'+material_id).style.display = 'none';
	}
}
function group_material_checkbox_onclick(e,Ptr)
{
	var albums_id = $("albums_id").value;
	var tarNode = Ptr.parentNode;
	if(albums_id == 0)
	{
		return;
	}
	if(Ptr.checked == true)
	{
		tarNode.className = 'liBox check';
	}
	else
	{
		tarNode.className = 'liBox';
	}
	if(isIE)
		window.event.cancelBubble = true;
	else
	{
		e.stopPropagation();
	}
}

function group_material_checkall()
{
	var tar = $("pictures_form");
	for (var i=0;i<tar.elements.length;i++)
	{
		var e = tar.elements[i];
		if (e.type=='checkbox' && e.name !== 'checkall')
		{
			e.checked = $('checkall').checked;
			if($('checkall').checked == true)
			{
				e.parentNode.className = 'liBox check';
			}
			else
			{
				e.parentNode.className = 'liBox';
			}
		}
	}
}

function group_chang_albums_tab(type,pp)
{
	$('tip1').style.display="none";
	$('tip2').style.display="none";
	if(type == 'pictures')
	{
		$('albums_tab_albums').className='auto';
		$('albums_tab_albums_new').innerHTML='';
		if(document.getElementById('albums_tab_create'))
			$('albums_tab_create').className='auto';
		$('albums_tab_pictures').className='current';
	}
	else if(type == 'albums')
	{
		$('albums_tab_pictures').className='auto';
		$('albums_tab_pictures_new').innerHTML='';
		if(document.getElementById('albums_tab_create'))
			$('albums_tab_create').className='auto';
		$('albums_tab_albums').className='current';
	}
	else if(type == 'create')
	{
		$('albums_tab_pictures').className='auto';
		$('albums_tab_pictures_new').innerHTML='';
		$('albums_tab_albums').className='auto';
		$('albums_tab_albums_new').innerHTML='';
		$('albums_tab_create').className='current';
	}

	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
	xajax_chang_albums_tab(type,pp);
}

function show_group_albums(pp)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
	xajax_show_group_albums(pp);
}

function show_group_pictures(pp,time)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
	xajax_show_group_pictures(pp,time);
}

function switch_albums_category(id)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=home_ajax';
	xajax_switch_albums_category(id);
}

function group_do_albums_create(action)
{
	if($('albums_name').value=='')
	{
		alert('相册名称不能为空!');
		$('albums_name').focus();
		return false;
	}

	if($('albums_category_id').value==0)
	{
		alert('请选择相册分类!');
		$('albums_category_id').focus();
		return false;
	}

	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
	xajax_do_albums_create(action,xajax.getFormValues('album_create_form'));
}

function group_delete_albums(group_id,albums_id)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax'
	xajax_group_delete_albums(group_id,albums_id);
}

function check_g_albums_upload(albums_id)
{
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
	xajax_check_g_albums_upload(albums_id);
}
function xajax_set_cover(){return xajax.call("set_cover", arguments, 1);}
function xajax_delete_picture(){return xajax.call("delete_picture", arguments, 1);}
function xajax_publish_comment(){return xajax.call("publish_comment", arguments, 1);}
function xajax_group_pircture_name_edit(){return xajax.call("group_pircture_name_edit", arguments, 1);}
function xajax_delete_picture_comment(){return xajax.call("delete_picture_comment", arguments, 1);}
function group_delete_picture_comment(material_id,primary_comment_id,comment_id)
{
	xajaxRequestUri = 'index.php?module=picture&is_ajax=group_ajax'
	xajax_delete_picture_comment(material_id,primary_comment_id,comment_id);
}
function group_pircture_name_edit(material_id,old_name,type)
{
	if(type == 0)
	{
		$('material_name_edit_'+material_id).innerHTML = '<input name="material_description" id="material_description" value="'+old_name+'" onblur="group_pircture_name_edit('+material_id+',\''+old_name+'\',1);"/>';
		$('material_description').focus();
	}
	else
	{
		if($('material_description').value == old_name || $('material_description').value=='')
		{
			$('material_name_edit_'+material_id).innerHTML = old_name;
			return false;
		}
		else
		{
			xajaxRequestUri = 'index.php?module=picture&is_ajax=group_ajax'
			xajax_group_pircture_name_edit(material_id,$('material_description').value);
		}
	}
	return false;
}

function group_set_cover(material_id)
{
	xajaxRequestUri = 'index.php?module=picture&is_ajax=group_ajax'
	xajax_set_cover(material_id);
}

function group_delete_picture(material_id)
{
	xajaxRequestUri = 'index.php?module=picture&is_ajax=group_ajax'
	xajax_delete_picture(material_id);
}

function group_publish_comment()
{
	if($('content').value=='')
	{
		alert('请输入评论内容!');
		$('content').focus();
		return;
	}
	xajaxRequestUri = 'index.php?module=picture&is_ajax=group_ajax'
	xajax_publish_comment(xajax.getFormValues('publish_comment_form'));
}

function quote(Ptr)
{
	var val1 = Ptr.parentNode.parentNode.firstChild.firstChild.nextSibling.innerHTML;
	var val2 = Ptr.parentNode.parentNode.firstChild.lastChild.innerHTML;
	var val3 = Ptr.parentNode.parentNode.parentNode.lastChild.innerHTML;
	var val4 = Ptr.parentNode.lastChild.innerHTML;
	$('content').value = '<blockquote style="BORDER-RIGHT: gray 1px dashed; PADDING-RIGHT: 10px; BORDER-TOP: gray 1px dashed; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; MARGIN: 10px; BORDER-LEFT: gray 1px dashed; PADDING-TOP: 10px; BORDER-BOTTOM: gray 1px dashed">引自：<cite>' +val4+ ' '+val1+'</cite>  于 <ins>'+val2+'</ins> 发表的评论<br />引用内容：<br /><q>' +val3+ '</q></blockquote>';
	var range = document.getElementById('content').createTextRange();
	range.moveStart('character',$('content').value.length);
	range.collapse(true);
	range.select();
}

function replay(Ptr)
{
	var val1 = Ptr.parentNode.parentNode.firstChild.firstChild.nextSibling.innerHTML;
	var val2 = Ptr.parentNode.parentNode.firstChild.lastChild.innerHTML;
	var val3 = Ptr.parentNode.parentNode.parentNode.lastChild.innerHTML;
	var val4 = Ptr.parentNode.lastChild.innerHTML;
	$('content').value = '<blockquote style="BORDER-RIGHT: gray 1px dashed; PADDING-RIGHT: 10px; BORDER-TOP: gray 1px dashed; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; MARGIN: 10px; BORDER-LEFT: gray 1px dashed; PADDING-TOP: 10px; BORDER-BOTTOM: gray 1px dashed">回复：<cite>' +val4+ ' '+val1+'</cite>  于 <ins>'+val2+'</ins> 发表的评论<br />原内容：<br /><q>' +val3+ '</q></blockquote>';
	$('content').focus();
	var range = document.getElementById('content').createTextRange();
	range.moveStart('character',$('content').value.length);
	range.collapse(true);
	range.select();
}
function xajax_group_check_blog(){return xajax.call("group_check_blog", arguments, 1);}
function xajax_group_blog_batch_check(){return xajax.call("group_blog_batch_check", arguments, 1);}

function group_check_blog(blog_id,group_id,check)
{
	xajaxRequestUri = 'index.php?module=blog&is_ajax=group_ajax';
	xajax_group_check_blog(blog_id,group_id,check);
	return false;
}

function group_blog_checkall()
{
	var tar = $("group_blog_form");
	for (var i=0;i<tar.elements.length;i++)
	{
		var e = tar.elements[i];
		if (e.type=='checkbox' && e.name !== 'checkall')
		{
			e.checked = $('checkall').checked;
		}
	}
}
////显示地图
//function showMap()
//{
//	var map_canvas = document.getElementById("map_canvas");
//	map_canvas.style.display = "block";
//}
function group_blog_batch_check(group_id,check)
{
	xajaxRequestUri = 'index.php?module=blog&is_ajax=group_ajax';
	var tar = $("group_blog_form");
	var blog_ids = '';
	var split_tag = '';
	for (var i=0;i<tar.elements.length;i++)
	{
		var e = tar.elements[i];
		if (e.type=='checkbox' && e.name !== 'checkall')
		{
			if(e.checked == true)
			{
				blog_ids += split_tag +e.value;
			}
			split_tag = ',';
		}
	}
	//alert(blog_ids);
	if(blog_ids == '')
		return alert('您没有选择!');
	xajax_group_blog_batch_check(blog_ids,group_id,check);
}
function xajax_save_permission(){return xajax.call("save_permission", arguments, 1);}
function save_permission(form_name)
{
	xajaxRequestUri = 'index.php?module=manage&is_ajax=group_ajax';
	xajax_save_permission(xajax.getFormValues(form_name));
	return false;
}
function xajax_showMembers(){return xajax.call("showMembers", arguments, 1);}
function showMembers(group_id)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_showMembers(group_id);
	return false;
}

function xajax_save_basic(){return xajax.call("save_basic", arguments, 1);}
function save_basic(form_name)
{
	xajaxRequestUri = 'index.php?module=manage&is_ajax=group_ajax';
	xajax_save_basic(xajax.getFormValues(form_name));
	return false;
}

function xajax_save_img(){return xajax.call("save_img", arguments, 1);}
function save_img(form_name)
{
	xajaxRequestUri = 'index.php?module=manage&is_ajax=group_ajax';
	xajax_save_img(xajax.getFormValues(form_name));
	return false;
}

function xajax_save_groupinfo(){return xajax.call("save_groupinfo", arguments, 1);}
function save_groupinfo(form_name, ct)
{
	xajaxRequestUri = 'index.php?module=manage&is_ajax=group_ajax';
	xajax_save_groupinfo(xajax.getFormValues(form_name), ct);
	return false;
}

function assign_upload_img(material_id,src)
{
	$('new_logo_img').value = material_id;
	$('logo_img').src = src;
}


function list_my_g()
{
	if(!$('li_my_g').innerHTML)
	{
		xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
		xajax.call("list_my_g", arguments, 1);
	}
	showElement('li_my_g');
}
function hide_my_g()
{
	hideElement('li_my_g');
	clearTimeout(g_setTime);
	g_setTime = null;
}



function xajax_save_sort(){return xajax.call("save_sort", arguments, 1);}
function save_sort()
{
	var currentPP = $('currentPP').value;
	var ablumMax = $('ablumMax').value;
	var liss = $('ablumMoveArea').getElementsByTagName('li');
	var value = '';
	for(var m=0;m<liss.length-1;m++)
	{
		if(liss[m].id)
		{
			value += liss[m].id + '--' + (currentPP*ablumMax + m) + '||';
		}
	}
	var group_ablum_id = $('groupAlbumId').value;
	xajaxRequestUri = 'index.php?module=albums&is_ajax=group_ajax';
	xajax_save_sort(value,group_ablum_id);
}

function threadList(filter , group_id,rewrite)
{
    if(!filter) return false;
	if(rewrite)
	{
		var url = 'thread-' + group_id + '-f-' + filter + '.html';
	}
	else
	{
		var url = '?m=thread&group_id=' + group_id + '&filter=' + filter;
	}
    if(top)
    {
        top.window.location.href = url;
    }
    else
    {
        window.location.href = url;
    }
    return false;
}


function xajax_addCategory(){return xajax.call("addCategory", arguments, 1);}
function addCategory(group_id)
{
	var ca_name = document.getElementById("cate_name_"+group_id).value;
	if(!ca_name)
	{
		alert("分类名不可为空,请填写分类名!");
	}
	else
	{
		xajaxRequestUri = 'index.php?module=thread&is_ajax=group_ajax';
		xajax_addCategory(group_id,ca_name);
	}
}
function xajax_showMap(){return xajax.call("showMap", arguments, 1);}
function showMap()
{
	document.getElementById("hide_btn_map").style.display = 'block';
	
	map_op_type = 1;
	xajaxRequestUri = 'index.php?module=map_show&is_ajax=group_ajax';
	xajax_showMap();
	if(document.getElementById("child_cell")){
		document.getElementById("child_cell").style.display="none";
	} 
}

function xajax_check_user_grand(){return xajax.call("check_user_grand", arguments, 1);}
function check_user_grand(userid)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_check_user_grand(userid);
	/*
	if(!document.getElementById("nologin_g_lat").value)
	{
		document.getElementById('area_result_div').style.color = "red";
	}
	else
	{
		
	}	*/
}

//当地主
function xajax_grand_this(){return xajax.call("grand_this", arguments, 1);}
function grand_this(user_id,group_id)
{
	if(parseInt(g_login_userId,10) > 0)
	{
		xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
		xajax_grand_this(user_id,group_id);
	}
	else
	{
		alert('您尚未登录，请先登录!');
	}
}
function xajax_showChildren(){return xajax.call("showChildren", arguments, 1);}
function showChildren(group_id)
{
	//GUnload();
	document.getElementById("hide_btn_map").style.display = 'block';  
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_showChildren(group_id);
}

function hideContent()
{
	//GUnload();
	document.getElementById("sort_container").innerHTML = ''; 
	document.getElementById("hide_btn_map").style.display = 'none';
	if(document.getElementById("child_cell")){
		document.getElementById("child_cell").style.display="block";
	}
}

function change_val(thread_id)
{
	var chk = document.getElementById('syntot' + thread_id);
	if(chk.checked == true)
	{
		document.getElementById('syntot' + thread_id).value=1;
	}
	else
	{
		document.getElementById('syntot' + thread_id).value=0;
	}
}
function changevalue(obj)
{
	if(obj.checked == true)
	{
		obj.value = 1;
	}
	else
	{
		obj.value = 0;
	}
}

//显示更多讨论区
function xajax_show_moregroup(){return xajax.call("show_moregroup", arguments, 1);}
function show_more_g(gid)
{
	
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax'; 
	xajax_show_moregroup(gid);
}

//收起更多
function fold_more()
{
	document.getElementById('more_groups').innerHTML = '';
	document.getElementById('more_groups').style.display = 'none';
}

//根据标题搜索帖子
function xajax_search_thread(){return xajax.call("search_thread", arguments, 1);}
function search_thread()
{
	var val = document.getElementById("txt_search_thread").value;
	if(!val)
	{
		alert("请输入搜索关键字");
		document.getElementById("txt_search_thread").focus();
	}
	else
	{
		xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax'; 
		xajax_search_thread();
	}
}

//search for group  
function _search_group(id)
{
	xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
	xajax_g_search_group(xajax.getFormValues(id));
	 
}
function xajax_checkHash(){return xajax.call("checkHash", arguments, 1);}
function checkHash()
{ 	
	var h = document.location.hash;
	if(h != "undefined" && h.length > 0)
	{
		h = h.replace("#",'');
		xajaxRequestUri = 'index.php?module=group&is_ajax=group_ajax';
		xajax_checkHash(h);
	} 
	 
}



function showIframe(link)
{
	var html = '<div style="position:relative;width:560px;"><span style="display:block;position:absolute;right:36px;top:10px;cursor:pointer;"><img width="14" height="14" onclick="document.getElementById(\'mask_outbox\').style.display=\'none\';" src="http://www.hoolo.tv/res/cspd/images/close.gif"></span><iframe src="' + link + '" style="width:560px;height:430px"></iframe></div>';
	document.getElementById("mask_outbox").style.display="block";
	document.getElementById("mask_content").innerHTML=html;
	return false;
}

function getName(id,type)
{
	switch(id)
	{
		case 'gname':
			title = "地盘名称:" + document.getElementById(id).value;
			break;
		case 'group_type':
			title = "地盘类型:" + addField(id);
			break;
		case 'province':
			title = "所在城区:" +  addField(id);
			break;
		default:
			break;
	}
	document.getElementById("show"+id).style.display="block";
//	var prex = document.getElementById("prex_"+id).innerHTML;
	document.getElementById("show"+id).innerHTML = title;
}

function addField(id){
    var optionItems = document.getElementById(id).options;   //得到option集合
    var value = document.getElementById(id).value;
    var title ;
    for(var i=0;i < optionItems.length;i++){
       if(optionItems[i].value==value){
         title = optionItems[i].innerHTML+"";   //这就是你选中的 option value对应的 描述文字。
       }
    }
  return title;
}

function insert_image(imgpath)
{
	imgpath = '<img  src="'+imgpath+ '"  border="0" style="vertical-align:middle" /> '; 
	editors.insertHtml(imgpath);
}
