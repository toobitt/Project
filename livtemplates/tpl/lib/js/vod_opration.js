var gOrderTask = 0;
var ss_time =0;
var old_id = '';//记录原始排序状态
function hg_switch_order(obj_name,flag)
{
	var h = 0;
	var w = parseInt($(window).width())/2; 
    var sw = parseInt($('#infotip').width())/2;
	var texttip = '';
	clearTimeout(ss_time);
	if(gDragMode)
	{
		if(!flag && $('#save_order').length)//此时代表没保存
		{
			if(!confirm('排序已改变，您确定要放弃此次排序吗？'))
			{
				return;
			}
			else
			{
				window.location.reload();//刷新页面
			}
		}
		
		$('#info_list_search').removeClass('b');
		texttip = flag?'排序保存成功':'排序模式已关闭';
		gDragMode = false;
		$("#"+obj_name).sortable( 'disable' );
		ss_time = setTimeout(function(){$('#infotip').fadeOut(1000);},2000);
		hg_taskCompleted(gOrderTask);
		old_id = '';//清空原始排序状态
	}
	else
	{
		$('#info_list_search').addClass('b');
		texttip = '排序模式已开启<span onclick="hg_switch_order(\''+obj_name+'\');" style="color:#666;margin:0 0 0 5px;font-size:12px;cursor: pointer;">退出</span>';
		gDragMode = true;
		hg_hasOperWin();/*如果右边操作框已经弹出来，就关闭掉*/
		$("#"+obj_name).sortable( 'enable' );
	}
	
	$('#infotip').css({'left':w-sw,'top':h}).html(texttip).show();

	hg_isShowCheckBox(gDragMode);
	
}

/*排序函数*/
var video_order_arr = new Array();
var gObjName = '';//记录操作的排序对象的名字
function  tablesort(obj_name,table_name,order_name,flag,flag2)
{
	gObjName = obj_name;
	$("#"+obj_name).sortable({
        revert: true,
        cursor: 'move',
        containment: 'document',
        scrollSpeed: 100,
        tolerance: 'intersect' ,
        start : function(event, ui){
			if(!old_id)
			{
				old_id = get_old_order('name');
			}
			hg_dragingStyle(true,ui.item);/*拖动时的样式*/
			video_order_arr = new Array();
			var old_arr_order = $(this).sortable('toArray');
			var order_id = '';
			if(flag2)
			{
				order_id = 'corderid';
			}
			else
			{
				order_id = 'orderid';
			}
			for(var i = 0;i < old_arr_order.length;i++)
			{
				video_order_arr.push($('#'+old_arr_order[i]).attr(order_id));
			}
	    },
        stop:function(event, ui){
	    	hg_dragingStyle(false,ui.item);/*松手时的样式*/
	    	var new_id = get_old_order('name');
	    	var new_arr_order = $(this).sortable('toArray');
	    	var order_id = '';
			if(flag2)
			{
				order_id = 'corderid';
			}
			else
			{
				order_id = 'orderid';
			}
	    	for(var i = 0;i < new_arr_order.length;i++)
			{
				$('#'+new_arr_order[i]).attr(order_id,video_order_arr[i]);
			}
	    	
	    	if(new_id == old_id)
	    	{
	    		if($('#save_order').length)
	    		{
	    			$('#save_order').remove();
	    			hg_taskCompleted(gOrderTask);
	    			old_id = '';//清空原始排序状态
	    		}
	    		return;
	    	}
	    	
	    	if(!$('#save_order').length)
	    	{
	    		flag = flag?true:false;
	    		flag2 = flag2?true:false;
	    		$('#infotip').append('<input  id="save_order" class="button_4" style="margin-left:10px;" type="button" value="保存排序" onclick="hg_save_order(\''+table_name+'\',\''+order_name+'\','+flag+','+flag2+');" />');
	    		gOrderTask = hg_add2Task({'name':'排序'});
	    	}
        }
	});
}

