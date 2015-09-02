{code}
	if (!empty($formdata))
	{
		foreach ($formdata AS $key => $value)
		{
			$$key = $value;
		}
	}
{/code}
<script type="text/javascript">
	function setSwfPlay(flashId, url ,width, height, mute, objectId)
	{
		var swfVersionStr = "11.1.0";
	
		var xiSwfUrlStr = RESOURCE_URL+"swf/playerProductInstall.swf?201301261";
		var flashvars = {objectId: objectId, namespace: "player", url: url, mute: mute};
		var params = {};
		params.quality = "high";
		params.bgcolor = "#000";
		params.allowscriptaccess = "sameDomain";
		params.allowfullscreen = "true";
		params.wmode = "transparent";
		var attributes = {};
		attributes.id = flashId;
		attributes.name = flashId;
		attributes.align = "middle";
		swfobject.embedSWF(
		   RESOURCE_URL+"swf/Main.swf?201301261", flashId, 
		    width, height, 
		    swfVersionStr, xiSwfUrlStr, 
		    flashvars, params, attributes);

		swfobject.createCSS("#"+flashId, "display:block;text-align:left;");
	
	}

	$(function(){
		setSwfPlay('flashBox', "{$stream_info[0]['output_url']}", '400', '300', 100, 'flashBox');
	});
</script>
<script type="text/javascript">
	function hg_set_url(name, stream_name, output_url)
	{
		$('#play_title').text(name + ' [' + stream_name + ']');
		document.getElementById('flashBox').setUrl(output_url);
	}
</script>

<div class="info clear vider_s" id="vodplayer_{$id}" style="border-radius: 3px;box-shadow: 0 0 10px black;margin-bottom: 10px;background:#000;">
	<font id="play_title" style="padding: 0px 5px;font-size:10px;color: #fff;background: rgba(121, 121, 121, 0.5);position: absolute;top: 0;height: 27px;line-height: 27px;">{$ch_name} [{$stream_info[0]['name']}]</font>
	<div id="flashBox"></div>
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'stream')"><span title="展开\收缩"></span>备播信号</h4>
	<div id="stream" class="channel_info_box">
		<ul class="clear">
			<li class="overflow"><span>信号标识：</span>{$ch_name}</li>
			<li class="overflow"><span>所属服务器：</span>{$server_name}</li>
			<li class="overflow"><span>信号方式：</span>{if !$wait_relay}拉取{else}推送{/if}</li>
			<li class="overflow"><span>信号格式：</span>{if !$audio_only}视频{else}音频{/if}</li>
			<li class="overflow"><span>信号类型：</span>{if !$type}直播流{else}文件流{/if}</li>
		</ul>
	</div>
</div>
<div class="info clear cz">
   <div id="video_opration" class="clear common-list" style="border:0;height:auto">
	    <div class="common-opration-list">
	       <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$id}&infrm=1">编辑</a>
          <a class="button_4" onclick="hg_check_channel({$id});" href="javascript:void(0);">删除</a>
        </div>
    </div>
</div>
{if !empty($stream_info)}
	{foreach $stream_info AS $vv}
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'stream_info_{$vv['id']}')"><span title="展开\收缩"></span>{$vv['name']} 信号</h4>
	<div id="stream_info_{$vv['id']}" class="channel_info_box">
		<ul class="clear">
			<li class="overflow">
				<span>流名称：</span>
				<a title="点击播放" href="javascript:void(0);" onclick="hg_set_url('{$ch_name}', '{$vv['name']}', '{$vv['output_url']}');">{$vv['name']}</a>
			</li>
			<li class="overflow"><span>码流：</span>{$vv['bitrate']}</li>
			<li class="overflow" style="width:350px;"><span>输入流：</span>{$vv['input_url']}</li>
			<li class="overflow" style="width:350px;">
				<span>输出流：</span><a title="点击播放" href="javascript:void(0);" onclick="hg_set_url('{$ch_name}', '{$vv['name']}', '{$vv['output_url']}');">{$vv['output_url']}</a>
			</li>
			<li class="overflow" style="width:350px;"><span>所选备播文件：</span>{$vv['backup_name']}</li>
			
		</ul>
	</div>
</div>
	{/foreach}
{/if}