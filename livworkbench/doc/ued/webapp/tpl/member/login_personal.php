<?php 
$title = '个人中心';
$icons = array(
	left => array(
		icons => array(
			0 => 'back'
		)
	),
);
require '../../lib/head/head.php';
require '../../lib/unit/nav.php';
?>
<link href="../../lib/css/page.css" type="text/css" rel="stylesheet" />
<link href="../../lib/css/personal.css" type="text/css" rel="stylesheet" />
<section class="main-wrap personal login_personal">
<div class="head">
	<div class="head-area">
		<p>
			<a class="login-icon" href="logining_personal.php">
			</a>
		</p>
		<span class="addpic"></span>
	</div>
	<h3><a href="logining_personal.php">登录账号</a></h3>
</div>
<div class="content">
	<ul class="list">
		<li class="collect">
			<a class="anchor" href="../../tpl/collect/collect_article.php"><img src="../../lib/images/personal/icon_collect.png" /><span>我的收藏</span></a>
			<div class="config next"><a class="anchor-next" href="../../tpl/collect/collect_article.php"></a></div>
		</li>
		<li class="weather">
			<a class="anchor" href="../../tpl/weather/weather.php"><img src="../../lib/images/personal/icon_weather.png" /><span>南京天气</span></a>
			<div class="config">22/18</div>
		</li>
	</ul>
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
	</ul>
</div>
</section>
<?php 
require '../../lib/footer/footer.php';
?>