/*保存当前的排序*/
function hg_save_order(table_name,order_name,flag,flag2)
{
	var ids = new Array();
	var ids_str = '';
	var order_ids = new Array();
	var order_ids_str = '';
	var obj = '';
	if($("li[id^='r_']").length)
	{
		obj = $("li[id^='r_']");
	}

	if($("tr[id^='r_']").length)
	{
		obj = $("tr[id^='r_']");
	}

	obj.each(function(){
		if(flag2)
		{
			ids.push($(this).attr('cname'));
		}
		else
		{
			ids.push($(this).attr('name'));
		}
		
		if(flag2)
		{
			order_ids.push($(this).attr('corderid'));
		}
		else
		{
			order_ids.push($(this).attr('orderid'));
		}
	});
	
	ids_str = ids.join(',');/*以字符串的形式将数据提交到后台*/
	order_ids_str = order_ids.join(',');
	if(flag)
	{
		var url = "?a=drag_order&video_id="+ids_str+"&order_id="+order_ids_str+"&table_name="+table_name+"&order_name="+order_name;
	}
	else
	{
		var url = "./run.php?mid="+gMid+"&a=drag_order&video_id="+ids_str+"&order_id="+order_ids_str+"&table_name="+table_name+"&order_name="+order_name;
	}
	hg_ajax_post(url);
}

/*保存完排序的回调函数*/
function hg_overSaveDragOrder()
{
	hg_switch_order(gObjName,true);
}

/*首页复选框的切换 */
function hg_isShowCheckBox(flag)
{
     if(!flag)
     {
    	 $("a[name='alist[]']").removeClass('pic_logo');
    	 $("input[name='infolist[]']").css('visibility','visible');
     }
     else
     {
    	 $("a[name='alist[]']").addClass('pic_logo');
    	 $("input[name='infolist[]']").css('visibility','hidden');
     }
}

/*拖动时的效果 */
function hg_dragingStyle(flag,obj)
{
     if(flag)
     {
    	 $(obj).find('a[name="alist[]"]').removeClass('pic_logo');
    	 $(obj).find('a[name="alist[]"]').addClass('pic_logo_draging');
     }
     else
     {
    	 $(obj).find('a[name="alist[]"]').removeClass('pic_logo_draging');
    	 $(obj).find('a[name="alist[]"]').addClass('pic_logo');
     }

}

/*点击启动排序模式的时候,判断操作框有没有弹出来,如果已经弹出来,就隐藏*/
function hg_hasOperWin()
{
   	if($('#video_opration').length)
   	{
   		hg_close_opration_info();
   	}
}


/*跳转到编辑页面*/
function hg_jumpEdit(obj)
{
	if(gDragMode)
    {
	   return  false;
    }
	
	location.href=$(obj).attr('href');
}

/*跳转到自动创建的集合*/
function hg_goToCollect(obj,collect_id)
{
	if(gDragMode)
    {
	   return  false;
    }
	
	if(collect_id)
	{
		location.href=$(obj).attr('href');
	}
	else
	{
		alert('还没有集合！');
		return false;
	}
}

function  get_old_order(name)
{
	var old_ids = new Array();
	var obj = '';
	if($("li[id^='r_']").length)
	{
		obj = $("li[id^='r_']");
	}

	if($("tr[id^='r_']").length)
	{
		obj = $("tr[id^='r_']");
	}

	obj.each(function(){
		old_ids.push($(this).attr(name));
	});
	
	return old_ids.join(',');
	
}
var hg_show_left = 0;
function  hg_fabu(id)
{
	if(gDragMode)
    {
	   return;
    }
	hg_show_left = $("#img_lm_"+id).offset().left-$("#img_lm_"+id).width()/2-12;
	$("#fabu_"+id).css({"display":"block",'left':hg_show_left});
	
}

function hg_back_fabu(id)
{ 
	if(gDragMode)
    {
	   return;
    }
	
	$("#fabu_"+id).css("display","none");
}
function  hg_fabu_phone(id)
{
	if(gDragMode)
    {
	   return;
    }
	hg_show_left = $("#img_sj_"+id).offset().left-$("#img_sj_"+id).width()/2-18;
	$("#fabu_phone"+id).css({"display":"block",'left':hg_show_left});
	
}

function hg_back_fabu_phone(id)
{ 
	if(gDragMode)
    {
	   return;
    }
	
	$("#fabu_phone"+id).css("display","none");
}

