<?php 
require '../../lib/head/head.php';
$flag = '正在播放';
$list = array(
	0 => array(
		channelname => '新闻综合频道',
		live => '大城小事',
		href => './tv_detail.php'
	),
	1 => array(
		channelname => '都市咨询频道',
		live => '阿福讲故事',
		href => './tv_detail.php'
	),
	2 => array(
		channelname => '经济频道',
		live => '观点制胜（重播）',
		href => './tv_detail.php'
	),
	3 => array(
		channelname => '娱乐频道',
		live => '娱乐风尚',
		href => './tv_detail.php'
	),
	4 => array(
		channelname => '生活频道',
		live => '开心剧场',
		href => './tv_detail.php'
	),
	5 => array(
		channelname => '移动电视',
		live => '精彩节目',
		href => './tv_detail.php'
	),
);
$title = '直播';
$icons = array(
	left => array(
		0 => 'modules'
	),
	right => array(
		0 => 'info'
	)
);
require '../../lib/unit/nav.php';
?>

<link rel="stylesheet" href="../../lib/css/live.css" type="text/css" />
<div class="subnav m2o-flex">
	<a class="item m2o-flex-one selected">电视</a>
	<a class="item m2o-flex-one" href="./broadcast_list.php">广播</a>
</div>
<div class="main-wrap">
	<ul class="list">
	<?php foreach( $list as $k => $v ){?>
		<?php 
		require '../../lib/unit/list.php';
		?>
	<?php }?>
	</ul>
</div>
</body>
</html>