<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :org_users_list.php
 * package  :package_name
 * Created  :2013-5-27,Writen by scala
 * 
 ******************************************************************/
?>
<!--
<select name="to_user" id="to_user">
	<option value="0">请选择用户</option>
{foreach $formdata as $val} 
	<option value="{code}echo $val['id'],',',$val['user_name'];{/code}">{$val['user_name']}</option>
{/foreach}	
</select>

-->


{foreach $formdata as $val} 
	<input type="checkbox" name="sendto[{code}echo $val['id'];{/code}]" value="{$val['user_name']}">{$val['user_name']}
{/foreach}	