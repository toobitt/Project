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
<div class="info clear vider_s" id="vodplayer_{$formdata['id']}">
	<object id="pri_small_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_control.swf?12042611" width="400" height="300">
		<param name="movie" value="{$RESOURCE_URL}swf/mms_control.swf?12042611"/>
		<param name="allowscriptaccess" value="always">
		<param name="bgcolor" value="#000000">
		<param name="allowFullScreen" value="true">
		<param name="wmode" value="transparent">
		<param name="flashvars" value="streamUrl={$formdata['out_streams_uri'][0]}&streamName={$formdata['name']} [{$formdata['out_stream_name'][0]}]&keepAliveUrl=keep_alive.php?access_token={$_user['token']}&clientCount=0&connectName={code} echo TIMENOW;{/code}&drmUrl={if $formdata['drm']}{$_configs['antileech']}{/if}">
	</object>
	<span onclick="hg_close_opration_info();" title="关闭/ALT+Q"></span>
</div>
<div class="info clear cz">
	<ul id="video_opration" class="clear" style="border:0;height:auto">
		<li class="ml_10">
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$formdata['id']}&infrm=1">编辑</a>
		</li>
		<li class="ml_10">
			<a class="button_4" onclick="return hg_ajax_post(this, '删除', 1);" title="" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$formdata['id']}">删除</a>
		</li>
		{code}
			$program_link_id = array_shift(array_keys($_relate_module));
			$program_link = array($program_link_id => $_relate_module[$program_link_id]);
			unset($_relate_module[$program_link_id]);
		{/code}
		{foreach $program_link AS $kkk => $vvv}
		<li class="ml_10">
			<a class="button_4" href="./run.php?mid={$kkk}&channel_id={$formdata['id']}&infrm=1">{$vvv}</a>
		</li>
		{/foreach}
		{if $formdata['is_live']}
		{foreach $_relate_module AS $kkk => $vvv}
		<li class="ml_10">
			<a class="button_4" href="./run.php?mid={$kkk}&channel_id={$formdata['id']}&infrm=1">{$vvv}</a>
		</li>
		{/foreach}
		<li class="ml_10"> 
			<a class="button_4" href="./run.php?mid={$formdata['relate_module_id']}&a=form&id={$formdata['id']}&infrm=1">播控</a>
		</li>
		{else}
		{foreach $_relate_module AS $kkk => $vvv}
		<li class="ml_10">
			<a class="button_4 button_none" href="javascript:void();">{$vvv}</a>
		</li>
		{/foreach}
		<li class="ml_10"> 
			<a class="button_4 button_none" href="javascript:void();">播控</a>
		</li>
		{/if}
		<li class="ml_10" style="margin-top:5px;">
			<a class="button_4" href="./run.php?mid={$_INPUT['mid']}&a=publish&id={$formdata['id']}&infrm=1">发布至</a>
		</li>
	</ul>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'channel_info_box')"><span title="展开\收缩"></span>频道属性</h4>
	<div id="channel_info_box" class="channel_info_box">
		<ul class="clear">
			<li><span>台号：</span>{$formdata['code']}</li>
			<li class="overflow"><span>频道名称：</span>{$formdata['name']}</li>
			<li><span>信号流：</span>{$formdata['stream_display_name']}</li>
			<li>
				<span>流状态：</span>
					<a href="javascript:void(0);" id="a_info_{$formdata['id']}" onclick="hg_stream_status({$formdata['id']})">
						{if $formdata['stream_state']}已启动{else}未启动{/if}
					</a>
			</li>
			<li><span>回看时间：</span>{$formdata['save_time']}小时</li>
			<li><span>延时时间：</span>{$formdata['live_delay']}分钟</li>
		</ul>
	</div>
</div>
<div class="channel_info info clear vo">
	<h4 onclick="hg_slide_up(this,'output_stream')"><span title="展开\收缩"></span>输出流</h4>
	<div id="output_stream">
		<div style="font-size:12px;border-top:1px solid #E0E0E0;">
		{foreach $formdata['out_streams'] as $kk=>$vv}
			<p style="padding:0 4px;margin-left:10px;line-height:22px;" id="out_uri_{$v['id']}_{$kk}"><a  onclick="hg_stream_uri('{$kk}',{$v['id']});"href="javascript:void(0);">{$kk}</a><span style="font-size:10px;margin-left:5px;">({$vv})</span>
			</p>
			<input type="hidden" id="formdata_preview_uri_{$v['id']}_{$kk}" value="{$vv}" />
			<input type="hidden" id="formdata_code_preview_uri_{$v['id']}_{$kk}" value="{$formdata['name']}" />
			<input type="hidden" id="formdata_streamName_preview_uri_{$v['id']}_{$kk}" value="{$kk}" />
		{/foreach}
		{if $formdata['open_ts']}
			{foreach $formdata['ts_uri'] as $kk => $vv}
			<p style="padding:0 4px;margin-left:10px;line-height:22px;">
				<span>{$kk}</span><span style="font-size:10px;margin-left:5px;">({$vv})</span>
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
		<p style="padding:0 4px;margin-left:10px;line-height:22px;" id="out_uri_{$v['id']}_{$kk}"><span>{$kk}</span><span style="font-size:10px;margin-left:5px;">({$vv})</span>
		</p>
		{/foreach}
	</div>
</div>















