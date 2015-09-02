{if is_array($formdata)}
	{foreach $formdata as $k => $value}
		{code}
			$$k = $value;			
		{/code}
	{/foreach}
{/if}
<div class="service-time" style="width:{if $type == 4}650px;{else}350px;{/if}">
	<input class="checkbox-server_time" _id="{$key}" type="checkbox" name="server_time[{$key}]" value="{$type}" />
	<span>{$name}</span>
	{if $type == 4}
	<input type="text" name="start_date_{$key}"   style="margin-left:12px;"/> - <input type="text" name="end_date_{$key}"  />
	{/if}
	<input type="text" name="start_time_{$key}"   {if $type != 2} style="margin-left:12px;"{/if} /> - <input type="text" name="end_time_{$key}"  />
</div>