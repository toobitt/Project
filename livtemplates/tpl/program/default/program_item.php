<ul class="day_con_ul" unselectable="on" onselectstart="return false;">
{code}
	if(!$program_item)
	{
		$program_item = $programs[$i];
	}
	$channel_id = 0;
	if(is_array($program_item))
	{
	$datas = $program_item['date'];
	unset($program_item['date']);
	$stime = strtotime($datas.' 00:00:00') + $toff ;
	
	{/code}
	{foreach $program_item as $key => $val}
		{code}
			$channel_id = $val['channel_id'];
		{/code}
		<li onmouseover="hg_pro_show({$val['id']});" onmouseout="hg_pro_hide({$val['id']});" id="pro_{$val['id']}" class="{$_configs['program_type'][$val['type_id']]['class']}" onclick="program_form({$_INPUT['mid']},{$val['id']},'update','',{$i},{$channel_id},'');">
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
		</li>
		{code}
			$toff += intval($val['toff']);
		{/code}
	{/foreach}
	{if $toff < 86340}
		{code}
		$stime = strtotime($datas.' 00:00:00') + $toff ;
		{/code}
		<li id="pro_{$stime}" class="day_con_ul_li border_r" style="width:{code} echo (180/3600)*(86340 - $toff);{/code}px;" onclick="program_form({$_INPUT['mid']},'{$channel_id}','create', '{$stime}',{$i},{$channel_id},event);" title="请添加新节目"></li>
	{/if}
	{code}
	}
	{/code}
</ul>