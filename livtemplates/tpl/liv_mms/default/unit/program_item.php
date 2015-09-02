<ul class="day_con_ul"  unselectable="on" onselectstart="return false;">
{code}
	if(!$program_item)
	{
		$program_item = $programs[$i];
	}
	if(is_array($program_item))
	{
	$stime = strtotime($date[$i]['w'].' 00:00:00') + $toff ;
	{/code}
	{foreach $program_item as $key => $val}
		<li onmouseover="hg_pro_show({$val['id']});" onmouseout="hg_pro_hide({$val['id']});" id="pro_{$val['id']}" class="{$_configs['program_type'][$val['type_id']]['class']}" onclick="scroll_show({$val['id']});"><!-- program_form({$_INPUT['mid']},{$val['id']},'update','',{$i},{$channel_id},''); -->
		<div style="width:{code} echo ceil((180/3600)*$val['toff']);{/code}px;">
		<div class="bor_item">
			<span class="day_con_1">{code} echo date('H:i',$val['start_time']);{/code}</span>
			<span class="day_con_2">{$val['theme']}</span>
		</div>
		</div>
		<div id="item_{$val['id']}" class="item_show">
			<span class="item_1">{code} echo date('H:i',$val['start_time']);{/code} - {code} echo date('H:i',$val['start_time'] + $val['toff']);{/code}</span>
			{if $val['subtopic']}
				<span class="item_3" id="item_3_{$val['id']}">{$val['theme']}：{$val['subtopic']}</span>
			{else}
				<span class="item_3" id="item_3_{$val['id']}">{$val['theme']}</span>
			{/if}
		</div>
		<div class="update_item" style="display:none" id="update_item_{$val['id']}">
		{template:unit/program_single}</div>
		</li>
		{code}
			$toff += intval($val['toff']);
		{/code}
	{/foreach}
	{if $toff < 86340}
		{code}
		$stime = strtotime($date[$i]['w'].' 00:00:00') + $toff ;
		{/code}
		<li onmouseover="hg_pro_show({$stime});" onmouseout="hg_pro_hide({$stime});" id="pro_{$stime}" class="day_con_ul_li border_r" style="width:{code} echo (180/3600)*(86340 - $toff);{/code}px;" onclick="program_form({$_INPUT['mid']},'{$program_list['id']}','create', '{$stime}',{$i},{$channel_id},event);" title="请添加新节目"></li>
	{/if}
	{code}
	}
	else
	{
	$stime = strtotime($date[$i]['w'].' 00:00:00') ;
	{/code}
		<li onmouseover="hg_pro_show({$stime});" onmouseout="hg_pro_hide({$stime});" id="pro_{$stime}" class="day_con_ul_li" style="width:100%;cursor:pointer;" onclick="program_form({$_INPUT['mid']},'{$program_list['id']}','create', '{$stime}', {$i},{$channel_id},event);" title="请添加新节目"></li>
	{code}
	}
	$program_item = '';
	{/code}
</ul>