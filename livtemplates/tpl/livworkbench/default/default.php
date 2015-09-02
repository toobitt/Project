<?php 
/* $Id: crontab.php 3047 2011-11-07 02:17:27Z repheal $ */
?>
{template:head}
{js:crontab}
{css:ad_style}
<style type="text/css">
.table_b_t_l {margin:0 0 8px;line-height:25px;border-top:0;border-left:#ccc 1px solid;}
.table_b_b_r {border-bottom:#ccc 1px solid;border-right:#ccc 1px solid;padding:4px;}
h3{border: 1px solid #C7D8EA;border-bottom:#ccc 1px solid;color: #3A6EA5;background: url("{$RESOURCE_URL}ybz_title.png") top repeat-x;height: 26px;line-height: 26px;font-size:12px;font-weight:bold;padding:0 10px;}
.default .form_ul p{line-height:25px;}
.default .form_div{margin-bottom: 10px;width:100%;display:block;border-bottom:#ccc 1px solid;}
.default .form_ul{border-right:#ccc 1px solid;border-left:#ccc 1px solid;padding:8px}
</style>

<!--
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_index first dq"><em></em><a>首页</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
-->

<div class="wrap default" style="padding:10px;">
<div class="left ad_form h_l" style="width:50%;">
<h3>系统状态</h3>
<table width="100%" class="table_b_t_l" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15%" class="table_b_b_r">
		计划任务
		</td>
		<td class="table_b_b_r">
		<span id="crontab_state">{template:unit/cron_status}</span>
		<td>
	</tr>
	{if $index_live}
	<tr>
		<td width="20%" class="table_b_b_r">
		直播服务
		</td>
		<td class="table_b_b_r">
		<span id="trans_state">
		{if $index_live['live_status'] == 200}
		<span style="color:green">运行正常</span>
		{else}
		<span style="color:red">服务异常</span>
		{/if}
		</span>
		<td>
	</tr>
	{/if}
	{if $index_program_record}
	<tr>
		<td width="20%" class="table_b_b_r">
		录制服务
		</td>
		<td class="table_b_b_r">
		<span id="trans_state">
		{if $index_program_record['record_status'] == 200}
		<span style="color:green">运行正常</span>
		{else}
		<span style="color:red">服务异常</span>
		{/if}
		</span>
		<td>
	</tr>
	{/if}
	{if $index_livmedia}
	<tr>
		<td width="20%" class="table_b_b_r">
		视频上传服务
		</td>
		<td class="table_b_b_r">
		<span id="trans_state">
		{if $index_livmedia['upload_status'] == 200}
		<span style="color:green">运行正常</span>
		{else}
		<span style="color:red">服务异常</span>
		{/if}
		{if $index_livmedia['diskspace']['size']}
		<span style="color:{if $index_livmedia['diskspace']['size'] <= 5368709120}red{else}green{/if}">剩余空间：{$index_livmedia['diskspace']['text']}</span>
		{/if}
		</span>
		<td>
	</tr>
	<tr>
		<td width="20%" class="table_b_b_r">
		视频转码服务
		</td>
		<td class="table_b_b_r">
		<span id="trans_state">
		{if $index_livmedia['trans_status'] == 200}
		<span style="color:green">运行正常</span>
		{else}
		<span style="color:red">服务异常</span>
		{/if}
		</span>
		<td>
	</tr>
	{/if}
</table>

	<h3>系统信息</h3>
	<div class="form_div">
		<ul class="form_ul">
			<li class="i">
				<p>{$_settings['name']} {$_settings['version']}</p>
				<p>操作系统：{code}echo PHP_OS; {/code}</p>
			</li>
			<li class="i">
				<p>服务器信息：{code}echo $_SERVER['SERVER_SOFTWARE'];{/code} </p>
				<p>MySQL连接：{$dbconstr}</p>
				<p>PHP版本： {code}echo PHP_VERSION;{/code}</p>
				<p>ZEND版本：{code}echo zend_version();{/code}</p>
				{code}
				$max_execution_time = get_cfg_var("max_execution_time");
				$memory_limit = get_cfg_var("memory_limit");
				{/code}
				{if $max_execution_time}
				<p>最大执行时间：{$max_execution_time}秒</p>
				{/if}
				{if $memory_limit}
				<p>占用最大内存：{$memory_limit}</p>
				{/if}
			</li>
		</ul>
	</div>
</div>
<div class="right ad_form h_l" style="width:49%">
	<h3>个人信息</h3>
	<div class="form_div">
		<ul class="form_ul">
			<li class="i">
				<p>尊敬的 <span style="color: #8298C1;">{$_user['user_name']}</span> , 欢迎回来</p>
				<p>所属用户组：{$_user['name']}</p>
				<p>使用浏览器：{$_SERVER['HTTP_USER_AGENT']}</p>
			</li>
			<li class="i">
				<p>当前 IP：{code}echo $_SERVER['REMOTE_ADDR'];{/code}</p>
				<p>当前日期：{code}echo date("Y-m-d H:i:s");{/code}</p>
			</li>
		</ul>
	</div>
	{if $_settings['license']}
	<h3>授权信息</h3>
		<div class="form_div">
			<ul class="form_ul">
				<li class="i">	
					<p>授权用户：{$_settings['license']}</p>
					<!-- <p>到期时间：{code}echo date("Y-m-d", $_settings['license']['expire']);{/code}, 还有{code}echo intval(($_settings['license']['expire'] - TIMENOW) / 86400);{/code}天到期</p> -->
				</li>
			</ul>
		</div>
	{/if}
</div>

</div>
{template:foot}