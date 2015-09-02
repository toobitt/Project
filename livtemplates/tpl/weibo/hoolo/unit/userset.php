<?php 
/* $Id: userset.php 388 2011-07-26 05:32:48Z lijiaying $ */
?>
<ul class="userset clear">
{foreach $_settings['personset'] as $k => $v}
	{if $k == $gScriptName}
		<li><strong><a href="{code} echo hg_build_link($v['filename']); {/code}">{$v['name']}</a></strong></li>
	{else}
		<li><a href="{code} echo hg_build_link($v['filename']); {/code}">{$v['name']}</a></li>
	{/if}
{/foreach}
</ul>
