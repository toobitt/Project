<?php 
/* $Id: interactive_member_form.php 14825 2012-11-29 09:41:39Z lijiaying $ */
?>
{template:head}
{css:ad_style}
{js:mms_default}

{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}

<div class="ad_middle">
<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post"enctype='multipart/form-data' class="ad_form h_l">
	<h2>账号管理编辑</h2>
	<ul class="form_ul">
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">频道选择：</span>
				{if $channel_info}
				{foreach $channel_info AS $k=>$v}
				<label>
				<input 
				{if $channel_id}
					{foreach $channel_id AS $kk=>$vv}
						{if $vv == $v['id']}
						checked="checked"
						{/if}
					{/foreach}
				{/if}
				type="checkbox" value="{$v['id']}" name="channel_id[]" />
				{$v['name']}
				</label>
				{/foreach}
				{else}
				暂无频道信息
				{/if}
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">站外平台：</span>
				<input type="text" value="{$plat_name}" disabled="disabled" />
				<input type="hidden" name="plat_name" value="{$plat_name}" />
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">会员名：</span>
				<input type="text" value="{$member_name}" disabled="disabled" />
				<input type="hidden" name="member_name" value="{$member_name}" />
			</div>
			<div class="form_ul_div">
				<span class="title">昵称：</span>
				<input type="text" value="{$nick_name}" disabled="disabled" />
				<input type="hidden" name="nick_name" value="{$nick_name}" />
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<p class="clear" style="margin-bottom:10px;">
					<span class="title"></span>
					<label><input class="n-h" type="checkbox" onclick="hg_plan_repeat(this);" {if count($week_day)}checked{/if}/><span>周期性节目</span></label>
				</p>
				<div id="week_date" class="clear" {if !count($week_day)}style="display:none;"{/if}>
					{code}
						$week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
					{/code}
					<span class="title">重复：</span>
						<label>
							<input class="n-h" type="checkbox" onclick="hg_plan_repeat(this,1);" id="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
						</label>
					{foreach $week_day_arr as $key => $value}
						<label>
						<input onclick="hg_plan_repeat(this,2);" class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
						</label>
					{/foreach}
				</div>
				<div id="date_list" class="clear" {if count($week_day)}style="display:none;"{else} style="background:url('{$RESOURCE_URL}dottedLine.png') repeat-x 0 top;padding-top: 15px;"{/if}>
					<span class="title">日期：</span>
					{code}
					$type_source = array('other'=>' size="14" autocomplete="off" style="width:165px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'dates','style'=>'width:190px;float: left;','type'=>'yyyy-MM-dd','other_focus' => 'hg_plan_check_day()');
					$dates = $start_time ? date('Y-m-d',strtotime($start_time)) : date('Y-m-d');
					{/code}
					{template:form/wdatePicker,dates,$dates,'',$type_source}
				</div>
			</div>
		</li>
		<li class="i">
			
			<div class="form_ul_div clear">
				<span class="title">时间：</span>
					{code}
					$type_source = array('other'=>' size="14" autocomplete="off" style="width:100px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_toff();"','name'=>'start_time','style'=>'width:110px;float: left;','type'=>'HH:mm:ss','other_focus' => 'hg_plan_check_day()');
					$default_start = $start_time ? date('H:i:s',strtotime($start_time)) : '';
					{/code}
					{template:form/wdatePicker,start_times,$default_start,'',$type_source} 
					<span class="time-h-k">-</span> 
					{code}
					$type_source = array('other'=>' size="14" autocomplete="off" style="width:100px;height: 18px;font-size:12px;padding-left:5px;line-height: 20px;float: left;border:none;" onblur="hg_plan_toff();"','name'=>'end_time','style'=>'float: left;margin-left:5px;width:110px','type'=>'HH:mm:ss','other_focus' => 'hg_plan_check_day()');
					$default_end = $end_time ? date('H:i:s',strtotime($end_time)) : '';
					{/code}
					{template:form/wdatePicker,end_times,$default_end,'',$type_source}
			</div>
		</li>
		<!--
<li class="i" id="channel_box">
			<div class="form_ul_div clear">
				<span class="add_sub" style="cursor: pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;">添加频道</span>
			</div>
		</li>
-->
	</ul>
	</br>
	{if !$id && !$is_exists}
		<input type="submit" name="sub" value="添加" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="create" id="action" />
		<input type="hidden" name="referto" value="{$referto}" />
	{else}
		<input type="submit" name="sub" value="更新" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="update" id="action" />
		<input type="hidden" name="referto" value="{if $is_exists}{$referto}{else}{$_INPUT['referto']}{/if}" />
	{/if}
	<input type="hidden" name="{$primary_key}" value="{$id}" />
	
	<input type="hidden" name="member_id" value="{$member_id}" />
	<input type="hidden" name="avatar" value="{$avatar}" />
	<input type="hidden" name="plat_id" value="{$plat_id}" />
	<input type="hidden" name="plat_name" value="{$plat_name}" />
	<input type="hidden" name="plat_type" value="{$plat_type}" />
	<input type="hidden" name="plat_token" value="{$plat_token}" />
	<input type="hidden" name="plat_expired_time" value="{$plat_expired_time}" />
	<input type="hidden" name="plat_can_access" value="{$plat_can_access}" />
</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}