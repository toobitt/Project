<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first"><em></em><a>模块</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}模块</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">名称: </span><input type="text" name="name" value="{$formdata['name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">icon: </span><input type="text" name="icon" value="{$formdata['icon']}" /><font class="important">无后缀表示class名</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">描述: </span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">所属系统: </span>{template:form/select,application_id,$formdata['application_id'],$applications}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">父级模块: </span>{template:form/select,fatherid,$formdata['fatherid'],$modules}
</div>
</li>
<li class="i">
<div class="form_ul_div clear l">
<span  class="title">关联模块: </span>{template:form/checkbox_t,relate_module,$formdata['relate_module'],$modules}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">主机: </span><input type="text" name="host" size="50" value="{$formdata['host']}" /><font class="important">不填继承系统设置</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">路径: </span><input type="text" name="dir" size="50" value="{$formdata['dir']}" /><font class="important">不填继承系统设置</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">文件: </span><input type="text" name="file_name" size="50" value="{$formdata['file_name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">方法名: </span><input type="text" name="func_name" size="50" value="{$formdata['func_name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">模板名: </span><input type="text" name="template" size="50" value="{$formdata['template']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">返回名: </span><input type="text" name="return_var" size="50" value="{$formdata['return_var']}" /><font class="important">不填默认为模板名</font>
</div>
</li>
<li class="i">
<div class="clear">
{if $formdata['apidata']}
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="list clear">
<tr>
<th>字段</th>
<th>排序</th>
<th>显示</th>
<th>主键</th>
<th>标题</th>
<th>描述</th>
<th>图片</th>
<th>时间</th>
<th title="内容原文链接">链接</th>
<th>可推荐</th>
</tr>
{foreach $formdata['apidata'] AS $k => $v}
{code}
$i++;
if (!$formdata['settings']['order'][$k])
{
	$formdata['settings']['order'][$k] = $i;
}
{/code}
	{if is_array($v)}
		{foreach $v AS $kk => $vv}
			{code}
			if (!$kk)
			{
				continue;
			}
			$rcdata = array($kk => '');

			$showattr = array('name' => "show[$k][$kk]");
			$picattr = array('name' => "pic[$k][$kk]");
			$timeattr = array('name' => "time[$k][$kk]");
			
			$i++;
			if (!$formdata['settings']['order'][$k.$kk])
			{
				$formdata['settings']['order'][$k.$kk] = $i;
			}		
			$formdata['settings']['show_append'][$k][$kk] = htmlspecialchars($formdata['settings']['show_append'][$k][$kk]);
			{/code}
			<tr onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r{$k}_{$kk}">
			<td style="font-weight:bold;">&nbsp;&nbsp;{$k}&nbsp;-&nbsp;{$kk}</td>
			<td>&nbsp;</td>
			<td>{template:form/checkbox, show[$k][$kk], $formdata['settings']['show'][$k], $rcdata}
			<input type="text" name="order[{$k}.{$kk}]" value="{$formdata['settings']['order'][$k.$kk]}" size="2" title="显示顺序" />
			<input type="text" name="show_title[{$k}][{$kk}]" value="{$formdata['settings']['show_title'][$k][$kk]}" size="10" />
		<textarea name="show_append[{$k}][{$kk}]" title="显示更多字段" cols="20" rows="1" class="textarea_row">{$formdata['settings']['show_append'][$k][$kk]}</textarea>
		<input type="text" name="show_width[{$k}][{$kk}]" value="{$formdata['settings']['width'][$k][$kk]}" size="2" title="显示宽度百分比" />
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>{template:form/checkbox, pic[$k][$kk], $formdata['settings']['pic'][$k], $rcdata}</td>
			<td>{template:form/checkbox, time[$k][$kk], $formdata['settings']['time'][$k], $rcdata}</td>
			<td>&nbsp;</td>
			<td>
			<input type="text" name="cancommend[{$k}][{$kk}]" value="{$formdata['settings']['cancommend'][$k][$kk]}" size="10" title="推荐tag" /></td>
			</tr>
		{/foreach}
	{else}
		{code}
		$rcdata = array($k => '');

		if (!$formdata['settings']['primary'])
		{
			$formdata['settings']['primary'] = 'id';
		}
		if (!$formdata['settings']['title'])
		{
			$formdata['settings']['title'] = 'title';
		}
		$formdata['settings']['show_append'][$k] = htmlspecialchars($formdata['settings']['show_append'][$k]);
		{/code}
		<tr onmouseover="hg_row_interactive(this, 'on');" onmouseout="hg_row_interactive(this, 'out');" onclick="hg_row_interactive(this, 'click', 'cur');" id="r{$k}">
		<td style="font-weight:bold;">{$k}</td>
		<td>{template:form/checkbox, canorder[$k], $formdata['settings']['canorder'], $rcdata}</td>
		<td>{template:form/checkbox, show[$k], $formdata['settings']['show'], $rcdata}
		<input type="text" name="order[{$k}]" value="{$formdata['settings']['order'][$k]}" size="2" title="显示顺序" />
		<input type="text" name="show_title[{$k}]" value="{$formdata['settings']['show_title'][$k]}" size="10" />
		<textarea name="show_append[{$k}]"  title="显示更多字段" cols="20" rows="1" class="textarea_row">{$formdata['settings']['show_append'][$k]}</textarea>
		<input type="text" name="show_width[{$k}]" value="{$formdata['settings']['width'][$k]}" size="2" title="显示宽度百分比" />
		</td>
		<td>{template:form/radio, primary, $formdata['settings']['primary'], $rcdata}</td>
		<td>{template:form/radio, title, $formdata['settings']['title'], $rcdata}</td>
		<td>{template:form/radio, brief, $formdata['settings']['brief'], $rcdata}</td>
		<td>{template:form/checkbox, pic[$k], $formdata['settings']['pic'], $rcdata}</td>
		<td>{template:form/checkbox, time[$k], $formdata['settings']['time'], $rcdata}</td>
		<td>{template:form/radio, link, $formdata['settings']['link'], $rcdata}</td>
		<td>
		<input type="text" name="cancommend[{$k}]" value="{$formdata['settings']['cancommend'][$k]}" size="10" title="推荐tag" />
		</td>
		</tr>
	{/if}
{/foreach}
</table>
{else}
{/if}
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">是否分页: </span>{template:form/radio,is_pages,$formdata['is_pages'],$option}
</div>
</li>
<li>
<div class="form_ul_div clear">
<span  class="title">每页显示数目: </span><input type="text" name="page_count" size="10" value="{$formdata['page_count']}" /><font class="important">不填自动设置</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">排序: </span><input type="text" name="order_id" size="4" value="{$formdata['order_id']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">位置: </span><input type="text" name="menu_pos" size="4" value="{$formdata['menu_pos']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">关联模块: </span><input type="text" name="relate_molude_id" size="4" value="{$formdata['relate_molude_id']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">关联发布: </span><input type="checkbox" name="is_pub" size="4" value="1" {if $formdata['is_pub']}checked="checked"{/if}/>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title overflow">发布模块ID: </span><input type="input" name="pub_module_id" size="4" value="{$formdata['pub_module_id']}"/><font class="important">自定义发布模块ID 发布优先级 模块ID<关联模块ID<自定义模块ID</font>
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}