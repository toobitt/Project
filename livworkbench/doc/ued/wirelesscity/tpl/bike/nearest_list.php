<?php 
$title = '自行车';
require '../../lib/head/head.php';
$icons = array(
	left => array(
		0 => 'modules'
	),
);
require '../../lib/unit/nav.php';
$bike_icon = array(
	selected => 'nearest',
	icons => array(
		0 => 'nearest',
		1 => 'filter',
		2 => 'website'
	)
);
$list = array(
	0 => array(
		areaname => '市民广场站',
		bikename => '鸿山遗址博物馆',
		bikenum => 12,
		carportnum => 3,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '144.24555m'
	),
	1 => array(
		areaname => '新区',
		bikename => '梁鸿湿地公园站',
		bikenum => 12,
		carportnum => 3,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '144.527m'
	),
	2 => array(
		areaname => '中国吴文化博览园',
		bikename => '中华赏石园',
		bikenum => 12,
		carportnum => 3,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '120m'
	),
	3 => array(
		areaname => '马山区',
		bikename => '国际博览中心站',
		bikenum => 12,
		carportnum => 3,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '180m'
	),
	4 => array(
		areaname => '崇安区',
		bikename => '无锡太科园西站',
		bikenum => 12,
		carportnum => 3,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '300m'
	),
);
require '../../lib/unit/bike/tab_subnav.php';
?>
<link href="../../lib/css/bike.css" type="text/css" rel="stylesheet" />
<div class="main-wrap bike">
	<ul class="bike-list">
	<?php foreach( $list as $k => $v ){?>
		<?php 
		require '../../lib/unit/bike/nearest_list.php';
		?>
	<?php }?>
	</ul>
</div>
<div class="mode-select"><a href="./nearest_map.php">地图模式</a></div>
</body>
</html>