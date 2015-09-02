<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if $_user['id']}
<ul>
{if $_user['group_type'] <= $_settings['max_admin_type']}
<a href="appstore.php">应用商店</a>
{if $_settings['hostmanage']}
<a href="{$_settings['hostmanage']}">云主机</a>
{/if}
{/if}
<li>{$_user['user_name']}</li>
<li><a href="login.php?a=logout">退出</a></li>
</ul>
{/if}