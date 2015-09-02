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
			<li class="nav_module first"><em></em><a>菜单</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}菜单</h2>
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
<span class="title">链接：</span><input type="text" name="url" value="{$formdata['url']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">模块：</span><input type="text" name="module_id" value="{$formdata['module_id']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">父级菜单: </span>{template:form/select,father_id,$formdata['father_id'],$modules}<input type="text" name="apps" value="{$formdata['include_apps']}"/><font class="important">如果是顶级菜单，请填写包含的应用标识，多个标识用“,”分割</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">Class: </span><input type="text" name="class" value="{$formdata['class']}" /><font class="important">无后缀表示class名</font>
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">是否关闭: </span>{template:form/radio,close,$formdata['close'],$option}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">加载到首页: </span>{template:form/radio,index,$formdata['index'],$option}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">排序: </span><input type="text" name="order_id" size="4" value="{$formdata['order_id']}" />
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="pp" value="{$_INPUT['pp']}" />
<input type="hidden" name="goon" value="1" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}