var TVPlayer;
var param = {
			num:1,
			t:0,
			db_t:0,
			down:0,
			non:0
			}
var gTimer, gCurPlayIndex = 0,gProgramId = 0,gap = 10;

function timing(){
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
	play_url();
}


function lightOnHanle() { lamps(2,1);}
function lightOffHandle() { lamps(2,0);}
function play_url(){
	if (gCurPlayIndex > gLastProgram)
	{
		gCurPlayIndex = 0;
		tipsport('播放结束！');
		return;
	}
	$('#line_'+gProgramId).removeClass('play');
	gProgramId = gProgramIds[gCurPlayIndex];
	document.location.hash = gProgramId;
	var title = gTitles[gProgramId];
	var brief = '';//gBriefs[gProgramId];
	var relation = gRelations[gProgramId];
	var video_id = gVideoIds[gProgramId];
	var toff = gToffs[gProgramId] + gap;
	var media = gMedias[gProgramId];
//	var schematic = gSchematic[gProgramId];

     
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
	'<div><img src="'+RESOURCE_DIR+'img/adico5.gif"  /></div><div>';
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

		video_address = media;
        var resource = "http://video.hcrt.cn/flash-player/";
        var width = 538;
        var height = 462;
        
        TVPlayer = new TViePlayer(get_player_id(), resource+"CBNVodSkin.swf", width, height);
        
        TVPlayer.videoScaleMode = "original";
		TVPlayer.bgcolor="#000";
        //tvie.forceBase64 = true;
        TVPlayer.loader = resource+"Loader.swf";
        TVPlayer.player = resource+"Player.swf";
        TVPlayer.setTVieVod("video.hcrt.cn", video_address);
        TVPlayer.setHttpProgressiveMode(video_address);
        TVPlayer.setJSCallback("playComplete", "lightOnHanle", "lightOffHandle");
        var adLoader = resource+"MockADS/TVieADLoader.swf";
        var layout = {layout:"float",vertical:"middle",horizen:"center"};
        
        TVPlayer.addPlugin(resource+"TVieNotice.swf", { mode: "isolate" }, {layout:"float",x:105,y:420}, ["api","http://www.hoolo.tv/dealfunc/notice.php","width",300,"height",20,"refreshInterval",3]);
        TVPlayer.addPlugin(resource+"MockADS/TVieADLoader.swf",{mode:"isolate"},{layout:"float",vertical:"top",horizen:"right",x:-20,y:10},["url","http://www.hoolo.tv/liv_loadfile/test/folder279/fold1/1315453753_34927600.png","width",115,"height",57],null,true,null,null);
        TVPlayer.run();
    }

	function playComplete() {gCurPlayIndex++;play_url();}
	
	function get_player_id(){
        var id = "player_div_id";
        var container = document.getElementById("player");//请替换成你的id
        // 找到并删除旧容器
        var player_div = document.getElementById(id);
        if(player_div != null){
            container.removeChild(player_div);
        }
        // 创建新的容器
        player_div = document.createElement("div");
        player_div["id"]=id;
        player_div.id=id;
        container.appendChild(player_div);
        // 创建播放器存放的容器
        var div = document.createElement("div");
        div["id"]=id+".inner";
        div.id=id+".inner";
        player_div.appendChild(div);
        // 返回播放器容器id存放播放器
        return div.id;
    }

	play_new = function (nums)
	{
		gCurPlayIndex = gIndexs[nums];
		play_url();
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