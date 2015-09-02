{code}
$hg_attr['class'] = $hg_attr['class'] ? $hg_attr['class']:'transcoding down_list';
$hg_attr['show'] = $hg_attr['show'] ? $hg_attr['show'] :'transcoding_show';
$hg_attr['type'] = $hg_attr['type'] ? 1:0;
if($hg_attr['width'] && $hg_attr['width'] != 104 ){
	$width = $hg_attr['width'];
}else{
	$width = 90;
}
{/code}
<style>
.select-search .date-picker::-webkit-input-placeholder{text-indent:15px;color:#727272;}
.select-search .date-picker::-moz-placeholder{text-indent:15px;color:#727272;}
</style>
<div class="{$hg_attr['class']}" style="width:{code} echo $width . 'px'{/code};"   onmouseover="hg_search_show(1,'{$hg_attr['show']}','{$hg_attr['extra_div']}', this);" onmousemove="{$hg_attr['extra_over']}"  onmouseout="hg_search_show(0,'{$hg_attr['show']}','{$hg_attr['extra_div']}', this);{$hg_attr['extra_out']}">
   <span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label id="display_{$hg_attr['show']}" class="overflow" {if $hg_attr['state'] == 4}onclick="hg_open_column(this)"{/if}>{$hg_data[$hg_value]}</label></a></span>
	<ul id="{$hg_attr['show']}" style="display:none;"  class="{$hg_attr['show']} defer-hover-target">
		{if $hg_attr['state'] == 2}
	 	<div class="range-search" style="position:relative;width:90px;">
	 		<input type="text" name="{$hg_name}_key" class="{$hg_name}_key" style="width:80px;height:18px;border-bottom:0;" />
	 		<input type="button" class="btn_search" style="position:absolute;margin:0;right:4px;top:1px;" onclick="if(type_serach(this, '{$hg_attr['method']}', '{$hg_attr['key']}'  )){};" />
	 	</div>
		{/if}
		{if $hg_data}
		{foreach $hg_data as $k => $v}
			{if $hg_attr['state'] == 4}
			<li><a class="overflow">{$v}</a></li>
			{else}
		
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
				<li style="cursor:pointer;" {$hg_attr['extra_li']}><a {if $hg_attr['href']}href="{$expandhref}"{else}href="###" onclick="if(hg_select_value(this,{$hg_attr['state']},'{$hg_attr['show']}','{$hg_name}{code} echo $hg_attr['more']?'_'.$hg_attr['more']:'';{/code}',{$is_sub})){{$hg_attr['onclick']}};"{/if}   attrid="{$k}" class="overflow">{$v}</a></li>
		{/if}
		{/foreach}
		{/if}
	</ul>
	{if $hg_attr['state'] == 4}<input type="hidden" name="pub_column_name" value="{$hg_attr['select_column']}" />{/if}
</div>

{if $hg_attr['state'] == 1}
{code}
$start_time = 'start_time' . $hg_attr['time_name'];
$end_time = 'end_time' . $hg_attr['time_name'];
{/code}
	<div class="input" {if $hg_value == 'other'} style="width:{code} echo $width . 'px'{/code};display:block;border-right:1px solid #cfcfcf;float: left;" {else} style="width:{code} echo $width . 'px'{/code};display:none;float: left;border-right:1px solid #cfcfcf;" {/if} id="start_time_box{$hg_name}">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle">
			<input type="text" name="{$start_time}" id="start_time{$hg_name}" autocomplete="off" size="12" value="{$_INPUT[$start_time]}" class="date-picker" placeholder="起始时间" />
		</span>
	</div>
								
	<div class="input" {if $hg_value == 'other'} style="width:{code} echo $width . 'px'{/code};display:block;float: left;border-right:1px solid #cfcfcf;" {else} style="width:{code} echo $width . 'px'{/code};float: left;display:none;border-right:1px solid #cfcfcf;" {/if}  id="end_time_box{$hg_name}">
		<span class="input_left"></span>
		<span class="input_right"></span>
		<span class="input_middle" >
			<input type="text" name="{$end_time}" id="end_time{$hg_name}" autocomplete="off" size="12" value="{$_INPUT[$end_time]}" class="date-picker"  placeholder="结束时间"/>
		</span>
	</div>
	<input type="submit" value="" {if $hg_value == 'other'}style="display: block;margin-top:10px;" {else} style=" display:none;" {/if}id="go_date{$hg_name}" class="btn_search" />
{/if}


{if $hg_attr['more']}
	<input type="hidden" name="{$hg_name}[{$hg_attr['more']}]"  id="{$hg_name}_{$hg_attr['more']}"  value="{$hg_value}"/>
{else}

{if strstr($hg_name,'[]')}
<input type="hidden" name="{$hg_name}" value="{$hg_value}"/>
{else}
<input type="hidden" name="{$hg_name}"  id="{$hg_name}"  value="{$hg_value}"/>
{/if}
	
	
{/if}
{if $hg_attr['para']}
	{foreach $hg_attr['para'] as $k => $v}
	<input type="hidden" name="{$k}"  id="{$k}"  value="{$v}"/>
	{/foreach}
{/if}
