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
			<li class="nav_module first"><em></em><a>操作</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}平台</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户名称: </span><input type="text" name="name" value="{$formdata['name']}" />
</div>
</li>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">授权API：</span><input type="text" name="authapi" value="{$formdata['authapi']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">APPID：</span><input type="text" name="appid" value="{$formdata['appid']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">APPkey：</span><input type="text" name="appkey" value="{$formdata['appkey']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户KEY：</span><input type="text" name="custom_appkey" value="{$formdata['custom_appkey']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">用户名：</span><input type="text" name="username" value="{$formdata['username']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">密码：</span><input type="text" name="pwd" value="{$formdata['pwd']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">本地用户名：</span><input type="text" name="localusername" value="{$formdata['localusername']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">本地密码：</span><input type="text" name="localuserpwd" value="{$formdata['localuserpwd']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">是否关闭: </span>{template:form/radio,is_close,$formdata['is_close'],$option}
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