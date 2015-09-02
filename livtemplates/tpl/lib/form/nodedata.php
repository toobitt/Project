<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
<ul class="nodeselected" style="height:{$hg_attr['height']}px;" id="hg_node_depth_{$hg_name}_{$hg_attr['depth']}">
{code}
if (!$hg_attr['level'])
{
	$hg_attr['level'] = 0;
}
{/code}
{if $hg_attr['depth'] !== NULL}
<li onclick="hg_return_parent_node('{$hg_name}', '{$hg_attr['level']}');" style="cursor:pointer;">返回上一级</li>
{/if}
{foreach $hg_data AS $hg_k => $hg_v}
<li id="hg_node{$hg_name}_{$hg_v['id']}" style="cursor:pointer;" onclick="hg_select_node('{$hg_name}', '{$hg_attr['nodeapi']}',{id:'{$hg_v['id']}',name:'{$hg_v['name']}',depth:'{$hg_v['depth']}'},'{$hg_attr['multiple']}','{$hg_attr['height']}','{$hg_attr['level']}', '{$hg_v['is_last']}')">
{$hg_v['name']}
{if !$hg_v['is_last']}
<span style="float:right"><img src="{$RESOURCE_URL}next.png" /></span>
{else}
{/if}
</li>
{/foreach}
</ul>