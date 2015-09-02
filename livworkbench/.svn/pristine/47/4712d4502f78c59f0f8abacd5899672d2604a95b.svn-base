<?php 
$title = '自行车';
$flag = '离你最近';
require '../../lib/head/head.php';
$icons = array(
	left => array(
		0 => 'modules'
	),
);
require '../../lib/unit/nav.php';
$bike_icon = array(
	selected => 'filter',
	icons => array(
		0 => 'nearest',
		1 => 'filter',
		2 => 'website',
	)
);
$list = array(
	0 => array(
		areaname => '太湖新城',
		websitenum => 12,
		href => './area_nearest.php',
		address => '市民广场站',
		distance => '220m'
	),
	1 => array(
		areaname => '新区',
		websitenum => 3,
		href => './area_nearest.php',
		address => '无锡软件园站',
		distance => '220m'
	),
	2 => array(
		areaname => '中国吴文化博览园',
		websitenum => 8,
		href => './area_nearest.php',
		address => '无锡软件园站',
		distance => '220m'
	),
	3 => array(
		areaname => '崇安区',
		websitenum => 22,
		href => './area_nearest.php',
		address => '太湖新城',
		distance => '220m'
	),
	4 => array(
		areaname => '马山区',
		websitenum => 12,
		href => './area_nearest.php',
		address => '市民广场站',
		distance => '220m'
	),
	5 => array(
		areaname => '锡山区',
		websitenum => 12,
		href => './area_nearest.php',
		address => '金匮区',
		distance => '220m'
	),
);
require '../../lib/unit/bike/tab_subnav.php';
?>
<link href="../../lib/css/bike.css" type="text/css" rel="stylesheet" />
<div class="main-wrap bike">
	<ul class="bike-list">
	<?php foreach( $list as $k => $v ){?>
		<?php 
		require '../../lib/unit/bike/filter_list.php';
		?>
	<?php }?>
	</ul>
</div>
<!-- <div class="mode-select"><a href="">地图模式</a></div> -->
</body>
</html>