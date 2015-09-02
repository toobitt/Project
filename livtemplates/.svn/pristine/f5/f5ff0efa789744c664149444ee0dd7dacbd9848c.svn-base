{template:head}
{template:head/nav}
{css:mms_control_list}
{css:tab_btn}
{js:mms_control}
{css:mms_control}
<script type="text/javascript">
	$(function(){
		setSwfPlay('flashContent', '{$mms_control_vod_list[0]["down_stream_url"][0]}', '378', '284', 0, 'flashContent');	
	});
</script>
<h2 class="title_bg">直播控制-电视墙
{template:menu/btn_menu}
</h2>
{code}
/*hg_pre($mms_control_vod_list);*/
{/code}
<ul class="live_list">
{if $mms_control_vod_list}
	<li class="big_flv" id="big_flv" style="background-color:black;">
			<div class="flv_box" id="flv_play">
				<div id="flashContent"></div>
				<span id="channelMainName" class="channelMainName">{$mms_control_vod_list[0]['name']}</span>
			</div>
		<div class="channel_info">
			<a id="liveHref" href="run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&id={$mms_control_vod_list[0]['id']}" class="isLiveButtom">切播</a>
			<span id="program" class="program_title overflow">{$mms_control_vod_list[0]['start_time']} - {$mms_control_vod_list[0]['end_time']}      {$mms_control_vod_list[0]['current']}</span>	
		</div>
	</li>
	{foreach $mms_control_vod_list as $k => $v}
	<script type="text/javascript">
		$(function(){
			setSwfPlay('outputFlashBox_{$v["id"]}', '{$v["down_stream_url"][0]}', '184', '138', 1, 'outputFlashBox_{$v["id"]}');	
		});
	</script>
	<li style="background-color:black;">
		<div id="outputFlashBox_{$v['id']}"></div>
		<span class="channelNameList">{$v['name']}</span>
		<input type="hidden" id="outputFlashBox_{$v['id']}_2" value="{$v['id']},{$v['name']},{$v['down_stream_url'][0]},{$v['start_time']},{$v['end_time']},{$v['current']}" />
	</li>
	{/foreach}
{else}
	 <li>暂无记录</li>
{/if}
</ul>
{template:foot}
