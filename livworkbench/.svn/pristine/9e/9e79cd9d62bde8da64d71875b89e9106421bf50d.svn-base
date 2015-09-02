<?php 
require '../../lib/head/head.php';
$flag = '最近更新';
$list = array(
	0 => array(
		channelname => '新闻综合频道',
		live => '大城小事',
		type => 'tv'
	),
	1 => array(
		channelname => '都市咨询频道',
		live => '阿福讲故事',
		type => 'tv'
	),
	2 => array(
		channelname => '经济频道',
		live => '观点制胜（重播）',
		type => 'tv'
	),
	3 => array(
		channelname => '娱乐频道',
		live => '娱乐风尚',
		type => 'radio',
		href => './broadcast_past_list.php'
	),
	4 => array(
		channelname => '生活频道',
		live => '开心剧场',
		type => 'radio',
		href => './broadcast_past_list.php'
	),
	5 => array(
		channelname => '移动电视',
		live => '精彩节目',
		type => 'radio',
		href => './broadcast_past_list.php'
	),
);
$title = '点播';
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
<style>
.list-item .live-name{color: #999;}
</style>
<div class="subnav m2o-flex">
	<a class="item m2o-flex-one selected">节目推荐</a>
	<a class="item m2o-flex-one" href="./channel_list.php">全部频道</a>
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