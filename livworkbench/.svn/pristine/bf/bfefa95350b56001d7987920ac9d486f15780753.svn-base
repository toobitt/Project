<?php 
$list = array(
	0 => array(
			href => 'news.php',
			type => 'news',
			title => '新闻'
	),
	1 => array(
			href => 'rss_list.php',
			type => 'rss_list',
			title => '订阅'
	),
	2 => array(
			href => 'tuji.php',
			type => 'tuji',
			title => '图片'
	),
	3 => array(
			href => 'special.php',
			type => 'special',
			title => '专题'
	),
	4 => array(
			href => 'video.php',
			type => 'video',
			title => '视频'
	),
);
?>
<script src="../../lib/js/drawer.js" type="text/javascript"></script>
<link href="../../lib/css/drawer.css" type="text/css" rel="stylesheet" />
	<div class="left-column">
		<ul class="column">
		<?php 
		foreach ( $list as $k => $v ){ ?>
			<li class="drawer-list <?php echo $v['type']?>">
				<a href="<?php echo $v['href'] ?>"><?php echo $v['title'] ?></a>
			</li>
		<?php }?>		
		</ul>
	</div>
	
