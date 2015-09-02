<?php
require '../../lib/head/head.php';

$index_icons = array(
//	0 => array(
//		name => '今日看点',
//		classname => 'topic'
//	),
	1 => array(
		name => '新浪微博',
		classname => 'weibo',
		href => 'http://www.sina.com'
	),
	2 => array(
		name => '头条热点',
		classname => 'hot',
		href => 'http://www.baidu.com'
	),
	3 => array(
		name => '汽车频道',
		classname => 'car'
	),
	4 => array(
		name => '精彩专题',
		classname => 'special'
	),
	5 => array(
		name => '健康频道',
		classname => 'health'
	),
	6 => array(
		name => '旅游频道',
		classname => 'travel'
	),
	7 => array(
		name => '教育频道',
		classname => 'educate'
	)
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/magazine/index.css" />
<div class="m2o-flex index-outer m2o-flex-center index">
<div class="m2o-flex index-wrap">
	<section class="m2o-flex-one index-icons">
			<div class="index-item topic selected">
				<a href="" class="index-icon topic">今日看点</a>
			</div>
		<?php foreach ( $index_icons as $k=>$v ) {?>
			<div class="index-item <?php echo $v['classname']?>">
				<a href="<?php echo $v['href']?>" class="index-icon <?php echo $v['classname']?>"><?php echo $v['name']?></a>
			</div>
		<?php }?>
	</section>
	<aside class="m2o-flex m2o-vertical handler-box">
		<div class="m2o-flex-one">
			<a class="user" href="../member/login_personal.php?theme=blue">
				<img src="../../lib/images/sunny.png" />
			</a>
		</div>
		<div class="handler-btns">
			<a class="index-btn collect" href="../collect/collect_article.php?theme=blue"></a>
			<a class="index-btn add" href="../module/rss_list.php?theme=blue"></a>
			<a class="index-btn set" href="../setting/theme.php?theme=blue"></a>
		</div>
	</aside>
</div>
</div>
<?php 
require '../../lib/footer/footer.php';
?>