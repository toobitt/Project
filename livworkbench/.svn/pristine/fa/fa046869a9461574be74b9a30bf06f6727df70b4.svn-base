<?php
require '../../lib/head/head.php';
?>
<link rel="stylesheet" href="../../lib/css/gmu/tabs/tabs.default.css" type="text/css">
<link rel="stylesheet" href="../../lib/css/gmu/tabs/tabs.css" type="text/css">
<script src="../../lib/js/gmu/widget/tabs/tabs.js"></script>
<?php 
$bus = array(
	0 => array(
		num => '211',
		end => '中央公园',
		nearest => '运河饭店',
		distance => '1',
		special => '环形'
	),
	1 => array(
		num => '56',
		end => '金城桥',
		nearest => '运河饭店',
		distance => '1',
		special => '环形'
	),
	2 => array(
		num => '131',
		end => '市民中心西',
		nearest => '运河饭店',
		distance => '1',
	),
	3 => array(
		num => '宜家',
		end => '崇山',
		nearest => '运河饭店',
		distance => '1',
		special => '专线'
	),
	4 => array(
		num => '211',
		end => '中央公园',
		nearest => '运河饭店',
		distance => '1',
		special => '环形'
	)
);
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
<div class="main-wrap">
<?php foreach( $bus as $k=> $v ){ ?>
	<div class="bus-item">
		<div class="m2o-flex bus-info m2o-flex-center">
			<div class="bus-base-info">
				<p class="bus-num blue"><?php echo $v['num']?></p>
				<p class="bus-special-flag blue"><?php echo $v['special']?></p>
			</div>
			<div>
				<p class="bus-end">开往<span class="blue"><?php echo $v['end']?></span></p>
				<p class="nearest">离我最近站<span class="red"><?php echo $v['nearest']?></span></p>
			</div>
		</div>
		<div class="handle m2o-flex m2o-flex-center">
			<p class="distance m2o-flex-one">最近一班车距离<span class="red"><?php echo $v['distance']?>站</span></p>
			<a class="btn reverse"></a>
			<a class="btn favor"></a>
		</div>
	</div>
<?php }?>
</div>
<?php 
require '../map/baidu_map.php';
?>