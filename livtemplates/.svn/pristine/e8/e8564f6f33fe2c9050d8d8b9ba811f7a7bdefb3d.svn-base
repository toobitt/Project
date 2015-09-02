function hg_showAddServer(id)
{
	if(gDragMode)
    {
	   return  false;
    }

	if(id)
	{
		$('#server_title').html('编辑服务器');
	}
	else
	{
		$('#server_title').html('新增服务器');
	}

	if($('#add_server').css('display')=='none')
	{
	   var url = "run.php?mid="+gMid+"&a=form&id="+id;
	   hg_ajax_post(url);
	   $('#add_server').css({'display':'block'});
	   $('#add_server').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeServer();
	}
}

//关闭面板
function hg_closeServer()
{
	$('#add_server').animate({'right':'120%'},'normal',function(){$('#add_server').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}
//放入模板
function hg_putServerTpl(html)
{
	$('#server_form').html(html);
}
//create操作的回调
function hg_OverFormServer(obj)
{
	var obj = eval('('+obj+')');
	var url = "run.php?mid="+gMid+"&a=add_new_server&id="+obj.id;
	hg_ajax_post(url);
	hg_closeServer();
}
//新增一行列表
function hg_insert_serverlist(html)
{
	$('#server_form_list').prepend(html);
}

//更新回调
function hg_change_update_data(obj)
{
	var obj = eval('('+obj+')');
	var id = obj.id;
	$('#name_' +id).html(obj.name);
	$('#uniqueid_'+id).html(obj.uniqueid);
	$('#ip_'+id).html(obj.ip);
	$('#outside_ip_'+id).html(obj.outside_ip);
	$iscur = parseInt(obj.iscur)?'<font color="red">是</font>':'<font color="blue">不是</font>';
	$('#iscur_'+id).html($iscur);
	hg_closeServer();
}