var gisEditId = 0;
function hg_showAddTuJi(id,show_pic)
{
	if(!id)
	{	
		$('#tuji_title').text('新增图集');
		id = 0;
		gisEditId = 0;
	}
	else
	{
		$('#tuji_title').text('编辑图集');
		gisEditId = id;//编辑的标志位
	}
	
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
		 
		 if(show_pic)
		 {
			 hg_showAllImage();
		 }
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeTuJiTpl();
	}
}

function hg_closeTuJiTpl()
{
	 $('#add_tuji').animate({'right':'120%'},'normal',function(){$('#add_tuji').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 top.livUpload.initPosition();//flash初始位置
}

//将图集表单模板放入页面中
function hg_putTujiAddFormTpl(html)
{
	$('#tuji_contents_form').html(html);
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
}

//检测在编辑的时候，输入框的值是否改变，若改变则不能上传图片(要隐藏上传按钮)
function hg_check_ischange(e)
{
	if(gisEditId)
	{
		var g_stitle =  $('#stitle').val();
		var g_ntitle = $('#title').val();
		if(g_stitle != g_ntitle)
		{
			top.livUpload.initPosition();//flash初始位置
		}
		else
		{
			top.livUpload.OpenPosition();//找回自己的位置
			top.livUpload.SWF.setButtonDimensions(94,24);
		}
	}
}

function hg_OverUpdateTuJi(json)
{
	var obj = eval('('+json+')');
	$('#tuji_title_'+obj.id).text(obj.title);
	$('#tuji_sort_' +obj.id).text(obj.tuji_sort_name);
	hg_closeTuJiTpl();
}

function hg_OverCreateTuJi(obj)
{
	var obj = eval('('+obj+')');
	var url = "./run.php?mid="+gMid+"&a=add_tuji_new&id="+obj.tuji_id;//插入一行列表
	hg_ajax_post(url);
	hg_closeTuJiTpl();
}

//鼠标放上图片上与移走时的事件
function hg_onPicMouseOver(obj,e)
{
	var btn_id = '';
	if($(obj).hasClass('arrL'))
	{
		btn_id = 'left_btn';
	}
	else
	{
		btn_id = 'right_btn';
	}
	
	if(e)
	{
		if($('#'+btn_id).css('display') == 'none')
		{
			$('#'+btn_id).show();
		}
	}
	else
	{
		if($('#'+btn_id).css('display') == 'block')
		{
			$('#'+btn_id).hide();
		}
	}
}

//鼠标放上按钮上显示按钮
function hg_show_btn(obj)
{
	$(obj).show();
}

//显示另外一张图片
function hg_showOtherPic(id,current_page,direct)
{
	if(!parseInt($('#isover').val()) || !direct)
	{
		close_tip_box();
		var url = "./run.php?mid="+gMid+"&a=get_tuji_image&id="+id+"&start="+current_page;
		hg_ajax_post(url);
	}
	else
	{
		display_tip_box();
	}
}

function hg_getTuJiImage(html)
{
	
	$('#tuji_pics_show').html(html);
	$('#tuji_content_img').hide();
	$('#tuji_content_img').fadeIn();
	Liv_resize('#tuji_pics_show');
}

function hg_showAllImage()
{
	if($('#big_content').css('display') == 'none')
	{
		$('#big_content').slideDown('normal',function(){
			top.livUpload.OpenPosition();//flash校正位置
		});
		$('#img_list').hide();
		$('#save_tuji').hide();
		$('#base_info').hide();
		if(gisEditId)//在编辑的情况下才显示
		{
			$('#save_base_info').show();
		}
		$('#show_all').show();
	}
	else
	{
		$('#big_content').slideUp('normal',function(){
			top.livUpload.OpenPosition();//flash校正位置
		});
		if($('div[id^="pic_info_"]').length || gisEditId)
		{
			$('#save_tuji').show();
		}
		else
		{
			$('#save_tuji').hide();
		}
		$('#img_list').show();
		$('#base_info').show();
		$('#save_base_info').hide();
		$('#show_all').hide();
		if(gisEditId && !$('div[id^="pic_info_"]').length)//如果是编辑的话
		{
			var url = "./run.php?mid="+gMid+"&a=show_all_images&id="+gisEditId;
			hg_ajax_post(url);
		}
	}
}

function hg_show_tuji_allimages(html)
{
	$('#img_list').html(html);
}

//点击同上将描述复制到笑面默认框
function hg_copy_comment()
{
	var e = $('#likeup').attr('checked');
	if(e)
	{
		$('#default_comment').val($('#comment').val());
	}
	else
	{
		$('#default_comment').val('');
	}
}

//点击同上时的事件
function hg_checkbox()
{
	var e = $('#likeup').attr('checked');
	if(e)
	{
		$('#likeup').attr('checked',false);
	}
	else
	{
		$('#likeup').attr('checked','checked');
	}
	hg_copy_comment();
}

//实现输入文字时的同步
function hg_onkeyup_copy(obj)
{
	var e = $('#likeup').attr('checked');
	if(e)
	{
		$('#default_comment').val($(obj).val());
	}
}

//点击checkbox旁边的字时同时也详单于选中checkbox
function hg_checkboxOn(id)
{
	var e = $('#'+id).attr('checked');
	if(e)
	{
		$('#'+id).attr('checked',false);
	}
	else
	{
		$('#'+id).attr('checked','checked');
	}
}

//删除小图标的显示与隐藏
function hg_show_png(id,e)
{
	if(e){$('#remove_icon_'+id).show();}else{$('#remove_icon_'+id).hide();}
}

//删除当前
function hg_remove_thisone(id)
{
	$('#pic_info_'+id).remove();
}

//选择某个图片作为封面
function hg_switch_cover(obj,id)
{
	if($(obj).css('display') == 'block')
	{
		$(obj).hide();
		$('#pic_cover_id').val(0);
		return;
	}
	
	$('div[id^="select_cover_"]').hide();
	$(obj).show();
	$('#pic_cover_id').val(id);
}

//保存对图片信息的编辑
function hg_saveImageInfo()
{
	var ids = new Array();
	var ids_str = '';
	$('input[name="image_ids[]"]').each(function(){
		ids.push($(this).val());
	});
	ids_str = ids.join(',');
	var url = "./run.php?mid="+gMid+"&a=save_image_info&id="+ids_str;
	hg_ajax_post(url);
}

//保存图片的更新的回调
function hg_saveImageOk(obj)
{
	var obj = eval('('+obj+')');
	if(obj.flag)
	{
		$('#img_'+obj.tuji_id).attr('src',obj.img);
	}
	hg_closeTuJiTpl();
}

//编辑图片的描述
function hg_edit_comment(obj,e,id,is_tuji)
{
	$(obj).hide();
	if(e)
	{
		$('#pic_text').show().focus();
	}
	else
	{
		var val = $('#pic_text').val();
		$('#picinfo_comment').text(val);
		$('#picinfo').show();
		if(is_tuji)
		{
			var url = "./run.php?mid="+gMid+"&a=change_comment&id="+id+"&comment="+val+"&is_tuji=1";//改变图集的描述
		}
		else
		{
			var url = "./run.php?mid="+gMid+"&a=change_comment&id="+id+"&description="+val;//改变图片的描述
		}
		hg_ajax_post(url);
	}
}

/*************************移动图集到特定类别*********************************/
function hg_showMoveTuJi(id)
{
    if($('#move_tuji').css('display')=='none')
	{
       var url= "./run.php?mid="+gMid+"&a=move_tuji&id="+id;
       hg_ajax_post(url);
	   $('#move_tuji').css({'display':'block'});
	   $('#move_tuji').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
	     if($('div[id^="vodplayer_"]').length)
		 {
	    	 hg_close_opration_info();
		 }
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeMoveTuJiTpl();
	}
}

function hg_closeMoveTuJiTpl()
{
	 $('#move_tuji').animate({'right':'120%'},'normal',function(){$('#move_tuji').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 top.livUpload.initPosition();//flash初始位置
}

function moveTujiputTpl(html)
{
	$('#tuji_sort_form').html(html);
}

function hg_OverMoveTuji(json)
{
	var obj = eval('('+json+')');
	$('#tuji_sort_' +obj.id).text(obj.tuji_sort_name);
	hg_closeMoveTuJiTpl();
}

/************************************************************************************/
//审核回调函数
function hg_change_status(obj)
{
	$('#tuji_status_'+obj[0].id).text(obj[0].status);
	hg_close_opration_info();
}

//显示提示框
function display_tip_box()
{
	 $('#over_tip').show();
	 $('#over_tip').animate({'top':'30%'},'normal');
}

function close_tip_box()
{
	$('#over_tip').animate({'top':'-33%'},'normal',function(){
		$('#over_tip').hide();
	});
}

//图片排序
var tuji_order_arr = new Array();
function hg_tuji_pic_order(obj_name)
{
	$('#'+obj_name).sortable({ 
		axis: 'y' ,
		scrollSpeed:100,
		revert: true,
		scroll: true,
		containment: 'parent',
		tolerance: 'pointer',
		start: function(event, ui){
			tuji_order_arr = new Array();//清空
			tuji_order_arr = hg_get_tuji_orderid();
		},
		stop: function(event, ui){
			var new_arr_order = $(this).sortable('toArray');
			for(var i = 0;i < new_arr_order.length;i++)
			{
				$('#'+new_arr_order[i]).find('input[name="order_ids[]"]').val(tuji_order_arr[i]);
			}
		}
	});
}

function hg_get_tuji_orderid()
{
	var old_ids = new Array();
	$('div[id^="pic_info_"]').each(function(){
		old_ids.push($(this).find('input[name="order_ids[]"]').val());
	});
	return old_ids;
}

var Liv_resize = function(elem){
	var $o =$(elem);
	var $img = $o.find("img");
	var _img = new Image();
	var ie = document.all;
	var tem_img = new Image();
	tem_img.src = $img[0].src;
	$img.hide();
	if(tem_img.complete){
		resize();
	}
	tem_img.onload = function(){
			resize();
	}
	function resize(){
		var _width = $o.width();
		var _height = $o.height();
		var img_width =$img.width();
		var img_height =$img.height();
		if(img_width < _width && img_height < _height){
			$img.css({left : _width/2 - img_width/2, top : _height/2 - img_height/2});
			$img.fadeIn();
			return;
		}
		_width/_height < img_width/img_height ? $img.css({width : _width }) : $img.css({height : _height});
		$img.css({left : _width/2 - $img.width()/2, top : _height/2 - $img.height()/2});
		$img.fadeIn();
	}
}

/*******************水印部分操作***********************/
function hg_show_water_pos(obj)
{
	var $o = $(obj).find('input');
	var id = $o.val();
	
	if(!$o.attr('checked'))
	{
		$o.attr('checked','checked');
	}
	$('div[id^="water_img_"]').css('visibility','hidden');
	$('#water_img_'+id).css('visibility','visible');
}

//水印上传
function hg_waterUpload()
{
	var vod_swfu_pic;
	var url = "run.php?mid="+gMid+"&a=water_upload&admin_id="+gAdmin.admin_id+"&admin_pass="+gAdmin.admin_pass;
	vod_swfu_pic = new SWFUpload({
		upload_url: url,
		post_params: {"ad_token": "<?php echo $ad_token; ?>"},
		file_size_limit : "2 GB",
		file_types : "*.jpg;*.jpeg;*.png;*.gif;",
		file_types_description : "预览图片",
		file_upload_limit : "0",
		file_post_name : 'imagefile',
		button_placeholder_id : "waterplace",
		button_width: 94,
		button_height: 24,
		button_text : "<span class='white'>选择图片文件</span>",
		button_text_style : ".white{cursor: pointer;text-align:center;color:#FFFFFF;font-family:sans-serif;font-size:12px;font-weight:bold;}",
		button_image_url : RESOURCE_URL + 'select_upload.png',
		button_text_top_padding: 2,
		button_text_left_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_action:SWFUpload.BUTTON_ACTION.SELECT_FILE,
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",

		custom_settings : {
			upload_target : "divFileProgressContainer"
		},
		
		/*事件句柄*/
		file_queue_error_handler     : water_fileQueueError,
		file_dialog_complete_handler : water_fileDialogComplete,
		upload_progress_handler      : water_uploadProgress,
		upload_error_handler         : water_uploadError,
		upload_success_handler       : water_uploadSuccess,
		
		debug: false/*调试模式*/
	});
}

//直接创建图集
function hg_direct_create_tuji(form_name)
{
	if(!$('input[name="tuji_sort_id"]').val())
	{
		alert('请选择图集类别');
		return;
	}
	
	if($('#title').val() == '' || $('#title').val() == '在这里添加标题')
	{
		alert('请填写图集标题');
		return;
	}
	hg_ajax_submit(form_name);
}

//展开某个图集列表显示该图集下面的图片
function hg_open_tuji(id)
{
	//将图集下的图片请求回来
	if(!$('#page_'+id).length)
	{
		var url = "run.php?mid="+gMid+"&a=open_tuji&tuji_id="+id;
		hg_ajax_post(url);
	}
	else
	{
		check_menu(id);
	}
}

//查看图集列表下的图片的回调
function hg_open_tuji_tpl(html,tuji_id)
{
	$('#content_'+tuji_id).html(html);
	check_menu(tuji_id);
}














