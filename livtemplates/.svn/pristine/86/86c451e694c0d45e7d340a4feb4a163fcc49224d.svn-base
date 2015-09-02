function hg_showAddpicsort(id)
{
	id = parseInt(id);
	if(!id)
	{	
		$('#tuji_title').text('新增图集分类');
		id = 0;
	}
	else
	{
		$('#tuji_title').text('编辑图集分类');
	}
    if($('#add_new_sort').css('display')=='none')
	{
		var url= './run.php?mid='+gMid+'&a=form&id='+id;
		hg_ajax_post(url);
	   $('#add_new_sort').css({'display':'block'});
	   $('#add_new_sort').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closePicSortTpl();
	}
}

function hg_show_sortTpl(html)
{
	$('#tuji_new_sort').html(html);
}

function hg_closePicSortTpl()
{
	 $('#add_new_sort').animate({'right':'120%'},'normal',function(){$('#add_new_sort').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}

function hg_update_callback(json)
{
	var json_data = $.parseJSON(json);
	if(json_data['id'])
	{
		$('#tuji_sort_name_'+json_data['id']).text(json_data['sort_name']).css('color',json_data.color);
		$('#tuji_sort_desc_'+json_data['id']).text(json_data['sort_desc']);
		hg_closePicSortTpl();
	}
}

function hg_create_callback(json)
{
	var json_data = $.parseJSON(json);
	var url = "run.php?mid="+gMid+"&a=add_new_list&id="+json_data.id;
	hg_ajax_post(url);
}

function hg_OverAddTujiSort(html)
{
	$('#tuji_sortlist').prepend(html);
	hg_closePicSortTpl();
}

function hg_delete_tujisort(obj)
{
	var obj =  eval('('+obj+')');
	if(obj.sid)
	{
		var ids = new Array();
		for(var i = 0;i<obj.sid.length;i++)
		{
			ids.push(obj.sid[i]);
		}
		hg_remove_row(ids.join(','));
	}
	//提示用户不能删除的分类
	if(obj.nid[0])
	{
		alert('有'+obj.nid.length+'个分类里面存在集合，不能删除');
	}
}
