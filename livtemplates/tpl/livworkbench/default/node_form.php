<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_sort first"><em></em><a>节点</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}节点</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div"><span  class="title">名称: </span><input type="text" name="name" value="{$formdata['name']}" /></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">描述: </span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">所属系统: </span>{template:form/select,application_id,$formdata['application_id'],$applications}</div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">关联模块: </span><input type="text" name="module_id" size="50" value="{$formdata['module_id']}" /><font class="important">填写该节点所要关联的模块id(必填)</font></div></li>
<div class="form_ul_div">
<span  class="title">主机: </span><input type="text" name="host" size="50" value="{$formdata['host']}" /><font class="important">不填继承系统设置</font></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">路径: </span><input type="text" name="dir" size="50" value="{$formdata['dir']}" /><font class="important">不填继承系统设置</font></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">文件: </span><input type="text" name="file_name" size="50" value="{$formdata['file_name']}" /></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">方法名: </span><input type="text" name="func_name" size="50" value="{$formdata['func_name']}" /></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">节点标识: </span><input type="text" name="node_uniqueid" size="50" value="{$formdata['node_uniqueid']}" /></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">Token: </span><input type="text" name="token" size="50" value="{$formdata['token']}" /></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">模板: </span><input type="text" name="template" size="50" value="{$formdata['template']}" /></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">返回值: </span><input type="text" name="return_var" size="50" value="{$formdata['return_var']}" /></div></li>
<li class="i">
<div class="form_ul_div">
<span  class="title">排序: </span><input type="text" name="order_id" size="4" value="{$formdata['order_id']}" /></div></li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}"  class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}