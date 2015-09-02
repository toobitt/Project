<?php 
$title = '投票';
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
		sub_title => '娱乐',
		info => array(
			0 => array(
				id => 1,
				type => '专题',
				url => 'http://instasrc.com/80x50',
				slide_url => '../../lib/images/pic1.jpg',
				title => '坠毁50人遇',
				descr => '村官嫁女共收礼金83万元',
				src => '../../tpl/module/news.php'
			),
			1 => array(
				id => 2,
				type => '图集',
				url => 'http://instasrc.com/80x51',
				slide_url => '../../lib/images/pic2.jpg',
				title => '俄客机坠毁50人遇难 多次试降失败后爆炸',
				descr => '事业单位改革逐步取消行政级别',
				src => '../../tpl/tuji/tuji.php'
			),
			2 => array(
				id => 3,
				type => '视频',
				url => '../../lib/images/pic3.jpg',
				title => '中方回应侦察机抵钓鱼岛:即便如此也合法',
				descr => '3721名干部违反八项规定受处分'
			),
		)
	),
	1 => array(
		sub_title => '政治',
		info => array(
			0 => array(
				id => 1,
				type => '专题',
				url => 'http://instasrc.com/80x50',
				slide_url => '../../lib/images/pic1.jpg',
				title => '中方回应侦察机抵钓鱼岛:即便如此也合法',
				descr => '村官嫁女共收礼金83万元',
				src => '../../tpl/module/news.php'
			),
			1 => array(
				id => 2,
				type => '图集',
				url => 'http://instasrc.com/80x51',
				slide_url => '../../lib/images/pic2.jpg',
				title => '政治政治俄客机坠毁50人遇难 多次试降失败后爆炸',
				descr => '事业单位改革逐步取消行政级别',
				src => '../../tpl/tuji/tuji.php'
			),
		)
	),
	2 => array(
		sub_title => '体育',
		info => array(
			0 => array(
				id => 1,
				type => '专题',
				url => 'http://instasrc.com/80x50',
				slide_url => '../../lib/images/pic1.jpg',
				title => '恒大加冕亚冠冠军',
				descr => '村官嫁女共收礼金83万元',
				src => '../../tpl/module/news.php'
			),
			1 => array(
				id => 2,
				type => '图集',
				url => 'http://instasrc.com/80x51',
				slide_url => '../../lib/images/pic2.jpg',
				title => '政治政治俄客机坠毁50人遇难 多次试降失败后爆炸',
				descr => '事业单位改革逐步取消行政级别',
				src => '../../tpl/tuji/tuji.php'
			),
		)
	)
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/list.css" />
<link rel="stylesheet" type="text/css" href="../../lib/css/subnav.css" />
<style>
.main-wrap{background:#fff;padding-top:0;}
</style>
<div class="main-wrap">
	<?php 
	foreach ( $list as $k => $v ){
		require '../../lib/unit/sub_title.php';
		?>
		<ul class="m2o-list common-list">
			<?php 
			foreach ( $v['info'] as $kk => $v ){
			require '../../lib/unit/list_only_title.php';
			}?>
		</ul>
	<?php }?>
</div>
<?php 
require '../../lib/footer/footer.php';
?>