function  hg_fabu_jh(id)
{
	if(gDragMode)
    {
	   return;
    }
	hg_show_left = $("#img_jh_"+id).offset().left-$("#img_jh_"+id).width()/2-14;
	$("#fabu_jh"+id).css({"display":"block",'left':hg_show_left});
	
}

function hg_back_fabu_jh(id)
{ 
	if(gDragMode)
    {
	   return;
    }
	
	$("#fabu_jh"+id).css("display","none");
}


function check_menu(id)
{
    if(gDragMode)
    {
	   return;
    }
	
	var  current_id = 'content_'+id;
	
	$("div[id^='content_']").each(function(){
		
		op_id = $(this).attr('id');
		
		if(op_id != current_id )
		{
			$(this).slideUp();	
		}
	
	});
	
	$("#content_"+id).slideToggle('normal', function(){
		correctPosition();
	    hg_resize_nodeFrame();
	});
	
}

/*校正右边弹出框小三角的位置*/
function correctPosition()
{
	if($('#vodplayer_' + gId + ',#flv_preview').length)
	{
		var h = $('#r_'+gId).height()/2 - 10;
		var y = $('#r_'+gId).offset().top + h;
		$('#arrow_show').animate({top:y+'px'},700);
	}
}


function  hg_change_list(name)
{
   var list_status = parseInt(hg_get_cookie('hg_vod_list_mode'));
   if(!list_status)
   {
	   $('#'+name).removeClass('list');
	   $('#'+name).addClass('list_img');
	   list_status = 1;
	   
   }
   else
   {
	   $('#'+name).removeClass('list_img');
	   $('#'+name).addClass('list');
	   list_status = 0;
   }
   hg_set_cookie('hg_vod_list_mode', list_status);
   hg_resize_nodeFrame();
}


var hg_video_id = "";

function  hg_get_collect_info(video_id,mid)
{
	 hg_video_id = video_id;
	 var url = "./run.php?mid="+mid+"&a=get_collect_info&ajax=1&video_id="+video_id;
	 hg_request_to(url,"","","hg_show_collectinfo");
}

function  hg_show_collectinfo(obj)
{
	var obj = obj[0];
	var str = "<div style='text-align:center;height:25px;color:gray;'>所属集合</div>";
	for(var i = 0;i<obj.length;i++)
	{
		str += "<div style='text-align:center;line-height:20px;'>"+obj[i].collect_name+"</div>"; 
	}
	
	$("#collect_info").html(str);
	$("#collect_info").css("display","block");

	setTimeout("hg_back_status()",3000);
	
}

function hg_back_status()
{
	$("#collect_info").hide(1000);
}


var hg_content = "";
function hg_getcollect_video()
{
	 var text_content = $("#get_contents").val();
	 hg_content = text_content;
	 var url = "./run.php?mid="+gMid+"&a=back_words&ajax=1&contents="+text_content;
	 hg_request_to(url,"","","hg_show_words");
}

function hg_show_words(obj)
{
	if(obj[0] && obj != 1)
	{
		var obj = obj[0];
		if(obj.length && obj)
		{
			var str = "";
			for(var i = 0;i<obj.length;i++)
			{
				   if(obj[i].collect_name)
				   {
					  var collect_name = obj[i].collect_name;
					  var collect_id = obj[i].id;
					  var new_name = collect_name.replace(hg_content,"<font color='red'>"+hg_content+"</font>");
					  str += "<div name='collect_name' class='overflow' style='cursor:pointer;font-size:13px;width:142px'  onclick='addtotext(this);' onmouseover='hg_changeBgColor(this,true);'  onmouseout='hg_changeBgColor(this,false);'  id="+collect_id+">"+new_name+"</div>";
				   }
			}
			
			$("#content_list").css("display","block");
			$("#content_list").html(str);
			hg_checkIsExits();//兼容图集里面类别的搜索
		}
		else
	    {
			 $("#content_list").css("display","none");
		     $("#content_list").text("");
		     hg_checkIsExits();//兼容图集里面类别的搜索
	    }
	}
	else
	{
		 $("#content_list").css("display","none");
	     $("#content_list").text("");
	     hg_checkIsExits();//兼容图集里面类别的搜索
	}

}

