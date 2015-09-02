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
	<li class="day_default" id="li_{$val['id']}" onmouseover="hg_move_index({$val['id']},true);" onmouseout="hg_move_index({$val['id']},false);">
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
			<div class="input">
				<span class="input_left"></span>
				<span class="input_right"></span>
				<span class="input_middle"><input type="text" name="end_time[{$val['id']}]" id="end_time{$val['id']}" class="single_input" value="{code} echo date('H:i:s',$val['start_time']+$val['toff']);{/code}" onchange="hg_check_input_get(this,{$val['id']},'end')" onfocus=""/></span>
			</div><span onmousedown="hg_move_focus({$val['id']});" class="focus_move" onmouseover="hg_show_delete({$val['id']},true);" onmouseout="hg_show_delete({$val['id']},false);">
			<span class="lb-btn{if $val['item']} cur{/if}" id="lb-btn_{$val['id']}" style="display:{if $val['outdate']}none{else}block{/if};" onclick="hg_request_source({$val['id']});"></span>
			{if $val['item'] && $val['start_time']+$val['toff'] > TIMENOW}
			{code}
				$default = $val['item'] ? $val['item'] : -1;
				$item_source = array(
					'class' => 'down_lists',
					'show' => 'item_shows_'.$val['id'],
					'width' => 83,/*列表宽度*/		
					'state' => 0, /*0--正常数据选择列表，1--日期选择*/
					'is_sub'=>1,
					'onclick'=>'hg_check_source(' . $val['id'] . ',' . $default . ');',
					'more' => $val['id'],
				);
				$program_item[-1] = '录播分类';
				foreach($formdata as $k =>$v)
				{
					$program_item[$k] = $v;
				}
			{/code}
			<span class="down_list" style="" id="lb_column_{$val['id']}">{template:form/search_source,item,$default,$program_item,$item_source}
			{else}
			<span class="down_list" style="display:none" id="lb_column_{$val['id']}">{/if}</span>
			<span id="single_tips_{$val['id']}" class="tips_error"></span>
			<span class="down_del" id="del_{$val['id']}" onclick="hg_delete_program({$val['id']});" style="display:none;"></span></span>
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
			<input type="hidden" name="plan[{$val['id']}]" id="plan_{$val['id']}" value="1"/>
		{/if}
		<input type="text" style="width: 0px; height: 0px; position: absolute; margin-left: -100px;" id="focus_cache_{$val['id']}" value="">
	</li>
{else}
	<li class="none" onclick="hg_create_single(this);" onmousedown="hg_move_focus({$val['id']});"><input type="hidden" id="start_time{$val['id']}" value="{code} echo date('H:i:s',$val['start_time']);{/code}"/><input type="hidden" id="end_time{$val['id']}" value="{code} echo date('H:i:s',$val['start_time']+$val['toff']);{/code}"/><input id="pid_{$val['id']}" value="{$val['id']}" type="hidden"/><input type="text" style="width: 0px; height: 0px; position: absolute; margin-left: -100px;" id="focus_cache_{$val['id']}" value=""></li>
{/if}
{/foreach}
