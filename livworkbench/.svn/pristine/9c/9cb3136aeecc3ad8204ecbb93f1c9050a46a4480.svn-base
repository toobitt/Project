<?php 
//普通列表页,左图+标题+描述
$list = array(
	0 => array(
		id => 1,
		type => '专题',
		url => 'http://instasrc.com/80x50',
		slide_url => '../../lib/images/pic1.jpg',
		title => '【内容-文稿】曝光劳模村一万曝光劳模村一万',
		descr => '村官嫁女共收礼金83万元',
		src => '../../tpl/module/news.php'
	),
	1 => array(
		id => 2,
		type => '图集',
		url => 'http://instasrc.com/80x51',
		slide_url => '../../lib/images/pic2.jpg',
		title => '【内容-图集】俄客机坠毁50人遇难 多次试降失败后爆炸',
		descr => '事业单位改革逐步取消行政级别',
		src => '../../tpl/tuji/tuji.php'
	),
	2 => array(
		id => 3,
		type => '视频',
		slide_url => 'images/pic3.jpg',
		slide_url => '../../lib/images/images_pic5.jpg',
		title => '中方回应侦察机抵钓鱼岛:即便如此也合法',
		descr => '3721名干部违反八项规定受处分',
		src => '../weather/weather.php'
	)
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/list.css" />
<ul class="m2o-list common-list">
<?php 
foreach ( $list as $k => $v ){
?>
	<li class="m2o-flex list-item">
		<div class="pic">
			<img src="<?php echo $v['url']?>" alt="<?php echo $v['title']?>" />
			<span class="pic-flag"><?php echo $v['type']?></span>
		</div>
		<div class="m2o-flex-one desc m2o-flex m2o-vertical">
			<h3 class="ft16 m2o-flex-one">
				<a class="title" href="<?php echo $v['src']?>"><?php echo $v['title']?></a>
			</h3>
			<p class="sub-title"><?php echo $v['descr']?></p>
		</div>
	</li>
<?php }?>
</ul>