//判断输入的图集类别存不存在
function hg_checkIsExits()
{
	//先判断用户输入的内容有没有与下拉列表中相匹配的,如果有的话就采用该集合
	 if(!$('#tuji_sort_content').length)
	 {
		return;
	 }
	var content_text = $('#get_contents').val();
	var isHave = false;
	if(content_text)
	{
		$('div[name="collect_name"]').each(function(){
			var txt = $(this).text();
			if(txt == content_text)
			{
				isHave = true;
			}
		});
	}
	
	if(isHave)
	{
		 $('#tuji_sort_content').hide();
	}
	else
	{
		 $('#tuji_sort_content').show();
	}
	top.livUpload.OpenPosition();
}

function addtotext(obj)
{
	 var txt = $(obj).text();
	 $("#get_contents").val(txt);
	 $("#content_list").css("display","none");
	 //此处是为了兼容图集里面类别搜索的
	 hg_checkIsExits();
}

function hg_hide_contents()
{
	setTimeout(function(){
		$('#content_list').css('display','none');
	},500);
}

//该表列表颜色
function hg_changeBgColor(obj,e)
{
	if(e)
	{
		$(obj).addClass('vbg_color');
	}
	else
	{
		$(obj).removeClass('vbg_color');
	}
}

var gImgListId = '';
function hg_get_img(id)
{
	  if(gDragMode)
	  {
		  return;
	  }
	  
	  if(hg_isTransing(id))
	  {
		  var h = '0px';
		  var w = parseInt($(window).width())/2; 
		  var sw = parseInt($('#getimgtip').width())/2;
		  var texttip = '视频正在转码中，无法获取截图，请稍后...';
		  $('#getimgtip').css({'left':w-sw,'top':h}).text(texttip).show();
		  setTimeout(function(){$('#getimgtip').fadeOut(1000);},3000);
		  return;
	  }
		  
	  gImgListId = id;
	  if(!$('#content_'+id).is(':visible'))
	  {
		  if(!$('#img_loaded_'+id).attr('id'))
		  {
			  var img_count = 10;
			  var url = "./run.php?mid="+gMid+"&a=form_get_img&img_count="+img_count+"&id="+id;
			  hg_ajax_post(url);
			  upload_preview(gMid,'add_from_compueter_'+id,id);
		  }
	  }
	  
	  check_menu(id);
}

/*显示列表图片*/
function hg_show_picture(html)
{
	$('#show_list_'+gImgListId).html(html);
}

/*判断当前视频是否正在转码中*/
function hg_isTransing(id)
{
   if($("#text_"+id).text() == '转码中')
   {
	   return true;
   }
   else
   {
	   return false;
   }
}




function hg_play_video2(id)
{
	  if(gDragMode)
	  {
		  return;
	  }
	 
	$("#player_container_o").addClass("player_style_o");
	$("#player_container_c").addClass("player_style_c");
	$("#close_player").css("display","block");
	
	 hg_get_size();
	 
	  var parameters =
	  {   width: "610",
		  height: "498",
		  video: id,
		  autoPlay :true,
		  sideBarMode : 2
	  };
	
	  swfobject.embedSWF
	  (   PLAYER_URL, 
		  "player", 
		  parameters["width"], 
		  parameters["height"], 
		  "10.1.0", 
		  {}, 
		  parameters, 
		  { allowFullScreen: "true", wmode: "transparent" , allowscriptaccess: "always"}
	  ); 
	  
}

function hg_play_video(id)
{
	if(gDragMode)
	{
	   return;
	}
	 
	var url = "./run.php?mid="+gMid+"&a=vodplay&id="+id;
	hg_ajax_post(url);
}

function hg_insVideoPlayer(html)
{
	$('#player').html(html);
	$("#player_container_o").addClass("player_style_o");
	$("#player_container_c").addClass("player_style_c");
	$("#close_player").css("display","block");
}

/*选择图片之后，立即请求接口改变图片*/
function hg_select_pic(obj,id)
{
	if(typeof(obj) == 'string')
	{
		var url = "./run.php?mid="+gMid+"&a=update_img&img_src="+obj+"&id="+id+"&module_id="+gMid;
	}
	else
	{
		var url = "./run.php?mid="+gMid+"&a=update_img&img_src="+$(obj).attr('src')+"&id="+id+"&module_id="+gMid;
	}
    
    hg_ajax_post(url,'','','hg_change_listimg');
}

