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
<span class="title">数据库服务器：</span><input type="text" name="host" id="host" value="{$formdata['host']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　数据库用户：</span><input type="text" name="user" id="user" value="{$formdata['user']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　数据库密码：</span><input type="password" name="pass" id="pass" value="{$formdata['pass']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　数据库名称：</span><input type="text" name="database" id="database" onfocus="hg_getDb()" value="{$formdata['database']}" />
<span id="dbs" style="display:none;"></span>数据库不存在将自动创建(需有权限)
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　覆盖数据表：</span><input type="checkbox" name="cover" id="cover" value="1" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　数据表前缀：</span><input type="text" name="dbprefix" id="dbprefix" value="{$formdata['dbprefix']}" />
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