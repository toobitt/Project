<?php 
$title = '我的收藏';
require '../../lib/head/head.php';
$icons = array(
	left => array(
		icons => array(
			0 => 'back'
		)
	),
	right => array(
		0 => 'delete',
	),
);
$foot_icon = array(
	selected => 'picture',
	icons => array(
		0 => 'article',
		1 => 'picture',
	)
);
require '../../lib/unit/nav.php';
require '../../lib/unit/collect_nav.php';
$list = array(
	0 => array(
		id => 1,
		url1 => '../../lib/images/pic1.jpg',
		url2 => '../../lib/images/pic2.jpg',
		url3 => '../../lib/images/pic3.jpg',
		title => '曝光劳模村官嫁女 该村商户至少送1万',
		tm => '2013-11-11 16:25',
		src => '../../tpl/collect/collect_article.php'
	),
	1 => array(
		id => 2,
		url1 => '../../lib/images/pic1.jpg',
		url2 => '../../lib/images/pic2.jpg',
		url3 => '../../lib/images/pic3.jpg',
		title => '传朱丽倩怀男 乘机到港探望华仔给惊喜',
		tm => '2013-11-09 20:40',
		src => '../../tpl/collect/collect_article.php'
	),
	2 => array(
		id => 3,
		url1 => '../../lib/images/pic1.jpg',
		url2 => '../../lib/images/pic2.jpg',
		url3 => '../../lib/images/pic3.jpg',
		title => '中国铁路改发委官员 下步应拆分铁总',
		tm => '2013-11-04 09:35',
		src => '../../tpl/collect/collect_article.php'
	),
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/list.css" />
<div class="main-wrap collect_picture">
<ul class="m2o-list pic-list">
	<?php 
	foreach ( $list as $k => $v ){
	require '../../lib/unit/collect_piclist.php';
	}?>
</ul>
</div>
<?php 
require '../../lib/footer/footer.php';
?>