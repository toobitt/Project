
function setSwfPlay(flashId, url ,width, height, mute, objectId)
{
	var swfVersionStr = "11.1.0";

	var xiSwfUrlStr = RESOURCE_URL+"swf/playerProductInstall.swf?1111111";
	var flashvars = {objectId: objectId, namespace: "player", url: url, mute: mute};
	var params = {};
	params.quality = "high";
	params.bgcolor = "#000";
	params.allowscriptaccess = "sameDomain";
	params.allowfullscreen = "true";
	params.wmode = "transparent";
	var attributes = {};
	attributes.id = flashId+'_1';
	attributes.name = flashId+'_1';
	attributes.align = "middle";
	swfobject.embedSWF(
	   RESOURCE_URL+"swf/Main.swf?1111111", flashId, 
	    width, height, 
	    swfVersionStr, xiSwfUrlStr, 
	    flashvars, params, attributes);

	swfobject.createCSS("#"+flashId, "display:block;text-align:left;");

}
	
/*
setUrl 切换流地址
setVolume 100静音 0有声音
clickHandler 定义onclick事件
*/
var Player = function() 
{
	this.rollOverHandler = function(id) {
		var id = id + '_1';
		document.getElementById('flashContent_1').setVolume(0);	
		document.getElementById(id).setVolume(100);
	};
	this.rollOutHandler = function(id) {
		var id = id + '_1';
		document.getElementById(id).setVolume(0);		
		document.getElementById('flashContent_1').setVolume(100);	
	};
	this.clickHandler = function(id) {
		var streamStr = $('#'+id).val();
		var backupStr = $('#'+id+'_1').val();
		var channelUrl = $('#' + id + '_2').val();
		if (streamStr)		/*信号流切播*/
		{
			var stream = new Array();
				stream = streamStr.split(","); 
			hg_emergency_change(stream[0],stream[1],stream[2]);
		}
		else if (backupStr)		/*文件流切播*/
		{
			var backup = new Array();
				backup = backupStr.split(","); 
			hg_emergency_change(backup[0],backup[1],backup[2]);
		}
		else if (channelUrl)		/*电视墙视频列表*/
		{
			var channel = new Array();
				channel = channelUrl.split(",");
			document.getElementById('flashContent_1').setUrl(channel[2]);
			$('#channelMainName').html('');
			$('#channelMainName').html(channel[1]);
			$('#program').html(channel[3] + ' - ' + channel[4] + ' ' + channel[5]);
			var liveHref = './run.php?mid='+gMid+'&a=form&infrm=1&id=' + channel[0];

			$('#liveHref').removeAttr('href');
			$('#liveHref').attr('href', liveHref);
		}
	
	};
};
var player = new Player();


function hg_stream2backup(obj, type)
{
	$(obj).css('color', 'gray');
	if (type == 'stream')
	{
		$(obj).next().css('color', '#000');
		$('#stream').show();
		$('#backup').hide();
	}
	else if (type == 'backup')
	{
		$(obj).prev().css('color', '#000');
		$('#stream').hide();
		$('#backup').show();
	}
}


var stream_status_id = "";
function  hg_output_stream_status(id)
{
	stream_status_id = id;
	var url = "./run.php?mid=" + gMid + "&a=streamState&id=" + id + "&infrm=1";
	hg_ajax_post(url,"","",'hg_stream_states');
}
function hg_stream_states(obj)
{
	var obj = obj[0];
	if(obj == '1')
	{
		$('#a_' + stream_status_id).html('停止流输出');
		$('#a_' + stream_status_id).attr('title','已启动流');
		$('#out_uri_' + stream_status_id +' a').css('background-color','#8CC7ED');
	}
	else if (obj == '2')
	{
		$('#a_' + stream_status_id).html('启动流输出');
		$('#a_' + stream_status_id).attr('title','已停止流');
		$('#out_uri_' + stream_status_id +' a').css('background-color','');
	}
	else
	{
		alert('操作失败！');
		return;
	}
}


function hg_getBackupFlash(id, backupUrl)
{
	var div = '<div style="width: 360px;padding: 0px 20px;margin-left: 10px;background: black;border-radius: 3px;box-shadow: 0 0 10px black;"><div id="backupFlashBox_'+id+'"></div></div>';

	$('#livwindialogbody').html(div);
	setSwfPlay('backupFlashBox_'+id, backupUrl, '360', '270', 0, 'backupFlashBox_'+id);

	$('#livwindialog').show();
	hg_BackupFlashInfoShow();
}

/*滑动效果*/
function hg_BackupFlashInfoShow()
{
	var off = $(top.document.getElementById('mainwin').contentWindow.document.getElementsByTagName('body')).scrollTop();

	off = off + 120;

	$('#livwindialog').animate({'top':off});
}
/*关闭其他选项窗口*/
function hg_getBackupFlashClose()
{
	var div = '<div style="width: 360px;padding: 0px 20px;margin-left: 10px;background: black;border-radius: 3px;box-shadow: 0 0 10px black;"><div id="backupFlashBox_" style="height:270px;width:360px;background:#000;"></div></div>';
	$('#livwindialogbody').html(div);
	$('#livwindialog').animate({'top':'-480px'});
//	$('#livwindialog').hide();

}

/*切播*/
function hg_emergency_change(channel_id,stream_id,chg_type,live_back)
{
	if(stream_id)
	{
		var url = "./run.php?mid="+gMid+"&a=emergency_change&channel_id=" + channel_id + "&stream_id=" + stream_id + "&chg_type=" + chg_type + "&live_back=" + live_back;
		hg_ajax_post(url,'','','emergency_change_back');
	}
}

function emergency_change_back(obj)
{	
	var obj = obj[0];
	
	try
	{
		if (obj['chg_type'] == 'stream')
		{
			chg_type = '信号';
		}
		else if(obj['chg_type'] == 'file')
		{
			chg_type = '备播文件';
			hg_getBackupFlashClose();
		}
		
		if (obj['stream_id'] == $('#stream_id').val() && obj['chg_type'] == 'stream')
		{
			type = '直播';
		}
		else
		{
			type = '切播';
		}
		
		$('#liv_status').html('正在 <font style="color:red;">'+type+'</font> ['+obj['stream_name']+'] ' + chg_type);
	/*	setSwfPlay('flashContent', gStreamUrl, '360', '270', 0, 'flashContent');		*/
		document.getElementById('flashContent_1').setVolume(100);
	}
	catch (e)
	{
		return;
	}
	
}




