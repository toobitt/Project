/* $Id: program_week.js 5656 2011-12-10 09:30:02Z repheal $ */
function disp_confirm()
{
	var name=confirm("确定复制上一周节目？当前所有节目将被清空");
	if (name==true)
	{
		window.location.href="run.php?mid=" + gMid + "&a=copy";
	}

}
function record_show() {		
		if($("#record_info").css("top")=="20px")
		{
			p_day_ul_show();
		}
		else
		{
			$("#screen_info_title").hide();
			$("#record_info_title").show();
			$("#record_info").animate({"top":"20px"});
			$("#screen_info").animate({"top":"320px"});
		}
  }
function screen_show() {  
	if($("#screen_info").css("top")=="20px")
	{
		p_day_ul_show();
	}
	else
	{
		$("#record_info_title").hide();
		$("#screen_info_title").show();
		$("#record_info").animate({"top":"320px"});
		$("#screen_info").animate({"top":"20px"});
	}
}

function p_day_ul_show(){
	$("#record_info").animate({"top":"320px"});
	$("#screen_info").animate({"top":"320px"});
	$("#record_info_title").hide();
	$("#screen_info_title").hide();
}

function switch_date()
{
	/*$("#dates").val();*/
}

function switch_date_show(id)
{
	if($("#"+id).css('display') != 'none')
	{
		$("#"+id).hide();
		$("#" + id + "_bg").hide();
	}
	else
	{
		$("#"+id).show();
		$("#" + id + "_bg").show();
	}
}

function switch_date_bg(id)
{
	$("#"+id).hide();
	$("#" + id + "_bg").hide();
}



function close_all(){
	$("#p_day_ul").hide();
	$("#record_info").hide();
	$("#screen_info").hide();
}
$(function(){
	var vid = $('#hg_channel').val();
	$('#channel_show_'+vid).css('border','1px solid #939393');

});