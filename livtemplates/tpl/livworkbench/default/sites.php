{template:head}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_web_site first dq"><em></em><a>站点</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
		<span onclick="document.location.href='?a=form'" class="button_6"><strong>新增站点</strong></span>
	</div>
</div>
<div class="wrap n">
{code}
	$list = $sites;
{/code}
<form name="listform" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list form_table">
	{if $list_fields}
	<tr class="h" align="left" valign="middle">
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
				{if !$vv['group_op']}
				<!--不显示删除操作-->
				{if $kk!='delete'}
				<a href="{$vv['link']}&amp;{$vv['pre']}{$primary_key}={$v[$primary_key]}{$_ext_link}" title="{$vv['brief']}"{$vv['attr']}>{$vv['name']}</a>
				{/if}
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
		{/foreach}
	{else}
	{code}
	$colspan = count($list_fields) + 2;
	{/code}
	<tr><td colspan="{$colspan}" style="text-align:center;">暂无此类信息</td></tr>
	{/if}
	</tbody>
</table>
<div class="form_bottom clear">
	<div class="live_delete">
		<input type="hidden" name="a" id="a" value="delete" />
	</div>
	<div class="live_page">{$pagelink}</div>
</div>
</form>
</div>
{template:foot}