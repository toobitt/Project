
var vod_video_id = "" ;

function hg_show_localurl(obj)
{
	var localurl = $(obj).val();
	$("#video_localurl").text(localurl);
	
	 $("#upload_video").filestyle({
			
	        image:  RESOURCE_URL+"reselect.png",
	
	        imageheight : 24,
	
	        imagewidth : 89,
	
	        display : "none"
	
	 });

}


function hg_panduan_video()
{
	 //获取欲上传的文件路径
	var filepath = $("#video_localurl").text();
	var video_type = $("#video_type").val();
	//如果文件上传控件里面有值
	if(filepath)
	{
        if($('#title_vod').val() == '在这里添加标题')
        {
        	$('#title_vod').val('');
        }
		
		if($('#title_vod').val())
		{
			//为了避免转义反斜杠出问题，这里将对其进行转换
			var re = /(\\+)/g; 
			var filename=filepath.replace(re,"#");
			//对路径字符串进行剪切截取
			var one=filename.split("#");
			//获取数组中最后一个，即文件名
			var two=one[one.length-1];
			//再对文件名进行截取，以取得后缀名
			var three=two.split(".");
			 //获取截取的最后一个字符串，即为后缀名
			var last=three[three.length-1];
			//将其转换为小写
			last = last.toLowerCase();
			//添加需要判断的后缀名类型
			var tp = video_type;
			//返回符合条件的后缀名在字符串中的位置
			var rs=tp.indexOf(last);
			//如果返回的结果大于或等于0，说明包含允许上传的文件类型
			if(rs>=0)
			{
			   return true;
			}
			else
			{
			    alert("您选择的上传文件不是有效的视频文件！");
			    return false;
			}
		}
		else
		{
			alert("您没有填写标题！");
		    return false;
		}
		
	}
	else
	{
		alert("您没有选择的上传的视频文件！");
		return false;
	}
	
}

function hg_close_win()
{
   $("#livwindialogClose").click();	
}

function  single_video_submit()
{
	if(hg_panduan_video())
	{
		return hg_ajax_submit('single_video_form','','','hg_single_video');
	}
	else
	{
		return false;
	}
}

function hg_switch_upload()
{
	 if(hg_hasFormData())
	 {
		 if(!confirm("切换到批量上传将丢失附加信息，确定要切换吗？"))
		 {
			return;
		 }
	 }
	 
	 /*隐藏单视频窗口*/
	 var id=window.parent.document.getElementById('single_upload_vod');
	 $(id).attr('class','button_4');
	 $('#single_upload').animate({'right':'120%'},'fast',function(){$('#single_upload').css({'display':'none','right':'0'});});
	 
	 /*找到多视频上传按钮*/
	 var obj = hg_findFrameElements('vod_upload');
	 obj.click();
	 
}

/*判断单视频上传表单里面有没有填写数据*/
function hg_hasFormData()
{
	 if($('#title_vod').val() == '在这里添加标题')
     {
     	$('#title_vod').val('');
     }
	 
	 if($('#comment_vod').val() == '这里输入描述')
     {
     	$('#comment_vod').val('');
     }
	
	if($('#title_vod').val() || $('#comment_vod').val() || $('#author_vod').val() || $('#subtitle_vod').val() || $('#keywords_vod').val() || $('#vod_sort_id').val() || $('#source').val())
	{
		return true;
	}
	else
	{
		return false;
	}
}


function hg_submit_more()
{
    $("#single_video").click();
}

function hg_task_show(html,error)
{
//	alert(html);
	var id = window.parent.document.getElementById("channels_menu");
	$(id).html(html);
	$(id).show();
	$("#add_record").attr('class','button_4_cur');
}

function hg_add_record(mid)
{
	var id = window.parent.document.getElementById("channels_menu");
	
	if($(id).css('display')=='none')
	{
		if(!$(id).html())
		{
			var url = "./run.php?mid="+mid+"&a=record_form";
			hg_ajax_post(url);
		}
		else
		{
			$(id).show();
			$("#add_record").attr('class','button_4_cur');
		}
	}
	else
	{
		$(id).hide();
		$("#add_record").attr('class','button_4');
	}
}

function hg_show_record(html,error)
{
/*	var frame = document.getElementById("mainwin");
	if ($(frame).attr('id'))
	{
		frame = frame.contentWindow;
		var nodeframe = frame.document.getElementById("nodeFrame");
		if(nodeframe)
		{
			frame = nodeframe.contentWindow;
		}
		
	}*/
	if(error && error == 'error')
	{
		$("#errorTips").html(html);
		$("#errorTips").fadeIn(1000);
		$("#errorTips").fadeOut(1000);
	}
	else
	{
		//alert($("body",parent.document,'#vodlist').attr('id'));
		/*if ($('#vodlist').attr('id'))
		{
			
			
			
		}*/
		var frame = document.getElementById("mainwin");
		if ($(frame).attr('id'))
		{
			frame = frame.contentWindow;
			var nodeframe = frame.document.getElementById("nodeFrame");
			if(nodeframe)
			{
				frame = nodeframe.contentWindow;
			}
			
			if (frame.$('#vodlist').attr('id'))
			{
				frame.$('#vodlist').prepend(html);
			}
		}
		$("#livwindialogClose").click();
	//	parent.window.hg_dialog_close();
	}
}