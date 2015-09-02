<?php
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head}
{js:crontab}
{css:ad_style}
{css:2013/iframe}
<style type="text/css">
h3{border: 1px solid #C7D8EA;color: #3A6EA5;background: url("{$RESOURCE_URL}ybz_title.png") top repeat-x;height: 26px;line-height: 26px;font-size:12px;font-weight:bold;padding:0 10px;}
.wrap{padding:10px;}
.alert{color:red;font-weight:bold}
.stats{float:left;border-right:1px solid #ccc;}
.stats li{float:left;border-bottom:1px solid #ccc;width:50%;}
.stats .l, .stats .r{float:left;width:160px;border-left:1px solid #ccc;padding:10px;height:270px;}
.stats .r{width:370px;}
.license{clear:both;padding-top:15px;}
.license-content{padding:10px;border:1px solid #ccc;border-top:none;}
.license p{line-height:20px;}
.host {width:200px; height:130px;border:1px solid #ccc;float:left;margin:8px 8px 0 0;padding:4px}
</style>
<script type="text/javascript">
function hg_hostop_callback(id, d, msg)
{
	var html = '';
	if (d == 'boot')
	{
		html = '启动中....';
	}
	if (d == 'reboot')
	{
		html = '重启中....';
	}
	if (d == 'shutdown')
	{
		html = '关机中....';
	}
	if (d == 'fail')
	{
		html = '';
		$("#msg_" + id).html(msg);
	}
	if (html)
	{
		$("#op_" + id).html(html);
		setTimeout("document.location.href='?a=show';", 20000);
	}
}
</script>
<div class="wrap">
<div class="clearfix">
<h3>主机列表</h3>
{if $hosts}
<ul>
{foreach $hosts AS $zone =>$host}
{foreach $host['instance_set'] AS $instance}
{code}
$memory_current = $instance['memory_current'] / 1024;
{/code}
<li class="host">
<div style="height:10px;"></div>
<div>主机：{$instance['instance_name']}({$instance['instance_id']})</div>
 <div>配置：{$instance['vcpus_current']}核 {$memory_current}G </div>
<div>
 {if $instance['eip']}
 IP地址： {$instance['eip']['eip_addr']}
 {/if}&nbsp;
 </div>
 <div>系统：{$instance['image']['processor_type']} {$instance['image']['platform']}({$instance['image']['os_family']})</div>
 <div>状态：
 {if $instance['status'] == 'running'}
<span style="color:green">运行中</span>
{else if $instance['status'] == 'stoped'}
<span style="color:red">已关机</span>
 {/if}&nbsp;
 </div>
<div style="height:15px;"></div>
<div style="text-align:center;" id="op_{$instance['instance_id']}">
{if $instance['status'] == 'running'}
<a href="?a=shutdown&instance_id={$instance['instance_id']}&zone={$zone}" onclick="if (confirm('确认关机吗？')){return hg_ajax_post(this, '关机', 0);}else{return false;}">关机</a>
<a href="?a=reboot&instance_id={$instance['instance_id']}&zone={$zone}"" onclick="if (confirm('确认重启吗？')){return hg_ajax_post(this, '重启', 0);}else{return false;}">重启</a>
{else}
<a href="?a=boot&instance_id={$instance['instance_id']}&zone={$zone}"" onclick="return hg_ajax_post(this, '开机', 0);">开机</a>
{/if}
</div>
<div style="color:red;" id="msg_{$instance['instance_id']}"></div>
 </li>
{/foreach}
{/foreach}
</ul>
{else}
没有云主机
{/if}
</div>

</div>
{template:foot}