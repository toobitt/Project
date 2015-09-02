
/*
*
*播放视频
*url:视频地址
*/
function hg_play_preview_video(url)
{
	hg_play_animat();
	$('#flashhandler').empty().html('<div style="background:black;"><span style="float:right;" onclick="hg_video_gb();"><a href="#" style="color:white;">关闭</a></span><div id="_vod_preview" style="display:none"></div></div>');
	
	var videourl = url;
	if (videourl == '')
	{
		return;
	}
	var resource = "http://video.hcrt.cn/flash-player/";
	var width = 530;
	var height = 462;
	var tvie = new TViePlayer("_vod_preview", resource+"CBNVodSkin.swf", width, height);
	
	tvie.videoScaleMode = "original";
	tvie.bgcolor="#000";
	tvie.loader = resource+"Loader.swf";
	tvie.player = resource+"Player.swf";
	tvie.setTVieVod("video.hcrt.cn", videourl);
	tvie.setJSCallback("playComplete", "lightOnHanle", "lightOffHandle");
	var adLoader = resource+"MockADS/TVieADLoader.swf";
	var layout = {layout:"float",vertical:"middle",horizen:"center"};
	
	tvie.addPlugin(resource+"TVieNotice.swf", { mode: "isolate" }, {layout:"float",x:105,y:420}, ["api","http://www.hoolo.tv/dealfunc/notice.php","width",300,"height",20,"refreshInterval",3]);
	tvie.addPlugin(resource+"MockADS/TVieADLoader.swf",{mode:"isolate"},{layout:"float",vertical:"top",horizen:"right",x:-20,y:10},["url","http://img.hoolo.tv/test/folder279/fold1/1315453753_34927600.png","width",115,"height",57],null,true,null,null);
	tvie.run();
}

function hg_play_animat()
{
	
	if($('#flashhandler').css('display') == 'none')
	{
		$('#flashhandler').show();
		$('#flashhandler').animate({'top':'27%'},1000);
	}
}