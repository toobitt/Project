<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{if $trans_status['pid']}已运行{$trans_status['runtime']}，有 {$trans_status['trans_file_num']}个视频正在转码中 {if $_user['group_type'] < 3}<a href="status.php?a=trans_stop" style="color:red">停止转码</a>{/if} {else} {if $_user['group_type'] < 3}已停止<a href="status.php?a=trans_start" style="color:green">启动转码</a>{/if}{/if}