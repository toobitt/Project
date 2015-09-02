$(document).ready(function(){
	var param = {
			num:1,
			t:0,
			db_t:0,
			down:0,
			non:0
			}
	var gTimer, gCurPlayIndex = 0,gProgramId = 0,gap = 10;
	timing = function(){
		var hs = document.location.hash.substring(1) ;
		if(hs)
		{
			try
			{
				if (gIndexs[hs])
				{
					gCurPlayIndex = gIndexs[hs];
				}
			}
			catch (e)
			{
				gCurPlayIndex = 0;
			}
		}
		playVideo();
	}
	
	playVideo = function(){
		clearTimeout(gTimer);
		$('#line_'+gProgramId).removeClass('play');
		gProgramId = gProgramIds[gCurPlayIndex];
		document.location.hash = gProgramId;
		var title = gTitles[gProgramId];
		var brief = '';//gBriefs[gProgramId];
		var relation = gRelations[gProgramId];
		var video_id = gVideoIds[gProgramId];
		var toff = gToffs[gProgramId] + gap;
		var media = gMedias[gProgramId];
		var schematic = gSchematic[gProgramId];

		video_address = media;
        video_title = title;
         
		$("#line_"+gProgramId).addClass("play");
		$("#vedio_name_container").html(title);
    	$("#video_briefs").val(brief);
		tv1s = '<div><img src="'+RESOURCE_DIR+'img/adico1.gif"  /></div><div id="status_share"><a href="javascript:void(0);" onclick="show_status_share('+video_id+');">分享到点滴</a></div><div><img src="'+RESOURCE_DIR+'img/adico2.gif" /></div><div id="video_collect">';
		if(relation)
		{
			tv1s +='<a href="javascript:void(0);">已收藏</a>';
		}
		else
		{
			tv1s +='<a href="javascript:void(0);" onclick="add_collect('+video_id+',0,'+user_id+');">收藏</a>';
		}
		tv1s +='</div><div><img src="'+RESOURCE_DIR+'img/adico3.gif"/></div><div id="program_bt"><a href="javascript:void(0);" onclick="add_program_out('+video_id+','+sta_id+','+toff+')">选入编单</a></div> <div><img src="'+RESOURCE_DIR+'img/adico4.gif"  /></div>'+
		'<div id="la_2"><a href="javascript:void(0);" onclick="lamps(2,0);">关灯</a></div><div><img src="'+RESOURCE_DIR+'img/adico5.gif"  /></div><div>';
		if(user_id)
		{
			tv1s +='<a href="javascript:void(0);" onclick="get_comment_plot(1)">评论</a>';
		}
		else
		{
			tv1s +='<a href="javascript:void(0);" onclick="get_comment_plot(0)">评论</a>';
		}
		tv1s += '</div><div><img src="'+RESOURCE_DIR+'img/adico6.gif"/></div><div><a href="javascript:void(0);" onclick="show_share(1,\''+REQUEST_URL+'\')">分享</a></div>';
		$("#tv1").html(tv1s);
    	if($.browser.msie) 
    	{
    		var video = '<embed height="355" width="532" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="autoStart=true&amp;id=0&amp;datarate=1&amp;site=flashdiv&amp;ws=false&amp;mode=SimpleVOD&amp;autostart=true&amp;days=undefined&amp;starttime=0&amp;endtime=0&amp;cdnurlsuffixenable=undefined&amp;cid=0&amp;fill=tvie_flash_players&amp;width=532&amp;height=458&amp;quality=high&amp;flashver=9.0.115&amp;flvplayer=http://res.hoolo.tv/res/v/swf/vod_player.swf&amp;channel_id=0&amp;fileurl='+ media +'&amp;dar=1.5" allowscriptaccess="always" allowfullscreen="true" quality="high" bgcolor="#FFFFFF" name="tvie_flash_players" id="tvie_flash_players" src="http://res.hoolo.tv/res/v/swf/vod_player.swf" type="application/x-shockwave-flash">';
    	}
    	else
    	{
    		var video = '<object height="355" width="532" wmode="transparent" type="application/x-shockwave-flash" data="http://res.hoolo.tv/res/v/swf/vod_player.swf" id="tvie_flash_players" style="visibility: visible;"><param name="quality" value="high"><param name="allowScriptAccess" value="always"><param name="bgcolor" value="#000000"><param name="allowFullscreen" value="true"><param name="flashvars" value="autoStart=true&amp;id=0&amp;datarate=1&amp;site=flashdiv&amp;ws=false&amp;mode=SimpleVOD&amp;autostart=true&amp;days=undefined&amp;starttime=0&amp;endtime=0&amp;cdnurlsuffixenable=undefined&amp;cid=0&amp;fill=tvie_flash_players&amp;width=532&amp;height=458&amp;quality=high&amp;flashver=9.0.115&amp;flvplayer=http://res.hoolo.tv/res/v/swf/vod_player.swf&amp;channel_id=0&amp;fileurl='+ media +'&amp;dar=1.5"></object>';
    	}
    	$("#play_next").html(video);
    	
    	
		if (gCurPlayIndex > gLastProgram)
		{
			gCurPlayIndex = 0;
			tipsport('播放结束！');
			return;
		}
		toff = gToffs[gProgramId]*1000;	

		gCurPlayIndex++;
		gTimer = setTimeout("playVideo();",toff); 
//		gProgramId = gProgramIds[gCurPlayIndex];
	}
	

	play_new = function (nums)
	{
		gCurPlayIndex = gIndexs[nums];
		playVideo();
	}	

	getTimeStamp = function ()
	{
		var player = $('#tvie_flash_players');
		alert(player.getPlayingTimestamp);
	}
	
	change_list = function(type)
	{
		var obj = $("#cent_bt"); 
		he = obj.height();
		ohe = $("#tvt").height();
		var offset = obj.offset(); 
		if(he>ohe)
		{
			switch(type)
			{
				case 1:
					if(param.down<0)
					{
						param.down = param.down+50; 
						obj.css({ 
							'height':'auto',
							'margin-top': '20px',
							'position': 'relative',
							'top':param.down}); 
					}
					break;
				case 2:
					if(offset.top != 0)
					{
						if(-param.down<(he-ohe+50))
						{
							param.down = param.down-50;
							obj.css({ 
								'height':'auto',
								'margin-top': '20px',
								'position': 'relative',
								'top':param.down}); 
						}
					}
					break;
				default:
					break;
			}
		}
	}

});