/*改变列表的图片*/
function hg_change_listimg(obj)
{
	$('#img_'+obj[0].id).attr('src',obj[0].img);
	//$('#img_test').html("<img src='"+obj[0].img+"' />");
	check_menu(obj[0].id);
}

/*截图预览*/
function upload_preview(mid,placeholder,id)
{
	var vod_swfu_pic;
	var url = "./run.php?mid="+mid+"&a=preview_pic&id="+id+"&admin_id="+gAdmin.admin_id+"&admin_pass="+gAdmin.admin_pass;
	vod_swfu_pic = new SWFUpload({
		upload_url: url,
		post_params: {"access_token":gToken},
		file_size_limit : "50 MB",
		file_types : "*.jpg;*.jpeg;*.png;*.gif;",
		file_types_description : "预览图片",
		file_upload_limit : "0",

		file_queue_error_handler     : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_progress_handler      : uploadProgress,
		upload_error_handler         : uploadError,
		upload_success_handler       : uploadSuccess,

		button_image_url : RESOURCE_URL+"add_from_cpu.png",
		button_placeholder_id : placeholder,
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


function  hg_move_sort(obj)
{
	var obj = obj[0];
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
			for(var i = 0;i<obj.id.length;i++)
		    {
				frame.$('#sortname_'+obj.id[i]).text(obj.sort_name).css('color',obj.color);
		    }
			
		}
	}
	
	$('#livwindialogClose').click();
	
}


var gId = 0;
function hg_show_opration_info(id,_type,_id)
{
	 if(gDragMode)
	 {
		   return;
	 }
	 
	 /*判断当前有没有打开，打开的话就关闭*/
	 if($('#vodplayer_'+id).length)
	 {
		 hg_close_opration_info();
		 return;
	 }
	 /*关闭之前保存选项卡的状态到cookie*/
	 hg_saveItemCookie();

	gId = id;

	var ajaxcallback = function(){
		var param_type = '';
		var param_sort = '';
		if(_type)
		{
			param_type = '&frame_type='+_type;
		}
		
		if(_id)
		{
			param_sort = '&frame_sort='+_id;
		}
		var url = "./run.php?mid="+gMid+"&a=show_opration&id="+id+param_type+param_sort;
		hg_ajax_post(url);
	}

	;(function(){
		var h;
		h = Math.max($('body').scrollTop(), $(window.parent.document).scrollTop());
		$('#edit_show').html('<img src="'+ RESOURCE_URL + 'loading2.gif' +'" style="width:50px;height:50px;"/>');
		click_title_show(h, ajaxcallback);
	})();
}

function hg_show_opration_group(id,_type,_id)
{
	 if(gDragMode)
	 {
		   return;
	 }
	 
	 /*判断当前有没有打开，打开的话就关闭*/
	 if($('#vodplayer_'+id).length)
	 {
		 hg_close_opration_info();
		 return;
	 }
	 /*关闭之前保存选项卡的状态到cookie*/
	 hg_saveItemCookie();

	gId = id;
	var param_type = '';
	var param_sort = '';
	if(_type)
	{
		param_type = '&frame_type='+_type;
	}
	
	if(_id)
	{
		param_sort = '&frame_sort='+_id;
	}
	var url = "./run.php?mid="+gMid+"&a=show_opration&group_id="+id+param_type+param_sort;
	hg_ajax_post(url);
}

function hg_show_opration_tp(html)
{
	//var h=$('body',window.parent.document).scrollTop();
	$('#edit_show').html(html);
	correctPosition();
	//click_title_show(h);
	$('li[id^="r_"]').removeClass('cur2');
	if (gRowCls != 'cur')
	{
		$('#r_'+gId).addClass('cur2');
		gRowCls = 'cur2';
	}
}
function hg_close_opration_info()
{
	/*关闭之前保存选项卡的状态到cookie*/
	hg_saveItemCookie();

	$('li[id^="r_"]').removeClass('cur2');
	if (gRowCls == 'cur2')
	{
		gRowCls = '';
	}
	$('#arrow_show').css({right:"-422px"});
	$('.edit_show').animate({right:"-440px"},"fast",function(){$('#edit_show').empty();$('.edit_show').css('display','none');hg_resize_nodeFrame();});
}

/*关闭之前保存选项卡的状态到cookie*/
function hg_saveItemCookie()
{
	if(gId && $('#vodplayer_'+gId).length && $('#video_subinfo').length && $('#video_info').length && $('#video_collect').length)
	{  
		hg_set_cookie('video_subinfo',$('#video_subinfo').css('display'), 1);
		hg_set_cookie('video_info',$('#video_info').css('display'), 1);
		hg_set_cookie('video_collect',$('#video_collect').css('display'), 1);
	}
	
	if(gId && $('#vodplayer_'+gId).length && $('#channel_info_box').length && $('#output_stream').length && $('#stream_uri').length)
	{  
		hg_set_cookie('channel_info_box',$('#channel_info_box').css('display'), 1);
		hg_set_cookie('output_stream',$('#output_stream').css('display'), 1);
		hg_set_cookie('stream_uri',$('#stream_uri').css('display'), 1);
	}
}

/*编辑显示*/
function hg_arrow_show(){
	if($('#add_videos').css('display')=='block')
	{
		hg_closeUploadTpl();
	}
	
	if($('#add_to_collect').css('display')=='block')
	{
		 hg_closeAddToCollectTpl();
	}
	hg_resize_nodeFrame();
	$('#arrow_show').css({right:"422px"});
}
function click_title_show(h, callback)
{
	$('.edit_show').css('display','block');
	var id=window.parent.document.getElementById('channels_menu');
	var ld=window.parent.document.getElementById('info_live');
	var wrapcallback = function(){
		callback();
		hg_arrow_show();
	}
	
	if($(id).css('display')=='block')
	{
		
			if(h >= 363 && $(ld).css('display')=='block')
			{
				h= h-363+40;
				$('.edit_show').animate({'right':'0','top': h + 'px'},"fast",function(){wrapcallback();});
			}
			else if(h >= 150 && $(ld).css('display')=='none')
			{
				h= h-150+40;
				$('.edit_show').animate({'right':'0','top': h + 'px'},"fast",function(){wrapcallback();});
			}
			else
			{
				$('.edit_show').animate({'right':'0','top':'40px'},"fast",function(){wrapcallback();});
			}
		
		
	}
	else 
	{
		if(h >= 103)
		{
			h= h-103+40;
			$('.edit_show').css({'top': h + 'px'});
			$('.edit_show').animate({'right':'0'},"fast",function(){wrapcallback();});
		}
		
		else
		{
			var top
			if(gMid == 20||gMid==58)
			{
				top = '40px';
			}
			else
			{
				top = '0px';
			}
			$('.edit_show').css({'top':top});
			$('.edit_show').animate({'right':'0'},"fast",function(){wrapcallback();});
		}
	}
	
}

/*手动设置完成标注的回调函数*/
function hg_OverFinishMark(obj)
{
	var obj = eval('('+obj+')');
	for(var i = 0;i<obj.length;i++)
	{
		$('#is_finish_'+obj[i]).text('完成');
	}
}

/*手动设置视频为不允许标注*/
function hg_OverRemoveFromMark(obj)
{
	var obj = eval('('+obj+')');
	for(var i = 0;i<obj.length;i++)
	{
		$('#r_'+obj[i]).remove();
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

/*推荐*/
function hg_vod_recommend(obj)
{
	 if(gDragMode)
	 {
		 return  false;
	 }
	 
	 return  hg_ajax_post(obj, '推荐', 0);
}


function hotkey(e) 
{ 
	
	var q=window.event ? e.keyCode:e.which; 
	if((q==81)&&(e.altKey)) //快速关闭编辑
	{ 
		hg_close_opration_info(); 
	}
	if((q==87)&&(e.altKey)) //文件属性
	{
		$('#video_subinfo').slideUp();
		hg_slide_up(this,'video_info');
	}
	if((q==82)&&(e.altKey)) //排序
	{ 
		hg_switch_order(gObjName); 
	}
}

document.onkeydown = hotkey; /*当onkeydown 事件发生时调用hotkey函数 */
