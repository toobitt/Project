<?php 
$title = '投票';
$icons = array(
	left => array(
		icons => array(
			0 => 'back'
		)
	),
);
require '../../lib/head/head.php';
require '../../lib/unit/nav.php';

$list = array(
	1 => array(
		content => '惊讶！赛季初没敢奢望恒大夺冠',
	),
	2 => array(
		content => '祝贺！希望这次夺冠不是终点站',
		
	),
	3 => array(
		content => '佩服！首尔FC踢得也不错应尊重'
	)
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/vote.css" />
<div class="main-wrap vote">
	<header>
		<h1>恒大加冕亚冠冠军你怎么看？凑字数凑字数凑字数凑字数</h1>
		<p>
			<span class="vote-type">多选</span>
			<span class="date">2013-10-15</span>
			<span class="line">投票进行中</span>
			<span class="count">3421人</span>
		</p>
	</header>
	<section>
		<ul class="vote-list">
			<li class="vote-item">
				<span class="checkbox selected"></span>
				1. 恭喜！恒大创造了中国足球历史
			</li>
		<?php 
		foreach ( $list as $k => $v ){
		?>
			<li class="vote-item">
				<span class="checkbox"></span>
				<?php echo $k+1?>. <?php echo $v['content']?>
			</li>
		<?php }?>
		</ul>
		<div class="handler-btns">
			<a class="m2o-big-btn submit use-base-bg use-base-border" href="vote_result.php">提交</a>
			<a class="m2o-big-btn more" href="vote_list.php">查看更多投票</a>
		</div>
	</section>
</div>
<?php 
require '../../lib/footer/footer.php';
?>