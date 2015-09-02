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
		content => '恭喜！恒大创造了中国足球历史',
		result => '60',
		bgcolor => '#2b6cc8'
	),
	1 => array(
		content => '惊讶！赛季初没敢奢望恒大夺冠',
		result => '10',
		bgcolor => '#24c8d2'
	),
	2 => array(
		content => '祝贺！希望这次夺冠不是终点站',
		result => '40',
		bgcolor => '#f9cf2d'
	),
	3 => array(
		content => '佩服！首尔FC踢得也不错应尊重',
		result => '50',
		bgcolor => '#eb4f26'
	)
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/vote.css" />
<div class="main-wrap vote">
	<header>
		<h1>恒大加冕亚冠冠军你怎么看？凑字数凑字数凑字数凑字数</h1>
		<p>
			<span class="vote-type multi-type">多选</span>
			<span class="date">2013-10-15</span>
			<span class="line">投票进行中</span>
			<span class="count">3421人</span>
		</p>
	</header>
	<section>
		<ul class="vote-list">
		<?php 
		foreach ( $list as $k => $v ){
		?>
			<li class="vote-item">
				<?php echo $k+1?>. <?php echo $v['content']?>
				<div class="result m2o-flex m2o-flex-center">
					<div class="line" style="width:<?php echo $v['result']?>%;background:<?php echo $v['bgcolor']?>"></div>
					<span class="percent"><?php echo $v['result']?>%</span>
				</div>
			</li>
		<?php }?>
		</ul>
		<div class="handler-btns">
			<a class="m2o-big-btn more" href="vote_list.php">查看更多投票</a>
		</div>
	</section>
</div>
<?php 
require '../../lib/footer/footer.php';
?>