<?php 
require '../../lib/head/head.php';
$title = '查看评论';
$icons = array(
	left => array(
		0 => 'back'
	)
);
require '../../lib/unit/nav.php';
$comment = array(
	0 => array(
		user => 'JamesWang',
		time => '2小时前',
		comment => '中国航天员可以在空间站呆多久，普通人能否进入空间站一开眼界'
	),
	1 => array(
		user => '小乔',
		time => '2小时前',
		comment => '北京市政协委员陈善广16日透露，中国计划于2020年搭建完成首个空间站，此后，航天员在太空停驻的时间可增至180天以上。有经济基础的游客也可在其中停留几天，体验“太空旅游'
	),
	2 => array(
		user => '小张',
		time => '2小时前',
		comment => '原中国航天员科研训练中心主任'
	),
	3 => array(
		user => '小胡',
		time => '2小时前',
		comment => '他介绍说，中国的载人航天任务现已由中短期太空飞行转为长期空间驻留。此前，中国的太空最长驻留时间是2013年发射的神州十号，航天员在太空驻留了15天。到2020年空间站搭建完成后，航天员在太空的驻留时间要争取达到180天以上'
	),
	4 => array(
		user => '小陈',
		time => '2小时前',
		comment => '他提到，中国在建成一个长期运行的空间站后，普通民众也将有机会作为游客，造访空间站'
	),
	5 => array(
		user => '小安',
		time => '2小时前',
		comment => '中国在建成一个长期运行的空间站后，普通民众也将有机会作为游客，造访空间站，还可在其中驻留，短至几日，长至十多天。“太空旅行”对游客的年龄性别都没有限制，对其身体要求也不象对航天员那样严苛，“只要没有心血管系统的疾病，健康状况良好就可以'
	),
	6 => array(
		user => '小司徒',
		time => '2小时前',
		comment => '中国航天员可以在空间站呆多久，普通人能否进入空间站一开眼界'
	),
);
?>
<link rel="stylesheet" type="text/css" href="../../lib/css/news.css" />
<div class="main-wrap">
	<ul class="comment-list">
		<?php foreach ( $comment as $v ){?>
		<li class="list-item">
			<span class="user"><?php echo $v['user']?></span>
			<span class="time"><?php echo $v['time']?></span>
			<p class="comment"><?php echo $v['comment']?></p>
		</li>
		<?php }?>
	</ul>
</div>
<div class="write-comment">
	<div>写评论</div>
</div>





