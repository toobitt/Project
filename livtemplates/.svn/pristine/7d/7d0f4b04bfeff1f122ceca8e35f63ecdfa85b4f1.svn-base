var i=0;
	/*拖拉效果*/
		
	function  task_liveclose()
	{
		$(".task_live").slideUp();
		$("#task_2").attr('class','text-click');
		$("#magnifier").hide();
		$("#zoom").show();
		$("#program").attr('class','');
	}

	
	/*伸缩效果*/	 
    var img_flag = 0;
	function uploadimg_show(id,mid)
	{
		$("#info-img").slideToggle('normal', function(){hg_resize_nodeFrame();});
		img_flag = !img_flag; 
		if(!$("img[id^='list_pic_']").length)
		{
			hg_get_img_html(id,mid);
		}

		closeall();
	}
  	
  	
	 $("#show").mousemove(function(){
			 if(i==0)
			 {
				$("#edit").attr('class','edit_move');
			 }
			 else
			 {				
				openall();
			 }
	 }); 
	 
	 
	$("#show").mouseout(function(){
			if(i==0)
			 {
				closeall();
			 }
			 else
			 {
				openall();
			}
	}); 
		 
	function  closeall()
	{
		i=0;
		$("#edit").attr('class','edit');
		$("#show").attr('class','show');
		$("#column").slideUp();
		$("#column_2").attr("id","column_1");
		setTimeout('$("#column_1").hide(600, function(){hg_resize_nodeFrame();});',100);
	}

	function openall()
	{
			$("#info-img").slideUp();
			$("#column").slideDown();
			$("#show").attr('class','show_move');
			$("#column_1").show();
			$("#column_1").attr("id","column_2");
	}

	var c=0;
	$("#video").click(function(){
		if(c==0){
		$("#video").attr('class','video_click');
		c=1;
		}
		else
		{
		$("#video").attr('class','video');
		c=0;
			}
	});
	
  $("#show").attr('class','show');
  $("#mark").click(function(){
	  		  livclose();
  $("#show").attr('class','show');
  $(".add").slideToggle();
  $("#info-img").slideUp();
  $("#column").slideUp();
  });
	    
$("#add-button").click(function(){
	
	var count = document.getElementById('add-ul').getElementsByTagName('li').length;
	if(count >= 8)
	{
		}
	else
	{
		$("#add-ul").append('<li><a><img src="IMG/2.png" width="59" height="45" /><span class="start-time">18:10:22</span><span class="end-time">18:10:24</span></a></li>');
		}	
	});	


