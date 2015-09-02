function hg_showAddcontributesort(id)
{
	id = parseInt(id);
	if(!id)
	{	
		$('#contributetitle').text('新增分类');
		id = 0;
	}
	else
	{
		$('#contribute_title').text('编辑分类');
	}
    if($('#add_new_sort').css('display')=='none')
	{
		var url= './run.php?mid='+gMid+'&a=form&id='+id;
		hg_ajax_post(url);
	   $('#add_new_sort').css({'display':'block'});
	   $('#add_new_sort').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 //hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeContributeSortTpl();
	}
}
function hg_show_sortTpl(html)
{
	$('#contribute_new_sort').html(html);
}

function hg_closeContributeSortTpl()
{
	 $('#add_new_sort').animate({'right':'120%'},'normal',function(){$('#add_new_sort').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}

function hg_update_callback(json)
{   
	var json_data = $.parseJSON(json);
	if(json_data['id'])
	{
		$('#contribute_sort_name_'+json_data['id']).text(json_data['sort_name']);
		$('#contribute_sort_desc_'+json_data['id']).text(json_data['brief']);
		hg_closeContributeSortTpl();
	}
}

function hg_create_callback(json)
{
	var json_data = $.parseJSON(json);
	var url = "run.php?mid="+gMid+"&a=add_new_list&id="+json_data.id;
	hg_ajax_post(url);
}

function hg_put_newsortlist(html)
{
	$('#contri_sortlist').prepend(html);
	hg_closeContributeSortTpl();
}
