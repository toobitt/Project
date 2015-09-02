<?php 
$login_title = array(
	'sina' => '新浪微博',
	'tengxun' => '腾讯微博',
	'phone' => '手机号'
);
$type = $_GET['type'];
$username = $_POST['username'];
$name = $login_title[$type];
$title = $name."登录";
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
<section class="main-wrap">
	<div class="login_form login_<?php echo $type ?>">
	<form method="post" action="">
		<div class="item pwd">
			<input type="password" name="password"  placeholder="请输入密码" value=""/>
		</div>
		<div class="item submit-btn">
			<input type="hidden" name="username" value="<?php echo $username?>">
			<a class="submit" href="../tab/index.php">登录</a>
		</div>
		<div class="item forget">
			<a href="#">?忘记密码</a>
		</div>
	</form>
	</div>
</section>
<?php 
require '../../lib/footer/footer.php';
?>