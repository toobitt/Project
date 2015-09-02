<?php 
/* $Id$ */
?>
<h3 class="">
授权管理 &gt;&gt; 授权用户—{$userinfo['user_name']}
</h3>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="admin.php" method="post" class="form"  id="editform" onsubmit="return hg_ajax_submit('editform', 'hg_prms_setting_call');">
<table width="600px">
	<thead>
	<tr>
	<th>&nbsp;</th>
	{foreach $authorize_op as $k=>$v}
		<th>{$v[0]}</th>
	{/foreach}
	</tr>
	</thead>
	<tbody>
	{foreach $modules as $key=>$value}
	<tr>
		<td>{$value}</td>
			{foreach $authorize_op as $k=>$v}
			<td align="center"><input type="checkbox" name="module_prms[{$key}][{$v[1]}]" value="1" {if ($module_prms['group'][$key][$v[1]]==1)}checked="checked" disabled="disabled"{else if $module_prms['personal'][$key][$v[1]]==1}checked="checked"{/if}/></td>
			{/foreach}
	</tr>
	{/foreach}
	</tbody>
</table>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="sys_id" value="{$_INPUT['sys_id']}" />
<input type="submit" name="sub" value="{$optext}" />
</form>