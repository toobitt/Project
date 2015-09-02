<?php 
/* $Id: channel_form.php 22479 2013-06-27 08:55:32Z lijiaying $ */
?>
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{template:head}
{css:ad_style}
{css:style}
{js:new_live/channel}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
window.globalData = {
	client_logo: {code}echo json_encode($client_logo);{/code}
};	
</script>
<script type="text/javascript">
$(function(){
	hg_stream_name_order('stream_box');
	var is_live = "{$is_sys}";
	
	if (is_live == 1)
	{
		$('input').attr('readonly', 'readonly');
		
		$('input[name="is_audio"]').attr('disabled', 'disabled');
	}
});
</script>
{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
		
		if (!$formdata['id'])
		{
			$action = 'create';
		}
	{/code}
{/if}
<div class="ad_middle">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{$optext}频道</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">频道名称：</span>
					<input type="text" name="name" value="{$name}" style="width:192px"/>
					<font class="important">必填</font>
				</div>
				<div class="form_ul_div">	
					<span class="title">描述：</span>
					{template:form/textarea,brief,$brief}
				</div>
				<div class="form_ul_div clear">
					<span class="title">长形台标：</span>
					<span class="file_input s" style="float:left;">选择文件</span>
					<span style="float:right;">
						{if $logo_rectangle}<img width="80" height="30" src="{$logo_rectangle['host']}/{$logo_rectangle['dir']}/{$logo_rectangle['filepath']}/{$logo_rectangle['filename']}" />{/if}
					</span>
					<input name="logo_rectangle" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">台标设置：</span>
					<div id="client_logo">
						<div class="client_logo">
							{if $client_logo}
							{foreach $client_logo AS $k => $v}
							<div class="client_logo_item" id="client_logo_item{$v['appid']}" data-appid="{$v['appid']}">
								<p><img width="40" height="40" src="{$v['host']}/{$v['dir']}/{$v['filepath']}/{$v['filename']}" /></p>
								<p>{$v['appname']}</p>
								<span class="client_logo_delete">x</span>
								<input type="hidden" name="_appid[{$v['appid']}]" value="{$v['appid']}" />
								<input type="hidden" name="_appname[{$v['appid']}]" value="{$v['appname']}" />
							</div>
							{/foreach}
							{/if}
							<div class="client_logo_item client_logo_item_add">
								<p></p>
								<p>更多客户端</p>
							</div>
						</div>
						
						<div class="client_all_list">
							<span class="client_all_list_pointer"></span>
							<div class="client_all_list_con"></div>
						</div>
					</div>
				</div>
			</li>
			<!--
<li class="i">
				<div class="form_ul_div clear">
					<span class="title">信号设置：</span>
					<label>
						<input name="is_url" type="checkbox" value=1 class="n-h"
							{if $is_url}
								checked="checked"
							{/if}
						/>
						<span class="s">允许添加信号流</span>
					</label>
					<label>
						<input name="is_mobile_phone" type="checkbox" value=1 class="n-h"
							{if $is_mobile_phone}
								checked="checked"
							{/if}
						/>
						<span class="s">允许添加手机流</span>
					</label>
				</div>
			</li>
-->
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">频道截图：</span>
					<span style="margin-left: 10px;">host</span>
					<input name="snap_host" type="text" value="{$snap['host']}" style="width:200px;margin-left: 19px;" />
					<span style="margin-left: 10px;">dir</span>
					<input name="snap_dir" type="text" value="{$snap['dir']}" style="width:200px;margin-left: 34px;" />
				</div>
				<div class="form_ul_div clear">
					<span class="title"></span>
					<span style="margin-left: 10px;">filepath</span>
					<input name="snap_filepath" type="text" value="{$snap['filepath']}" style="width:200px;" />
					<span style="margin-left: 10px;">filename</span>
					<input name="snap_filename" type="text" value="{$snap['filename']}" style="width:200px;" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">信号设置：</span>
					<div class="stream_box" id="stream_box">
					{if $id && $channel_stream}
						{foreach $channel_stream AS $vv}
						<div class="stream">
							<span class="s_left" onclick="hg_stream_operate(this, 1);"></span>
							<span style="margin-left: 10px;">信号流</span>
							<input name="url[]" type="text" value="{$vv['url']}" style="width:170px;" />
							<span style="margin-left: 10px;">网页</span>
							<input name="output_url[]" type="text" value="{$vv['output_url']}" style="width:70px;" />
							<span>手机</span>
							<input name="m3u8[]" type="text" value="{$vv['m3u8']}" style="width:70px;" />
							<span>时移</span>
							<input name="timeshift_url[]" type="text" value="{$vv['timeshift_url']}" style="width:70px;" />
							<!--
<span>码流</span>
							<input name="bitrate[]" type="text" value="{$vv['bitrate']}" style="width:50px;" />
-->
							<span name="delete_submit[]" class="s_right {if $core_count == 1}display{/if}" onclick="hg_stream_operate(this, 0);"></span>
							<input type="hidden" name="stream_id[]" value="{$vv['id']}" />
						</div>
						{/foreach}
					{else}
						{template:unit/stream_list}
					{/if}
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">录制流：</span>
					<input type="text" name="record_uri" style="height:18px;width:300px;" value="{$record_uri}"
					/>
					
					<input name="can_record" type="checkbox" value="1" class="n-h" title="允许录制"
							{if $can_record}
								checked="checked"
							{/if}
						/>
					<span class="s"></span>
					<font class="important">若填写，则录制时此流地址优先</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">音频设置：</span>
					<label>
						<input name="is_audio" type="checkbox" value=1 class="n-h" _onclick="hg_set_logo_audio(this);"
							{if $is_audio}
								checked="checked"
							{/if}
						/>
						<span class="s">纯音频</span>
					</label>
				</div>
			</li>
			
			<li class="i">
				<div class="form_ul_div">
					<span class="title">时移：</span>
					<input type="text" name="time_shift" style="height:18px;width:49px;text-align:center;" 
						{if !$id}
							value="24"
						{else}
							value="{$time_shift}"
						{/if}
					/>
					<span class="s">小时</span>
					<font class="important">时移时间(最大{$_configs['max_time_shift']}小时)</font>
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" id="channel_id" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div id="tmp_client">
	
</div>
<div id="tmp_stream_box" class="display">
	{template:unit/stream_list}
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}