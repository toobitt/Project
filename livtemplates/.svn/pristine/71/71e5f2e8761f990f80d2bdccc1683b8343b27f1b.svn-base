function hg_stop_start_crontab(type)
{
	if(type)
	{
		var html = '运行中...<a onclick="return hg_ajax_post(this, \'停止\', 0);" href="?a=stop">停止</a>';
	}
	else
	{
		var html = '已停止<a onclick="return hg_ajax_post(this, \'开始\', 0);" href="?a=start">开始</a>';
	}
	$("#crontab_state").html(html);
}