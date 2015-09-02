function hg_showAddConfig(flag,config_id)
{
	if(gDragMode)
    {
	   return  false;
    }
	
    if($('#add_config').css('display')=='none')
	{
       hg_checkConfigOpType(flag,config_id);/*进来之前要判断操作的类型(新增集合/编辑集合)*/
	   $('#add_config').css({'display':'block'});
	   $('#add_config').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		 hg_resize_nodeFrame();
	   });
	}
	else
	{
		hg_closeConfigTpl();
	}
}

/*进来之前要判断操作的类型(新增集合/编辑集合)*/
function hg_checkConfigOpType(flag,config_id)
{
	/*为true的时候是编辑*/
	if(flag)
	{
		$('#config_title').text('编辑配置');
		$('#action').val('update_config');
		$('#config_id').val(config_id);
		$('#add_config_button').hide();
		$('#edit_config_button').show();
		
	    var url = './run.php?mid='+gMid+'&a=edit_config&id='+config_id;
	    hg_ajax_post(url,'','','hg_change_configtext_value');
		
	}
	else
	{
		$('#config_title').text('新增配置');
		$('#action').val('create');
		$('#add_config_button').show();
		$('#edit_config_button').hide();
	}
}

/*如果是编辑载入原始数据*/
function hg_change_configtext_value(obj)
{
	hg_clearConfigForm();
	$('#config_name').val(obj[0].name);
	$('#unique_id').val(obj[0].unique_id);
	$('#output_format').val(obj[0].output_format);
	$('#codec_format').val(obj[0].codec_format);
	$('#codec_profile').val(obj[0].codec_profile);
	$('#width').val(obj[0].width);
	$('#height').val(obj[0].height);
	$('#video_bitrate').val(obj[0].video_bitrate);
	$('#audio_bitrate').val(obj[0].audio_bitrate);
	$('#frame_rate').val(obj[0].frame_rate);
	$('#gop').val(obj[0].gop);
	$('#vpre').val(obj[0].vpre);
	$('#water_mark').val(obj[0].water_mark);
	$('#pic_face').attr('src',obj[0].water_mark);
	if(parseInt(obj[0].is_open_water))
	{
		$('#is_open_water_1').attr('checked','checked');
		$('#is_open_water_0').attr('checked',false);
	}
	else
	{
		$('#is_open_water_1').attr('checked',false);
		$('#is_open_water_0').attr('checked','checked');
	}
	
	if(parseInt(obj[0].is_use))
	{
		$('#is_use_1').attr('checked','checked');
		$('#is_use_0').attr('checked',false);
	}
	else
	{
		$('#is_use_1').attr('checked',false);
		$('#is_use_0').attr('checked','checked');
	}
	if(parseInt(obj[0].is_default))
	{
		$('#is_default_1').attr('checked','checked');
		$('#is_default_0').attr('checked',false);
	}
	else
	{
		$('#is_default_1').attr('checked',false);
		$('#is_default_0').attr('checked','checked');
	}
	
	$water_pic_position = obj[0].water_pic_position;
	if(!$water_pic_position)
	{
		$water_pic_position = '0,0';
	}
	$('#_water_position').children().each(function(){
		if($(this).attr('_val') == $water_pic_position)
		{
			$(this).css('background','red');
		}
		else
		{
			$(this).css('background','green');
		}
	});

	rebuild_water_area(obj[0].width, obj[0].height, obj[0].water_mark);
}
function rebuild_water_area(w, h, water_src)
{
	var area = $("#water-position-area"),
		water = $('#water-org'),
		water_text = $("#water_text"),
		water_input = $('#water_offset'),
		max_width = 400;
	
	var img = new Image;
	img.onload = function () {
		var iw = img.width,
			ih = img.height,
			suo = w > max_width ? max_width / w : 1;
		
		w *= suo;
		h *= suo;
		iw *= suo;
		ih *= suo;
		area.width(w).height(h);
		water.data('suo', suo).css({
			width: iw,
			height: ih,
			left: 0,
			top: 0
		}).html( ['<img src="', water_src, '" style="width:', iw, 'px;height:', ih, 'px;" />'].join('') );
		water_text.text('距左上角：(0px, 0px)');
		if ( !water.data('bind') ) {
			water.data('bind', true);
			water.draggable({
				containment: "parent",
				drag: function () {
					var l = Math.round(water.css('left').replace('px', '') / water.data('suo')),
						t = Math.round(water.css('top').replace('px', '') / water.data('suo'));
					water_text.text(
					 ['距左上角：(', l, 'px, ', t, 'px)'].join('')
					);
					water_input.val([l, t].join());
				}
			});
		}
	};
	img.src = water_src;
}
/*关闭新增配置面板*/
function hg_closeConfigTpl()
{
	 $('#add_config').animate({'right':'120%'},'normal',function(){$('#add_config').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
	 hg_clearConfigForm();
}

function hg_overCreateConfig(obj)
{
	   var obj = eval('('+obj+')');
	   var url = "./run.php?mid="+gMid+"&a=add_new_configlist&id="+obj.id;
	   hg_ajax_post(url);
}

/*插入新增加的列表*/
function hg_put_newConfiglist(html)
{
	$('#vod_config_form_list').prepend(html);
	hg_closeConfigTpl();
}

/*清除表单数据*/
function hg_clearConfigForm()
{
	$("#config_form").get(0).reset();
}

/*更新完之后的回调函数*/
function hg_overEditConfig(obj)
{
	var obj = eval('('+obj+')');
	for(var name in obj)
	{
		if(name == 'water_mark')
		{
			$('#'+name+'_'+obj.id).attr('src',obj[name]);
			continue;
		}
		$('#'+name+'_'+obj.id).text(obj[name]);
	}
	hg_closeConfigTpl();
}

function hg_delete_config(id)
{
	if(gDragMode)
    {
	   return  false;
    }
	
	var url = './run.php?mid='+gMid+'&a=delete&id='+id;
	return hg_ajax_post(url,'删除',1);
}
//选取水印呈现在视频上的位置
function select_water_position(obj)
{
	$(obj).parent('div').children().css('background','green');
	$(obj).css('background','red');
	var position = $(obj).attr('_val');
	$('#water_pic_position').val(position);
}

function upload_water_image()
{
	var water_swf;
	var url = "run.php?mid="+gMid+"&a=upload_water_img&admin_id="+gAdmin.admin_id+"&admin_pass="+gAdmin.admin_pass;
	water_swf = new SWFUpload({
		upload_url: url,
		post_params: {"access_token":gToken},
		file_size_limit : "20 MB",
		file_types : "*.jpg;*.jpeg;*.png;*.gif;",
		file_types_description : "预览图片",
		file_upload_limit : "0",

		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,

		button_image_url : RESOURCE_URL + 'add_from_cpu.png',
		button_placeholder_id : "water_mark",
		button_width: 76,
		button_height: 57,
		button_text : '',
		button_text_style : '.button {font-family: Helvetica, Arial, sans-serif; font-size: 12pt;}',
		button_text_top_padding: 0,
		button_text_left_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_action:SWFUpload.BUTTON_ACTION.SELECT_FILE,
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",
		custom_settings : {
			upload_target : "divFileProgressContainer"
		},
		debug: false
	});
}