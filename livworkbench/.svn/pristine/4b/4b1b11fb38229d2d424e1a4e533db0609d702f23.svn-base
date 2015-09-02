<?php
require '../../lib/head/head.php';
$title = '公交';
$icons = array(
	left => array(
		0 => 'modules'
	),
);
require '../../lib/unit/nav.php';
$subnav = array(
	0 => array(
		name => '附近',
		url => '../../tpl/bus/geolocation_failed.php'
	),
	1 => array(
		name => '线路',
		url => '../../tpl/bus/route.php'
	),
	2 => array(
		name => '站点',
		url => '../../tpl/bus/station.php'
	),
	3 => array(
		name => '换乘',
		url => '../../tpl/bus/interchange.php'
	),
	4 => array(
		name => '收藏',
		url => '../../tpl/bus/collect_list.php'
	),
);
require '../../lib/unit/subnav.php';
?>
<link rel="stylesheet" href="../../lib/css/bus.css" type="text/css" />
<style>
.search-area{position:relative;}
.change-btn{position: absolute;width: 22px;height: 22px;background-color: #fff;top:40px;left:10px;}
</style>
<div class="bus-inner">
	<div class="search-area">
		<div class="common-input-box">
			<div class="input-item m2o-flex m2o-flex-center">
				<span class="icon icon-location"></span>
				<input class="input m2o-flex-one" placeholder="输入起始站" value=""/>
				<span class="handle-btn location"></span>
			</div>
			<div class="input-item m2o-flex m2o-flex-center">
				<span class="icon icon-location"></span>
				<input class="input m2o-flex-one" placeholder="输入终点站" value=""/>
				<span class="handle-btn location"></span>
			</div>
		</div>
		<a class="change-btn icon-interchange"></a>
	</div>
	<div class="vertical-btns">
		<a class="common-btn">查询</a>
	</div>
	<div class="query-area">
		<span class="title">最近查询</span>
		<a class="clear-btn"></a>
		<div class="recent-query">
			<span>港下</span>
			<span>运河饭店</span>
			<span>无锡中央车站</span>
			<span>解放北路</span>
			<span>港下</span>
			<span>解放北路</span>
			<span>无锡中央车站</span>
		</div>
	</div>
</div>