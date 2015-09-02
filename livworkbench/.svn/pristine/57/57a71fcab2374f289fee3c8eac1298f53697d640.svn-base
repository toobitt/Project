<?php 
$title = $_GET['address'];
$classnav = 'bike_special';
require '../../lib/head/head.php';
$bike_icon = array(
	selected => 'nearest',
	icons => array(
		0 => 'nearest',
		2 => 'website'
	)
);
$icons = array(
	left => array(
		0 => 'back'
	),
);
require '../../lib/unit/nav.php';
$list = array(
	0 => array(
		bikename => '鸿山遗址博物馆',
		bikenum => 12,
		carportnum => 3,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '50m'
	),
	1 => array(
		bikename => '梁鸿湿地公园站',
		bikenum => 11,
		carportnum => 5,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '60m'
	),
	2 => array(
		bikename => '中华赏石园',
		bikenum => 1,
		carportnum => 15,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '120m'
	),
	3 => array(
		bikename => '国际博览中心站',
		bikenum => 3,
		carportnum => 4,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '180m'
	),
	4 => array(
		bikename => '无锡太科园西站',
		bikenum => 6,
		carportnum => 12,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '300m'
	),
	5 => array(
		bikename => '吴文化广场',
		bikenum => 11,
		carportnum => 5,
		href => './website_general.php',
		address => '鸿山遗址博物馆广场',
		pic => '../../lib/images/bike/bike.png',
		distance => '1000m'
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
</body>
</html>