<?php 
/* $Id: program_shield_list.php 19874 2013-04-24 09:52:30Z lijiaying $ */
?>
{template:head}
{css:mms_style}
{css:program_shield}
{js:program/program_shield}
{code}
//hg_pre($list);
	$channel = $list['channel_info'];
	$dates = $list['dates'];

	unset($list['channel_info'], $list['dates']);
{/code}
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
<!--
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <div class="button_op">
	<a class="button_4" onclick="switch_channel();"><strong>切换频道</strong></a>
	</div>
</div>
-->
<div class="wrap_conter clear" style="margin:0;border-radius:0;min-width:952px;">
<!--
	<div id="channel_hid" class="channel_hid" style="{if !isset($_INPUT['nav'])}display:none;{/if}">
		<div class="hg_program_list"></div>
		<ul class="hg_program_ul">
		{foreach $channel_info AS $key => $value}
			<li id="channel_show_{$value['id']}"  class="channel_logo f_l"><a href="run.php?mid={$_INPUT['mid']}&menuid=360&channel_id={$value['id']}{$_ext_link}">
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
-->
	<h2 class="title_bg">
		<span class="channel_name">
		{code}
			echo $channel['name'].'&nbsp;&nbsp;'.date('Y/m/d',strtotime($dates));
		{/code}</span>
	</h2>
	<form name="shield_form" id="shield_form" action="./run.php?mid={$_INPUT['mid']}" method="POST" onsubmit="return hg_program_shield_edit();">
	<div class="single" style="width: 56%;">
		<ul id="shield_list" {if empty($list[0]) && $dates < date('Y-m-d')} style="border:none;" {/if}>
		{if !empty($list[0])}
			{foreach $list[0] AS $v}
				{if $v['id']}
					{template:unit/program_shield_edit}
				{else}
					<p title="点击添加" name="p" class="p_bg" onclick="hg_add_shield(this,'p');" onmouseover="hg_p_bg(this,'over');" onmouseout="hg_p_bg(this,'out');"></p>
				{/if}
			{/foreach}
		{else if $dates >= date('Y-m-d')}
			{if $dates == date('Y-m-d') && date('H:i:s') > '00:00:00'}
			<p title="点击添加" name="p" class="p_bg" onclick="hg_add_shield(this,'p');" onmouseover="hg_p_bg(this,'over');" onmouseout="hg_p_bg(this,'out');"></p>
			{/if}
			{template:unit/program_shield}
		{/if}
		</ul>
		<div class="add_sub">
<!-- 			<span id="add_sub" onclick="hg_add_shield(this);">添加</span> -->
			<p title="点击添加" id="add_sub" class="p_bg {if empty($list[0]) && $dates < date('Y-m-d')} p_bg_2 {/if}" onclick="hg_add_shield(this);" onmouseover="hg_p_bg(this,'over');" onmouseout="hg_p_bg(this,'out');"></p>
		</div>
	</div>
	
	<div class="f_l">
		<div class="time_control f_l" >
			{js:hg_date}
			{css:hg_date}
			<div id="right_date">
			<div id="program_dates" class="program-date"></div>
			<script type="text/javascript">
			
			$('#right_date').hogeDate({
			        showId:'program_dates',
						valueId:'dates',
						defaultValue:'{$dates}',
						extra_click:function(event){
							location.href = location.href.replace(/dates=([0-9\-]*)/, 'dates=' + $(event.currentTarget).attr('_date'));
							return false;
						}
			      });
			</script>
			</div>
			<div class="submit">
				<input type="submit" name="sub" value="保存修改" id="sub" class="button_4 {if empty($list[0]) && $dates < date('Y-m-d')} button_none {else if !empty($list[0])} button_none {/if}" {if empty($list[0]) && $dates < date('Y-m-d')} disabled="disabled" {else if !empty($list[0])} button_none  {/if} /> 
				<input type="hidden" name="a" value="edit" id="action" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="channel_id" id="channel_id" value="{$_INPUT['channel_id']}" />
				<input type="hidden" name="shield_toff" id="shield_toff" value="{$_configs['shield_toff']}" />
			</div>
		</div>
	</div>
	</form>
	<div id="dom_tmp" style="display:none;">
	{template:unit/program_shield}
	</div>
</div>
{template:foot}

