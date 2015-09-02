<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if $list_fields}
<tr onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r{$v[$primary_key]}">
	{if $batch_op}
	<td class="left"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
	{/if}
	{foreach $list_fields AS $kk => $vv}
		{code}
			$exper = $vv['exper'];			
			eval("\$val = \"$exper\";");
		{/code}
	<td>{$styles[$kk]}{$val}</td>
	{/foreach}
	{if $op}
	<td align="center" class="right">
	{foreach $op AS $kk => $vv}
	{if !$vv['group_op']}
	<a href="{$vv['link']}&amp;{$vv['pre']}{$primary_key}={$v[$primary_key]}" title="{$vv['brief']}"{$vv['attr']}>{$vv['name']}</a>
	{else}
	{code}
	$group_op = $vv['group_op'];
	$name = $kk . '__' . $primary_key . '=' . $v[$primary_key];
	$attr['onchange'] = $vv['attr'];
	$value = $v[$kk];
	{/code}
	{template:form/select, $name, $value, $group_op, $attr}
	{/if}
	{/foreach}
	</td>
	{/if}
</tr>
{/if}
