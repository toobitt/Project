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
</style>

<div class="wrap">
<div class="clearfix">
<h3>系统状态</h3>

<ul class="stats">
    <li><span class="l">计划任务</span><span class="r"><span id="crontab_state">{template:unit/cron_status}</span></span></li>
    {code}
    $counts = 2;
    {/code}
    {if $app_stats}
    	{foreach $app_stats AS $k => $v}
    	{code}$counts++;{/code}
    	<li>
    	<span class="l">{$v['name']} <div><当前版本:{code}echo $v['version']?$v['version']:'未知';{/code}></div></span>
    	<span class="r">
    	    {if $v['http_code'] == 200}
            <span style="color:green">接口正常
            {if !$v['inited']}
            <span style="color:red;margin-right:10px;">系统未初始化</span><a href="run.php?a=relate_module_show&app_uniq={$k}" style="color:blue;">现在初始化</a>
            {/if}
            {else}
            <span style="color:red" title="http_code:{$v['http_code']}">接口异常
            {/if}
			<div style="border:1px solid #ccc;clear:both;padding:4px;color:#999;width:99%;">
				<div>程序位置: {$v['host']} {if $v['ip']} <{$v['ip']}> {/if}</div>
				<div>程序目录: {$v['api_dir']} </div>
				<div>调试模式: {if $v['debuged']}<span style="color:red;">是</span>{else}<span style="color:green;">否</span>{/if}</div>
				<div>配置文件可写: {if $v['config_file_purview']==1||empty($v['config_file_purview'])}<span style="color:green;">是</span>{else if($v['config_file_purview']=='-1')}<span style="color:red;">否</span>{/if}</div>
				<div>DATA目录可写: {if $v['data_file_purview']==1||empty($v['data_file_purview'])}<span style="color:green;">是</span>{else if($v['data_file_purview']=='-1')}<span style="color:red;">否</span>{/if}</div>
				<div>CACHE目录可写: {if $v['cache_file_purview']==1||empty($v['cache_file_purview'])}<span style="color:green;">是</span>{else if($v['cache_file_purview']=='-1')}<span style="color:red;">否</span>{/if}</div>
				{if $v['db']}
					<div>数据库位置: {$v['db']['host']} {if $v['db']['ip']}<{$v['db']['ip']}>{/if} </div>
					<div>数据库名: {$v['db']['database']} </div>
					<div>连接成功: {if $v['dbconnected']}<span style="color:green;">是</span>{else}<span style="color:red;">否</span>{/if}</div>
					<div>连接耗时: {$v['connect_time']} </div>
				{/if}
				{if $v['freespace']}
					<div>根分区剩余空间: <span style="color:{if $v['freespace']['rootfree']['size'] <= 5368709120}red{else}green{/if}">剩余空间：{$v['freespace']['rootfree']['text']}</span> </div>
					<div>当前分区剩余空间: <span style="color:{if $v['freespace']['curfree']['size'] <= 5368709120}red{else}green{/if}">剩余空间：{$v['freespace']['curfree']['text']}</span> </div>
				{/if}
				<div>运行时间: {$v['runtime']} </div>
			</div></span>
    	</span>
    	</li>
    	{/foreach}
    {/if}
    {if $vod_status}
    <li>
        <span class="l">视频上传服务</span>
        <span class="r">
            <span id="trans_state">
            {if $vod_status['upload_status'] == 200}
            <span style="color:green">运行正常</span>
            {else}
            <span style="color:red">服务异常</span>
            {/if}
            {if $vod_status['diskspace']['size']}
            <span style="color:{if $index_livmedia['diskspace']['size'] <= 5368709120}red{else}green{/if}">剩余空间：{$vod_status['diskspace']['text']}</span>
            {/if}
            </span>
        <span>
    </li>
    {/if}

    {if $counts / 2 != 0}
        <li><span class="l">&nbsp;</span><span class="r">&nbsp;</span></li>
    {/if}
</ul>


{if $license}
<div class="license">
    <h3>授权信息</h3>
    <div class="license-content">
        <p>授权用户：{$license['custom_name']}</p>
        <p>授权域名：{$license['domain']}</p>
        {if $license['expire_time']}
        <p{if $license['leftday']<30} class="alert"{/if}>到期时间：{$license['expire']}, 还有{$license['leftday']}天到期</p>
        {else}
        <p>永久授权</p>
        {/if}
    </div>
</div>
{/if}
</div>

</div>
{template:foot}