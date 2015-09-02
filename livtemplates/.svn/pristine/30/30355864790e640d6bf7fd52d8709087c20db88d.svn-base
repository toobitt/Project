<?php 
/* $Id: program_list_day_single.php 7228 2012-01-07 05:46:32Z repheal $ */
?>
{foreach $programs as $key => $val}
{code}
	if($val['color'])
	{
		$color = explode(',',$val['color']);
		$pcolor = $color[0];
		$bcolor = $color[1];
	}
{/code}
{if !$val['new']}
	<li class="day_default" id="li_{$val['id']}">
		<div class="single_item" id="update_item_{$val['id']}" style="{code} echo $bcolor?'background:'.$bcolor.';':'';{/code}{if $val['is_plan']}border-left-color: {$pcolor};border-left-width: 2px;{/if}">
			<span class="box" onclick="hg_color({$val['id']},{$_INPUT['mid']})" id="btn_{$val['id']}" style="{code} echo $pcolor?'background:'.$pcolor:'';{/code}"></span>
			<div id="colors_{$val['id']}" class="color_show"></div>
			<div class="input">
				<span class="input_left"></span>
				<span class="input_right"></span>
				<span class="input_middle"><input type="text" name="start_time[{$val['id']}]" id="start_time{$val['id']}" class="single_input" value="{code} echo date('H:i:s',$val['start_time']);{/code}" onchange="hg_check_input_get(this,{$val['id']},'start')" onfocus=""/></span>
			</div>
			<div class="input text">
				<span class="input_left"></span>
				<span class="input_right"></span>
				<span class="input_middle"><input type="text" name="theme[{$val['id']}]" id="theme{$val['id']}" value="{code} echo $val['subtopic']?$val['theme'].':'.$val['subtopic']:$val['theme'];{/code}" onchange="hg_check_input_get(this,{$val['id']},'theme')" /></span>
			</div>
			
			{if $val['is_plan']}
			<div class="input">
				<span style="position: absolute;margin-left: 70px;margin-top: -8px;display: inline-block;width: 10px;background-color: black;border-radius: 6px;line-height: 10px;text-align: center;padding: 1px;cursor: pointer;color: white;" onclick="hg_clear_plan(this);">x</span>
				<span class="input_left"></span>
				<span class="input_right"></span>
				<span class="input_middle"><input type="text" name="end_time[{$val['id']}]" id="end_time{$val['id']}" class="single_input" value="{code} echo date('H:i:s',$val['start_time']+$val['toff']);{/code}" onchange="hg_check_input_get(this,{$val['id']},'end')"/></span>
			</div>
			{else}
			<div class="input_text">
				<span id="end_time_span{$val['id']}">{code} echo date('H:i:s',$val['start_time']+$val['toff']);{/code}</span><input type="hidden" name="end_time[{$val['id']}]" id="end_time{$val['id']}" class="single_input" value="{code} echo date('H:i:s',$val['start_time']+$val['toff']);{/code}"/>
			</div>
			{/if}
			<span onmousedown="hg_move_focus({$val['id']});" class="focus_move" onmouseover="hg_show_delete({$val['id']},true);" onmouseout="hg_show_delete({$val['id']},false);">
				<span id="single_tips_{$val['id']}" class="tips_error"></span>
				<span class="down_del" id="del_{$val['id']}" onclick="hg_delete_program({$val['id']});" style="display:none;"></span>
			</span>
		</div>				
		<input id="showcolor_{$val['id']}" name="color[{$val['id']}]" value="{$val['color']}" type="hidden"/>
		<input id="pid_{$val['id']}" value="{$val['id']}" type="hidden"/>
		{if $val['space']}
			<input id="checke_{$val['id']}" name="checke[{$val['id']}]" value="1" type="hidden"/>
			<input type="hidden" name="new[{$val['id']}]" id="new_{$val['id']}" value="1"/>
		{else}
			<input id="checke_{$val['id']}" name="checke[{$val['id']}]" value="{if $val['is_plan']}1{else}0{/if}" type="hidden"/>
			{if $val['is_plan']}
				<input type="hidden" name="new[{$val['id']}]" id="new_{$val['id']}" value="1"/>
			{/if}
		{/if}
		{if $val['is_plan']}
			<input type="hidden" name="plan[{$val['id']}]" id="plan_{$val['id']}" value="{$val['is_plan']}"/>
		{/if}
		<input type="hidden" name="program_source[{$val['id']}]" id="program_source_{$val['id']}" value="0"/>
		<input type="text" style="width: 0px; height: 0px; position: absolute; margin-left: -100px;" id="focus_cache_{$val['id']}" value="">
		<div onclick="hg_after_create(this);" style="height: 6px;border-top: 1px solid #CED3D6;cursor: pointer;"></div>
	</li>
{else}
<li class="none" onclick="hg_create_single(this);" onmousedown="hg_move_focus({$val['id']});"><input type="hidden" id="start_time{$val['id']}" value="{code} echo date('H:i:s',$val['start_time']);{/code}"/><input type="hidden" id="end_time{$val['id']}" value="{code} echo date('H:i:s',$val['start_time']+$val['toff']);{/code}"/><input id="pid_{$val['id']}" value="{$val['id']}" type="hidden"/><input type="text" style="width: 0px; height: 0px; position: absolute; margin-left: -100px;" id="focus_cache_{$val['id']}" value=""></li>
{/if}
{/foreach}
