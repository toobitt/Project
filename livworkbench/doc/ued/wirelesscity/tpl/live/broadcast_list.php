<?php 
require '../../lib/head/head.php';
$flag = '正在播放';
$list = array(
	0 => array(
		channelname => '新闻广播',
		live => '新闻讲述',
		href => './broadcast_detail.php',
		type => 'radio'
	),
	1 => array(
		channelname => '经济频率',
		live => '咨询云搜索',
		href => './broadcast_detail.php',
		type => 'radio'
	),
	2 => array(
		channelname => '汽车音乐频率',
		live => '健康时间',
		href => './broadcast_detail.php',
		type => 'radio'
	),
	3 => array(
		channelname => '交通频率',
		live => '欢乐直通车',
		type => 'radio'
	),
	4 => array(
		channelname => '江南之声频率',
		live => '就听好歌不说话',
		type => 'radio'
	),
	5 => array(
		channelname => '都市生活频率',
		live => '传奇书场',
		type => 'radio'
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
	<a class="item m2o-flex-one" href="./tv_list.php">电视</a>
	<a class="item m2o-flex-one selected">广播</a>
</div>
<div class="main-wrap">
	<ul class="list broadcast-list">
		<li class="list-item m2o-flex m2o-flex-center radio is-play">
			<span class="list-pic">
				<img src="http://instasrc.com/80x50"/>
			</span>
			<div class="info m2o-flex-one">
				<h3 class="channel-name"><?php echo $list[0]['channelname']?></h3>
				<p>正在播放：<span class="live-name"><?php echo $list[0]['live']?></span></p>
			</div>
			<a class="live-flag" href="./broadcast_detail.php"></a>
		</li>
	<?php foreach( $list as $k => $v ){?>
		<?php 
		require '../../lib/unit/list.php';
		?>
	<?php }?>
	</ul>
</div>
</body>
</html>