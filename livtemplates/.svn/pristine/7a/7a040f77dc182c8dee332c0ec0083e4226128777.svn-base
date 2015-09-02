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
<span class="title">客户名称：</span><input type="text" name="custom_name" value="{$formdata['custom_name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　　简称：</span><input type="text" name="display_name" value="{$formdata['display_name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户标识：</span><input type="text" name="bundle_id" value="{$formdata['bundle_id']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">授权域名：</span><input type="text" name="domain" value="{$formdata['domain']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户描述：</span><textarea name="custom_desc" style="width:400px;height:100px;" cols="60" rows="5">{$formdata['custom_desc']}</textarea>
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="pp" value="{$_INPUT['pp']}" />
<input type="hidden" name="goon" value="1" />
<br>
<input type="submit" name="sub" value="下一步" class="button_6_14"/>
</form>
{template:head/install_foot}