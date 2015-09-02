<?php 
/* $Id: node_drop.php 9378 2012-05-18 08:48:40Z hanwenbin $ */
?>
{js:jscroll}
{css:node_drop}
{code}
if (!$hg_attr['level'])
{
	$hg_attr['level'] = 0;
}
if ($hg_attr['_selfurl'])
{
	$_selfurl = $hg_attr['_selfurl'];
}
if (!$hg_attr['text'])
{
	$hg_attr['text'] = '全部分类';
}
$cc = 'cur';
{/code}
<div id="hg_node_depth_{$hg_name}_{$hg_attr['depth']}" class="been_marked_drop">
<ul id="hg_node_list_{$hg_name}">
<li id="hg_node{$hg_name}_0"><a id="hg_a{$hg_name}_0" href="{$_selfurl}" class="{$cc}" onclick="hg_drop_child_class('{$hg_name}', '0', '0','0');" target="nodeFrame">{$hg_attr['text']}</a></li>
{foreach $hg_data AS $hg_k => $hg_v}
	{code}
	$_input_k = $hg_v['input_k'] ? $hg_v['input_k'] : '_id';
	{/code}
	{if $hg_v['id'] === $_INPUT['_id']}
		{code}
		$class = ' class="cur"';
		{/code}
	{else}
		{code}
		$class = '';
		{/code}
	{/if}
<li id="hg_node{$hg_name}_{$hg_v['id']}" >
<a id="hg_a{$hg_name}_{$hg_v['id']}" onclick="hg_drop_child_class('{$hg_name}', '{$hg_v['depth']}', '{$hg_v['id']}','{$hg_v['id']}');" href="{$_selfurl}&amp;{$_input_k}={$hg_v['id']}" {$class} target="nodeFrame"{if $hg_v['attr']['color']} style="color:{$hg_v['attr']['color']}"{/if}>{$hg_v['name']}</a>
{if !$hg_v['is_last']}<span onclick="hg_show_node_child('{$hg_name}', '{$hg_attr['nodeapi']}', '{$hg_v['id']}','{$fid}', '{$hg_v['depth']}', '{$hg_attr['level']}', '{$hg_v['is_last']}');"><em id="hg_em{$hg_name}_{$hg_v['id']}" {$class}></em></span>{/if}
</li>
{/foreach}
</ul>
</div>