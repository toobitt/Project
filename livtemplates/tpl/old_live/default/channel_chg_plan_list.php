<?php 
/* $Id: channel_mms_chg_plan_list.php 9972 2012-07-12 07:22:11Z lijiaying $ */
?>
{template:head}
{css:mms_control_list}
{css:mms_style}
{css:tab_btn}
{css:mark_style}
{js:vod_video_edit}
{js:upload}
{js:channel_chg_plan}
{css:type_source}
{css:jPaginate_style}
{js:jquery.paginate}

{code}
	$channel_name = $list['channel_name'];
	$channel_id = $list['channel_id'];
	$dates = $list['dates_api'];
	$uri = $list['uri'];
	$audio_only = $list['audio_only'];
	$ntpHis = $list['ntpHis'];
	$ntpYmdhis = $list['ntpYmdhis'];
	$server_id = $list['server_id'];
	unset($list['channel_name'], $list['channel_id'], $list['ntpHis'], $list['ntpYmdhis'], $list['server_id']);
	$change_item_attr = array(
		'disabled' => ' disabled="disabled"',
		'disabled_cls' => ' chg_disabled none',
	);
{/code}

<script type="text/javascript">
var gNtpHis = '{$ntpHis}',
	gNtpYmdhis = '{$ntpYmdhis}';
	gServerId = '{$server_id}';
$(function(){

	for(var i = 0; i < $('#chg_box .input_div').length; i++)
	{
		if($($('#chg_box .input_div')[i]).find('.hidden_flag').val())
		{
			sub_disabled();
		}
	}
});
function hg_channel_chg_plan_out()
{
	hg_taskCompleted(gCid);
	return hg_ajax_submit('channel_chg_plan','','','hg_return_cid');
}

function hg_return_cid(obj)
{
	var obj = obj[0];
	if(!obj)
	{
		if (obj == undefined)
		{
			return;
		}
		
		alert('所填内容不能为空，请重填！');
		return;
	}
	else if(obj['ids'])
	{
		$('#channel_chg_plan_source').prependTo('#div_box_0').hide();
		$('#sub').attr('class','button_4 button_none');
		$('#sub').attr('disabled','disabled');
		$('#chg_box').find('.input_div').css('background','#f6f6f8');
		
		for(var j = 0; j < $('#chg_box .edit_flag').length; j++)
		{
			$($('#chg_box .edit_flag')[j]).find('.hidden_id').val(obj['ids'][j]);
			$($('#chg_box .edit_flag')[j]).find('.epg_id').val(obj['epg_ids'][j]);
			$('input[name^="hidden_temp"]').val('');
		}
	}
}

function hg_chg_plan_preview()
{
	$('#chg_vodplayer').toggle();
	setSwfPlay('flashBox', '{$uri}', '245', '184', 100, 'flashBox');
	hg_resize_nodeFrame();
}

function hg_chg_flv_close()
{
	$('#chg_vodplayer').hide();
	hg_resize_nodeFrame();
}
</script>

<script type="text/javascript">
	function setSwfPlay(flashId, url ,width, height, mute, objectId)
	{
		var swfVersionStr = "11.1.0";
	
		var xiSwfUrlStr = RESOURCE_URL+"swf/playerProductInstall.swf?20120910111";
		var flashvars = {objectId: objectId, namespace: "player", url: url, mute: mute};
		var params = {};
		params.quality = "high";
		params.bgcolor = "#fff";
		params.allowscriptaccess = "sameDomain";
		params.allowfullscreen = "true";
		params.wmode = "transparent";
		var attributes = {};
		attributes.id = flashId+'_1';
		attributes.name = flashId+'_1';
		attributes.align = "middle";
		swfobject.embedSWF(
		   RESOURCE_URL+"swf/Main.swf?20120910111", flashId, 
		    width, height, 
		    swfVersionStr, xiSwfUrlStr, 
		    flashvars, params, attributes);

		swfobject.createCSS("#"+flashId, "display:block;text-align:left;");
	
	}
</script>
<script language="javascript" type="text/javascript">
	function switch_channel()
	{
		$("#channel_hid").slideToggle();
		hg_resize_nodeFrame();
	}
	$(function(){
		var vid = $('#hg_channel').val();
		$('#channel_show_'+vid).css({'background':'#EFEFEF','border':'1px solid #CCC'});
   });
</script>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <div class="button_op">
	<a class="button_4" onclick="switch_channel();"><strong>切换频道</strong></a>
	</div>
</div>