$("#addinfo-img").click(function(){
	
	var count = document.getElementById('add-img').getElementsByTagName('dd').length;
	if(count >= 9)
	{
		}
	else
	{
		$("#add-img").append('<dd><a name="flag" id="add-check-'+count+'" onclick="add_pic(this);"><img src="IMG/4.jpg" width="117" height="88" /></a></dd>');
		}	
});
	
	var flag = false;
	
	add_pic = function(obj)
			  {
					var id='#'+obj.id;
					
					if(flag == false)
					{
						$(id).append('<span id="info-img-selected"></span>');
						flag = true;	
					}
					else
					{
						$('#info-img-selected').empty();
					}
			   }	
	


	function hg_get_img_html(id,mid,stime,etime)
	{
		var img_count = 10;
		var url = "./run.php?mid="+mid+"&a=get_img_update&img_count="+img_count+"&id="+id;
		if(stime)
		{
			url = url + "&stime="+stime;
		}
		
		if(etime)
		{
			url = url + "&etime="+etime;
		}
		
		hg_ajax_post(url);
	}
 
    function  hg_get_img_update(html)
    {
    	$('#add-img').html(html);
		hg_resize_nodeFrame();
    }
    
    
    function upload_update_preview(mid)
    {
    	var vod_swfu_pic;
    	var url = "run.php?mid="+mid+"&a=preview_pic&admin_id="+gAdmin.admin_id+"&admin_pass="+gAdmin.admin_pass;
    	vod_swfu_pic = new SWFUpload({
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

    		button_image_url : "",
    		button_placeholder_id : "add_from_compueter",
    		button_width: 100,
    		button_height: 75,
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
    
    var hg_old_sel = "";
    function  hg_show_pic(obj)
    {
    	$('#img_src_cpu').val('');
    	if(hg_old_sel)
    	{
    		$('#sel_'+hg_old_sel).removeClass('info-img-selected');
			$('#'+hg_old_sel).parent().attr('class','');
    	}
    	
    	var id = $(obj).attr('id');
    	if(hg_old_sel != id)
    	{
    		$('#pic_face').attr('src',$(obj).attr('src'));
        	$('#img_src').val($(obj).attr('src'));
    		hg_old_sel = id;
        	$('#sel_'+id).addClass('info-img-selected');
        	$(obj).parent().attr('class','cur');
    	}
    	else
    	{
    		$('#sel_'+id).removeClass('info-img-selected');
    		$('#pic_face').attr('src',$('#source_img_pic').val());
        	$('#img_src').val('');
    		hg_old_sel = "";
    	}
    	
    	
    }
    
    /*获取所点击的版本*/
    function hg_get_copyright(obj,id,mid)
    {
    	  $('li[name^="copyright[]"]').css('background','');
    	  $('li[name^="copyright[]"]').find('span').css('color','#7D7D7D');
    	  $(obj).css('background','#5F9BD1');
    	  $(obj).find('span').css('color','white');
    	  var url = "./run.php?mid="+mid+"&a=get_copyright&ajax=1&id="+id;
    	  hg_request_to(url,"","","hg_show_copyright");
    }
    
    /*获取更多的版本选项*/
    var gTotalNum = 0;
    function hg_getMoreCopyright(id,mid)
    {
    	var length = parseInt($('#page_num').val());
    	var offset = gTotalNum + length;
    	gTotalNum = offset;
    	var url = "./run.php?mid="+mid+"&a=more_copyright&id="+id+"&start="+offset+"&length="+length;
    	hg_ajax_post(url);
    }
    
	function hg_getMoreCopyright_auto(obj,top){

		$("#"+obj).jscroll({ W:"4px"//设置滚动条宽度
			,Bg:"none"//设置滚动条背景图片position,颜色等
			,Bar:{
				 Pos:top
				 ,Bd:{Out:"#000",Hover:"#000"}//设置滚动滚轴边框颜色：鼠标离开(默认)，经过
				 ,Bg:{Out:"#000",Hover:"#000",Focus:"#000"}}//设置滚动条滚轴背景：鼠标离开(默认)，经过，点击
			,Btn:{btn:false}
		});
		
	}

    /*插入版本列表中*/
    function hg_doTheCopyright(html)
    {

		if($('#copyright_list').hasClass('m'))
		{
			$('#copyright_list .jscroll-c').append(html);
		}
		else{
			$('#copyright_list').append(html);
			$('#copyright_list').addClass('m');
		}
		var total_num = parseInt($('#total_num').val());
    	var page_num  = parseInt($('#page_num').val());
    	if(total_num < gTotalNum + page_num)
    	{
			
    		$('#haveMore').hide();
    	}

		$('#copyright_list').animate({'height':'483px'},function(){
			hg_resize_nodeFrame();
			hg_getMoreCopyright_auto('copyright_list','bottom');
		});
    }
    
    
    /*显示版本*/
    function  hg_show_copyright(obj)
    {
    	 obj = obj[0];
    	 $("#title").val(obj.title);
    	 $("#comment").val(obj.comment);
    	 $("#subtitle").val(obj.subtitle);
    	 $("#keywords").val(obj.keywords);
    	 $("#author").val(obj.author);
    	 $("#pic_face").attr('src',obj.img);
    	 $("#img_src").val(obj.img);
    	 $("#img_src_cpu").val('');
    	 if(obj.source_name != -1)
    	 {
    		 $("#display_source_show").text(obj.source_name);
    		 $("#update_source_id").val(obj.source);
    	 }
    	 else
    	 {
    		 $("#display_source_show").text('自动');
    		 $("#update_source_id").val(obj.source);
    	 }
    	 
    	 if(obj.vod_sort_name != -1)
    	 {
    		 $("#display_up_sort_show").text(obj.vod_sort_name);
    		 $("#update_sort_id").val(obj.vod_sort_id);
    	 }
    	 else
    	 {
    		 $("#display_up_sort_show").text('自动');
    		 $("#update_sort_id").val(obj.vod_sort_id);
    	 }
    }
    
    

function hotkey(e) 
{ 
	
	var q=window.event ? e.keyCode:e.which; 
	if((q==87)&&(e.altKey)) 
	{ 
		video_show(); 
	}
}

document.onkeydown = hotkey;