var gSortId = 0;
function hg_showAddSort(id)
{
	if(gDragMode)
    {
	   return  false;
    }
	
	id = parseInt(id);
	if(!id)
	{
		$('#sort_title').text('新增分类');
		id = 0;
	}
	else
	{
		$('#sort_title').text('编辑分类');
	}
	
    if($('#add_sorts').css('display')=='none')
	{
		var url= './run.php?mid='+gMid+'&a=form&id='+id;
		hg_ajax_post(url);
	   $('#add_sorts').css({'display':'block'});
	   $('#add_sorts').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeSortTpl();
	}
}

function hg_put_vod_node_form(html)
{
	$('#add_sort_tpl').html(html);
}





/*编辑的放入已有的数据*/
function hg_change_sorttext_value(obj)
{
	//$('#display_addleixing_show').text(obj[0].vod_leixing);
	//alert(obj[0].fid);
	$('#addleixing_show').find('a[attrid='+obj[0].fid+']').click();
	$('#vod_addleixing_id').val(obj[0].fid);
	$('#add_sort_name').val(obj[0].name).css('color', obj[0].color);
	$('#add_sort_name_cv').val(obj[0].color);
	gSortId = obj[0].id;
}

/*关闭面板*/
function hg_closeSortTpl()
{
	 $('#add_sorts').animate({'right':'120%'},'normal',function(){$('#add_sorts').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 hg_clearSortData();/*每次关闭都要清空数据*/
}

/*创建完成的回调函数*/
function hg_overCreateSort(sort_id)
{
	hg_closeSortTpl();
	var url = './run.php?mid='+gMid+'&a=add_new_sortlist&id='+sort_id;
	hg_ajax_post(url);
}

/*将新添加的一行插入到列表中*/
function hg_put_newSortList(html)
{
	$('#vod_sort_form_list').prepend(html);
}

/*清空数据*/
function hg_clearSortData()
{
	$('#add_sort_name').val('');
	var fid = parseInt($('#fid').val());
	$('#addleixing_show').find('a[attrid='+fid+']').click();
	gSortId = 0;
}

function hg_overEditSort(obj)
{
	var obj = eval('('+obj+')');
	$('#sort_name_' + obj.id).text(obj.name).css('color',obj.color);
	hg_closeSortTpl();
}

function hg_delete_sort(sort_id)
{
	if(gDragMode)
    {
	   return  false;
    }
	var url = "./run.php?mid="+gMid+"&a=delete&sort_id="+sort_id;
	hg_ajax_post(url,'删除',true);
}

function hg_overDeleteSort(obj)
{
	var obj = eval('('+obj+')');
	var ids = obj.ids;
	hg_remove_row(ids);
}
