<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:global/head}
<div class="wrap">
{if $vodinfo_list}
<ul class="video">
{foreach $vodinfo_list AS $k => $v}
<li><a href="vod.php?id={$v['id']}" target="_blank"><img src="{$v['img']}" width="80" /></a><a href="vod.php?id={$v['id']}" target="_blank" class="title overflow" style=";">{$v['title']}</a></li>
{/foreach}
</ul>
{$pagelink}
{/if}
</div>
{template:global/foot}