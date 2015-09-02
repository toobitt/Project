<?php 
/* $Id: channel_mms_chg_plan_list.php 9972 2012-07-12 07:22:11Z lijiaying $ */
?>
{template:head}
{css:mms_style}
{css:tab_btn}
{css:mark_style}
{js:vod_video_edit}
{js:upload}
{js:channel_chg_plan}
{css:type_source}
{css:jPaginate_style}
{js:jquery.paginate}
<script type="text/javascript">
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
		
		for(var j = 0;j<$('#chg_box .input_div').length;j++)
		{
			$($('#chg_box .input_div')[j]).find('.hidden_id').val(obj['ids'][j]);
			$($('#chg_box .input_div')[j]).find('.epg_id').val(obj['epg_ids'][j]);
			$('input[name^="hidden_temp"]').val('');
		}
	}
}

function hg_chg_plan_preview()
{
	$('#chg_vodplayer').toggle();
}
function hg_chg_flv_close()
{
	$('#chg_vodplayer').hide();
}
</script>

{code}
	$channel_name = $list['channel_name'];
	$channel_id = $list['channel_id'];
	$dates = $list['dates_api'];
	$uri = $list['uri'];
	$audio_only = $list['audio_only'];
	unset($list['channel_name'], $list['channel_id']);
	$change_item_attr = array(
		'disabled' => ' disabled="disabled"',
		'disabled_cls' => ' chg_disabled none',
	);
{/code}
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
<div class="wrap_conter clear" >
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
		<div class="chg_flv" id="chg_vodplayer">
			<object id="pri_small_colorollor" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/mms_control.swf?11112501" width="245" height="184">
				<param name="movie" value="{$RESOURCE_URL}swf/mms_control.swf?11112501"/>
				<param name="allowscriptaccess" value="always">
				<param name="bgcolor" value="#000000">
				<param name="allowFullScreen" value="true">
				<param name="wmode" value="transparent">
				<param name="flashvars" value="streamUrl={$uri}&streamName={$channel_name}&keepAliveUrl=keep_alive.php?access_token={$_user['token']}&clientCount=0">
			</object>
			<span title="关闭" onclick="hg_chg_flv_close();" class="flv_close">X</span>
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
				<input type="hidden" name="a" value="create" id="action" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="channel_id" id="channel_id" value="{$channel_id}" />
				<input type="hidden" id="chg_date" name="chg_date" value="{$dates}" />
				<input type="hidden" id="audio_only" name="audio_only" value="{$audio_only}" />
				<input type="button" value="复制到" onclick="hg_copy_show_plan('date_show_copy_plan','dates');" class="button_4" style="margin-left:25px;" />
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