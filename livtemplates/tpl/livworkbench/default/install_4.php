<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head/install}
{if $msg}
<div style="color:red;">{$msg}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">　用户名：</span><input type="text" name="user" id="user" value="{$formdata['user']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　　密码：</span><input type="password" name="pass" id="pass" value="{$formdata['pass']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">重复密码：</span><input type="password" name="cpass" id="cpass" value="{$formdata['cpass']}" />
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="nowapply" value="{$_INPUT['nowapply']}" />
<input type="hidden" name="goon" value="1" />
<br>
<input type="submit" name="sub" value="下一步" class="button_6_14"/>
</form>
{template:head/install_foot}