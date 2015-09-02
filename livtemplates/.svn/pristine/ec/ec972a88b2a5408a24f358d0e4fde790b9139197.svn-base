<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>广告预览</title>
</head>
<body>
<div>
{code}
	$pos_type = $formdata['pos_type'];
	$group_flag = $formdata['group_flag'];
	$currentpos = $formdata['currentpos'];
	unset($formdata['group_flag']);
	unset($formdata['pos_type']);
{/code}
{if  $pos_type==0}
	{if $group_flag == $_configs['hg_ad_flag']['vod_player_flag']}
	<object id="swfplayer" style="" type="application/x-shockwave-flash" data="" width="640" height="513">
	<param name="movie" value="http://{$_configs['App_player']['host']}/{$_configs['App_player']['dir']}vod/vod.swf">
	<param name="allowscriptaccess" value="always">
	<param name="allowFullScreen" value="true">
	<param name="wmode" value="transparent">
	<param name="flashvars" value="video={$_configs['App_player']['demovideo']}">
	</object>
	{else}
	<object type="application/x-shockwave-flash" data="http://{$_configs['App_player']['host']}/{$_configs['App_player']['dir']}live/live.swf" width="640" height="513" id="player" style="visibility: visible;">
		<param name="allowFullScreen" value="true">
		<param name="wmode" value="transparent">
		<param name="allowscriptaccess" value="always">
		<param name="flashvars" value="extend=hg_ad_preview%3Dtrue&width=540&amp;height=513&amp;channelId={$_configs['App_player']['demochannel']}">
	</object>
	{/if}
{else if $pos_type == 2}
<div hg_adbox="{$currentpos}" id="ad_{$currentpos}"></div>
{else if $pos_type == 1}
<div hg_adbox="{$currentpos}"></div>
{/if}
{if $pos_type == 2 || $pos_type == 1}
<!-- 必须载入js控制广告效果文件 -->
<script type="text/javascript">
hg_adExtendParameters = {'aid':"{$_INPUT['content_id']}"}
</script>
<script type="text/javascript" src="{$_configs['ad_access_domain']['domain']}/{$_configs['ad_access_domain']['dir']}hg_ad.js"></script>
{/if}
{if $formdata}
<dl style="line-height:24px;font-size:14px;list-style:none;margin:0px;padding:0px;">
<dt>请点击选择需要预览的广告位</dt>
{foreach $formdata as $k=>$v}
	
		{foreach $v as $kk=>$vv}
		<dd>
			<a href="run.php?mid={$_INPUT['mid']}&a=adpreview&content_id={$_INPUT['content_id']}&mtype={$_INPUT['mtype']}&pub_id={$vv}">
			[{$k}{$kk}]
			</a>
		</dd>
		{/foreach}

{/foreach}
</dl>
{else}
该广告暂未投放
{/if}
</div>
<br/>
</body>
</html>