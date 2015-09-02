{code}
if($hg_attr['width'] && $hg_attr['width'] != 104 ){
	$width = $hg_attr['width'];
}else{
	$width = 90;
}
$hg_attr['class'] = $hg_attr['class'] ? $hg_attr['class']:'transcoding down_list';
$hg_attr['show'] = $hg_attr['show'] ? $hg_attr['show']:'transcoding_show';
$hg_attr['type'] = $hg_attr['type'] ? 1:0;
{/code}
<div class="{$hg_attr['class']}" style="width:{code} echo $width . 'px'{/code};"  onmouseover="hg_search_show(1,'{$hg_attr['show']}','{$hg_attr['extra_div']}', this);" onmousemove="{$hg_attr['extra_over']}"  onmouseout="hg_search_show(0,'{$hg_attr['show']}','{$hg_attr['extra_div']}', this);{$hg_attr['extra_out']}">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_{$hg_attr['show']}" class="overflow">{$hg_data[$hg_value]}</label></a></span>
	<ul id="{$hg_attr['show']}" style="display:none;"  class="{$hg_attr['show']} defer-hover-target">
		{foreach $hg_data as $k => $v}
		{code}
			if($hg_attr['is_sub'])
			{
				$is_sub = 0;
			}
			else
			{
				$is_sub = 1;
				if($k === 'other')
				{
					$is_sub = 0;
				}
			}
			if($hg_attr['href'])
			{
				if(!strpos($hg_attr['href'],'fid='))
				{
					$expandhref=$hg_attr['href'].'&nid='.$k;
				}
				else
				{
					$expandhref=$hg_attr['href'].$k;
				}
			}
		{/code}
				<li style="cursor:pointer;" {$hg_attr['extra_li']}><a {if $hg_attr['href']}href="{$expandhref}"{else}href="###" onclick="if(special_hg_select_value(this,{$hg_attr['state']},'{$hg_attr['show']}','{$hg_name}{code} echo $hg_attr['more']?'_'.$hg_attr['more']:'';{/code}',{$is_sub})){{$hg_attr['onclick']}};"{/if}   attrid="{$k}" class="overflow">{$v}</a></li>
		{/foreach}
	</ul>
</div>

{if $hg_attr['state'] == 1}
	<div class="input" {if $hg_value == 'other'} style="width:{code} echo $width . 'px'{/code};display:block;border-right:1px solid #cfcfcf;float: left;" {else} style="width:{code} echo $width . 'px'{/code};display:none;float: left;border-right:1px solid #cfcfcf;" {/if} id="special_start_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="start_time" id="special_start_time" autocomplete="off" size="12" class="date-picker" value="{$_INPUT['start_time']}"/>
		</span>
	</div>
								
	<div class="input" {if $hg_value == 'other'} style="width:{code} echo $width . 'px'{/code};display:block;float: left;border-right:1px solid #cfcfcf;" {else} style="width:{code} echo $width . 'px'{/code};float: left;display:none;border-right:1px solid #cfcfcf;" {/if}  id="special_end_time_box">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="end_time" id="special_end_time" autocomplete="off" size="12" class="date-picker" value="{$_INPUT['end_time']}"/>
		</span>
	</div>
	<input type="submit" value="" {if $hg_value == 'other'}style="display: block;margin-top:10px;" {else} style=" display:none;" {/if}id="go_date" class="btn_search" />
{/if}

{if $hg_attr['more']}
	<input type="hidden" name="{$hg_name}[{$hg_attr['more']}]"  id="{$hg_name}_{$hg_attr['more']}"  value="{$hg_value}"/>
{else}
	<input type="hidden" name="{$hg_name}"  id="{$hg_name}"  value="{$hg_value}"/>
{/if}
{if $hg_attr['para']}
	{foreach $hg_attr['para'] as $k => $v}
	<input type="hidden" name="{$k}"  id="{$k}"  value="{$v}"/>
	{/foreach}
{/if}
