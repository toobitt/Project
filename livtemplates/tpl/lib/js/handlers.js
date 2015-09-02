
/* This is an example of how to cancel all the files queued up.  It's made somewhat generic.  Just pass your SWFUpload
object in to this method and it loops through cancelling the uploads. */

var gUploadTaskId;//存放上传任务的id
/******************视频上传的一些参数**********************************/
var gMoreFileIds          = new Array();//存放多视频上传文件的id
var DataObject = {};//存放每组表单数据的容器(视频)
/******************图片上传的一些参数**********************************/
var gMoreImageFileIds     = new Array();//存放多图片上传文件的id
var DataImageObject = {};//存放每组表单数据的容器(单图片)
var DataImageMoreObject = {};//存放多图上传过程中的共用数据
var DataImageCommon = {};//存放公共数据的对象
var gPrompt = false;//用于提示用户
/******************公共的一些参数**************************************/
var gOldFileId = '';//存放单(视频/图片)上传文件的id
var gFileNum = 0;//记录队列中文件的数量
var allFileSize = 0;//记录总的文件的总字节数
var uploadedSize = 0;//记录已经上传文件的字节数
var gspeed = 0;//存放上传的速度
var oldBytes = 0;//临时存放已上传的字节
var iTime = 0;//记录时间
var fileSelect = false;//用于单视频中重新选择视频，还是添加视频的标志 
var isVideo = false;//指示当前有没有视频文件等待上传
var isImage = false;//指示当前有没有图片文件等待上传
var timeTip = 0;//文字提示信息轮换定时器
var gfileName = '';/*存放当前上传的文件名*/



function livUpload_cancelQueue(instance) 
{
	//document.getElementById(instance.customSettings.cancelButtonId).disabled = true;
	instance.stopUpload();
	var stats;
	
	do {
		stats = instance.getStats();
		instance.cancelUpload();
	} while (stats.files_queued !== 0);
	
}

/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function livUpload_fileDialogStart() 
{
	/* I don't need to do anything here */
	if(top.livUpload.upload_type)//为1时处理图片的逻辑
	{
		var frame = hg_findNodeFrame();
		if(frame.$('#big_content').css('display') == 'block')
		{
			frame.hg_showAllImage();
		}
	}
}


