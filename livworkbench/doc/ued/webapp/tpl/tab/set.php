<?php 
$title = '个人中心';
$icons = array(
	left => array(
	),
	right => array(
		0 => 'avatar',
	),
);
$foot_icon = array(
	selected => 'home',
	icons => array(
		0 => 'home',
		1 => 'pic',
		2 => 'topics',	//专题
		3 => 'video',
		4 => 'more'
	)
);
require '../../lib/head/head.php';
require '../../lib/unit/nav.php';
?>
<link href="../../lib/css/page.css" type="text/css" rel="stylesheet" />
<link href="../../lib/css/personal.css" type="text/css" rel="stylesheet" />
<section class="main-wrap personal set_personal">
<div class="content">
	<ul class="list">
		<li class="push">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_push.png" /><span>推送设置</span></a>
			<div class="config next on"><a class="anchor-next" href="#"></a></div>
		</li>
		<li class="wipe">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_delete.png" /><span>清除缓存</span></a>
			<div class="config">0.0KB</div>
		</li>
		<li class="examine">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_update.png" /><span>检查更新</span></a>
			<div class="config next"><a class="anchor-next" href="#"></a></div>
		</li>
	</ul>
	<ul class="list">
		<li class="feedback">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_opinion.png" /><span>意见反馈</span></a>
			<div class="config next"><a class="anchor-next" href="#"></a></div>
		</li>
		<li class="recommend">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_recommend.png" /><span>推荐给好友</span></a>
			<div class="config next"><a class="anchor-next" href="#"></a></div>
		</li>
		<li class="about">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_about.png" /><span>关于我们</span></a>
			<div class="config next"><a class="anchor-next" href="#"></a></div>
		</li>
	</ul>
</div>
</section>
<?php 
require '../../lib/unit/tab_foot.php';
?>
<?php 
require '../../lib/footer/footer.php';
?>