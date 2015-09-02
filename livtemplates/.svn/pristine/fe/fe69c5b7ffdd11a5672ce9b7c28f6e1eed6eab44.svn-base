/*开启回收站*/
var gSettingId = '';
function hg_recycle_is_open(id,type)
{
	gSettingId = id;
	if(type==1)
	{
		var url = './run.php?mid=' + gMid + '&a=app_recycle_open&id=' + id;
	}
	else
	{
		var url = './run.php?mid=' + gMid + '&a=module_recycle_open&id=' + id;
	}
	hg_ajax_post(url,'','','recycle_open_back');
}
function recycle_open_back(obj)
{
	if (obj==1)
	{
		$('#is_open_' + gSettingId).addClass('a');
		$('#is_open_' + gSettingId).removeClass('b');
		$('#is_open_' + gSettingId).attr('title','已开启');
	}
	else
	{
		$('#is_open_' + gSettingId).addClass('b');
		$('#is_open_' + gSettingId).removeClass('a');
		$('#is_open_' + gSettingId).attr('title','未开启');
	}
}

function hg_show_edit_module(id,app_name)
{
	$('#tuji_title').text(app_name);
    if($('#add_tuji').css('display')=='none')
	{
       var url= "./run.php?mid="+gMid+"&a=form&id="+id;
       hg_ajax_post(url);
	   $('#add_tuji').css({'display':'block'});
	   $('#add_tuji').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 if($('div[id^="vodplayer_"]').length)
		 {
			 hg_close_opration_info();
		 }
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_close_edit_module_tpl();
	}
}

function hg_close_edit_module_tpl()
{
	 $('#add_tuji').animate({'right':'120%'},'normal',function(){$('#add_tuji').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}

function hg_put_module_tpl(html)
{
	$('#tuji_contents_form').html(html);
	/*
	if(gisEditId)
	{
		top.livUpload.SWF.setButtonText("<span class='white'>添加图片</span>");
		$('#save_base_info').show();
		$('#direct_create').hide();
	}
	else
	{
		top.livUpload.SWF.setButtonText("<span class='white'>选择图片创建</span>");
		$('#direct_create').show();
	}
	*/
}