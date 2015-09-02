<?php 
$title = '编辑栏目';
$icons = array(
	left => array(
		icons => array(
			0 => 'back'
		)
	),
	right => array(
		0 => 'avatar',
	),
);
require '../../lib/head/head.php';
require '../../lib/unit/nav.php';


$list = array(
	0 => array(
		sub_title => '我的订阅',
		tip => '(点击添加，拖动顺序)',
		info => array(
			0 => '头条',
			1 => '娱乐',
			2 => '体育',
			3 => '头条',
			4 => '娱乐',
			5 => '体育',
			6 => '头条',
			7 => '娱乐',
		)
	),
	1 => array(
		sub_title => '可添加的订阅',
		info => array(
			0 => '头条',
			1 => '娱乐',
			2 => '体育',
			3 => '头条',
			4 => '娱乐',
			5 => '体育',
			6 => '头条',
			7 => '娱乐',
		)
	),
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/list.css" />
<link rel="stylesheet" type="text/css" href="../../lib/css/subnav.css" />
<style>
.main-wrap{background:#fff;padding-top:0;}
.rss-wrap{padding:0.8em 0;}
.rss-item{display: block;background: #f7f7f7;font-size: 1.6em;color: #434343;text-align: center;height: 2.5em;line-height: 2.5em;margin-bottom: 0.5em;}
</style>
<div class="main-wrap">
	<?php 
	foreach ( $list as $k => $v ){
		require '../../lib/unit/sub_title.php';
		?>
		<div class="rss-wrap m2o-column-four">
			<?php 
			foreach ( $v['info'] as $kk => $v ){
			?>
			<span class="rss-item"><?php echo $v?></span>
			<?php  }?>
		</div>
	<?php }?>
	<a class="m2o-big-btn">加载更多</a>
</div>
