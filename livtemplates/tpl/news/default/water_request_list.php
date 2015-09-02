{if is_array($formdata)}
	{foreach $formdata as $k => $v}
		<div id="water_{$v['id']}" class="imglist">
			<img src="{$v['small_url']}" alt="{$v['filename']}" onclick="hg_select_water('{$v['small_url']}','{$v['filename']}',1);"/>
		</div>
	{/foreach}
{/if}
