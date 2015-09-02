<?php 
/* $Id: interactive_list.php 14744 2012-11-23 02:46:40Z lijiaying $ */
?>
{template:head}
{css:vod_style}
{css:interactive}
{js:vod_opration}
{js:live_interactive/interactive}
{css:mms_style}

{code}
$channel 	  = $list['channel'];
$channel_id   = $channel['channel_id'];
$channel_name = $channel['channel_name'];
$channel_logo = $channel['channel_logo'];
$dates		  = $list['dates'];
$program	  = $list['program'];
$start_end	  = $list['start_end'];
$presenter	  = $list['presenter'];
unset($list['dates'],$list['channel'],$list['program'],$list['start_end'], $list['presenter']);
$first_data = $list[0];
/*hg_pre($list);*/
{/code}

{template:unit/head}
{template:unit/nav}
<div class="biaoz inter_right" id="body_content">
	<form action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="program_form" id="program_form" onsubmit="return hg_program_edit();">
		<div class="_title">
			<span class="s_title">节目设置</span>
			<div class="_title_">
				<span>主持人：</span>
				{if $presenter}
					{code}
						$i = 1;
					{/code}
					{foreach $presenter AS $k=>$v}
					<label>
						<input 
						{if $first_data['presenter_id']}
							{foreach $first_data['presenter_id'] AS $kk=>$vv}
								{if $k == $vv}
								checked="checked"
								{/if}
							{/foreach}
						{elseif $i == 1}
							checked="checked"
						{/if}
						{code}
							$i ++;
						{/code}
						type="checkbox" name="presenter_id[]" value="{$k}" />{$v}
					</label>
					{/foreach}
				{/if}
			</div>
			<div class="_title_">
				<span>站外账号：</span>
				{if $member_info}
					{code}
						$j = 1;
					{/code}
					{foreach $member_info AS $k=>$v}
					<label>
						<input 
						{if $first_data['member_id']}
							{foreach $first_data['member_id'] AS $kk=>$vv}
								{if $v['id'] == $vv}
								checked="checked"
								{/if}
							{/foreach}
						{elseif $j == 1}
							checked="checked"
						{/if}
						{code}
							$j ++;
						{/code}
						type="checkbox" name="member_id[]" value="{$v['id']}" />{$v['member_name']}
					</label>
					{/foreach}
				{/if}
			</div>
			
		</div>
		
		<div class="single" id="single_day" style="margin: 10px;min-height: 600px;width:60%;">
			<ul id="program_list">
			{if $list}
				{foreach $list AS $k => $v}
					{template:unit/interactive_program_list_edit}
				{/foreach}
			{else}
				{template:unit/interactive_program_list}
			{/if}
			</ul>
			<div style="margin: 10px 0px;">
				<span style="cursor:pointer;" onclick="hg_program_add();">添加节目环节</span>
			</div>
			<input type="submit" class="button_4" value="保存修改" id="save_edit">
			<input type="hidden" name="a" value="program_edit" id="action" />
			<input type="hidden" name="dates" value="{$dates}" id="dates" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="channel_id" id="channel_id" value="{$channel_id}" />
			{code}
				$start2end = '';
				if (!empty($program))
				{
					foreach($program AS $k =>$v)
					{
						$start = date('H:i:s',$v['start_time']);
						$end = date('H:i:s',$v['start_time']+$v['toff']);
						if ($v['zhi_play'])
						{
							$start2end = $start.','.$end;
						}
					}
				}
				if ($start_end)
				{
					$start2end = $start_end;
				}
			{/code}
			<input type="hidden" name="start2end" id="start2end" value="{$start2end}" />
		</div>
</form>
</div>
<div style="display:none;" id="program_html">
	{template:unit/interactive_program_list}
</div>
{template:foot}