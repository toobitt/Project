<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{code}
$cnameMap = array(
	8 => 'app-item-blue',
	2 => 'app-item-red',
	3 => 'app-item-green',
	7 => 'app-item-yellow',
	4 => 'app-item-green_2',
	5 => 'app-item-purple',
	6 => 'app-item-pink',
	1 => 'app-item-blue_2'
);
$statusCnameMap = array(
	0 => '',
	1 => 'install',
	-1 => 'update',
);
$statusLabelMap = array( 1 => '已安装', -1 => '有更新' );
//hg_pre($menu_apps);
{/code}
{template:head}
{css:appstore}
{js:underscore}
{js:appstore/app}

<script>
var app = '{$app}';
</script>
	<div class="nav-bg">
		<div class="nav">
			<span class="logo">应用商店</span>
			<ul>
				<li class="current">全部</li>
				{foreach $menu_group as $id => $group}
				<li data-id="{$id}">{$group['name']}</li>
				{/foreach}
			</ul>
		</div>
		<a class="close"></a>
	</div>
	
	<div class="middle-ware"></div>
	
	<div class="app-main">
		{code}$index = 0;{/code}
		{foreach $menu_apps as $group => $apps}
		<div class="app-group clear" id="group_{$group}">
			{code}$cname = $cnameMap[$group];{/code}
			<div class="app-item {$cname}">
				<ul>
				{foreach $apps as $app}
					<li data-app_uniqueid="{$app['app_uniqueid']}">
						<a>
							<img width="226" height="128" original="{$RESOURCE_URL}appstore/app/{$app['app_uniqueid']}.png">
							<em>
								{$app['name']}
								{if $app['status'] != 0}
								{code}
								$cname = $statusCnameMap[$app['status']];
								$label = $statusLabelMap[$app['status']];
								{/code}
								<span class="{$cname}">{$label}</span>
								{/if}
							</em>
						</a>
					</li>
				{/foreach}
				</ul>
			</div>
		</div>
		{/foreach}
	</div>
	<div class="app-tools">
	<div style="cursor:pointer">小工具</div>
	<ul>
		<li><a href="appstore.php?a=rebuild_global_conf" target="toolsiframe">重建全局配置</a></li>
		<li><a href="appstore.php?a=rebuild_templates" target="toolsiframe">重建所有模板</a></li>
		<li><a href="appstore.php?a=sync_js" target="toolsiframe" title="各应用同步更新为m2o的版本">更新js</a></li>
		<!-- <li><a href="appstore.php?a=rebuild_hosts" target="toolsiframe">重建hosts配置</a></li> -->
		<li><a href="appstore.php?a=modify_serv_hosts" target="toolsiframe">更改hosts配置</a></li>
		<li><a href="appstore.php?a=onekupdate" target="toolsiframe">一键更新所有应用</a></li>
	</ul>
	<iframe name="toolsiframe" id="toolsiframe" src=""></iframe>
	</div>
	<div id="edit-area">
		<div class="edit-content-wrap">
			<div class="edit-content"></div>
			<div id="features"></div>
			<div class="iframe-wrapper">
				<iframe name="iframe" id="iframe"></iframe>
				<a class="iframe-back">返回</a>
			</div>
			<div class="edit-close">x</div>
		</div>
	</div>
	
	<script type="tpl" id="edit-info-tpl">
	<% if (obj.wait) { %>
	<img class="waiting-img" src="{$RESOURCE_URL}loading2.gif" />
	<% } else { %>
	<h2 style="background-image:url({$RESOURCE_URL}menu2013/app/<%= appinfo.app_uniqueid %>.png);"><%= appinfo.name %></h2>
	<div class="edit-info">
		<% if (appinfo.status == -1) { %>
		<a class="btn" target="iframe" href="appstore.php?a=upgrade&app=<%= appinfo.app_uniqueid %>">
			更新<% if (appinfo.install_version) { %>
				<span style="font-size:10px">当前版本: &nbsp;<%= appinfo.install_version %></span>
			<% } %></a>
		<% } else if (appinfo.status == 1) { %>
		<a class="btn" target="iframe"  href="run.php?a=relate_module_show&app_uniq=<%= appinfo.app_uniqueid %>">打开</a>
		<% } else if (appinfo.status == 0) { %>
		<a class="btn" target="iframe"  href="appstore.php?a=install&app=<%= appinfo.app_uniqueid %>">安装</a>
		<%  } %>
	</div>
	<% } %>
	</script>
	
</body>
</html>