<?php 
/* $Id: _nodedrop.php 8096 2012-02-22 05:07:24Z zhuld $ */
?>
<ul id="child_{$fid}" style="display:none;">
{foreach $hg_data AS $hg_k => $hg_v}
	{code}
	$_input_k = $hg_v['input_k'] ? $hg_v['input_k'] : '_id';
	$_input_t = $hg_v['input_t'] ? $hg_v['input_t'] : '_type';
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
<li id="hg_child{$hg_name}_{$hg_v['id']}"{$class}>
<a id="hg_achild{$hg_name}_{$hg_v['id']}" href="{$_selfurl}&amp;{$_input_t}={$hg_v['fid']}&amp;{$_input_k}={$hg_v['id']}" target="nodeFrame" onclick="hg_drop_child_class('{$hg_name}', '{$hg_v['depth']}', '{$hg_v['id']}','{$fid}');"{if $hg_v['attr']['color']} style="color:{$hg_v['attr']['color']}"{/if}>{$hg_v['name']}</a>
</li>
{/foreach}
</ul>