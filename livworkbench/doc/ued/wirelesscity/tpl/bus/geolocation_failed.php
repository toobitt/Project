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
<div class="main-wrap geolocation-failed">
	<div class="falied-pic"></div>
	<div class="buttons">
		<div class="vertical-btns">
			<a class="common-btn" href="./getloc_map.php">地图选位置</a>
			<a class="common-btn" href="./position.php">重新定位</a>
		</div>
		<div class="m2o-flex space-between-btns">
			<a class="square-btn route" href="./route.php"></a>
			<a class="square-btn station" href="./station.php"></a>
			<a class="square-btn"></a>
		</div>
	</div>
</div>