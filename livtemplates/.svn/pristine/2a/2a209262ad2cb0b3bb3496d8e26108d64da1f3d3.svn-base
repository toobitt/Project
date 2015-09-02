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
		attributes.id = flashId+'_1';
		attributes.name = flashId+'_1';
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
			setSwfPlay('flashBox', "{$formdata['out_streams_uri'][0]}", '400', '300', 100, 'flashBox');
		});
	}
	
</script>
<div class="info clear vider_s" id="vodplayer_{$formdata['id']}" style="border-radius: 3px;box-shadow: 0 0 10px black;margin-bottom: 10px;background:#000;">
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
	         {code}
			$program_link_id = array_shift(array_keys($_relate_module));
			$program_link = array($program_link_id => $_relate_module[$program_link_id]);
			unset($_relate_module[$program_link_id]);
		   {/code}
		    {foreach $program_link AS $kkk => $vvv}
		    <a class="button_4 anchor" href="./run.php?mid={$kkk}&channel_id={$formdata['id']}&menuid={$relate_menu[$kkk]}&infrm=1">{$vvv}</a>
		    {/foreach}
	    </div>
		<div class="common-opration-list">
		     {if $formdata['is_live']}
		    {foreach $_relate_module AS $kkk => $vvv}
			     <a class="button_4 anchor{if $kkk==352} planlist-option-iframe{/if}" {if $kkk==352 && !DEVELOP_MODE} style="display:none;"{/if} href="./run.php?mid={$kkk}&channel_id={$formdata['id']}&menuid={$relate_menu[$kkk]}&infrm=1">{$vvv}</a>
		    {/foreach}
			     <a class="button_4 anchor" target="mainwin" href="./run.php?mid={$formdata['relate_module_id']}&a=form&id={$formdata['id']}&audio_only={$formdata['audio_only']}&infrm=1">播控</a>
		   {else}
		    {foreach $_relate_module AS $kkk => $vvv}
			    <a class="button_4 button_none anchor" href="javascript:void();">{$vvv}</a>
		    {/foreach}
			<a class="button_4 button_none anchor" href="javascript:void();">播控</a>
		    {/if}
		</div>
		<div class="common-opration-list">
		     <a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=recommend&id={$formdata['id']}" onclick="return hg_ajax_post(this, '发布', 0);">发布</a>
		</div>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'channel_info_box')"><span title="展开\收缩"></span>频道属性</h4>
	<div id="channel_info_box" class="channel_info_box">
		<ul class="clear">
			<li><span>台号：</span>{$formdata['code']}</li>
			<li class="overflow"><span>频道名称：</span>{$formdata['name']}</li>
			<!-- <li><span>信号流：</span>{$formdata['stream_display_name']}</li> -->
			<li>
				<span>流状态：</span>
					<a href="javascript:void(0);" id="a_info_{$formdata['id']}" lonclick="hg_stream_status({$formdata['id']})">
						{if $formdata['stream_state']}
							已启动
						{else}
							未启动
						{/if}
					</a>
			</li>
			<li><span>回看时间：</span>{$formdata['save_time']}小时</li>
			<li><span>延时时间：</span>{$formdata['live_delay']}秒</li>
			<li><span>所属服务器：</span>{$formdata['server_name']}</li>
		</ul>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'output_stream')"><span title="展开\收缩"></span>输出流</h4>
	<div id="output_stream">
		<div style="font-size:12px;border-top:1px solid #E0E0E0;">
		{foreach $formdata['out_streams'] as $kk=>$vv}
			<p style="padding:0 4px;margin-left:10px;line-height:22px;" id="out_uri_{$v['id']}_{$kk}"><a  onclick="hg_stream_uri('{$kk}',{$v['id']});"href="javascript:void(0);">{$kk}</a><span title="{$vv}" style="font-size:10px;margin-left:5px;display: inline-block;width: 340px;overflow: hidden;white-space: nowrap;">：{$vv}</span>
			</p>
			<input type="hidden" id="formdata_preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
			<input type="hidden" id="formdata_code_preview_uri_{$v['id']}_{$kk}" value="{$formdata['name']}" />
			<input type="hidden" id="formdata_streamName_preview_uri_{$v['id']}_{$kk}" value="{$kk}" />
		{/foreach}
		{if $formdata['open_ts']}
			{foreach $formdata['ts_uri'] as $kk => $vv}
			<p style="padding:0 4px;margin-left:10px;line-height:22px;">
				<span>{$kk}</span><span title="{$vv}" style="font-size:10px;margin-left:5px;display: inline-block;width: 340px;overflow: hidden;white-space: nowrap;">：{$vv}</span>
			</p>
			{/foreach}
		{/if}
		</div>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'stream_uri')"><span title="展开\收缩"></span>信号流</h4>
	<div id="stream_uri" style="font-size:12px;border-top:1px solid #E0E0E0;">
		{foreach $formdata['stream_uri'] as $kk=>$vv}
		<p style="padding:0 4px;margin-left:10px;line-height:22px;" id="out_uri_{$v['id']}_{$kk}"><span title="{$vv}">{$kk}</span><span style="font-size:10px;margin-left:5px;">：{$vv}</span>
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















