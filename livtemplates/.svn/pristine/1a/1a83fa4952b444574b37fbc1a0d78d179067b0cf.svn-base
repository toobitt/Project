{template:head}
{template:head/nav}
{css:mms_control_list}
{css:tab_btn}
<script type="text/javascript">
	var gControllor = {};
	gControllor.change = function(name,url,data)
	{
		var data = data.split('#');
		id = data[0];
		is_live = data[1];
		program = data[3]+' - '+ data[4] + ' ' +data[2];
		$('#name').html(name);
		$('#program').html(program);
		if(is_live == 1)
		{
			$('#is_live').html('切播');
			var run = "./run.php?mid=" + gMid + "&a=form{$_ext_link}&id=" + id;
			$('#live_contral').attr({href:run,style:'cursor:default'});
			$('#live_contral_in').attr({href:run,style:'cursor:pointer'});
		}
		else
		{
			$('#is_live').html('');
			$('#live_contral').removeAttr('href');
			$('#live_contral_in').removeAttr('href');
		}

		document.getElementById('pri_big_colorollor').play(name, url, drmUrl);
	}
</script>
<h2 class="title_bg">直播控制-电视墙
{template:menu/btn_menu}
</h2>

<ul class="live_list">
{if $mms_control_vod_list}
	<li class="big_flv" id="big_flv">
			<div class="flv_box" id="flv_play">
				<object class="obj_pri_small" id="pri_big_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_control.swf?11112312" width="378" height="284">
					<param name="movie" value="{$RESOURCE_URL}swf/mms_control.swf?11112312"/>
					<param name="allowscriptaccess" value="always">
					<param name="wmode" value="transparent">
					<param name="flashvars" value="streamName={$mms_control_vod_list[0]['name']}&streamUrl={$mms_control_vod_list[0]['down_stream_url'][0]}&connectName=synTime_{code}echo TIMENOW;{/code}&keepAliveUrl=keep_alive.php?access_token={$_user['token']}&clientCount={code} echo count($mms_control_vod_list);{/code}&drmUrl={if $mms_control_vod_list[0]['drm']}{$_configs['antileech']}{/if}">
				</object>
			</div>
		<div class="channel_info">
			<a id="live_contral_in" href="{if $mms_control_vod_list[0]['is_live']}run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&id={$mms_control_vod_list[0]['id']}{else}###" style="cursor:default;{/if}"><span  style="color:#3F8FD8;text-decoration:underline;text-shadow: 0 0 1px black;" id="is_live">切播</span></a>
			<span id="program" class="program_title overflow">{$mms_control_vod_list[0]['start_time']} - {$mms_control_vod_list[0]['end_time']}      {$mms_control_vod_list[0]['current']}</span>
			
		</div>
		<div class="program_video_bg"></div>
	</li>
	{foreach $mms_control_vod_list as $k => $v}
	<li>
		<object id="pri_small_colorollor" class="obj_pri_small" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_player.swf?11" width="184" height="138">
			<param name="movie" value="{$RESOURCE_URL}swf/mms_player.swf?11"/>
			<param name="allowscriptaccess" value="always">
			<param name="wmode" value="transparent">
			<param name="flashvars" value="mute=true&streamName={$v['name']}&streamUrl={$v['down_stream_url'][0]}&connectName=synTime_{code}echo TIMENOW;{/code}&connectIndex={code} echo $k+1;{/code}&jsNameSpace=gControllor&data={$v['id']}#{$v['is_live']}#{$v['current']}#{$v['start_time']}#{$v['end_time']}&drmUrl={if $v['drm']}{$_configs['antileech']}{/if}">
		</object>
	</li>
	{/foreach}
{/if}
</ul>
{template:foot}
