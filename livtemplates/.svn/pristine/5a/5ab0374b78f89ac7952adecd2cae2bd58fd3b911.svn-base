function hg_showAddAuth(appid)
{
	if(gDragMode)
    {
	   return  false;
    }

	if(appid)
	{
		$('#auth_title').html('编辑auth');
	}
	else
	{
		$('#auth_title').html('新增auth');
	}

	if($('#add_auth').css('display')=='none')
	{
	   var url = "run.php?mid="+gMid+"&a=form&appid="+appid;
	   hg_ajax_post(url);
	   $('#add_auth').css({'display':'block'});
	   $('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeAuth();
	}
}

//关闭面板
function hg_closeAuth()
{
	$('#add_auth').animate({'right':'120%'},'normal',function(){$('#add_auth').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}
//放入模板
function hg_putAuthTpl(html)
{
	$('#auth_form').html(html);
}
//create操作的回调
function hg_OverFormAuth(obj)
{
	var obj = eval('('+obj+')');
	var url = "run.php?mid="+gMid+"&a=add_new_auth&appid="+obj.appid;
	hg_ajax_post(url);
	hg_closeAuth();
}
//新增一行列表
function hg_insert_authlist(html)
{
	$('#auth_form_list').prepend(html);
}

function hg_show_auth_audit(appid,e)
{
	if(e)
	{
		$('#auth_audit_'+appid).css('visibility','visible');
	}
	else
	{
		$('#auth_audit_'+appid).css('visibility','hidden');
	}
}

function hg_auth_audit(appid)
{
	var url = "run.php?mid="+gMid+"&a=audit&appid="+appid;
	hg_ajax_post(url,'审核',1);
}

//重新绑定appkey
function hg_rebind_appkey(appid)
{
	var url = "run.php?mid="+gMid+"&a=rebind_appkey&appid="+appid;
	hg_ajax_post(url,'重新绑定appkey',1);
}

function hg_over_rebind(obj)
{
	var obj = eval('('+obj+')');
	$('#appkey_show').html(obj.appkey);
}

//审核回调
function hg_change_authstatus(obj)
{
	var obj = eval('('+obj+')');
	$('#auth_status_'+obj.appid).html(obj.status);
	$('#audit_button_'+obj.appid).val(obj.bt_val);
}

//更新回调
function hg_change_update_data(obj)
{
	var obj = eval('('+obj+')');
	$('#custom_name_'+obj.appid).html(obj.custom_name);
	$('#custom_desc_'+obj.appid).html(obj.custom_desc);
	$('#display_name_'+obj.appid).html(obj.display_name);
	obj.expire_time = obj.expire_time!="0" ? obj.expire_time : '永久有效';
	$('#expire_time_'+obj.appid).html(obj.expire_time);
	$('#bundle_id_'+obj.appid).html(obj.bundle_id);
	hg_closeAuth();
}










