function hg_showModule(bundle,id,appname,check_all)
{
	if(gDragMode)
    {
	   return  false;
    }

	if(bundle)
	{
		$('#auth_title').html('更新'+appname+'权限');
	}

	if($('#add_auth').css('display')=='none')
	{
	   var url = "run.php?mid="+gMid+"&a=get_module_limit&bundle="+bundle+"&check_all="+check_all+"&id="+id;
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
function hg_show_role_node(id)
{
	$('#'+id).toggle();
}

function hg_statePublish(id,app,mod,method, el)
{
	window.pubPel = el;
	var url = './run.php?mid=' + gMid + '&a=node_prms&app='+app+'&mod='+mod+'&method='+method+'&id=' + id;
	hg_ajax_post(url);
}
$(function ($) {
hg_show_pubhtml = function (html,id)
{
	$('#vodpub_body').html(html);
	var btn = $(pubPel);
	var tops = '100px', t = '120px';
	var offset = $(pubPel).offset(),
		t = offset.top,
		l = offset.left;

	$('#vodpub').animate({'top':t + 20},
		function(){
			$('#vod_fb').css({'top':t + 'px','left':'98px'}).hide();
		}
	).css({'z-index':99999999,'left': 200});
	
	$('#recommendform').one('submit', function () {
		var column_id = JSON.stringify(eval($('input[name=column_id]', this).val()));
		
		var id = btn.data('dataid');
		$('#' + id).find('input[name=' + id + ']').val(column_id);
		hg_vodpub_hide(true);
		return false;
	});
}

hg_vodpub_hide = function (id)
{
	$('#vod_fb').hide();
	$('#vodpub').css({'top':'-440px'});
}
});