<div class="wrap_conter clear" >
	<div id="channel_hid" class="channel_hid" style="{if !isset($_INPUT['nav'])}display:none;{/if}">
		<div class="hg_program_list"></div>
		<ul class="hg_program_ul">
		{foreach $channel_info AS $key => $value}
			<li id="channel_show_{$value['id']}"  class="channel_logo f_l"><a href="run.php?mid={$_INPUT['mid']}&menuid=223&channel_id={$value['id']}{$_ext_link}">
			{if $value['logo_url']}
				<img style="width:113px;height:43px;" id="cha_logo" src="{$value['logo_url']}" title="{$value['name']}" />
			{else}
				{$value['name']}
			{/if}</a></li>
			<input id="cha_{$value['id']}" type="hidden" name="channel_id" value="{$value['id']}" />
		{/foreach}
		</ul>
		<input id="hg_channel" type="hidden" name="hg_channel" value="{$_INPUT['channel_id']}" />
	</div>
	<h2 class="title_bg">
	{code}
		$source = array('extra' => '&channel_id='.$_INPUT['channel_id']);
	{/code}
		{template:menu/btn_menu,'','','',$source}
		{$channel_name} 串联单 &nbsp;
		<span id="show_date_plan">{code} echo date('Y/m/d', strtotime($dates));{/code}</span>
		<a href="javascript:void(0);" onclick="hg_chg_plan_preview();" style="margin-left:103px;">查看输出流</a>
	</h2>
	<form name="channel_chg_plan" id="channel_chg_plan" action="./run.php?mid={$_INPUT['mid']}" method="POST" onsubmit="return hg_channel_chg_plan_out();">
	<div class="chg_plan f_l" {if !$list[0] && strtotime($dates) < strtotime(date('Y-m-d'))}style="min-height:1px;border-top:0px;"{/if} >
		<div id="chg_box">
		{if $list[0]}
			{foreach $list[0] as $k => $v}
				{if $v['id']}
					{if $v['plan_status']}
						{template:unit/change_item,,,,$change_item_attr}
					{else}
						{template:unit/change_item}
					{/if}
				{else}
					{if !$v['plan_status'] && strtotime($dates) > strtotime(date('Y-m-d'))}
						<p onclick="add_plan_p(this);" onmouseout="plan_bg_color(this,1);" onmouseover="plan_bg_color(this,0);" name="p" style="height:{code} if($v['empty_toff'] > 7200){ echo 40;}else{ echo $v['empty_toff']*30/3600;} {/code}px;width:100%;border-bottom:1px solid #D2D6D9;"></p>
					{else}
						<p name="p" style="height:{code} if($v['empty_toff'] > 7200){ echo 40;}else{ echo $v['empty_toff']*30/3600;} {/code}px;width:100%;border-bottom:1px solid #D2D6D9;"></p>
					{/if}
				{/if}
			{/foreach}
		{/if}
		</div>
		{if strtotime($dates) >= strtotime(date('Y-m-d'))}
		<div id="div_box_0" title="点击添加" class="input_div" onclick="hg_input_div_bgcolor(this);">
			<span style="display:block;cursor:pointer;width:500px;height:41px;" onclick="hg_add_plan(null),hg_last_time_checked(this)">
				<span id="add_div" class="chg_plan_left f_l"></span>
			</span>
		</div>
		{/if}
		<div id="channel_chg_plan_source" class="type_con_b type_content_hide clear"></div>
	</div>
	<div class="f_l">
		<div class="chg_flv" id="chg_vodplayer" style="border-radius: 3px;box-shadow: 0 0 10px black;margin-bottom: 10px;background:#000;">
			<div id="flashBox"></div>
		<!--
	<object id="pri_small_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_control.swf?11112501" width="245" height="184">
				<param name="movie" value="{$RESOURCE_URL}swf/mms_control.swf?11112501"/>
				<param name="allowscriptaccess" value="always">
				<param name="bgcolor" value="#000000">
				<param name="allowFullScreen" value="true">
				<param name="wmode" value="transparent">
				<param name="flashvars" value="streamUrl={$uri}&streamName={$channel_name}&keepAliveUrl=keep_alive.php?access_token={$_user['token']}&clientCount=0">
			</object>
-->
			<span title="关闭" onclick="hg_chg_flv_close();" class="flv_close" style="top:-184px;">X</span>
		</div>
		<div class="time_control f_l" >
			<div id="date_show_plan">
				{code}
					unset($v);
					$date_source = array(
						'default' => 1,
						'class' => 'date_div',
						'id' => 'date_show_plan',
						'extra_onclick' => "switch_date_plan(" . $_INPUT['mid'] . "," . $channel_id . ");",
					);
				{/code}
				{template:form/hg_date,dates,$dates,,$date_source}
			</div>
			<div class="submit">
				<input type="submit" name="sub" value="保存修改" id="sub" class="button_4 button_none" disabled="disabled" />
				<input type="hidden" name="a" value="edit" id="action" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="channel_id" id="channel_id" value="{$channel_id}" />
				<input type="hidden" id="chg_date" name="chg_date" value="{$dates}" />
				<input type="hidden" id="audio_only" name="audio_only" value="{$audio_only}" />
				<input type="button" value="复制到" onclick="hg_copy_show_plan('date_show_copy_plan','dates');" class="button_4" />
				<input type="button" value="生成节目单" class="button_4" onclick="hg_chg2program({$channel_id});" />
			</div>
			{code}
				$copy_source = array(
					'default' => 1,
					'class' => 'copy_date',
					'id' => 'date_show_copy_plan',
					'extra_onclick' => 'hg_check_copy_plan("copy_dates","dates");',
				);
			{/code}
			{template:form/hg_date,copy_dates,$dates,,$copy_source}
		</div>
	</div>
	</form>
</div> 
<div id="change_item_html" style="display:none">
{template:unit/change_item}
</div>

{template:foot}