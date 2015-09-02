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
		name => '换乘'
	),
	4 => array(
		name => '收藏',
		url => '../../tpl/bus/collect_list.php'
	),
);
require '../../lib/unit/subnav.php';
?>
<link rel="stylesheet" href="../../lib/css/bus.css" type="text/css" />
<div class="main-wrap collect-wrap collect-interchange">
	<ul class="collect-list">
	<?php for ($i=1; $i<=10; $i++){ ?>
		<li class="collect-item m2o-flex m2o-flex-center">
			<span class="flag"></span>
			<span class="name">211路</span>
			<span class="info m2o-flex-one m2o-overflow">无锡火车站方向无锡火车站方向无锡火车站方向无锡火车站方向无锡火车站方向</span>
			<a class="handle-btn"></a>
		</li>
	<?php }?>
	</ul>
</div>
<script>
$('.collect-item').click(function(){
	$(this).toggleClass('current').siblings().removeClass('current');
	
});
</script>