<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first"><em></em><a>模块</a></li>
			<li class=" dq" id="hg_cur_nav_last"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}模块</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">模板名: </span><input type="text" name="template" size="50" value="{$formdata['template']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
{if $formdata['apidata']}
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list clear">
<tr>
<th>字段</th>
<th>可修改</th>
<th>排序/显示</th>
<th>表现</th>
<th>分组</th>
<th>行列</th>
</tr>
{code}
if (!$formdata['form_set']['primary'])
{
	$formdata['form_set']['primary'] = 'id';
}
{/code}
{foreach $formdata['apidata'] AS $k => $v}
{code}
$i++;
$rcdata = array($k => '');
if (!$formdata['form_set']['order'][$k])
{
	$formdata['form_set']['order'][$k] = $i;
}

{/code}
		<tr onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r{$k}">
		<td style="font-weight:bold;">{$k}</td>
		<td>
		{template:form/checkbox, canedit[$k], $formdata['form_set']['canedit'][$k], $rcdata}
		</td>
		<td><input type="text" name="order[{$k}]" value="{$formdata['form_set']['order'][$k]}" size="2" />
			<input type="text" name="title[{$k}]" value="{$formdata['form_set']['title'][$k]}" size="12" title="显示名称" />
			<input type="text" name="width[{$k}]" value="{$formdata['form_set']['width'][$k]}" size="2" title="显示宽度" />			
			<input type="text" name="height[{$k}]" value="{$formdata['form_set']['height'][$k]}" size="2" title="显示高度" />
		</td>
		<td>{template:form/select, show_type[$k], $formdata['form_set']['show_type'][$k], $show_types}</td>
		<td>{template:form/select, group[$k], $formdata['form_set']['group'][$k], $groups}</td>
		<td>{template:form/select, rowscols[$k], $formdata['form_set']['rowscols'][$k], $rowscols}</td>
		</tr>
{/foreach}
</table>
{else}
{/if}
</div>
</li>
</ul>
<input type="hidden" name="a" value="doform_set" />
<input type="hidden" name="id" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="submit" name="sub" value="确定"  class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}