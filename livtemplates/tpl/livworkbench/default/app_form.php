<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_system first"><em></em><a>系统</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$optext}应用</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div">
<span class="title">名称: </span><input type="text" name="name" value="{$formdata['name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">标识: </span><input type="text" name="softvar" value="{$formdata['softvar']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">logo: </span><input type="text" name="logo" value="{$formdata['logo']}" /><font  class="important">无后缀表示class名</font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">上级系统:</span>{template:form/select,father_id,$formdata['father_id'],$applications}
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">描述: </span><textarea name="brief" style="width:400px;height:100px;" cols="60" rows="5">{$formdata['brief']}</textarea>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">主机: </span><input type="text" name="host" size="50" value="{$formdata['host']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">路径: </span><input type="text" name="dir" size="50" value="{$formdata['dir']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">通信key: </span><input type="text" name="token" size="40" value="{$formdata['token']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">排序: </span><input type="text" name="order_id" size="4" value="{$formdata['order_id']}" />
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