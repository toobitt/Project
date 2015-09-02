<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :notice_org_select.php
 * package  :package_name
 * Created  :2013-5-28,Writen by scala
 * 
 ******************************************************************/
?>

<!--
<select name="sendto" id="user_orgs">
	<option value="-1">请选择组织</option>
	<option value="0">全部</option>
{foreach $formdata as $val} 
	<option value="{code}echo $val['id'],',',$val['name'];{/code}">{$val['name']}</option>
{/foreach}	
</select>

-->

{foreach $formdata as $val} 
	<input type="checkbox" name="sendto[{code}echo $val['id'];{/code}]" value="{$val['name']}">{$val['name']}
{/foreach}	