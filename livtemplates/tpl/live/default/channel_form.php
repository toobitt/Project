<?php 
/* $Id: channel_form.php 31542 2014-04-10 08:30:18Z wangleyuan $ */
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
{code}
	$server_source = array(
		'class' => 'down_list i',
		'show' => 'item_shows_',
		'width' => 100,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
		'onclick' => 'hg_get_stream_count()',
	);
	
	$default_id = 0;
	foreach ($server_info AS $v)
	{
		if ($formdata['id'])
		{
			$server[$v['id']] = $v['name'];
		}
		elseif ($v['status'])
		{
			$server[$v['id']] = $v['name'];
			$default_id = $v['id'];
		}
		$_serverinfo[$v['id']] = array('host'=>$v['host'],'output_dir'=>$v['output_dir']);
	}
	$server_id = $server_id ? $server_id : $default_id;
	
{/code}
<script type="text/javascript">
window.globalData = {
	client_logo: {code}echo json_encode($client_logo);{/code}
};	
</script>
<script type="text/javascript">
if (!gChannelId)
{
	$(function(){
		hg_get_stream_count();
	});
}

$(function(){
	hg_stream_name_order('stream_box');
});

var gServerInfo = {code}echo json_encode($_serverinfo){/code};
</script>
<script type="text/javascript">
$(function($){
	var MC = $('#editform');
	MC.on('click' , 'input[name="is_push"]' , function(){				/*事件绑定*/
		getinfo();
	});

	MC.on('blur' , 'input[name="stream_name[]"]' , function(event){
		getinfo();	
	});

	MC.on('blur' , 'input[name="code"]' , function(){
		getinfo();	
	});
	
	MC.on('click' , '.down_list li' , function(){
		getinfo();	
	});
	
	function getinfo(){													/*事件处理*/
		var select = $('input[name="is_push"]').prop('checked'),
			code = MC.find('input[name="code"]').val(),
			obj = MC.find('.stream');
			server = MC.find('input[name="server_id"]').val(),
			sign = obj.map(function(){
				return $(this).find('input[name="stream_name[]"]').val();
			}).get();
		$.each(sign , function(key , value){
			var url = getsource(code , server , value);	
			if(select  && code && sign != '' ){
				MC.find('input[name="url[]"]:eq('+ key +')').val(url);
			}else{
				MC.find('input[name="url[]"]:eq('+ key +')').val('');
			}
		})
	};
	
	function getsource(code , server , sign){							/*地址来源编辑器*/
		var output_dir = gServerInfo[server]['output_dir'];
		if(output_dir.charAt(output_dir.length - 1) === '/'){
			output_dir = output_dir;
		}else{
			output_dir = output_dir + '/';
		}
		if(sign == ''){
			return;
		}
		var source = 'rtmp://' + gServerInfo[server]['host'] + '/' + output_dir + code + '_' + sign;
		return source;
	};
})
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
					<span class="title">台号：</span>
					<input style="width:85px;" type="text" name="code" value="{$code}" {if $type == 'tvie'}readonly="readonly"{/if} />
					<font class="important">必填，包含英文、数字</font>
				</div>
				<div class="form_ul_div clear">
					<span class="title">长形台标：</span>
					<span class="file_input s" style="float:left;">选择文件</span>
					<span style="float:right;">
						{if $logo_rectangle_url}<img width="80" height="30" src="{$logo_rectangle_url}" />{/if}
					</span>
					<input name="logo_rectangle" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
				</div>
				<!--
<div class="form_ul_div clear">
					<span class="title">方形台标：</span>
					<span class="file_input s" style="float:left;">选择文件</span>
					<input name="logo_square" type="file" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
					<span style="float:right;">
						{if $logo_square_url}<img width="30" height="30" src="{$logo_square_url}" />{/if}
					</span>
				</div>
-->
			</li>
			<!--
			<div class="client_logo_box">
						<span class="client_name">{$v['appname']}：</span>
						<span class="file_input s" style="float:left;">选择文件</span>
						<input name="client_logo[{$v['appid']}]" type="file" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
						<span style="margin-left: 200px;">
							<img width="30" height="30" src="{$v['host']}/{$v['dir']}/{$v['filepath']}/{$v['filename']}" />
						</span>
						<input type="hidden" name="_appid[{$v['appid']}]" value="{$v['appid']}" />
						<input type="hidden" name="_appname[{$v['appid']}]" value="{$v['appname']}" />
						<span class="s_right" onclick="hg_unset_client_logo(this);" style="display: inline;"></span>
						</div>	
			-->
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
			
		{if !empty($server_info)}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">服务器：</span>
				{if !$id}
					{template:form/search_source,server_id,$server_id,$server,$server_source}
				{else}
					<div class="down_list i" style="width:100px">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle">
							<a><em></em><label class="overflow">{$server[$server_id]}</label></a>
						</span>
					</div>
					<input type="hidden" name="server_id" value="{$server_id}" id="server_id" />
				{/if}
					<span id="over_count" style="margin-top: 4px;float: right;color: #bebebe;">{if $formdata['id']}剩余 {$over_count} 条{/if}</span>
				</div>
			</li>
		{/if}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">信号类型：</span>
					<label>
						<input name="is_push" type="checkbox" value=1 class="n-h"
							{if $is_push}
								checked="checked"
							{/if}
							{if $formdata['id']}
								disabled="disabled"
							{/if}
						/>
						<span class="s">推送</span>
					</label>
					<font class="important">不选则是 拉取</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">信号流：</span>
					<div class="stream_box" id="stream_box">
					{if $id && $channel_stream}
						{foreach $channel_stream AS $kk =>$vv}
						<div class="stream">
							<span class="s_left" onclick="hg_stream_operate(this, 1);"></span>
							<span style="margin-left: 10px;">输出标识</span>
							<input disabled="disabled" type="text" value="{$vv['stream_name']}" style="width:30px;" />
							<input name="stream_name[]" type="hidden" value="{$vv['stream_name']}" />
							<span>来源地址</span>
							<input name="url[]" type="text" value="{$vv['url']}" style="width:200px;" />

