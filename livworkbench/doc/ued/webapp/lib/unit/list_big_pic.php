<?php 
//大图列表页,大图+标题
$list = array(
	0 => array(
		id => 1,
		url => 'http://instasrc.com/300x120',
		title => '曝光劳模村官嫁女该村商户至少送一万',
		href => '../../tpl/module/news.php'
	),
	1 => array(
		id => 2,
		url => 'http://instasrc.com/300x122',
		title => '俄客机坠毁50人遇难 多次试降失败后爆炸',
		href => '../../tpl/module/news.php'
	),
	2 => array(
		id => 3,
		url => 'http://instasrc.com/300x123',
		title => '中方回应侦察机抵钓鱼岛:即便如此也合法',
		href => '../../tpl/module/news.php'
	)
);
?>
<ul class="m2o-list m2o-pic-list">
	<?php 
	foreach ( $list as $k => $v ){
	?>
		<li class="list-item">
			<div class="pic">
				<a href="<?php echo $v['href']?>"><img src="<?php echo $v['url']?>"/></a>
			</div>
			<div class="desc">
				<a class="title"><?php echo $v['title']?></a>
			</div>
		</li>
	<?php }?>
</ul>