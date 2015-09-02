<div class="form_ul_div">
	{if $hg_data}
	<span class="indexLabel">{code}echo $k + 1;{/code}</span>
	{/if}
	<input type="text" value="{$v}" name="{$hg_name}">
	<span class="optBtn drag">排序</span>
	<span class="optBtn add">插入</span>
	<span class="optBtn del">删除</span>
	<input type="hidden" class="saveFlag" name="$hg_value" value="{$v}" />    
</div>