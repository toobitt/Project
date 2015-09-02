<?php 
$title = $_GET['address'];
$name = $_GET['name'];
require '../../lib/head/head.php';
$bike_icon = array(
	selected => 'notice',
	icons => array(
		0 => 'general',
		1 => 'notice'
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
		websitetime => '3月1日',
		href => './website_noticeinfo.php',
	),
	1 => array(
		inform => '朝阳车站开站通知',
		websitetime => '3月1日',
		href => './website_noticeinfo.php',
	),
	2 => array(
		inform => '国邮大厦站点维修通知',
		websitetime => '3月1日',
		href => './website_noticeinfo.php',
	),
	3 => array(
		inform => '下月市民卡可租用公共自行车',
		websitetime => '3月1日',
		href => './website_noticeinfo.php',
	),
	4 => array(
		inform => '南禅寺朝阳广场站临时拆除通知',
		websitetime => '3月1日',
		href => './website_noticeinfo.php',
	),
	5 => array(
		inform => '3Q租车会员卡满一年后须延长有效期',
		websitetime => '2月10日',
		href => './website_noticeinfo.php',
	),
);
?>
<link href="../../lib/css/bike.css" type="text/css" rel="stylesheet" />
<?php require '../../lib/unit/bike/website_tab.php';?>
<div class="main-wrap bike">
	<ul class="bike-list">
	<?php foreach( $list as $k => $v ){?>
		<?php 
		require '../../lib/unit/bike/website_noticelist.php';
		?>
	<?php }?>
	</ul>
</div>
</body>
</html>