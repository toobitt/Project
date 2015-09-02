<?php 
/* $Id$ */
?>
{template:head}
<h3 class="">
节点授权管理 &gt;&gt; 授权用户—{$userinfo['user_name']}
</h3>
<form name="editform" id="editform" action="" method="post" class="form">
<ul>
<li><div><span class="label">栏目: </span> 
{code}
$hg_columns_attr['height'] = 120;
$hg_columns_attr['multiple'] = 1;

{/code}
{foreach $node as $k=>$v}
{template:form/nodeselect,node_prms_$k,$$v['selected'],$$v['list'],$hg_columns_attr}
<input type="hidden" name="node_id[]" value="{$k}" />
{/foreach}
</div>
</li>
<li><input type="hidden" name="a" value="node_authorize_update" /></li>
<li><input type="hidden" name="user_id" value="{$user_info['id']}" /></li>
<li><input type="hidden" name="sys_id" value="{$_INPUT['sys_id']}" /></li>
<li><input type="submit" name="sub" value="确定" /></li>
<ul>
</form>
{template:foot}