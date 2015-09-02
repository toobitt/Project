{code}
$all_select_style = array(
		'class' 	=> 'down_list',
		'state' 	=> 	0,
		'is_sub'	=>	1,
	);
{/code}
{if is_array($formdata)}
	{foreach $formdata as $k => $value}
		{code}
			$$k = $value;			
		{/code}
	{/foreach}
{/if}
<div class="collect-fee">
	<input type="checkbox" name="fees[{$key}]" value="{$type}"  style="float:left; margin-top:5px;"   class="checkbox-fees" _id="{$key}"/>
	<span>{$name}</span>
	{if $type != 3}
	<input type="text" name="s_time[{$key}]"   style="margin-left:{if $type == 4}34px;{else}12px;{/if}" />
	<span> - </span>
	<input type="text" name="e_time[{$key}]"/>
	{/if}
	{code}
		${'car_type_style_' . $key} = $all_select_style;
		${'car_type_style_' . $key}['show'] = 'car_type_item_show_' . $key;
		$hidden_name = 'car_type_' . $key;
		$style_name = ${'car_type_style_' . $key};
		$car_type_default = -1;
	{/code}
	<div style="float:left;{if $type == 3}margin-left:11px;{else}margin-left:2px;{/if}">{template:form/search_source,$hidden_name,$car_type_default,$_configs['car_type'],$style_name}</div>
	<input type="text" name="instruction[{$key}]"  style="float:left;margin-left:2px;{if $type == 3}width:200px;{elseif $type == 4}width:242px;{else}width:85px;{/if}" />
	{if $type != 4}
	<input type="text" name="price[{$key}]" style="float:left;margin-left:2px;width:18px;" />
	<span>元/</span>
	{code}
		${'charge_unit_style_' . $key} = $all_select_style;
		${'charge_unit_style_' . $key}['show'] = 'charge_unit_item_show_' . $key;
		$c_hidden_name = 'charge_unit_' . $key;
		$c_style_name = ${'charge_unit_style_' . $key};
		$charge_unit_default = -1;
	{/code}
	<div style="float:left;margin-left:3px;">{template:form/search_source,$c_hidden_name,$charge_unit_default,$_configs['charge_unit'],$c_style_name}</div>
	{/if}
</div>