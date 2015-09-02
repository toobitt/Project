$(document).ready(function (){
	scaleVideo = function(id,mid){
//		id = sid + mid;
		var title = $('#vt_'+id).val().length > 15 ? $('#vt_'+id).val().substr(0,15)+"...":$('#vt_'+id).val();
		var url = $('#vu_'+id).val();
		var ids = "vo_" + id;
		var link = $('#vl_'+id).val();
		var div = '<div class="pad_sp"><a href="javascript:void(0);" onclick="upvideo('+ mid +');">收起</a><a style="padding-left: 5px;" href="'+ url +'">'+ title +'</a><a style="padding-left: 5px;" href="javascript:void(0);" onclick="popvideo('+ id +','+ mid +');">弹出</a></div>';
		$('#v_'+mid).show();
		$('#v_'+mid).html(div+'<img src="./res/img/loading.gif"/>正在加载中...');
		if($.browser.msie) 
		{
			var video = '<embed height="300" width="440" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="autoStart=true&amp;id=0&amp;datarate=1&amp;site=flashdiv&amp;ws=false&amp;mode=SimpleVOD&amp;autostart=true&amp;days=undefined&amp;starttime=0&amp;endtime=0&amp;cdnurlsuffixenable=undefined&amp;cid=0&amp;fill=tvie_flash_players&amp;width=440&amp;height=300&amp;quality=high&amp;flashver=9.0.115&amp;flvplayer='+ RESOURCE_DIR +'swf/vod_player.swf&amp;channel_id=0&amp;fileurl='+ link +'&amp;dar=1.5" allowscriptaccess="always" allowfullscreen="true" quality="high" bgcolor="#FFFFFF" name="'+ mid +'" id="'+ mid +'" src="'+ RESOURCE_DIR +'swf/vod_player.swf" type="application/x-shockwave-flash">';
		}
		else
		{
			var video = '<object height="300" width="440" type="application/x-shockwave-flash" data="'+ RESOURCE_DIR +'swf/vod_player.swf" id="'+ mid +'" style="visibility: visible;"><param name="quality" value="high"><param name="allowScriptAccess" value="always"><param name="bgcolor" value="#000000"><param name="allowFullscreen" value="true"><param name="flashvars" value="autoStart=true&amp;id=0&amp;datarate=1&amp;site=flashdiv&amp;ws=false&amp;mode=SimpleVOD&amp;autostart=true&amp;days=undefined&amp;starttime=0&amp;endtime=0&amp;cdnurlsuffixenable=undefined&amp;cid=0&amp;fill=tvie_flash_players&amp;width=440&amp;height=300&amp;quality=high&amp;flashver=9.0.115&amp;flvplayer='+ RESOURCE_DIR +'swf/vod_player.swf&amp;channel_id=0&amp;fileurl='+ link +'&amp;dar=1.5"></object>';
		}
		$('#v_'+mid).html(div + video);		
		$('#pre_'+mid).hide();
	}		
	closevideo = function(id){
		$('#pop').hide();
		$('#pop').html('');		
		$('#pre_'+id).show();		
	}
	upvideo = function(id){
		$('#v_'+id).hide();
		$('#v_'+id).html('');
		$('#pre_'+id).show();
	}
	popvideo = function(id,mid){
		upvideo(mid);
		var link = $('#vl_'+id).val();
		var ids = "vo_" + id;
		
		if($.browser.msie) 
		{
			var video = '<span style="font-size:12px;color:#0082CB;width:auto;cursor:pointer;" onclick="closevideo('+id+')">关闭</span><div><embed height="300" width="440" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="autoStart=true&amp;id=0&amp;datarate=1&amp;site=flashdiv&amp;ws=false&amp;mode=SimpleVOD&amp;autostart=true&amp;days=undefined&amp;starttime=0&amp;endtime=0&amp;cdnurlsuffixenable=undefined&amp;cid=0&amp;fill=tvie_flash_players&amp;width=440&amp;height=300&amp;quality=high&amp;flashver=9.0.115&amp;flvplayer='+ RESOURCE_DIR +'swf/vod_player.swf&amp;channel_id=0&amp;fileurl='+ link +'&amp;dar=1.5" allowscriptaccess="always" allowfullscreen="true" quality="high" bgcolor="#FFFFFF" name="'+ id +'" id="'+ id +'" src="'+ RESOURCE_DIR +'swf/vod_player.swf" type="application/x-shockwave-flash"></div>';
		}
		else
		{
			var video = '<span style="font-size:12px;color:#0082CB;width:auto;cursor:pointer;" onclick="closevideo('+id+')">关闭</span><div><object height="300" width="440" type="application/x-shockwave-flash" data="'+ RESOURCE_DIR +'swf/vod_player.swf" id="'+ id +'" style="visibility: visible;"><param name="quality" value="high"><param name="allowScriptAccess" value="always"><param name="bgcolor" value="#000000"><param name="allowFullscreen" value="true"><param name="flashvars" value="autoStart=true&amp;id=0&amp;datarate=1&amp;site=flashdiv&amp;ws=false&amp;mode=SimpleVOD&amp;autostart=true&amp;days=undefined&amp;starttime=0&amp;endtime=0&amp;cdnurlsuffixenable=undefined&amp;cid=0&amp;fill=tvie_flash_players&amp;width=440&amp;height=300&amp;quality=high&amp;flashver=9.0.115&amp;flvplayer='+ RESOURCE_DIR +'swf/vod_player.swf&amp;channel_id=0&amp;fileurl='+ link +'&amp;dar=1.5"></object></div>';
		}
		$('#pop').html(video);
		$('#pop').show();
	}
	open_create = function()
	{
		location.href = SNS_VIDEO + "my_album.php";
	}
});