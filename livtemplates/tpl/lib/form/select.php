<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if $hg_data}
<select name="{$hg_name}"{$hg_attr['onchange']} {$hg_attr['style']}>
{foreach $hg_data AS $hg_k => $hg_v}
{code}
if ($hg_k == $hg_value)
{
	$selected = ' selected="selected"';
}
else
{
	$selected = '';
}
{/code}
<option value="{$hg_k}"{$selected}>{$hg_v}</option>
{/foreach}
</select>
{else}
未设定选择数据
{/if}