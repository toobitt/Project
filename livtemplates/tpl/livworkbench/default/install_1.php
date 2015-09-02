<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head/install}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li><span>文件</span><span>需权限</span><span>当前权限</span></li>
{foreach $checkdir AS $k => $v}
	{code}
	if ($v != $dirprms[$k])
	{
		$style = ' style="color:red"';
	}
	else
	{
		$style = '';
	}
	{/code}
	<li{$style}>
	<span>{$k}</span><span>{$v}</span><span>{$dirprms[$k]}</span>
	</li>
{/foreach}
</ul>
<input type="hidden" name="a" value="{$a}" />
<br>

<input type="submit" name="sub" value="下一步" class="button_6_14"/>
</form>
{template:head/install_foot}