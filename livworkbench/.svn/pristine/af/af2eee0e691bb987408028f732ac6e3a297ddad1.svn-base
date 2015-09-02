<?php 
function getStyle($flag){
	switch( $flag ){
		case 'sina' : 
			echo '新浪微博号';
			break;
		case 'tengxun' : 
			echo '腾讯微博号';
			break;
		case 'phone' : 
			echo '手机号';
			break;
	}
}
$login_title = array(
	'sina' => '新浪微博',
	'tengxun' => '腾讯微博',
	'phone' => '手机号'
);
$type = $_GET['type'];
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
		<form method="post" action="login_confirm.php?type=<?php echo $type ?>">
			<div class="item username">
				<input type="text" name="username"  placeholder="请输入你的<?php getStyle($type)?>" value=""/>
			</div>
			<div class="item submit-btn">
				<a class="submit" href="login_confirm.php?type=<?php echo $type ?>">下一步</a>
			</div>
		</form>
	</div>
</section>
<?php 
require '../../lib/footer/footer.php';
?>