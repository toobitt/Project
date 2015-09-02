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
		var video = '<embed src="http://video.hcrt.cn/flash-player/Share.swf?mode=TVieVod&width=530&height=350&server=liveapi.hcrt.cn&file='+ link.substr(21) +'&startTimeInMS=0&forceBase64=false&serverVersion=1.5b&skin=d0d0d0video.hcrt.cna_bflash-playera_bCBNVodSkin.swf&amp;astart=true" quality="high" width="500" height="436" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>';
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
		var video = '<span style="font-size:12px;color:#0082CB;width:auto;cursor:pointer;" onclick="closevideo('+id+')">关闭</span><div><embed src="http://video.hcrt.cn/flash-player/Share.swf?mode=TVieVod&width=530&height=350&server=liveapi.hcrt.cn&file='+ link.substr(21) +'&startTimeInMS=0&forceBase64=false&serverVersion=1.5b&skin=d0d0d0video.hcrt.cna_bflash-playera_bCBNVodSkin.swf&amp;astart=true" quality="high" width="500" height="436" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed></div>';
		$('#pop').html(video);
		$('#pop').show();
	}
	open_create = function()
	{
		location.href = SNS_VIDEO + "my_album.php";
	}
});