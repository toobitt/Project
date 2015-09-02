<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
//hg_pre($list);
{/code}
<div class="wrap">

<form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list form_table">
	{if $list_fields}
	<tr class="h" align="left" valign="middle">
		{if $batch_op}
		<th width="10" class="left"></th>
		{/if}
		{foreach $list_fields AS $k => $v}
		<th title="{$v['brief']}"{$v['width']}>{$v['title']}</th>
		{/foreach}
		<th>管理</th>
	</tr>
	{/if}
	<tbody id="{$hg_name}">
	{if $list}
		{foreach $list AS $k => $v}
			{if $list_fields}
			<tr onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r_{$v['comment_id']}" name="{$v['comment_id']}" class="h" align="left" valign="middle">
				{if $batch_op}
				<td class="left"><input type="checkbox" name="infolist[]"  value="{$v['comment_id']}" title="{$v['comment_id']}" /></td>
				{/if}
				{foreach $list_fields AS $kk => $vv}
					{code}
						$exper = $vv['exper'];			
						eval("\$val = \"$exper\";");
					{/code}
				<td{$vv['width']}><span class="m2o-common-title">{$val}</span></td>
				{/foreach}
				{if $op}
				<td align="center" class="right">
				<a id="delete_{$v['comment_id']}" href="run.php?mid={$_INPUT['mid']}&a=delete&id={$v['comment_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
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
 <div class="left" style="width:400px;">
   <input type="checkbox" name="checkall" id="checkall" value="infolist" title="全选" />
   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
 </div>
</div>
{$pagelink}
</form>
</div>
{template:foot}