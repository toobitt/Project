/**
 * 弹出遮罩层
 */

function hg_showAddSort(id)
{
	if(!id)
	{	
		$('#sort_title').text('新增投稿');
		id = 0;
	}
	else
	{
		$('#sort_title').text('编辑投稿');
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

function hg_show_sortTpl(html)
{
	$('#add_sort_tpl').html(html);
	if(typeof oEdit1 != 'undefined'){
		oEdit1.REPLACE('contribute_text', 'contribute_text_iframe');
	}
}

function hg_closeSortTpl()
{
	 $('#add_sorts').animate({'right':'120%'},'normal',function(){$('#add_sorts').css({'display':'none','right':'0'});hg_resize_nodeFrame();});
}

function hg_update_callback(json)
{   	
	var json_data = $.parseJSON(json);	
	if(json_data['id'])
	{
		$('#contribute_title_'+json_data['id']).text(json_data['title']);
		$('#contribute_sort_'+json_data['id']).text(json_data['sort']);
		$('#contribute_audit_'+json_data['id']).text(json_data['audit_name']);
		$('#stateAudit_' + json_data['id']).removeAttr('onclick');
		$('#stateAudit_' + json_data['id']).attr('onclick','hg_stateAudit('+json_data['id']+','+json_data['audit']+')');
		hg_closeSortTpl();
	}
}

function hg_create_callback(json)
{
	var json_data = $.parseJSON(json);
	var url = "run.php?mid="+gMid+"&a=add_new_list&id="+json_data.id;
	hg_ajax_post(url);
}

function hg_put_newlist(html)
{
	$('#contribute_list').prepend(html);
	hg_closeSortTpl();
}



var  gAuditId = '';
function hg_stateAudit(id,audit,btn)
{
	gAuditId = id;
	$.getJSON("./run.php?mid="+gMid+"&a=check_sort&gmid="+gMid+"&id="+id+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
			function(data) {
			var name, op;
			obj = data[0];
			if (obj)
			{
				if (audit==1)
				{
					name = '审核';
					op = '转发至';
				}
				if (audit==2)
				{
					name = '打回';
					op = '删除';
				}
				if (audit==3)
				{
					name = '审核';
					op = '转发至'
				}
				jConfirm( name+"操作会同步"+op+" "+obj, "审核提醒", function (yes) {
					if (yes) {
						var url = './run.php?mid=' + gMid + '&a=stateAudit&id=' + id + '&audit=' + audit;
						hg_ajax_post(url,'','','stateAudit_back');
					}
				}).position(btn);
				
			}else
			{
				var url = './run.php?mid=' + gMid + '&a=stateAudit&id=' + id + '&audit=' + audit;
				hg_ajax_post(url,'','','stateAudit_back');
			}
		});
}
function stateAudit_back(obj){

	if (obj == 1)
	{
		$('#contribute_audit_' + gAuditId).html('未审核');
		$('#stateAudit_' + gAuditId).html('审核');
	}
	if (obj == 2)
	{
		$('#contribute_audit_' + gAuditId).html('已审核');
		$('#stateAudit_' + gAuditId).html('打回');
	}
	if (obj ==3)
	{
		$('#contribute_audit_' + gAuditId).html('被打回');
		$('#stateAudit_' + gAuditId).html('审核');
	}
	$('#stateAudit_' + gAuditId).removeAttr('onclick');
	$('#stateAudit_' + gAuditId).attr('onclick','hg_stateAudit('+gAuditId+','+obj+',this)');
	
	
}

function hg_audit_callback(json){
	var json_data = $.parseJSON(json);
	for(var a in json_data)
	{
		$('#contribute_audit_'+json_data[a]).html('已审核');
		$('#stateAudit_' + json_data[a]).html('打回');
		$('#stateAudit_' + json_data[a]).removeAttr('onclick');
		$('#stateAudit_' + json_data[a]).attr('onclick','hg_stateAudit('+json_data[a]+',2,this)');
	}

}
function hg_back_callback(json){
	var json_data = $.parseJSON(json);
	for(var a in json_data)
	{
		$('#contribute_audit_'+json_data[a]).html('被打回');
		$('#stateAudit_' + json_data[a]).html('审核');
		$('#stateAudit_' + json_data[a]).removeAttr('onclick');
		$('#stateAudit_' + json_data[a]).attr('onclick','hg_stateAudit('+json_data[a]+',3,this)');
	}	
}
function hg_form_info(id){
	if ($('li[id^="r_"]').length){
		hg_close_opration_info();
	}
	hg_showAddSort(id);
	
}

function hg_show_affix()
{
	if($("#affix_content").attr('display') == 'block')
	{
		
		$("#affix_content").attr('display','none').slideUp(200,function(){hg_resize_nodeFrame();});	
		setTimeout('hg_tmp_affix()',300);
		$("#affix_title").attr('class','affix_title_default');
	
	}
	else
	{
	$("#affix_content").attr('display','block').slideDown(200,function(){hg_resize_nodeFrame();});
	$("#affix_title").attr('class','affix_title_show');
	}
} 














function hg_slide_up(obj,id)
{
	if($(obj).children().hasClass('b2'))
	{
		$(obj).children().removeClass('b2');
	}
	else
	{
		$(obj).children().addClass('b2');
	}
	
	$("#"+id).slideToggle('normal', function(){hg_resize_nodeFrame();});
	
}


function hg_swf_image()
{
	var con_swfu_pic;
	var url = "./run.php?mid=" + gMid + "&a=upload&admin_id="+gAdmin.admin_id+"&admin_pass="+gAdmin.admin_pass;
	con_swfu_pic = new SWFUpload({
		upload_url: url,
		file_size_limit : "50 MB",
		file_types : "*.jpg;*.JPG;*.jpeg;*.JPEG;*.png;*.PNG;*.gif;*.GIF;*.doc;*.DOC;*.xls;*.XLS;*.txt;*.TXT;*.flv;*.FLV;*.mp4;*.MP4;*.f4v;*.F4V;*.zip;*.ZIP;*.rar;*.RAR;",
		file_types_description : "选择附件",
		file_upload_limit : "0",

		file_queue_error_handler     : con_fileQueueError,
		file_dialog_complete_handler : con_fileDialogComplete,
		upload_start_handler 		 : con_uploadStart,
		upload_progress_handler      : con_uploadProgress,
		upload_error_handler         : con_uploadError,
		upload_success_handler       : con_uploadSuccess,
		upload_complete_handler      : con_uploadComplete,

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

function con_fileQueueError(file, errorCode, message) {
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


function con_fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function con_uploadStart(file) {
	try {
		this.setPostParams({'content_id':$("#material_history").val(),'cid':gMid,'access_token':gToken});
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("正在上传...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	return true;
}

function con_uploadComplete(file) {
	try {
		//多个文件上传排队自动上传
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setStatus("");
			progress.setComplete();
			progress.toggleCancel(false);
		}
	} catch (ex) {
		this.debug(ex);
	}
}


function con_uploadProgress(file, bytesLoaded) {
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

function con_uploadSuccess(file, serverData) {
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
			var pic= eval(obj[0].pic);
			var pic_url = pic.host+pic.dir+'100x100/'+pic.file_path+pic.file_name;
            var ori_url = pic.host+pic.dir+pic.file_path+pic.file_name;
			other = ' onmouseover="hg_indexpic_show(' + obj[0].id + ',1);return false;" onmouseout="hg_indexpic_show(' + obj[0].id + ',0);return false;"';
			var article_id = $("#id").val() ? $("#id").val() : 0;
			var str = '<div id="affix_' + obj[0].id + '" class="imglist" ' + other + '>'+
						'<div id="del_' + obj[0].id + '" class="del" onclick="hg_material_del(' + obj[0].id + ');">x</div>'+
						'<input type="hidden" name="material_id[]" value="' + obj[0].id + '" />'+
				        '<input type="hidden" name="material_name[]" value="'+obj[0].filename+'"/>'+
						'<img  src="' + pic_url + '" alt="' + obj[0].name + '"   onclick="insert_into(' + obj[0].id + ',\'' + ori_url + '\',\'pic\')" />'+
						'<div id="over_' + obj[0].id + '" class="over"  onclick="hg_material_indexpic(' + obj[0].cid + ',' + obj[0].id + ');">设为索引</div></div>';;
			$("#image_box").prepend(str);
			
		}
	} catch (ex) {
		this.debug(ex);
	}
}


function con_uploadError(file, errorCode, message) {
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






function hg_indexpic_show(id,type)
{
	if(type)
	{
		$("#del_" + id).stop().animate({top:0},800);
		
			$("#over_" + id).stop().animate({bottom:0},800);
		
	}
	else
	{
		
		$("#del_" + id).stop().animate({top:-20},800);
	
			$("#over_" + id).stop().animate({bottom:-20},800);
	}
}
var arr = new Array();
function hg_material_del(id)
{
	
	//var i;
	//var length;
	if(id)
	{	
		$("#affix_" + id).remove();
		var url = './run.php?'+'mid=' + gMid + '&a=del_material&pic=' + id;
		hg_ajax_post(url);
		//length = arr.push(id);
	}
	
	
}

function hg_save_pic()
{
	var pic_arr =arr.join(',');
	if(pic_arr.length)
	{
		var url = './run.php?'+'mid=' + gMid + '&a=del_material&pic=' + pic_arr;
		hg_ajax_post(url);
	}
}
function hg_material_indexpic(content_id,id)
{
	var url = './run.php?'+'mid=' + gMid + '&a=update_indexpic&content_id=' + content_id+'&id='+id;
	hg_ajax_post(url);

}
function indexpic_back(json)
{
	
	var obj = eval('('+json+')');
	var url = obj.host+obj.dir+'160x120/'+obj.file_path+obj.file_name;
	$("#img_"+obj.cid).attr('src',url);
	
}


var gStateId = '';
function hg_statePublish(id)
{
	gStateId = id;
	var url = './run.php?mid=' + gMid + '&a=recommend&id=' + id;
	hg_ajax_post(url);
}

var ii = 0;
var tt;
function show_video()
{
	$("#hoge_edit_play").clearQueue();
	
	if(ii==0)
	{
		ii=1;
		clearTimeout(tt);
		$("#hoge_edit_play").css("display","block");
		$("#hoge_edit_play").animate({top:"50px"});
		
	}
	else{
		ii=0;
		$("#hoge_edit_play").animate({top:"-378px"});
		tt = setTimeout(video_close,600);
	}
}

function video_close()
{
	$("#hoge_edit_play").css("display","none");
} 

var hg_pub_left = 0;
function  hg_conPub(id)
{
	if(gDragMode)
    {
	   return;
    }
	hg_pub_left = $("#conimg_lm_"+id).offset().left-$("#conimg_lm_"+id).width()/2-12;
	$("#conPub_"+id).css({'left':hg_pub_left + 'px'}).show();
	
}

function hg_back_conPub(id)
{ 
	if(gDragMode)
    {
	   return;
    }
	$("#conPub_"+id).css("display","none");
}
function  hg_conPhone(id)
{
	if(gDragMode)
    {
	   return;
    }
	hg_pub_left = $("#conimg_sj_"+id).offset().left-$("#conimg_sj_"+id).width()/2-18;
	$("#conPhone_"+id).css({'left':hg_pub_left + 'px'}).show();
	
}

function hg_back_conPhone(id)
{ 
	if(gDragMode)
    {
	   return;
    }	
	$("#conPhone_"+id).css("display","none");
}

