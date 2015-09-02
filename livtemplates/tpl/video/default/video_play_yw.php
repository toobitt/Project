{code}
$videourl = $formdata['streaming_media'];
{/code}
{if $videourl}
<object type="application/x-shockwave-flash" data="http://vblog.ywcity.cn/res/swf/vod_player.swf" width="500" height="350" id="tvie_flash_players" style="visibility: visible; ">
<param name="allowFullScreen" value="true"/>
<param name="allowScriptAccess" value="always"/>
<param name="bgcolor" value="#000000"/>
<param name="quality" value="high"/>
<param name="wmode" value="transparent"/>
<param name="flashvars" value="autoStart=true&id=0&datarate=1&site=flashdiv&ws=false&mode=SimpleVOD&autostart=true&days=undefined&starttime=0&endtime=0&cdnurlsuffixenable=undefined&cid=0&fill=tvie_flash_players&width=500&height=350&quality=high&flashver=9.0.115&flvplayer=http://vblog.ywcity.cn/res/swf/vod_player.swf&channel_id=0&fileurl={$videourl}&dar=1.5"/>
</object>
{else}
<div>视频正在转码中，无法预览</div>
{/if}