<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :org_select.php
 * package  :package_name
 * Created  :2013-5-27,Writen by scala
 * 
 ******************************************************************/
?>
<select name="to_user_org" id="show_orgs">
	<option value="-1">请选择组织</option>
{foreach $formdata as $val} 
	<option value="{$val['id']}">{$val['name']}</option>
{/foreach}	
</select>
