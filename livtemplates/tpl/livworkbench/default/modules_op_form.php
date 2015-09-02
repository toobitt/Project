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
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$modules['name']}-{$optext}操作</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div"><span  class="title">名称: </span><input type="text" name="name" value="{$formdata['name']}" /></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">描述: </span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">操作名: </span><input type="text" name="op" value="{$formdata['op']}" /><font class="important">英文和字母</font></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">主机: </span><input type="text" name="host" size="50" value="{$formdata['host']}" /><font class="important">不填继承系统设置</font></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">路径: </span><input type="text" name="dir" size="50" value="{$formdata['dir']}" /><font class="important">不填继承系统设置</font></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">文件: </span><input type="text" name="file_name" size="50" value="{$formdata['file_name']}" /></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">方法名: </span><input type="text" name="func_name" size="50" value="{$formdata['func_name']}" /></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">模板名: </span><input type="text" name="template" size="50" value="{$formdata['template']}" /><font class="important">可多模块设置</font></div></li>
<li class="i">
<div class="form_ul_div"><span  class="title">延伸操作: </span><textarea name="group_op" cols="60" rows="5">{$formdata['group_op']}</textarea><font class="important">一行一个，格式: (value = 名称)</font></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">支持批量: </span>{template:form/radio,has_batch,$formdata['has_batch'],$option}</div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">需确认: </span>{template:form/radio,need_confirm,$formdata['need_confirm'],$option}</div></li>
<!--
<li class="i">
<div class="form_ul_div clear"><span  class="title">全局操作: </span>{template:form/radio,is_global,$formdata['is_global'],$option}</div></li>
-->
<li class="i">
<div class="form_ul_div clear"><span  class="title">是否显示: </span>{template:form/radio,is_show,$formdata['is_show'],$option}</div></li>
<li title="此模块禁用" class="i">
<div class="form_ul_div clear"><span  class="title">禁用: </span>{template:form/radio,ban,$formdata['ban'],$option}&nbsp;<font class="important">此模块禁用</font></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">请求类型: </span>{template:form/select,request_type,$formdata['request_type'],$request_types}<font class="important">可多模块设置</font></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">直接返回: </span>{template:form/radio,direct_return,$formdata['direct_return'],$option}<font class="important">可多模块设置</font></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">callback操作: </span><input type="text" name="callback" size="50" value="{$formdata['callback']}" /><font class="important">js callback函数设置,可多模块设置</font></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">触发发布: </span><input type="checkbox" name="trigger_pub" size="50" value="1" {if $formdata['trigger_pub']}checked="checked"{/if}/><font class="important">如果调用此方法 是否更新发布计划任务</font></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">显示发布: </span><input type="checkbox" name="show_pub" size="50" value="1" {if $formdata['show_pub']}checked="checked"{/if}/><font class="important">是否在此操作中输出已发布信息</font></div></li>
<li class="i">
<div class="form_ul_div clear"><span  class="title">排序: </span><input type="text" name="order_id" size="4" value="{$formdata['order_id']}" /></div></li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="module_id" value="{$module_id}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}