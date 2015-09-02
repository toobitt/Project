jQuery(document).ready(function (){
	//点击某个未读通知类型后，将该类所有的未读通知标记为已读
	insertReadSMS = function(id_str,type)
	{
		jQuery.getScript(sns_ui_url +'notifyajax.php?a=send_this_notify&type=' + type + '&n_ids='+ id_str,function(){
					location.href = reads.page_link;
			});
	};
	
	//标记全部已读
	markAllSMS = function()
	{
		jQuery.getScript(sns_ui_url +'notifyajax.php?a=send_read&type=-1',function(){
				jQuery("#notice_a").hide();
				jQuery("#notice_div").hide();
				jQuery("#head_n").css("visibility","hidden");
		});
	};
	////弹出通知
	getnotify = function()
	{	
		jQuery.getScript(sns_ui_url +'notifyajax.php?a=getnotify&ajax=1',function(){
			eval(data.callback);
		});
		setTimeout('getnotify()',30000);
	}; 

	hg_getnotify = function(html,tip)
	{
		if(html && html !='null' )
		{ 
			jQuery("#nav_002").html(html);
			jQuery("#notice_div").show();
			jQuery("#head_n").css("visibility","visible");
		}
	};
	hide_notice = function()
	{
		jQuery("#notice_div").hide();
	}; 
});


