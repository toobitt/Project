function fileQueueError(file, errorCode, message) {
	try {
		var imageName = "error.gif";
		var errorName = "";
		if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
			errorName = "You have attempted to queue too many files.";
		}

		if (errorName !== "") {
			alert(errorName);
			return;
		}

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			imageName = "zerobyte.gif";
			break;
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			imageName = "toobig.gif";
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
		default:
			alert(message);
			break;
		}

		addImage("images/" + imageName);

	} catch (ex) {
		this.debug(ex);
	}

}


function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadStart(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("正在上传...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	return true;
}

function uploadProgress(file, bytesLoaded) {

	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		var progress = new FileProgress(file,  this.customSettings.upload_target);
		progress.setProgress(percent);
		if (percent === 100) {
			progress.setStatus("正在生成预览图...");
			progress.toggleCancel(false, this);
		} else {
			progress.setStatus("上传中，请等待...");
			progress.toggleCancel(true, this);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("上传成功");
		if (serverData.replace(/\s+/g,""))
		{
			var obj = eval('('+serverData+')');
			var space = "";
			if($("#material_history").val())
			{
				space = ",";
			}
			$("#material_history").val( $("#material_history").val() + space + obj[0].id );
			
			var other = '';
			other = ' onmouseover="hg_indexpic_show(' + obj[0].id + ',\'' + obj[0].mark + '\',1);return false;" onmouseout="hg_indexpic_show(' + obj[0].id + ',\'' + obj[0].mark + '\',0);return false;"';
			var article_id = $("#id").val() ? $("#id").val() : 0;
			var str = '<div id="affix_' + obj[0].id + '" class="imglist" ' + other + '>'+
						'<div id="del_' + obj[0].id + '" class="del" onclick="hg_material_del(' + obj[0].id + ');">x</div>'+
						'<input type="hidden" name="material_id[]" value="' + obj[0].id + '" />'+
				        '<input type="hidden" name="material_name[]" value="'+obj[0].filename+'"/>'+
						'<img onclick="insert_into(' + obj[0].id + ',\'' + obj[0].ori_url + '\',\'' + obj[0].mark + '\')" src="' + obj[0].url + '" alt="' + obj[0].name + '" />'+
				'<div id="over_' + obj[0].id + '" class="over" onclick="hg_material_indexpic(' + article_id + ',' + obj[0].id + ');">设为索引</div></div>';
			$("#image_box").prepend(str);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	try {
		//多个文件上传排队自动上传
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setComplete();
			progress.toggleCancel(false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	var imageName =  "error.gif";
	var progress;
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Cancelled");
				progress.toggleCancel(false);
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setCancelled();
				progress.setStatus("Stopped");
				progress.toggleCancel(true);
			}
			catch (ex2) {
				this.debug(ex2);
			}
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			imageName = "uploadlimit.gif";
			break;
		default:
			alert(message);
			break;
		}

		addImage("images/" + imageName);

	} catch (ex3) {
		this.debug(ex3);
	}

}

function hg_addImage(src) {
	
}

function hg_swf_image()
{	
	var vod_swfu_pic;
	var url = "./run.php?mid=" + gMid + "&a=upload&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass;
	//post_params: {},
	vod_swfu_pic = new SWFUpload({
		upload_url: url,
	    post_params: {"access_token": gToken},
		file_size_limit : "50 MB",
		file_types : "*.jpg;*.jpeg;*.png;*.gif;*.doc;*.xls;*.txt;*.flv;*.mp4;*.f4v;*.zip;*.rar;",
		file_types_description : "选择附件",
		file_upload_limit : "0",
		file_queue_limit: 1,

		file_queue_error_handler     : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler      : uploadProgress,
		upload_error_handler         : uploadError,
		upload_success_handler       : uploadSuccess,

		button_image_url : RESOURCE_URL+"news_from_cpu.png",
		button_placeholder_id : 'image_material',
		button_width: 100,
		button_height: 75,
		button_text : '',
		button_text_style : '.button {font-family: Helvetica, Arial, sans-serif; font-size: 12pt;}',
		button_text_top_padding: 0,
		button_text_left_padding: 0,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_action:SWFUpload.BUTTON_ACTION.SELECT_FILES,
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",
		custom_settings : {
			upload_target : "divFileProgressContainer",
			progressTarget : "image_box"
		},
		debug: false
	});
}


function hg_show_affix()
{
	if($("#affix_content").attr('display') == 'block')
	{
		$("#affix_content").attr('display','none').slideUp(200,function(){hg_resize_nodeFrame();});
		setTimeout('hg_tmp_affix()',300);
	}
	else
	{
	
		$("#affix_content").attr('display','block').slideDown(200,function(){hg_resize_nodeFrame();});
		$("#affix_title").attr('class','affix_title_show');
	}
}

function hg_water_settings()
{
	if($("#water_content").attr('display') == 'block')
	{
		$("#water_content").attr('display','none').slideUp(200,function(){hg_resize_nodeFrame();});
		setTimeout('hg_tmp_water()',300);
	}
	else
	{
		$("#water_content").attr('display','block').slideDown(200,function(){hg_resize_nodeFrame();});
		$("#water_title").attr('class','affix_title_show');
	}
}

function hg_tmp_affix()
{
	$("#affix_title").attr('class','affix_title_default');
}
function hg_tmp_water()
{
	$("#water_title").attr('class','affix_title_default');
}

function hg_indexpic_show(id,mark,type)
{
	if(type)
	{
		$("#del_" + id).stop().animate({top:0},800);
		if(mark == 'img')
		{
			$("#over_" + id).stop().animate({bottom:0},800);
		}
	}
	else
	{
		$("#del_" + id).stop().animate({top:-20},800);
		if(mark == 'img')
		{
			$("#over_" + id).stop().animate({bottom:-20},800);
		}
	}
}

function hg_material_del(id)
{
	if(id)
	{
		$("#affix_" + id).remove();
		if($("#indexpic").val() && id == $("#indexpic").val())
		{
			$("#indexpic_url").attr('src','');
		}
	}
//	var url = './run.php?'+'mid=' + gMid + '&a=del_material&id=' + id;
//	hg_request_to(url);
}

function hg_material_del_call(data)
{
	var obj = eval('(' + data + ')');
	if(obj.id)
	{
		$("#affix_" + obj.id).remove();
		if($("#indexpic").val() && obj.id == $("#indexpic").val())
		{
			$("#indexpic_url").attr('src','');
		}
	}
}

function hg_material_indexpic(id,m_id)
{
	var url = './run.php?'+'mid=' + gMid + '&a=update_indexpic&id=' + id + '&m_id=' + m_id;
	hg_request_to(url);	
}

function hg_material_indexpic_call(data)
{
	var obj = eval('(' + data + ')');
	if(obj.indexpic)
	{
		$("#indexpic").val(obj.indexpic);
		$("#indexpic_url").attr('src',obj.indexpic_url);
		if($("#indexpic_url").attr('display')=='none')
		{
			$("#indexpic_url").attr('display','block');
		}
	}
}

function hg_article_history(id)
{
	var url = './run.php?mid=' + gMid + '&a=detail_history&id=' + id;
	hg_request_to(url);
}

function hg_article_history_call(data)
{
	var json=eval('('+data+')');

	$("#history_id").val(json.history_id);
	$("#title").val(json.title);
	$("#brief").val(json.brief);
	
	var oEditor = oUtil.oEditor;
	var str = oEditor.document.body.innerHTML;
	oEditor.document.body.innerHTML = decodeURIComponent(json.allpages);
	if(json.indexpic)
	{
		$("#indexpic").val(json.indexpic);
		$("#indexpic_url").attr('src',json.indexpic_url);
		$("#indexpic").css({"display":"block"});
	}
	else
	{
		$("#indexpic").val('');
		$("#indexpic_url").attr({src:'',original:''});
		$("#indexpic").css({"display":"none"});
	}

	if(json.outlink && json.outlink !='请填写超链接！')
	{
		$("#outlink_check").attr('checked',true);
		$("#outlink_1").css("display","none");
		$("#outlink_2").css("display","none");
		$("#outlink_3").css("display","none");
	}
	else
	{
		$("#outlink_check").attr('checked',false);
		$("#outlink_1").css("display","block");
		$("#outlink_2").css("display","block");
		$("#outlink_3").css("display","block");
	}
	$("#outlink").val(json.outlink);
//	$("#material_history").val('');
	if(json.iswater==1)
	{
		$("#water").attr('checked',true);
		$("#water_content").css("display","block");
		$("#water_title").attr('class','affix_title_show');
	}
	else
	{
		$("#water").attr('checked',false);
		$("#water_content").css("display","none");
		$("#water_title").attr('class','affix_title_default');
	}

	if(json.is_system)
	{
		$("#is_system").val(1);
	}
	else
	{
		$("#is_system").val(0);
	}

	if(json.water_pic)
	{
		$("#water_name").val(json.water_pic);
		$("#water_url").attr('src',json.water_url);
	}
	else
	{
		$("#water_name").val('');
		$("#water_url").attr({src:'',original:''});
	}

	if(json.article_water)
	{
		var article_water = '<div class="imglist awb" style="display:block;"><img src="' + json.article_water + '" alt="" onclick="hg_select_water(\'' + json.article_water + '\',\'' + json.water_pic + '\',0);"/></div><div id="image_water"></div>';
	}
	else
	{
		var article_water = '<div class="imglist awb"></div><div id="image_water"></div>';
	}
	$("#article_water_box").html(article_water);

	$("#subtitle").val(json.subtitle);
	$("#author").val(json.author);
	$("#source").val(json.source);
	$("#keywords").val(json.keywords);
	$("#create_time").val(json.create_time);
	if(json.istop==1)
	{
		$("#istop").attr('checked',true);
	}
	else
	{
		$("#istop").attr('checked',false);
	}


	if(json.tcolor)
	{
		$("#iscolor").attr('checked',true);
	    $("#pickcolor").css({"background-color":json.tcolor,"display":"inline-block"}).val(json.tcolor);
	}
	else
	{
		$("#iscolor").attr('checked',false);
		$("#pickcolor").css({"background-color":"","display":"none"}).val('');
	}
     
	if(json.isbold==1)
	{
		$("#isbold").attr('checked',true);
	}
	else
	{
		$("#isbold").attr('checked',false);
	}

	if(json.isitalic==1)
	{
		$("#isitalic").attr('checked',true);
	}
	else
	{
		$("#isitalic").attr('checked',false);
	}

	var str='';
	for(var key in json.material)
	{
		var article_id = json.id;
		var id = json.material[key].id;
		var material_id = json.material[key].material_id;
		var filename = json.material[key].filename;
		var material_url = json.material[key].small_url;
		var ori_url=json.material[key].ori_url;
		var mark=json.material[key].mark;
		var other='';
		other = ' onmouseover="hg_indexpic_show(' + id + ',\'' + mark + '\',1);return false;" onmouseout="hg_indexpic_show(' + id + ',\'' + mark + '\',0);return false;"';
	    str += '<div id="affix_' + id + '" class="imglist" ' + other + '>'+
					'<div id="del_' + id + '" class="del" onclick="hg_material_del(' + id + ');">x</div>'+
					'<input type="hidden" name="material_id[]" value="' + material_id + '" />'+
				    '<input type="hidden" name="material_name[]" value="'+filename+'"/>'+
					'<img onclick="insert_into(' + id + ',\'' + ori_url + '\',\'' + mark + '\')" src="' + material_url + '" alt="' + name + '" />'+
				'<div id="over_' + id + '" class="over" onclick="hg_material_indexpic(' + article_id + ',' + material_id + ');">设为索引</div></div>';
	}
	str +='<div id="image_material"></div><div class="clear" style="margin-bottom:10px;"></div>';
	$("#image_box").html(str);/**/
	hg_swf_image();
}

function hg_load_editor()
{
	oEdit1.width = 680;
	oEdit1.height = 400;
	oEdit1.features = ["FullScreen","Flash","Media","Image","|",
	"Undo","Redo","|","Hyperlink","Bookmark","|",
	"JustifyLeft","JustifyCenter","JustifyRight","JustifyFull","|",
	"FontName","FontSize","|",
	"Bold","Italic","Underline","Strikethrough","|",
	"ForeColor","BackColor","ClearAll","XHTMLSource"];
	oEdit1.mode="HTMLBody";
}

function hg_outlink(e)
{
	if($(e).attr('checked'))
	{
		$("#outlink").fadeIn(200,function(){hg_resize_nodeFrame();});
		$("#outlink_1").attr('display','none').slideUp(100,function(){hg_resize_nodeFrame();});
		$("#outlink_2").attr('display','none').slideUp(100,function(){hg_resize_nodeFrame();});
		$("#outlink_3").attr('display','none').slideUp(100,function(){hg_resize_nodeFrame();},function(){hg_resize_nodeFrame();});
		//$("#outlink").fadeIn(200,function(){hg_resize_nodeFrame();});
		$("#outlink_title").fadeIn(200,function(){hg_resize_nodeFrame();});
	}
	else
	{
		$("#outlink_1").attr('display','none').slideDown(100,function(){hg_resize_nodeFrame();});
		$("#outlink_2").attr('display','none').slideDown(100,function(){hg_resize_nodeFrame();});
		$("#outlink_3").attr('display','none').slideDown(100,function(){hg_resize_nodeFrame();});
		$("#outlink").val('');
		$("#outlink").fadeOut(200,function(){hg_resize_nodeFrame();});
		$("#outlink_title").fadeOut(200,function(){hg_resize_nodeFrame();});
	}
}

function hg_tcolor(e)
{
	if($(e).attr('checked'))
	{
		$("#pickcolor").attr('display','none').fadeIn(600);
	}
	else
	{
		$("#pickcolor").attr('display','none').fadeOut(600);
	}
}

function hg_news_submit( callback )
{
	if($("#title").val()=='')
	{
		alert('请填写标题');
		$("#title").focus();
		return false;
	}
	if($("#content").val()=='')
	{
		alert('内容不能为空');
		$("#content").focus();
		return false;
	}
	if($("#outlink_check").attr('checked'))
	{
		if($("#outlink").val()=='' || $("#outlink").val()=='请填写超链接！')
		{
			alert('请填写外链地址！');
			$("#outlink").focus();
			return false;
		}
	}
	if($("#water_tup").attr('checked'))
	{
		if(!$("#water_name").val())
		{
			alert('请选中水印图片');
			return false;
		}
	}
	if($("#water_wenzi").attr('checked'))
	{
		if(!$("#water_text").val())
		{
			alert('请填写水印文字');
			return false;
		}
	}
	$.isFunction( callback ) && callback();
	
	return true;
}

function hg_show_water()
{
	var url = "./run.php?mid="+gMid+"&a=water_request";
	hg_ajax_post(url);
}
function hg_show_water_call(html)
{
	if(html)
	{
		if($("#water_c").attr('display') == 'block')
		{
			$("#water_c").attr('display','none').slideUp(100,function(){hg_resize_nodeFrame();});
		}
		else
		{	
			$('#water_box').html(html);
			$("#water_c").attr('display','block').slideDown(100);
			$("#water_a_c").attr('display','none').slideUp(100,function(){hg_resize_nodeFrame();});
		}
	}
	else
	{
		alert('系统暂无水印！');
	}
}
function hg_article_water()
{
	if($("#water_a_c").attr('display') == 'block')
	{
		$("#water_a_c").attr('display','none').slideUp(100,function(){hg_resize_nodeFrame();});
	}
	else
	{	
		$("#water_a_c").attr('display','block').slideDown(100);
		$("#water_c").attr('display','none').slideUp(100,function(){hg_resize_nodeFrame();});
		hg_swf_water();
	}
}
function hg_select_water(url,filename,type)
{
	$("#water_c").hide(500);
	
    var str='<div class="imglt">';
	if(type)
	{
		str += '<input type="hidden" id="is_system" name="is_system" value="1"/>';
	}
	str += '<input type="hidden" id="water_name" name="water_name" value="' + filename + '"/><img src="' + url +'" alt="' + filename + '"/></div>';
	$("#water").attr('display','block').slideDown(200,function(){hg_resize_nodeFrame();});
	$("#image_box2").html(str);
}

function hg_page_title(e)
{
	if($(e).attr('checked'))
	{
		$(".page_title_input").fadeIn(100);
	}
	else
	{
		$(".page_title_input").fadeOut(100);
	}
}

function hg_water_comp()
{
	if($("#water_tup").attr('checked'))
	{
		if(!$("#water_name").val())
		{
			alert('请选中水印图片');
			return false;
		}
	}
	if($("#water_wenzi").attr('checked'))
	{
		if(!$("#water_text").val())
		{
			alert('请填写水印文字');
			return false;
		}
	}
	$("#water_content").attr('display','none').slideUp(200,function(){hg_resize_nodeFrame();});
	setTimeout('hg_tmp_water()',300);
}




