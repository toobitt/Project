<?php 
/* $Id: userset.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>


<ul class="userset clear">
{foreach $_settings['personset'] as $k => $v}
    {if $k == $gScriptName}
       <li><strong><a href="{code} echo hg_build_link($v['filename']); {/code}">{$v['name']}</a></strong></li>
    {else}
       <li><a href="{code} echo hg_build_link($v['filename']){/code}">{$v['name']}</a></li>
    {/if}
{/foreach}
</ul>