<?php 
/* $Id$ */
?>
<?php 
/* $Id$ */
?>
{template:head}
<h3 class="">
系统权限管理 &gt;&gt; 系统权限-用户{$userinfo['user_name']}
</h3>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="form">
<table width="700px">
	<thead>
	<tr>
	<th>&nbsp;</th>
	{foreach $authorize_op as $k=>$v}
		<th>{$v[0]}</th>
	{/foreach}
	</tr>
	</thead>
	<tbody>
	{foreach $sysinfo as $key=>$value}
	<tr>
		<td width='100px'>{$value[0]}</td>
			{foreach $authorize_op as $k=>$v}
			<td align="center"><input type="checkbox" name="sys_prms[{$key}][{$v[1]}]" value="1" {if $sys_prms['group'][$key][$v[1]]}checked="checked" disabled = "disabled"{else if($sys_prms['personal'][$key][$v[1]])}checked="checked"{/if}/></td>
			{/foreach}
		<td width="420px"><a href="?a=module_authorize&sys_id={$key}&id={$user_id}" onclick="return hg_ajax_post(this, '用户权限设置',0)">&gt;&gt;{$value[0]}系统模块权限设置</a><a href="?a=node_authorize&sys_id={$key}&user_id={$user_id}">>>节点</a></td>
	</tr>
	{/foreach}
	</tbody>
</table>
<input type="hidden" name="user_id" value="{$user_id}" />
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="submit" name="sub" value="{$optext}" />
</form>
{template:foot}