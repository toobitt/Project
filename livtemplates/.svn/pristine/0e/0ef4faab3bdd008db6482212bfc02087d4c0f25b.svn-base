
	{code}
		$show = $formdata['show'];
		$name = $formdata['name'];
		unset($formdata['name'],$formdata['show']);
	{/code}
	{if($formdata)}
	   	 {foreach $formdata as $k => $v }
		<li style="cursor:pointer;">
			<a href="###" onclick="if(hg_select_value(this,0,'{$show}','{$name}',1)){};"   attrid="{$k}" class="overflow">{$v}</a>
		</li>
		{/foreach}
	{/if}