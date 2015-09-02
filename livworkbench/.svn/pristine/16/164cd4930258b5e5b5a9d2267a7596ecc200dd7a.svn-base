<?php 
$title = $_GET['address'];
$classnav = 'bike_special';
require '../../lib/head/head.php';
$bike_icon = array(
	selected => 'website',
	icons => array(
		0 => 'nearest',
		2 => 'website',
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
		inform => '太湖新城市民广场迁移通知',
		websitetime => '20分钟前',
		href => './area_nearest.php',
		address => '太湖新城',
		areaname => '市民官场站'
	),
	1 => array(
		inform => '朝阳车站开站通知',
		websitetime => '1天前',
		href => './area_nearest.php',
		address => '崇安区',
		areaname => '朝阳车站'
	),
	2 => array(
		inform => '国邮大厦站点维修通知',
		websitetime => '2天前',
		href => './area_nearest.php',
		address => '太湖新城',
		areaname => '国邮大厦站'
	),
	3 => array(
		inform => '下月市民卡可租用公共自行车',
		websitetime => '3天前',
		href => './area_nearest.php',
		address => '',
		areaname => ''
	),
	4 => array(
		inform => '南禅寺朝阳广场站临时拆除通知',
		websitetime => '3月1日',
		href => './area_nearest.php',
		address => '朝阳广场站',
		areaname => '国邮大厦站'
	),
	5 => array(
		inform => '3Q租车会员卡满一年后须延长有效期',
		websitetime => '2月10日',
		href => './area_nearest.php',
		address => '',
		areaname => ''
	),
);
require '../../lib/unit/bike/tab_subnav.php';
?>
<link href="../../lib/css/bike.css" type="text/css" rel="stylesheet" />
<div class="main-wrap bike">
	<ul class="bike-list">
	<?php foreach( $list as $k => $v ){?>
		<?php 
		require '../../lib/unit/bike/website_list.php';
		?>
	<?php }?>
	</ul>
</div>
</body>
</html>