function livUpload_fileQueued(file) 
{
	try
	{
		var frame = hg_findNodeFrame();
		var taskName = '';//任务名称
		var LocalUrlBox = '';//本地文件url显示标志 
		var moreFileQueue = '';//多(视频|图片)摆放队列的对象id
		var everyQueue = '';//每个队列容器的id的前缀
		if(top.livUpload.upload_type)//为1时处理图片的逻辑
		{
			taskName = '新增图片';
			LocalUrlBox = 'image';
			moreFileQueue = 'imagesinfo';
			everyQueue = 'imageup_info_';

			frame.hg_taskCompleted(frame.gAddImageTaskId);/*清除task*/
			frame.gAddImageTaskId = frame.hg_add2Task({'name':taskName});
			
			/*一旦选择了图片文件，就置标志位为true*/
			isImage = true;
			
			gMoreImageFileIds.push(file.id);//把多图片的file_id存起来
			
			/*
			var html  = '<div style="width:100%;height:55px;margin-top:10px;position:relative;z-index:1;" id=pic_'+file.id+'>';
				html += '<div style="height:48px;width:48px;border-radius:6px;border:1px solid red;float:left;background:green;"></div>';
				html += '<textarea rows="2"  id="pic_comment_'+file.id+'" name="pic_comment"  class="info-description info-input-left t_c_b"  style="height:42px;width:460px;float:left;margin-left:15px;"  onfocus="text_value_onfocus(this,\'这里输入图片描述\');" onblur="text_value_onblur(this,\'这里输入图片描述\');">'+file.name+'</textarea>';
				html += '<\/div>';
				
			frame.$('#img_list').append(html);
			frame.$('#button_content').show();//显示提交按钮
			frame.$('#uploadStatus').text('您选择了' + gMoreImageFileIds.length + '个文件');//设置选择文件的个数
			*/

			/***********************************************图片上传一张一张地传**********************************/
			var isup = true;//是否可以上传的标志位
			var tuji_title = frame.$('#title').val();
			var tuji_sort  = frame.$('input[name="tuji_sort_id"]').val();
			if(!tuji_title || tuji_title == '在这里添加标题')
			{
				isup = false;
			}
			
			if(!tuji_sort)
			{
				isup = false;
			}

			if(isup)
			{
				frame.$('#save_tuji').show();
				frame.$('#direct_create').hide();
				top.livUpload.startUploadFile(false,true);
			}
			else
			{
				//从队列中清除该文件
				if(!gPrompt)
				{
					alert('请填写好图集名称和类别再选择图片');
					gPrompt = true;
				}
				top.livUpload.SWF.cancelUpload(file.id);
				isImage = false;
			}
			
			/************************************暂时预留**************************************************************
			if(top.livUpload.uploadMode)//单图片上传
			{
				//fileSelect为true的时候,重新选择,false的时候正常添加
				if(fileSelect)
				{
					if(gOldFileId)
					{
						this.cancelUpload(gOldFileId);
					}
				}
				else
				{
					fileSelect = true;
				}
				
				gOldFileId = file.id;
				frame.$('#'+LocalUrlBox+'_localurl').text(file.name);
				
				//如果是单视频，则需要将按钮上的字设置成'重新选择'
				top.livUpload.SWF.setButtonText("<span class='white'>重新选择</span>");
			}
			else//多图片上传
			{
				gOldFileId = '';//进入多图片清空单图
				gMoreImageFileIds.push(file.id);//把多图片的file_id存起来
				frame.$('#'+moreFileQueue).append('<div id='+everyQueue+file.id+' class="info_upload"><span class="removequeue"  onclick="hg_removeImagesQueue(\''+file.id+'\');"></span><div class="videoinfostyle"><span class="a">'+file.name+'</span><span class="c">等待上传...</span></div></div>');
				//显示开始上传按钮|取消上传按钮
				frame.$('#uploadStatus_content').show();
			    frame.$('#uploadStatus').text('您选择了' + gMoreImageFileIds.length + '个文件');//设置选择文件的个数
			}
			**************************************************************************************************************************************/
	
		}
		else
		{
			taskName = '新增视频';
			LocalUrlBox = 'video';
			moreFileQueue = 'videoinfo';
			everyQueue = 'vup_info_';
			frame.hg_taskCompleted(frame.gAddVideoTaskId);/*清除task*/
			frame.gAddVideoTaskId = frame.hg_add2Task({'name':taskName});
			
			/*一旦选择了视频文件，就置标志位为true*/
			isVideo = true;
			/*在单视频模式中*/
			if(top.livUpload.uploadMode)
			{
				/*fileSelect为true的时候,重新选择,false的时候正常添加*/
				if(fileSelect)
				{
					if(gOldFileId)
					{
						this.cancelUpload(gOldFileId);
					}
				}
				else
				{
					fileSelect = true;
				}

				gOldFileId = file.id;
				frame.$('#'+LocalUrlBox+'_localurl').text(file.name);
				//如果是单视频，则需要将按钮上的字设置成'重新选择'
				top.livUpload.SWF.setButtonText("<span class='white'>重新选择</span>");

			}
			else
			{
				gOldFileId = '';/*进入多视频清空单视频*/
				gMoreFileIds.push(file.id);/*把多视频的file_id存起来*/
				frame.$('#'+moreFileQueue).append('<div id='+everyQueue+file.id+' class="info_upload"><span class="removequeue"  onclick="hg_removeQueue(\''+file.id+'\');"></span><div class="videoinfostyle"><span class="a">'+file.name+'</span><span class="c">等待上传...</span></div></div>');
				/*显示开始上传按钮|取消上传按钮*/
				frame.$('#uploadStatus_content').show();
			    frame.$('#uploadStatus').text('您选择了' + gMoreFileIds.length + '个文件');/*设置选择文件的个数*/
			}
		}		
		var progress = new FileProgress(file,this.customSettings.progressTarget);
		progress.setStatus(hg_format_num(file.size));
		progress.toggleCancel(true, this);
		/*刚添加进队列的文件要隐藏，必须点击上传按钮之后才能显示(视频上传的情况)---图片上传的时候不需要这样*/
		if(!top.livUpload.upload_type)
		{
			top.$('#'+file.id).hide();
		}
		/******************************************************************/
		//top.$('#livUpload_windows').css({'display':'block'});//显示进度条容器,调试的时候用一下
		/******************************************************************/
	
	}catch (ex) {
		this.debug(ex);
	}
	
}

