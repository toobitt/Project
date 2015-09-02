<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{js:tree}
{if !$hg_data}
{code}
$hg_data = array(0 => array('name' => ' 请选择 '));
{/code}
{/if}
{code}
if (!$hg_attr['height'])
{
	$hg_attr['height'] = 200;
}
if (!$hg_attr['multiple'])
{
	$hg_attr['multiple'] = 0;
	$hg_multiple_suffix = '';
}
else
{
	$hg_attr['multiple'] = 1;
	$hg_multiple_suffix = '[]';
}
$width = ($hg_attr['depth'] + 1) * 176;
{/code}
<span id="hg_node_{$hg_name}" class="nodeselct" style="width:{$width}px;">
{template:form/nodedata}
</span>
{if !$hg_attr['multiple']}
<input type="hidden" name="{$hg_name}{$hg_multiple_suffix}" value="" />
{/if}
<ul id="hg_selected_{$hg_name}" class="nodeselected" style="height:{$hg_attr['height']}px;">
{code}
if (!$hg_value)
{
	$hg_value = array();
}
{/code}
{foreach $hg_value AS $hg_k => $hg_v}
<li style="cursor:pointer;" id="hg_selected_{$hg_name}_{$hg_v['id']}" ondblclick="hg_remove_node('{$hg_name}', {$hg_v['id']});">
<input type="hidden" name="{$hg_name}{$hg_multiple_suffix}" value="{$hg_v['id']}" />
{$hg_v['name']}
<span style="float:right" onclick="hg_remove_node('{$hg_name}', {$hg_v['id']});">X</span>
</li>
{/foreach}
</ul>
<div class="clear"></div>