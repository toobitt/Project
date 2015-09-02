<?php 
$title = '个人中心';
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
?>
<link href="../../lib/css/page.css" type="text/css" rel="stylesheet" />
<link href="../../lib/css/personal.css" type="text/css" rel="stylesheet" />
<section class="main-wrap personal edit_personal">
<div class="head">
	<div class="head-area">
		<p><img src="../../lib/images/images_pic2.jpg" title="头像"/></p>
	</div>
	<h3><a href="#">Grace</a></h3>
</div>
<div class="content">
	<ul class="list">
		<li class="nickname">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_avatar.png"><span>昵称</span></a>
			<div class="config"><input type="text" placeholder="请输入昵称" name="nickname" value="Grace" /></div>
		</li>
		<li class="email">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_mail.png"><span>邮箱</span></a>
			<div class="config"><input type="email" placeholder="请输入邮箱" name="email" value="loveeyond@163.com" /></div>
		</li>
	</ul>
	<ul class="list">
		<li class="original">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_Password.png"><span>原始密码</span></a>
			<div class="config"><input type="password" placeholder="请输入原始密码" name="original" value="" /></div>
		</li>
		<li class="modify">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_Password.png"><span>修改密码</span></a>
			<div class="config"><input type="password" placeholder="请输入修改密码" name="modify" value="" /></div>
		</li>
		<li class="repeat">
			<a class="anchor" href="#"><img src="../../lib/images/personal/icon_Password.png"><span>重复密码</span></a>
			<div class="config"><input type="password" placeholder="请再次输入密码" name="repeat" value="" /></div>
		</li>
	</ul>
</div>
</section>
<?php 
require '../../lib/footer/footer.php';
?>