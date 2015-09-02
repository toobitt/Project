<?php 
$title = '个人中心';
$icons = array(
	left => array(
		icons => array(
			0 => 'back'
		)
	)
);
require '../../lib/head/head.php';
require '../../lib/unit/nav.php';
?>
<?php
$login_icon = array(
	icons => array(
		0 => 'sina',
		1 => 'tengxun',
		2 => 'phone'
	)
);
function getStyle($flag){
	switch( $flag ){
		case 'sina' : 
			echo '新浪微博';
			break;
		case 'tengxun' : 
			echo '腾讯微博';
			break;
		case 'phone' : 
			echo '手机号';
			break;
	}
}
?>
<link href="../../lib/css/page.css" type="text/css" rel="stylesheet" />
<link href="../../lib/css/personal.css" type="text/css" rel="stylesheet" />
<section class="main-wrap">
	<ul class="list login-type">
		<?php foreach ( $login_icon['icons'] as $k => $v ){?>
			<li><a class="anchor <?php echo $v?>" href="login_form.php?type=<?php echo $v ?>"><img src="../../lib/images/personal/icon_<?php echo $v?>.png" />
				<span>用<?php getStyle($v)?>登录</span></a></li>
		<?php }?>
	</ul>
</section>
<?php 
require '../../lib/footer/footer.php';
?>