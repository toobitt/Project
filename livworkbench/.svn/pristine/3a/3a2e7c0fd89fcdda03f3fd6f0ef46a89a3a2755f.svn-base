<?php 
require '../../lib/head/head.php';
$pic_intro = array(
	0 => array(
		'pic' => 'http://instasrc.com/85x53',
		'intro' => '国内外重大新闻第一时间播报，环球热点新闻重点关注，生活提示、文娱信息无所不包'
	),
);
$list = array(
	0 => array(
		time => '01-14',
		live_name => '大城小事',
		duration => '90:33',
		href => './'
	),
	1 => array(
		time => '01-13',
		live_name => '阿福讲故事',
		duration => '90:33'
	),
	2 => array(
		time => '01-12',
		live_name => '观点制胜（重播）',
		duration => '90:33'
	),
	3 => array(
		time => '01-11',
		live_name => '娱乐风尚',
		duration => '90:33'
	),
	4 => array(
		time => '01-10',
		live_name => '开心剧场',
		duration => '90:33'
	),
	5 => array(
		time => '01-09',
		live_name => '精彩节目',
		duration => '90:33'
	),
);
$title = '无锡晚高峰';
$icons = array(
	left => array(
		0 => 'modules'
	)
);
require '../../lib/unit/nav.php';
?>
<link rel="stylesheet" href="../../lib/css/live.css" type="text/css" />
<style>
.main-wrap{padding:0 15px 15px 15px;}
.detail-list .list-item,.detail-list .live-time{background:#fff;}
.detail-list .list-item .duration{margin-right:10px;}
.detail-list .live-time{width:auto;margin-left:13px;}
.broadcast-play-box{bottom:0;}
</style>
<div class="main-wrap">
	<div class="intro-wrap">
		<?php foreach ( $pic_intro as $k=>$v){?>
			<?php 
			require '../../lib/unit/pic_intro.php';
			?>
		<?php }?>
	</div>
	<ul class="list detail-list">
	<?php foreach( $list as $k => $v ){?>
		<?php  require '../../lib/unit/list_duration.php'; ?>
	<?php }?>
	</ul>
</div>

<?php 
require '../../lib/unit/player_broadcast.php'
?>
</body>
</html>