{code}
$videourl = $formdata['streaming_media'];
/*$videourl = str_replace(array('http://218.108.132.13/', 'http://video.hcrt.cn/'), '', $videourl);*/
{/code}
<script type="text/javascript" src="http://video.hcrt.cn/flash-player/swfobject.js"></script>
<script type="text/javascript" src="http://video.hcrt.cn/flash-player/tvieplayer.js"></script>

{if $videourl}
<div id="video_preview">{$videourl}</div>
{else}
<div>视频正在转码中，无法预览</div>
{/if}