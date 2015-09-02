<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{if $cron_status}运行正常&nbsp;&nbsp;{if $_user['group_type'] < 3}<a onclick="return hg_ajax_post(this, '停止', 0);" href="admin_crontab.php?a=stop" style="color:red">停止</a>{/if}{else}停止运行&nbsp;&nbsp;{if $_user['group_type'] < 3}<a onclick="return hg_ajax_post(this, '开始', 0);" href="admin_crontab.php?a=start" style="color:green">开始</a>{/if} {/if}