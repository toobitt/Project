function hg_showAddServer(id)
{
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

//更新回调
function hg_change_update_data(obj)
{
	var obj = eval('('+obj+')');
	var id = obj.id;
	$('#version_name_' +id).html(obj.version_name);
	hg_closeServer();
}

function hg_diff_version(obj)
{
	var tmp = obj;
	obj = hg_find_nodeparent(obj, 'FORM');
	var ids = hg_get_checked_id(obj);
	if(!ids)
	{
		alert('请选择版本');
		return;
	}
	var url = "run.php?mid="+gMid+"&a=diffsql&id="+ids+"&infrm=1";
	window.location.href = url;
}













