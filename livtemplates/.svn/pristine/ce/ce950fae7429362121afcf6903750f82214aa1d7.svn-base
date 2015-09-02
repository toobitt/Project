
	{code}
		unset($formdata['name'],$formdata['show']);
	{/code}
	{if($formdata)}
	   	 {foreach $formdata as $k => $v }
		<li class=" ">
		  <label title="" class="ui-corner-all ui-state-hover">
		  <input name="multiselect_{$k}" type="checkbox" value="{$k}" title="{$v}"><span>{$v}</span></label></li>
		{/foreach}
	{/if}