var tt_m=tt_s=hg_img_url=0;
var img_move="#img_move";/*移动图片ID*/
var move_time = 500;/*移动时间*/

function getTime()
{
	var times = document.getElementById('view').getTime();
	var endTime = parseInt(times[0]) + parseInt(times[1]);
	return {'start':times[0],'end':endTime};
}

var selector = {};
selector.snap = function(current_time)
{
	 hg_request_imgage(current_time);
}

/*拖动标注时松开把柄时的事件*/
var memery_time = 0;
selector.change = function(startTime, duration) 
{
	if(gLoadFlashMode)
	{
		return;
	}
	
	if(memery_time != startTime)
	{
		hg_request_imgage(startTime);
	}
	memery_time = startTime;
	hg_editvideoBox(startTime,duration);
	hg_get_mark_images(true);
	/*松开把柄的那一瞬间就去检测所有时间段有没有重复的，如果有重复就不能反选*/
	var sTime   = parseInt(startTime);
	var eTime = parseInt(startTime) + parseInt(duration);

	if(eTime)
	{
		if(!hg_check_repeat(sTime,eTime) && !hg_checkFormOneType())
		{
			$('#unselect').show();
		}
		else
		{
			$('#unselect').hide();
		}
	}
}

//检测时间段有没有重复
function hg_check_repeat(sTime,eTime)
{
	var id = parseInt(hg_getCurrentId());
	var iDiff = false;
	$('li[id^="video_box_"]').each(function(){
		var cid = parseInt($(this).attr('id').substr(10));
		var start_time = parseInt($(this).find('input[name="start_time[]"]').val());
		var duration   = parseInt($(this).find('input[name="duration[]"]').val());
		var end_time   = start_time + duration;
		if(id != cid)
		{
			if(!(sTime >= end_time || eTime <= start_time))//这种情况是不可以反选
			{
				iDiff = true;
			}
		}
	});
	return iDiff;
}

function hg_request_imgage(current_time)
{
	var vcr_id = hg_getCurrentId();
	var id = $('#original_id_'+vcr_id).val();
	var img_count = 1;
	var url = "./run.php?mid="+gMid+"&a=get_current_img&img_count="+img_count+"&stime="+current_time+"&id="+id;
	hg_ajax_post(url,'','','hg_get_one_img');
}

function hg_get_one_img(obj)
{
	$(img_move).clearQueue();
	clearTimeout(tt_s,tt_m);
	$(img_move).attr('src',obj[0].new_img);
    $('#source_img_pic').val(obj[0].new_img);
	move_img();
	tt_s = setTimeout(he_get_mov_img_show,move_time+10);
}

function he_get_mov_img_show()
{
	hg_img_url = $('#source_img_pic').val()
	$('#pic_face').attr('src',hg_img_url);
}
function move_img()
{
	$(img_move).addClass('move_img_b');
	$(img_move).animate({top:'80px',left:'851px',width:'190px','height':'143px'},move_time);
	tt_m = setTimeout(remove_move_img,move_time+20);
}
function remove_move_img()
{
	$(img_move).remove();
	$("#view").before('<img class="move_img_a" src="" style="width:330px;height:240px;" id="img_move"/>');
}
var old_time = "";
function  hg_get_mark_images(flag)
{
	var vcr_id = hg_getCurrentId();
	var id = $('#original_id_'+vcr_id).val();
	var iopen = $('#info-img').css('display');
	var is_display = '';
	is_display = flag?'block':'none';
	
	var mark_time = getTime();
	if(!mark_time.end)return;/*如果结束时间不存在就退出*/

	if( ( mark_time.start != old_time.start  ||  mark_time.end != old_time.end ) && iopen == is_display )
	{
		 if(!mark_time.start)
		 {
             mark_time.start = mark_time.start + 1;
		 }
		 
		 old_time = mark_time;
		 hg_get_img_html(id,gMid,mark_time.start,mark_time.end);
	}
}


function  hg_submit_mark_video(formid)
{
    var mark_time = getTime();
    $('#mark_start').val(mark_time.start);
    $('#mark_end').val(mark_time.end);
    if($('#title').val() == '请输入标题')
    {
    	alert('您未填写标题');
    	return false;
    }
    
    if($('#comment').val() == '这里输入描述')
    {
    	$('#comment').val('');
    }
    
    $('#vod_sort_id').val($('#mark_sort_id').val());
    $('#source').val($('#mark_source_id').val());
    if(parseInt($('#add_edit').val()) == -1)
    {
    	if(confirm('编辑视频后不可返回'))
    	{
    		return hg_ajax_submit(formid,'','','hg_reset_mark_form');
    	}
    	else
    	{
    		return false;
    	}
    }
    return hg_ajax_submit(formid,'','','hg_reset_mark_form');
}

/*获取一张默认图*/
function hg_getDefaultImage(flag)
{
	if(!parseInt(add_edit))
	{
		var se_tiem = getTime();
		if(flag)
		{
			hg_request_imgage(se_tiem.end);
		}
		else
		{
			var img_src = $('#img_src').val();
			var img_src_cpu = $('#img_src_cpu').val();
			if(!(img_src || img_src_cpu))
			{
				hg_request_imgage(se_tiem.start);
			}
		}
	}
}

function hg_reset_mark_form(obj)
{
	if($('#add_edit').val() == 0)
	{
		$('#title').val('请输入标题').addClass('t_c_b');
		$('#subtitle').val('');
		$('#comment').val('这里输入描述').addClass('t_c_b');
		$('#keywords').val('');
		$('#author').val('');
		/*
		$('#mark_sort_id').val('');
		$('#display_mark_source_show').text('自动');
	    $('#mark_source_id').val('');
	    $('#display_mark_sort_show').text('自动');
	    */
		//只有是单段标注时才做一下的操作
		if(!obj[0].more_vcr)
		{
			//setMarkTime(obj[0].startTime,obj[0].endTime);
			$('#view').get(0).setMedia(obj[0].mark_vodid,obj[0].video_mark,obj[0].mark_start,obj[0].mark_duration,obj[0].mark_aspect);
			$('#count_id').text(obj[0].markCount).show();
		}
		
		if(!parseInt(mark_count))
		{
			$('#mark_text').text('该视频已被标注：');
		}
		
		$('#count_type').show();
		hg_getDefaultImage(true);
	}
	else
	{
		var url = $('#go_to_refer').val();
		url = url.replace('&a=video_mark','');
		url = url + '&a=frame';
		location.href = url;
	}	
	
	
}

function hg_info_bigimg_show()
{
	hg_livclose();
	$("#info-img").slideToggle(function(){
		hg_adjust_position();
	});
	
	$(".add").slideUp();
}


function setMarkTime(startTime,endTime)
{
	$('#view').get(0).setStart(startTime);
	$('#view').get(0).setEnd(endTime);
	/*document.getElementById('view').setMedia(obj.vodid,obj.video_mark,obj.start,obj.duration,obj.aspect);*/
}

/*调整页面的位置*/
function hg_adjust_position()
{
	if($('#info-img').css('display') == 'block' && $('#hgCounter_0_column').css('display') == 'block')
	{
		$('#content_m_l').height(1058);
		$('#content_big').height(1078);
	}
	else
	{
		$('#content_m_l').height(839);
		$('#content_big').height(859);
	}
}
