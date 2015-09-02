<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
<div class="wrap">
{if !$close_search}
{template:search}
{/if}
<form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list form_table">
	{if $list_fields}
	<tr class="h" align="left" valign="middle">
		{if $batch_op}
		<th width="10" class="left"><input type="checkbox" name="checkall" id="checkall" value="infolist" title="全选" /></th>
		{/if}
		{foreach $list_fields AS $k => $v}
		<th title="{$v['brief']}"{$v['width']}>{$v['title']}</th>
		{/foreach}
		{if $op}
		<th>管理</th>
		{/if}
	</tr>
	{/if}
	<tbody id="{$hg_name}">
	{if $list}
		{foreach $list AS $k => $v}
			{if $list_fields}
			<tr onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r{$v[$primary_key]}"  class="h" align="left" valign="middle">
				{if $batch_op}
				<td class="left"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></td>
				{/if}
				{foreach $list_fields AS $kk => $vv}
					{code}
						$exper = $vv['exper'];			
						eval("\$val = \"$exper\";");
					{/code}
				<td{$vv['width']}>{$val}</td>
				{/foreach}
				{if $op}
				<td align="center" class="right">
				{foreach $op AS $kk => $vv}
				{code}
				switch($kk)
				{
					case 'recommend':
				{/code}
				<a href="{$vv['link']}&amp;{$vv['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}" title="{$vv['brief']}"{$vv['attr']}>{$vv['name']}</a>
				{code}
					break;
					case 'delete':
				{/code}
				<a href="{$vv['link']}&amp;{$vv['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}" title="{$vv['brief']}"{$vv['attr']}>{$vv['name']}</a>
				{code}
					break;
					case 'audit':
				{/code}
				{if $vv['group_op']}
					{code}
					$group_op = $vv['group_op'];
					$name = $kk . '__' . $primary_key . '=' . $v[$primary_key];
					$attr['onchange'] = $vv['attr'];
					$value = $v[$kk];
					{/code}
					{template:form/select, $name, $value, $group_op, $attr}
				{/if}
				{code}
					break;
					default:
					break;
					
				}
				{/code}
				{/foreach}
				</td>
				{/if}
			</tr>
			{/if}
		{/foreach}
	{else}
	{code}
	$colspan = count($list_fields) + 1;
	{/code}
	<tr><td colspan="{$colspan}" style="text-align:center;">暂无此类信息</td></tr>
	{/if}
	</tbody>
</table>
<input type="hidden" name="a" id="a" value="delete" />
<div class="batch_op">
{if $batch_op}
	<ul class="batch_opmenu">
		{foreach $batch_op AS $k => $v}
		<li>
		{code}
				switch($k)
				{
					case 'recommend':
				{/code}
				<input type="button" name="bat{$k}" class="button_4" value="批量{$v['name']}" title="{$v['brief']}"{$v['attr']} />
				{code}
					break;
					case 'delete':
				{/code}
				<input type="button" name="bat{$k}" class="button_4" value="批量{$v['name']}" title="{$v['brief']}"{$v['attr']} />
				{code}
					break;
					case 'audit':
				{/code}
				{if $v['group_op']}
					{code}
					$group_op = $v['group_op'];
					$name = 'batch__' . $k;
					$attr['onchange'] = $v['attr'];
					{/code}
					{template:form/select, $name, -1, $group_op, $attr}&nbsp;
				{/if}
				{code}
					break;
					default:
					break;
					
				}
				{/code}
		</li>
		{/foreach}
	</ul>
	<div class="clear"></div>
	{/if}
</div>
{$pagelink}
</form>
</div>
{template:foot}