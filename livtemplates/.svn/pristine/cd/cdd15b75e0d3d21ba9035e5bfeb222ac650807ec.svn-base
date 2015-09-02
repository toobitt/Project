<div {if $v['is_plan']} style="background:#E5EEFF;" {/if} class="input_div{$hg_attr['disabled_cls']}" onchange="hg_content_change(this);"  onclick="hg_input_div_bgcolor(this);">
	<span {if !$v['plan_status']}title="点击添加" {else} style="cursor:default;"{/if} class="chg_plan_left f_l" {if !$hg_attr['disabled']}onclick="add_plan_check(this);"{/if}></span>
	<input class="chg_plan_input f_l" type="text" name="start_times[]" value="{$v['start_time']}" onblur="hg_time_checked(this,1);" onchange="update_check_time(this,1),hg_out_end_time(this,1)"{$hg_attr['disabled']} />
	<span {if !$hg_attr['disabled']}onclick="hg_plan_form(this)"{/if} class="chg_type_span overflow f_l">		
	{if $v['type'] == 2}
		<span class="chg_type_fir">文件</span>
		<span class="chg_type_sec">{$v['channel2_name']}<span>{if $v['file_toff']}{$v['file_toff']}{/if}</span></span>
	{else if $v['type'] == 3}
		<span class="chg_type_fir">时移</span>
		<span class="chg_type_sec"><span class="title">{$v['channel2_name']}</span><span>{$v['s_time']} - {$v['e_time']}</span></span>
	{else}
		<span class="chg_type_fir">直播</span>
		<span class="chg_type_sec">{$v['channel2_name']}</span>	
	{/if}
		<input type="hidden" class="channel2_id" name="channel2_ids[]" value="{$v['channel2_id']}"{$hg_attr['disabled']} />
		<input type="hidden" class="channel2_name" name="channel2_name[]" value="{$v['channel2_name']}"{$hg_attr['disabled']} />
		<input type="hidden" class="type" name="type[]" value="{$v['type']}"{$hg_attr['disabled']} />
		<input type="hidden" class="program_start_time" name="program_start_time[]" value="{if $v['program_start_time']}{$v['program_start_time']}{/if}"{$hg_attr['disabled']} />
		<input type="hidden" class="program_end_time" name="program_end_time[]" value="{if $v['program_end_time']}{$v['program_end_time']}{/if}"{$hg_attr['disabled']} />
		<input type="hidden" class="toff" name="toff[]" value="{$v['toff']}"{$hg_attr['disabled']} />
		<input type="hidden" class="start_time" value="{$v['start_time']}"{$hg_attr['disabled']} />
		<input type="hidden" class="end_time" value="{$v['end_time']}"{$hg_attr['disabled']} />
		<input type="hidden" class="hidden_temp" name="hidden_temp[]" value=""{$hg_attr['disabled']} />
	</span>
	<input class="chg_plan_input f_l" type="text"  name="end_times[]" value="{$v['end_time']}" onblur="hg_time_checked(this);" onchange="update_check_time(this),hg_out_end_time(this)"{$hg_attr['disabled']} />
	<span {if !$v['plan_status']}title="点击删除" {else} style="cursor:default;"{/if}  class="chg_plan_right f_r" {if !$hg_attr['disabled']}onclick="hg_del_plan(this)"{/if}></span>
	<input type="hidden" class="hidden_id" name="ids[]" value="{$v['id']}"{$hg_attr['disabled']} />
	<input type="hidden" class="epg_id" name="epg_id[]" value="{$v['epg_id']}"{$hg_attr['disabled']} />
	<input type="hidden" class="hidden_flag" value="{$v['is_plan']}" />
</div>