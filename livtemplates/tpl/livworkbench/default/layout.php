<?php 
?>
{template:head}
<style>
th{text-align:left;}

</style>
<!--
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first dq"><em></em><a>布局商店</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
-->
<div class="wrap n">
<div class="search_a">
<form name="searchform" action="" method="get">

<input type="hidden" name="a" value="show" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />&nbsp;
</form>
</div>
{code}
//print_r($list_fields);
{/code}
<form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list form_table">
	{if $list_fields}
	<tr class="h" align="left" valign="middle">
		<th></th>
		<th title="{$list_fields['brief']}"{$list_fields['width']}>{$list_fields['indexpic']['title']}</th>
		<th class="list-id" title="{$v['brief']}"{$list_fields['width']}>{$list_fields['id']['title']}</th>
		<th title="{$list_fields['brief']}"{$list_fields['width']}>{$list_fields['name']['title']}</th>
		<th>管理</th>
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
				
				{code}
				//print_r($list);
					$indexpic = $v['indexpic'];
					unset($v['indexpic']);
					$exper = $vv['exper'];			
					eval("\$val = \"$exper\";");
				{/code}
				{if $indexpic}
					<td style="width:60px"><img src="{$indexpic}" style="width:40px;height:30px;margin-right:10px;" /></td>
				{else}
					<td></td>
				{/if}
				<td style="margin-left:10px;">{$v['id']}</td>
				<td>{$v['title']}</td>
			
				{if $v['op_name']}
				<td align="center" class="right">
					<a href="{$v['link']}&amp;{$v['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}" title="{$v['brief']}"{$v['attr']}>{$v['op_name']}</a>
				</td>
				{else}
					{if is_array($op)}
					<td align="center" class="right">
					{foreach $op AS $kk => $vv}
					<a href="{$vv['link']}&amp;{$vv['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}" title="{$vv['brief']}"{$vv['attr']}>{$vv['name']}</a>
					{/foreach}
					{/if}
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
	<input type="hidden" name="mid" value="168" />
	<input type="hidden" name="a" value="edit_c" />
	</tbody>
</table>
<div class="form_bottom clear">
	<div class="live_delete">
		<input type="checkbox" name="checkall" id="checkall" value="infolist" title="全选" class="n-h">
		<input type="hidden" name="a" id="a" value="delete" />
		<div class="batch_op">{template:menu/op}</div>
	</div>
	<div class="live_page">{$pagelink}</div>
</div>
</form>
</div>

{template:foot}