function livUpload_fileQueueError(file, errorCode, message) 
{
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("（上传视频大小超过限制大小！）");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("（无法上传零字节的文件！）");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			progress.setStatus("（无效的文件类型！）");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
			alert("You have selected too many files.  " +  (message > 1 ? "You may only add " +  message + " more files" : "You cannot add any more files."));
			break;
		default:
			if (file !== null) {
				progress.setStatus("Unhandled Error");
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function livUpload_fileDialogComplete(numFilesSelected, numFilesQueued) 
{
	try {
        //top.$('#livUpload_text').text(top.livUpload.displayStatus());/*设置顶级的进度条容器的文件个数*/	    

		/* I want auto start and I can do that here */
		/*this.startUpload();*/
	}catch (ex)  {
        this.debug(ex);
	}
}


function livUpload_uploadStart(file) 
{
	var _this = this;
	try
	{
		/*传配置参数*/
		$('.fast-set-hidden').each( function(){
			var value = $.trim( $(this).val() ),
				name = $(this).attr( 'name' );
			_this.removePostParam( name );
			if( value ){
				_this.addPostParam( name, value );
			}
		} );
		
		var node_frame_wrapper = top.$("#mainwin")[0].contentWindow.$("#livnodewin");
		if( node_frame_wrapper.length ){
			var node_frame = node_frame_wrapper.find('#nodeFrame'),
				href = node_frame.attr('src'),
				map = search2map( href);
			_this.removePostParam( 'vod_sort_id' );
			if( map['_id'] ){
				_this.addPostParam( 'vod_sort_id', map['_id'] );
			}
		}
		
		iTime = new Date();/*获得开始上传的时间*/
		if(!gfileName)
        {
        	gfileName = file.name;
        }
		
		if(livUpload.upload_type)
		{
			//上传之前要进行数据的绑定
			top.$('#livUpload_windows').css({'display':'block'});//上传图片的话每次上传的时候要让进度框显示
			uploadPostImagesData(file.id);
	        if(hg_ObjProNum(DataImageObject) != 0)
	        {
	        	top.$('#livUpload_text_b').text(hg_ObjProNum(DataImageObject) + '个文件等待上传');
	        }
	        else
	        {
	        	clearInterval(timeTip);
	        	top.hg_goToTop();
	        	timeTip = 0;
	        	top.$('#livUpload_text_a').html('正在上传    '+ '<strong>' + gfileName+ '</strong>');
	        }

	        top.$('#livUpload_text_a').html('正在上传    '+ '<strong>' + gfileName+ '</strong>');
	
		}
		else
		{
	        uploadPostData(file.id);/*每一个文件在上传之前都要进行数据绑定 */
	        if(hg_ObjProNum(DataObject) != 0)
	        {
	        	top.$('#livUpload_text_b').text(hg_ObjProNum(DataObject) + '个文件等待上传');
	        }
	        else
	        {
	        	clearInterval(timeTip);
	        	top.hg_goToTop();
	        	timeTip = 0;
	        	top.$('#livUpload_text_a').html('正在上传    '+ '<strong>' + gfileName+ '</strong>');
	        }

	        top.$('#livUpload_text_a').html('正在上传    '+ '<strong>' + gfileName+ '</strong>');
		}
		/* I don't want to do any file validation or anything,  I'll just update the UI and return true to indicate that the upload should start */
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus(hg_format_num(file.size));
		progress.toggleCancel(true, this);
		

	}
	catch (ex) {
	}
	
	
	return true;
}

function livUpload_uploadProgress(file, bytesLoaded, bytesTotal) 
{
	try 
	{
		/*计算上传的速度*/
		var currentTime = new Date();/*获取当前的时间*/
		var offTime = Math.ceil((currentTime - iTime)/1000);/*得出时间差单位秒*/
		gspeed   =  hg_format_num(Math.ceil(bytesLoaded/offTime));
		oldBytes = bytesLoaded;
		top.$('#livUpload_speed').text(gspeed + '/s');
		
		/*用于显示总的文件的上传进度*/
		var allFileRate = 0;
		allFileRate = Math.ceil((bytesLoaded / bytesTotal) * 100);
		allFileRate = allFileRate + '%';
		top.$('#livUpload_windows_b').css('width',allFileRate);
		
	    /*用于显示每个文件的进度*/
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		
		if (percent >= 100)
		{
			progress.setStatus("正在处理中...");	
		}
		else
		{
			progress.setStatus(allFileRate);	
		}
		
		
	} catch (ex) {
		this.debug(ex);
	}
}

function livUpload_uploadSuccess1(file,serverData) 
{
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus('');
		progress.toggleCancel(false);
		if(serverData)
		{
			var obj = eval('('+ serverData + ')');
			var frame = hg_findNodeFrame();
			//图片上传的操作
			if(obj[0] && obj[0].img_flag)
			{
				if(obj[0].pic_src)
				{
					var html  = '<div style="width:100%;height:55px;margin-top:10px;position:relative;z-index:1;" id="pic_info_'+obj[0].pic_id+'">';
						html += '<div style="background:url(\''+RESOURCE_URL+'bg-all.png\') no-repeat -121px -164px;width:16px;height:16px;position:absolute;left:0px;top:0px;z-index:10;cursor:pointer;display:none;" id="select_cover_'+obj[0].pic_id+'"></div>';
						html += '<div style="background:url(\''+RESOURCE_URL+'close_plan.png\') no-repeat;width:16px;height:16px;position:absolute;left:36px;top:-6px;z-index:10;cursor:pointer;display:none;" id="remove_icon_'+obj[0].pic_id+'" onmouseover="hg_show_png(\''+obj[0].pic_id+'\',1);" onmouseout="hg_show_png(\''+obj[0].pic_id+'\',0);" onclick="hg_remove_thisone(\''+obj[0].pic_id+'\');"><\/div>';
						html += '<div style="height:48px;width:48px;border-radius:6px;float:left;background:url('+obj[0].pic_src+') no-repeat;"  onmouseover="hg_show_png(\''+obj[0].pic_id+'\',1);" onmouseout="hg_show_png(\''+obj[0].pic_id+'\',0);"  onclick="hg_switch_cover(\'#select_cover_'+obj[0].pic_id+'\','+obj[0].pic_id+');"  title="单击选中设为封面"></div>';
						html += '<textarea rows="2"  id="pic_comment_'+obj[0].pic_id+'" name="pic_comment[]"  class="info-description info-input-left t_c_b"  style="height:42px;width:450px;float:left;margin-left:15px;"  onfocus="text_value_onfocus(this,\'这里输入图片描述\');" onblur="text_value_onblur(this,\'这里输入图片描述\');">'+obj[0].description+'<\/textarea>';
						html += '<input type="hidden" name="image_ids[]"  value="'+obj[0].pic_id+'" \/>';
						html += '<input type="hidden" name="order_ids[]"  value="'+obj[0].pic_id+'" \/>';
						html += '<\/div>';
					frame.$('#img_list').append(html);
				}

				if(!parseInt(frame.$('input[name="tuji_id"]').val()))
				{
					frame.$('input[name="tuji_id"]').val(obj[0].tuji_id);
				}
				
				/*请求接口在页面中插入一行图集记录*/
				if(!frame.$('li[id^="r_'+obj[0].tuji_id+'"]').length)
				{
					var url = "./run.php?mid="+frame.gMid+"&a=add_tuji_new&id="+obj[0].tuji_id;
					hg_ajax_post(url);
				}
			}
			else
			{
				//视频上传的处理
				/*
				if(!obj.errorno)
				{
					var mid = frame.gMid;
					var publish_type = 0;
					if(obj.columnid_vod)
					{
						publish_type = parseInt(obj.column_type);
						var url = "./run.php?a=dorecommend&mid="+mid+"&hg_id="+obj.id+"&columnid="+obj.columnid_vod;
						hg_ajax_post(url);
					}
					
					if(frame.hg_checkFirstPage())
					{
						var vod_sort_id = parseInt(frame._id);
						var vod_leixing = parseInt(frame._type);
						if((vod_leixing == 1 && vod_sort_id == parseInt(obj.vod_sort_id))|| (vod_sort_id == -1 && vod_leixing == -1))
						{
							hg_single_video(obj,publish_type);
						}
					}
				}
				else
				{
					hg_show_template(obj.errortext);
				}
				*/
				if(obj[0]['return'] == 'success')
				{
					if(frame.hg_checkFirstPage())
					{
						hg_single_video(obj);
					}
				}
				else
				{
					hg_show_template('上传失败');
				}
			}
		}

	} catch (ex) {
		this.debug(ex);
	}
}

function livUpload_uploadComplete(file) 
{
	try
	{
		var frame = hg_findNodeFrame();
		if(livUpload.upload_type)
		{
			if (this.getStats().files_queued === 0) 
			{
				frame.hg_taskCompleted(frame.gAddImageTaskId);/*清除task*/
				hg_taskCompleted(gUploadTaskId);//清除任务
				/*最里层页面的状态显示*/
			    frame.$('#uploadStatus').text('');
			    top.$('#livUpload_windows_b').css('width','0%');
			    top.$('#livUpload_speed').text('');
			    top.$('#livUpload_windows').hide();/*隐藏大进度条*/
			    
			    /*清空记录的数据*/
			    gMoreImageFileIds = new Array();//多图片文件id清空
	            gOldFileId = '';//单视频文件id清空
	            gspeed = 0;
	            oldBytes = 0;
	            DataImageObject = {};//清空单图上传的表单数据组
	            DataImageMoreObject = {};//清空多图上传时保存到数据
	            DataImageCommon = {};//清空公共数据
	            gPrompt = false;//提示标志初始化
	            /*清空定时器*/
	            clearInterval(timeTip);
	            timeTip = 0;
	            top.$('#livUpload_text_b').text('');
				top.$('#livUpload_text_a').text('上传完成');
			}
			else
			{
				top.$('#livUpload_windows_b').css('width','0%');
				top.$('#livUpload_speed').text('');
				
				if(!hg_ObjProNum(DataImageObject))
				{
					clearInterval(timeTip);
					timeTip = 0;
				    top.$('#livUpload_text_b').text('');
					top.$('#livUpload_text_a').text('上传完成');
					top.$('#livUpload_windows').hide();/*隐藏大进度条*/
					//return false;/*停止下面文件的上传*/
				}
			}
		   
		}
		else
		{
			/*  I want the next upload to continue automatically so I'll call startUpload here */
			if (this.getStats().files_queued === 0) 
			{
				//document.getElementById(this.customSettings.cancelButtonId).disabled = true;
				hg_taskCompleted(gUploadTaskId);
				/*最里层页面的状态显示*/
			    frame.$('#uploadStatus').text('');
			    top.$('#livUpload_windows_b').css('width','0%');
			    top.$('#livUpload_speed').text('');
			    top.$('#livUpload_windows').hide();/*隐藏大进度条*/
			    /*多视频清空页面数据*/
	        	frame.$('#display_msort_show').text('选择分类');
	        	frame.$('#vod_sort_ids').val('');
	            
			    /*清空记录的数据*/
	            gMoreFileIds = new Array();//多视频文件id清空
	            gOldFileId = '';//单视频文件id清空
	            gspeed = 0;
	            oldBytes = 0;
	            DataObject = {};
	            /*清空定时器*/
	            clearInterval(timeTip);
	            timeTip = 0;
	            top.$('#livUpload_text_b').text('');
				top.$('#livUpload_text_a').text('上传完成');
	            
			} 
			else 
			{	
				//top.$('#livUpload_text').text(top.livUpload.displayStatus());
				top.$('#livUpload_windows_b').css('width','0%');
				top.$('#livUpload_speed').text('');
				
				if(!hg_ObjProNum(DataObject))
				{
					clearInterval(timeTip);
					timeTip = 0;
				    top.$('#livUpload_text_b').text('');
					top.$('#livUpload_text_a').text('上传完成');
					top.$('#livUpload_windows').hide();/*隐藏大进度条*/
					return false;/*停止下面文件的上传*/
				}
			}
		}
	}
	catch(ex)
	{
		this.debug(ex);
	}
}


function livUpload_uploadError(file, errorCode, message)
{
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("Upload Error: " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
			progress.setStatus("Configuration Error");
			this.debug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("Upload Failed.");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("Server (IO) Error");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("Security Error");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			progress.setStatus("Upload limit exceeded.");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
			progress.setStatus("File not found.");
			this.debug("Error Code: The file was not found, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			progress.setStatus("Failed Validation.  Upload skipped.");
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
			}
			progress.setStatus("Cancelled");
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			
			progress.setStatus("Stopped");
			break;
		default:
			progress.setStatus("Unhandled Error: " + error_code);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}


/***************************************************工具函数*******************************************************************/
/*上传之前对图片数据进行绑定*/
function uploadPostImagesData(fileid)
{
	/*
	var DataObj =  DataImageObject[fileid];
	for(var name in DataObj)	
	{
		top.livUpload.SWF.addFileParam(fileid,name,DataObj[name]);
	}
	*/
	
	for(var name in DataImageCommon)	
	{
		top.livUpload.SWF.addFileParam(fileid,name,DataImageCommon[name]);
	}
	
	/*****************************************************************************************
	//当DataObj为1时表示属于多图片添加过来的,否则单图片
	if(DataObj == 1)
	{
		for(var name in DataImageMoreObject)	
		{
			top.livUpload.SWF.addFileParam(fileid,name,DataImageMoreObject[name]);
		}
    	gfileName = '';
	}
	else
	{
		for(var name in DataObj)	
		{
			top.livUpload.SWF.addFileParam(fileid,name,DataObj[name]);
		}
		gfileName = DataObj['old_name'];
	}
	delete DataImageObject[fileid];//将该条数据从对象中清空
	*******************************************************************************************/
}

/*上传之前做的视频数据绑定*/
function uploadPostData(fileid)
{
	var arr = DataObject[fileid];
	/*当arr为1时表示属于多视频添加过来的,否则单视频*/
	if(arr == 1)
	{
		var frame = hg_findNodeFrame();
    	var sort_value = frame.$('#vod_sort_ids').val();
    	var vcr_type = frame.$('#vcr_type').val();
    	top.livUpload.SWF.addFileParam(fileid,'vod_sort_id',sort_value);
    	if(parseInt(vcr_type) != -1)
    	{
    		top.livUpload.SWF.addFileParam(fileid,'vcr_type',vcr_type);
    	}
    	gfileName = '';
	}
	else
	{
		for(var name in arr)	
		{
			top.livUpload.SWF.addFileParam(fileid,name,arr[name]);
		}
		gfileName = arr['title'];
	}
	
	delete DataObject[fileid];/*将该条数据从对象中清空*/
}

/*文件大小格式化*/
function hg_format_num(fileSize)
{
	var size, unit;
	if((fileSize/1024) < 1024)
	{
		size = fileSize/1024;
		unit = 'KB';
	}
	else if( ((fileSize/1024) > 1024) && ((fileSize/(1024*1024)) < 1024))
	{
		size = fileSize/(1024*1024);
		unit = 'MB';
	}
	else
	{
		size = fileSize/(1024*1024*1024);
		unit = 'GB';
	}
	size = size.toFixed(2);
	return size + unit;
}

/*清空视频表单数据*/
function hg_clear_formdata(flag)
{   
	var frame = hg_findNodeFrame();
	if(frame.$('#video_localurl')){frame.$('#video_localurl').text('');}
	if(frame.$('#title_vod')){frame.$('#title_vod').val('在这里添加标题').addClass("t_c_b");}
	if(frame.$('#comment_vod')){frame.$('#comment_vod').val('这里输入描述').addClass("t_c_b");}
	if(frame.$('#subtitle_vod')){frame.$('#subtitle_vod').val('');}
	if(frame.$('#author_vod')){frame.$('#author_vod').val('');}
	if(frame.$('#keywords_vod')){frame.$('#keywords_vod').val('');}
	
	//此处是区分要不要清除分类
	if(!flag)
	{
		if(frame.$('#display_sort_show')){frame.$('#display_sort_show').text('选择分类');}
		if(frame.$('#vod_sort_id')){frame.$('#vod_sort_id').val('');}
	}
	
	if(frame.$('#display_source_show_vod')){frame.$('#display_source_show_vod').text('自动');}
	if(frame.$('#source_id_vod')){frame.$('#source_id_vod').val('');}
	top.livUpload.SWF.setButtonText("<span class='white'>选择视频文件</span>");
}

/*清空图片表单数据*/
function hg_clear_image_formdata()
{
	var frame = hg_findNodeFrame();
	var formobj = frame.$('#tuji_form').get(0);
	formobj.reset();
	//类别下拉框置为默认
	frame.$('#tuji_sort_id').val('');
	frame.$('#display_tuji_sort_show').text('图集类别');

	/*************************预留数据****************************************************************
	var formObj = frame.$('#single_image_upload_form').get(0);
	if($(formObj.single_title)){$(formObj.single_title).val('在这里添加标题').addClass("t_c_b");}
	if($(formObj.single_comment)){$(formObj.single_comment).val('这里输入描述').addClass("t_c_b");}
	if($(formObj.get_contents)){$(formObj.get_contents).val('');}
	if($(formObj.tuji_sort_name)){$(formObj.tuji_sort_name).val(-1);}
	if($(formObj.display_tuji_sort_show)){$(formObj.display_tuji_sort_show).val('图集类别');}
	frame.$('#tuji_sort_content').hide();
	frame.$('#image_localurl').text('');
	top.livUpload.SWF.setButtonText("<span class='white'>选择图片</span>");
	top.livUpload.getPosition();
	***********************************************************************************************/
}

/*返回js对象里面属性的个数*/
function hg_ObjProNum(obj)
{
	var num = 0;
	for(var i in obj)
	{
		num++;
	}
	return num;
}


/*转换url为map*/
function search2map(search) {
	var map = {};
	search = search + '';
	if (search) {
		search.split('&').forEach(function(item) {
			item = item.split('=');
			map[item[0]] = item[1];
		});
	}
	return map;
}