<span>码流</span>
							<input name="bitrate[]" type="text" value="{$vv['bitrate']}" style="width:50px;" />
							<span>默认</span><input name="is_default" type="radio" value="{$kk}"{if $vv['is_default']} checked="checked"{/if} title="默认流" />
							<span name="delete_submit[]" class="s_right {if $core_count == 1}display{/if}" onclick="hg_stream_operate(this, 0);"></span>
							<input type="hidden" name="stream_id[]" value="{$vv['id']}" />
						</div>
						{/foreach}
					{else}
						{template:unit/stream_list,,1}
					{/if}
					</div>
				</div>
			</li>
			<!--
			<li class="i">
				<div class="form_ul_div">
					<span class="title">录制流：</span>
					<input type="text" name="record_uri" style="height:18px;width:300px;" value="{$record_uri}"
					/>
					<span class="s"></span>
					<font class="important">若填写，则录制时此流地址优先</font>
				</div>
			</li>
		-->
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
				<!--
<div class="form_ul_div clear" style="margin-left: 69px;{if !$is_audio} display: none; {/if}" id="logo_audio_box">
					<span class="title">音频台标：</span>
					<span class="file_input s" style="float:left;">选择文件</span>
					<input id="logo_audio" name="logo_audio" type="file" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
					<span style="float:right;">
						{if $logo_audio_url}<img width="30" height="30" src="{$logo_audio_url}" />{/if}
					</span>
				</div>
-->
			</li>
			{if $_configs[schedule_control_wowza][is_wowza]}
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">播控设置：</span>
					<label>
						<input name="is_control" type="checkbox" value=1 class="n-h"
							{if $is_control}
								checked="checked"
							{/if}
							{if $type == 'tvie'}
								disabled="disabled"
							{/if}
						/>
						<span class="s">允许播控</span>
						<!--
						{code}
							$wowzahost = str_replace(":8086", "", $_configs[schedule_control_wowza]['host']);
						{/code}
						{if $schedule_control['control']['output_id']}pull rtmp://{$wowzahost}/input/{$schedule_control['control']['output_id']}.output name={$code}_{$main_stream_name} static;{/if}
						-->
					</label>
				</div>
			</li>
			{/if}
			<!--
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">输出设置：</span>
					<label>
						<input name="is_mobile_phone" type="checkbox" value=1 class="n-h"
							{if $is_mobile_phone || !$id}
								checked="checked"
							{/if}
						/>
						<span class="s">允许打开手机流</span>
					</label>
					<font class="important">开启后将输出m3u8流</font>
				</div>
			</li>

			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">延时设置：</span>
					<label>
						<input name="is_delay" type="checkbox" value=1 class="n-h" onclick="hg_is_delay(this);"
							{if $delay}
								checked="checked"
							{/if}
							{if $type == 'tvie'}
								disabled="disabled"
							{/if}
						/>
						<span class="s">允许延时</span>
					</label>
				</div>
				<div id="delay_box" class="form_ul_div clear" {if !$delay} style="display:none;" {/if}>
					<span class="title"></span>
					<input type="text" name="delay" value="{$delay}" style="height:18px;width:49px;text-align:center;" 
						{if !$delay}
							disabled="disabled"
						{/if}
					/>
					<span class="s">秒</span>
					<font class="important">延时时间(最大{$_configs['max_delay']}秒)</font>
					<span class="s" style="color:red;display:none;position:relative;left: 10px;top:0;margin-left:10px;">改变延时时间，流将被重启！</span>
				</div>
			</li>-->
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
					<span class="s" style="color:red;display:none;position:relative;left: 10px;top:0;margin-left:10px;">改变时移时间，流将被重启！</span>
				</div>
			</li>
			<!--
<li class="i">
				<div class="form_ul_div clear">
					<span class="title">时间偏差：</span>
					<input type="text" name="record_time_diff" value="{$record_time_diff}" style="height:18px;width:49px;text-align:center;" />
					<span class="s">秒</span>
					<font class="important">录制节目时间偏差设置&nbsp;(±30秒)</font>
				</div>
			</li>
			
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">防盗链：</span>
					<label>
						<input type="checkbox" name="drm" value=1  class="n-h" 
						{if $drm}
							checked="checked"
						{/if} 
						/>
						<span>开启防盗链</span>
					</label>
				</div>
			</li>

			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">发布至：</span>
            		{template:unit/publish, 1, $formdata['column_id']}
		             <script>
		             jQuery(function($){
		                $('#publish-1').css('margin', '10px auto').commonPublish({
		                    column : 2,
		                    maxcolumn : 2,
		                    height : 224,
		                    absolute : false
		                });
		             });
             		 </script>
			    </div>
			</li>
-->
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