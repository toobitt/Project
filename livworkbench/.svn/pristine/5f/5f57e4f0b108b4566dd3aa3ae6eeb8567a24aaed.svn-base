<?php
require '../../lib/head/head.php';
$subnav = array(
	0 => '周四',
	1 => '周五',
	2 => '周六',
	3 => '周日',
	4 => '昨天',
	5 => '今天',
	6 => '明天',
);
$list = array(
	0 => array(
		time => '18:30',
		live_name => '大城小事',
		current => false,
		live => false,
		canplay => true
	),
	1 => array(
		time => '19:35',
		live_name => '阿福讲故事',
		current => false,
		live => false,
		canplay => true
	),
	2 => array(
		time => '19:50',
		live_name => '观点制胜（重播）',
		current => false,
		live => false,
		canplay => true
	),
	3 => array(
		time => '20:10',
		live_name => '娱乐风尚',
		current => true,
		live => false,
		canplay => true
	),
	4 => array(
		time => '20:30',
		live_name => '开心剧场',
		current => false,
		live => true,
		canplay => true
	),
	5 => array(
		time => '21:00',
		live_name => '精彩节目',
		current => false,
		live => false,
		canplay => false
	),
	6 => array(
		time => '21:00',
		live_name => '精彩节目',
		current => false,
		live => false,
		canplay => false
	),
	7 => array(
		time => '21:00',
		live_name => '精彩节目',
		current => false,
		live => false,
		canplay => false
	),
);
$tabbar = array(
	0 => 'share',
	1 => '',
	2 => 'comment'
);
$title = '交通频率';
$icons = array(
	left => array(
		0 => 'back'
	),
	right => array(
		0 => 'comment'
	)
);
require '../../lib/unit/nav.php';
?>
<link rel="stylesheet" href="../../lib/css/live.css" type="text/css" />
<?php 
require '../../lib/unit/subnav.php';
?>
<div class="main-wrap">
	<ul class="list detail-list">
	<?php foreach( $list as $k => $v ){?>
		<?php  require '../../lib/unit/list_program.php'; ?>
	<?php }?>
	</ul>
</div>
<?php 
require '../../lib/unit/player_broadcast.php';
require '../../lib/unit/tabbar.php';
?>
</body>
</html>
