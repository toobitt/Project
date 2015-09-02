{if $hg_data == '1'}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span><input type="text" name="{$hg_name}" value="{$hg_value}">
{else if $hg_data=='2'}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span><input type="hidden" value="seconds" name="sec_{$hg_name}"/><input type="text" name="{$hg_name}" value="{code}echo $hg_value/1000;{/code}" style="width:20px;margin:0 4px;">秒
{else if $hg_data=='3'}
{code}
	$time = explode('@',$hg_value);
{/code}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span>
	<script type="text/javascript">
		function hg_ad_period(select)
		{
			if(select.value == 6 || select.value==7 || select.value == 8)
			{
				$('#hg_ad_period').show();
			}
			else
			{
				$('#hg_ad_period').hide();
			}
		}
	</script>
	<select name="period_{$hg_name}" onchange="hg_ad_period(this)">
		<option value="9" {if $time[1]==9} selected="selected"{/if}>当日</option>
		<option value="1" {if $time[1]==1} selected="selected"{/if}>每日</option>
		<option value="2" {if $time[1]==2} selected="selected"{/if}>单号</option>
		<option value="3" {if $time[1]==3} selected="selected"{/if}>双号</option>
		<option value="4" {if $time[1]==4} selected="selected"{/if}>单周</option>
		<option value="5" {if $time[1]==5} selected="selected"{/if}>双周</option>
		<option value="6" {if $time[1]==6} selected="selected"{/if}>指定周（几）</option>
		<option value="7" {if $time[1]==7} selected="selected"{/if}>未来几日（含今日）</option>
		<option value="8" {if $time[1]==8} selected="selected"{/if}>未来几日（不含今日）</option>
	</select>
	<span id="hg_ad_period" {if !$time[2]}style="display:none"{/if}><input type="text" style="width:40px;" name="value_period_{$hg_name}" value="{$time[2]}"/></span>
	<input type="text" name="{$hg_name}" value="{$time[0]}" style="width:54px;margin:0 4px;" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})">
{else if $hg_data=='4'}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span><input style="margin:0 4px;width:40px;" name="{$hg_name}" value="{$hg_value}" type="text" />像素
{else if $hg_data=='5' || $hg_data=='6'}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span>
	<select style="margin:0 4px;" name="{$hg_name}">
	<option value="0"{if $hg_value==0} selected="selected"{/if}>全屏</option>
	<option value="0"{if $hg_value==1} selected="selected"{/if}>左上角</option>
	<option value="0"{if $hg_value==2} selected="selected"{/if}>顶部居中</option>
	<option value="0"{if $hg_value==3} selected="selected"{/if}>右上角</option>
	<option value="0"{if $hg_value==4} selected="selected"{/if}>上下剧中左对齐</option>
	<option value="0"{if $hg_value==5} selected="selected"{/if}>居中</option>
	<option value="0"{if $hg_value==6} selected="selected"{/if}>上下居中右对齐</option>
	<option value="0"{if $hg_value==7} selected="selected"{/if}>左下角</option>
	<option value="0"{if $hg_value==8} selected="selected"{/if}>底部居中</option>
	<option value="0"{if $hg_value==9} selected="selected"{/if}>右下角</option>
	</select>
{else if $hg_data=='7'}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span>
	<select style="margin:0 4px;" name="{$hg_name}">
		<option value="1"{if $hg_value==1} selected="selected"{/if}>左上</option>
		<option value="3"{if $hg_value==3} selected="selected"{/if}>左下</option>
		<option value="2"{if $hg_value==2} selected="selected"{/if}>右上</option>
		<option value="4"{if $hg_value==4} selected="selected"{/if}>右下</option>
	</select>
{else if $hg_data=='8'}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span><input value="{$hg_value}" type="text" name="{$hg_name}" style="width:20px;margin:0 4px;">次
{else}
<span title="{$hg_name}-{$hg_data}">{$hg_attr['text']}</span><input style="margin:0 4px;width:20px;" name="{$hg_name}" type="text" value="{$hg_value}"/>
{/if}