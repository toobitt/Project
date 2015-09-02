<script type="text/javascript">
  var tp_id = "{$formdata['id']}";
  var vs = hg_get_cookie('channel_info_box');
  var vi = hg_get_cookie('output_stream');
  var vc = hg_get_cookie('stream_uri');
  $(document).ready(function(){
	$('#channel_info_box').css('display',vs?vs:'block');
	$('#output_stream').css('display',vi?vi:'block');
	$('#stream_uri').css('display',vc?vc:'block');
  });
</script>
<script type="text/javascript">
	function setSwfPlay(flashId, url ,width, height, mute, objectId)
	{
		var swfVersionStr = "11.1.0";
	
		var xiSwfUrlStr = RESOURCE_URL+"swf/playerProductInstall.swf?20120910";
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
		   RESOURCE_URL+"swf/Main.swf?20120910", flashId, 
		    width, height, 
		    swfVersionStr, xiSwfUrlStr, 
		    flashvars, params, attributes);

		swfobject.createCSS("#"+flashId, "display:block;text-align:left;");
	
	}
	if (!ISIOS && !ISANDROID)
	{
		$(function(){
			setSwfPlay('flashBox', "{$formdata['channel_stream'][0]['output_url']}", '400', '300', 100, 'flashBox');
		});
	}
	
	function hg_set_url(obj, flashId)
	{
		var url = $(obj).attr('_url');
		var name = $(obj).attr('_name');
		var stream_name = $(obj).attr('_stream_name');
		if (url)
		{
			$('#play_title').text(name + ' [' + stream_name + ']');
			setUrl(flashId, url);
		}
	}
	
	function setUrl(flashId, url)
	{
		document.getElementById(flashId).setUrl(url);
	}
	
</script>
<div class="info clear vider_s" id="vodplayer_{$formdata['id']}" style="border-radius: 3px;box-shadow: 0 0 10px black;margin-bottom: 10px;background:#000;">
	<font id="play_title" style="padding: 0px 5px;font-size:10px;color: #fff;background: rgba(121, 121, 121, 0.5);position: absolute;top: 0;height: 27px;line-height: 27px;">{$formdata['name']} [{$formdata['channel_stream'][0]['stream_name']}]</font>
	<div id="flashBox">
	{if (ISIOS || ISANDROID) && $formdata['open_ts']}
	{code}
		$msu8stream = @array_values($formdata['ts_uri'])
	{/code}
		{if $msu8stream}
		<video id="phonehtmt5player" src="{$msu8stream[0]}" width="400" height="300" controls="controls" autoplay="autoplay"></video>
		{/if}
	{/if}
	</div>
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<div id="video_opration" class="clear common-list" style="border:0;height:auto">
	    <div class="common-opration-list">
	         <a class="button_4 anchor" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
	         <a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" title="" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a>
	    </div>
	
		<div class="common-opration-list">
	         <a class="button_4 anchor" href="./run.php?mid=365&menuid=385&channel_id={$formdata['id']}&infrm=1">节目单</a>
	         {if $formdata['is_control']}
	         <a class="button_4 anchor" href="./run.php?mid=375&channel_id={$formdata['id']}&infrm=1">串联单</a>
	         {else}
	         <a class="button_4 button_none" href="javascript:void();">串联单</a>
	         {/if}
		</div>
		<div class="common-opration-list">
		     <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '发布', 0);">发布</a>
	         {if $formdata['is_control']}
	         <a class="button_4 anchor" target="formwin" href="./run.php?mid=362&a=form&id={$formdata['id']}&infrm=1">播控</a>
	         {else}
	         <a class="button_4 button_none" href="javascript:void();">播控</a>
	         {/if}
		</div>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'channel_info_box')"><span title="展开\收缩"></span>频道属性</h4>
	<div id="channel_info_box" class="channel_info_box">
		<ul class="clear">
			<li><span>台号：</span>{$formdata['code']}</li>
			<li class="overflow"><span>频道名称：</span>{$formdata['name']}</li>
			<li>
				<span>流状态：</span>
					<a href="javascript:void(0);" id="a_info_{$formdata['id']}" lonclick="hg_stream_status({$formdata['id']})">
						{if $formdata['status']}
							已启动
						{else}
							未启动
						{/if}
					</a>
			</li>
			<li><span>回看时间：</span>{$formdata['time_shift']}小时</li>
			<li><span>延时时间：</span>{$formdata['delay']}秒</li>
			<li><span>所属服务器：</span>{$formdata['server_name']}</li>
			<li><span>添加人：</span>{$formdata['user_name']}</li>
			<li style="width: 180px;"><span>添加时间：</span>{$formdata['create_time']}</li>
		</ul>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'output_stream')"><span title="展开\收缩"></span>输出流</h4>
	<div id="output_stream">
		<div style="font-size:12px;border-top:1px solid #E0E0E0;">
		{foreach $formdata['channel_stream'] AS $vv}
			<p style="margin-left:10px;line-height:22px;">
				<a _url="{$vv['output_url']}" _name="{$formdata['name']}" _stream_name="{$vv['stream_name']}" title="点击预览信号" onclick="hg_set_url(this, 'flashBox');">{$vv['stream_name']}</a>
				<span title="{$vv['output_url']}" style="font-size:10px;margin-left:5px;display: inline-block;width: 340px;overflow: hidden;white-space: nowrap;">：{$vv['output_url']}</span>
			</p>
			{if $formdata['is_mobile_phone']}
			<p style="margin-left:10px;line-height:22px;">
				<span>{$vv['stream_name']}</span>
				<span title="{$vv['m3u8']}" style="font-size:10px;margin-left:5px;display: inline-block;width: 340px;overflow: hidden;white-space: nowrap;">：{$vv['m3u8']}</span>
			</p>
			{/if}
		{/foreach}
		</div>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'stream_uri')"><span title="展开\收缩"></span>信号流</h4>
	<div id="stream_uri" style="font-size:12px;border-top:1px solid #E0E0E0;">
		{foreach $formdata['channel_stream'] AS $vv}
		<p style="margin-left:10px;line-height:22px;">
			<span>{$vv['stream_name']}</span>
			<span title="{$vv['url']}" style="font-size:10px;margin-left:5px;">：{$vv['url']}</span>
		</p>
		{/foreach}
	</div>
</div>

<script>
jQuery(function($){
    $('.planlist-option-iframe').click(function(){
        top.$('#livwinarea').trigger('iopen', [{
            src : $(this).attr('href'),
            gMid: gMid
        }]);
        return false;
    });
});
</script>