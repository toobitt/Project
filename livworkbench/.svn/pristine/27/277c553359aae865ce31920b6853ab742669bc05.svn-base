<?php 
require '../../lib/head/head.php';
$channel_info = array(
	0 => array(
		'channel_name' => '新闻综合频道',
		'program' => array(
			0 => '无锡新闻',
			1 => '第一看点',
			2 => '新闻夜线',
			3 => '服务车',
			4 => '发现'
		)
	),
	1 => array(
		'channel_name' => '都市咨询频道',
		'program' => array(
			0 => '无锡新闻',
			1 => '第一看点',
			2 => '新闻夜线'
		)
	),
	2 => array(
		'channel_name' => '经济频道',
		'program' => array(
			0 => '无锡新闻',
			1 => '第一看点',
			2 => '新闻夜线',
			3 => '服务车',
			4 => '发现'
		)
	),
	3 => array(
		'channel_name' => '娱乐频道',
		'program' => array(
			0 => '无锡新闻',
			1 => '第一看点',
			2 => '新闻夜线',
			3 => '服务车',
			4 => '发现'
		)
	),
	4 => array(
		'channel_name' => '娱乐频道',
		'program' => array(
			0 => '无锡新闻',
			1 => '第一看点',
			2 => '新闻夜线',
			3 => '服务车',
			4 => '发现'
		)
	)
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
<div class="subnav m2o-flex">
	<a class="item m2o-flex-one"  href="./program_recommend.php">节目推荐</a>
	<a class="item m2o-flex-one selected">全部频道</a>
</div>
<div class="main-wrap">
	<div class="list vod-channel-list">
	<?php foreach( $channel_info as $k => $v ){?>
		<div class="each-list">
			<?php require '../../lib/unit/list_only_title.php'; ?>
			<div class="program-wrap m2o-column-two">
			<?php foreach( $v['program'] as $kk => $vv ){?>
				<div class="program-item"><?php echo $vv?></div>
			<?php }?>
			</div>
		</div>
	<?php }?>
	</div>
</div>
</body>
</html>
<script>
$('.live-flag').click(function(){
	var parent = $(this).closest('.each-list');
	parent.toggleClass('current');
});
</script>