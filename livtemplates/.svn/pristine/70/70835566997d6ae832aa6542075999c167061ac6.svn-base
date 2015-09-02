<?php 
/* $Id$ */
?>
{template:head}
{css:ad_style}
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_user first"><em></em><a>用户</a></li>
			<li class="dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap">
<div class="ad_middle">
<h2>{$optext}用户</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div">
<span  class="title">用户名：</span><input type="text" name="user_name" value="{$formdata['user_name']}"/>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">用户组：</span>
<!-- <select name="admin_group_id">
<option value="0">==请选择用户组==</option>
{foreach $group as $k=>$value}
{if $value['id'] == $formdata['gid']}
<option value="{$value['id']}" selected='selected'>{$value['name']}</option>
{else}
<option value="{$value['id']}">{$value['name']}</option>
{/if}
{/foreach}
</select> -->
{template:form/select,admin_group_id,$formdata['admin_group_id'],$group}
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title">密码：</span><input type="text" name="password" value="" />{if $a=='update'}<font class="important">无需更改，请留空</font>{/if}
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span  class="title" style="width:73px;">绑定密保卡：</span><input type="checkbox" name="security_card"  />
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br/>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}