{if $formdata}
   {foreach $formdata[0] AS $k => $v}
	   <span onclick="hg_put_name(this);">{$v['name']}</span>
   {/foreach}
{else}
    <h1 color="red">sorry! there is no application in appstore</h1>
{/if}