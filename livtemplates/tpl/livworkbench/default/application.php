<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}

<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_system first dq"><em></em><a>应用管理</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<span class="button_6" onclick="document.location.href='?a=form'"><strong>新增应用</strong></span>
		<span class="button_6" onclick="document.location.href='api_install.php'"><strong>应用商店</strong></span>
	</div>
</div>
<div class="wrap n">
<form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list">
	{if $list_fields}
	<tr>
		{if $batch_op}
		<th width="50" class="left"></th>
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
	{if $applications_relate[0]}
		{foreach $applications_relate[0] AS $aid}
			{code}
			$v = $applications[$aid];
			$styles['name'] = '';
			{/code}
			{template:list/list_item}
			{if $applications_relate[$aid]}
				{foreach $applications_relate[$aid] AS $caid}
				{code}
				$v = $applications[$caid];
				$styles['name'] = '—&nbsp;';
				{/code}
				{template:list/list_item}
				{/foreach}
			{/if}
		{/foreach}
	{else}
	{code}
	$colspan = count($list_fields) + 1;
	{/code}
	<tr><td colspan="{$colspan}" >暂无此类信息</td></tr>
	{/if}
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