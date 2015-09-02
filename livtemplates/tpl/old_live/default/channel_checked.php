{template:head}
<style type="text/css">
	.channel_box{height: 190px;background: #E6DEDE;margin-bottom: 5px;}
	.channel_box .channel{color: white;margin: 10px 0px 5px 10px;}
	.channel_box .channel .channel_name{font-size: 16px;color: #0F1011;font-weight: bold;margin-top: 10px;display: inline-block;}
	.channel_box .channel .channel_stream{}
	.channel_box .channel .stream_state{color: red;}
	.channel_box .f_l{float: left;background: green;margin: 5px 0px 5px 5px;padding: 5px;border-radius: 5px;}
	.channel_box .f_l .no_flash{width: 140px;height: 105px;}
	.channel_box .f_l .cengji{margin-top: 5px; color: white;}
</style>
<script type="text/javascript">
function setSwfPlay(flashId, url ,width, height, mute, objectId)
{
	var swfVersionStr = "11.1.0";
	var xiSwfUrlStr = RESOURCE_URL+"swf/playerProductInstall.swf?201211131";
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
	   RESOURCE_URL+"swf/Main.swf?201211131", flashId, 
	    width, height, 
	    swfVersionStr, xiSwfUrlStr, 
	    flashvars, params, attributes);
	swfobject.createCSS("#"+flashId, "display:block;text-align:left;");
}

var Player = function() 
{
	this.rollOverHandler = function(id) {
		document.getElementById(id).setVolume(100);
	};
	this.rollOutHandler = function(id) {
		document.getElementById(id).setVolume(0);		
	};
};
var player = new Player();
</script>

{code}
/*hg_pre($formdata);*/
{/code}
{if $formdata}
	{foreach $formdata AS $k => $channel}
<script type="text/javascript">
$(function(){
	{if $channel['channel_stream'][0]['input_url']}
		setSwfPlay('flashBox_input_'+ {$k}, "{$channel['channel_stream'][0]['input_url']}", '140', '105', 1, 'flashBox_input_' + {$k});
	{else}
		$('#flashBox_input_'+ {$k}).addClass('no_flash');
	{/if}
	{if $channel['channel_stream'][0]['delay_url']}
		setSwfPlay('flashBox_delay_'+ {$k}, "{$channel['channel_stream'][0]['delay_url']}", '140', '105', 1, 'flashBox_delay_' + {$k});
	{else}
		$('#flashBox_delay_'+ {$k}).addClass('no_flash');
	{/if}
	{if $channel['channel_stream'][0]['chg_url']}
		setSwfPlay('flashBox_chg_'+ {$k}, "{$channel['channel_stream'][0]['chg_url']}", '140', '105', 1, 'flashBox_chg_' + {$k});
	{else}
		$('#flashBox_chg_'+ {$k}).addClass('no_flash');
	{/if}
	{if $channel['channel_stream'][0]['out_url']}
		setSwfPlay('flashBox_out_'+ {$k}, "{$channel['channel_stream'][0]['out_url']}", '140', '105', 100, 'flashBox_out_' + {$k});
	{else}
		$('#flashBox_out_'+ {$k}).addClass('no_flash');
	{/if}
	{if $channel['channel_stream'][0]['dvr_url']}
	setSwfPlay('flashBox_dvr_'+ {$k}, "{$channel['channel_stream'][0]['dvr_url']}", '140', '105', 1, 'flashBox_dvr_' + {$k});
	{else}
		$('#flashBox_dvr_'+ {$k}).addClass('no_flash');
	{/if}
	{if $channel['channel_stream'][0]['_out_url']}
		setSwfPlay('flashBox__out_'+ {$k}, "{$channel['channel_stream'][0]['_out_url']}", '140', '105', 1, 'flashBox__out_' + {$k});
	{else}
		$('#flashBox__out_'+ {$k}).addClass('no_flash');
	{/if}
});
</script>
<div class="channel_box">
	<div class="channel">
		<span class="channel_name">{$channel['name']}</span>
		<span class="channel_stream">{$channel['channel_stream'][0]['stream_name']}</span>
		<span class="stream_state">{if !$channel['stream_state']}未启动{/if}</span>
	</div>
	<div class="f_l"><div id="flashBox_input_{$k}"></div><div class="cengji">输入层 {if !$channel['s_status']}(未启动){/if}</div></div>
	<div class="f_l">
		<div id="flashBox_delay_{$k}"></div><div class="cengji">延时层 {if !$channel['channel_stream'][0]['delay_url']}(不存在){/if}</div>
	</div>
	<div class="f_l"><div id="flashBox_chg_{$k}"></div><div class="cengji">切播层</div></div>
	<div class="f_l"><div id="flashBox_out_{$k}"></div><div class="cengji">时移层</div></div>
	<div class="f_l"><div id="flashBox_dvr_{$k}"></div><div class="cengji">时移层(dvr)</div></div>
	<div class="f_l">
		<div id="flashBox__out_{$k}"></div><div class="cengji">直播层 {if !$channel['channel_stream'][0]['_out_url']}(不存在){/if}</div>
	</div>
	<div class="f_l">
		<video src="{$channel['channel_stream'][0]['m3u8_url']}" width="140" height="105" controls="controls" autoplay="autoplay">
		</video>
		<div class="cengji">手机时移 {if !$channel['channel_stream'][0]['m3u8_url']}(不存在){/if}</div>
	</div>
	<div class="f_l">
		<video src="{$channel['channel_stream'][0]['_m3u8_url']}" width="140" height="105" controls="controls" autoplay="autoplay">
		</video>
		<div class="cengji">手机直播 {if !$channel['channel_stream'][0]['_m3u8_url']}(不存在){/if}</div>
	</div>
</div>
	{/foreach}
{/if}
{template:foot}












