<?php 
/* $Id: topic_list_single.php 8396 2012-03-23 07:27:42Z repheal $ */
?>
{foreach $topic as $key => $val}
{code}
$pcolor = $bcolor = ''; 
	if($val['color'])
	{
		$color = explode(',',$val['color']);
		$bcolor = $color[1];
	}
{/code}
	{if !$val['null']}
	<li class="day_default" id="li_{$val['id']}">
		<div class="single_item" id="update_item_{$val['id']}" style="{code} echo $bcolor?'background:'.$bcolor.';':'';{/code}">
			<div id="colors_{$val['id']}" class="color_show"></div>
			<div class="input">
				<span class="input_left"></span>
				<span class="input_right"></span>
				<span class="input_middle"><input type="text" name="start_time[{$val['id']}]" id="start_time{$val['id']}" class="single_input" value="{$val['start']}" onchange="hg_check_input_get(this,{$val['id']},'start')" onfocus=""/></span>
			</div>
			<div class="input text">
				<span class="input_left"></span>
				<span class="input_right"></span>
				<span class="input_middle"><input type="text" name="name[{$val['id']}]" id="name{$val['id']}" value="{$val['name']}" onchange="hg_check_input_get(this,{$val['id']},'name')" /></span>
			</div>
			<div class="input">
				<span class="input_left"></span>
				<span class="input_right"></span>
				<span class="input_middle"><input type="text" name="end_time[{$val['id']}]" id="end_time{$val['id']}" class="single_input" value="{$val['end']}" onchange="hg_check_input_get(this,{$val['id']},'end')" onfocus=""/></span>
			</div>
			<span onmousedown="hg_move_focus({$val['id']});" class="focus_move" onmouseover="hg_show_delete({$val['id']},true);" onmouseout="hg_show_delete({$val['id']},false);">
				<span id="single_tips_{$val['id']}" class="tips_error"></span>
				<span class="down_del" id="del_{$val['id']}" onclick="hg_delete_topic({$val['id']});" style="display: none; "></span>
			</span>
			{if $val['space']}
				<input id="checke_{$val['id']}" name="checke[{$val['id']}]" value="1" type="hidden"/>
				<input type="hidden" name="new[{$val['id']}]" id="new_{$val['id']}" value="1"/>
			{else}
				<input id="checke_{$val['id']}" name="checke[{$val['id']}]" value="0" type="hidden"/>
			{/if}			
		</div>
	</li>
	{else}
		<li class="none" onclick="hg_create_single(this);" onmousedown="hg_move_focus({$val['id']});"><input type="hidden" id="start_time{$val['id']}" value="{$val['start']}"/><input type="hidden" id="end_time{$val['id']}" value="{$val['end']}"/><input id="pid_{$val['id']}" value="{$val['id']}" type="hidden"/><input type="text" style="width: 0px; height: 0px; position: absolute; margin-left: -100px;" id="focus_cache_{$val['id']}" value=""></li>
	{/if}
{/foreach}

