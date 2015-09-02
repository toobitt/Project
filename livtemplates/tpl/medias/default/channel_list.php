<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:global/head}
<div class="wrap">
{if $channel_list}
<ul class="channel">
{foreach $channel_list AS $k => $v}
<li><a href="live.php?id={$v['id']}" target="_blank"><img src="{$v['img']}" width="80" /></a>&nbsp;&nbsp;<a href="live.php?id={$v['id']}" target="_blank">{$v['name']}</a></li>
{/foreach}
</ul>
{$pagelink}
{/if}
</div>
{template:global/foot}